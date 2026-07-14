<?php

namespace App\Http\Controllers;

use App\Models\ArtisanProfile;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $ordersCount = $user->orders()->count();
        $wishlistCount = $user->wishlistItems()->count();
        $reviewsCount = $user->reviews()->count();
        $recentOrders = $user->orders()->with('items.product.images')->latest()->take(5)->get();

        return view('user.dashboard', compact('user', 'ordersCount', 'wishlistCount', 'reviewsCount', 'recentOrders'));
    }

    public function profile()
    {
        return view('user.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        $data = $request->only('name', 'phone', 'bio', 'address', 'city');

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profil yangilandi!');
    }

    public function reviews()
    {
        $reviews = auth()->user()->reviews()
            ->with('reviewable', 'images')
            ->latest()
            ->paginate(10);

        return view('user.reviews', compact('reviews'));
    }

    public function becomeArtisan(Request $request)
    {
        $user = auth()->user();

        if (!$user->isUser()) {
            return back()->with('error', "Bu so'rov faqat oddiy foydalanuvchilar uchun.");
        }

        $request->validate([
            'shop_name' => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
        ]);

        $user->update(['role' => 'artisan']);

        ArtisanProfile::create([
            'user_id' => $user->id,
            'shop_name' => $request->shop_name,
            'specialty' => $request->specialty,
            'status' => 'pending',
        ]);

        return redirect()->route('artisan.dashboard')
            ->with('success', "So'rovingiz yuborildi! Admin tasdiqlashini kuting.");
    }
}
