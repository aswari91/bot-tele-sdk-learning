<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function webhook($bot_token)
    {
        $update = Telegram::commandsHandler(true);
        $message = $update->getMessage();

        $text   = trim((string)($message->getText() ?? ''));
        $chatId = $message->getChat()->getId();

        if ($text !== '/catat' && $text !== '/help') {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text'    => 'Ketik /catat untuk meminta URL catat Tagihan CC.',
            ]);
        }
        return response('OK', 200);
    }
}
