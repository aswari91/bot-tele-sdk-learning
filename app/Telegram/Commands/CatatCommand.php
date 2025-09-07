<?php

namespace App\Telegram\Commands;

use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class CatatCommand extends Command
{
    protected string $name = 'catat';
    protected string $description = 'Meminta URL catat Tagihan CC';

    public function handle()
    {
        $fallbackUsername = $this->getUpdate()->getMessage()->from->username;
        $chatId          = $this->getUpdate()->getMessage()->chat->id;

        # Get the username argument if the user provides,
        # (optional) fallback to username from Update object as the default.
        $username = $this->argument(
            'username',
            $fallbackUsername
        );

        $terdaftar = !(\App\Models\User::where('tg_chat_id', $chatId)->count() === 0);

        if (!$terdaftar) {
            $this->replyWithMessage([
                'text' => "$username, Anda belum terdaftar. Silakan hubungi admin.",
            ]);
            return;
        }

        $signedUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'bayar-tagihan',
            now()->addMinutes(30),
        );

        $this->replyWithMessage([
            'text' => "Halo $username, silakan catat tagihan CC Anda di sini berlaku 30 menit.",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => 'Catat Tagihan CC', 'url' => $signedUrl],
                    ],
                ],
            ]),
        ]);
    }

    private function key(int $userId): string
    {
        return "tg:conv:{$userId}";
    }
}
