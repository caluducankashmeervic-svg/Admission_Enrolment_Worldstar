@extends('layouts.app')
@section('title', 'Your Application Status')

@section('content')
@php
    $exam = $applicant->latestExamResult;
    $ver  = $applicant->verification;
    $enr  = $applicant->enrollment;

    // Exam-result-aware label/colour for the 4th step.
    if ($exam && $exam->result === 'passed') {
        $examStep = ['Exam Passed', true, 'bg-emerald-50 border-emerald-200 text-emerald-800', '✓'];
    } elseif ($exam && $exam->result === 'failed') {
        $examStep = ['Exam Failed', true, 'bg-rose-50 border-rose-200 text-rose-800', '✕'];
    } else {
        $examStep = ['Exam Score Pending', false, 'bg-slate-50 border-slate-200 text-slate-500', '•'];
    }

    $doneCls    = 'bg-emerald-50 border-emerald-200 text-emerald-800';
    $pendingCls = 'bg-slate-50 border-slate-200 text-slate-500';

    $steps = [
        ['Pre-Registered',      true,                                          null,       null],
        ['Form Approved',       $applicant->status !== 'pre_registered',       null,       null],
        ['Exam Scheduled',      (bool) $exam,                                  null,       null],
        $examStep,
        ['Documents Verified',  $ver && $ver->status === 'verified',           null,       null],
        ['Enrolled',            (bool) $enr,                                   null,       null],
    ];
@endphp

<div class="max-w-3xl mx-auto">
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-semibold">Hello, {{ $applicant->first_name }}!</h1>
        <p class="text-sm text-slate-500">Reference Code:
            <span class="font-mono font-bold text-blue-700">{{ $applicant->reference_code }}</span>
        </p>

        <ol class="mt-6 grid gap-2 grid-cols-2 sm:grid-cols-3 md:grid-cols-6">
            @foreach($steps as [$label, $done, $cls, $icon])
                @php
                    $cellCls = $cls ?? ($done ? $doneCls : $pendingCls);
                    $glyph   = $icon ?? ($done ? '✓' : '•');
                @endphp
                <li class="border rounded p-2 text-center {{ $cellCls }}">
                    <div class="text-lg leading-none">{{ $glyph }}</div>
                    <div class="text-[11px] leading-tight mt-1">{{ $label }}</div>
                </li>
            @endforeach
        </ol>

        <dl class="mt-6 grid md:grid-cols-2 gap-y-2 text-sm">
            <dt class="text-slate-500">Course</dt>
            <dd>{{ $applicant->preferredCourse?->code }} — {{ $applicant->preferredCourse?->name }}</dd>
            <dt class="text-slate-500">Term</dt>
            <dd>{{ $applicant->academicTerm?->school_year }} {{ $applicant->academicTerm?->semester }}</dd>
            <dt class="text-slate-500">Current Status</dt>
            <dd class="capitalize">{{ str_replace('_', ' ', $applicant->status) }}</dd>
        </dl>
    </div>

    @if($exam)
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6 mt-5">
        <h2 class="font-semibold">Entrance Exam</h2>
        <dl class="mt-2 grid md:grid-cols-2 gap-y-1 text-sm">
            <dt class="text-slate-500">Batch</dt>
            <dd class="font-mono">{{ $exam->examSchedule?->batch_code }}</dd>
            <dt class="text-slate-500">When</dt>
            <dd>{{ optional($exam->examSchedule?->exam_datetime)->format('M d, Y g:i A') ?? '—' }}</dd>
            <dt class="text-slate-500">Venue</dt>
            <dd>{{ $exam->examSchedule?->venue ?? '—' }}</dd>
            <dt class="text-slate-500">Score</dt>
            <dd>{{ $exam->score ?? '—' }}</dd>
            <dt class="text-slate-500">Result</dt>
            <dd class="capitalize font-semibold">{{ $exam->result }}</dd>
        </dl>
    </div>
    @endif

    @php
        $inVerificationWindow = $exam && $exam->result === 'passed' && ! $enr;
        $docLabels = [
            'doc_form_137'       => 'Form 137 / SF10',
            'doc_psa_birth_cert' => 'PSA Birth Certificate',
            'doc_good_moral'     => 'Certificate of Good Moral',
            'doc_id_photos'      => '2x2 ID Photos',
            'doc_medical_cert'   => 'Medical Certificate',
            'doc_diploma'        => 'Diploma / Certificate of Graduation',
        ];
    @endphp

    @if($inVerificationWindow)
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6 mt-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h2 class="font-semibold">Required Documents</h2>
                <p class="text-xs text-slate-500 mt-1">
                    Submit these documents to the registrar. This list updates as items are verified.
                </p>
            </div>
            <span class="text-[11px] text-slate-400">auto-refreshes every 10s</span>
        </div>

        @php
            $checked = 0;
            foreach (array_keys($docLabels) as $key) { if ($ver && $ver->{$key}) $checked++; }
            $total = count($docLabels);
        @endphp

        <div class="mt-3 text-xs text-slate-600">
            Progress: <strong>{{ $checked }}</strong> of {{ $total }} verified
            @if($ver)
                · Status:
                <span class="capitalize font-medium
                    @if($ver->status === 'verified') text-emerald-700
                    @elseif($ver->status === 'rejected') text-rose-700
                    @elseif($ver->status === 'incomplete') text-amber-700
                    @else text-slate-700 @endif">
                    {{ str_replace('_', ' ', $ver->status) }}
                </span>
            @else
                · Status: <span class="text-slate-500">not started</span>
            @endif
        </div>

        <ul class="mt-4 divide-y divide-slate-100 border border-slate-100 rounded">
            @foreach($docLabels as $key => $label)
                @php $done = (bool) ($ver?->{$key}); @endphp
                <li class="flex items-center gap-3 px-3 py-2 text-sm">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full border
                        {{ $done ? 'bg-emerald-100 border-emerald-300 text-emerald-700' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                        {{ $done ? '✓' : '○' }}
                    </span>
                    <span class="{{ $done ? 'text-slate-800' : 'text-slate-600' }}">{{ $label }}</span>
                    @if($done)
                        <span class="ml-auto text-[11px] text-emerald-700">Verified</span>
                    @else
                        <span class="ml-auto text-[11px] text-slate-400">Pending</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($ver && $ver->override_reason)
    <div class="bg-amber-50 border border-amber-200 text-amber-900 rounded-lg shadow-sm p-6 mt-5">
        <h2 class="font-semibold">Registrar Override Notice</h2>
        <p class="mt-2 text-sm">
            Although the entrance exam was marked <strong>{{ $exam?->result ?? 'failed' }}</strong>,
            the registrar verified this applicant's documents with the following recorded reason:
        </p>
        <blockquote class="mt-2 border-l-4 border-amber-400 bg-white/60 px-3 py-2 text-sm italic">
            {{ $ver->override_reason }}
        </blockquote>
    </div>
    @endif

    @if($enr)
    <div class="bg-emerald-50 border-2 border-emerald-300 text-emerald-900 rounded-lg shadow-sm p-6 mt-5">
        <div class="flex items-start gap-3">
            <div class="text-3xl leading-none">🎉</div>
            <div>
                <h2 class="text-lg font-semibold">You are officially enrolled!</h2>
                <p class="mt-1 text-sm">
                    Enrollment No. <strong class="font-mono">{{ $enr->enrollment_no }}</strong> —
                    confirmed {{ $enr->enrolled_at?->format('M d, Y g:i A') }}.
                </p>
            </div>
        </div>
    </div>
    @endif

    <a href="{{ route('applicant.status.form') }}" class="inline-block mt-4 text-blue-600 hover:underline">← Check another</a>
</div>

@if($inVerificationWindow)
<script>
    // Refresh the page while the applicant is awaiting document verification
    // so registrar check-offs surface without a manual reload. Pauses when
    // the tab is hidden to avoid wasted requests.
    (function () {
        const INTERVAL_MS = 10000;
        let timer = null;
        function start() {
            stop();
            timer = setTimeout(() => window.location.reload(), INTERVAL_MS);
        }
        function stop() { if (timer) { clearTimeout(timer); timer = null; } }
        document.addEventListener('visibilitychange', () => {
            document.hidden ? stop() : start();
        });
        start();
    })();
</script>
@endif
@endsection
