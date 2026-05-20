@extends('layouts.app')
@section('title', 'Finalize Enrollment')

@section('content')
<h1 class="text-2xl font-semibold">Finalize Enrollment</h1>
<p class="text-sm text-slate-500">Applicant: <strong>{{ $applicant->full_name }}</strong> ({{ $applicant->reference_code }})</p>

@if($applicant->enrollment)
    <div class="mt-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded p-4">
        Already enrolled — <strong>{{ $applicant->enrollment->enrollment_no }}</strong>.
        <a href="{{ route('enrollment.cor.download', $applicant->enrollment) }}"
           class="ml-2 underline">Download COR</a>
    </div>
@else
<form method="POST" action="{{ route('registrar.enrollment.finalize') }}" class="mt-6 max-w-lg bg-white border border-slate-200 rounded-lg shadow-sm p-5 space-y-4">
    @csrf
    <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
    <input type="hidden" name="course_id" value="{{ $applicant->preferred_course_id }}">

    <div>
        <span class="text-sm text-slate-500">Course</span>
        <p class="font-semibold">{{ $applicant->preferredCourse?->code }} — {{ $applicant->preferredCourse?->name }}</p>
    </div>

    <label class="block">
        <span class="text-sm font-medium">Section</span>
        <select name="section_id" class="mt-1 w-full border rounded px-3 py-2">
            <option value="">Auto-assign available section</option>
            @foreach($sections as $s)
                <option value="{{ $s->id }}" @disabled(! $s->hasSlot())>
                    {{ $s->name }} — Year {{ $s->year_level }}
                    ({{ $s->enrolled_count }}/{{ $s->capacity }})
                    @if(! $s->hasSlot()) [FULL] @endif
                </option>
            @endforeach
        </select>
    </label>

    <button class="w-full bg-emerald-600 text-white py-2.5 rounded hover:bg-emerald-700">
        Finalize & Generate COR
    </button>
</form>
@endif

<a href="{{ route('registrar.verify.show', $applicant) }}" class="inline-block mt-4 text-blue-600 hover:underline">← Back to verification</a>
@endsection
