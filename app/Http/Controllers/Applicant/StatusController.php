<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function form()
    {
        return view('applicant.status-form');
    }

    public function check(Request $request)
    {
        $data = $request->validate([
            'reference_code' => ['required', 'string', 'max:20'],
        ]);

        $applicant = Applicant::with([
                'preferredCourse', 'academicTerm',
                'latestExamResult.examSchedule', 'verification', 'enrollment.section',
            ])
            ->where('reference_code', strtoupper(trim($data['reference_code'])))
            ->first();

        if (! $applicant) {
            return back()->withErrors(['reference_code' => 'Reference code not found.'])->withInput();
        }

        return view('applicant.status', compact('applicant'));
    }
}
