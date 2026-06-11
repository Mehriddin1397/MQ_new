<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['user_id', 'artisan_id', 'last_message_at'];

    protected function casts(): array
    {
        return ['last_message_at' => 'datetime'];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function artisan()
    {
        return $this->belongsTo(User::class, 'artisan_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function unreadMessagesFor(User $user)
    {
        return $this->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at');
    }

    public function getOtherUser(User $currentUser): User
    {
        return $currentUser->id === $this->user_id ? $this->artisan : $this->user;
    }
}
