<?php

namespace App\Http\Controllers;

use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        return view('about.announcements', [
            'announcements' => Announcement::published()
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }
}