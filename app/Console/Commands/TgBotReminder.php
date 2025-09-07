<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TgBotReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tg:bot-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder to Telegram bot users to pay their bills';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = \App\Models\User::whereNotNull('tg_chat_id')->get();
        foreach ($users as $user) {
            $now = now();
            $oneDay = now()->addDays(1);

            $billNow = \App\Models\Tagihan::where('user_id', $user->id)
                ->where('lunas', false)
                ->whereDate('jatuh_tempo', $now)
                ->orderBy('jatuh_tempo')
                ->with('creditCard')
                ->get();

            $billTomorrow = \App\Models\Tagihan::where('user_id', $user->id)
                ->where('lunas', false)
                ->whereDate('jatuh_tempo', $oneDay)
                ->orderBy('jatuh_tempo')
                ->with('creditCard')
                ->get();


            $overdue = \App\Models\Tagihan::where('user_id', $user->id)
                ->where('lunas', false)
                ->whereBeforeToday('jatuh_tempo')
                ->orderBy('jatuh_tempo', 'asc') // paling lama terlewat dulu
                ->with('creditCard')
                ->get();

            if ($billTomorrow->isEmpty() && $overdue->isEmpty()) {
                continue;
            }

            $message = "Halo " . ($user->name ?? 'Pengguna') . ",\n\n";
            if (!$billNow->isEmpty()) {
                $message .= "Berikut adalah tagihan Anda yang jatuh tempo hari ini:\n\n";
                foreach ($billNow as $t) {
                    $message .= "- CC info : " . $t->creditCard->card_name . "\n";
                    $message .= "  Jumlah tagihan: Rp " . number_format($t->total_tagihan, 0, ',', '.') . "\n";
                    $message .= "  Tagihan terbayar: Rp " . number_format($t->tagihan_terbayar, 0, ',', '.') . "\n";
                    $message .= "  Sisa tagihan: Rp " . number_format($t->sisa_tagihan, 0, ',', '.') . "\n";
                    $message .= "  Jatuh tempo: " . $t->jatuh_tempo->format('d-m-Y') . "\n\n";
                }
                $message .= "-----------------------------------------\n";
            }

            if (!$billTomorrow->isEmpty()) {
                $message .= "Berikut adalah tagihan Anda yang akan jatuh tempo besok:\n\n";
                foreach ($billTomorrow as $t) {
                    $message .= "- CC info : " . $t->creditCard->card_name . "\n";
                    $message .= "  Jumlah tagihan: Rp " . number_format($t->total_tagihan, 0, ',', '.') . "\n";
                    $message .= "  Tagihan terbayar: Rp " . number_format($t->tagihan_terbayar, 0, ',', '.') . "\n";
                    $message .= "  Sisa tagihan: Rp " . number_format($t->sisa_tagihan, 0, ',', '.') . "\n";
                    $message .= "  Jatuh tempo: " . $t->jatuh_tempo->format('d-m-Y') . "\n\n";
                }
                $message .= "-----------------------------------------\n";
            }

            if (!$overdue->isEmpty()) {
                $message .= "Berikut adalah tagihan Anda yang sudah jatuh tempo:\n\n";
                foreach ($overdue as $t) {
                    $message .= "- CC info : " . $t->creditCard->card_name . "\n";
                    $message .= "  Jumlah tagihan: Rp " . number_format($t->total_tagihan, 0, ',', '.') . "\n";
                    $message .= "  Tagihan terbayar: Rp " . number_format($t->tagihan_terbayar, 0, ',', '.') . "\n";
                    $message .= "  Sisa tagihan: Rp " . number_format($t->sisa_tagihan, 0, ',', '.') . "\n";
                    $message .= "  Jatuh tempo: " . $t->jatuh_tempo->format('d-m-Y') . "\n\n";
                }
                $message .= "-----------------------------------------\n";
            }
            $message .= "Silakan lakukan pembayaran sebelum tanggal jatuh tempo untuk menghindari denda dan ketik /catat untuk mencatat pembayaran Anda.\n";
            $message .= "~Terima kasih.";

            $job = new \App\Jobs\sendReminder($user->tg_chat_id, $message);
            dispatch($job);
        }
    }
}
