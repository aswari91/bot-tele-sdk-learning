<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class sendReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $chat_id;
    public $message;

    /**
     * Create a new job instance.
     */
    public function __construct($chat_id, $message)
    {
        $this->chat_id = $chat_id;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            \Telegram\Bot\Laravel\Facades\Telegram::sendMessage([
                'chat_id' => $this->chat_id,
                'text'    => $this->message,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to send Telegram message to chat_id {$this->chat_id}: " . $e->getMessage());
        }
    }
}
