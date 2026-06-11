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
                return redirect()->route('artisan.pending');
            }
        }

        return $next($request);
    }
}
