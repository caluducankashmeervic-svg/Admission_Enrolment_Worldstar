@extends('layouts.app')
@section('title', 'TESDA Pre-Registration')

@php $cls = 'mt-1 w-full rounded border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500'; @endphp

@section('content')
<div class="max-w-5xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-6 mt-6">
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-bold text-slate-800">TESDA Diploma Course Pre-Registration</h1>
        <a href="{{ route('applicant.pre-register.choose') }}" class="text-sm text-blue-600 hover:underline">← Change program</a>
    </div>
    <p class="text-sm text-slate-500 mb-6">Manpower Profile. Fill in all required fields.</p>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-700 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('applicant.pre-register.tesda.store') }}" class="space-y-8">
        @csrf

        {{-- 1. Manpower Profile / Name --}}
        <fieldset>
            <legend class="font-semibold text-amber-700">1. Manpower Profile · Name</legend>
            <div class="grid md:grid-cols-4 gap-4 mt-3">
                <label class="block"><span class="text-sm font-medium">Last Name *</span>
                    <input name="last_name" required value="{{ old('last_name') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">First Name *</span>
                    <input name="first_name" required value="{{ old('first_name') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Middle Name</span>
                    <input name="middle_name" value="{{ old('middle_name') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Extension (Jr., Sr.)</span>
                    <input name="suffix" value="{{ old('suffix') }}" class="{{ $cls }}"></label>
            </div>
        </fieldset>

        {{-- 1.2 Permanent Mailing Address --}}
        <fieldset>
            <legend class="font-semibold text-amber-700">1.2 Complete Permanent Mailing Address</legend>
            <div class="grid md:grid-cols-3 gap-4 mt-3">
                <label class="block md:col-span-2"><span class="text-sm font-medium">Number, Street *</span>
                    <input name="address_line" required value="{{ old('address_line') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Barangay</span>
                    <input name="barangay" value="{{ old('barangay') }}" class="{{ $cls }}"></label>
            </div>
            <div class="grid md:grid-cols-4 gap-4 mt-4">
                <label class="block"><span class="text-sm font-medium">District</span>
                    <input name="district" value="{{ old('district') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">City/Municipality *</span>
                    <input name="city" required value="{{ old('city') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Province *</span>
                    <input name="province" required value="{{ old('province') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Region</span>
                    <input name="region" value="{{ old('region') }}" class="{{ $cls }}"></label>
            </div>
            <div class="grid md:grid-cols-4 gap-4 mt-4">
                <label class="block md:col-span-2"><span class="text-sm font-medium">Email / Facebook</span>
                    <input name="facebook" value="{{ old('facebook') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Email Address</span>
                    <input type="email" name="email" value="{{ old('email') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Contact No. *</span>
                    <input name="mobile" required value="{{ old('mobile') }}" class="{{ $cls }}"></label>
            </div>
            <div class="grid md:grid-cols-2 gap-4 mt-4">
                <label class="block"><span class="text-sm font-medium">Nationality *</span>
                    <input name="nationality" required value="{{ old('nationality', 'Filipino') }}" class="{{ $cls }}"></label>
            </div>
        </fieldset>

        {{-- 2. Personal Information --}}
        <fieldset>
            <legend class="font-semibold text-amber-700">2. Personal Information</legend>
            <div class="grid md:grid-cols-3 gap-4 mt-3">
                <div>
                    <span class="text-sm font-medium block mb-1">2.1 Sex *</span>
                    <div class="flex gap-4 mt-2 text-sm">
                        @foreach(['Male','Female'] as $g)
                            <label class="inline-flex items-center">
                                <input type="radio" name="gender" value="{{ $g }}" required @checked(old('gender')==$g) class="mr-2">{{ $g }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <span class="text-sm font-medium block mb-1">2.2 Civil Status *</span>
                    <div class="flex flex-wrap gap-3 mt-2 text-sm">
                        @foreach(['Single','Married','Widow/er','Separated'] as $cs)
                            <label class="inline-flex items-center">
                                <input type="radio" name="civil_status" value="{{ $cs }}" required @checked(old('civil_status')==$cs) class="mr-1">{{ $cs }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <span class="text-sm font-medium block mb-1">2.3 Employment Status (before training) *</span>
                    <div class="flex gap-4 mt-2 text-sm">
                        @foreach(['Employed','Unemployed'] as $es)
                            <label class="inline-flex items-center">
                                <input type="radio" name="employment_status" value="{{ $es }}" required @checked(old('employment_status')==$es) class="mr-2">{{ $es }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="grid md:grid-cols-4 gap-4 mt-4">
                <label class="block"><span class="text-sm font-medium">2.4 Birthdate *</span>
                    <input type="date" name="birth_date" required value="{{ old('birth_date') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">2.5 Birthplace — City/Municipality</span>
                    <input name="birthplace_city" value="{{ old('birthplace_city') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Province</span>
                    <input name="birthplace_province" value="{{ old('birthplace_province') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Region</span>
                    <input name="birthplace_region" value="{{ old('birthplace_region') }}" class="{{ $cls }}"></label>
            </div>
            <div class="mt-4">
                <span class="text-sm font-medium block mb-1">2.6 Educational Attainment (before training) *</span>
                <div class="grid md:grid-cols-3 gap-2 mt-2 text-sm">
                    @foreach([
                        'No Grade Completed', 'Pre-School (Nursery/Kinder)', 'Elementary Undergraduate',
                        'High School Undergraduate', 'High School Graduate', 'Post-Secondary',
                        'College Undergraduate', 'College Graduate',
                    ] as $ea)
                        <label class="inline-flex items-center">
                            <input type="radio" name="educational_attainment" value="{{ $ea }}" required @checked(old('educational_attainment')==$ea) class="mr-2">{{ $ea }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="grid md:grid-cols-3 gap-4 mt-4">
                <label class="block"><span class="text-sm font-medium">2.7 Parent/Guardian Full Name *</span>
                    <input name="parent_full_name" required value="{{ old('parent_full_name') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Complete Permanent Address</span>
                    <input name="parent_address" value="{{ old('parent_address') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Contact No. *</span>
                    <input name="parent_contact" required value="{{ old('parent_contact') }}" class="{{ $cls }}"></label>
            </div>
        </fieldset>

        {{-- 3. Learner/Trainee/Student Classification --}}
        <fieldset>
            <legend class="font-semibold text-amber-700">3. Learner/Trainee/Student Classification</legend>
            <p class="text-xs text-slate-500 mb-2">(Check all that apply)</p>
            <div class="grid md:grid-cols-3 gap-2 mt-2 text-sm">
                @foreach([
                    'Students','Out of School Youth','Solo Parent','Solo Parent\'s Children','Senior Citizens',
                    'TVET Trainers','Displaced HEIs Teaching Personnel','Persons with Disabilities',
                    'Currently Employed Workers','Employees With Contractual/Job Order Status',
                    'Urban and Rural Poor','Informal Workers',
                    'Industry Workers','Cooperatives','Family Enterprises','Family Member of Micro entrepreneurs',
                    'Micro Entrepreneurs','Farmers and Fisherman','Family Members of Farmers and Fisherman',
                    'Community Trng. & Employment Coordinator','Overseas Filipino Workers (OFW) Dependents',
                    'Returning/Repatriated Overseas Filipino Workers',
                    'Indigenous People & Cultural Communities','Disadvantaged Women',
                    'Victim of Natural Disaster & Calamities','Victim or Survivor of Human Trafficking',
                    'Drug Dependent Surrenderers','Rebel Returnees or Decommissioned','Inmates and Detainees',
                    'Family Members of Trainees and Detainees','Uniformed Personnel','Wounded-In-Action AFP & PNP Personnel',
                    'Family Members of AFP & PNP Killed-and-Wounded in Action','TESDA Alumni',
                ] as $cls_item)
                    <label class="inline-flex items-start">
                        <input type="checkbox" name="classifications[]" value="{{ $cls_item }}"
                               @checked(in_array($cls_item, (array) old('classifications', []))) class="mr-2 mt-0.5">
                        <span>{{ $cls_item }}</span>
                    </label>
                @endforeach
            </div>
            <label class="block mt-3"><span class="text-sm font-medium">Others (specify) / OFW country &amp; length of stay</span>
                <input name="classification_other" value="{{ old('classification_other') }}" class="{{ $cls }}"></label>
        </fieldset>

        {{-- 4. Type of Disability --}}
        <fieldset>
            <legend class="font-semibold text-amber-700">4. Type of Disability (PWD only)</legend>
            <div class="grid md:grid-cols-3 gap-2 mt-2 text-sm">
                @foreach([
                    'Mental/Intellectual','Visual Disability','Orthopedic (Musculoskeletal) Disability',
                    'Hearing Disability','Speech Impairment','Multiple Disabilities',
                    'Psychosocial Disability','Disability Due to Chronic Illness','Learning Disability',
                ] as $d)
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="disability_types[]" value="{{ $d }}"
                               @checked(in_array($d, (array) old('disability_types', []))) class="mr-2">{{ $d }}
                    </label>
                @endforeach
            </div>
        </fieldset>

        {{-- 5. Causes of Disability --}}
        <fieldset>
            <legend class="font-semibold text-amber-700">5. Causes of Disability (PWD only)</legend>
            <div class="flex flex-wrap gap-4 mt-2 text-sm">
                @foreach(['Congenital/Inborn','Illness','Injury'] as $cd)
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="disability_causes[]" value="{{ $cd }}"
                               @checked(in_array($cd, (array) old('disability_causes', []))) class="mr-2">{{ $cd }}
                    </label>
                @endforeach
            </div>
        </fieldset>

        {{-- 6. NCAE / YP4SC --}}
        <fieldset>
            <legend class="font-semibold text-amber-700">6. Taken NCAE/YP4SC Before?</legend>
            <div class="grid md:grid-cols-4 gap-4 mt-3">
                <div>
                    <div class="flex gap-4 mt-2 text-sm">
                        <label class="inline-flex items-center"><input type="radio" name="ncae_taken" value="1" @checked(old('ncae_taken')=='1') class="mr-1">Yes</label>
                        <label class="inline-flex items-center"><input type="radio" name="ncae_taken" value="0" @checked(old('ncae_taken','0')=='0') class="mr-1">No</label>
                    </div>
                </div>
                <label class="block"><span class="text-sm font-medium">Where</span>
                    <input name="ncae_where" value="{{ old('ncae_where') }}" class="{{ $cls }}"></label>
                <label class="block md:col-span-2"><span class="text-sm font-medium">When</span>
                    <input name="ncae_when" value="{{ old('ncae_when') }}" class="{{ $cls }}"></label>
            </div>
        </fieldset>

        {{-- 7. Course / Scholarship --}}
        <fieldset>
            <legend class="font-semibold text-amber-700">7. Course &amp; Scholarship</legend>
            <div class="grid md:grid-cols-2 gap-4 mt-3">
                <label class="block"><span class="text-sm font-medium">7.1 Name of Course/Qualification *</span>
                    <input name="course_qualification" required value="{{ old('course_qualification') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">7.2 Scholarship Package (TWSP, PESFA, STEP)</span>
                    <input name="scholarship_type" value="{{ old('scholarship_type') }}" class="{{ $cls }}"></label>
            </div>
        </fieldset>

        {{-- 8. Insurance Beneficiaries --}}
        <fieldset>
            <legend class="font-semibold text-amber-700">8. Insurance Beneficiaries</legend>
            <div class="grid md:grid-cols-2 gap-4 mt-3">
                <label class="block"><span class="text-sm font-medium">Beneficiary 1 (Last, First, Middle)</span>
                    <input name="beneficiary_1" value="{{ old('beneficiary_1') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Beneficiary 2 (Last, First, Middle)</span>
                    <input name="beneficiary_2" value="{{ old('beneficiary_2') }}" class="{{ $cls }}"></label>
            </div>
        </fieldset>

        {{-- 9. Program Preference --}}
        <fieldset>
            <legend class="font-semibold text-amber-700">9. Program Preference</legend>
            <div class="grid md:grid-cols-1 gap-4 mt-3">
                <label class="block"><span class="text-sm font-medium">Preferred Course *</span>
                    <select name="diploma_course" required class="{{ $cls }}">
                        <option value="">Select…</option>
                        @foreach([
                            'CST (Computer Science Technology)',
                            'CET (Computer Engineering Technology)',
                            'EET (Electronics Engineering Technology)',
                            'ICT (Information and Communications Technology)',
                        ] as $dc)
                            <option value="{{ $dc }}" @selected(old('diploma_course')===$dc)>{{ $dc }}</option>
                        @endforeach
                    </select></label>
            </div>
        </fieldset>

        {{-- 10. Privacy Disclaimer --}}
        <fieldset>
            <legend class="font-semibold text-amber-700">10. Privacy Disclaimer</legend>
            <p class="text-sm text-slate-700 mt-2">
                I hereby allow TESDA to use/post my contact details, name, e-mail, cellphone/landline nos. and other information
                I provided which may be used by processing my scholarship, for employment purposes and other opportunities.
            </p>
            <label class="inline-flex items-center mt-3">
                <input type="checkbox" name="privacy_consent" value="1" @checked(old('privacy_consent')) class="mr-2">
                <span class="text-sm font-medium">I Agree</span>
            </label>
        </fieldset>

        <div class="flex justify-end">
            <button class="bg-amber-500 text-white px-5 py-2.5 rounded hover:bg-amber-600">
                Submit Pre-Registration
            </button>
        </div>
    </form>
</div>
@endsection
