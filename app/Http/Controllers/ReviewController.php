<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|max:2048',
        ]);

        if (!auth()->user()->hasPurchased($product)) {
            return back()->with('error', 'Faqat mahsulotni sotib olgan va qabul qilgan xaridorlar sharh qoldira oladi.');
        }

        $review = Review::create([
            'user_id' => auth()->id(),
            'reviewable_type' => Product::class,
            'reviewable_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                ReviewImage::create([
                    'review_id' => $review->id,
                    'image' => $path,
                ]);
            }
        }

        // Update product rating
        $avg = $product->reviews()->avg('rating');
        $count = $product->reviews()->count();
        $product->update(['rating' => round($avg, 2), 'total_reviews' => $count]);

        return back()->with('success', 'Sharh muvaffaqiyatli qo\'shildi!');
    }
}
