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
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/image.png') }}" alt="Worldstar Logo"
                     class="w-10 h-10 object-contain">
                <span class="font-semibold text-blue-700 leading-tight">
                    <span class="block text-base">Worldstar College</span>
                    <span class="block text-[11px] text-slate-500 font-normal">of Science and Technology, Inc.</span>
                </span>
            </a>
            <nav class="flex items-center gap-4 text-sm">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                        <a href="{{ route('admin.enrollees.index') }}" class="hover:text-blue-600">Manage Enrollees</a>
                        <a href="{{ route('registrar.applicants.index') }}" class="hover:text-blue-600">Applicants</a>
                        <a href="{{ route('registrar.enrollments.index') }}" class="hover:text-blue-600">Enrollments</a>
                        <a href="{{ route('admin.courses.index') }}" class="hover:text-blue-600">Courses</a>
                        <a href="{{ route('admin.sections.index') }}" class="hover:text-blue-600">Sections</a>
                        <a href="{{ route('admin.terms.index') }}" class="hover:text-blue-600">Terms</a>
                        <a href="{{ route('exam.schedule.index') }}" class="hover:text-blue-600">Exams</a>
                        <a href="{{ route('admin.users.index') }}" class="hover:text-blue-600">Users</a>
                        <a href="{{ route('admin.audit.index') }}" class="hover:text-blue-600">Audit</a>
                    @elseif(auth()->user()->isRegistrar())
                        <a href="{{ route('registrar.applicants.index') }}" class="hover:text-blue-600">Applicants</a>
                        <a href="{{ route('registrar.enrollments.index') }}" class="hover:text-blue-600">Enrollments</a>
                        <a href="{{ route('registrar.lookup') }}" class="hover:text-blue-600">Lookup</a>
                        <a href="{{ route('exam.schedule.index') }}" class="hover:text-blue-600">Exams</a>
                    @else
                        <a href="{{ route('applicant.admission.create') }}" class="hover:text-blue-600">My Admission</a>
                        <a href="{{ route('applicant.status.form') }}" class="hover:text-blue-600">Check Status</a>
                    @endif
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-2 text-slate-500 hover:text-blue-600">
                        <img src="{{ auth()->user()->profile_photo_url }}"
                             class="w-7 h-7 rounded-full object-cover border border-slate-200" alt="">
                        <span>{{ auth()->user()->name }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-rose-600 hover:underline">Logout</button>
                    </form>
                @else
                    <a href="{{ url('/#mission') }}" class="hover:text-blue-600">Vision &amp; Mission</a>
                    <a href="{{ url('/#values') }}" class="hover:text-blue-600">Core Values</a>
                    <a href="{{ url('/#questions') }}" class="hover:text-blue-600">Questions</a>
                    <a href="{{ route('login') }}" class="hover:text-blue-600">Admin</a>
                    <a href="{{ route('register') }}"
                       class="border border-blue-300 text-blue-700 px-3 py-1.5 rounded hover:bg-blue-50">Register</a>
                    <a href="{{ route('login') }}"
                       class="bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700">LOGIN</a>
                @endauth
            </nav>
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
