<?php

namespace App\Http\Controllers;

use App\Models\HelpVideo;

class HelpController extends Controller
{
    public function index()
    {
        $videos = HelpVideo::latest()->get();

        return view('help.index', compact('videos'));
    }
}
