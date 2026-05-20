<?php

namespace App\Services;

use App\Models\Applicant;

class ReferenceCodeGenerator
{
    public function generate(string $prefix = 'APP'): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $random = '';
            for ($i = 0; $i < 6; $i++) {
                $random .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }
            $code = "{$prefix}-{$random}";
        } while (Applicant::where('reference_code', $code)->exists());

        return $code;
    }
}
