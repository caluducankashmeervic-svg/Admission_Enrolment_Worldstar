<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Barryvdh\DomPDF\Facade\Pdf;

class PreRegistrationPdfController extends Controller
{
    public function download(Applicant $applicant)
    {
        $applicant->load(['preferredCourse', 'academicTerm']);

        $filename = 'pre-reg-' . $applicant->reference_code . '.pdf';

        $pdf = Pdf::loadView('documents.pre-registration', compact('applicant'))
            ->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }
}
