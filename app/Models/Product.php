<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'discount_price',
        'quantity',
        'sku',
        'is_active',
        'is_featured',
        'rating',
        'total_reviews',
        'views_count',
        'sales_count',
        'specifications',
        'tags',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'rating' => 'decimal:2',
            'specifications' => 'array',
            'tags' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function artisan()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->discount_price ?? $this->price;
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if (!$this->discount_price || $this->discount_price >= $this->price)
            return null;
        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $img = $this->primaryImage ?? $this->images->first();
        return $img ? asset('storage/' . $img->image) : asset('images/no-image.png');
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->effective_price, 0, '.', ' ') . ' so\'m';
    }
}
