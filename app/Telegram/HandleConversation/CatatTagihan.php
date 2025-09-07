<?php

namespace App\Telegram\HandleConversation;

use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Laravel\Facades\Telegram;

class CatatTagihan
{
    public function handle($userId, $chatId, $text)
    {
        $state = Cache::get("tg:conv:{$userId}");

        if (!$state) {
            return;
        }

        // Proses pencatatan tagihan
        $this->prosesTagihan($chatId, $text);

        // Hapus state setelah selesai
        Cache::forget("tg:conv:{$userId}");
    }

    private function prosesTagihan($chatId, $text)
    {
        // Logika untuk mencatat tagihan
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text'    => "Tagihan Anda telah dicatat: {$text}",
        ]);
    }
}
