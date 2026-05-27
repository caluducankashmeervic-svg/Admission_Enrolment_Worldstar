@extends('layouts.app')

@section('title', $q ? 'Search: ' . $q : 'Search')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">

    {{-- Search header --}}
    <h1 class="text-3xl font-bold text-slate-800 mb-2">Search</h1>

    {{-- Search form (re-query) --}}
    <form action="{{ route('search') }}" method="GET" class="flex items-center gap-3 mb-8">
        <div class="flex-1 flex items-center gap-3 border border-slate-300 rounded-full px-5 py-3 bg-white shadow-sm focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500">
            <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="q" value="{{ $q }}"
                   placeholder="Type a keyword and press Enter"
                   class="flex-1 text-slate-700 placeholder-slate-400 bg-transparent outline-none text-base">
        </div>
        <button type="submit"
                class="bg-blue-700 hover:bg-blue-800 text-white font-semibold px-6 py-3 rounded-full transition-colors">
            Search
        </button>
    </form>

    @if($q === '')
        {{-- Empty state --}}
        <p class="text-slate-500 text-base">Enter a keyword above to search programs, departments, and pages.</p>

    @elseif($courses->isEmpty() && $pages->isEmpty())
        {{-- No results --}}
        <div class="text-center py-16">
            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <p class="text-xl font-semibold text-slate-600 mb-1">No results for &ldquo;{{ $q }}&rdquo;</p>
            <p class="text-slate-400 text-sm">Try a different keyword, such as a program name or department.</p>
        </div>

    @else
        <p class="text-slate-500 text-sm mb-6">
            Showing results for <span class="font-semibold text-slate-700">&ldquo;{{ $q }}&rdquo;</span>
            &mdash; {{ $courses->count() + $pages->count() }} result(s) found
        </p>

        {{-- Programs / Courses --}}
        @if($courses->isNotEmpty())
        <section class="mb-10">
            <h2 class="text-lg font-bold text-slate-700 uppercase tracking-wider mb-4 border-b border-slate-200 pb-2">
                Programs &amp; Courses
            </h2>
            <ul class="space-y-3">
                @foreach($courses as $course)
                <li>
                    <a href="{{ route('academics.program', $course->code) }}"
                       class="flex items-start gap-3 p-4 rounded-xl border border-slate-200 bg-white hover:border-blue-400 hover:shadow-md transition group">
                        <span class="mt-0.5 inline-flex items-center justify-center w-9 h-9 rounded-full bg-blue-50 text-blue-700 shrink-0 group-hover:bg-blue-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0-6l6.16-3.422A12.083 12.083 0 0121 12c0 2.386-.764 4.585-2.037 6.337"/>
                            </svg>
                        </span>
                        <div>
                            <p class="font-semibold text-slate-800 group-hover:text-blue-700 transition">{{ $course->name }}</p>
                            <p class="text-sm text-slate-500">
                                {{ $course->code }}
                                @if($course->department) &bull; {{ $course->department }} @endif
                                @if($course->duration_years) &bull; {{ $course->duration_years }} yr(s) @endif
                            </p>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
        </section>
        @endif

        {{-- Pages --}}
        @if($pages->isNotEmpty())
        <section>
            <h2 class="text-lg font-bold text-slate-700 uppercase tracking-wider mb-4 border-b border-slate-200 pb-2">
                Pages
            </h2>
            <ul class="space-y-3">
                @foreach($pages as $page)
                <li>
                    <a href="{{ $page['url'] }}"
                       class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 bg-white hover:border-blue-400 hover:shadow-md transition group">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-slate-100 text-slate-500 shrink-0 group-hover:bg-blue-50 group-hover:text-blue-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                        <span class="font-medium text-slate-800 group-hover:text-blue-700 transition">{{ $page['title'] }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </section>
        @endif
    @endif

</div>
@endsection
