@extends('layouts.app')
@section('title', 'Scholarship Information')

@section('content')
<div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-5 md:p-8 mt-4 md:mt-6">
    <div class="border-l-4 border-amber-400 pl-3 md:pl-4 mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-[#1D4ED8] tracking-wide">Scholarship</h1>
        <p class="text-sm text-slate-500 mt-1">Available scholarship grants, application process, and documentary requirements.</p>
    </div>

    <section class="mb-8">
        <h2 class="text-lg md:text-xl font-semibold text-slate-800 mb-3">Available Scholarships</h2>
        <ul class="list-disc list-inside space-y-1 text-slate-700 text-sm">
            <li>Academic Merit Scholarship for qualified high-performing students</li>
            <li>Government Assistance Programs (such as UNIFAST/TES, subject to government slots)</li>
            <li>Institutional Financial Assistance for students who pass screening and interview</li>
        </ul>
    </section>

    <section class="mb-8">
        <h2 class="text-lg md:text-xl font-semibold text-slate-800 mb-3">How to Apply</h2>
        <ol class="list-decimal list-inside space-y-1 text-slate-700 text-sm">
            <li>Complete the pre-registration process and secure your reference code.</li>
            <li>Submit the scholarship application form to Admissions or the Registrar.</li>
            <li>Attach all required documents and wait for evaluation/interview schedule.</li>
            <li>Check your application status through the portal or registrar updates.</li>
        </ol>
    </section>

    <section>
        <h2 class="text-lg md:text-xl font-semibold text-slate-800 mb-3">Requirements</h2>
        <ul class="list-disc list-inside space-y-1 text-slate-700 text-sm">
            <li>Latest Report Card (for incoming students) or Transcript of Records (for transferees)</li>
            <li>Certificate of Good Moral Character</li>
            <li>Barangay Indigency or Income Proof (if required by program)</li>
            <li>Valid ID and two 2x2 ID photos</li>
            <li>Completed Scholarship Application Form</li>
        </ul>
    </section>
</div>
@endsection
