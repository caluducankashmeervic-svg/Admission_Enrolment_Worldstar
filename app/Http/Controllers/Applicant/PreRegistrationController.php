<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShsPreRegistrationRequest;
use App\Http\Requests\TesdaPreRegistrationRequest;
use App\Models\AcademicTerm;
use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\Course;
use App\Services\ReferenceCodeGenerator;
use Illuminate\Support\Facades\DB;

class PreRegistrationController extends Controller
{
    public function __construct(protected ReferenceCodeGenerator $codes) {}

    /** Program-type chooser (entry point). */
    public function choose()
    {
        return view('applicant.program-choice');
    }

    /* ---------------- Senior High School ---------------- */

    public function createShs()
    {
        return view('applicant.pre-register-shs');
    }

    public function storeShs(ShsPreRegistrationRequest $request)
    {
        $applicant = DB::transaction(function () use ($request) {
            $v = $request->validated();

            $profile = [
                'facebook'             => $v['facebook']            ?? null,
                'landline'             => $v['landline']            ?? null,
                'father_name'          => $v['father_name']         ?? null,
                'father_occupation'    => $v['father_occupation']   ?? null,
                'father_contact'       => $v['father_contact']      ?? null,
                'mother_name'          => $v['mother_name']         ?? null,
                'mother_occupation'    => $v['mother_occupation']   ?? null,
                'mother_contact'       => $v['mother_contact']      ?? null,
                'school_type'          => $v['school_type']         ?? null,
                'track_category'       => $v['track_category'],
                'track_program'        => $v['track_program'],
                'varsity_player'       => (bool)($v['varsity_player']      ?? false),
                'school_dancer'        => (bool)($v['school_dancer']       ?? false),
                'planning_college'     => (bool)($v['planning_college']    ?? false),
                'family_income'        => $v['family_income']       ?? null,
                'financial_assistance' => (bool)($v['financial_assistance']?? false),
                'referral_sources'     => $v['referral_sources']    ?? [],
                'referral_other'       => $v['referral_other']      ?? null,
            ];

            $guardianName    = $v['father_name']    ?? $v['mother_name']    ?? 'N/A';
            $guardianContact = $v['father_contact'] ?? $v['mother_contact'] ?? ($v['mobile'] ?? 'N/A');
            $guardianRel     = isset($v['father_name']) ? 'Father' : (isset($v['mother_name']) ? 'Mother' : 'Guardian');

            $a = Applicant::create([
                'reference_code'        => $this->codes->generate('SHS'),
                'applicant_type'        => Applicant::TYPE_SHS,
                'user_id'               => optional($request->user())->id,
                'first_name'            => $v['first_name'],
                'middle_name'           => $v['middle_name']  ?? null,
                'last_name'             => $v['last_name'],
                'gender'                => $v['gender'],
                'birth_date'            => $v['birth_date'],
                'nationality'           => $v['nationality'],
                'religion'              => $v['religion']     ?? null,
                'email'                 => $v['email']        ?? null,
                'mobile'                => $v['mobile'],
                'address_line'          => $v['address_line'],
                'city'                  => $v['city'],
                'province'              => $v['province'],
                'last_school_attended'  => $v['junior_high_school'],
                'last_school_address'   => $v['junior_high_address'] ?? null,
                'strand_track'          => $v['track_program'],
                'year_graduated'        => $v['year_graduated']      ?? null,
                'guardian_name'         => $guardianName,
                'guardian_relationship' => $guardianRel,
                'guardian_contact'      => $guardianContact,
                'status'                => Applicant::STATUS_PRE_REGISTERED,
                'profile_data'          => $profile,
            ]);

            AuditLog::record('applicant.pre_register.shs', $a, [
                'reference_code' => $a->reference_code,
            ]);

            return $a;
        });

        return redirect()
            ->route('applicant.pre-register.success', ['code' => $applicant->reference_code])
            ->with('status', 'Senior High pre-registration submitted successfully.');
    }

    /* ---------------- TESDA / Diploma ---------------- */

    public function createTesda()
    {
        return view('applicant.pre-register-tesda', [
            'courses' => Course::where('is_active', true)->orderBy('code')->get(),
            'terms'   => AcademicTerm::where('is_active', true)->orderBy('school_year', 'desc')->get(),
        ]);
    }

    public function storeTesda(TesdaPreRegistrationRequest $request)
    {
        $applicant = DB::transaction(function () use ($request) {
            $v = $request->validated();

            $profile = [
                'barangay'               => $v['barangay']               ?? null,
                'district'               => $v['district']               ?? null,
                'region'                 => $v['region']                 ?? null,
                'facebook'               => $v['facebook']               ?? null,
                'employment_status'      => $v['employment_status'],
                'birthplace_city'        => $v['birthplace_city']        ?? null,
                'birthplace_province'    => $v['birthplace_province']    ?? null,
                'birthplace_region'      => $v['birthplace_region']      ?? null,
                'educational_attainment' => $v['educational_attainment'],
                'parent_full_name'       => $v['parent_full_name'],
                'parent_address'         => $v['parent_address']         ?? null,
                'parent_contact'         => $v['parent_contact'],
                'classifications'        => $v['classifications']        ?? [],
                'classification_other'   => $v['classification_other']   ?? null,
                'disability_types'       => $v['disability_types']       ?? [],
                'disability_causes'      => $v['disability_causes']      ?? [],
                'ncae_taken'             => (bool)($v['ncae_taken']      ?? false),
                'ncae_where'             => $v['ncae_where']             ?? null,
                'ncae_when'              => $v['ncae_when']              ?? null,
                'course_qualification'   => $v['course_qualification'],
                'scholarship_type'       => $v['scholarship_type']       ?? null,
                'beneficiary_1'          => $v['beneficiary_1']          ?? null,
                'beneficiary_2'          => $v['beneficiary_2']          ?? null,
                'privacy_consent'        => true,
            ];

            $a = Applicant::create([
                'reference_code'        => $this->codes->generate('TES'),
                'applicant_type'        => Applicant::TYPE_TESDA,
                'user_id'               => optional($request->user())->id,
                'preferred_course_id'   => $v['preferred_course_id'],
                'academic_term_id'      => $v['academic_term_id'],
                'first_name'            => $v['first_name'],
                'middle_name'           => $v['middle_name'] ?? null,
                'last_name'             => $v['last_name'],
                'suffix'                => $v['suffix']      ?? null,
                'gender'                => $v['gender'],
                'birth_date'            => $v['birth_date'],
                'civil_status'          => $v['civil_status'],
                'nationality'           => $v['nationality'],
                'email'                 => $v['email']       ?? null,
                'mobile'                => $v['mobile'],
                'address_line'          => $v['address_line'],
                'city'                  => $v['city'],
                'province'              => $v['province'],
                'last_school_attended'  => $v['educational_attainment'],
                'guardian_name'         => $v['parent_full_name'],
                'guardian_relationship' => 'Parent/Guardian',
                'guardian_contact'      => $v['parent_contact'],
                'status'                => Applicant::STATUS_PRE_REGISTERED,
                'profile_data'          => $profile,
            ]);

            AuditLog::record('applicant.pre_register.tesda', $a, [
                'reference_code' => $a->reference_code,
            ]);

            return $a;
        });

        return redirect()
            ->route('applicant.pre-register.success', ['code' => $applicant->reference_code])
            ->with('status', 'TESDA pre-registration submitted successfully.');
    }

    /* ---------------- Success ---------------- */

    public function success(string $code)
    {
        $applicant = Applicant::where('reference_code', $code)->firstOrFail();
        return view('applicant.reference-code', compact('applicant'));
    }
}
