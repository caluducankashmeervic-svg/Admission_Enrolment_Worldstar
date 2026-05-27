@extends('layouts.app')
@section('title', 'Welcome')

@section('content')
{{-- Full-bleed: break out of the layout's max-w-7xl container so sections span the entire viewport width --}}
<div class="relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen -my-6">

{{-- ============== HERO (cinematic dark banner) ============== --}}
<section class="relative bg-black text-white overflow-hidden">
    <div class="relative h-[360px] md:h-[440px] flex">
        {{-- Decorative right-side imagery strip (gradient placeholders) --}}
        <div class="absolute inset-0 grid grid-cols-4">
            <div class="bg-gradient-to-br from-slate-800 via-slate-900 to-black"></div>
            <div class="bg-gradient-to-br from-slate-700 to-slate-900"></div>
            <div class="bg-gradient-to-br from-slate-600 to-slate-800"></div>
            <div class="bg-gradient-to-br from-blue-900 via-slate-800 to-black"></div>
        </div>
        {{-- Dark overlay on the left for text legibility --}}
        <div class="absolute inset-y-0 left-0 w-full md:w-1/2 bg-gradient-to-r from-black via-black/90 to-transparent"></div>

        {{-- Hero copy --}}
        <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-12 flex items-center w-full">
            <div class="max-w-md">
                <p class="text-amber-300 text-lg md:text-xl font-semibold"></p>
                <h1 class="text-amber-300 text-3xl md:text-4xl font-extrabold leading-tight mt-1">
                            Enroll <br>Now
                </h1>
                <p class="text-white/90 text-sm mt-3 font-semibold">
                    Enroll online at <br>Worldstar College
                </p>
                <a href="{{ route('register') }}"
                   class="inline-block mt-5 bg-amber-300 hover:bg-amber-400 text-slate-900 font-bold text-sm px-5 py-2 rounded">
                    Register now
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ============== INTRO ============== --}}
<section class="bg-white">
    <div class="max-w-3xl mx-auto px-6 py-10 text-center md:text-left">
        <h2 class="text-blue-700 text-xl font-bold">For a better tomorrow</h2>
        <p class="mt-3 text-slate-700 text-sm leading-relaxed">
          Worldstar College of Science & Technology (formerly Isabela Colleges of Science & Technology) has been providing 25 years of quality yet affordable education in Region 2
            <a href="#courses" class="text-blue-600 underline">future-focused degrees</a>, world-leading
            sustainability <a href="#" class="text-blue-600 underline">research</a> and 5-star teaching.*
        </p>
        <p class="mt-3 text-slate-900 text-sm font-semibold">
            In 2026 we invite you to discover the moments, research and people who have shaped Worldstar,
            and celebrate with us at our 30<sup>th</sup> anniversary events.
        </p>
        <p class="mt-3 text-[11px] text-slate-500">*Good Universities Guide 2026</p>
    </div>
</section>

{{-- ============== FEATURED BANNER (Explore campuses) ============== --}}
<section class="bg-white">
    <div class="max-w-3xl mx-auto px-6 pb-8">
        <div class="relative h-56 md:h-64 rounded-sm overflow-hidden bg-gradient-to-r from-slate-700 via-slate-500 to-slate-400 shadow">
            <div class="absolute inset-0 bg-black/30"></div>
            <div class="relative h-full flex items-center px-8">
                <div>
                    <p class="text-white font-extrabold uppercase tracking-wide leading-tight text-lg md:text-2xl">
                        Explore our<br>award-winning<br>campuses and<br>state-of-the-art<br>facilities
                    </p>
                    <a href="#courses"
                       class="inline-block mt-4 bg-amber-300 hover:bg-amber-400 text-slate-900 font-bold text-sm px-4 py-2 rounded">
                        BOOK A GUIDED TOUR
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============== THREE IMAGE CARDS ============== --}}
<section class="bg-white">
    <div class="max-w-3xl mx-auto px-6 pb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ([
            ['STUDY',    'from-blue-200 to-blue-400',    '#courses'],
            ['RESEARCH', 'from-slate-300 to-slate-500',  '#mission'],
            ['INDUSTRY', 'from-amber-200 to-rose-300',   '#values'],
        ] as [$label, $grad, $href])
            <a href="{{ $href }}" class="block border-2 border-blue-700 group">
                <div class="relative aspect-[4/3] bg-gradient-to-br {{ $grad }} overflow-hidden">
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition"></div>
                    <div class="absolute bottom-0 left-0 right-0 px-3 py-2 bg-white">
                        <span class="text-blue-700 font-bold text-sm tracking-wider">{{ $label }} ›</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>

{{-- ============== BLUE BUTTON ROW ============== --}}
<section class="bg-slate-100 py-4">
    <div class="max-w-3xl mx-auto px-6 flex flex-wrap gap-3 justify-center md:justify-start">
        @foreach ([
            ['Locations',     '#mission'],
            ['How to apply',  route('register')],
            ['News',          '#news'],
        ] as [$label, $href])
            <a href="{{ $href }}"
               class="border-2 border-blue-700 text-blue-700 font-semibold text-sm px-5 py-2 bg-white hover:bg-blue-50">
                {{ $label }}
            </a>
        @endforeach
    </div>
</section>

{{-- ============== STUDY AREAS ============== --}}
<section class="bg-white py-6">
    <div class="max-w-4xl mx-auto px-6">
        <h2 class="text-blue-700 font-bold text-lg border-b border-blue-200 pb-2 mb-2">Study areas</h2>
        @php
            $studyAreas = $courses->count() ? $courses->pluck('name')->all() : [
                'Arts and Humanities','Engineering','Psychology and Social Work',
                'Business and Commerce','Environmental Studies','Science',
                'Communication','Law and Criminology','Sport and Exercise Science',
                'Creative Industries','Medical and Health Sciences','Technology',
                'Design','Nursing and Midwifery','Pathways and Bridging Programs',
                'Education','Paramedicine',
            ];
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-8">
            @foreach ($studyAreas as $area)
                <a href="#courses"
                   class="flex items-center gap-2 text-blue-700 text-sm py-2 border-b border-dotted border-slate-300 hover:text-blue-900">
                    <span class="text-amber-500">›</span> {{ $area }}
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============== FIND A PROGRAM BAR ============== --}}
<section class="bg-white pb-4">
    <div class="max-w-4xl mx-auto px-6">
        <form action="{{ route('register') }}" method="GET"
              class="bg-blue-800 text-white flex flex-wrap items-center gap-3 p-4 rounded-sm">
            <span class="font-bold text-amber-300 uppercase tracking-wide text-sm">Find a program</span>
            <select name="location" class="flex-1 min-w-[160px] text-slate-700 text-sm px-3 py-2 rounded-sm">
                <option>Study location</option>
                <option>Main Campus</option>
                <option>Online</option>
            </select>
            <select name="area" class="flex-1 min-w-[160px] text-slate-700 text-sm px-3 py-2 rounded-sm">
                <option>Study area</option>
                @foreach ($courses as $course)
                    <option>{{ $course->name }}</option>
                @endforeach
            </select>
            <button type="submit"
                    class="bg-amber-300 hover:bg-amber-400 text-slate-900 font-bold text-sm px-4 py-2 rounded-sm">
                GO
            </button>
        </form>
    </div>
</section>

{{-- ============== THREE INFO CARDS (light blue bg) ============== --}}
<section class="bg-slate-100 py-6">
    <div class="max-w-4xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach ([
            
        ] as [$title, $body, $grad])
            <article class="bg-white border-2 border-blue-700">
                <div class="aspect-[16/10] bg-gradient-to-br {{ $grad }}"></div>
                <div class="p-4">
                    <h3 class="text-blue-700 font-bold leading-snug">{{ $title }}</h3>
                    <p class="mt-2 text-xs text-slate-600 leading-relaxed">{{ $body }}</p>
                </div>
            </article>
        @endforeach
    </div>
</section>

{{-- ============== FAQ (kept for /#questions anchor) ============== --}}
<section id="questions" class="bg-white pb-6">
    <div class="max-w-4xl mx-auto px-6">
        <h3 class="font-bold text-slate-900 text-lg mb-4">Frequently Asked Questions</h3>
        <div class="space-y-3 text-sm text-slate-700">
            <details class="group border border-slate-200 rounded-md p-3">
                <summary class="cursor-pointer font-medium">Who can apply?</summary>
                <p class="mt-2 text-slate-600">Senior-high-school graduates and transferees are welcome to apply online.</p>
            </details>
            <details class="group border border-slate-200 rounded-md p-3">
                <summary class="cursor-pointer font-medium">Is there an entrance exam?</summary>
                <p class="mt-2 text-slate-600">Yes. After pre-registration, you'll receive a schedule for the entrance exam.</p>
            </details>

        </div>
    </div>
</section>

{{-- ============================================================
     FOOTER — Sunburst (UniSC) background + USC 3-column layout
     · Pronounced upward-arching SVG curved top edge
     · CSS conic-gradient sunburst from bottom-left corner
       (alternating deep navy #0b1c96 / royal blue #1a38d6)
     · Top-edge darkening overlay for smooth arch blend
     · Origin radial vignette at bottom-left source point
     · Col 1: Logo + School name + Social icons (USC style)
     · Col 2: Quick Links — serif heading + uppercase links
     · Col 3: Contact Details — serif heading + contact text
     · Sub-footer: thin separator + centred copyright
============================================================ --}}

{{-- ── Pronounced curved arch transition (white → deep blue) ── --}}


{{-- ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
     FOOTER ELEMENT
     Background: CSS conic-gradient sunburst radiating
     from the bottom-left corner (0% 100%), sweeping
     0° (up) → 90° (right), covering the full rectangle.
     15 alternating bands × 6° = 90° total sweep.
░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ --}}
<footer class="relative text-white overflow-hidden"
        style="background: conic-gradient(
                 from 0deg at 0% 100%,
                 #1a38d6  0deg   6deg,
                 #0b1c96  6deg  12deg,
                 #1a38d6 12deg  18deg,
                 #0b1c96 18deg  24deg,
                 #1a38d6 24deg  30deg,
                 #0b1c96 30deg  36deg,
                 #1a38d6 36deg  42deg,
                 #0b1c96 42deg  48deg,
                 #1a38d6 48deg  54deg,
                 #0b1c96 54deg  60deg,
                 #1a38d6 60deg  66deg,
                 #0b1c96 66deg  72deg,
                 #1a38d6 72deg  78deg,
                 #0b1c96 78deg  84deg,
                 #1a38d6 84deg  90deg,
                 #0b1c96 90deg 360deg);">

    {{-- Radial vignette at bottom-left origin (ray convergence point) --}}
    <div class="absolute inset-0 pointer-events-none"
         style="background: radial-gradient(ellipse 55% 65% at 0% 100%,
                  rgba(5,10,70,0.45) 0%,
                  transparent 65%);"></div>

    {{-- ════════════════════════════════════════
         CONTENT  —  3-column USC-style layout
    ════════════════════════════════════════ --}}
    <div class="relative max-w-7xl mx-auto px-8 md:px-12
                pt-14 pb-12
                grid grid-cols-1 md:grid-cols-3 gap-12 md:gap-16">

        {{-- ── COLUMN 1: Branding & Social Icons ── --}}
        <div class="flex flex-col items-start gap-7">

            {{-- Circular logo + school name --}}
            <div class="flex flex-col items-center gap-3 self-start">
                <img src="{{ asset('images/logo.png') }}"
                     alt="Worldstar College Logo"
                     class="w-28 h-28 object-contain">

                <div class="text-center">
                    <p class="text-white font-bold tracking-[0.16em] text-[11px]
                               uppercase leading-snug">
                        WORLDSTAR COLLEGE
                    </p>
                    <p class="text-white/60 text-[10px] tracking-wider italic mt-0.5"
                       style="font-family: Georgia, 'Times New Roman', serif;">
                        of Science and Technology
                    </p>
                </div>
            </div>

            {{-- Social icons row — Facebook only --}}
            <div class="flex items-center gap-3">
                <a href="#" title="Facebook"
                   class="w-10 h-10 rounded-full border border-white
                          flex items-center justify-center text-white
                          hover:bg-white hover:text-blue-900
                          transition-colors duration-200">
                    <svg class="w-[15px] h-[15px]" fill="currentColor"
                         viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- ── COLUMN 2: Quick Links ── --}}
        <div>
            <h3 class="text-white mb-7"
                style="font-family: Georgia, 'Times New Roman', serif;
                       font-size: 1.5rem;
                       font-weight: 400;
                       letter-spacing: 0.01em;">
                Quick Links
            </h3>
            <ul class="space-y-[18px]">
                @foreach ([
                    ['About',        '#about'],
                    ['Academics',    '#academics'],
                    ['Admission',    route('register')],
                    ['Student Life', '#student-life'],
                    ['Contact',      '#contact'],
                ] as [$label, $href])
                    <li>
                        <a href="{{ $href }}"
                           class="text-[11px] tracking-[0.22em] uppercase text-white/80
                                  hover:text-white hover:tracking-[0.28em]
                                  transition-all duration-200"
                           style="font-family: 'Helvetica Neue', Arial, sans-serif;
                                  font-weight: 300;">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- ── COLUMN 3: Contact Details ── --}}
        <div>
            <h3 class="text-white mb-7"
                style="font-family: Georgia, 'Times New Roman', serif;
                       font-size: 1.5rem;
                       font-weight: 400;
                       letter-spacing: 0.01em;">
                Contact Details
            </h3>
            <div class="space-y-5"
                 style="font-family: 'Helvetica Neue', Arial, sans-serif;">

                <div>
                    <p class="text-[10px] uppercase tracking-widest text-white/50 mb-1">
                        Trunkline
                    </p>
                    <p class="text-[14px] leading-relaxed text-white/85">
                        (02) 8245-4201 &nbsp;|&nbsp; (02) 8245-4202
                    </p>
                </div>

                <div>
                    <p class="text-[10px] uppercase tracking-widest text-white/50 mb-1">
                        Email Address
                    </p>
                    <p class="text-[14px] leading-relaxed text-white/85">
                        info@worldstar.edu.ph
                    </p>
                </div>

                <div>
                    <p class="text-[10px] uppercase tracking-widest text-white/50 mb-1">
                        Address
                    </p>
                    <p class="text-[14px] leading-relaxed text-white/85">
                        P. Paredes St., Sampaloc,<br>
                        Manila, Philippines 1008
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Sub-footer ── --}}
    <div class="relative max-w-7xl mx-auto px-8 md:px-12">
        <hr class="border-white/20">
        <p class="py-6 text-center text-xs text-white/60"
           style="font-family: 'Helvetica Neue', Arial, sans-serif; font-weight: 300;">
            &copy; Copyright 2025 &ndash; 2026 &nbsp;|&nbsp; Privacy Notice
        </p>
    </div>

</footer>



</div>{{-- end full-bleed wrapper --}}
@endsection
