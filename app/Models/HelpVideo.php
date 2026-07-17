<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'youtube_link',
    ];

    public function getYoutubeEmbedUrlAttribute(): string
    {
        $link = $this->youtube_link;
        $videoId = null;

        if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([A-Za-z0-9_-]+)~', $link, $matches)) {
            $videoId = $matches[1];
        }

        return $videoId ? 'https://www.youtube.com/embed/' . $videoId : $link;
    }
}
