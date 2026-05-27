@extends('layouts.app')
@section('title', 'TESDA Diploma Courses')

@section('content')
<div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-5 md:p-8 mt-4 md:mt-6">
    <div class="border-l-4 border-amber-400 pl-3 md:pl-4 mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-[#1D4ED8] tracking-wide">TESDA Diploma Courses</h1>
        <p class="text-sm text-slate-500 mt-1">Browse TESDA diploma offerings. Click any course to open its full Academics page.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach ([
            ['CST (Computer Science Technology)', 'cst-computer-science-technology'],
            ['CET (Computer Engineering Technology)', 'cet-computer-engineering-technology'],
            ['EET (Electronics Engineering Technology)', 'eet-electronics-engineering-technology'],
            ['ICT (Information and Communications Technology)', 'ict-information-communications-technology'],
        ] as [$label, $slug])
            <a href="{{ route('academics.program', $slug) }}" class="block p-4 border border-slate-200 rounded-lg hover:border-[#1D4ED8] hover:shadow-sm transition">
                <p class="font-semibold text-slate-800">{{ $label }}</p>
                <p class="text-xs text-slate-500 mt-1">View program details</p>
            </a>
        @endforeach
    </div>
</div>
@endsection
