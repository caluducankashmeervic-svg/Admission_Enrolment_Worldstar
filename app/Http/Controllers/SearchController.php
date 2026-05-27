<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->input('q', ''));

        $courses = collect();
        $pages   = collect();

        if ($q !== '') {
            // Search active courses by name, department, or code
            $courses = Course::where('is_active', true)
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                          ->orWhere('department', 'like', "%{$q}%")
                          ->orWhere('code', 'like', "%{$q}%");
                })
                ->orderBy('name')
                ->get();

            // Static page matching
            $staticPages = [
                [
                    'title'    => 'About Our Story',
                    'url'      => route('about.story'),
                    'keywords' => 'story history about school background founding',
                ],
                [
                    'title'    => 'Philosophy',
                    'url'      => route('about.philosophy'),
                    'keywords' => 'philosophy belief commitment education',
                ],
                [
                    'title'    => 'Vision & Mission',
                    'url'      => route('about.vision-mission'),
                    'keywords' => 'vision mission goals objectives purpose',
                ],
                [
                    'title'    => 'Core Values',
                    'url'      => route('about.core-values'),
                    'keywords' => 'core values principles integrity excellence',
                ],
                [
                    'title'    => 'Apply / Pre-Registration',
                    'url'      => route('applicant.pre-register.choose'),
                    'keywords' => 'apply admission pre-registration enroll register application',
                ],
                [
                    'title'    => 'Login',
                    'url'      => route('login'),
                    'keywords' => 'login sign in account access portal',
                ],
            ];

            $qLower = strtolower($q);
            $pages  = collect($staticPages)->filter(function ($page) use ($qLower) {
                return str_contains(strtolower($page['title']), $qLower)
                    || str_contains(strtolower($page['keywords']), $qLower);
            })->values();
        }

        return view('search.results', compact('q', 'courses', 'pages'));
    }
}
