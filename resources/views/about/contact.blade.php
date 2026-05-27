@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')
<div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-8 mt-6">
    <div class="border-l-4 border-amber-400 pl-4 mb-8">
        <h1 class="text-3xl font-bold text-[#1D4ED8] tracking-wide">Contact Us</h1>
        <p class="text-sm text-slate-500 mt-1">Get in touch with Worldstar College of Science and Technology</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- Phone / Contact --}}
        <div class="flex items-start gap-4">
            <div class="shrink-0 w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-[#1D4ED8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498A1 1 0 0121 15.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-slate-800 text-[15px]">Phone / Mobile</p>
                <p class="text-slate-600 mt-1 text-[15px]">(078) 642-0421</p>
                <p class="text-slate-600 text-[15px]">0916 908 8531</p>
            </div>
        </div>

        {{-- Email --}}
        <div class="flex items-start gap-4">
            <div class="shrink-0 w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-[#1D4ED8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-slate-800 text-[15px]">Email Address</p>
                <a href="mailto:wcst.2016@gmail.com"
                   class="text-[#1D4ED8] hover:underline mt-1 block text-[15px]">wcst.2016@gmail.com</a>
            </div>
        </div>

        {{-- Address --}}
        <div class="flex items-start gap-4">
            <div class="shrink-0 w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-[#1D4ED8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-slate-800 text-[15px]">Address</p>
                <p class="text-slate-600 mt-1 text-[15px] leading-relaxed">
                    Alliance Bldg., National Highway, Bantug,<br>
                    Roxas, Philippines, 3320
                </p>
            </div>
        </div>

        {{-- Office Hours --}}
        <div class="flex items-start gap-4">
            <div class="shrink-0 w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-[#1D4ED8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-slate-800 text-[15px]">Office Hours</p>
                <p class="text-slate-600 mt-1 text-[15px]">Monday – Friday: 8:00 AM – 5:00 PM</p>
                <p class="text-slate-600 text-[15px]">Saturday: 8:00 AM – 4:00 PM</p>
            </div>
        </div>

    </div>

    {{-- Facebook --}}
    <div class="mt-8 pt-6 border-t border-slate-200">
        <p class="font-semibold text-slate-800 mb-3">Follow us on Social Media</p>
        <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-2 text-[#1D4ED8] hover:underline text-[15px]">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
            </svg>
            Worldstar College on Facebook
        </a>
    </div>
</div>
@endsection
