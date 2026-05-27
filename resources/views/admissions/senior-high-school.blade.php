@extends('layouts.app')
@section('title', 'Senior High School Programs')

@section('content')
<div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-8 mt-6">
    <div class="border-l-4 border-amber-400 pl-4 mb-6">
        <h1 class="text-3xl font-bold text-[#1D4ED8] tracking-wide">Senior High School Programs</h1>
        <p class="text-sm text-slate-500 mt-1">Available strands and course offerings for Senior High School.</p>
    </div>

    <div class="space-y-5 text-slate-700">
        <div class="p-4 border border-slate-200 rounded-lg">
            <h2 class="font-semibold text-lg text-slate-800">STEM (Science, Technology, Engineering, and Mathematics)</h2>
            <p class="mt-1 text-sm">For students aiming for science, engineering, medicine, and technology-related college programs.</p>
        </div>
        <div class="p-4 border border-slate-200 rounded-lg">
            <h2 class="font-semibold text-lg text-slate-800">ABM (Accountancy, Business, and Management)</h2>
            <p class="mt-1 text-sm">For students preparing for business, entrepreneurship, accountancy, and management courses.</p>
        </div>
        <div class="p-4 border border-slate-200 rounded-lg">
            <h2 class="font-semibold text-lg text-slate-800">HUMSS (Humanities and Social Sciences)</h2>
            <p class="mt-1 text-sm">For students interested in communication, public service, law, education, and social studies.</p>
        </div>
        <div class="p-4 border border-slate-200 rounded-lg">
            <h2 class="font-semibold text-lg text-slate-800">GAS (General Academic Strand)</h2>
            <p class="mt-1 text-sm">A flexible strand for students still exploring their preferred college specialization.</p>
        </div>
    </div>
</div>
@endsection
