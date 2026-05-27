<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\AuditLog;

class TrashController extends Controller
{
    public function index()
    {
        $applicants = Applicant::with(['preferredCourse', 'academicTerm'])
            ->where('status', Applicant::STATUS_REJECTED)
            ->latest()
            ->paginate(25);

        return view('admin.trash', compact('applicants'));
    }

    public function destroy(Applicant $applicant)
    {
        abort_unless($applicant->status === Applicant::STATUS_REJECTED, 403, 'Only rejected applicants can be permanently deleted.');

        AuditLog::record('applicant.permanent_delete', $applicant);
        $applicant->delete();

        return back()->with('status', "Applicant {$applicant->reference_code} permanently deleted.");
    }
}
