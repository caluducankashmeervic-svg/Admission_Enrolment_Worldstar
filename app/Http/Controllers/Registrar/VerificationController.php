<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\Verification;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function lookup()
    {
        return view('registrar.lookup');
    }

    public function find(Request $request)
    {
        $request->validate([
            'reference_code' => ['required', 'string', 'max:20'],
        ]);

        $applicant = Applicant::with([
            'preferredCourse', 'academicTerm', 'latestExamResult.examSchedule', 'verification', 'enrollment',
        ])->where('reference_code', $request->reference_code)->first();

        if (! $applicant) {
            return back()->withErrors(['reference_code' => 'Reference code not found.'])
                ->withInput();
        }

        AuditLog::record('registrar.lookup', $applicant);
        return redirect()->route('registrar.verify.show', $applicant->id);
    }

    public function show(Applicant $applicant)
    {
        $applicant->load([
            'preferredCourse', 'academicTerm', 'latestExamResult.examSchedule', 'verification',
        ]);

        return view('registrar.verify', compact('applicant'));
    }

    public function store(Request $request, Applicant $applicant)
    {
        $data = $request->validate([
            'doc_form_137'       => ['sometimes', 'boolean'],
            'doc_psa_birth_cert' => ['sometimes', 'boolean'],
            'doc_good_moral'     => ['sometimes', 'boolean'],
            'doc_id_photos'      => ['sometimes', 'boolean'],
            'doc_medical_cert'   => ['sometimes', 'boolean'],
            'doc_diploma'        => ['sometimes', 'boolean'],
            'remarks'            => ['nullable', 'string', 'max:500'],
        ]);

        $docs = collect(Verification::REQUIRED_DOCS)
            ->mapWithKeys(fn ($d) => [$d => (bool) ($data[$d] ?? false)])
            ->all();

        $verification = Verification::updateOrCreate(
            ['applicant_id' => $applicant->id],
            array_merge($docs, [
                'registrar_id' => $request->user()->id,
                'remarks'      => $data['remarks'] ?? null,
            ])
        );

        $complete = $verification->allDocumentsComplete();
        $verification->update([
            'status'      => $complete ? Verification::STATUS_VERIFIED : Verification::STATUS_INCOMPLETE,
            'verified_at' => $complete ? now() : null,
        ]);

        if ($complete && $applicant->status !== Applicant::STATUS_ENROLLED) {
            $applicant->update(['status' => Applicant::STATUS_VERIFIED]);
        }

        AuditLog::record('registrar.verification.save', $applicant, [
            'status' => $verification->status,
        ]);

        return back()->with('status', $complete
            ? 'All documents verified. Applicant is ready for enrollment.'
            : 'Verification saved. Some documents are still missing.');
    }
}
