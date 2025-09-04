<?php

namespace App\Filament\Resources\TgConfigs\Pages;

use Telegram\Bot\Api;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TgConfigs\TgConfigResource;

class ListTgConfigs extends ListRecords
{
    protected static string $resource = TgConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('Set Webhook')
                ->visible(fn() => static::$resource::getEloquentQuery()
                    ->where('key', 'bot_token')
                    ->where('value', '!=', null)
                    ->count() > 0)
                ->icon('heroicon-o-link')
                ->action('setWebhook')
                ->color('success'),
        ];
    }

    public function setWebhook()
    {
        $bot_token = static::$resource::getEloquentQuery()
            ->where('key', 'bot_token')
            ->where('value', '!=', null)
            ->first()->value;

        $telegram = new Api($bot_token);
        $base_url = config('app.url') ?? url('/');

        $webhook_url =  $base_url . '/api/' . $bot_token . '/webhook';
        // $webhook_url = 'https://example.com' . '/api/' . $bot_token . '/webhook';

        try {
            $telegram->setWebhook(['url' => $webhook_url]);
            $telegram->getWebhookInfo();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error: ' . $e->getMessage())
                ->danger()
                ->send();
            return;
        }
        Notification::make()
            ->title('Setting Telegram Webhook...')
            ->success()
            ->send();
    }
}
