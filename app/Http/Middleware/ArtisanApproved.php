<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ArtisanApproved
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->isArtisan()) {
            $profile = auth()->user()->artisanProfile;
            if (!$profile || !$profile->isApproved()) {
                return redirect()->route('artisan.dashboard')
                    ->with('warning', 'Bu bo\'lim admin tasdiqlagandan so\'ng ochiladi. Hozircha do\'kon profilingizni sozlab qo\'yishingiz mumkin.');
            }
        }

        return $next($request);
    }
}
