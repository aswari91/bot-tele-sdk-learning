<?php

namespace App\Filament\Resources\Tagihans\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Tagihan;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Models\MetodePembayaran;
use Filament\Actions\CreateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Tagihans\TagihanResource;
use Filament\Resources\RelationManagers\RelationManager;

class BayarTagihansRelationManager extends RelationManager
{
    protected static string $relationship = 'bayarTagihans';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->label('Metode Pembayaran'),
                Tables\Columns\TextColumn::make('jumlah_bayar')
                    ->label('Jumlah Bayar')
                    ->numeric(),
                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                // ...
            ])
            ->defaultSort('created_at', direction: 'desc')
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        $data['jumlah_bayar'] = str_replace([',', 'Rp', ' '], '', $data['jumlah_bayar']);
                        MetodePembayaran::firstOrCreate(
                            ['metode_pembayaran' => $data['metode_pembayaran']]
                        );
                        return $data;
                    })
                    ->after(function ($record) {
                        $tagihan = Tagihan::where('id', $record->tagihan_id)->first();
                        $tagihan->increment('tagihan_terbayar', $record->jumlah_bayar);
                        $tagihan->decrement('sisa_tagihan', $record->jumlah_bayar);
                        if ($tagihan->sisa_tagihan <= 0) {
                            $tagihan->update(['lunas' => true]);
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(function ($records) {
                            $tagihanId = $records->first()->tagihan_id;
                            $totalPembayaran = $records->sum('jumlah_bayar');
                            $tagihan = Tagihan::where('id', $tagihanId)->first();
                            $tagihan->decrement('tagihan_terbayar', $totalPembayaran);
                            $tagihan->increment('sisa_tagihan', $totalPembayaran);
                            if ($tagihan->sisa_tagihan > 0) {
                                $tagihan->update(['lunas' => false]);
                            }
                        }),
                ]),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->datalist(fn($get) => MetodePembayaran::pluck('metode_pembayaran')->toArray())
                    ->required()
                    ->reactive()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jumlah_bayar')
                    ->prefix('Rp ')
                    ->reactive()
                    ->hintAction(Action::make('set_max')
                        ->label('Full Payment')
                        ->action(function (callable $set, callable $get, $state) {
                            $tagihan = $this->ownerRecord;
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
                Forms\Components\DatePicker::make('tanggal_bayar')
                    ->default(now())
                    ->label('Tanggal Bayar')
                    ->required(),
            ]);
    }
}
