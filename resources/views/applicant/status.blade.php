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

    @if($enr)
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-lg shadow-sm p-6 mt-5">
        <h2 class="font-semibold">Enrollment Confirmed</h2>
        <p class="mt-2 text-sm">
            Enrollment No. <strong class="font-mono">{{ $enr->enrollment_no }}</strong> —
            Section {{ $enr->section?->name }}.
        </p>
    </div>
    @endif

    <a href="{{ route('applicant.status.form') }}" class="inline-block mt-4 text-blue-600 hover:underline">← Check another</a>
</div>
@endsection
