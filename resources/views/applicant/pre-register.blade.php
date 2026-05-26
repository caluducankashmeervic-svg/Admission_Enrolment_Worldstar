@extends('layouts.app')
@section('title', 'Applicant Pre-Registration')

@php
    $cls = 'mt-1 w-full rounded border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500';
@endphp

@section('content')
<div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-6">
    <h1 class="text-2xl font-semibold">Applicant Pre-Registration</h1>
    <p class="text-sm text-slate-500 mt-1">Fill in all required fields. You will receive a reference code upon submission.</p>

    <form method="POST" action="{{ route('applicant.pre-register.store') }}" class="mt-6 space-y-8">
        @csrf

        <fieldset>
            <legend class="font-semibold text-blue-700">Personal Information</legend>
            <div class="grid md:grid-cols-3 gap-4 mt-3">
                <label class="block"><span class="text-sm font-medium">First Name *</span>
                    <input name="first_name" required value="{{ old('first_name') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Middle Name</span>
                    <input name="middle_name" value="{{ old('middle_name') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Last Name *</span>
                    <input name="last_name" required value="{{ old('last_name') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Suffix</span>
                    <input name="suffix" value="{{ old('suffix') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Gender *</span>
                    <select name="gender" required class="{{ $cls }}">
                        <option value="">Select…</option>
                        @foreach(['Male','Female','Other'] as $g)
                            <option @selected(old('gender')===$g)>{{ $g }}</option>
                        @endforeach
                    </select></label>
                <label class="block"><span class="text-sm font-medium">Birth Date *</span>
                    <input type="date" name="birth_date" required value="{{ old('birth_date') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Civil Status *</span>
                    <input name="civil_status" required value="{{ old('civil_status', 'Single') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Nationality *</span>
                    <input name="nationality" required value="{{ old('nationality', 'Filipino') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Religion</span>
                    <input name="religion" value="{{ old('religion') }}" class="{{ $cls }}"></label>
            </div>
        </fieldset>

        <fieldset>
            <legend class="font-semibold text-blue-700">Contact Details</legend>
            <div class="grid md:grid-cols-3 gap-4 mt-3">
                <label class="block"><span class="text-sm font-medium">Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Mobile *</span>
                    <input name="mobile" required value="{{ old('mobile') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">ZIP</span>
                    <input name="zip" value="{{ old('zip') }}" class="{{ $cls }}"></label>
                <label class="block md:col-span-3"><span class="text-sm font-medium">Address *</span>
                    <input name="address_line" required value="{{ old('address_line') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">City *</span>
                    <input name="city" required value="{{ old('city') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Province *</span>
                    <input name="province" required value="{{ old('province') }}" class="{{ $cls }}"></label>
            </div>
        </fieldset>

        <fieldset>
            <legend class="font-semibold text-blue-700">Academic Background</legend>
            <div class="grid md:grid-cols-3 gap-4 mt-3">
                <label class="block md:col-span-2"><span class="text-sm font-medium">Last School *</span>
                    <input name="last_school_attended" required value="{{ old('last_school_attended') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">SHS Strand / Track</span>
                    <select name="strand_track" class="{{ $cls }}">
                        <option value="">Select…</option>
                        <optgroup label="Academic">
                            @foreach([
                                'ABM' => 'ABM (Accountancy, Business & Management)',
                                'HUMSS' => 'HUMSS (Humanities & Social Sciences)',
                                'STEM' => 'STEM (Science, Technology, Engineering & Mathematics)',
                                'GAS' => 'GAS (General Academics)',
                            ] as $val => $label)
                                <option value="{{ $val }}" @selected(old('strand_track')===$val)>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Tech-Voc">
                            @foreach([
                                'TVL-ICT' => 'TVL - Information & Communications Technology',
                                'TVL-HE' => 'TVL - Home Economics',
                                'TVL-IA' => 'TVL - Industrial Arts',
                                'TVL-AFA' => 'TVL - Agri-Fishery Arts',
                            ] as $val => $label)
                                <option value="{{ $val }}" @selected(old('strand_track')===$val)>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Other">
                            <option value="Sports" @selected(old('strand_track')==='Sports')>Sports</option>
                            <option value="Arts and Design" @selected(old('strand_track')==='Arts and Design')>Arts and Design</option>
                        </optgroup>
                    </select></label>
                <label class="block md:col-span-3"><span class="text-sm font-medium">School Address</span>
                    <input name="last_school_address" value="{{ old('last_school_address') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Year Graduated</span>
                    <input type="number" name="year_graduated" min="1950" max="{{ date('Y')+1 }}"
                           value="{{ old('year_graduated') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">GWA</span>
                    <input type="number" step="0.01" min="60" max="100" name="gwa"
                           value="{{ old('gwa') }}" class="{{ $cls }}"></label>
            </div>
        </fieldset>

        <fieldset>
            <legend class="font-semibold text-blue-700">Guardian</legend>
            <div class="grid md:grid-cols-3 gap-4 mt-3">
                <label class="block"><span class="text-sm font-medium">Name *</span>
                    <input name="guardian_name" required value="{{ old('guardian_name') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Relationship *</span>
                    <input name="guardian_relationship" required value="{{ old('guardian_relationship') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Contact Number *</span>
                    <input name="guardian_contact" required value="{{ old('guardian_contact') }}" class="{{ $cls }}"></label>
            </div>
        </fieldset>

        <fieldset>
            <legend class="font-semibold text-blue-700">Program Preference</legend>
            <div class="grid md:grid-cols-2 gap-4 mt-3">
                <label class="block"><span class="text-sm font-medium">Preferred Course *</span>
                    <select name="preferred_course_id" required class="{{ $cls }}">
                        <option value="">Select…</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}" @selected(old('preferred_course_id')==$c->id)>
                                {{ $c->code }} — {{ $c->name }}
                            </option>
                        @endforeach
                    </select></label>
                <label class="block"><span class="text-sm font-medium">Academic Term *</span>
                    <select name="academic_term_id" required class="{{ $cls }}">
                        <option value="">Select…</option>
                        @foreach($terms as $t)
                            <option value="{{ $t->id }}" @selected(old('academic_term_id', $terms->count()===1 ? $t->id : null)==$t->id)>
                                {{ $t->school_year }} — {{ $t->semester }} Sem
                            </option>
                        @endforeach
                    </select></label>
            </div>
        </fieldset>

        <div class="flex justify-end">
            <button class="bg-blue-600 text-white px-5 py-2.5 rounded hover:bg-blue-700">
                Submit Pre-Registration
            </button>
        </div>
    </form>
</div>
@endsection
