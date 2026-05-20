<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\Course;
use App\Services\ReferenceCodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmissionController extends Controller
{
    public function __construct(protected ReferenceCodeGenerator $codes) {}

    public function create(Request $request)
    {
        $user      = $request->user();
        $applicant = Applicant::where('user_id', $user->id)->latest()->first();

        return view('applicant.admission', [
            'user'      => $user,
            'applicant' => $applicant,
            'courses'   => Course::where('is_active', true)->orderBy('code')->get(),
            'terms'     => AcademicTerm::where('is_active', true)->orderBy('school_year', 'desc')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'campus'              => ['required', 'string', 'max:100'],
            'school_year'         => ['required', 'string', 'max:20'],
            'semester'            => ['required', 'in:1st,2nd,Summer'],
            'preferred_course_id' => ['required', 'integer', 'exists:courses,id'],
            'date_applied'        => ['required', 'date'],
        ]);

        $user = $request->user();
        $term = AcademicTerm::firstOrCreate(
            ['school_year' => $data['school_year'], 'semester' => $data['semester']],
            ['is_active' => false],
        );

        $applicant = DB::transaction(function () use ($data, $user, $term) {
            $existing = Applicant::where('user_id', $user->id)->first();

            $payload = [
                'user_id'             => $user->id,
                'preferred_course_id' => $data['preferred_course_id'],
                'academic_term_id'    => $term->id,
                'first_name'          => $user->firstname ?: $user->name,
                'middle_name'         => $user->middlename,
                'last_name'           => $user->lastname ?: '',
                'email'               => $user->email,
                'mobile'              => $user->contact_no,
                'status'              => Applicant::STATUS_PRE_REGISTERED,
            ];

            if ($existing) {
                $existing->update($payload);
                return $existing;
            }

            $payload['reference_code'] = $this->codes->generate('APP');
            return Applicant::create($payload);
        });

        AuditLog::record('applicant.admission.submit', $applicant, [
            'campus' => $data['campus'], 'school_year' => $data['school_year'],
        ]);

        return redirect()->route('applicant.pre-register.success', ['code' => $applicant->reference_code])
            ->with('status', 'Admission application submitted.');
    }
}
