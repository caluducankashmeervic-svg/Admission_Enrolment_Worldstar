@extends('layouts.app')
@section('title', 'Safe Space by Victory Church')

@section('content')
<div class="max-w-5xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-5 md:p-8 mt-4 md:mt-6">
    <div class="border-l-4 border-amber-400 pl-3 md:pl-4 mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-[#1D4ED8] tracking-wide">Safe Space by Victory Church</h1>
        <p class="text-sm text-slate-500 mt-1">A faith-based student care initiative focused on emotional support, guidance, and community healing.</p>
    </div>

    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 mb-6 text-sm text-slate-700 leading-relaxed">
        Safe Space by Victory Church provides students with a trusted environment to share struggles, ask for prayer, and receive mentoring.
        Through talks, group sessions, and counseling moments, the program encourages hope, mental and emotional wellness,
        and values-centered growth for every learner.
    </div>

    @if($items->isEmpty())
        <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 px-5 py-10 text-center">
            <p class="text-lg font-semibold text-slate-700">No Safe Space photos yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($items as $item)
                <article class="border border-slate-200 rounded-lg overflow-hidden bg-white">
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title ?: 'Safe Space event photo' }}" class="w-full h-52 object-cover">
                    <div class="px-4 py-3 space-y-1">
                        <p class="text-sm font-medium text-slate-700">{{ $item->title ?: 'Safe Space Event' }}</p>
                        @if($item->event_at)
                            <p class="text-xs text-slate-500">{{ $item->event_at->format('F d, Y g:i A') }}</p>
                        @else
                            <p class="text-xs text-slate-400">Date/time not specified</p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
