<?php

namespace App\Http\Controllers;

class SafeSpaceController extends Controller
{
    public function index()
    {
        $images = [
            'safe space.jpg',
            'safe space 2.jpg',
            'safe space 3.jpg',
            'safe space 4.jpg',
            'safe space 5.jpg',
            'safe space 6.jpg',
        ];

        $items = collect($images)->map(function ($file, $index) {
            return [
                'title' => 'Safe Space Event Photo ' . ($index + 1),
                'image_url' => asset('images/' . $file),
            ];
        });

        return view('admissions.safe-space', [
            'items' => $items,
        ]);
    }
}
