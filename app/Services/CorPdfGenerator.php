<?php

namespace App\Services;

use App\Models\Enrollment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CorPdfGenerator
{
    public function generate(Enrollment $enrollment): string
    {
        $enrollment->load([
            'applicant', 'course', 'section', 'academicTerm', 'processor',
        ]);

        $pdf = Pdf::loadView('documents.cor', ['enrollment' => $enrollment])
            ->setPaper('a4', 'portrait');

        $path = "cor/{$enrollment->enrollment_no}.pdf";
        Storage::disk(config('filesystems.cor_disk'))->put($path, $pdf->output());

        return $path;
    }
}
