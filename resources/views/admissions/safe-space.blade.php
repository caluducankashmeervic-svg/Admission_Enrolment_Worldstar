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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($items as $item)
                <a href="{{ $item['image_url'] }}" target="_blank" rel="noopener"
                   class="group block relative overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm hover:shadow-xl transition-shadow duration-300">
                    <div class="aspect-[4/3] overflow-hidden">
                        <img src="{{ $item['image_url'] }}"
                             alt="{{ $item['title'] ?: 'Safe Space event photo' }}"
                             class="w-full h-full object-cover transform transition-transform duration-500 ease-out group-hover:scale-110">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute bottom-0 left-0 right-0 px-4 py-3 text-white opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition duration-300">
                        <p class="text-xs uppercase tracking-wider font-semibold">Safe Space</p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
