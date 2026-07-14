<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = auth()->user();
            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors(['email' => 'Sizning hisobingiz bloklangan.']);
            }

            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'artisan' => redirect()->route('artisan.dashboard'),
                default => redirect()->route('home'),
            };
        }

        return back()->withErrors(['email' => 'Email yoki parol noto\'g\'ri.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
