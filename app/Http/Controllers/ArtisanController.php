<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;

class ArtisanController extends Controller
{
    public function index()
    {
        $artisans = User::where('role', 'artisan')
            ->whereHas('artisanProfile', fn($q) => $q->where('status', 'approved'))
            ->with('artisanProfile')
            ->withCount('products')
            ->paginate(12);

        return view('artisans.index', compact('artisans'));
    }

    public function show(User $artisan)
    {
        if (!$artisan->isArtisan())
            abort(404);

        $artisan->load('artisanProfile');

        $products = Product::where('user_id', $artisan->id)
            ->active()->inStock()
            ->with('images')
            ->latest()
            ->paginate(12);

        $reviews = $artisan->artisanProfile
            ->morphMany(\App\Models\Review::class, 'reviewable')
            ->with('user', 'images')
            ->latest()
            ->paginate(5);

        return view('artisans.show', compact('artisan', 'products', 'reviews'));
    }
}
