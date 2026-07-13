<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    protected string $token;

    public function __construct()
    {
        $this->token = config('services.telegram.bot_token');
    }

    protected function api(string $method, array $params = [])
    {
        return Http::asForm()
            ->post("https://api.telegram.org/bot{$this->token}/{$method}", $params)
            ->json();
    }

    public function sendMessage(int|string $chatId, string $text, array $replyMarkup = null): void
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        if ($replyMarkup) {
            $params['reply_markup'] = json_encode($replyMarkup);
        }

        $this->api('sendMessage', $params);
    }

    public function answerCallbackQuery(string $callbackQueryId): void
    {
        $this->api('answerCallbackQuery', ['callback_query_id' => $callbackQueryId]);
    }

    public function setWebhook(string $url, string $secretToken): array
    {
        return $this->api('setWebhook', [
            'url' => $url,
            'secret_token' => $secretToken,
            'allowed_updates' => json_encode(['message', 'callback_query']),
        ]);
    }

    public function mainKeyboard(): array
    {
        $miniAppUrl = config('services.telegram.mini_app_url');
        $channelUrl = config('services.telegram.channel_url');

        return [
            'inline_keyboard' => [
                [['text' => '🚀 Platformani ochish', 'web_app' => ['url' => $miniAppUrl]]],
                [['text' => "📖 Platforma haqida", 'callback_data' => 'about']],
                [['text' => "☎️ Bog'lanish", 'callback_data' => 'contact']],
                [['text' => '📢 Yangiliklar', 'url' => $channelUrl]],
            ],
        ];
    }
}
