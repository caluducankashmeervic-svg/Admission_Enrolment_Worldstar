<?php

namespace App\Http\Controllers;

use App\Models\Accreditation;

class AccreditationController extends Controller
{
    public function index()
    {
        return view('about.accreditation-recognition', [
            'items' => Accreditation::where('is_active', true)
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }
}
