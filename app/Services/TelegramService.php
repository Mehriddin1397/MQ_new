<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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

    /**
     * Verify Telegram WebApp initData signature per Telegram's login widget algorithm.
     * Returns the parsed field array on success, null if invalid/expired/unsigned.
     */
    public function verifyWebAppInitData(string $initData): ?array
    {
        parse_str($initData, $data);

        if (empty($data['hash'])) {
            return null;
        }

        $hash = $data['hash'];
        unset($data['hash']);

        ksort($data);

        $pairs = [];
        foreach ($data as $key => $value) {
            $pairs[] = "{$key}={$value}";
        }
        $dataCheckString = implode("\n", $pairs);

        $secretKey = hash_hmac('sha256', $this->token, 'WebAppData', true);
        $computedHash = hash_hmac('sha256', $dataCheckString, $secretKey);

        if (!hash_equals($computedHash, $hash)) {
            return null;
        }

        if (isset($data['auth_date']) && (time() - (int) $data['auth_date']) > 86400) {
            return null;
        }

        return $data;
    }

    public function findOrCreateUser(array $from): User
    {
        $telegramId = $from['id'];

        $user = User::where('telegram_id', $telegramId)->first();
        if ($user) {
            return $user;
        }

        $name = trim(($from['first_name'] ?? '') . ' ' . ($from['last_name'] ?? '')) ?: 'Telegram foydalanuvchi';

        return User::create([
            'name' => $name,
            'email' => 'tg' . $telegramId . '@telegram.mohirqollar.uz',
            'password' => Str::random(32),
            'role' => 'user',
            'status' => 'active',
            'telegram_id' => $telegramId,
            'telegram_username' => $from['username'] ?? null,
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
