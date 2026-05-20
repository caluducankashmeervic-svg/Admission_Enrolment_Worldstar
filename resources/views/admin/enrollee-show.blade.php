@extends('layouts.app')
@section('title', 'Applicant Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <a href="{{ route('admin.enrollees.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to list</a>

    <div class="mt-3 bg-white border border-slate-200 rounded-lg shadow-sm">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-4">
            <img src="{{ $applicant->user?->profile_photo_url
                ?? 'https://ui-avatars.com/api/?name='.urlencode($applicant->full_name).'&background=e2e8f0&color=475569' }}"
                 class="w-16 h-16 rounded-full object-cover border border-slate-200">
            <div class="flex-1">
                <h1 class="text-xl font-bold text-slate-900">{{ $applicant->full_name }}</h1>
                <p class="text-sm text-slate-500">
                    Ref: <span class="font-mono">{{ $applicant->reference_code }}</span>
                    · Student No: <span class="font-mono">{{ $applicant->user?->student_no ?? '—' }}</span>
                </p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs uppercase bg-slate-100 text-slate-700">
                {{ str_replace('_', ' ', $applicant->status) }}
            </span>
        </div>

        <dl class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div><dt class="text-slate-500">Email</dt><dd class="font-medium">{{ $applicant->email ?: '—' }}</dd></div>
            <div><dt class="text-slate-500">Mobile</dt><dd class="font-medium">{{ $applicant->mobile ?: '—' }}</dd></div>
            <div><dt class="text-slate-500">Preferred Course</dt><dd class="font-medium">{{ optional($applicant->preferredCourse)->code }} — {{ optional($applicant->preferredCourse)->name }}</dd></div>
            <div><dt class="text-slate-500">Academic Term</dt><dd class="font-medium">{{ optional($applicant->academicTerm)->school_year }} · {{ optional($applicant->academicTerm)->semester }}</dd></div>
            <div><dt class="text-slate-500">Exam Score</dt><dd class="font-medium">{{ optional($applicant->latestExamResult)->score ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">Verification</dt><dd class="font-medium">{{ optional($applicant->verification)->status ?? '—' }}</dd></div>
        </dl>

        <div class="px-6 py-4 border-t border-slate-100 flex gap-2">
            <form method="POST" action="{{ route('admin.enrollees.confirm', $applicant) }}"
                  onsubmit="return confirm('Confirm this applicant?')">
                @csrf
                <button class="px-4 py-2 rounded-md bg-emerald-600 text-white text-sm hover:bg-emerald-700">Confirm</button>
            </form>
            <form method="POST" action="{{ route('admin.enrollees.deny', $applicant) }}"
                  onsubmit="return confirm('Deny this applicant?')">
                @csrf
                <button class="px-4 py-2 rounded-md bg-rose-600 text-white text-sm hover:bg-rose-700">Deny</button>
            </form>
        </div>
    </div>
</div>
@endsection
