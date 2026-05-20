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
            <input type="password" name="password" required
                   class="mt-1 w-full rounded border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
@endsection
