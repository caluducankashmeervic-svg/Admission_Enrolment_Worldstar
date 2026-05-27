{{-- TESDA Pre-Registration — read-only structured display --}}
@php
    $p    = $applicant->profile_data ?? [];
    $val  = fn($v) => ($v !== null && $v !== '') ? $v : '—';
    $bool = fn($v) => $v ? 'Yes' : 'No';
    $lbl  = 'text-xs font-medium text-slate-500 uppercase tracking-wide';
    $fld  = 'mt-0.5 text-sm font-medium text-slate-800 border border-slate-200 rounded px-3 py-2 bg-slate-50 min-h-[2.25rem]';
@endphp

<div class="space-y-6 text-sm">

    {{-- 1. Manpower Profile - Name --}}
    <div>
        <h3 class="font-semibold text-blue-700 border-b border-blue-100 pb-1 mb-3">1. Manpower Profile — Name</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div><p class="{{ $lbl }}">Last Name</p><p class="{{ $fld }}">{{ $val($applicant->last_name) }}</p></div>
            <div><p class="{{ $lbl }}">First Name</p><p class="{{ $fld }}">{{ $val($applicant->first_name) }}</p></div>
            <div><p class="{{ $lbl }}">Middle Name</p><p class="{{ $fld }}">{{ $val($applicant->middle_name) }}</p></div>
            <div><p class="{{ $lbl }}">Extension</p><p class="{{ $fld }}">{{ $val($applicant->suffix) }}</p></div>
        </div>
    </div>

    {{-- 1.2 Complete Permanent Mailing Address --}}
    <div>
        <h3 class="font-semibold text-blue-700 border-b border-blue-100 pb-1 mb-3">1.2 Complete Permanent Mailing Address</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div><p class="{{ $lbl }}">Number, Street</p><p class="{{ $fld }}">{{ $val($applicant->address_line) }}</p></div>
            <div><p class="{{ $lbl }}">Barangay</p><p class="{{ $fld }}">{{ $val($p['barangay'] ?? null) }}</p></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3">
            <div><p class="{{ $lbl }}">District</p><p class="{{ $fld }}">{{ $val($p['district'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">City/Municipality</p><p class="{{ $fld }}">{{ $val($applicant->city) }}</p></div>
            <div><p class="{{ $lbl }}">Province</p><p class="{{ $fld }}">{{ $val($applicant->province) }}</p></div>
            <div><p class="{{ $lbl }}">Region</p><p class="{{ $fld }}">{{ $val($p['region'] ?? null) }}</p></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-3">
            <div><p class="{{ $lbl }}">Email / Facebook</p><p class="{{ $fld }}">{{ $val($p['facebook'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">Email Address</p><p class="{{ $fld }}">{{ $val($applicant->email) }}</p></div>
            <div><p class="{{ $lbl }}">Contact No.</p><p class="{{ $fld }}">{{ $val($applicant->mobile) }}</p></div>
        </div>
        <div class="mt-3">
            <p class="{{ $lbl }}">Nationality</p>
            <p class="{{ $fld }} max-w-xs">{{ $val($applicant->nationality) }}</p>
        </div>
    </div>

    {{-- 2. Personal Information --}}
    <div>
        <h3 class="font-semibold text-blue-700 border-b border-blue-100 pb-1 mb-3">2. Personal Information</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <div><p class="{{ $lbl }}">2.1 Sex</p><p class="{{ $fld }}">{{ $val($applicant->gender) }}</p></div>
            <div><p class="{{ $lbl }}">2.2 Civil Status</p><p class="{{ $fld }}">{{ $val($applicant->civil_status) }}</p></div>
            <div><p class="{{ $lbl }}">2.3 Employment Status</p><p class="{{ $fld }}">{{ $val($p['employment_status'] ?? null) }}</p></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3">
            <div><p class="{{ $lbl }}">2.4 Birthdate</p><p class="{{ $fld }}">{{ optional($applicant->birth_date)->format('M d, Y') ?? '—' }}</p></div>
            <div><p class="{{ $lbl }}">2.5 Birthplace — City</p><p class="{{ $fld }}">{{ $val($p['birthplace_city'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">Province</p><p class="{{ $fld }}">{{ $val($p['birthplace_province'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">Region</p><p class="{{ $fld }}">{{ $val($p['birthplace_region'] ?? null) }}</p></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
            <div><p class="{{ $lbl }}">2.6 Educational Attainment</p><p class="{{ $fld }}">{{ $val($p['educational_attainment'] ?? null) }}</p></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-3">
            <div><p class="{{ $lbl }}">2.7 Parent/Guardian Full Name</p><p class="{{ $fld }}">{{ $val($p['parent_full_name'] ?? $applicant->guardian_name) }}</p></div>
            <div><p class="{{ $lbl }}">Complete Permanent Address</p><p class="{{ $fld }}">{{ $val($p['parent_address'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">Contact No.</p><p class="{{ $fld }}">{{ $val($p['parent_contact'] ?? $applicant->guardian_contact) }}</p></div>
        </div>
    </div>

    {{-- 3. Learner/Trainee/Student Classification --}}
    <div>
        <h3 class="font-semibold text-blue-700 border-b border-blue-100 pb-1 mb-3">3. Learner/Trainee/Student Classification</h3>
        @php $classifications = $p['classifications'] ?? []; @endphp
        <p class="{{ $fld }}">
            @if(count($classifications))
                {{ implode(', ', $classifications) }}
                @if(!empty($p['classification_other'])) — {{ $p['classification_other'] }} @endif
            @else
                —
            @endif
        </p>
    </div>

    {{-- 4. Type of Disability (PWD) --}}
    <div>
        <h3 class="font-semibold text-blue-700 border-b border-blue-100 pb-1 mb-3">4. Type of Disability (PWD only)</h3>
        @php $disabilities = $p['disability_types'] ?? []; @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <p class="{{ $lbl }}">Disability Types</p>
                <p class="{{ $fld }}">{{ count($disabilities) ? implode(', ', $disabilities) : '—' }}</p>
            </div>
            <div>
                <p class="{{ $lbl }}">Causes of Disability</p>
                @php $causes = $p['disability_causes'] ?? []; @endphp
                <p class="{{ $fld }}">{{ count($causes) ? implode(', ', $causes) : '—' }}</p>
            </div>
        </div>
    </div>

    {{-- 5. NCAE --}}
    <div>
        <h3 class="font-semibold text-blue-700 border-b border-blue-100 pb-1 mb-3">5. NCAE</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <div><p class="{{ $lbl }}">NCAE Taken?</p><p class="{{ $fld }}">{{ $bool($p['ncae_taken'] ?? false) }}</p></div>
            <div><p class="{{ $lbl }}">Where</p><p class="{{ $fld }}">{{ $val($p['ncae_where'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">When</p><p class="{{ $fld }}">{{ $val($p['ncae_when'] ?? null) }}</p></div>
        </div>
    </div>

    {{-- 6. Course/Qualification --}}
    <div>
        <h3 class="font-semibold text-blue-700 border-b border-blue-100 pb-1 mb-3">6. Course/Qualification</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div><p class="{{ $lbl }}">Course Qualification</p><p class="{{ $fld }}">{{ $val($p['course_qualification'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">Diploma Course</p><p class="{{ $fld }}">{{ $val($p['diploma_course'] ?? null) }}</p></div>
        </div>
    </div>

    {{-- 7. Scholarship / Beneficiaries --}}
    <div>
        <h3 class="font-semibold text-blue-700 border-b border-blue-100 pb-1 mb-3">7. Scholarship</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <div><p class="{{ $lbl }}">Scholarship Type</p><p class="{{ $fld }}">{{ $val($p['scholarship_type'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">Beneficiary 1</p><p class="{{ $fld }}">{{ $val($p['beneficiary_1'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">Beneficiary 2</p><p class="{{ $fld }}">{{ $val($p['beneficiary_2'] ?? null) }}</p></div>
        </div>
    </div>

</div>
