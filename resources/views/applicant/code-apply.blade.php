@extends('layouts.app')
@section('title', 'Reference Code Apply')

@section('content')
<div class="max-w-md mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-8 mt-10">
    <div class="border-l-4 border-amber-400 pl-4 mb-6">
        <h1 class="text-2xl font-bold text-[#1D4ED8] tracking-wide">Reference Code Apply</h1>
        <p class="text-sm text-slate-500 mt-1">
            Enter the reference code you received after submitting your pre-registration form.
            After verification, you'll create an account so this code is bound to it and you can
            track your enrollment progress.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-700 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('applicant.code.check') }}" class="space-y-4">
        @csrf
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Reference Code *</span>
            <input name="reference_code"
                   required
                   value="{{ old('reference_code') }}"
                   placeholder="e.g. SHS-2026-000123"
                   class="mt-1 w-full rounded border border-slate-300 px-3 py-2 uppercase tracking-wider
                          focus:outline-none focus:ring-2 focus:ring-[#1D4ED8]">
        </label>

        <label class="block">
            <span class="text-sm font-medium text-slate-700">Last Name *</span>
            <input name="last_name"
                   required
                   value="{{ old('last_name') }}"
                   class="mt-1 w-full rounded border border-slate-300 px-3 py-2
                          focus:outline-none focus:ring-2 focus:ring-[#1D4ED8]">
        </label>

        <label class="block">
            <span class="text-sm font-medium text-slate-700">Birthdate *</span>
            <input type="date" name="birth_date"
                   required
                   value="{{ old('birth_date') }}"
                   class="mt-1 w-full rounded border border-slate-300 px-3 py-2
                          focus:outline-none focus:ring-2 focus:ring-[#1D4ED8]">
        </label>
        <p class="text-xs text-slate-500">Your last name and birthdate must match the pre-registration form for this code.</p>

        <button class="w-full bg-[#1D4ED8] text-white font-semibold px-5 py-2.5 rounded
                       hover:bg-[#1e40af] transition-colors">
            Verify &amp; Continue
        </button>
    </form>

    <p class="text-xs text-slate-500 mt-6 text-center">
        Don't have a reference code yet?
        <a href="{{ route('applicant.pre-register.choose') }}" class="text-[#1D4ED8] hover:underline">
            Submit a pre-registration form
        </a>.
    </p>
</div>
@endsection
