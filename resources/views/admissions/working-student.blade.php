@extends('layouts.app')
@section('title', 'Working Student Assistance')

@section('content')
<div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-8 mt-6">
    <div class="border-l-4 border-amber-400 pl-4 mb-6">
        <h1 class="text-3xl font-bold text-[#1D4ED8] tracking-wide">Apply as Working Student</h1>
        <p class="text-sm text-slate-500 mt-1">Support options, application process, and requirements for working students.</p>
    </div>

    <section class="mb-8">
        <h2 class="text-xl font-semibold text-slate-800 mb-3">Available Support</h2>
        <ul class="list-disc list-inside space-y-1 text-slate-700 text-sm">
            <li>Flexible class scheduling (subject to section availability)</li>
            <li>Installment tuition payment arrangement</li>
            <li>Guidance and academic advising for workload planning</li>
        </ul>
    </section>

    <section class="mb-8">
        <h2 class="text-xl font-semibold text-slate-800 mb-3">How to Apply</h2>
        <ol class="list-decimal list-inside space-y-1 text-slate-700 text-sm">
            <li>Inform Admissions or Registrar that you are applying as a working student.</li>
            <li>Submit proof of employment and your preferred study schedule.</li>
            <li>Attend advising for section and load assessment.</li>
            <li>Finalize enrollment after approval.</li>
        </ol>
    </section>

    <section>
        <h2 class="text-xl font-semibold text-slate-800 mb-3">Requirements</h2>
        <ul class="list-disc list-inside space-y-1 text-slate-700 text-sm">
            <li>Certificate of Employment or Employer Endorsement</li>
            <li>Valid Government-issued ID</li>
            <li>Completed Working Student Request Form</li>
            <li>Latest School Records (if transferee or continuing assessment required)</li>
        </ul>
    </section>
</div>
@endsection
