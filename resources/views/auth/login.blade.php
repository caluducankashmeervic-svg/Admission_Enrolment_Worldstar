@extends('layouts.app')
@section('title', 'Staff Login')

@section('content')
<div class="max-w-md mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-6 mt-8">
    <h1 class="text-xl font-semibold mb-4"></h1>
    <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="mt-1 w-full rounded border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium">Password</label>
            <div class="relative mt-1">
                <input type="password" name="password" id="password-input" required
                       class="w-full rounded border border-slate-300 px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" id="toggle-password"
                        aria-label="Show password"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none">
                    <svg id="eye-show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg id="eye-hide" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M3 3l18 18"/>
                    </svg>
                </button>
            </div>
        </div>
        <label class="inline-flex items-center text-sm">
            <input type="checkbox" name="remember" class="rounded border-slate-300">
            <span class="ml-2">Remember me</span>
        </label>
        <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Sign in</button>
    </form>
    <p class="mt-4 text-xs text-slate-500 text-center">
        Applicants apply <a href="{{ route('applicant.pre-register.form') }}" class="text-blue-600 underline">here</a>.
    </p>
</div>
<script>
    (function () {
        var btn   = document.getElementById('toggle-password');
        var input = document.getElementById('password-input');
        var show  = document.getElementById('eye-show');
        var hide  = document.getElementById('eye-hide');
        if (!btn || !input) return;
        btn.addEventListener('click', function () {
            var isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            show.classList.toggle('hidden', isHidden);
            hide.classList.toggle('hidden', !isHidden);
            btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });
    })();
</script>
@endsection
