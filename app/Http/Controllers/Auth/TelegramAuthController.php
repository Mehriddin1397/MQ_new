<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TelegramLoginToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TelegramAuthController extends Controller
{
    public function redirect()
    {
        $loginToken = TelegramLoginToken::create([
            'token' => Str::random(40),
            'status' => 'pending',
            'expires_at' => now()->addMinutes(10),
        ]);

        $botUrl = 'https://telegram.me/' . config('services.telegram.bot_username') . '?start=' . $loginToken->token;

        return view('auth.telegram-pending', [
            'token' => $loginToken->token,
            'botUrl' => $botUrl,
        ]);
    }

    public function status(string $token)
    {
        $loginToken = TelegramLoginToken::where('token', $token)->first();

        if (!$loginToken || $loginToken->isExpired()) {
            return response()->json(['status' => 'expired']);
        }

        return response()->json(['status' => $loginToken->status]);
    }

    public function complete(Request $request, string $token)
    {
        $loginToken = TelegramLoginToken::where('token', $token)
            ->where('status', 'confirmed')
            ->first();

        if (!$loginToken || $loginToken->isExpired() || !$loginToken->user_id) {
            return response()->json(['ok' => false], 404);
        }

        $user = $loginToken->user;

        if ($user->status !== 'active') {
            return response()->json(['ok' => false, 'message' => 'Sizning hisobingiz bloklangan.'], 403);
        }

        Auth::login($user);
        $request->session()->regenerate();
        $loginToken->delete();

        $redirect = match ($user->role) {
            'admin' => route('admin.dashboard'),
            'artisan' => route('artisan.dashboard'),
            default => route('home'),
        };

        return response()->json(['ok' => true, 'redirect' => $redirect]);
    }
}
