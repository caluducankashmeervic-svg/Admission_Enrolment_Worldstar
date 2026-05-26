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

{{-- ============== FOOTER (UniSC curved design + Worldstar content) ============== --}}

{{-- Curved arch transition: white background with a blue arch shape drawing the curve into the footer --}}
<div class="bg-white">
    <svg class="w-full block" viewBox="0 0 1440 80" preserveAspectRatio="none" style="height:72px;">
        <path d="M0,80 C400,5 1040,5 1440,80 L1440,80 L0,80 Z" fill="#1432BE"/>
    </svg>
</div>

<footer class="relative text-white overflow-hidden" style="background: linear-gradient(140deg, #1230b8 0%, #1a3dcc 25%, #2048e0 55%, #1635c2 100%);">

    {{-- Diagonal light-ray overlays (UniSC visual effect) --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute opacity-[0.07]"
             style="width:220%;height:220%;top:-50%;left:-20%;
                    background:repeating-linear-gradient(130deg,transparent 0%,transparent 46%,#fff 46%,#fff 54%);
                    background-size:260px 260px;"></div>
        <div class="absolute opacity-[0.05]"
             style="width:180%;height:180%;top:-30%;left:10%;
                    background:repeating-linear-gradient(50deg,transparent 0%,transparent 40%,#fff 40%,#fff 46%);
                    background-size:320px 320px;"></div>
    </div>

    {{-- ── Main 3-column grid ── --}}
    <div class="relative max-w-7xl mx-auto px-8 pt-10 pb-12 grid grid-cols-1 md:grid-cols-3 gap-14">

        {{-- Column 1: Be a Worldstar Lion --}}
        <div>
            <h4 class="font-bold uppercase tracking-widest text-sm text-white mb-6 border-b border-white/20 pb-3">
                Be a Worldstar Lion!
            </h4>
            <ul class="space-y-3 text-[15px] text-white/80">
                <li>
                    <a href="{{ route('register') }}"
                       class="font-bold text-white hover:text-[#FBBF24] transition-colors">
                        Apply Now
                    </a>
                </li>
                <li><a href="{{ route('login') }}"      class="hover:text-[#FBBF24] transition-colors">Applicant Portal</a></li>
                <li><a href="#forms"                    class="hover:text-[#FBBF24] transition-colors">Application Forms</a></li>
                <li><a href="/#questions"               class="hover:text-[#FBBF24] transition-colors">Enrollment FAQ's</a></li>
                <li><a href="#contact"                  class="hover:text-[#FBBF24] transition-colors">Contact Admission</a></li>
            </ul>
        </div>

        {{-- Column 2: Programs --}}
        <div>
            <h4 class="font-bold uppercase tracking-widest text-sm text-white mb-6 border-b border-white/20 pb-3">
                Programs
            </h4>
            <ul class="space-y-3 text-[15px] text-white/80">
                <li>
                    <a href="#shs"
                       class="font-bold text-white hover:text-[#FBBF24] transition-colors">
                        Senior High School
                    </a>
                </li>
                <li><a href="#transferees" class="hover:text-[#FBBF24] transition-colors">Transferees</a></li>
                <li><a href="#tesda"       class="hover:text-[#FBBF24] transition-colors">TESDA Diploma Courses</a></li>
            </ul>
        </div>

        {{-- Column 3: Student Support --}}
        <div>
            <h4 class="font-bold uppercase tracking-widest text-sm text-white mb-6 border-b border-white/20 pb-3">
                Student Support
            </h4>
            <ul class="space-y-3 text-[15px] text-white/80">
                <li>
                    <a href="#scholarship"
                       class="font-bold text-white hover:text-[#FBBF24] transition-colors">
                        Scholarship
                    </a>
                </li>
                <li><a href="#safespace" class="hover:text-[#FBBF24] transition-colors">Safe Space</a></li>
                <li><a href="#working"   class="hover:text-[#FBBF24] transition-colors">Apply as Working Student</a></li>
            </ul>
        </div>
    </div>

    {{-- ── Social icons ── --}}
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

    {{-- ── Sub-footer bar ── --}}
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
