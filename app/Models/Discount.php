<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_order_amount',
        'max_uses',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_active)
            return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses)
            return false;
        if ($this->starts_at && now()->lt($this->starts_at))
            return false;
        if ($this->expires_at && now()->gt($this->expires_at))
            return false;
        return true;
    }

    public function calculateDiscount(float $amount): float
    {
        if ($this->min_order_amount && $amount < $this->min_order_amount)
            return 0;
        return $this->type === 'percentage'
            ? $amount * ($this->value / 100)
            : min($this->value, $amount);
    }
}
