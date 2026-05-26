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
            <details class="group border border-slate-200 rounded-md p-3">
                <summary class="cursor-pointer font-medium">How do I check my status?</summary>
                <p class="mt-2 text-slate-600">Use your reference code on the
                    <a href="{{ route('applicant.status.form') }}" class="text-blue-600 hover:underline">Check Status</a> page.
                </p>
            </details>
        </div>
    </div>
</section>

{{-- ============================================================
     FOOTER — Illustrated architectural design
     · Pronounced upward-arching curved top edge (SVG)
     · Deep royal-blue gradient background
     · Sky gradient + blurred cloud shapes (top layer)
     · Warm brick / amber accent radials (mid layer)
     · Brick-mortar CSS grid texture (subtle overlay)
     · Decorative arched-window SVG shapes (bottom corners)
     · Roof-tile diagonal stripe hint (very faint)
     · Diagonal light rays (UniSC visual signature)
============================================================ --}}

{{-- ── Pronounced curved arch transition (white → deep blue) ── --}}
<div class="bg-white" style="line-height:0;">
    <svg class="w-full block" viewBox="0 0 1440 100"
         preserveAspectRatio="none" style="height:90px; display:block;">
        <defs>
            <linearGradient id="ftArchGrad" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%"   stop-color="#0c2196"/>
                <stop offset="100%" stop-color="#1432BE"/>
            </linearGradient>
        </defs>
        {{-- Main deep arch --}}
        <path d="M0,100 C360,8 1080,8 1440,100 L1440,100 L0,100 Z"
              fill="url(#ftArchGrad)"/>
        {{-- Secondary inner arch for depth --}}
        <path d="M0,100 C420,28 1020,28 1440,100 L1440,100 L0,100 Z"
              fill="rgba(255,255,255,0.04)"/>
    </svg>
</div>

<footer class="relative text-white overflow-hidden"
        style="background: linear-gradient(158deg,
                 #0b1f90 0%,
                 #0f2aaa 18%,
                 #1535cc 45%,
                 #1a3dd8 70%,
                 #1230b8 100%);">

    {{-- ═══ BACKGROUND LAYER 1: Sky gradient (top of footer) ═══ --}}
    <div class="absolute inset-0 pointer-events-none"
         style="background: linear-gradient(180deg,
                  rgba(30,70,210,0.55) 0%,
                  rgba(18,48,180,0.25) 35%,
                  transparent 65%);"></div>

    {{-- ═══ BACKGROUND LAYER 2: Blurred cloud shapes ═══ --}}
    <div class="absolute top-0 left-0 right-0 pointer-events-none overflow-hidden"
         style="height:200px; opacity:0.08;">
        <div style="position:absolute;top:18px; left:6%;  width:220px;height:60px; background:#fff; border-radius:50%; filter:blur(22px);"></div>
        <div style="position:absolute;top:8px;  left:13%; width:150px;height:42px; background:#fff; border-radius:50%; filter:blur(16px);"></div>
        <div style="position:absolute;top:35px; left:52%; width:240px;height:58px; background:#fff; border-radius:50%; filter:blur(24px);"></div>
        <div style="position:absolute;top:12px; left:63%; width:170px;height:46px; background:#fff; border-radius:50%; filter:blur(18px);"></div>
        <div style="position:absolute;top:28px; right:7%; width:195px;height:52px; background:#fff; border-radius:50%; filter:blur(20px);"></div>
        <div style="position:absolute;top:5px;  right:22%;width:120px;height:34px; background:#fff; border-radius:50%; filter:blur(14px);"></div>
    </div>

    {{-- ═══ BACKGROUND LAYER 3: Warm brick / amber accent radials ═══ --}}
    <div class="absolute inset-0 pointer-events-none"
         style="background:
                  radial-gradient(ellipse 55% 45% at 12% 85%, rgba(175,85,18,0.10) 0%, transparent 70%),
                  radial-gradient(ellipse 48% 38% at 88% 75%, rgba(205,145,35,0.08) 0%, transparent 65%),
                  radial-gradient(ellipse 30% 25% at 50% 95%, rgba(190,110,25,0.06) 0%, transparent 60%);"></div>

    {{-- ═══ BACKGROUND LAYER 4: Brick-mortar CSS texture ═══ --}}
    <div class="absolute inset-0 pointer-events-none"
         style="background-image:
                  repeating-linear-gradient(0deg,
                    transparent 0px, transparent 22px,
                    rgba(155,68,14,0.055) 22px, rgba(155,68,14,0.055) 23px),
                  repeating-linear-gradient(90deg,
                    transparent 0px, transparent 44px,
                    rgba(155,68,14,0.04)  44px, rgba(155,68,14,0.04)  45px),
                  repeating-linear-gradient(0deg,
                    transparent 0px, transparent 44px,
                    rgba(155,68,14,0.035) 44px, rgba(155,68,14,0.035) 45px);"></div>

    {{-- ═══ BACKGROUND LAYER 5: Roof-tile diagonal stripe hint ═══ --}}
    <div class="absolute top-0 left-0 right-0 pointer-events-none"
         style="height:80px; opacity:0.045;
                background: repeating-linear-gradient(135deg,
                  transparent 0px, transparent 10px,
                  rgba(200,160,55,1) 10px, rgba(200,160,55,1) 12px,
                  transparent 12px, transparent 22px);"></div>

    {{-- ═══ BACKGROUND LAYER 6: Diagonal light rays (UniSC signature) ═══ --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute" style="width:220%;height:220%;top:-50%;left:-20%;opacity:0.055;
                    background:repeating-linear-gradient(130deg,transparent 0%,transparent 46%,#fff 46%,#fff 53%);
                    background-size:280px 280px;"></div>
        <div class="absolute" style="width:180%;height:180%;top:-30%;left:15%;opacity:0.04;
                    background:repeating-linear-gradient(52deg,transparent 0%,transparent 41%,#fff 41%,#fff 47%);
                    background-size:340px 340px;"></div>
    </div>

    {{-- ═══ BACKGROUND LAYER 7: Arched-window decorative SVGs (corners) ═══ --}}
    <div class="absolute bottom-0 left-0 pointer-events-none" style="opacity:0.07;">
        <svg width="210" height="170" viewBox="0 0 210 170">
            <path d="M10,170 L10,80 Q10,38 52,38 Q94,38 94,80 L94,170 Z"
                  fill="rgba(205,155,75,1)" stroke="rgba(230,185,90,0.6)" stroke-width="1.5"/>
            <path d="M108,170 L108,92 Q108,56 147,56 Q186,56 186,92 L186,170 Z"
                  fill="rgba(205,155,75,1)" stroke="rgba(230,185,90,0.6)" stroke-width="1.5"/>
            {{-- Window-pane cross bars --}}
            <line x1="10" y1="105" x2="94" y2="105" stroke="rgba(230,185,90,0.4)" stroke-width="1"/>
            <line x1="52" y1="38"  x2="52" y2="170"  stroke="rgba(230,185,90,0.4)" stroke-width="1"/>
            <line x1="108" y1="120" x2="186" y2="120" stroke="rgba(230,185,90,0.4)" stroke-width="1"/>
            <line x1="147" y1="56"  x2="147" y2="170"  stroke="rgba(230,185,90,0.4)" stroke-width="1"/>
        </svg>
    </div>
    <div class="absolute bottom-0 right-0 pointer-events-none" style="opacity:0.065;">
        <svg width="190" height="155" viewBox="0 0 190 155">
            <path d="M10,155 L10,72 Q10,32 50,32 Q90,32 90,72 L90,155 Z"
                  fill="rgba(205,155,75,1)" stroke="rgba(230,185,90,0.5)" stroke-width="1.5"/>
            <path d="M102,155 L102,85 Q102,50 138,50 Q174,50 174,85 L174,155 Z"
                  fill="rgba(205,155,75,1)" stroke="rgba(230,185,90,0.5)" stroke-width="1.5"/>
            <line x1="10"  y1="98"  x2="90"  y2="98"  stroke="rgba(230,185,90,0.35)" stroke-width="1"/>
            <line x1="50"  y1="32"  x2="50"  y2="155"  stroke="rgba(230,185,90,0.35)" stroke-width="1"/>
            <line x1="102" y1="112" x2="174" y2="112" stroke="rgba(230,185,90,0.35)" stroke-width="1"/>
            <line x1="138" y1="50"  x2="138" y2="155"  stroke="rgba(230,185,90,0.35)" stroke-width="1"/>
        </svg>
    </div>

    {{-- ════════════════════════════════
         CONTENT
    ════════════════════════════════ --}}

    {{-- 3-column grid --}}
    <div class="relative max-w-7xl mx-auto px-8 pt-10 pb-12
                grid grid-cols-1 md:grid-cols-3 gap-14">

        {{-- Column 1: Be a Worldstar Lion --}}
        <div>
            <h4 class="font-bold uppercase tracking-widest text-sm text-white mb-6
                       border-b border-white/20 pb-3">
                Be a Worldstar Lion!
            </h4>
            <ul class="space-y-3 text-[15px] text-white/80">
                <li><a href="{{ route('register') }}"
                       class="font-bold text-white hover:text-[#FBBF24] transition-colors">Apply Now</a></li>
                <li><a href="{{ route('login') }}"   class="hover:text-[#FBBF24] transition-colors">Applicant Portal</a></li>
                <li><a href="#forms"                 class="hover:text-[#FBBF24] transition-colors">Application Forms</a></li>
                <li><a href="/#questions"            class="hover:text-[#FBBF24] transition-colors">Enrollment FAQ's</a></li>
                <li><a href="#contact"               class="hover:text-[#FBBF24] transition-colors">Contact Admission</a></li>
            </ul>
        </div>

        {{-- Column 2: Programs --}}
        <div>
            <h4 class="font-bold uppercase tracking-widest text-sm text-white mb-6
                       border-b border-white/20 pb-3">
                Programs
            </h4>
            <ul class="space-y-3 text-[15px] text-white/80">
                <li><a href="#shs"
                       class="font-bold text-white hover:text-[#FBBF24] transition-colors">Senior High School</a></li>
                <li><a href="#transferees" class="hover:text-[#FBBF24] transition-colors">Transferees</a></li>
                <li><a href="#tesda"       class="hover:text-[#FBBF24] transition-colors">TESDA Diploma Courses</a></li>
            </ul>
        </div>

        {{-- Column 3: Student Support --}}
        <div>
            <h4 class="font-bold uppercase tracking-widest text-sm text-white mb-6
                       border-b border-white/20 pb-3">
                Student Support
            </h4>
            <ul class="space-y-3 text-[15px] text-white/80">
                <li><a href="#scholarship"
                       class="font-bold text-white hover:text-[#FBBF24] transition-colors">Scholarship</a></li>
                <li><a href="#safespace" class="hover:text-[#FBBF24] transition-colors">Safe Space</a></li>
                <li><a href="#working"   class="hover:text-[#FBBF24] transition-colors">Apply as Working Student</a></li>
            </ul>
        </div>
    </div>

    {{-- Social icons --}}
    <div class="relative max-w-7xl mx-auto px-8 pb-10 flex items-center gap-4">
        @foreach ([
            ['Facebook',  'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z'],
            ['X',         'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.261 5.635 5.903-5.635zm-1.161 17.52h1.833L7.084 4.126H5.117z'],
            ['Instagram', 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z'],
            ['LinkedIn',  'M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z M4 6a2 2 0 100-4 2 2 0 000 4z'],
            ['YouTube',   'M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 00-1.95 1.96A29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.4a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58zM9.75 15.02V8.98L15.5 12l-5.75 3.02z'],
            ['TikTok',    'M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.32 6.32 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.75a4.85 4.85 0 01-1.01-.06z'],
        ] as [$name, $path])
            <a href="#" title="{{ $name }}"
               class="w-10 h-10 rounded-full border border-white/30 flex items-center justify-center
                      text-white/70 hover:text-white hover:border-white hover:bg-white/10 transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="{{ $path }}"/>
                </svg>
            </a>
        @endforeach
    </div>

    {{-- Sub-footer bar --}}
    <div class="relative border-t border-white/20">
        <div class="max-w-7xl mx-auto px-8 py-6
                    flex flex-col md:flex-row items-center justify-between gap-3
                    text-xs text-white/65">
            <p class="text-center md:text-left">
                P. Paredes St., Sampaloc, Manila, Philippines
                &nbsp;|&nbsp; (02) 8245-4201
                &nbsp;|&nbsp; info@worldstar.edu.ph
            </p>
            <p class="text-center md:text-right tracking-wide">
                &copy; 2024 WORLDSTAR COLLEGE of SCIENCE and TECHNOLOGY. All Rights Reserved.
            </p>
        </div>
    </div>

</footer>



</div>{{-- end full-bleed wrapper --}}
@endsection
