@extends('layouts.app')
@section('title', 'TESDA Diploma Courses')

@section('content')
<div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-5 md:p-8 mt-4 md:mt-6">
    <div class="border-l-4 border-amber-400 pl-3 md:pl-4 mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-[#1D4ED8] tracking-wide">TESDA Diploma Courses</h1>
        <p class="text-sm text-slate-500 mt-1">Technical-vocational diploma programs offered under TESDA.</p>
    </div>

    <div class="space-y-5 text-slate-700">
        <div class="p-4 border border-slate-200 rounded-lg">
            <h2 class="font-semibold text-base md:text-lg text-slate-800">CST (Computer Science Technology)</h2>
            <p class="mt-1 text-sm">Focuses on software fundamentals, system analysis, and practical coding skills.</p>
        </div>
        <div class="p-4 border border-slate-200 rounded-lg">
            <h2 class="font-semibold text-base md:text-lg text-slate-800">CET (Computer Engineering Technology)</h2>
            <p class="mt-1 text-sm">Covers hardware servicing, computer architecture, and system integration.</p>
        </div>
        <div class="p-4 border border-slate-200 rounded-lg">
            <h2 class="font-semibold text-base md:text-lg text-slate-800">EET (Electronics Engineering Technology)</h2>
            <p class="mt-1 text-sm">Builds competencies in electronics, instrumentation, and troubleshooting.</p>
        </div>
        <div class="p-4 border border-slate-200 rounded-lg">
            <h2 class="font-semibold text-base md:text-lg text-slate-800">ICT (Information and Communications Technology)</h2>
            <p class="mt-1 text-sm">Develops skills in networking, IT support, communications systems, and digital tools.</p>
        </div>
    </div>
</div>
@endsection
