<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramLoginToken extends Model
{
    protected $fillable = [
        'token',
        'status',
        'user_id',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
