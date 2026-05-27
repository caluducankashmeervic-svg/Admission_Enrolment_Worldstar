@extends('layouts.app')
@section('title', 'Vision and Mission')

@section('content')
<div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-5 md:p-8 mt-4 md:mt-6 space-y-8">
    <section>
        <div class="border-l-4 border-amber-400 pl-3 md:pl-4 mb-4">
            <h2 class="text-2xl md:text-3xl font-bold text-[#1D4ED8] tracking-wide">Vision</h2>
        </div>
        <p class="text-sm md:text-[15px] leading-relaxed text-slate-700 text-justify">
            By 2030, Worldstar College of Science and Technology Inc. envisions itself as a
            premier institution of 21<sup>st</sup> century education in Region 2, grounded in
            faith, innovation, sustainability, and inclusivity, committed to shaping globally
            competent and socially responsible professionals.
        </p>
    </section>

    <section>
        <div class="border-l-4 border-amber-400 pl-3 md:pl-4 mb-4">
            <h2 class="text-2xl md:text-3xl font-bold text-[#1D4ED8] tracking-wide">Mission</h2>
        </div>
        <p class="text-sm md:text-[15px] leading-relaxed text-slate-700 text-justify">
            Worldstar College of Science and Technology Inc. provides accessible, values-driven,
            holistic, and transformative education, that produces graduates with academic
            excellence, technical and entrepreneurial skills, ethical leadership, and global
            competitiveness.
        </p>
    </section>
</div>
@endsection
