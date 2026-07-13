<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'avatar',
        'bio',
        'address',
        'city',
        'status',
        'telegram_id',
        'telegram_username',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isArtisan(): bool
    {
        return $this->role === 'artisan';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function artisanProfile()
    {
        return $this->hasOne(ArtisanProfile::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'user_id');
    }

    public function artisanConversations()
    {
        return $this->hasMany(Conversation::class, 'artisan_id');
    }

    public function allConversations()
    {
        return Conversation::where('user_id', $this->id)
            ->orWhere('artisan_id', $this->id);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff';
    }

    public function hasPurchased(Product $product): bool
    {
        return $this->orders()
            ->where('status', 'delivered')
            ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
            ->exists();
    }
}
