<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TelegramWebAppAuthController extends Controller
{
    public function login(Request $request, TelegramService $telegram)
    {
        if (Auth::check()) {
            return response()->json(['ok' => true, 'redirect' => $this->redirectFor(Auth::user())]);
        }

        $request->validate(['init_data' => 'required|string']);

        $data = $telegram->verifyWebAppInitData($request->input('init_data'));

        if (!$data || empty($data['user'])) {
            return response()->json(['ok' => false], 422);
        }

        $from = json_decode($data['user'], true);
        if (!is_array($from) || empty($from['id'])) {
            return response()->json(['ok' => false], 422);
        }

        $user = $telegram->findOrCreateUser($from);

        if ($user->status !== 'active') {
            return response()->json(['ok' => false, 'message' => 'Sizning hisobingiz bloklangan.'], 403);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json(['ok' => true, 'redirect' => $this->redirectFor($user)]);
    }

    protected function redirectFor(User $user): string
    {
        return match ($user->role) {
            'admin' => route('admin.dashboard'),
            'artisan' => route('artisan.dashboard'),
            default => route('home'),
        };
    }
}
