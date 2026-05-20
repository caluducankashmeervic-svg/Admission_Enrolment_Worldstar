@extends('layouts.app')
@section('title', 'Pre-Registration Successful')

@section('content')
<div class="max-w-xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-8 text-center mt-8">
    <div class="text-emerald-600 text-5xl">&#10004;</div>
    <h1 class="text-2xl font-semibold mt-3">Pre-Registration Submitted</h1>
    <p class="text-slate-600 mt-2">
        Thank you, <strong>{{ $applicant->full_name }}</strong>. Keep your reference code safe —
        you will need it to check your exam schedule and pick up your COR.
    </p>

    <div class="mt-6 bg-slate-50 border border-dashed border-slate-300 rounded p-4">
        <p class="text-xs uppercase tracking-widest text-slate-500">Reference Code</p>
        <p class="text-3xl font-mono font-bold text-blue-700 mt-1">{{ $applicant->reference_code }}</p>
    </div>

    <dl class="mt-6 text-left text-sm grid grid-cols-2 gap-y-2">
        <dt class="text-slate-500">Course</dt>
        <dd>{{ $applicant->preferredCourse?->code }} — {{ $applicant->preferredCourse?->name }}</dd>
        <dt class="text-slate-500">Term</dt>
        <dd>{{ $applicant->academicTerm?->school_year }} {{ $applicant->academicTerm?->semester }} Sem</dd>
        <dt class="text-slate-500">Mobile</dt>
        <dd>{{ $applicant->mobile }}</dd>
        <dt class="text-slate-500">Status</dt>
        <dd class="capitalize">{{ str_replace('_', ' ', $applicant->status) }}</dd>
    </dl>

    <a href="{{ route('home') }}" class="inline-block mt-6 text-blue-600 hover:underline">Back to home</a>
</div>
@endsection
