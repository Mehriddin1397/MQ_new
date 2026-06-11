<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        // Get products that have a discount price
        $products = Product::active()
            ->whereNotNull('discount_price')
            ->where('discount_price', '>', 0)
            ->with(['images', 'category', 'user.artisanProfile'])
            ->latest()
            ->paginate(12);

        return view('promotions.index', compact('products'));
    }
}
