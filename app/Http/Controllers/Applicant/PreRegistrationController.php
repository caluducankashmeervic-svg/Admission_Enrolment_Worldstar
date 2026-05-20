<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreRegistrationRequest;
use App\Models\AcademicTerm;
use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\Course;
use App\Services\ReferenceCodeGenerator;
use Illuminate\Support\Facades\DB;

class PreRegistrationController extends Controller
{
    public function __construct(protected ReferenceCodeGenerator $codes) {}

    public function create()
    {
        return view('applicant.pre-register', [
            'courses' => Course::where('is_active', true)->orderBy('code')->get(),
            'terms'   => AcademicTerm::where('is_active', true)->orderBy('school_year', 'desc')->get(),
        ]);
    }

    public function store(PreRegistrationRequest $request)
    {
        $applicant = DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['reference_code'] = $this->codes->generate('APP');
            $data['status']         = Applicant::STATUS_PRE_REGISTERED;
            $data['user_id']        = optional($request->user())->id;

            $a = Applicant::create($data);

            AuditLog::record('applicant.pre_register', $a, [
                'reference_code' => $a->reference_code,
            ]);

            return $a;
        });

        return redirect()
            ->route('applicant.pre-register.success', ['code' => $applicant->reference_code])
            ->with('status', 'Pre-registration submitted successfully.');
    }

    public function success(string $code)
    {
        $applicant = Applicant::where('reference_code', $code)->firstOrFail();
        return view('applicant.reference-code', compact('applicant'));
    }
}
