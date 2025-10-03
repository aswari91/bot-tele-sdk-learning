<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Tagihan;
use Livewire\Component;
use Filament\Support\RawJs;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Illuminate\Http\Request;
use App\Models\MetodePembayaran;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Filament\Schemas\Components\Text;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use App\Models\CreditCard;

class BayarTagihan extends Component implements HasSchemas, HasActions
{
    use InteractsWithSchemas, InteractsWithActions;

    public ?array $data = [];

    public $user;

    public function mount(Request $request): void
    {
        // if (! $request->hasValidSignature() || !$request->query('tg_chat_id')) {
        //     abort(401);
        // }
        $this->user = User::firstWhere('tg_chat_id', $request->query('tg_chat_id'));
        if (! $this->user) {
            abort(404);
        }
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bayar Tagihan')
                    ->schema([
                        Select::make('id_tagihan')
                            ->hintIconTooltip('Pilih tagihan yang ingin dibayar')
                            ->label('Pilih Tagihan CC')
                            ->reactive()
                            ->searchable()
                            ->options(
                                \App\Models\Tagihan::where('lunas', false)
                                    ->where('user_id', $this->user->id)
                                    ->get()
                                    ->mapWithKeys(function ($tagihan) {
                                        return [
                                            $tagihan->id => $tagihan->creditCard->nama_kartu,
                                        ];
                                    })
                                    ->toArray()
                            )
                            ->required(),
                        TextInput::make('metode_pembayaran')
                            ->label('Metode Pembayaran')
                            ->datalist(fn($get) => MetodePembayaran::pluck('metode_pembayaran')->toArray())
                            ->required()
                            ->reactive()
                            ->maxLength(255),
                        TextInput::make('jumlah_bayar')
                            ->prefix('Rp ')
                            ->reactive()
                            ->hintAction(Action::make('set_max')
                                ->label('Full Payment')
                                ->icon('heroicon-o-arrow-up')
                                ->action(function (callable $set, callable $get, $state) {
                                    $tagihan = $get('id_tagihan') ? Tagihan::find($get('id_tagihan')) : null;
                                    if ($tagihan) {
                                        $set('jumlah_bayar', number_format($tagihan->sisa_tagihan, 0, '.', ','));
                                    }
                                }))
                            ->afterStateUpdated(function (callable $set, $state) {
                                if (!$state) {
                                    $set('jumlah_bayar', '');
                                }
                            })
                            ->suffixAction(Action::make('clear')
                                ->icon('heroicon-o-x-circle')
                                ->size('sm')
                                ->action(function (callable $set) {
                                    $set('jumlah_bayar', '');
                                }))
                            ->mask(RawJs::make('$money($input)'))
                            ->required(),
                        DatePicker::make('tanggal_bayar')
                            ->default(now())
                            ->label('Tanggal Bayar')
                            ->required(),
                    ]),
                Section::make()
                    ->schema([
                        Text::make('Untuk memudahkan pembayaran Copy nomor kartu di bawah ini:')
                            ->weight(FontWeight::Bold)
                            ->color('neutral'),
                        Text::make('Copy nomor kartu disini')
                            ->copyable()
                            ->copyableState(function ($get) {
                                $cardNumber = CreditCard::whereHas('tagihans', function ($query) use ($get) {
                                    $query->where('id', $get('id_tagihan'));
                                })->value('card_number');
                                return str_replace(' ', '', $cardNumber) ?? '';
                            })
                            ->fontFamily(\Filament\Support\Enums\FontFamily::Mono)
                            ->size(TextSize::Small)
                            ->extraAttributes(['class' => 'py-0 my-0'])
                            ->color(\Filament\Support\Colors\Color::Indigo)
                            ->visible(fn($get) => !empty($get('id_tagihan'))),
                        Text::make('Copy jumlah bayar disini')
                            ->copyable()
                            ->copyableState(fn($get) => str_replace(['Rp', ' ', ','], '', $get('jumlah_bayar')) ?? '')
                            ->fontFamily(\Filament\Support\Enums\FontFamily::Mono)
                            ->size(TextSize::Small)
                            ->color(\Filament\Support\Colors\Color::Indigo)
                            ->extraAttributes(['class' => 'py-0 my-0'])
                            ->visible(fn($get) => !empty($get('jumlah_bayar'))),
                    ]),


            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $state = $this->form->getState();

        // Minimal validation
        if (empty($state['id_tagihan']) || empty($state['jumlah_bayar']) || empty($state['tanggal_bayar'])) {
            Notification::make()
                ->danger()
                ->title('Gagal')
                ->body('Silakan lengkapi semua kolom yang diperlukan.')
                ->send();

            return;
        }

        try {
            $tagihan = Tagihan::findOrFail($state['id_tagihan']);
            $jumlah = str_replace([',', 'Rp', ' '], '', $state['jumlah_bayar']);

            $tagihan->bayarTagihans()->create([
                'user_id' => $tagihan->user_id,
                'jumlah_bayar' => $jumlah,
                'tanggal_bayar' => $state['tanggal_bayar'],
                'metode_pembayaran' => $state['metode_pembayaran'],
            ]);

            $tagihan->sisa_tagihan = $tagihan->sisa_tagihan - $jumlah;
            $tagihan->tagihan_terbayar = $tagihan->tagihan_terbayar + $jumlah;
            if ($tagihan->sisa_tagihan <= 0) {
                $tagihan->lunas = true;
            }
            $tagihan->save();

            MetodePembayaran::firstOrCreate(
                ['metode_pembayaran' => $state['metode_pembayaran']]
            );

            Notification::make()
                ->success()
                ->title('Pembayaran Diterima')
                ->body('Pembayaran Anda telah diproses. Terima kasih.')
                ->send();

            // Opsional: reset form state
            $this->form->fill();
            $this->dispatch('created')->self();
            // refresh select options
            $this->reset('data.id_tagihan');
            $this->dispatch('$refresh');
        } catch (\Throwable $e) {
            Log::error('Pembayaran gagal: ' . $e->getMessage());

            Notification::make()
                ->danger()
                ->title('Kesalahan')
                ->body('Terjadi kesalahan saat memproses pembayaran.')
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.bayar-tagihan');
    }
}
