@extends('layouts.app')
@section('title', 'Scholarship Information')

@section('content')
<div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-8 mt-6">
    <div class="border-l-4 border-amber-400 pl-4 mb-6">
        <h1 class="text-3xl font-bold text-[#1D4ED8] tracking-wide">Scholarship</h1>
        <p class="text-sm text-slate-500 mt-1">Available scholarships, how to apply, and documentary requirements.</p>
    </div>

    <section class="mb-8">
        <h2 class="text-xl font-semibold text-slate-800 mb-3">Available Scholarships</h2>
        <ul class="list-disc list-inside space-y-1 text-slate-700 text-sm">
            <li>Academic Merit Scholarship</li>
            <li>Government Assistance Programs (e.g., UNIFAST/TES)</li>
            <li>Institutional Financial Assistance (subject to screening)</li>
        </ul>
    </section>

    <section class="mb-8">
        <h2 class="text-xl font-semibold text-slate-800 mb-3">How to Apply</h2>
        <ol class="list-decimal list-inside space-y-1 text-slate-700 text-sm">
            <li>Complete the pre-registration process and secure your reference code.</li>
            <li>Submit a scholarship application form at the admissions or registrar office.</li>
            <li>Attach all required documents and wait for evaluation and interview schedule.</li>
            <li>Check your application status through the portal or registrar updates.</li>
        </ol>
    </section>

    <section>
        <h2 class="text-xl font-semibold text-slate-800 mb-3">Requirements</h2>
        <ul class="list-disc list-inside space-y-1 text-slate-700 text-sm">
            <li>Latest Report Card or Transcript of Records</li>
            <li>Certificate of Good Moral Character</li>
            <li>Barangay Indigency or Income Proof (if required by program)</li>
            <li>Valid ID and 2x2 ID Photos</li>
            <li>Completed Scholarship Application Form</li>
        </ul>
    </section>
</div>
@endsection
