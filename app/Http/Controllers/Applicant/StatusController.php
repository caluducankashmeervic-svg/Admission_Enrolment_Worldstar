<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Eager-load relations needed by the status view.
     */
    private const STATUS_RELATIONS = [
        'preferredCourse', 'academicTerm',
        'latestExamResult.examSchedule', 'verification', 'enrollment.section',
    ];

    public function form(Request $request)
    {
        // If the visitor is signed in and already bound to an applicant record,
        // skip the code-entry form and show their status directly.
        if ($user = $request->user()) {
            $applicant = Applicant::with(self::STATUS_RELATIONS)
                ->where('user_id', $user->id)
                ->latest('id')
                ->first();

            if ($applicant) {
                return view('applicant.status', compact('applicant'));
            }
        }

        return view('applicant.status-form');
    }

    public function check(Request $request)
    {
        $data = $request->validate([
            'reference_code' => ['required', 'string', 'max:20'],
        ]);

        $applicant = Applicant::with(self::STATUS_RELATIONS)
            ->where('reference_code', strtoupper(trim($data['reference_code'])))
            ->first();

        if (! $applicant) {
            return back()->withErrors(['reference_code' => 'Reference code not found.'])->withInput();
        }

        return view('applicant.status', compact('applicant'));
    }
}
