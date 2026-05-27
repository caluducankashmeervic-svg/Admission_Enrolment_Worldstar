@extends('layouts.app')
@section('title', $program['title'])

@section('content')
<div class="max-w-4xl mx-auto mt-6 px-4">

    {{-- Breadcrumb --}}
    <nav class="text-xs text-slate-400 mb-4 flex items-center gap-1.5">
        <a href="{{ route('home') }}" class="hover:text-[#1D4ED8]">Home</a>
        <span>/</span>
        <span class="text-slate-500">Academics</span>
        <span>/</span>
        <span class="text-slate-600 font-medium">{{ $program['category'] }}</span>
    </nav>

    {{-- Category badge --}}
    <span class="inline-block mb-4 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider
        @if(str_contains($program['category'], 'TESDA'))
            bg-amber-100 text-amber-800
        @elseif(str_contains($program['category'], 'Tech-Pro'))
            bg-emerald-100 text-emerald-800
        @else
            bg-blue-100 text-blue-800
        @endif">
        {{ $program['category'] }}
    </span>

    {{-- Main card --}}
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-8 mb-6">

        {{-- Title block --}}
        <div class="border-l-4 border-amber-400 pl-4 mb-6">
            <h1 class="text-3xl font-bold text-[#1D4ED8] tracking-wide leading-snug">
                {{ $program['title'] }}
            </h1>
            <p class="text-sm text-slate-500 mt-1">{{ $program['tagline'] }}</p>
        </div>

        {{-- Description --}}
        <div class="text-[15px] leading-relaxed text-slate-700 text-justify mb-8">
            <p>{{ $program['description'] }}</p>
        </div>

        {{-- Highlights --}}
        <div>
            <h2 class="text-base font-semibold text-slate-800 mb-3 uppercase tracking-wide text-xs text-[#1D4ED8]">
                Program Highlights
            </h2>
            <ul class="space-y-2">
                @foreach($program['highlights'] as $item)
                    <li class="flex items-start gap-3 text-sm text-slate-700">
                        <span class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full bg-blue-600 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        {{ $item }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- CTA --}}
    <div class="bg-[#1D4ED8] rounded-lg p-6 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="font-semibold text-base">Interested in this program?</p>
            <p class="text-sm text-blue-100 mt-0.5">Apply now and start your journey at Worldstar College.</p>
        </div>
        <a href="{{ route('applicant.pre-register.form') }}"
           class="shrink-0 inline-block px-5 py-2.5 bg-white text-[#1D4ED8] font-semibold text-sm rounded hover:bg-blue-50 transition">
            Apply Now
        </a>
    </div>

</div>
@endsection
