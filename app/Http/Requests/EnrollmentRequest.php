<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isRegistrar();
    }

    public function rules(): array
    {
        return [
            'applicant_id' => ['required', 'exists:applicants,id'],
            'course_id'    => ['required', 'exists:courses,id'],
            'section_id'   => ['nullable', 'exists:sections,id'],
        ];
    }
}
