<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['conversation_id', 'sender_id', 'body', 'type', 'attachment', 'read_at'];

    protected $appends = ['attachment_url'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment ? asset('storage/' . $this->attachment) : null;
    }
}
