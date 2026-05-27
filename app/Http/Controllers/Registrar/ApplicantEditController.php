<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\Course;
use Illuminate\Http\Request;

class ApplicantEditController extends Controller
{
    public function edit(Applicant $applicant)
    {
        $applicant->load(['preferredCourse', 'academicTerm']);
        $courses = Course::where('is_active', true)->orderBy('code')->get();
        $terms   = AcademicTerm::orderByDesc('school_year')->get();

        return view('registrar.applicant-edit', compact('applicant', 'courses', 'terms'));
    }

    public function update(Request $request, Applicant $applicant)
    {
        $validated = $request->validate([
            // Core model fields
            'first_name'            => ['required', 'string', 'max:100'],
            'middle_name'           => ['nullable', 'string', 'max:100'],
            'last_name'             => ['required', 'string', 'max:100'],
            'suffix'                => ['nullable', 'string', 'max:20'],
            'gender'                => ['nullable', 'string', 'max:20'],
            'birth_date'            => ['nullable', 'date'],
            'nationality'           => ['nullable', 'string', 'max:100'],
            'religion'              => ['nullable', 'string', 'max:100'],
            'civil_status'          => ['nullable', 'string', 'max:50'],
            'email'                 => ['nullable', 'email', 'max:200'],
            'mobile'                => ['nullable', 'string', 'max:20'],
            'address_line'          => ['nullable', 'string', 'max:255'],
            'city'                  => ['nullable', 'string', 'max:100'],
            'province'              => ['nullable', 'string', 'max:100'],
            'last_school_attended'  => ['nullable', 'string', 'max:255'],
            'last_school_address'   => ['nullable', 'string', 'max:255'],
            'strand_track'          => ['nullable', 'string', 'max:100'],
            'year_graduated'        => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'preferred_course_id'   => ['nullable', 'exists:courses,id'],
            'academic_term_id'      => ['nullable', 'exists:academic_terms,id'],
            'guardian_name'         => ['nullable', 'string', 'max:255'],
            'guardian_relationship' => ['nullable', 'string', 'max:100'],
            'guardian_contact'      => ['nullable', 'string', 'max:20'],
            // profile_data extras
            'profile'               => ['nullable', 'array'],
        ]);

        $modelFields = array_intersect_key($validated, array_flip([
            'first_name', 'middle_name', 'last_name', 'suffix',
            'gender', 'birth_date', 'nationality', 'religion', 'civil_status',
            'email', 'mobile', 'address_line', 'city', 'province',
            'last_school_attended', 'last_school_address', 'strand_track', 'year_graduated',
            'preferred_course_id', 'academic_term_id',
            'guardian_name', 'guardian_relationship', 'guardian_contact',
        ]));

        $profile = $request->input('profile', []);

        // Normalise boolean fields
        foreach (['varsity_player', 'school_dancer', 'planning_college', 'financial_assistance', 'ncae_taken'] as $boolKey) {
            if (array_key_exists($boolKey, $profile)) {
                $profile[$boolKey] = (bool) $profile[$boolKey];
            }
        }

        // Merge into existing profile_data (keeps keys not present in form)
        $mergedProfile = array_merge($applicant->profile_data ?? [], $profile);

        $applicant->update(array_merge($modelFields, ['profile_data' => $mergedProfile]));

        AuditLog::record('registrar.applicant.edit', $applicant, [
            'reference_code' => $applicant->reference_code,
        ]);

        return redirect()
            ->route('registrar.verify.show', $applicant)
            ->with('status', 'Applicant record updated successfully.');
    }
}
