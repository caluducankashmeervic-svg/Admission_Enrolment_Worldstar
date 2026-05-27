<?php

namespace App\Http\Controllers;

use App\Models\SafeSpacePhoto;

class SafeSpaceController extends Controller
{
    public function index()
    {
        return view('admissions.safe-space', [
            'items' => SafeSpacePhoto::where('is_active', true)
                ->orderByDesc('event_at')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }
}
