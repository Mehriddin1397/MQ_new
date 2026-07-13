<?php

namespace App\Http\Controllers;

use App\Models\TelegramLoginToken;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TelegramWebhookController extends Controller
{
    public function __invoke(Request $request, TelegramService $telegram)
    {
        if ($request->header('X-Telegram-Bot-Api-Secret-Token') !== config('services.telegram.webhook_secret')) {
            abort(403);
        }

        $update = $request->all();

        if (isset($update['message'])) {
            $this->handleMessage($update['message'], $telegram);
        } elseif (isset($update['callback_query'])) {
            $this->handleCallbackQuery($update['callback_query'], $telegram);
        }

        return response()->json(['ok' => true]);
    }

    protected function handleMessage(array $message, TelegramService $telegram): void
    {
        $chatId = $message['chat']['id'];
        $text = trim($message['text'] ?? '');

        if (!Str::startsWith($text, '/start')) {
            return;
        }

        $from = $message['from'];
        $user = $this->findOrCreateUser($from);

        $loginToken = trim(Str::after($text, '/start'));
        if ($loginToken !== '') {
            $token = TelegramLoginToken::where('token', $loginToken)
                ->where('status', 'pending')
                ->first();

            if ($token && !$token->isExpired()) {
                $token->update(['status' => 'confirmed', 'user_id' => $user->id]);
            }
        }

        $firstName = e($from['first_name'] ?? 'Foydalanuvchi');

        $telegram->sendMessage(
            $chatId,
            "👋 <b>Assalomu alaykum, {$firstName}!</b>\n\n" .
            "Bizning platformamizga xush kelibsiz! 🎉\n\n" .
            "✅ Siz muvaffaqiyatli ro'yxatdan o'tdingiz.\n" .
            "✅ Agar saytdan kelgan bo'lsangiz, saytga qaytib, avtomatik tizimga kirasiz.\n\n" .
            "Quyidagi tugmalardan birini tanlang 👇",
            $telegram->mainKeyboard()
        );
    }

    protected function handleCallbackQuery(array $query, TelegramService $telegram): void
    {
        $chatId = $query['message']['chat']['id'];
        $data = $query['data'] ?? '';

        if ($data === 'about') {
            $telegram->sendMessage(
                $chatId,
                "📖 <b>Platforma haqida</b>\n\n" .
                "Bizning platforma foydalanuvchilarga qulay va zamonaviy xizmatlarni " .
                "bir joyda taqdim etadi. Telegram Mini App orqali to'g'ridan-to'g'ri " .
                "botdan foydalanish imkoniyati mavjud.\n\n" .
                "Batafsil ma'lumot uchun \"🚀 Platformani ochish\" tugmasini bosing.",
                $telegram->mainKeyboard()
            );
        }

        if ($data === 'contact') {
            $phone = config('services.telegram.contact_phone');
            $username = config('services.telegram.contact_username');

            $telegram->sendMessage(
                $chatId,
                "☎️ <b>Biz bilan bog'lanish</b>\n\n" .
                "📱 Telefon: {$phone}\n" .
                "💬 Telegram: {$username}\n\n" .
                "Savollaringiz bo'lsa, biz bilan bemalol bog'laning!",
                $telegram->mainKeyboard()
            );
        }

        $telegram->answerCallbackQuery($query['id']);
    }

    protected function findOrCreateUser(array $from): User
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
}
