<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>COR — {{ $enrollment->enrollment_no }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; margin: 28px; }
        h1 { margin: 0; font-size: 18px; letter-spacing: 1px; }
        .sub { color: #555; font-size: 10px; margin-top: 2px; }
        .box { border: 1px solid #888; padding: 10px 14px; margin-top: 14px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 4px 6px; vertical-align: top; }
        .k { color: #555; width: 110px; }
        .sig { margin-top: 40px; display: flex; justify-content: space-between; }
        .sig div { text-align: center; width: 45%; border-top: 1px solid #333; padding-top: 4px; }
        .footer { margin-top: 26px; font-size: 9px; color: #666; text-align: center; }
        .badge { display: inline-block; background: #1e40af; color: #fff; padding: 2px 8px; font-size: 10px; border-radius: 3px; }
    </style>
</head>
<body>
    <div style="text-align: center;">
        <h1>CERTIFICATE OF REGISTRATION</h1>
        <div class="sub">School Enrollment System · Official Document</div>
    </div>

    <div class="box">
        <table>
            <tr>
                <td class="k">Enrollment No.</td>
                <td><strong>{{ $enrollment->enrollment_no }}</strong></td>
                <td class="k">Issued</td>
                <td>{{ $enrollment->enrolled_at?->format('M d, Y g:i A') }}</td>
            </tr>
            <tr>
                <td class="k">Reference</td>
                <td>{{ $enrollment->applicant->reference_code }}</td>
                <td class="k">Status</td>
                <td><span class="badge">{{ strtoupper($enrollment->status) }}</span></td>
            </tr>
        </table>
    </div>

    <div class="box">
        <strong>Student</strong>
        <table>
            <tr><td class="k">Name</td><td>{{ $enrollment->applicant->full_name }}</td></tr>
            <tr><td class="k">Gender</td><td>{{ $enrollment->applicant->gender }}</td>
                <td class="k">Birth Date</td><td>{{ $enrollment->applicant->birth_date?->format('M d, Y') }}</td></tr>
            <tr><td class="k">Mobile</td><td>{{ $enrollment->applicant->mobile }}</td>
                <td class="k">Email</td><td>{{ $enrollment->applicant->email ?? '—' }}</td></tr>
            <tr><td class="k">Address</td>
                <td colspan="3">{{ $enrollment->applicant->address_line }},
                    {{ $enrollment->applicant->city }}, {{ $enrollment->applicant->province }}</td></tr>
        </table>
    </div>

    <div class="box">
        <strong>Program</strong>
        <table>
            <tr><td class="k">Course</td><td>{{ $enrollment->course->code }} — {{ $enrollment->course->name }}</td></tr>
            <tr><td class="k">Department</td><td>{{ $enrollment->course->department }}</td>
                <td class="k">Duration</td><td>{{ $enrollment->course->duration_years }} years</td></tr>
            <tr><td class="k">Section</td><td>{{ $enrollment->section->name }}
                    (Year {{ $enrollment->section->year_level }})</td>
                <td class="k">Term</td>
                <td>{{ $enrollment->academicTerm->school_year }} —
                    {{ $enrollment->academicTerm->semester }} Sem</td></tr>
        </table>
    </div>

    <div class="sig">
        <div>{{ $enrollment->processor->name ?? 'Registrar' }}<br><small>Registrar</small></div>
        <div>{{ $enrollment->applicant->full_name }}<br><small>Student Signature</small></div>
    </div>

    <div class="footer">
        This is a computer-generated document. Reference any inquiries to Enrollment No.
        {{ $enrollment->enrollment_no }}.
    </div>
</body>
</html>
