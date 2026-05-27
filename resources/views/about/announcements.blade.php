@extends('layouts.app')
@section('title', 'Announcements')

@section('content')
<div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-5 md:p-8 mt-4 md:mt-6">
    <div class="border-l-4 border-amber-400 pl-3 md:pl-4 mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-[#1D4ED8] tracking-wide">Announcements</h1>
        <p class="text-sm text-slate-500 mt-1">School announcements and official updates from the administration.</p>
    </div>

    @forelse ($announcements as $announcement)
        <article class="border border-slate-200 rounded-lg p-5 mb-4 last:mb-0">
            <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
                <h2 class="text-lg md:text-xl font-semibold text-slate-900">{{ $announcement->title }}</h2>
                <p class="text-xs uppercase tracking-wider text-slate-500">
                    {{ optional($announcement->published_at ?? $announcement->created_at)->format('F d, Y g:i A') }}
                </p>
            </div>
            <div class="mt-3 text-sm md:text-[15px] leading-relaxed text-slate-700 whitespace-pre-line text-justify">{{ $announcement->body }}</div>
        </article>
    @empty
        <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center">
            <p class="text-lg font-semibold text-slate-700">No announcements yet.</p>
            <p class="text-sm text-slate-500 mt-2">There are currently no announcements posted by the admin.</p>
        </div>
    @endforelse
</div>
@endsection