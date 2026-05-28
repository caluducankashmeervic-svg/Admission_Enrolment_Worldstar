@extends('layouts.app')
@section('title', 'Welcome')

@section('content')
{{-- Full-bleed: break out of the layout's max-w-7xl container so sections span the entire viewport width --}}
<div class="relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen -my-6">

{{-- ============== HERO (cinematic dark banner) ============== --}}
<section class="relative bg-black text-white overflow-hidden">
    <div class="relative h-[520px] md:h-[720px] flex">

        {{-- ── Slider: full-bleed background ── --}}
        <div id="hero-slider" class="absolute inset-0 overflow-hidden">
            @foreach ([
                ['HeroSlide1.jpg', 'Worldstar Faculty'],
                ['HeroSlide2.jpg', 'Worldstar Sports Fest'],
                ['Heroslide3.png', 'Worldstar Students'],
                ['HeroSlide5.jpg', 'Worldstar Student Gathering'],
                ['HeroSlide6.jpg', 'Worldstar Student Activities'],
                ['HeroSlide7.jpg', 'Worldstar School Community'],
            ] as $index => [$image, $alt])
                <div class="hero-slide absolute inset-0 transition-opacity duration-700 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                    <img src="{{ asset('images/' . $image) }}" alt="{{ $alt }}" class="w-full h-full object-cover object-center">
                </div>
            @endforeach

            {{-- Prev button --}}
            <button onclick="heroSlide(-1)" aria-label="Previous slide"
                    class="absolute left-3 top-1/2 -translate-y-1/2 z-20
                           w-9 h-9 rounded-full flex items-center justify-center
                           bg-black/30 hover:bg-black/55 text-white/80 hover:text-white
                           transition-all duration-200 backdrop-blur-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            {{-- Next button --}}
            <button onclick="heroSlide(1)" aria-label="Next slide"
                    class="absolute right-3 top-1/2 -translate-y-1/2 z-20
                           w-9 h-9 rounded-full flex items-center justify-center
                           bg-black/30 hover:bg-black/55 text-white/80 hover:text-white
                           transition-all duration-200 backdrop-blur-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            {{-- Dot indicators --}}
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex gap-2">
                @for ($index = 0; $index < 6; $index++)
                    <button onclick="heroGoTo({{ $index }})" class="hero-dot w-2.5 h-2.5 rounded-full {{ $index === 0 ? 'bg-white/80' : 'bg-white/30' }} transition-all duration-300"></button>
                @endfor
            </div>
        </div>

        {{-- Dark overlay — left-heavy gradient for text legibility --}}
        <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/50 to-black/10 z-10"></div>

        {{-- Hero copy --}}
        <div class="relative z-20 max-w-7xl mx-auto px-8 md:px-16 flex items-center w-full">
            <div class="max-w-lg">
                <h1 class="text-amber-300 text-5xl md:text-7xl font-extrabold leading-tight">
                    Your Future<br>Starts Here
                </h1>
                <p class="text-white/85 text-base md:text-lg mt-5 leading-relaxed">
                    Quality education, affordable tuition —<br>
                    apply online at Worldstar College of Science and Technology.
                </p>
                <a href="{{ route('applicant.pre-register.choose') }}"
                   class="inline-block mt-7 bg-amber-300 hover:bg-amber-400 text-slate-900 font-bold text-sm px-7 py-3 rounded">
                    Apply Now
                </a>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
(function () {
    let current = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots   = document.querySelectorAll('.hero-dot');
    let timer    = startTimer();

    function show(idx) {
        slides[current].classList.replace('opacity-100', 'opacity-0');
        dots[current].classList.replace('bg-white/80', 'bg-white/30');
        current = (idx + slides.length) % slides.length;
        slides[current].classList.replace('opacity-0', 'opacity-100');
        dots[current].classList.replace('bg-white/30', 'bg-white/80');
    }

    function startTimer() {
        return setInterval(() => show(current + 1), 5000);
    }

    window.heroSlide = function (dir) {
        clearInterval(timer);
        show(current + dir);
        timer = startTimer();
    };

    window.heroGoTo = function (idx) {
        clearInterval(timer);
        show(idx);
        timer = startTimer();
    };
}());
</script>
@endpush

{{-- ============== WHY CHOOSE WORLDSTAR (replaces generic "Explore campuses" banner) ============== --}}
@php
    $stats = [
        ['10+',  'Years Building Worldstar Lions'],
        ['15+',  'SHS & TESDA Programs Offered'],
        ['100%', 'TESDA-Accredited Diploma Tracks'],
        ['1:1',  'Faith, Care & Mentorship Support'],
    ];
@endphp
<section class="bg-white pt-10">
    <div class="max-w-5xl mx-auto px-6 pb-8">
        <div class="rounded-md overflow-hidden bg-gradient-to-r from-[#0b1c96] via-[#1d4ed8] to-emerald-600 shadow-lg">
            <div class="px-8 py-8 md:py-10 grid md:grid-cols-[1.1fr_1fr] gap-6 items-center">
                <div>
                    <p class="text-amber-300 font-semibold uppercase tracking-widest text-xs">Why Choose Worldstar</p>
                    <h3 class="text-white font-extrabold leading-tight text-2xl md:text-3xl mt-2">
                        A faith-driven, industry-ready college<br class="hidden md:block">
                        right here in Roxas, Capiz.
                    </h3>
                    <p class="text-white/85 text-sm mt-3 max-w-md">
                        TESDA-accredited programs, dedicated faculty, and a Safe Space community
                        that supports every learner — academically, emotionally, and spiritually.
                    </p>
                    <a href="{{ route('applicant.pre-register.choose') }}"
                       class="inline-block mt-5 bg-amber-300 hover:bg-amber-400 text-slate-900 font-bold text-sm px-5 py-2.5 rounded">
                        START YOUR APPLICATION
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($stats as [$value, $label])
                        <div class="bg-white/10 backdrop-blur rounded-lg px-4 py-4 border border-white/20">
                            <p class="text-amber-300 font-extrabold text-2xl md:text-3xl leading-none">{{ $value }}</p>
                            <p class="text-white/90 text-xs mt-1 leading-snug">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============== THREE IMAGE CARDS ============== --}}
<section id="academics" class="bg-white">
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



{{-- ============== FAQ (kept for /#questions anchor) ============== --}}
<section id="questions" class="bg-white pb-6">
    <div class="max-w-4xl mx-auto px-6">
        <h3 class="font-bold text-slate-900 text-lg mb-4">Frequently Asked Questions</h3>
        <div class="space-y-3 text-sm text-slate-700 text-justify">
            <details class="group border border-slate-200 rounded-md p-3">
                <summary class="cursor-pointer font-medium">Who can apply?</summary>
                <p class="mt-2 text-slate-600">Senior-high-school graduates and transferees are welcome to apply online.</p>
            </details>
            <details class="group border border-slate-200 rounded-md p-3">
                <summary class="cursor-pointer font-medium">Is there an entrance exam?</summary>
                <p class="mt-2 text-slate-600">Yes. After pre-registration, you'll receive a schedule for the entrance exam.</p>
            </details>
            <details class="group border border-slate-200 rounded-md p-3">
                <summary class="cursor-pointer font-medium">What documents do I need to submit for enrollment?</summary>
                <p class="mt-2 text-slate-600">You will need to bring the following original documents: Form 137 or Report Card, PSA Birth Certificate, Certificate of Good Moral Character, two 2x2 ID photos, a Medical Certificate from a licensed physician, and your Diploma or Certificate of Graduation. The registrar's office will verify these during your enrollment appointment.</p>
            </details>
            <details class="group border border-slate-200 rounded-md p-3">
                <summary class="cursor-pointer font-medium">How do I check my application status?</summary>
                <p class="mt-2 text-slate-600">Once you have pre-registered and created an account, log in to the portal and click "Check Status" in the navigation menu. Your reference code and current application step will be displayed there.</p>
            </details>
            <details class="group border border-slate-200 rounded-md p-3">
                <summary class="cursor-pointer font-medium">Are there scholarships or financial assistance programs available?</summary>
                <p class="mt-2 text-slate-600">Yes. Worldstar College offers various scholarship and financial assistance options for qualified students, including academic merit scholarships and government-sponsored programs such as UNIFAST/TES. You may inquire directly at the registrar's or student affairs office for the current requirements and application periods.</p>
            </details>
            <details class="group border border-slate-200 rounded-md p-3">
                <summary class="cursor-pointer font-medium">What are the tuition fees and payment options?</summary>
                <p class="mt-2 text-slate-600">Worldstar College is committed to providing affordable, quality education. Tuition rates vary by program. Installment payment plans are available for students who need flexible arrangements. For the latest fee schedule, please contact the registrar's office at 0916 908 8531 or email wcst.2016@gmail.com.</p>
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
        <div class="flex flex-col items-center gap-6">

            {{-- Circular logo + school name --}}
            <div class="flex flex-col items-center gap-3 mt-4">
                <div class="inline-flex rounded-full p-0">
                    <img src="{{ asset('images/image.png') }}"
                         alt="Worldstar College Logo"
                         class="w-40 h-40 object-contain saturate-125 contrast-110">
                </div>

                <div class="text-center">
                    <p class="text-white font-extrabold text-base leading-snug">
                        Worldstar College of Science and Technology, Inc.
                    </p>
                </div>
            </div>

            {{-- Social icons row — Facebook only --}}
            <div class="flex items-center justify-center gap-3">
                <a href="https://www.facebook.com/WCST2016" title="Facebook" target="_blank" rel="noopener noreferrer"
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
        <div class="md:pl-10">
            <h3 class="text-white mb-7"
                style="font-family: Georgia, 'Times New Roman', serif;
                       font-size: 1.6rem;
                       font-weight: 400;
                       letter-spacing: 0.01em;">
                Quick Links
            </h3>
            <ul class="space-y-[20px]">
@foreach ([
                    ['About',      0],
                    ['Admissions', 1],
                    ['Academics',  2],
                ] as [$label, $menuIndex])
                    <li>
                        <a href="#"
                           onclick="event.preventDefault(); footerNavTo({{ $menuIndex }})"
                           class="text-[14px] tracking-[0.18em] uppercase text-white/80
                                  hover:text-white hover:tracking-[0.22em]
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
        <div class="md:pl-10">
            <h3 class="text-white mb-7"
                style="font-family: Georgia, 'Times New Roman', serif;
                       font-size: 1.6rem;
                       font-weight: 400;
                       letter-spacing: 0.01em;">
                Contact Details
            </h3>
            <div class="space-y-5"
                 style="font-family: 'Helvetica Neue', Arial, sans-serif;">

                <div>
                    <p class="text-[13px] uppercase tracking-widest text-white/50 mb-1">
                        Contact
                    </p>
                    <p class="text-[17px] leading-relaxed text-white/85">
                        (078) 642-0421 | 0916 908 8531
                    </p>
                </div>

                <div>
                    <p class="text-[13px] uppercase tracking-widest text-white/50 mb-1">
                        Email Address
                    </p>
                    <p class="text-[17px] leading-relaxed text-white/85">
                        wcst.2016@gmail.com
                    </p>
                </div>

                <div>
                    <p class="text-[13px] uppercase tracking-widest text-white/50 mb-1">
                        Address
                    </p>
                    <p class="text-[17px] leading-relaxed text-white/85">
                        Alliance Bldg., National Highway,<br>
                        Bantug, Roxas, Philippines, 3320
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Sub-footer ── --}}
    <div class="relative max-w-7xl mx-auto px-8 md:px-12">
        <hr class="border-white/20">
        <p class="py-6 text-center text-sm text-white/60"
           style="font-family: 'Helvetica Neue', Arial, sans-serif; font-weight: 300;">
            &copy; Copyright 2025 &ndash; 2026 &nbsp;|&nbsp; Privacy Notice
        </p>
    </div>

</footer>



</div>{{-- end full-bleed wrapper --}}
@endsection

@push('scripts')
<script>
function footerNavTo(menuIndex) {
    // Scroll to top first
    window.scrollTo({ top: 0, behavior: 'smooth' });
    // Wait for scroll to finish, then click the matching nav mega-menu button
    var delay = window.scrollY > 300 ? 700 : 150;
    setTimeout(function () {
        var btns = document.querySelectorAll('[data-megabtn]');
        if (btns[menuIndex]) btns[menuIndex].click();
    }, delay);
}
</script>
@endpush
