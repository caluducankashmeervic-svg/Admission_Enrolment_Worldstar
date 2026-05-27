<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Enrollment System'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        #search-overlay {
            transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.35s ease;
        }
        #search-overlay.search-open {
            transform: translateY(0) !important;
            opacity: 1 !important;
            pointer-events: auto !important;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">
    <header id="site-header" class="bg-white border-b border-slate-200 fixed top-0 left-0 right-0 z-40 shadow-md">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-3 md:py-6 flex items-center justify-between gap-3 md:gap-6 relative">
            {{-- LEFT: Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 md:gap-4 shrink-0">
                <img src="{{ asset('images/image.png') }}" alt="Worldstar Logo"
                     class="w-10 h-10 md:w-16 md:h-16 object-contain">
                <span class="font-semibold text-[#1D4ED8] leading-tight">
                    <span class="block text-sm md:text-xl">Worldstar College</span>
                    <span class="hidden sm:block text-[13px] text-slate-500 font-normal">of Science and Technology, Inc.</span>
                </span>
            </a>

            @auth
                {{-- Authenticated nav (desktop) --}}
                <nav class="hidden md:flex items-center gap-4 text-sm ml-auto">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-[#1D4ED8]">Dashboard</a>
                        <a href="{{ route('registrar.applicants.index') }}" class="hover:text-[#1D4ED8]">Applicants</a>
                        <a href="{{ route('registrar.enrollments.index') }}" class="hover:text-[#1D4ED8]">Enrollees</a>
                        <a href="{{ route('admin.courses.index') }}" class="hover:text-[#1D4ED8]">Courses</a>
                        <a href="{{ route('admin.sections.index') }}" class="hover:text-[#1D4ED8]">Sections</a>
                        <a href="{{ route('admin.terms.index') }}" class="hover:text-[#1D4ED8]">Terms</a>
                        <a href="{{ route('exam.schedule.index') }}" class="hover:text-[#1D4ED8]">Exams</a>
                        <a href="{{ route('admin.users.index') }}" class="hover:text-[#1D4ED8]">Users</a>
                        <a href="{{ route('admin.audit.index') }}" class="hover:text-[#1D4ED8]">Audit</a>
                    @elseif(auth()->user()->isRegistrar())
                        <a href="{{ route('registrar.applicants.index') }}" class="hover:text-[#1D4ED8]">Applicants</a>
                        <a href="{{ route('registrar.enrollments.index') }}" class="hover:text-[#1D4ED8]">Enrollees</a>
                        <a href="{{ route('exam.schedule.index') }}" class="hover:text-[#1D4ED8]">Exams</a>
                    @else
                        <a href="{{ route('applicant.admission.create') }}" class="hover:text-[#1D4ED8]">My Admission</a>
                        <a href="{{ route('applicant.my-status') }}" class="hover:text-[#1D4ED8]">Check Status</a>
                    @endif
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-2 text-slate-500 hover:text-[#1D4ED8]">
                        <img src="{{ auth()->user()->profile_photo_url }}"
                             class="w-7 h-7 rounded-full object-cover border border-slate-200" alt="">
                        <span>{{ auth()->user()->name }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-rose-600 hover:underline">Logout</button>
                    </form>
                </nav>
                {{-- Mobile hamburger (auth users) --}}
                <button type="button" id="mobile-menu-btn"
                        class="md:hidden ml-auto p-2 text-slate-600 hover:text-[#1D4ED8] focus:outline-none"
                        aria-label="Toggle navigation">
                    <svg id="hamburger-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            @else
                {{-- CENTER: Mega-menu navigation --}}
                @php
                    $aboutLinks = [
                        ['The Story of Worldstar', route('about.story')],
                        ['Vision and Mission',     route('about.vision-mission')],
                        ['Core Values',            route('about.core-values')],
                        ['Philosophy',             route('about.philosophy')],
                        ['Accreditations and Recognition', '#accreditation'],
                        ['Announcements', '#announcements'],
                        ['Contact Us', '#contact'],
                    ];
                @endphp
                <nav class="hidden md:flex items-center gap-10 text-base font-semibold text-slate-800 mx-auto">
                    {{-- ABOUT --}}
                    <div class="self-stretch flex items-center" data-megamenu>
                        <button type="button" data-megabtn aria-expanded="false"
                                class="flex items-center gap-1.5 py-2 text-lg font-semibold hover:text-[#1D4ED8] transition-colors">
                            About
                            <svg data-megacaret class="w-3.5 h-3.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        {{-- About Mega Menu --}}
                        <div data-megapanel
                             class="hidden absolute top-full left-0 right-0 pt-4 z-50 justify-end">
                            <div class="w-[min(1100px,calc(100%-6rem))] bg-white shadow-xl rounded-xl border-t-2 border-[#1D4ED8] px-12 py-10">
                                <h3 class="text-[#1D4ED8] font-bold uppercase text-[13px] tracking-widest mb-6">About WCST</h3>
                                <ul class="grid grid-cols-2 md:grid-cols-3 gap-x-12 gap-y-1">
                                    @foreach ($aboutLinks as [$label, $href])
                                        <li>
                                            <a href="{{ $href }}"
                                               class="block text-[15px] font-medium text-slate-700 hover:text-[#1D4ED8] hover:translate-x-1 transition py-[6px]">
                                                {{ $label }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- ADMISSIONS --}}
                    <div class="self-stretch flex items-center" data-megamenu>
                        <button type="button" data-megabtn aria-expanded="false"
                                class="flex items-center gap-1.5 py-2 text-lg font-semibold hover:text-[#1D4ED8] transition-colors">
                            Admissions
                            <svg data-megacaret class="w-3.5 h-3.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div data-megapanel
                             class="hidden absolute top-full left-0 right-0 pt-4 z-50 justify-end">
                            <div class="w-[min(1100px,calc(100%-6rem))] bg-white shadow-xl rounded-xl border-t-2 border-[#1D4ED8] px-12 py-10 grid grid-cols-1 md:grid-cols-3 gap-14">
                                <div>
                                    <h3 class="text-[#1D4ED8] font-bold uppercase text-[13px] tracking-widest mb-6">Be a Worldstar Lion!</h3>
                                    <ul class="space-y-3">
                                        @foreach ([
                                            ['Reference Code Apply', route('applicant.code.form')],
                                            ['Applicant Portal', route('login')],
                                            ['Application Forms', route('applicant.pre-register.choose')],
                                            ['Enrollment FAQs', '/#questions'],
                                            ['Contact Admission', '#contact'],
                                        ] as [$label, $href])
                                            <li><a href="{{ $href }}" class="block text-[15px] font-medium text-slate-700 hover:text-[#1D4ED8] transition">{{ $label }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-[#1D4ED8] font-bold uppercase text-[13px] tracking-widest mb-6">Programs</h3>
                                    <ul class="space-y-3">
                                        @foreach ([
                                            ['Senior High School', '#shs'],
                                            ['Transferees', '#transferees'],
                                            ['TESDA Diploma Courses', '#tesda'],
                                        ] as [$label, $href])
                                            <li><a href="{{ $href }}" class="block text-[15px] font-medium text-slate-700 hover:text-[#1D4ED8] transition">{{ $label }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-[#1D4ED8] font-bold uppercase text-[13px] tracking-widest mb-6">Student Support</h3>
                                    <ul class="space-y-3">
                                        @foreach ([
                                            ['Scholarship', '#scholarship'],
                                            ['Safe Space', '#safespace'],
                                            ['Apply as Working Student', '#working'],
                                        ] as [$label, $href])
                                            <li><a href="{{ $href }}" class="block text-[15px] font-medium text-slate-700 hover:text-[#1D4ED8] transition">{{ $label }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ACADEMICS --}}
                    <div class="self-stretch flex items-center" data-megamenu>
                        <button type="button" data-megabtn aria-expanded="false"
                                class="flex items-center gap-1.5 py-2 text-lg font-semibold hover:text-[#1D4ED8] transition-colors">
                            Academics
                            <svg data-megacaret class="w-3.5 h-3.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div data-megapanel
                             class="hidden absolute top-full left-0 right-0 pt-4 z-50 justify-end">
                            <div class="w-[min(1100px,calc(100%-6rem))] bg-white shadow-xl rounded-xl border-t-2 border-[#1D4ED8] px-12 py-10 grid grid-cols-1 md:grid-cols-3 gap-10">
                                <div>
                                    <h3 class="text-[#1D4ED8] font-bold uppercase text-[13px] tracking-widest mb-6">Academic Track</h3>
                                    <ul class="space-y-3">
                                        @foreach ([
                                            'Arts, Social Sciences, and Humanities',
                                            'Business and Entrepreneurship',
                                            'Science, Technology, Engineering & Mathematics (Health & Non-Health)',
                                        ] as $label)
                                            <li><a href="#academic" class="block text-[15px] font-medium text-slate-700 hover:text-[#1D4ED8] transition">{{ $label }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-[#1D4ED8] font-bold uppercase text-[13px] tracking-widest mb-6">Tech-Pro Track</h3>
                                    <ul class="space-y-3">
                                        @foreach ([
                                            'Automotive and Small Engine Technologies',
                                            'Business, Hospitality, and Tourism Bundle',
                                            'Creative Arts and Design Technologies Bundle',
                                            'ICT support and Computer Programming Technologies Bundle',
                                            'Industrial Arts Bundle',
                                        ] as $label)
                                            <li><a href="#techpro" class="block text-[15px] font-medium text-slate-700 hover:text-[#1D4ED8] transition">{{ $label }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="md:border-l md:border-slate-900/70 md:pl-10">
                                    <h3 class="text-[#1D4ED8] font-bold uppercase text-[13px] tracking-widest mb-6">TESDA Diploma Course</h3>
                                    <ul class="space-y-3">
                                        @foreach ([
                                            'CST (Computer Science Technology)',
                                            'CET (Computer Engineering Technology)',
                                            'EET (Electronics Engineering Technology)',
                                            'ICT (Information and Communications Technology)',
                                        ] as $label)
                                            <li><a href="#diploma" class="block text-[15px] font-medium text-slate-700 hover:text-[#1D4ED8] transition">{{ $label }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>

                {{-- RIGHT: Action Buttons + Search --}}
                <div class="flex items-center gap-3 shrink-0">
                    {{-- Search icon --}}
                    <button id="search-open" type="button"
                            class="p-2 text-slate-500 hover:text-[#1D4ED8] transition-colors"
                            aria-label="Open search">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                    <a href="{{ route('login') }}"
                       class="text-sm font-semibold bg-[#1D4ED8] text-white px-3 py-1.5 md:px-5 md:py-2 rounded hover:bg-[#1e40af] transition-colors">
                        LOGIN
                    </a>
                    {{-- Mobile hamburger (public) --}}
                    <button type="button" id="mobile-menu-btn"
                            class="md:hidden p-2 text-slate-600 hover:text-[#1D4ED8] focus:outline-none"
                            aria-label="Toggle navigation">
                        <svg id="hamburger-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endauth
        </div>
        {{-- ===== MOBILE NAV DRAWER ===== --}}
        <div id="mobile-nav" class="hidden border-t border-slate-100 bg-white">
            <nav class="max-w-7xl mx-auto px-4 pb-3 space-y-0.5 text-sm">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">Dashboard</a>
                        <a href="{{ route('registrar.applicants.index') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">Applicants</a>
                        <a href="{{ route('registrar.enrollments.index') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">Enrollees</a>
                        <a href="{{ route('admin.courses.index') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">Courses</a>
                        <a href="{{ route('admin.sections.index') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">Sections</a>
                        <a href="{{ route('admin.terms.index') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">Terms</a>
                        <a href="{{ route('exam.schedule.index') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">Exams</a>
                        <a href="{{ route('admin.users.index') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">Users</a>
                        <a href="{{ route('admin.audit.index') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">Audit</a>
                    @elseif(auth()->user()->isRegistrar())
                        <a href="{{ route('registrar.applicants.index') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">Applicants</a>
                        <a href="{{ route('registrar.enrollments.index') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">Enrollees</a>
                        <a href="{{ route('exam.schedule.index') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">Exams</a>
                    @else
                        <a href="{{ route('applicant.admission.create') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">My Admission</a>
                        <a href="{{ route('applicant.my-status') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">Check Status</a>
                    @endif
                    <div class="border-t border-slate-100 mt-2 pt-2 flex items-center justify-between px-1">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-2 py-2 rounded hover:bg-slate-100">
                            <img src="{{ auth()->user()->profile_photo_url }}" class="w-7 h-7 rounded-full border border-slate-200" alt="">
                            <span class="font-medium">{{ auth()->user()->name }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="px-3 py-1.5 text-rose-600 hover:bg-rose-50 rounded">Logout</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('about.story') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">About WCST</a>
                    <a href="{{ route('applicant.pre-register.choose') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">Apply Now</a>
                    <a href="{{ route('applicant.code.form') }}" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">Reference Code</a>
                    <a href="/#contact" class="block px-3 py-2.5 rounded hover:bg-slate-100 font-medium">Contact Us</a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- ===== SLIDE-DOWN SEARCH OVERLAY ===== --}}
    <div id="search-overlay"
         class="fixed left-0 right-0 z-50 flex flex-col"
         style="top: 0; transform: translateY(-100%); opacity: 0; pointer-events: none;"
         role="dialog" aria-modal="true" aria-label="Site search">

        {{-- Spacer matching the sticky header so the bar appears flush below it --}}
        <div class="h-16 md:h-[104px] shrink-0 bg-white"></div>

        {{-- White search bar --}}
        <div class="bg-white border-b border-slate-200 shadow-sm">
            <form action="{{ route('register') }}" method="GET"
                  class="max-w-7xl mx-auto px-8 flex items-center gap-5 h-[68px]">
                {{-- Search icon --}}
                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>

                {{-- Input --}}
                <input id="search-input"
                       type="text"
                       name="q"
                       placeholder="Type keyword and hit enter"
                       class="flex-1 text-slate-700 text-lg placeholder-slate-400 bg-transparent outline-none">

                {{-- Circular X close button --}}
                <button id="search-close"
                        type="button"
                        class="w-9 h-9 rounded-full border border-slate-300 flex items-center justify-center
                               text-slate-500 hover:text-slate-900 hover:border-slate-500 transition-colors shrink-0"
                        aria-label="Close search">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </form>
        </div>

        {{-- Dark dimmer backdrop (clicking it also closes) --}}
        <div id="search-backdrop" class="flex-1 bg-black/50 cursor-pointer"></div>
    </div>
    {{-- ===== END SEARCH OVERLAY ===== --}}

    <main class="flex-1 max-w-7xl w-full mx-auto px-4 py-6 pt-16 md:pt-[128px]">
        @if (session('status'))
            <div class="mb-4 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2 text-sm">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 rounded-md bg-rose-50 border border-rose-200 text-rose-800 px-4 py-2 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white py-4 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} {{ config('app.name') ?: 'Enrollment System' }}
    </footer>

    @stack('scripts')

    <script>
    (function () {
        var overlay  = document.getElementById('search-overlay');
        var openBtn  = document.getElementById('search-open');
        var closeBtn = document.getElementById('search-close');
        var input    = document.getElementById('search-input');

        function openSearch() {
            overlay.classList.add('search-open');
            setTimeout(function () { input && input.focus(); }, 80);
            document.body.style.overflow = 'hidden';
        }

        function closeSearch() {
            overlay.classList.remove('search-open');
            document.body.style.overflow = '';
        }

        openBtn  && openBtn.addEventListener('click', openSearch);
        closeBtn && closeBtn.addEventListener('click', closeSearch);

        var backdrop = document.getElementById('search-backdrop');
        backdrop && backdrop.addEventListener('click', closeSearch);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSearch();
        });
    })();
    </script>

    <script>
    // Mega-menu click toggle (replaces hover behaviour)
    (function () {
        var menus = document.querySelectorAll('[data-megamenu]');
        if (!menus.length) return;

        function closeAll(except) {
            menus.forEach(function (m) {
                if (m === except) return;
                var p = m.querySelector('[data-megapanel]');
                var b = m.querySelector('[data-megabtn]');
                var c = m.querySelector('[data-megacaret]');
                if (p) { p.classList.add('hidden'); p.classList.remove('flex'); }
                if (b) b.setAttribute('aria-expanded', 'false');
                if (c) c.classList.remove('rotate-180');
            });
        }

        menus.forEach(function (menu) {
            var btn   = menu.querySelector('[data-megabtn]');
            var panel = menu.querySelector('[data-megapanel]');
            var caret = menu.querySelector('[data-megacaret]');
            if (!btn || !panel) return;

            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var willOpen = panel.classList.contains('hidden');
                closeAll(menu);
                if (willOpen) {
                    panel.classList.remove('hidden');
                    panel.classList.add('flex');
                    btn.setAttribute('aria-expanded', 'true');
                    caret && caret.classList.add('rotate-180');
                } else {
                    panel.classList.add('hidden');
                    panel.classList.remove('flex');
                    btn.setAttribute('aria-expanded', 'false');
                    caret && caret.classList.remove('rotate-180');
                }
            });

            panel.addEventListener('click', function (e) { e.stopPropagation(); });
        });

        document.addEventListener('click', function () { closeAll(null); });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeAll(null);
        });
    })();
    </script>
    <script>
    // Mobile nav toggle
    (function () {
        var btn   = document.getElementById('mobile-menu-btn');
        var nav   = document.getElementById('mobile-nav');
        var hIcon = document.getElementById('hamburger-icon');
        var xIcon = document.getElementById('close-icon');
        if (!btn || !nav) return;
        var isOpen = false;
        function openMenu()  { isOpen = true;  nav.classList.remove('hidden'); hIcon && hIcon.classList.add('hidden');    xIcon && xIcon.classList.remove('hidden'); }
        function closeMenu() { isOpen = false; nav.classList.add('hidden');    hIcon && hIcon.classList.remove('hidden'); xIcon && xIcon.classList.add('hidden'); }
        btn.addEventListener('click', function (e) { e.stopPropagation(); isOpen ? closeMenu() : openMenu(); });
        document.addEventListener('click', function (e) { if (isOpen && !nav.contains(e.target)) closeMenu(); });
        window.addEventListener('resize', function () { if (window.innerWidth >= 768) closeMenu(); });
    })();
    </script>
</body>
</html>
