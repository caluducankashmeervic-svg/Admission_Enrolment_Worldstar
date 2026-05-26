<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Enrollment System'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">
    <header class="bg-white border-b border-slate-200 relative z-40">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-6">
            {{-- LEFT: Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                <img src="{{ asset('images/image.png') }}" alt="Worldstar Logo"
                     class="w-10 h-10 object-contain">
                <span class="font-semibold text-[#1D4ED8] leading-tight">
                    <span class="block text-base">Worldstar College</span>
                    <span class="block text-[11px] text-slate-500 font-normal">of Science and Technology, Inc.</span>
                </span>
            </a>

            @auth
                {{-- CENTER + RIGHT: Authenticated nav (unchanged behavior) --}}
                <nav class="flex items-center gap-4 text-sm ml-auto">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-[#1D4ED8]">Dashboard</a>
                        <a href="{{ route('admin.enrollees.index') }}" class="hover:text-[#1D4ED8]">Manage Enrollees</a>
                        <a href="{{ route('registrar.applicants.index') }}" class="hover:text-[#1D4ED8]">Applicants</a>
                        <a href="{{ route('registrar.enrollments.index') }}" class="hover:text-[#1D4ED8]">Enrollments</a>
                        <a href="{{ route('admin.courses.index') }}" class="hover:text-[#1D4ED8]">Courses</a>
                        <a href="{{ route('admin.sections.index') }}" class="hover:text-[#1D4ED8]">Sections</a>
                        <a href="{{ route('admin.terms.index') }}" class="hover:text-[#1D4ED8]">Terms</a>
                        <a href="{{ route('exam.schedule.index') }}" class="hover:text-[#1D4ED8]">Exams</a>
                        <a href="{{ route('admin.users.index') }}" class="hover:text-[#1D4ED8]">Users</a>
                        <a href="{{ route('admin.audit.index') }}" class="hover:text-[#1D4ED8]">Audit</a>
                    @elseif(auth()->user()->isRegistrar())
                        <a href="{{ route('registrar.applicants.index') }}" class="hover:text-[#1D4ED8]">Applicants</a>
                        <a href="{{ route('registrar.enrollments.index') }}" class="hover:text-[#1D4ED8]">Enrollments</a>
                        <a href="{{ route('registrar.lookup') }}" class="hover:text-[#1D4ED8]">Lookup</a>
                        <a href="{{ route('exam.schedule.index') }}" class="hover:text-[#1D4ED8]">Exams</a>
                    @else
                        <a href="{{ route('applicant.admission.create') }}" class="hover:text-[#1D4ED8]">My Admission</a>
                        <a href="{{ route('applicant.status.form') }}" class="hover:text-[#1D4ED8]">Check Status</a>
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
            @else
                {{-- CENTER: Mega-menu navigation --}}
                @php
                    $aboutLinks = [
                        ['The Story of Worldstar', '#about'],
                        ['Vision and Mission', '/#mission'],
                        ['Core Values', '/#values'],
                        ['Quality Policy', '#quality'],
                        ['Accreditations and Recognition', '#accreditation'],
                        ['Announcements', '#announcements'],
                        ['Contact Us', '#contact'],
                    ];
                @endphp
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-800 mx-auto">
                    {{-- ABOUT --}}
                    <div class="group">
                        <button type="button"
                                class="flex items-center gap-1 py-2 hover:text-[#1D4ED8] transition-colors">
                            About
                            <svg class="w-3.5 h-3.5 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        {{-- About Mega Menu --}}
                        <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-opacity duration-200
                                    absolute left-0 right-0 top-full bg-white shadow-lg rounded-b-lg border-t border-slate-100 z-50">
                            <div class="max-w-7xl mx-auto px-8 py-8">
                                <h3 class="text-[#1D4ED8] font-bold uppercase text-xs tracking-wider mb-4">About WCST</h3>
                                <ul class="grid grid-cols-2 md:grid-cols-3 gap-x-8 gap-y-2">
                                    @foreach ($aboutLinks as [$label, $href])
                                        <li>
                                            <a href="{{ $href }}"
                                               class="block text-sm text-slate-700 hover:text-[#FBBF24] hover:translate-x-1 transition py-1">
                                                {{ $label }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- ADMISSIONS --}}
                    <div class="group">
                        <button type="button"
                                class="flex items-center gap-1 py-2 hover:text-[#1D4ED8] transition-colors">
                            Admissions
                            <svg class="w-3.5 h-3.5 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-opacity duration-200
                                    absolute left-0 right-0 top-full bg-white shadow-lg rounded-b-lg border-t border-slate-100 z-50">
                            <div class="max-w-7xl mx-auto px-8 py-8 grid grid-cols-1 md:grid-cols-3 gap-10">
                                <div>
                                    <h3 class="text-[#1D4ED8] font-bold uppercase text-xs tracking-wider mb-4">Be a Worldstar Lion!</h3>
                                    <ul class="space-y-2">
                                        @foreach ([
                                            ['Apply', route('register')],
                                            ['Applicant Portal', route('login')],
                                            ['Application Forms', '#forms'],
                                            ['Enrollment FAQs', '/#questions'],
                                            ['Contact Admission', '#contact'],
                                        ] as [$label, $href])
                                            <li><a href="{{ $href }}" class="block text-sm text-slate-700 hover:text-[#FBBF24] transition py-1">{{ $label }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-[#1D4ED8] font-bold uppercase text-xs tracking-wider mb-4">Programs</h3>
                                    <ul class="space-y-2">
                                        @foreach ([
                                            ['Senior High School', '#shs'],
                                            ['Transferees', '#transferees'],
                                            ['TESDA Diploma Courses', '#tesda'],
                                        ] as [$label, $href])
                                            <li><a href="{{ $href }}" class="block text-sm text-slate-700 hover:text-[#FBBF24] transition py-1">{{ $label }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-[#1D4ED8] font-bold uppercase text-xs tracking-wider mb-4">Student Support</h3>
                                    <ul class="space-y-2">
                                        @foreach ([
                                            ['Scholarship', '#scholarship'],
                                            ['Safe Space', '#safespace'],
                                            ['Apply as Working Student', '#working'],
                                        ] as [$label, $href])
                                            <li><a href="{{ $href }}" class="block text-sm text-slate-700 hover:text-[#FBBF24] transition py-1">{{ $label }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ACADEMICS --}}
                    <div class="group">
                        <button type="button"
                                class="flex items-center gap-1 py-2 hover:text-[#1D4ED8] transition-colors">
                            Academics
                            <svg class="w-3.5 h-3.5 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-opacity duration-200
                                    absolute left-0 right-0 top-full bg-white shadow-lg rounded-b-lg border-t border-slate-100 z-50">
                            <div class="max-w-7xl mx-auto px-8 py-8 grid grid-cols-1 md:grid-cols-3 gap-10">
                                <div>
                                    <h3 class="text-[#1D4ED8] font-bold uppercase text-xs tracking-wider mb-4">Academic Track</h3>
                                    <ul class="space-y-2">
                                        @foreach ([
                                            'Arts, Social Sciences, and Humanities',
                                            'Business and Entrepreneurship',
                                            'Science, Technology, Engineering & Mathematics (Health & Non-Health)',
                                        ] as $label)
                                            <li><a href="#academic" class="block text-sm text-slate-700 hover:text-[#FBBF24] transition py-1">{{ $label }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-[#1D4ED8] font-bold uppercase text-xs tracking-wider mb-4">Tech-Pro Track</h3>
                                    <ul class="space-y-2">
                                        @foreach ([
                                            'Automotive and Small Engine Technologies',
                                            'Business, Hospitality, and Tourism Bundle',
                                            'Creative Arts and Design Technologies Bundle',
                                            'ICT support and Computer Programming Technologies Bundle',
                                            'Industrial Arts Bundle',
                                        ] as $label)
                                            <li><a href="#techpro" class="block text-sm text-slate-700 hover:text-[#FBBF24] transition py-1">{{ $label }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-[#1D4ED8] font-bold uppercase text-xs tracking-wider mb-4">3-Year Diploma Course</h3>
                                    <ul class="space-y-2">
                                        @foreach ([
                                            'CST (Computer Science Technology)',
                                            'CET (Computer Engineering Technology)',
                                            'EET (Electronics Engineering Technology)',
                                            'ICT (Information and Communications Technology)',
                                        ] as $label)
                                            <li><a href="#diploma" class="block text-sm text-slate-700 hover:text-[#FBBF24] transition py-1">{{ $label }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>

                {{-- RIGHT: Action Buttons --}}
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('login') }}" class="hidden lg:inline text-sm text-slate-600 hover:text-[#1D4ED8] mr-2">Admin</a>
                    <a href="{{ route('register') }}"
                       class="text-sm font-semibold border border-[#1D4ED8] text-[#1D4ED8] px-4 py-1.5 rounded hover:bg-[#1D4ED8] hover:text-white transition-colors">
                        Register
                    </a>
                    <a href="{{ route('login') }}"
                       class="text-sm font-semibold bg-[#1D4ED8] text-white px-4 py-1.5 rounded hover:bg-[#1e40af] transition-colors">
                        LOGIN
                    </a>
                </div>
            @endauth
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto px-4 py-6">
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
</body>
</html>
