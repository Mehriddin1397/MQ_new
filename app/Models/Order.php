<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'discount_amount',
        'status',
        'payment_status',
        'payment_method',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber(): string
    {
        return 'MQ-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_amount, 0, '.', ' ') . ' so\'m';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="badge bg-warning">Kutilmoqda</span>',
            'confirmed' => '<span class="badge bg-info">Tasdiqlangan</span>',
            'processing' => '<span class="badge bg-primary">Jarayonda</span>',
            'shipped' => '<span class="badge bg-secondary">Yetkazilmoqda</span>',
            'delivered' => '<span class="badge bg-success">Yetkazildi</span>',
            'cancelled' => '<span class="badge bg-danger">Bekor qilingan</span>',
            'refunded' => '<span class="badge bg-dark">Qaytarilgan</span>',
            default => '<span class="badge bg-light text-dark">' . $this->status . '</span>',
        };
    }
}
