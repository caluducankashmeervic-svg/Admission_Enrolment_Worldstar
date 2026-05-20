@extends('layouts.app')
@section('title', 'Check Application Status')

@section('content')
<div class="max-w-xl mx-auto mt-8 bg-white border border-slate-200 rounded-lg shadow-sm p-6">
    <h1 class="text-2xl font-semibold">Check Your Application Status</h1>
    <p class="text-sm text-slate-500 mt-1">Enter the reference code you received after pre-registration.</p>

    <form method="POST" action="{{ route('applicant.status.check') }}" class="mt-5 flex gap-3">
        @csrf
        <input name="reference_code" value="{{ old('reference_code') }}" placeholder="APP-XXXXXX"
               required autofocus class="flex-1 border rounded px-3 py-2 font-mono uppercase">
        <button class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">Check</button>
    </form>

    <p class="mt-4 text-xs text-slate-500">
        Don't have a code yet?
        <a href="{{ route('applicant.pre-register.form') }}" class="text-blue-600 underline">Pre-register first</a>.
    </p>
</div>
@endsection
