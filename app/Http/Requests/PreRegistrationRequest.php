<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreRegistrationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'first_name'            => ['required', 'string', 'max:100'],
            'middle_name'           => ['nullable', 'string', 'max:100'],
            'last_name'             => ['required', 'string', 'max:100'],
            'suffix'                => ['nullable', 'string', 'max:10'],
            'gender'                => ['required', 'in:Male,Female,Other'],
            'birth_date'            => ['required', 'date', 'before:today'],
            'civil_status'          => ['required', 'string', 'max:20'],
            'nationality'           => ['required', 'string', 'max:50'],
            'religion'              => ['nullable', 'string', 'max:50'],

            'email'                 => ['nullable', 'email', 'max:150'],
            'mobile'                => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'address_line'          => ['required', 'string', 'max:200'],
            'city'                  => ['required', 'string', 'max:100'],
            'province'              => ['required', 'string', 'max:100'],
            'zip'                   => ['nullable', 'string', 'max:10'],

            'last_school_attended'  => ['required', 'string', 'max:200'],
            'last_school_address'   => ['nullable', 'string', 'max:200'],
            'strand_track'          => ['nullable', 'string', 'max:100'],
            'year_graduated'        => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'gwa'                   => ['nullable', 'numeric', 'between:60,100'],

            'guardian_name'         => ['required', 'string', 'max:150'],
            'guardian_relationship' => ['required', 'string', 'max:50'],
            'guardian_contact'      => ['required', 'string', 'max:20'],

            'preferred_course_id'   => ['required', 'exists:courses,id'],
            'academic_term_id'      => ['required', 'exists:academic_terms,id'],
        ];
    }
}
