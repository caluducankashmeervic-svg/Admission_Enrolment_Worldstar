<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Services\CorPdfGenerator;
use Illuminate\Support\Facades\Storage;

class CORController extends Controller
{
    public function __construct(protected CorPdfGenerator $cor) {}

    public function download(Enrollment $enrollment)
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk(config('filesystems.cor_disk'));

        if (! $enrollment->cor_path || ! $disk->exists($enrollment->cor_path)) {
            $enrollment->update(['cor_path' => $this->cor->generate($enrollment)]);
        }

        return $disk->download(
            $enrollment->cor_path,
            "{$enrollment->enrollment_no}.pdf"
        );
    }

    public function preview(Enrollment $enrollment)
    {
        $enrollment->load(['applicant', 'course', 'section', 'academicTerm', 'processor']);
        return view('documents.cor', compact('enrollment'));
    }
}
