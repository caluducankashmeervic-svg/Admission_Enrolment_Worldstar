@extends('layouts.app')
@section('title', 'Admission Application')

@section('content')
<div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm">
    {{-- Formal document header --}}
    <div class="relative border-b border-slate-200 px-8 py-6">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xl">
                    LOGO
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">{{ config('app.name') ?: 'Admission Application' }}</h1>
                    <p class="text-sm text-slate-600">Admission Application Form</p>
                </div>
            </div>
            <img src="{{ $user->profile_photo_url }}"
                 class="w-20 h-20 rounded-md object-cover border-2 border-slate-300 shadow-sm"
                 alt="Applicant photo">
        </div>
    </div>

    <form method="POST" action="{{ route('applicant.admission.store') }}" class="px-8 py-6 space-y-6">
        @csrf

        {{-- Campus / Year / Date --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Campus</label>
                <select name="campus" required
                        class="mt-1 block w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500 px-3 py-2 border">
                    @foreach (['Main Campus', 'Campus 2', 'Campus 3', 'Campus 4'] as $c)
                        <option value="{{ $c }}" @selected(old('campus') === $c)>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">School Year</label>
                <input type="text" name="school_year" value="{{ old('school_year', optional($terms->first())->school_year ?? now()->year.'-'.(now()->year + 1)) }}"
                       required class="mt-1 block w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500 px-3 py-2 border">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Date Applied</label>
                <input type="date" name="date_applied" value="{{ old('date_applied', now()->toDateString()) }}" required
                       class="mt-1 block w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500 px-3 py-2 border">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Semester</label>
            <div class="mt-2 flex gap-4 text-sm">
                @foreach (['1st', '2nd', 'Summer'] as $s)
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" name="semester" value="{{ $s }}"
                               {{ old('semester', '1st') === $s ? 'checked' : '' }}
                               class="text-blue-600 focus:ring-blue-500">
                        {{ $s }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Degree program choices --}}
        <fieldset class="border border-slate-200 rounded-md p-4">
            <legend class="px-2 text-sm font-semibold text-slate-700">Degree Program Choice</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2">
                @foreach ($courses as $course)
                    <label class="flex items-start gap-3 p-3 border border-slate-200 rounded-md hover:border-blue-400 cursor-pointer">
                        <input type="radio" name="preferred_course_id" value="{{ $course->id }}" required
                               {{ old('preferred_course_id', optional($applicant)->preferred_course_id) == $course->id ? 'checked' : '' }}
                               class="mt-1 text-blue-600 focus:ring-blue-500">
                        <div>
                            <div class="font-semibold text-slate-800">{{ $course->code }}</div>
                            <div class="text-xs text-slate-500">{{ $course->name }}</div>
                        </div>
                    </label>
                @endforeach
            </div>
        </fieldset>

        {{-- Read-only applicant identity --}}
        <fieldset class="border border-slate-200 rounded-md p-4 bg-slate-50">
            <legend class="px-2 text-sm font-semibold text-slate-700">Applicant Information</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                <div>
                    <label class="block text-xs text-slate-500 uppercase">Last Name</label>
                    <div class="mt-1 font-medium text-slate-800">{{ $user->lastname ?: '—' }}</div>
                </div>
                <div>
                    <label class="block text-xs text-slate-500 uppercase">First Name</label>
                    <div class="mt-1 font-medium text-slate-800">{{ $user->firstname ?: $user->name }}</div>
                </div>
                <div>
                    <label class="block text-xs text-slate-500 uppercase">Middle Name</label>
                    <div class="mt-1 font-medium text-slate-800">{{ $user->middlename ?: '—' }}</div>
                </div>
                <div>
                    <label class="block text-xs text-slate-500 uppercase">Email Address</label>
                    <div class="mt-1 font-medium text-slate-800">{{ $user->email }}</div>
                </div>
            </div>
        </fieldset>

        <div class="flex justify-end gap-3 pt-2 border-t border-slate-100">
            <a href="{{ route('home') }}"
               class="px-5 py-2 rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">Cancel</a>
            <button type="submit"
                    class="px-5 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                Submit Application
            </button>
        </div>
    </form>
</div>
@endsection
