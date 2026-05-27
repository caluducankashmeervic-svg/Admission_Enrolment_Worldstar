@extends('layouts.app')
@section('title', 'Edit Applicant — ' . $applicant->reference_code)

@php
    $p   = $applicant->profile_data ?? [];
    $cls = 'mt-1 w-full rounded border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500';
    $isShs = $applicant->applicant_type === \App\Models\Applicant::TYPE_SHS;
@endphp

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Edit Applicant</h1>
            <p class="text-sm text-slate-500 font-mono mt-0.5">{{ $applicant->reference_code }}</p>
        </div>
        <a href="{{ route('registrar.verify.show', $applicant) }}"
           class="text-sm text-blue-600 hover:underline">← Back to applicant</a>
    </div>

    @if($errors->any())
        <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-700 text-sm">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('registrar.applicants.update', $applicant) }}"
          class="space-y-6 bg-white border border-slate-200 rounded-lg shadow-sm p-6">
        @csrf
        @method('PUT')

        {{-- ── Personal ── --}}
        <fieldset>
            <legend class="font-semibold text-blue-700 text-sm">Personal</legend>
            <div class="grid md:grid-cols-4 gap-4 mt-3">
                <label class="block"><span class="text-xs font-medium text-slate-600">Last Name *</span>
                    <input name="last_name" required value="{{ old('last_name', $applicant->last_name) }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">First Name *</span>
                    <input name="first_name" required value="{{ old('first_name', $applicant->first_name) }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Middle Name</span>
                    <input name="middle_name" value="{{ old('middle_name', $applicant->middle_name) }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Extension</span>
                    <input name="suffix" value="{{ old('suffix', $applicant->suffix) }}" class="{{ $cls }}"></label>
            </div>
            <div class="grid md:grid-cols-4 gap-4 mt-4">
                <label class="block"><span class="text-xs font-medium text-slate-600">Gender</span>
                    <select name="gender" class="{{ $cls }}">
                        <option value="">—</option>
                        @foreach(['Male','Female','Other'] as $g)
                            <option value="{{ $g }}" @selected(old('gender', $applicant->gender) == $g)>{{ $g }}</option>
                        @endforeach
                    </select></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Birthday</span>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $applicant->birth_date?->format('Y-m-d')) }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Citizenship</span>
                    <input name="nationality" value="{{ old('nationality', $applicant->nationality) }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Religion</span>
                    <input name="religion" value="{{ old('religion', $applicant->religion) }}" class="{{ $cls }}"></label>
            </div>
            @if(! $isShs)
            <div class="grid md:grid-cols-2 gap-4 mt-4">
                <label class="block"><span class="text-xs font-medium text-slate-600">Civil Status</span>
                    <select name="civil_status" class="{{ $cls }}">
                        <option value="">—</option>
                        @foreach(['Single','Married','Widow/er','Separated','Employed','Unemployed'] as $cs)
                            <option value="{{ $cs }}" @selected(old('civil_status', $applicant->civil_status) == $cs)>{{ $cs }}</option>
                        @endforeach
                    </select></label>
            </div>
            @endif
        </fieldset>

        {{-- ── Contact ── --}}
        <fieldset>
            <legend class="font-semibold text-blue-700 text-sm">Contact Details</legend>
            <div class="grid md:grid-cols-3 gap-4 mt-3">
                @if($isShs)
                <label class="block"><span class="text-xs font-medium text-slate-600">Landline</span>
                    <input name="profile[landline]" value="{{ old('profile.landline', $p['landline'] ?? '') }}" class="{{ $cls }}"></label>
                @endif
                <label class="block"><span class="text-xs font-medium text-slate-600">Mobile *</span>
                    <input name="mobile" required value="{{ old('mobile', $applicant->mobile) }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Facebook</span>
                    <input name="profile[facebook]" value="{{ old('profile.facebook', $p['facebook'] ?? '') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Email</span>
                    <input type="email" name="email" value="{{ old('email', $applicant->email) }}" class="{{ $cls }}"></label>
            </div>
            <div class="mt-4">
                <label class="block"><span class="text-xs font-medium text-slate-600">Permanent Mailing Address</span>
                    <input name="address_line" value="{{ old('address_line', $applicant->address_line) }}" class="{{ $cls }}"></label>
            </div>
            <div class="grid md:grid-cols-3 gap-4 mt-4">
                <label class="block"><span class="text-xs font-medium text-slate-600">City/Town</span>
                    <input name="city" value="{{ old('city', $applicant->city) }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Province</span>
                    <input name="province" value="{{ old('province', $applicant->province) }}" class="{{ $cls }}"></label>
                @if(! $isShs)
                <label class="block"><span class="text-xs font-medium text-slate-600">Barangay</span>
                    <input name="profile[barangay]" value="{{ old('profile.barangay', $p['barangay'] ?? '') }}" class="{{ $cls }}"></label>
                @endif
            </div>
        </fieldset>

        @if($isShs)
        {{-- ── Parents (SHS) ── --}}
        <fieldset>
            <legend class="font-semibold text-blue-700 text-sm">Parents</legend>
            <div class="grid md:grid-cols-3 gap-4 mt-3">
                <label class="block"><span class="text-xs font-medium text-slate-600">Father</span>
                    <input name="profile[father_name]" value="{{ old('profile.father_name', $p['father_name'] ?? '') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Occupation</span>
                    <input name="profile[father_occupation]" value="{{ old('profile.father_occupation', $p['father_occupation'] ?? '') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Contact No.</span>
                    <input name="profile[father_contact]" value="{{ old('profile.father_contact', $p['father_contact'] ?? '') }}" class="{{ $cls }}"></label>
            </div>
            <div class="grid md:grid-cols-3 gap-4 mt-4">
                <label class="block"><span class="text-xs font-medium text-slate-600">Mother</span>
                    <input name="profile[mother_name]" value="{{ old('profile.mother_name', $p['mother_name'] ?? '') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Occupation</span>
                    <input name="profile[mother_occupation]" value="{{ old('profile.mother_occupation', $p['mother_occupation'] ?? '') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Contact No.</span>
                    <input name="profile[mother_contact]" value="{{ old('profile.mother_contact', $p['mother_contact'] ?? '') }}" class="{{ $cls }}"></label>
            </div>
        </fieldset>
        @else
        {{-- ── Guardian (TESDA) ── --}}
        <fieldset>
            <legend class="font-semibold text-blue-700 text-sm">Parent / Guardian</legend>
            <div class="grid md:grid-cols-3 gap-4 mt-3">
                <label class="block"><span class="text-xs font-medium text-slate-600">Full Name</span>
                    <input name="profile[parent_full_name]" value="{{ old('profile.parent_full_name', $p['parent_full_name'] ?? $applicant->guardian_name) }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Address</span>
                    <input name="profile[parent_address]" value="{{ old('profile.parent_address', $p['parent_address'] ?? '') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Contact No.</span>
                    <input name="profile[parent_contact]" value="{{ old('profile.parent_contact', $p['parent_contact'] ?? $applicant->guardian_contact) }}" class="{{ $cls }}"></label>
            </div>
        </fieldset>
        @endif

        {{-- ── Educational Background ── --}}
        <fieldset>
            <legend class="font-semibold text-blue-700 text-sm">Educational Background</legend>
            <div class="grid md:grid-cols-2 gap-4 mt-3">
                <label class="block"><span class="text-xs font-medium text-slate-600">{{ $isShs ? 'Junior High School' : 'Last School Attended' }}</span>
                    <input name="last_school_attended" value="{{ old('last_school_attended', $applicant->last_school_attended) }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">School Address</span>
                    <input name="last_school_address" value="{{ old('last_school_address', $applicant->last_school_address) }}" class="{{ $cls }}"></label>
            </div>
            <div class="grid md:grid-cols-3 gap-4 mt-4">
                <label class="block"><span class="text-xs font-medium text-slate-600">Year Graduated</span>
                    <input type="number" min="1950" max="{{ date('Y') + 1 }}" name="year_graduated"
                           value="{{ old('year_graduated', $applicant->year_graduated) }}" class="{{ $cls }}"></label>
                @if($isShs)
                <label class="block"><span class="text-xs font-medium text-slate-600">School Type</span>
                    <select name="profile[school_type]" class="{{ $cls }}">
                        <option value="">—</option>
                        @foreach(['Private','Public'] as $st)
                            <option value="{{ $st }}" @selected(old('profile.school_type', $p['school_type'] ?? '') == $st)>{{ $st }}</option>
                        @endforeach
                    </select></label>
                @else
                <label class="block"><span class="text-xs font-medium text-slate-600">Educational Attainment</span>
                    <input name="profile[educational_attainment]"
                           value="{{ old('profile.educational_attainment', $p['educational_attainment'] ?? '') }}" class="{{ $cls }}"></label>
                @endif
            </div>
            @if($isShs)
            <div class="grid md:grid-cols-3 gap-4 mt-4">
                <label class="block"><span class="text-xs font-medium text-slate-600">Est. Family Annual Income (PHP)</span>
                    <input name="profile[family_income]" value="{{ old('profile.family_income', $p['family_income'] ?? '') }}" class="{{ $cls }}"></label>
                <div>
                    <span class="text-xs font-medium text-slate-600 block mb-2">Varsity Player?</span>
                    <div class="flex gap-4 text-sm">
                        <label class="inline-flex items-center gap-1.5">
                            <input type="radio" name="profile[varsity_player]" value="1" @checked(old('profile.varsity_player', $p['varsity_player'] ?? false))> Yes
                        </label>
                        <label class="inline-flex items-center gap-1.5">
                            <input type="radio" name="profile[varsity_player]" value="0" @checked(!old('profile.varsity_player', $p['varsity_player'] ?? false))> No
                        </label>
                    </div>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-600 block mb-2">Need Financial Assistance?</span>
                    <div class="flex gap-4 text-sm">
                        <label class="inline-flex items-center gap-1.5">
                            <input type="radio" name="profile[financial_assistance]" value="1" @checked(old('profile.financial_assistance', $p['financial_assistance'] ?? false))> Yes
                        </label>
                        <label class="inline-flex items-center gap-1.5">
                            <input type="radio" name="profile[financial_assistance]" value="0" @checked(!old('profile.financial_assistance', $p['financial_assistance'] ?? false))> No
                        </label>
                    </div>
                </div>
            </div>
            @endif
        </fieldset>

        {{-- ── Track / Course ── --}}
        <fieldset>
            <legend class="font-semibold text-blue-700 text-sm">{{ $isShs ? 'Track Selection' : 'Course / Qualification' }}</legend>
            @if($isShs)
            <div class="grid md:grid-cols-2 gap-4 mt-3">
                <label class="block"><span class="text-xs font-medium text-slate-600">Track Type</span>
                    <input name="profile[track_category]" value="{{ old('profile.track_category', $p['track_category'] ?? '') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Track Program / Strand</span>
                    <input name="strand_track" value="{{ old('strand_track', $applicant->strand_track) }}" class="{{ $cls }}"></label>
            </div>
            @else
            <div class="grid md:grid-cols-2 gap-4 mt-3">
                <label class="block"><span class="text-xs font-medium text-slate-600">Course Qualification</span>
                    <input name="profile[course_qualification]" value="{{ old('profile.course_qualification', $p['course_qualification'] ?? '') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Diploma Course</span>
                    <input name="profile[diploma_course]" value="{{ old('profile.diploma_course', $p['diploma_course'] ?? '') }}" class="{{ $cls }}"></label>
            </div>
            @endif
            <div class="grid md:grid-cols-2 gap-4 mt-4">
                <label class="block"><span class="text-xs font-medium text-slate-600">Preferred Course (system)</span>
                    <select name="preferred_course_id" class="{{ $cls }}">
                        <option value="">— None —</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}" @selected(old('preferred_course_id', $applicant->preferred_course_id) == $c->id)>
                                {{ $c->code }} — {{ $c->name }}
                            </option>
                        @endforeach
                    </select></label>
                <label class="block"><span class="text-xs font-medium text-slate-600">Academic Term</span>
                    <select name="academic_term_id" class="{{ $cls }}">
                        <option value="">— None —</option>
                        @foreach($terms as $t)
                            <option value="{{ $t->id }}" @selected(old('academic_term_id', $applicant->academic_term_id) == $t->id)>
                                {{ $t->school_year }} {{ $t->semester }}
                            </option>
                        @endforeach
                    </select></label>
            </div>
        </fieldset>

        {{-- ── Submit ── --}}
        <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
            <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                Save Changes
            </button>
            <a href="{{ route('registrar.verify.show', $applicant) }}"
               class="px-4 py-2 border border-slate-300 text-slate-700 text-sm rounded hover:bg-slate-50">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
