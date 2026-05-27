@extends('layouts.app')
@section('title', 'Verify — ' . $applicant->reference_code)

@section('content')
@php
    $verification = $applicant->verification;
    $exam         = $applicant->latestExamResult;

    // Program / track: derive from whichever field the pre-registration form populated.
    if ($applicant->preferredCourse) {
        $programLabel = 'Course';
        $programValue = $applicant->preferredCourse->code
            . ($applicant->preferredCourse->name ? ' — ' . $applicant->preferredCourse->name : '');
    } elseif ($applicant->applicant_type === \App\Models\Applicant::TYPE_SHS) {
        $programLabel = 'SHS Track';
        $programValue = $applicant->strand_track ?: '—';
    } elseif ($applicant->applicant_type === \App\Models\Applicant::TYPE_TESDA) {
        $programLabel = 'Diploma Course';
        $programValue = data_get($applicant->profile_data, 'diploma_course')
            ?: data_get($applicant->profile_data, 'course_qualification')
            ?: '—';
    } else {
        $programLabel = 'Program';
        $programValue = '—';
    }
@endphp

<div class="grid lg:grid-cols-3 gap-5">
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5">
        <h2 class="font-semibold mb-3">Applicant</h2>
        <dl class="text-sm grid grid-cols-[110px_1fr] gap-y-1.5">
            <dt class="text-slate-500">Ref Code</dt>
            <dd class="font-mono font-bold text-blue-700">{{ $applicant->reference_code }}</dd>
            <dt class="text-slate-500">Name</dt><dd>{{ $applicant->full_name }}</dd>
            <dt class="text-slate-500">{{ $programLabel }}</dt><dd>{{ $programValue }}</dd>
            @if($applicant->academicTerm)
                <dt class="text-slate-500">Term</dt>
                <dd>{{ $applicant->academicTerm->school_year }} {{ $applicant->academicTerm->semester }}</dd>
            @endif
            <dt class="text-slate-500">Mobile</dt><dd>{{ $applicant->mobile }}</dd>
            <dt class="text-slate-500">Status</dt>
            <dd class="capitalize">{{ str_replace('_', ' ', $applicant->status) }}</dd>
            <dt class="text-slate-500">Form</dt>
            <dd>
                @if($applicant->status === 'pre_registered')
                    <span class="bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded">Awaiting approval</span>
                @else
                    <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-0.5 rounded">Approved</span>
                @endif
            </dd>
        </dl>

        <h3 class="font-semibold mt-5 mb-2 text-sm">Exam</h3>
        @if($exam)
            <p class="text-sm">
                Batch: <span class="font-mono">{{ $exam->examSchedule->batch_code }}</span><br>
                Score: <strong>{{ $exam->score ?? '—' }}</strong> —
                <span class="capitalize">{{ $exam->result }}</span>
            </p>
        @else
            <p class="text-sm text-slate-500">No exam record.</p>
        @endif

        @if($applicant->status === 'verified' || ($exam && $exam->result === 'passed' && $verification && $verification->status === 'verified'))
            <a href="{{ route('registrar.enrollment.show', $applicant) }}"
               class="mt-5 block text-center bg-emerald-600 text-white py-2 rounded hover:bg-emerald-700">
                Proceed to Enrollment →
            </a>
        @endif
    </div>

    <div class="lg:col-span-2 space-y-5">
        {{-- Always-visible submitted form + status controls --}}
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5">
            <div class="flex items-start justify-between flex-wrap gap-2">
                <h2 class="font-semibold">Submitted Pre-Registration Form</h2>
                <span class="text-xs px-2 py-0.5 rounded capitalize
                    @class([
                        'bg-slate-100 text-slate-700'     => $applicant->status === 'pre_registered',
                        'bg-sky-100 text-sky-800'         => $applicant->status === 'exam_scheduled',
                        'bg-amber-100 text-amber-800'    => $applicant->status === 'exam_completed',
                        'bg-blue-100 text-blue-800'       => $applicant->status === 'verified',
                        'bg-emerald-100 text-emerald-800' => $applicant->status === 'enrolled',
                        'bg-rose-100 text-rose-800'       => $applicant->status === 'rejected',
                    ])">
                    {{ str_replace('_', ' ', $applicant->status) }}
                </span>
            </div>

            @error('approve')
                <div class="mt-3 rounded bg-rose-50 border border-rose-200 text-rose-800 px-3 py-2 text-sm">{{ $message }}</div>
            @enderror
            @error('reject')
                <div class="mt-3 rounded bg-rose-50 border border-rose-200 text-rose-800 px-3 py-2 text-sm">{{ $message }}</div>
            @enderror

            <details class="border border-slate-200 rounded mt-3" {{ $applicant->status === 'pre_registered' ? 'open' : '' }}>
                <summary class="cursor-pointer px-3 py-2 bg-slate-50 text-sm font-medium">
                    View submitted form
                </summary>
                <div class="p-4">
                    @if($applicant->applicant_type === \App\Models\Applicant::TYPE_SHS)
                        @include('registrar.partials._shs_form', ['applicant' => $applicant])
                    @else
                        @include('registrar.partials._tesda_form', ['applicant' => $applicant])
                    @endif
                </div>
            </details>

            <div class="mt-4 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-4">
                <a href="{{ route('registrar.applicants.edit', $applicant) }}"
                   class="inline-flex items-center gap-1.5 bg-slate-100 border border-slate-300 text-slate-700 text-sm px-4 py-2 rounded hover:bg-slate-200">
                    ✏ Edit Applicant
                </a>

                @if($applicant->status === 'pre_registered')
                    <form method="POST" action="{{ route('registrar.approve', $applicant) }}">
                        @csrf
                        <button class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 text-sm"
                                onclick="return confirm('Approve this pre-registration form? The applicant will be auto-assigned to the earliest open exam batch.');">
                            Approve Pre-Registration Form
                        </button>
                    </form>
                @endif

                @if(in_array($applicant->status, ['pre_registered','exam_scheduled','exam_completed','verified']))
                    <details class="relative">
                        <summary class="cursor-pointer text-sm bg-rose-600 text-white px-4 py-2 rounded hover:bg-rose-700">
                            Reject Application
                        </summary>
                        <form method="POST" action="{{ route('registrar.reject', $applicant) }}"
                              class="absolute right-0 mt-2 w-80 bg-white border border-slate-200 rounded shadow-lg p-3 z-10 space-y-2">
                            @csrf
                            <label class="block text-xs font-medium">Reason (required)</label>
                            <textarea name="reason" rows="3" required maxlength="500"
                                      class="w-full border rounded px-2 py-1.5 text-sm"
                                      placeholder="Explain why this application is being rejected"></textarea>
                            <p class="text-[11px] text-slate-500">
                                @if($exam)
                                    Rejecting will also remove the applicant from exam batch
                                    <span class="font-mono">{{ $exam->examSchedule?->batch_code }}</span> and free the slot.
                                @endif
                            </p>
                            <button class="w-full bg-rose-600 text-white text-sm py-1.5 rounded hover:bg-rose-700"
                                    onclick="return confirm('Reject this application? This cannot be undone from the UI.');">
                                Confirm Rejection
                            </button>
                        </form>
                    </details>
                @elseif($applicant->status === 'rejected')
                    <span class="text-xs text-rose-700 bg-rose-50 border border-rose-200 rounded px-3 py-1.5">
                        Application rejected. Reason logged in audit trail.
                    </span>
                @endif
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5">
        <h2 class="font-semibold mb-3">Document Verification</h2>

        @if(! $exam)
            <div class="rounded-md border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                <p class="font-semibold mb-1">Pending exam assignment.</p>
                <p>Document verification unlocks once the pre-registration form is approved and the applicant has completed the entrance exam.</p>
            </div>
        @elseif($exam->result === 'pending')
            <div class="rounded-md border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                <p class="font-semibold mb-1">Exam not yet scored.</p>
                <p>This applicant's entrance exam has not been scored. Documents cannot be verified until a score is recorded.</p>
            </div>
        @else
            @php $isFailed = $exam->result === 'failed'; @endphp

            @if($isFailed)
                <div class="rounded-md border border-rose-300 bg-rose-50 px-4 py-3 text-sm text-rose-900">
                    <p class="font-semibold mb-1">Applicant failed the entrance exam (score {{ $exam->score ?? '—' }}).</p>
                    <p>Document verification is locked. By policy, only applicants who passed may be verified for enrollment.</p>
                    @if(optional($verification)->override_reason)
                        <p class="mt-2"><strong>Existing override on file:</strong> {{ $verification->override_reason }}</p>
                    @endif
                </div>

                <details class="mt-3 border border-rose-200 rounded">
                    <summary class="cursor-pointer px-3 py-2 bg-rose-50 text-sm font-medium text-rose-800">
                        Override and verify anyway (requires reason)
                    </summary>
                    <div class="p-4">
                        @include('registrar.partials._verify_form', ['requireOverride' => true])
                    </div>
                </details>
            @else
                @include('registrar.partials._verify_form', ['requireOverride' => false])
            @endif
        @endif
        </div>
    </div>
</div>

<a href="{{ route('registrar.applicants.index') }}" class="inline-block mt-4 text-blue-600 hover:underline">← Back to applicants</a>
@endsection
