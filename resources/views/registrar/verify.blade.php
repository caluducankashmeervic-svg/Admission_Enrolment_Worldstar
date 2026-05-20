@extends('layouts.app')
@section('title', 'Verify — ' . $applicant->reference_code)

@section('content')
@php
    $verification = $applicant->verification;
    $exam         = $applicant->latestExamResult;
@endphp

<div class="grid lg:grid-cols-3 gap-5">
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5">
        <h2 class="font-semibold mb-3">Applicant</h2>
        <dl class="text-sm grid grid-cols-[110px_1fr] gap-y-1.5">
            <dt class="text-slate-500">Ref Code</dt>
            <dd class="font-mono font-bold text-blue-700">{{ $applicant->reference_code }}</dd>
            <dt class="text-slate-500">Name</dt><dd>{{ $applicant->full_name }}</dd>
            <dt class="text-slate-500">Course</dt><dd>{{ $applicant->preferredCourse?->code }}</dd>
            <dt class="text-slate-500">Term</dt>
            <dd>{{ $applicant->academicTerm?->school_year }} {{ $applicant->academicTerm?->semester }}</dd>
            <dt class="text-slate-500">Mobile</dt><dd>{{ $applicant->mobile }}</dd>
            <dt class="text-slate-500">Status</dt>
            <dd class="capitalize">{{ str_replace('_', ' ', $applicant->status) }}</dd>
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

    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-lg shadow-sm p-5">
        <h2 class="font-semibold mb-3">Document Verification</h2>
        <form method="POST" action="{{ route('registrar.verify.store', $applicant) }}" class="space-y-3">
            @csrf
            @php
                $docs = [
                    'doc_form_137'       => 'Form 137 / TOR',
                    'doc_psa_birth_cert' => 'PSA Birth Certificate',
                    'doc_good_moral'     => 'Good Moral Certificate',
                    'doc_id_photos'      => '2×2 ID Photos',
                    'doc_medical_cert'   => 'Medical Certificate',
                    'doc_diploma'        => 'Diploma / Certificate of Graduation',
                ];
            @endphp
            <div class="grid sm:grid-cols-2 gap-2">
                @foreach($docs as $key => $label)
                    <label class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded px-3 py-2">
                        <input type="checkbox" name="{{ $key }}" value="1"
                               @checked(optional($verification)->$key)>
                        <span class="text-sm">{{ $label }}</span>
                    </label>
                @endforeach
            </div>

            <label class="block">
                <span class="text-sm font-medium">Remarks</span>
                <textarea name="remarks" rows="3" class="mt-1 w-full border rounded px-3 py-2"
                          >{{ optional($verification)->remarks }}</textarea>
            </label>

            <div class="flex items-center justify-between">
                <div class="text-sm">
                    @if($verification)
                        Status:
                        @php $cls = ['verified'=>'emerald','incomplete'=>'amber','rejected'=>'rose','pending'=>'slate'][$verification->status] ?? 'slate'; @endphp
                        <span class="text-{{ $cls }}-700 bg-{{ $cls }}-100 rounded px-2 py-0.5 text-xs capitalize">
                            {{ $verification->status }}
                        </span>
                    @endif
                </div>
                <button class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">
                    Save Verification
                </button>
            </div>
        </form>
    </div>
</div>

<a href="{{ route('registrar.lookup') }}" class="inline-block mt-4 text-blue-600 hover:underline">← Another lookup</a>
@endsection
