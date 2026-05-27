@extends('layouts.app')
@section('title', 'Core Values')

@section('content')
<div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-5 md:p-8 mt-4 md:mt-6">
    <div class="border-l-4 border-amber-400 pl-3 md:pl-4 mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-[#1D4ED8] tracking-wide">Core Values</h1>
        <p class="text-sm text-slate-500 mt-1">W.C.S.T. — Wisdom · Compassion · Spirituality · Teamwork</p>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        @foreach ([
            ['W', 'isdom',     'We make sound decisions and judgments based on knowledge, experience, and understanding.'],
            ['C', 'ompassion', 'We encompass kindness, charity, and a genuine desire to help and support those in need.'],
            ['S', 'pirituality','We foster inner peace, moral integrity, and a profound awareness of one\'s purpose within the community.'],
            ['T', 'eamwork',   'We emphasize collaboration, respect, communication, and the ability to work effectively with others.'],
        ] as [$initial, $rest, $desc])
            <div class="border border-slate-200 rounded-lg p-5 hover:border-[#1D4ED8] transition">
                <h3 class="text-xl md:text-2xl font-bold text-slate-800 mb-2">
                    <span class="text-[#1D4ED8]">{{ $initial }}</span>{{ $rest }}
                </h3>
                <p class="text-sm md:text-[15px] leading-relaxed text-slate-700">{{ $desc }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection
