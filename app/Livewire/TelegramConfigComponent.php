<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasSort;

class TelegramConfigComponent extends Component implements HasForms
{
    use InteractsWithForms;
    use HasSort;

    public ?array $data = [];

    protected static int $sort = 0;

    public function mount(): void
    {
        $this->form->fill([
            'tg_chat_id' => auth()->user()->tg_chat_id,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Telegram')
                    ->aside()
                    ->description('Pengaturan untuk menghubungkan akun Telegram Anda dan menerima notifikasi.')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('tg_chat_id')
                            ->label('Telegram Chat ID')
                            ->numeric()
                            ->required()
                            ->helperText('Enter your Telegram Chat ID to receive notifications.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        auth()->user()->update($data);

        Notification::make()
            ->title('successfully saved')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.telegram-config-component');
    }
}
