@extends('layouts.app')
@section('title', 'Senior High School Programs')

@section('content')
<div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-5 md:p-8 mt-4 md:mt-6">
    <div class="border-l-4 border-amber-400 pl-3 md:pl-4 mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-[#1D4ED8] tracking-wide">Senior High School Programs</h1>
        <p class="text-sm text-slate-500 mt-1">Browse all Senior High School offerings. Click any program to open its full Academics page.</p>
    </div>

    <div class="space-y-6">
        <section>
            <h2 class="text-lg font-semibold text-slate-800 mb-3">SHS Academic Track</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach ([
                    ['Arts, Social Sciences, and Humanities', 'arts-social-sciences-humanities'],
                    ['Business and Entrepreneurship', 'business-and-entrepreneurship'],
                    ['Science, Technology, Engineering & Mathematics', 'science-technology-engineering-mathematics'],
                ] as [$label, $slug])
                    <a href="{{ route('academics.program', $slug) }}" class="block p-4 border border-slate-200 rounded-lg hover:border-[#1D4ED8] hover:shadow-sm transition">
                        <p class="font-semibold text-slate-800">{{ $label }}</p>
                        <p class="text-xs text-slate-500 mt-1">View program details</p>
                    </a>
                @endforeach
            </div>
        </section>

        <section>
            <h2 class="text-lg font-semibold text-slate-800 mb-3">SHS Tech-Pro Track</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach ([
                    ['Automotive and Small Engine Technologies', 'automotive-small-engine-technologies'],
                    ['Business, Hospitality, and Tourism Bundle', 'business-hospitality-tourism'],
                    ['Creative Arts and Design Technologies Bundle', 'creative-arts-design-technologies'],
                    ['ICT and Computer Programming Technologies Bundle', 'ict-computer-programming-technologies'],
                    ['Industrial Arts Bundle', 'industrial-arts'],
                ] as [$label, $slug])
                    <a href="{{ route('academics.program', $slug) }}" class="block p-4 border border-slate-200 rounded-lg hover:border-[#1D4ED8] hover:shadow-sm transition">
                        <p class="font-semibold text-slate-800">{{ $label }}</p>
                        <p class="text-xs text-slate-500 mt-1">View program details</p>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection
