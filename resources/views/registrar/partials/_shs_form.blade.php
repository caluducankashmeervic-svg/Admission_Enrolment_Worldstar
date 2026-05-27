{{-- SHS Pre-Registration — read-only structured display --}}
@php
    $p   = $applicant->profile_data ?? [];
    $val = fn($v) => ($v !== null && $v !== '') ? $v : '—';
    $bool = fn($v) => $v ? 'Yes' : 'No';
    $lbl = 'text-xs font-medium text-slate-500 uppercase tracking-wide';
    $fld = 'mt-0.5 text-sm font-medium text-slate-800 border border-slate-200 rounded px-3 py-2 bg-slate-50 min-h-[2.25rem]';
@endphp

<div class="space-y-6 text-sm">

    {{-- Personal --}}
    <div>
        <h3 class="font-semibold text-blue-700 border-b border-blue-100 pb-1 mb-3">Personal</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div><p class="{{ $lbl }}">Last Name</p><p class="{{ $fld }}">{{ $val($applicant->last_name) }}</p></div>
            <div><p class="{{ $lbl }}">First Name</p><p class="{{ $fld }}">{{ $val($applicant->first_name) }}</p></div>
            <div><p class="{{ $lbl }}">Middle Name</p><p class="{{ $fld }}">{{ $val($applicant->middle_name) }}</p></div>
            <div><p class="{{ $lbl }}">Extension</p><p class="{{ $fld }}">{{ $val($applicant->suffix) }}</p></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3">
            <div><p class="{{ $lbl }}">Gender</p><p class="{{ $fld }}">{{ $val($applicant->gender) }}</p></div>
            <div><p class="{{ $lbl }}">Birthday</p><p class="{{ $fld }}">{{ optional($applicant->birth_date)->format('M d, Y') ?? '—' }}</p></div>
            <div><p class="{{ $lbl }}">Citizenship</p><p class="{{ $fld }}">{{ $val($applicant->nationality) }}</p></div>
            <div><p class="{{ $lbl }}">Religion</p><p class="{{ $fld }}">{{ $val($applicant->religion) }}</p></div>
        </div>
    </div>

    {{-- Contact Details --}}
    <div>
        <h3 class="font-semibold text-blue-700 border-b border-blue-100 pb-1 mb-3">Contact Details</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <div><p class="{{ $lbl }}">Landline Number</p><p class="{{ $fld }}">{{ $val($p['landline'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">Mobile Number</p><p class="{{ $fld }}">{{ $val($applicant->mobile) }}</p></div>
            <div><p class="{{ $lbl }}">Facebook Account</p><p class="{{ $fld }}">{{ $val($p['facebook'] ?? null) }}</p></div>
        </div>
        <div class="mt-3">
            <p class="{{ $lbl }}">Permanent Mailing Address</p>
            <p class="{{ $fld }}">{{ $val($applicant->address_line) }}</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-3">
            <div><p class="{{ $lbl }}">City/Town</p><p class="{{ $fld }}">{{ $val($applicant->city) }}</p></div>
            <div><p class="{{ $lbl }}">Province</p><p class="{{ $fld }}">{{ $val($applicant->province) }}</p></div>
            <div><p class="{{ $lbl }}">Email Address</p><p class="{{ $fld }}">{{ $val($applicant->email) }}</p></div>
        </div>
    </div>

    {{-- Parents --}}
    <div>
        <h3 class="font-semibold text-blue-700 border-b border-blue-100 pb-1 mb-3">Parents</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <div><p class="{{ $lbl }}">Father</p><p class="{{ $fld }}">{{ $val($p['father_name'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">Occupation</p><p class="{{ $fld }}">{{ $val($p['father_occupation'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">Contact No.</p><p class="{{ $fld }}">{{ $val($p['father_contact'] ?? null) }}</p></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-3">
            <div><p class="{{ $lbl }}">Mother</p><p class="{{ $fld }}">{{ $val($p['mother_name'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">Occupation</p><p class="{{ $fld }}">{{ $val($p['mother_occupation'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">Contact No.</p><p class="{{ $fld }}">{{ $val($p['mother_contact'] ?? null) }}</p></div>
        </div>
    </div>

    {{-- Educational Background --}}
    <div>
        <h3 class="font-semibold text-blue-700 border-b border-blue-100 pb-1 mb-3">Educational Background</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div><p class="{{ $lbl }}">Junior High School</p><p class="{{ $fld }}">{{ $val($applicant->last_school_attended) }}</p></div>
            <div><p class="{{ $lbl }}">Address</p><p class="{{ $fld }}">{{ $val($applicant->last_school_address) }}</p></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3">
            <div><p class="{{ $lbl }}">Year Graduated</p><p class="{{ $fld }}">{{ $val($applicant->year_graduated) }}</p></div>
            <div><p class="{{ $lbl }}">School Type</p><p class="{{ $fld }}">{{ $val($p['school_type'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">Varsity Player?</p><p class="{{ $fld }}">{{ $bool($p['varsity_player'] ?? false) }}</p></div>
            <div><p class="{{ $lbl }}">School Dancer?</p><p class="{{ $fld }}">{{ $bool($p['school_dancer'] ?? false) }}</p></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-3">
            <div><p class="{{ $lbl }}">Planning to Pursue College?</p><p class="{{ $fld }}">{{ $bool($p['planning_college'] ?? false) }}</p></div>
            <div><p class="{{ $lbl }}">Est. Family Annual Income (PHP)</p><p class="{{ $fld }}">{{ $val($p['family_income'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">Need Financial Assistance?</p><p class="{{ $fld }}">{{ $bool($p['financial_assistance'] ?? false) }}</p></div>
        </div>
    </div>

    {{-- Track Selection --}}
    <div>
        <h3 class="font-semibold text-blue-700 border-b border-blue-100 pb-1 mb-3">Track Selection</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div><p class="{{ $lbl }}">Track Type</p><p class="{{ $fld }}">{{ $val($p['track_category'] ?? null) }}</p></div>
            <div><p class="{{ $lbl }}">Track Program</p><p class="{{ $fld }}">{{ $val($p['track_program'] ?? $applicant->strand_track) }}</p></div>
        </div>
    </div>

    {{-- Referral Sources --}}
    <div>
        <h3 class="font-semibold text-blue-700 border-b border-blue-100 pb-1 mb-3">How did you learn about Worldstar College?</h3>
        <div>
            <p class="{{ $lbl }}">Referral Sources</p>
            <p class="{{ $fld }}">
                @php $sources = $p['referral_sources'] ?? []; @endphp
                @if(count($sources))
                    {{ implode(', ', $sources) }}
                    @if(!empty($p['referral_other'])) — {{ $p['referral_other'] }} @endif
                @else
                    —
                @endif
            </p>
        </div>
    </div>

</div>
