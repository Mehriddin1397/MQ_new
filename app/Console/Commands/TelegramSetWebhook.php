<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class TelegramSetWebhook extends Command
{
    protected $signature = 'app:telegram-set-webhook';

    protected $description = 'Register the Telegram bot webhook pointing at APP_URL/telegram/webhook';

    public function handle(TelegramService $telegram)
    {
        $url = rtrim(config('app.url'), '/') . '/telegram/webhook';
        $secret = config('services.telegram.webhook_secret');

        $result = $telegram->setWebhook($url, $secret);

        if ($result['ok'] ?? false) {
            $this->info("Webhook set to: {$url}");
        } else {
            $this->error('Failed: ' . json_encode($result));
        }
    }
}
