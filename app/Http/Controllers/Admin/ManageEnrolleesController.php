<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ManageEnrolleesController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString() ?: 'pending';

        $query = Applicant::with(['user', 'preferredCourse', 'academicTerm'])
            ->orderByDesc('id');

        $query = match ($status) {
            'confirmed' => $query->whereIn('status', [Applicant::STATUS_VERIFIED, Applicant::STATUS_ENROLLED]),
            'denied'    => $query->where('status', Applicant::STATUS_REJECTED),
            default     => $query->whereIn('status', [
                Applicant::STATUS_PRE_REGISTERED,
                Applicant::STATUS_EXAM_SCHEDULED,
                Applicant::STATUS_EXAM_COMPLETED,
            ]),
        };

        return view('admin.manage-enrollees', [
            'applicants' => $query->paginate(15)->withQueryString(),
            'status'     => $status,
        ]);
    }

    public function confirm(Applicant $applicant)
    {
        $applicant->update(['status' => Applicant::STATUS_VERIFIED]);

        // Assign a student number if the linked user doesn't have one yet.
        if ($applicant->user && ! $applicant->user->student_no) {
            $year = now()->format('Y');
            $seq  = str_pad((string) ($applicant->id ?? 1), 5, '0', STR_PAD_LEFT);
            $applicant->user->update(['student_no' => "S{$year}-{$seq}"]);
        }

        AuditLog::record('admin.applicant.confirm', $applicant);

        return back()->with('status', "Applicant {$applicant->full_name} confirmed.");
    }

    public function deny(Applicant $applicant)
    {
        $applicant->update(['status' => Applicant::STATUS_REJECTED]);
        AuditLog::record('admin.applicant.deny', $applicant);

        return back()->with('status', "Applicant {$applicant->full_name} denied.");
    }

    public function show(Applicant $applicant)
    {
        $applicant->load(['user', 'preferredCourse', 'academicTerm', 'latestExamResult', 'verification', 'enrollment']);
        return view('admin.enrollee-show', compact('applicant'));
    }
}
