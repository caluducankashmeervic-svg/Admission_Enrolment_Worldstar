@extends('layouts.app')
@section('title', 'SHS Pre-Registration')

@php $cls = 'mt-1 w-full rounded border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500'; @endphp

@section('content')
<div class="max-w-5xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-6 mt-6">
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-bold text-slate-800">Senior High School Pre-Registration</h1>
        <a href="{{ route('applicant.pre-register.choose') }}" class="text-sm text-blue-600 hover:underline">← Change program</a>
    </div>
    <p class="text-sm text-slate-500 mb-6">Fill in all required fields. You will receive a reference code upon submission.</p>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-700 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('applicant.pre-register.shs.store') }}" class="space-y-8">
        @csrf

        {{-- Personal --}}
        <fieldset>
            <legend class="font-semibold text-blue-700">Personal</legend>
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
            <div class="grid md:grid-cols-4 gap-4 mt-4">
                <label class="block"><span class="text-sm font-medium">Gender *</span>
                    <select name="gender" required class="{{ $cls }}">
                        <option value="">Select…</option>
                        @foreach(['Male','Female','Other'] as $g)
                            <option value="{{ $g }}" @selected(old('gender')==$g)>{{ $g }}</option>
                        @endforeach
                    </select></label>
                <label class="block"><span class="text-sm font-medium">Birthday *</span>
                    <input type="date" name="birth_date" required value="{{ old('birth_date') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Citizenship *</span>
                    <input name="nationality" required value="{{ old('nationality', 'Filipino') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Religion</span>
                    <input name="religion" value="{{ old('religion') }}" class="{{ $cls }}"></label>
            </div>
        </fieldset>

        {{-- Contact --}}
        <fieldset>
            <legend class="font-semibold text-blue-700">Contact Details</legend>
            <div class="grid md:grid-cols-3 gap-4 mt-3">
                <label class="block"><span class="text-sm font-medium">Landline Number</span>
                    <input name="landline" value="{{ old('landline') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Mobile Number *</span>
                    <input name="mobile" required value="{{ old('mobile') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Facebook Account</span>
                    <input name="facebook" value="{{ old('facebook') }}" class="{{ $cls }}"></label>
            </div>
            <div class="grid md:grid-cols-1 gap-4 mt-4">
                <label class="block"><span class="text-sm font-medium">Permanent Mailing Address *</span>
                    <input name="address_line" required value="{{ old('address_line') }}"
                           placeholder="Block #, Lot #, House #, Street, Subd./Village, Brgy."
                           class="{{ $cls }}"></label>
            </div>
            <div class="grid md:grid-cols-3 gap-4 mt-4">
                <label class="block"><span class="text-sm font-medium">City/Town *</span>
                    <input name="city" required value="{{ old('city') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Province *</span>
                    <input name="province" required value="{{ old('province') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Email Address</span>
                    <input type="email" name="email" value="{{ old('email') }}" class="{{ $cls }}"></label>
            </div>
        </fieldset>

        {{-- Parents --}}
        <fieldset>
            <legend class="font-semibold text-blue-700">Parents</legend>
            <div class="grid md:grid-cols-3 gap-4 mt-3">
                <label class="block"><span class="text-sm font-medium">Father</span>
                    <input name="father_name" value="{{ old('father_name') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Occupation</span>
                    <input name="father_occupation" value="{{ old('father_occupation') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Contact No.</span>
                    <input name="father_contact" value="{{ old('father_contact') }}" class="{{ $cls }}"></label>
            </div>
            <div class="grid md:grid-cols-3 gap-4 mt-4">
                <label class="block"><span class="text-sm font-medium">Mother</span>
                    <input name="mother_name" value="{{ old('mother_name') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Occupation</span>
                    <input name="mother_occupation" value="{{ old('mother_occupation') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Contact No.</span>
                    <input name="mother_contact" value="{{ old('mother_contact') }}" class="{{ $cls }}"></label>
            </div>
        </fieldset>

        {{-- Educational Background --}}
        <fieldset>
            <legend class="font-semibold text-blue-700">Educational Background</legend>
            <div class="grid md:grid-cols-2 gap-4 mt-3">
                <label class="block"><span class="text-sm font-medium">Junior High School *</span>
                    <input name="junior_high_school" required value="{{ old('junior_high_school') }}" class="{{ $cls }}"></label>
                <label class="block"><span class="text-sm font-medium">Address</span>
                    <input name="junior_high_address" value="{{ old('junior_high_address') }}" class="{{ $cls }}"></label>
            </div>
            <div class="grid md:grid-cols-2 gap-4 mt-4">
                <label class="block"><span class="text-sm font-medium">Year Graduated</span>
                    <input type="number" min="1950" max="{{ date('Y')+1 }}" name="year_graduated"
                           value="{{ old('year_graduated') }}" class="{{ $cls }}"></label>
                <div>
                    <span class="text-sm font-medium block mb-1">School Type</span>
                    <div class="flex gap-4 mt-2 text-sm">
                        @foreach(['Private','Public'] as $st)
                            <label class="inline-flex items-center">
                                <input type="radio" name="school_type" value="{{ $st }}" @checked(old('school_type')==$st) class="mr-2">
                                {{ $st }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="grid md:grid-cols-3 gap-4 mt-4">
                <div>
                    <span class="text-sm font-medium block mb-1">Varsity Player?</span>
                    <div class="flex gap-4 mt-2 text-sm">
                        <label class="inline-flex items-center"><input type="radio" name="varsity_player" value="1" @checked(old('varsity_player')=='1') class="mr-1">Yes</label>
                        <label class="inline-flex items-center"><input type="radio" name="varsity_player" value="0" @checked(old('varsity_player','0')=='0') class="mr-1">No</label>
                    </div>
                </div>
                <div>
                    <span class="text-sm font-medium block mb-1">School Dancer?</span>
                    <div class="flex gap-4 mt-2 text-sm">
                        <label class="inline-flex items-center"><input type="radio" name="school_dancer" value="1" @checked(old('school_dancer')=='1') class="mr-1">Yes</label>
                        <label class="inline-flex items-center"><input type="radio" name="school_dancer" value="0" @checked(old('school_dancer','0')=='0') class="mr-1">No</label>
                    </div>
                </div>
                <div>
                    <span class="text-sm font-medium block mb-1">Planning to pursue college?</span>
                    <div class="flex gap-4 mt-2 text-sm">
                        <label class="inline-flex items-center"><input type="radio" name="planning_college" value="1" @checked(old('planning_college')=='1') class="mr-1">Yes</label>
                        <label class="inline-flex items-center"><input type="radio" name="planning_college" value="0" @checked(old('planning_college','0')=='0') class="mr-1">No</label>
                    </div>
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-4 mt-4">
                <label class="block"><span class="text-sm font-medium">Estimated Family Annual Income (PhP)</span>
                    <input name="family_income" value="{{ old('family_income') }}" class="{{ $cls }}"></label>
                <div>
                    <span class="text-sm font-medium block mb-1">Need Financial Assistance?</span>
                    <div class="flex gap-4 mt-2 text-sm">
                        <label class="inline-flex items-center"><input type="radio" name="financial_assistance" value="1" @checked(old('financial_assistance')=='1') class="mr-1">Yes</label>
                        <label class="inline-flex items-center"><input type="radio" name="financial_assistance" value="0" @checked(old('financial_assistance','0')=='0') class="mr-1">No</label>
                    </div>
                </div>
            </div>
        </fieldset>

        {{-- Track Selection --}}
        <fieldset>
            <legend class="font-semibold text-blue-700">Track Selection</legend>
            <div class="grid md:grid-cols-2 gap-4 mt-3">
                <label class="block"><span class="text-sm font-medium">Track Type *</span>
                    <select name="track_category" id="track-category" required class="{{ $cls }}">
                        <option value="">Select…</option>
                        <option value="Academic Track" @selected(old('track_category')==='Academic Track')>Academic Track</option>
                        <option value="Tech-Pro Track" @selected(old('track_category')==='Tech-Pro Track')>Tech-Pro Track</option>
                    </select>
                </label>
                <label class="block"><span class="text-sm font-medium">Track Program *</span>
                    <select name="track_program" id="track-program" required class="{{ $cls }}">
                        <option value="">Select track type first…</option>
                    </select>
                </label>
            </div>
        </fieldset>

        {{-- Referral --}}
        <fieldset>
            <legend class="font-semibold text-blue-700">How did you learn about Worldstar College of Science and Technology?</legend>
            <p class="text-xs text-slate-500 mb-2">(Please check all that applies)</p>
            <div class="grid md:grid-cols-4 gap-2 mt-2 text-sm">
                @foreach(['Facebook Ads','Radio Ads','Online','Career Talk','Guidance/Teacher','Principal','Friends/Family','Posters','Brochure/Flyers','Tarpaulins'] as $src)
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="referral_sources[]" value="{{ $src }}"
                               @checked(in_array($src, (array) old('referral_sources', []))) class="mr-2">
                        {{ $src }}
                    </label>
                @endforeach
            </div>
            <label class="block mt-3"><span class="text-sm font-medium">Others, please specify</span>
                <input name="referral_other" value="{{ old('referral_other') }}" class="{{ $cls }}"></label>
        </fieldset>

        <div class="flex justify-end">
            <button class="bg-blue-600 text-white px-5 py-2.5 rounded hover:bg-blue-700">
                Submit Pre-Registration
            </button>
        </div>
    </form>
</div>

<script>
    (function () {
        var category = document.getElementById('track-category');
        var program = document.getElementById('track-program');
        if (!category || !program) return;

        var optionsByCategory = {
            'Academic Track': [
                'Arts, Social Sciences, and Humanities',
                'Business and Entrepreneurship',
                'Science, Technology, Engineering & Mathematics (Health & Non-Health)'
            ],
            'Tech-Pro Track': [
                'Automotive and Small Engine Technologies',
                'Business, Hospitality, and Tourism Bundle',
                'Creative Arts and Design Technologies Bundle',
                'ICT support and Computer Programming Technologies Bundle',
                'Industrial Arts Bundle'
            ]
        };

        var oldProgram = @json(old('track_program'));

        function renderPrograms() {
            var selectedCategory = category.value;
            var list = optionsByCategory[selectedCategory] || [];
            program.innerHTML = '';

            var placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = list.length ? 'Select…' : 'Select track type first…';
            program.appendChild(placeholder);

            list.forEach(function (name) {
                var opt = document.createElement('option');
                opt.value = name;
                opt.textContent = name;
                if (oldProgram && oldProgram === name) {
                    opt.selected = true;
                }
                program.appendChild(opt);
            });
        }

        category.addEventListener('change', function () {
            oldProgram = null;
            renderPrograms();
        });

        renderPrograms();
    })();
</script>
@endsection
