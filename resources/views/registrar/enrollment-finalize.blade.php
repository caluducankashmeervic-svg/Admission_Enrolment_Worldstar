@extends('layouts.app')
@section('title', 'Finalize Enrollment')

@section('content')
@php
    $exam       = $applicant->latestExamResult;
    $ver        = $applicant->verification;
    $enr        = $applicant->enrollment;
    $canFinalize = ! $enr
        && $applicant->status === \App\Models\Applicant::STATUS_VERIFIED
        && $ver && $ver->status === \App\Models\Verification::STATUS_VERIFIED;
@endphp

<div class="max-w-3xl mx-auto">
    <a href="{{ route('registrar.verify.show', $applicant) }}"
       class="inline-flex items-center gap-1 text-sm text-blue-700 hover:underline mb-4">
        &larr; Back to verification
    </a>

    <h1 class="text-2xl font-semibold text-slate-900">Finalize Enrollment</h1>
    <p class="text-sm text-slate-500 mt-1">
        Confirming enrollment marks the applicant as officially enrolled and the confirmation appears on their status page.
        No section assignment, payment, or COR is required at this step.
    </p>

    @if($errors->any())
        <div class="mt-4 bg-rose-50 border border-rose-200 text-rose-800 rounded p-3 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('status'))
        <div class="mt-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded p-3 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <div class="mt-6 bg-white border border-slate-200 rounded-lg shadow-sm p-5 space-y-4">
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="text-xs uppercase tracking-wide text-slate-500">Applicant</div>
                <div class="text-lg font-semibold text-slate-900">{{ $applicant->full_name }}</div>
                <div class="text-sm text-slate-500">Ref: <span class="font-mono">{{ $applicant->reference_code }}</span></div>
            </div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                @switch($applicant->status)
                    @case('enrolled')   bg-emerald-100 text-emerald-800 @break
                    @case('verified')   bg-blue-100 text-blue-800 @break
                    @case('rejected')   bg-rose-100 text-rose-800 @break
                    @default            bg-slate-100 text-slate-700
                @endswitch">
                {{ str_replace('_', ' ', ucfirst($applicant->status)) }}
            </span>
        </div>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm border-t border-slate-100 pt-4">
            <div>
                <dt class="text-slate-500">Track</dt>
                <dd class="font-medium text-slate-800">
                    @if(in_array($applicant->applicant_type, [\App\Models\Applicant::TYPE_SHS, \App\Models\Applicant::TYPE_TESDA], true))
                        {{ strtoupper($applicant->applicant_type) }}
                        @if($applicant->strand_track) — {{ $applicant->strand_track }}@endif
                    @else
                        College
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-slate-500">Program / Course</dt>
                <dd class="font-medium text-slate-800">
                    {{ $applicant->preferredCourse?->code ?? '—' }}
                    @if($applicant->preferredCourse?->name) — {{ $applicant->preferredCourse->name }} @endif
                </dd>
            </div>
            <div>
                <dt class="text-slate-500">Academic Term</dt>
                <dd class="font-medium text-slate-800">{{ $applicant->academicTerm?->label ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-slate-500">Mobile</dt>
                <dd class="font-medium text-slate-800">{{ $applicant->mobile ?: '— (no number on file)' }}</dd>
            </div>
            <div>
                <dt class="text-slate-500">Entrance Exam</dt>
                <dd class="font-medium
                    @if($exam?->result === 'passed') text-emerald-700
                    @elseif($exam?->result === 'failed') text-rose-700
                    @else text-slate-800 @endif">
                    @if($exam)
                        {{ ucfirst($exam->result) }}
                        @if($ver?->override_reason)
                            <span class="ml-1 px-1.5 py-0.5 text-xs bg-amber-100 text-amber-800 rounded">override</span>
                        @endif
                    @else — @endif
                </dd>
            </div>
            <div>
                <dt class="text-slate-500">Documents</dt>
                <dd class="font-medium
                    {{ $ver?->status === 'verified' ? 'text-emerald-700' : 'text-amber-700' }}">
                    {{ $ver?->status ? ucfirst($ver->status) : 'Not started' }}
                </dd>
            </div>
        </dl>

        @if($ver?->override_reason)
            <div class="bg-amber-50 border border-amber-200 text-amber-900 rounded p-3 text-sm">
                <strong>Override notice on file:</strong> {{ $ver->override_reason }}
            </div>
        @endif
    </div>

    @if($enr)
        <div class="mt-6 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-lg p-5">
            <div class="flex items-center gap-2 font-semibold text-emerald-900">
                <span>✓ Already enrolled</span>
            </div>
            <p class="mt-1 text-sm">
                Enrollment No: <span class="font-mono font-bold">{{ $enr->enrollment_no }}</span>
                — finalized {{ $enr->enrolled_at?->format('M d, Y g:i A') }}.
            </p>
        </div>
    @elseif($canFinalize)
        <form method="POST" action="{{ route('registrar.enrollment.finalize') }}"
              class="mt-6 bg-white border border-emerald-200 rounded-lg shadow-sm p-5"
              onsubmit="return confirm('Finalize enrollment for {{ $applicant->full_name }}? This will mark the applicant as enrolled and cannot be undone.');">
            @csrf
            <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">

            <p class="text-sm text-slate-700">
                By confirming, this applicant becomes officially <strong>enrolled</strong>.
                The confirmation will appear on their status page.
            </p>

            <button type="submit"
                    class="mt-4 w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2.5 rounded">
                Confirm Enrollment
            </button>
        </form>
    @else
        <div class="mt-6 bg-amber-50 border border-amber-200 text-amber-900 rounded-lg p-4 text-sm">
            This applicant is not yet eligible for enrollment. Documents must be fully verified first.
        </div>
    @endif
</div>
@endsection
