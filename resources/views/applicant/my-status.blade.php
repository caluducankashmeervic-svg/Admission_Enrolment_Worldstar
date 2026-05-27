@extends('layouts.app')
@section('title', 'Check Status')

@section('content')
<div class="max-w-2xl mx-auto mt-8">

    @if(! $applicant)
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-8 text-center">
            <h1 class="text-xl font-semibold text-slate-800">No Application Found</h1>
            <p class="text-sm text-slate-500 mt-2">
                We couldn't find an application linked to your account.
            </p>
            <a href="{{ route('applicant.pre-register.form') }}"
               class="inline-block mt-4 px-5 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
                Start Pre-Registration
            </a>
        </div>
    @else
        @php
            $steps = [
                'pre_registered'  => ['label' => 'Pre-Registered',  'desc' => 'Form submitted'],
                'exam_scheduled'  => ['label' => 'Exam Scheduled',  'desc' => 'Assigned to a batch'],
                'exam_completed'  => ['label' => 'Exam Completed',  'desc' => 'Score recorded'],
                'verified'        => ['label' => 'Verified',         'desc' => 'Documents cleared'],
                'enrolled'        => ['label' => 'Enrolled',         'desc' => 'Enrollment finalized'],
            ];
            $order  = array_keys($steps);
            $current = $applicant->status;
            $currentIdx = array_search($current, $order);
            $exam = $applicant->latestExamResult;
        @endphp

        <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6 mb-4">
            <div class="flex items-start justify-between flex-wrap gap-3">
                <div>
                    <p class="text-xs uppercase tracking-widest text-slate-500">Reference Code</p>
                    <p class="text-2xl font-mono font-bold text-blue-700">{{ $applicant->reference_code }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold
                    @switch($applicant->status)
                        @case('enrolled')       bg-emerald-100 text-emerald-800 @break
                        @case('verified')       bg-blue-100   text-blue-800   @break
                        @case('rejected')       bg-rose-100   text-rose-800   @break
                        @case('exam_completed') bg-indigo-100 text-indigo-800 @break
                        @case('exam_scheduled') bg-amber-100  text-amber-800  @break
                        @default                bg-slate-100  text-slate-700
                    @endswitch">
                    {{ str_replace('_', ' ', ucfirst($applicant->status)) }}
                </span>
            </div>

            {{-- Step tracker --}}
            @if($applicant->status !== 'rejected')
            <div class="mt-6 flex items-center gap-0 overflow-x-auto">
                @foreach($steps as $key => $step)
                    @php
                        $stepIdx  = array_search($key, $order);
                        $done     = $stepIdx <= ($currentIdx ?? -1) && $current !== 'rejected';
                        $isActive = $key === $current;
                    @endphp
                    <div class="flex items-center min-w-0 @if(!$loop->last) flex-1 @endif">
                        <div class="flex flex-col items-center shrink-0 w-20 text-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                                {{ $done ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                                @if($done && !$isActive)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    {{ $stepIdx + 1 }}
                                @endif
                            </div>
                            <p class="text-[10px] mt-1 leading-tight
                                {{ $isActive ? 'text-blue-700 font-semibold' : ($done ? 'text-slate-700' : 'text-slate-400') }}">
                                {{ $step['label'] }}
                            </p>
                        </div>
                        @if(!$loop->last)
                            <div class="flex-1 h-0.5 mb-4 {{ $stepIdx < ($currentIdx ?? -1) ? 'bg-blue-600' : 'bg-slate-200' }}"></div>
                        @endif
                    </div>
                @endforeach
            </div>
            @else
                <div class="mt-4 rounded bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 text-sm">
                    Your application has been <strong>rejected</strong>. Please contact the registrar for details.
                </div>
            @endif
        </div>

        {{-- Step context notice --}}
        @php
            $notices = [
                'pre_registered'  => ['color' => 'amber',   'icon' => '⏳', 'title' => 'Awaiting Registrar Review', 'body' => 'Your pre-registration form has been submitted and is currently being reviewed by the registrar. No action required on your end.'],
                'exam_scheduled'  => ['color' => 'blue',    'icon' => '✅', 'title' => 'Form Approved — Exam Scheduled', 'body' => 'Your pre-registration form has been approved. Please check your entrance exam batch and schedule below.'],
                'exam_completed'  => ['color' => 'indigo',  'icon' => '📋', 'title' => 'Exam Completed', 'body' => 'Your entrance exam score has been recorded. The registrar will review your documents next.'],
                'verified'        => ['color' => 'emerald', 'icon' => '🎉', 'title' => 'Application Verified', 'body' => 'Congratulations! Your documents have been verified. You are cleared for enrollment.'],
                'enrolled'        => ['color' => 'emerald', 'icon' => '🏫', 'title' => 'Enrollment Complete', 'body' => 'You are officially enrolled. Welcome to Worldstar College of Science and Technology!'],
                'rejected'        => ['color' => 'rose',    'icon' => '❌', 'title' => 'Application Rejected', 'body' => 'Your application has been rejected. Please visit or contact the registrar\'s office for details.'],
            ];
            $notice = $notices[$applicant->status] ?? null;
        @endphp
        @if($notice)
        @php $c = $notice['color']; @endphp
        <div class="mb-4 flex gap-3 items-start rounded-lg border px-4 py-3 text-sm
            bg-{{ $c }}-50 border-{{ $c }}-200 text-{{ $c }}-800">
            <span class="text-base leading-tight shrink-0">{{ $notice['icon'] }}</span>
            <div>
                <p class="font-semibold">{{ $notice['title'] }}</p>
                <p class="mt-0.5 text-{{ $c }}-700">{{ $notice['body'] }}</p>
            </div>
        </div>
        @endif

        {{-- Details card --}}
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6 space-y-4 text-sm">
            <h2 class="font-semibold text-slate-800">Application Details</h2>
            <dl class="grid grid-cols-2 gap-y-3 gap-x-6">
                <div>
                    <dt class="text-slate-500">Name</dt>
                    <dd class="font-medium">{{ $applicant->full_name }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Type</dt>
                    <dd class="font-medium uppercase">{{ $applicant->applicant_type }}</dd>
                </div>
                @if($applicant->preferredCourse)
                <div>
                    <dt class="text-slate-500">Course</dt>
                    <dd class="font-medium">{{ $applicant->preferredCourse->code }} — {{ $applicant->preferredCourse->name }}</dd>
                </div>
                @elseif($applicant->strand_track)
                <div>
                    <dt class="text-slate-500">Track</dt>
                    <dd class="font-medium">{{ $applicant->strand_track }}</dd>
                </div>
                @endif
                @if($applicant->academicTerm)
                <div>
                    <dt class="text-slate-500">Term</dt>
                    <dd class="font-medium">{{ $applicant->academicTerm->school_year }} {{ $applicant->academicTerm->semester }}</dd>
                </div>
                @endif
            </dl>

            @if($exam)
            <div class="border-t border-slate-100 pt-4">
                <h3 class="font-semibold text-slate-700 mb-2">Entrance Exam</h3>
                <dl class="grid grid-cols-2 gap-y-2 gap-x-6">
                    <div>
                        <dt class="text-slate-500">Batch</dt>
                        <dd class="font-mono font-medium">{{ $exam->examSchedule?->batch_code ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Schedule</dt>
                        <dd class="font-medium">{{ $exam->examSchedule?->exam_datetime?->format('M d, Y g:i A') ?? '—' }}</dd>
                    </div>
                    @if($exam->score !== null)
                    <div>
                        <dt class="text-slate-500">Score</dt>
                        <dd class="font-medium">{{ $exam->score }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Result</dt>
                        <dd class="font-medium capitalize
                            {{ $exam->result === 'passed' ? 'text-emerald-700' : ($exam->result === 'failed' ? 'text-rose-700' : 'text-slate-600') }}">
                            {{ $exam->result }}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
            @endif

            @if($applicant->enrollment)
            <div class="border-t border-slate-100 pt-4">
                <h3 class="font-semibold text-slate-700 mb-2">Enrollment</h3>
                <dl class="grid grid-cols-2 gap-y-2 gap-x-6">
                    <div>
                        <dt class="text-slate-500">Section</dt>
                        <dd class="font-medium">{{ $applicant->enrollment->section?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Status</dt>
                        <dd class="font-medium capitalize">{{ $applicant->enrollment->status }}</dd>
                    </div>
                </dl>
            </div>
            @endif
        </div>

        {{-- Document Verification Checklist --}}
        @php
            $ver = $applicant->verification;
            $docLabels = [
                'doc_form_137'       => 'Form 137 / Report Card',
                'doc_psa_birth_cert' => 'PSA Birth Certificate',
                'doc_good_moral'     => 'Certificate of Good Moral',
                'doc_id_photos'      => '2×2 ID Photos',
                'doc_medical_cert'   => 'Medical Certificate',
                'doc_diploma'        => 'Diploma / Certificate of Graduation',
            ];
        @endphp
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6 text-sm">
            <div class="flex items-center justify-between flex-wrap gap-2 mb-4">
                <h2 class="font-semibold text-slate-800">Document Requirements</h2>
                @if($ver)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                        @switch($ver->status)
                            @case('verified')   bg-emerald-100 text-emerald-800 @break
                            @case('rejected')   bg-rose-100 text-rose-800       @break
                            @case('incomplete') bg-amber-100 text-amber-800     @break
                            @default            bg-slate-100 text-slate-600
                        @endswitch">
                        {{ ucfirst($ver->status) }}
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">Not Yet Started</span>
                @endif
            </div>
            <ul class="space-y-2">
                @foreach($docLabels as $key => $label)
                    @php $submitted = $ver && $ver->{$key}; @endphp
                    <li class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $submitted ? 'bg-emerald-500 text-white' : 'border-2 border-slate-300 text-transparent' }}">
                            ✓
                        </span>
                        <span class="{{ $submitted ? 'text-slate-800' : 'text-slate-400' }}">{{ $label }}</span>
                        @if($submitted)
                            <span class="ml-auto text-xs text-emerald-600 font-medium">Submitted</span>
                        @else
                            <span class="ml-auto text-xs text-slate-400">Pending</span>
                        @endif
                    </li>
                @endforeach
            </ul>
            @if($ver && $ver->remarks)
                <div class="mt-4 rounded bg-amber-50 border border-amber-200 text-amber-800 px-3 py-2 text-xs">
                    <span class="font-semibold">Registrar Note:</span> {{ $ver->remarks }}
                </div>
            @endif
            @if(!$ver)
                <p class="mt-3 text-xs text-slate-400">Documents are checked by the registrar during verification. Nothing to submit on your end — just bring originals.</p>
            @endif
        </div>

        <p class="text-center text-xs text-slate-400 mt-4">
            Questions? Contact the registrar's office.
        </p>
    @endif
</div>
@endsection
