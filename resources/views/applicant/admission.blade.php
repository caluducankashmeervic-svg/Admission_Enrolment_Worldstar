@extends('layouts.app')
@section('title', 'My Admission')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-semibold text-slate-900">My Admission</h1>
        <p class="text-sm text-slate-500 mt-1">
            Read-only summary of your submitted application. For the latest progress, visit
            <a href="{{ route('applicant.status.form') }}" class="text-blue-700 hover:underline font-medium">Check Status</a>.
        </p>

        @if(session('status'))
            <div class="mt-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded p-3 text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if(! $applicant)
            <div class="mt-6 bg-amber-50 border border-amber-200 text-amber-900 rounded-lg p-5">
                <h2 class="font-semibold">No application on file</h2>
                <p class="mt-1 text-sm">
                    We couldn't find an admission record linked to your account. If you have a
                    reference code, you can check its status below, or start a new pre-registration.
                </p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('applicant.status.form') }}"
                       class="inline-flex items-center px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium">
                        Check Status
                    </a>
                    <a href="{{ route('applicant.pre-register.form') }}"
                       class="inline-flex items-center px-4 py-2 rounded border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium">
                        Start Pre-Registration
                    </a>
                </div>
            </div>
        @else
            <div class="mt-6 border border-slate-200 rounded-lg p-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-xs uppercase tracking-wide text-slate-500">Reference Code</div>
                        <div class="text-lg font-mono font-bold text-blue-700">{{ $applicant->reference_code }}</div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                        @switch($applicant->status)
                            @case('enrolled')        bg-emerald-100 text-emerald-800 @break
                            @case('verified')        bg-blue-100 text-blue-800 @break
                            @case('rejected')        bg-rose-100 text-rose-800 @break
                            @case('exam_completed')  bg-indigo-100 text-indigo-800 @break
                            @case('exam_scheduled')  bg-amber-100 text-amber-800 @break
                            @default                 bg-slate-100 text-slate-700
                        @endswitch">
                        {{ str_replace('_', ' ', ucfirst($applicant->status)) }}
                    </span>
                </div>

                <dl class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6 text-sm border-t border-slate-100 pt-4">
                    <div>
                        <dt class="text-slate-500">Applicant Name</dt>
                        <dd class="font-medium text-slate-800">{{ $applicant->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Email</dt>
                        <dd class="font-medium text-slate-800">{{ $applicant->email ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Mobile</dt>
                        <dd class="font-medium text-slate-800">{{ $applicant->mobile ?: '—' }}</dd>
                    </div>
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
                            @if($applicant->preferredCourse)
                                {{ $applicant->preferredCourse->code }} — {{ $applicant->preferredCourse->name }}
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Academic Term</dt>
                        <dd class="font-medium text-slate-800">
                            {{ $applicant->academicTerm?->school_year ?? '—' }}
                            {{ $applicant->academicTerm?->semester }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Submitted</dt>
                        <dd class="font-medium text-slate-800">
                            {{ $applicant->created_at?->format('M d, Y g:i A') ?? '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Last Updated</dt>
                        <dd class="font-medium text-slate-800">
                            {{ $applicant->updated_at?->format('M d, Y g:i A') ?? '—' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="mt-5 flex flex-wrap gap-2">
                <a href="{{ route('applicant.status.form') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium">
                    Check Status
                </a>
                <p class="text-xs text-slate-500 self-center">
                    Need to update any information? Please contact the registrar.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
