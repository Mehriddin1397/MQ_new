<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ArtisanProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:user,artisan',
            'shop_name' => 'required_if:role,artisan|nullable|string|max:255',
            'specialty' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'artisan') {
            ArtisanProfile::create([
                'user_id' => $user->id,
                'shop_name' => $request->shop_name,
                'specialty' => $request->specialty,
                'status' => 'pending',
            ]);
        }

        Auth::login($user);

        return match ($user->role) {
            'artisan' => redirect()->route('artisan.pending'),
            default => redirect()->route('home'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
