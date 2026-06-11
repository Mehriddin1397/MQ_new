<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()->featured()->inStock()
            ->with(['images', 'user', 'category'])
            ->latest()
            ->take(8)
            ->get();

        $newProducts = Product::active()->inStock()
            ->with(['images', 'user', 'category'])
            ->latest()
            ->take(12)
            ->get();

        $categories = Category::active()->parents()
            ->withCount('products')
            ->orderBy('sort_order')
            ->take(8)
            ->get();

        $topArtisans = User::where('role', 'artisan')
            ->whereHas('artisanProfile', fn($q) => $q->where('status', 'approved'))
            ->with('artisanProfile')
            ->withCount('products')
            ->orderByDesc('products_count')
            ->take(6)
            ->get();

        $discountedProducts = Product::active()->inStock()
            ->whereNotNull('discount_price')
            ->where('discount_price', '>', 0)
            ->with(['images', 'user', 'category'])
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact(
            'featuredProducts',
            'newProducts',
            'categories',
            'topArtisans',
            'discountedProducts'
        ));
    }
}
