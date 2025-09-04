<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function webhook($bot_token)
    {
        $telegram = new \Telegram\Bot\Api($bot_token);
        $update = $telegram->commandsHandler(true);


        $update  = $telegram->getWebhookUpdate();
        $message = $update->getMessage();

        if (!$message) {
            return response('OK', 200);
        }

        $chatId = $message->getChat()->getId();
        $userId = $message->getFrom()->getId();
        $textRaw = $message->getText() ?? '';
        $text = strtolower(trim($textRaw));

        // helper state
        $key = "tg:conv:$userId";
        $ttl = now()->addMinutes(1); // masa berlaku state percakapan

        $state = Cache::get($key, [
            'step' => null,         // null | ask_name | ask_age
            'data' => ['name' => null],
        ]);

        // reset cepat
        if (in_array($text, ['/start', '/reset', 'reset'])) {
            Cache::forget($key);
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text'    => "hai nama kamu siapa?",
            ]);
            Cache::put($key, ['step' => 'ask_name', 'data' => ['name' => null]], $ttl);
            return response('OK', 200);
        }

        // mulai percakapan saat user ketik "hai"
        if ($text === 'hai' && $state['step'] === null) {
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text'    => "hai nama kamu siapa?",
            ]);
            Cache::put($key, ['step' => 'ask_name', 'data' => ['name' => null]], $ttl);
            return response('OK', 200);
        }

        // STEP: minta nama
        if ($state['step'] === 'ask_name') {
            // ambil nama dari teks asli (jangan lowercase)
            $name = trim($textRaw);
            // validasi sederhana nama (optional)
            if ($name === '' || mb_strlen($name) < 2) {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text'    => "Namanya terlalu pendek. Coba ketik lagi ya, kak 😊",
                ]);
                return response('OK', 200);
            }

            $state['data']['name'] = $name;
            $state['step'] = 'ask_age';
            Cache::put($key, $state, $ttl);

            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text'    => "Hai, kak {$name} umur kamu berapa?",
            ]);
            return response('OK', 200);
        }

        // STEP: minta umur (harus angka)
        if ($state['step'] === 'ask_age') {
            // hanya angka 1–3 digit (boleh sesuaikan)
            if (!preg_match('/^\d{1,3}$/', $text)) {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text'    => "Umur yang kamu masukan tidak benar, coba masukan kembali umur mu?",
                ]);
                return response('OK', 200);
            }

            $age = (int) $text;
            if ($age <= 0 || $age > 120) {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text'    => "Umur yang kamu masukan tidak benar, coba masukan kembali umur mu?",
                ]);
                return response('OK', 200);
            }

            $name = $state['data']['name'] ?? 'kak';
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text'    => "Kak {$name} umur mu sekarang {$age} tahun.",
            ]);

            // percakapan selesai → hapus state
            Cache::forget($key);
            return response('OK', 200);
        }

        // default (jika user ngomong di luar flow)
        $telegram->sendMessage([
            'chat_id' => $chatId,
            'text'    => "Ketik *hai* untuk memulai atau /reset untuk mengulang.",
            'parse_mode' => 'Markdown',
        ]);

        return response('OK', 200);
    }
}
