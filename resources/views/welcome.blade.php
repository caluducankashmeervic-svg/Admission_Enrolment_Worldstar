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

{{-- ============== BIG BLUE FOOTER (UniSC-style) ============== --}}
<section class="bg-blue-900 text-white">
    <div class="max-w-6xl mx-auto px-6 py-12 grid grid-cols-2 md:grid-cols-4 gap-8 text-sm">
        @foreach ([
            ['SERVICES', [
                'Library','Campus security and emergencies','Online payments',
                'Sonis Online','MyWorldstar — staff resources','Workplace health, safety and wellbeing',
            ]],
            ['WEBSITE', [
                'A–Z directory','Browsing this site','Site map','Accessibility','Explanation of terms',
            ]],
            ['LEGAL', [
                'Privacy','Copyright','Disclaimer','Right to Information','Data Collection Preferences',
            ]],
            ['CONTACT', [
                'Maps and directions','Student Central','Media enquiries','Careers at Worldstar','Contact Worldstar',
            ]],
        ] as [$heading, $links])
            <div>
                <h4 class="font-bold text-amber-300 tracking-wide mb-3">{{ $heading }}</h4>
                <ul class="space-y-2 text-white/90">
                    @foreach ($links as $link)
                        <li><a href="#" class="hover:text-amber-300">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    <div class="border-t border-white/20">
        <div class="max-w-6xl mx-auto px-6 py-6 flex flex-col md:flex-row gap-6 items-start md:items-center justify-between text-xs text-white/80">
            <div class="flex items-center gap-3">
                @foreach (['Facebook','X','Instagram','LinkedIn','YouTube','TikTok'] as $sn)
                    <a href="#" class="w-8 h-8 rounded-full border border-white/40 flex items-center justify-center hover:bg-white/10"
                       title="{{ $sn }}">
                        <span class="text-[10px]">{{ $sn[0] }}</span>
                    </a>
                @endforeach
            </div>
            <p class="max-w-2xl leading-relaxed">
                Worldstar College of Science and Technology, Inc. acknowledges the communities on whose
                lands we live, work and study. We pay our respects to leaders past, present and emerging,
                and recognise the strength, resilience and capacity of all our partners.
            </p>
            <div class="bg-white text-blue-900 font-extrabold px-4 py-3 rounded-sm">Worldstar</div>
        </div>
    </div>
</section>

</div>{{-- end full-bleed wrapper --}}
@endsection
