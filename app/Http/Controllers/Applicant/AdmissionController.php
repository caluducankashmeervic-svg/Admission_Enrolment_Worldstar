<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function create(Request $request)
    {
        $user      = $request->user();
        $applicant = Applicant::with(['preferredCourse', 'academicTerm'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        return view('applicant.admission', [
            'user'      => $user,
            'applicant' => $applicant,
        ]);
    }

    public function status(Request $request)
    {
        $user      = $request->user();
        $applicant = Applicant::with([
            'preferredCourse', 'academicTerm',
            'latestExamResult.examSchedule', 'enrollment.section',
            'verification',
        ])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        return view('applicant.my-status', compact('applicant'));
    }
}
