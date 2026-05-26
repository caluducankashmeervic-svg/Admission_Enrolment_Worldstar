@extends('layouts.app')
@section('title', 'Choose Program')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <div class="text-center mb-10">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Applicant Pre-Registration</h1>
        <p class="text-slate-600 mt-2">Choose the program you wish to apply for.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        {{-- Senior High School --}}
        <a href="{{ route('applicant.pre-register.shs.form') }}"
           class="group block bg-white border-2 border-slate-200 hover:border-blue-600 rounded-xl p-8 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-700 mb-4 group-hover:bg-blue-600 group-hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-slate-800 group-hover:text-blue-700">Senior High School</h2>
            <p class="text-sm text-slate-600 mt-2">
                DepEd K-12 Grade 11 &amp; 12 program. Choose a strand: STEM, ABM, TVL, HUMSS, or GAS.
            </p>
            <span class="inline-block mt-4 text-sm font-medium text-blue-600 group-hover:underline">
                Proceed to SHS form →
            </span>
        </a>

        {{-- TESDA / Diploma --}}
        <a href="{{ route('applicant.pre-register.tesda.form') }}"
           class="group block bg-white border-2 border-slate-200 hover:border-blue-600 rounded-xl p-8 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-center w-16 h-16 rounded-full bg-amber-100 text-amber-700 mb-4 group-hover:bg-amber-500 group-hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-slate-800 group-hover:text-amber-700">TESDA Diploma Course &amp; Others</h2>
            <p class="text-sm text-slate-600 mt-2">
                Technical-vocational and diploma courses under the TESDA Manpower Profile registration.
            </p>
            <span class="inline-block mt-4 text-sm font-medium text-amber-600 group-hover:underline">
                Proceed to TESDA form →
            </span>
        </a>
    </div>

    <p class="text-center text-xs text-slate-500 mt-8">
        Already registered? <a href="{{ route('applicant.status.form') }}" class="text-blue-600 underline">Check your status</a>.
    </p>
</div>
@endsection
