<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 1.25in; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; font-size: 10pt; color: #1e293b; line-height: 1.5; }

    /* ── Page header ── */
    .header { display: flex; align-items: center; border-bottom: 2.5px solid #1d4ed8; padding-bottom: 12px; margin-bottom: 18px; }
    .header img { width: 58px; height: 58px; object-fit: contain; margin-right: 14px; }
    .header-text { flex: 1; }
    .school-name { font-size: 13pt; font-weight: bold; color: #1d4ed8; }
    .school-sub  { font-size: 8.5pt; color: #475569; margin-top: 1px; }
    .doc-title   { text-align: right; font-size: 11pt; font-weight: bold; color: #1e293b; }
    .doc-ref     { text-align: right; font-size: 8.5pt; color: #64748b; margin-top: 2px; }

    /* ── Sections ── */
    .section { margin-bottom: 18px; }
    .section-title {
        font-size: 9pt; font-weight: bold; color: #1d4ed8;
        background: #eff6ff; border-left: 3px solid #1d4ed8;
        padding: 4px 10px; margin-bottom: 10px;
        text-transform: uppercase; letter-spacing: 0.04em;
    }
    .grid { display: flex; flex-wrap: wrap; gap: 10px 16px; }
    .field { flex: 1; min-width: 100px; margin-bottom: 8px; }
    .field.half  { flex: 0 0 calc(50% - 8px); }
    .field.third { flex: 0 0 calc(33.33% - 11px); }
    .field.fourth{ flex: 0 0 calc(25% - 12px); }
    .field.full  { flex: 0 0 100%; }
    .field-label { font-size: 7.5pt; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px; }
    .field-value {
        font-size: 9.5pt; font-weight: 500; color: #0f172a;
        border-bottom: 1px solid #cbd5e1; padding: 3px 0 5px 0;
        min-height: 20px;
    }

    /* ── Checkbox list ── */
    .check-list { display: flex; flex-wrap: wrap; gap: 6px 18px; }
    .check-item { font-size: 9pt; display: flex; align-items: center; gap: 4px; }
    .box { display: inline-block; width: 11px; height: 11px; border: 1px solid #64748b; margin-right: 2px; text-align: center; font-size: 8pt; line-height: 11px; }
    .box.checked { background: #1d4ed8; color: #fff; font-weight: bold; }

    /* ── Footer ── */
    .footer { border-top: 1px solid #cbd5e1; margin-top: 20px; padding-top: 10px; font-size: 8pt; color: #94a3b8; text-align: center; }
    .sig-row { display: flex; gap: 30px; margin-top: 28px; }
    .sig-box { flex: 1; border-top: 1px solid #64748b; padding-top: 4px; text-align: center; font-size: 8.5pt; color: #475569; }
</style>
</head>
<body>

@php
    $p    = $applicant->profile_data ?? [];
    $val  = fn($v) => ($v !== null && $v !== '') ? $v : '';
    $date = optional($applicant->birth_date)->format('F d, Y') ?? '';
    $isShs = $applicant->applicant_type === \App\Models\Applicant::TYPE_SHS;

    // Applicant type label
    $typeLabel = $isShs ? 'Senior High School (SHS)' : 'TESDA Diploma Program';

    // Program
    if ($applicant->preferredCourse) {
        $program = $applicant->preferredCourse->code . ' — ' . $applicant->preferredCourse->name;
    } elseif ($isShs) {
        $program = $val($applicant->strand_track);
    } else {
        $program = $val($p['diploma_course'] ?? '') ?: $val($p['course_qualification'] ?? '');
    }

    $term = $applicant->academicTerm
        ? $applicant->academicTerm->school_year . ' ' . $applicant->academicTerm->semester
        : '';
@endphp

{{-- ── Page header ── --}}
<div class="header">
    <img src="{{ public_path('images/image.png') }}" alt="WCST">
    <div class="header-text">
        <div class="school-name">Worldstar College of Science and Technology</div>
        <div class="school-sub">Alliance Bldg., National Highway, Bantug, Roxas, Philippines 3320</div>
        <div class="school-sub">wcst.2016@gmail.com &nbsp;|&nbsp; 0916 908 8531</div>
    </div>
    <div>
        <div class="doc-title">Pre-Registration Form</div>
        <div class="doc-ref">Ref: {{ $applicant->reference_code }}</div>
        <div class="doc-ref">Date: {{ now()->format('F d, Y') }}</div>
    </div>
</div>

{{-- ── Application Info ── --}}
<div class="section">
    <div class="section-title">Application Information</div>
    <div class="grid">
        <div class="field third"><div class="field-label">Applicant Type</div><div class="field-value">{{ $typeLabel }}</div></div>
        <div class="field third"><div class="field-label">Applied Program</div><div class="field-value">{{ $program }}</div></div>
        <div class="field third"><div class="field-label">Academic Term</div><div class="field-value">{{ $term }}</div></div>
    </div>
</div>

@if($isShs)
{{-- ══════════════════════════ SHS FORM ══════════════════════════ --}}

<div class="section">
    <div class="section-title">Personal Information</div>
    <div class="grid">
        <div class="field fourth"><div class="field-label">Last Name</div><div class="field-value">{{ $val($applicant->last_name) }}</div></div>
        <div class="field fourth"><div class="field-label">First Name</div><div class="field-value">{{ $val($applicant->first_name) }}</div></div>
        <div class="field fourth"><div class="field-label">Middle Name</div><div class="field-value">{{ $val($applicant->middle_name) }}</div></div>
        <div class="field fourth"><div class="field-label">Extension</div><div class="field-value">{{ $val($applicant->suffix) }}</div></div>
    </div>
    <div class="grid" style="margin-top:6px">
        <div class="field fourth"><div class="field-label">Gender</div><div class="field-value">{{ $val($applicant->gender) }}</div></div>
        <div class="field fourth"><div class="field-label">Birthday</div><div class="field-value">{{ $date }}</div></div>
        <div class="field fourth"><div class="field-label">Citizenship</div><div class="field-value">{{ $val($applicant->nationality) }}</div></div>
        <div class="field fourth"><div class="field-label">Religion</div><div class="field-value">{{ $val($applicant->religion) }}</div></div>
    </div>
</div>

<div class="section">
    <div class="section-title">Contact Details</div>
    <div class="grid">
        <div class="field third"><div class="field-label">Landline</div><div class="field-value">{{ $val($p['landline'] ?? '') }}</div></div>
        <div class="field third"><div class="field-label">Mobile Number</div><div class="field-value">{{ $val($applicant->mobile) }}</div></div>
        <div class="field third"><div class="field-label">Facebook Account</div><div class="field-value">{{ $val($p['facebook'] ?? '') }}</div></div>
    </div>
    <div class="grid" style="margin-top:6px">
        <div class="field full"><div class="field-label">Permanent Mailing Address</div><div class="field-value">{{ $val($applicant->address_line) }}</div></div>
    </div>
    <div class="grid" style="margin-top:6px">
        <div class="field third"><div class="field-label">City / Town</div><div class="field-value">{{ $val($applicant->city) }}</div></div>
        <div class="field third"><div class="field-label">Province</div><div class="field-value">{{ $val($applicant->province) }}</div></div>
        <div class="field third"><div class="field-label">Email Address</div><div class="field-value">{{ $val($applicant->email) }}</div></div>
    </div>
</div>

<div class="section">
    <div class="section-title">Parents</div>
    <div class="grid">
        <div class="field third"><div class="field-label">Father's Name</div><div class="field-value">{{ $val($p['father_name'] ?? '') }}</div></div>
        <div class="field third"><div class="field-label">Occupation</div><div class="field-value">{{ $val($p['father_occupation'] ?? '') }}</div></div>
        <div class="field third"><div class="field-label">Contact No.</div><div class="field-value">{{ $val($p['father_contact'] ?? '') }}</div></div>
    </div>
    <div class="grid" style="margin-top:6px">
        <div class="field third"><div class="field-label">Mother's Name</div><div class="field-value">{{ $val($p['mother_name'] ?? '') }}</div></div>
        <div class="field third"><div class="field-label">Occupation</div><div class="field-value">{{ $val($p['mother_occupation'] ?? '') }}</div></div>
        <div class="field third"><div class="field-label">Contact No.</div><div class="field-value">{{ $val($p['mother_contact'] ?? '') }}</div></div>
    </div>
</div>

<div class="section">
    <div class="section-title">Educational Background</div>
    <div class="grid">
        <div class="field half"><div class="field-label">Junior High School</div><div class="field-value">{{ $val($applicant->last_school_attended) }}</div></div>
        <div class="field half"><div class="field-label">School Address</div><div class="field-value">{{ $val($applicant->last_school_address) }}</div></div>
    </div>
    <div class="grid" style="margin-top:6px">
        <div class="field fourth"><div class="field-label">Year Graduated</div><div class="field-value">{{ $val($applicant->year_graduated) }}</div></div>
        <div class="field fourth"><div class="field-label">School Type</div><div class="field-value">{{ $val($p['school_type'] ?? '') }}</div></div>
        <div class="field fourth"><div class="field-label">Est. Family Income</div><div class="field-value">{{ $val($p['family_income'] ?? '') }}</div></div>
        <div class="field fourth"><div class="field-label">Financial Assistance</div><div class="field-value">{{ ($p['financial_assistance'] ?? false) ? 'Yes' : 'No' }}</div></div>
    </div>
</div>

<div class="section">
    <div class="section-title">Track Selection</div>
    <div class="grid">
        <div class="field half"><div class="field-label">Track Type</div><div class="field-value">{{ $val($p['track_category'] ?? '') }}</div></div>
        <div class="field half"><div class="field-label">Track Program / Strand</div><div class="field-value">{{ $val($p['track_program'] ?? $applicant->strand_track) }}</div></div>
    </div>
</div>

@php
    $sources = $p['referral_sources'] ?? [];
    $allSources = ['School Visit','Social Media','Friend/Family Referral','Flyer/Poster','TV/Radio','Other'];
@endphp
<div class="section">
    <div class="section-title">How did you learn about Worldstar College?</div>
    <div class="check-list">
        @foreach($allSources as $s)
            <div class="check-item">
                <span class="box {{ in_array($s, $sources) ? 'checked' : '' }}">{{ in_array($s, $sources) ? '✓' : '' }}</span> {{ $s }}
            </div>
        @endforeach
    </div>
    @if(!empty($p['referral_other']))
        <div style="margin-top:4px;font-size:9pt">Other: {{ $p['referral_other'] }}</div>
    @endif
</div>

@else
{{-- ══════════════════════════ TESDA FORM ══════════════════════════ --}}

<div class="section">
    <div class="section-title">1. Manpower Profile — Name</div>
    <div class="grid">
        <div class="field fourth"><div class="field-label">Last Name</div><div class="field-value">{{ $val($applicant->last_name) }}</div></div>
        <div class="field fourth"><div class="field-label">First Name</div><div class="field-value">{{ $val($applicant->first_name) }}</div></div>
        <div class="field fourth"><div class="field-label">Middle Name</div><div class="field-value">{{ $val($applicant->middle_name) }}</div></div>
        <div class="field fourth"><div class="field-label">Extension</div><div class="field-value">{{ $val($applicant->suffix) }}</div></div>
    </div>
</div>

<div class="section">
    <div class="section-title">1.2 Complete Permanent Mailing Address</div>
    <div class="grid">
        <div class="field half"><div class="field-label">Number, Street</div><div class="field-value">{{ $val($applicant->address_line) }}</div></div>
        <div class="field half"><div class="field-label">Barangay</div><div class="field-value">{{ $val($p['barangay'] ?? '') }}</div></div>
    </div>
    <div class="grid" style="margin-top:6px">
        <div class="field fourth"><div class="field-label">District</div><div class="field-value">{{ $val($p['district'] ?? '') }}</div></div>
        <div class="field fourth"><div class="field-label">City / Municipality</div><div class="field-value">{{ $val($applicant->city) }}</div></div>
        <div class="field fourth"><div class="field-label">Province</div><div class="field-value">{{ $val($applicant->province) }}</div></div>
        <div class="field fourth"><div class="field-label">Region</div><div class="field-value">{{ $val($p['region'] ?? '') }}</div></div>
    </div>
    <div class="grid" style="margin-top:6px">
        <div class="field third"><div class="field-label">Email / Facebook</div><div class="field-value">{{ $val($p['facebook'] ?? '') }}</div></div>
        <div class="field third"><div class="field-label">Email Address</div><div class="field-value">{{ $val($applicant->email) }}</div></div>
        <div class="field third"><div class="field-label">Contact No.</div><div class="field-value">{{ $val($applicant->mobile) }}</div></div>
    </div>
    <div class="grid" style="margin-top:6px">
        <div class="field third"><div class="field-label">Nationality</div><div class="field-value">{{ $val($applicant->nationality) }}</div></div>
    </div>
</div>

<div class="section">
    <div class="section-title">2. Personal Information</div>
    <div class="grid">
        <div class="field third"><div class="field-label">2.1 Sex</div><div class="field-value">{{ $val($applicant->gender) }}</div></div>
        <div class="field third"><div class="field-label">2.2 Civil Status</div><div class="field-value">{{ $val($applicant->civil_status) }}</div></div>
        <div class="field third"><div class="field-label">2.3 Employment Status</div><div class="field-value">{{ $val($p['employment_status'] ?? '') }}</div></div>
    </div>
    <div class="grid" style="margin-top:6px">
        <div class="field fourth"><div class="field-label">2.4 Birthdate</div><div class="field-value">{{ $date }}</div></div>
        <div class="field fourth"><div class="field-label">2.5 Birthplace City</div><div class="field-value">{{ $val($p['birthplace_city'] ?? '') }}</div></div>
        <div class="field fourth"><div class="field-label">Province</div><div class="field-value">{{ $val($p['birthplace_province'] ?? '') }}</div></div>
        <div class="field fourth"><div class="field-label">Region</div><div class="field-value">{{ $val($p['birthplace_region'] ?? '') }}</div></div>
    </div>
    <div class="grid" style="margin-top:6px">
        <div class="field half"><div class="field-label">2.6 Educational Attainment</div><div class="field-value">{{ $val($p['educational_attainment'] ?? '') }}</div></div>
    </div>
    <div class="grid" style="margin-top:6px">
        <div class="field third"><div class="field-label">2.7 Parent/Guardian Full Name</div><div class="field-value">{{ $val($p['parent_full_name'] ?? $applicant->guardian_name) }}</div></div>
        <div class="field third"><div class="field-label">Permanent Address</div><div class="field-value">{{ $val($p['parent_address'] ?? '') }}</div></div>
        <div class="field third"><div class="field-label">Contact No.</div><div class="field-value">{{ $val($p['parent_contact'] ?? $applicant->guardian_contact) }}</div></div>
    </div>
</div>

@php
    $classifications = $p['classifications'] ?? [];
    $allClassifications = [
        '4Ps Beneficiary','Displaced Workers','Farmerettes','Indigenous People',
        'Out-of-School Youth','Returning OFW','Solo Parent','Women','Other',
    ];
@endphp
<div class="section">
    <div class="section-title">3. Learner/Trainee/Student Classification</div>
    <div class="check-list">
        @foreach($allClassifications as $cl)
            <div class="check-item">
                <span class="box {{ in_array($cl, $classifications) ? 'checked' : '' }}">{{ in_array($cl, $classifications) ? '✓' : '' }}</span> {{ $cl }}
            </div>
        @endforeach
    </div>
    @if(!empty($p['classification_other']))
        <div style="margin-top:4px;font-size:9pt">Other: {{ $p['classification_other'] }}</div>
    @endif
</div>

@php
    $disabilityTypes  = $p['disability_types']  ?? [];
    $disabilityCauses = $p['disability_causes'] ?? [];
    $allDisabilityTypes  = ['Visual','Hearing','Speech','Orthopedic','Learning Disability','Mental Disability','Other'];
    $allDisabilityCauses = ['Congenital/Inborn','Acquired','Other'];
@endphp
<div class="section">
    <div class="section-title">4. Type of Disability (PWD only)</div>
    <div class="grid">
        <div class="field half">
            <div class="field-label" style="margin-bottom:4px">Disability Types</div>
            <div class="check-list">
                @foreach($allDisabilityTypes as $dt)
                    <div class="check-item">
                        <span class="box {{ in_array($dt, $disabilityTypes) ? 'checked' : '' }}">{{ in_array($dt, $disabilityTypes) ? '✓' : '' }}</span> {{ $dt }}
                    </div>
                @endforeach
            </div>
        </div>
        <div class="field half">
            <div class="field-label" style="margin-bottom:4px">Causes of Disability</div>
            <div class="check-list">
                @foreach($allDisabilityCauses as $dc)
                    <div class="check-item">
                        <span class="box {{ in_array($dc, $disabilityCauses) ? 'checked' : '' }}">{{ in_array($dc, $disabilityCauses) ? '✓' : '' }}</span> {{ $dc }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">5. NCAE</div>
    <div class="grid">
        <div class="field third"><div class="field-label">NCAE Taken?</div><div class="field-value">{{ ($p['ncae_taken'] ?? false) ? 'Yes' : 'No' }}</div></div>
        <div class="field third"><div class="field-label">Where</div><div class="field-value">{{ $val($p['ncae_where'] ?? '') }}</div></div>
        <div class="field third"><div class="field-label">When</div><div class="field-value">{{ $val($p['ncae_when'] ?? '') }}</div></div>
    </div>
</div>

<div class="section">
    <div class="section-title">6. Course / Qualification &amp; 7. Scholarship</div>
    <div class="grid">
        <div class="field half"><div class="field-label">Course Qualification</div><div class="field-value">{{ $val($p['course_qualification'] ?? '') }}</div></div>
        <div class="field half"><div class="field-label">Diploma Course</div><div class="field-value">{{ $val($p['diploma_course'] ?? '') }}</div></div>
    </div>
    <div class="grid" style="margin-top:6px">
        <div class="field third"><div class="field-label">Scholarship Type</div><div class="field-value">{{ $val($p['scholarship_type'] ?? '') }}</div></div>
        <div class="field third"><div class="field-label">Beneficiary 1</div><div class="field-value">{{ $val($p['beneficiary_1'] ?? '') }}</div></div>
        <div class="field third"><div class="field-label">Beneficiary 2</div><div class="field-value">{{ $val($p['beneficiary_2'] ?? '') }}</div></div>
    </div>
</div>

@endif

{{-- ── Signature Row ── --}}
<div class="sig-row" style="margin-top: 24px;">
    <div class="sig-box">
        <div style="height:36px"></div>
        Applicant's Signature over Printed Name
    </div>
    <div class="sig-box">
        <div style="height:36px"></div>
        Parent / Guardian's Signature over Printed Name
    </div>
    <div class="sig-box">
        <div style="height:36px"></div>
        Registrar's Signature
    </div>
</div>

<div class="footer">
    This document is computer-generated and printed on {{ now()->format('F d, Y') }}.
    Reference code: {{ $applicant->reference_code }} &nbsp;|&nbsp; Worldstar College of Science and Technology
</div>

</body>
</html>
