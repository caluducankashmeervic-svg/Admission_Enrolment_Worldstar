<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TesdaPreRegistrationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // Manpower Profile
            'last_name'              => ['required', 'string', 'max:100'],
            'first_name'             => ['required', 'string', 'max:100'],
            'middle_name'            => ['nullable', 'string', 'max:100'],
            'suffix'                 => ['nullable', 'string', 'max:10'],

            // Permanent Address
            'address_line'           => ['required', 'string', 'max:200'],
            'barangay'               => ['nullable', 'string', 'max:100'],
            'district'               => ['nullable', 'string', 'max:100'],
            'city'                   => ['required', 'string', 'max:100'],
            'province'               => ['required', 'string', 'max:100'],
            'region'                 => ['nullable', 'string', 'max:100'],

            'email'                  => ['nullable', 'email', 'max:150'],
            'facebook'               => ['nullable', 'string', 'max:100'],
            'mobile'                 => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'nationality'            => ['required', 'string', 'max:50'],

            // Personal
            'gender'                 => ['required', 'in:Male,Female,Other'],
            'civil_status'           => ['required', 'in:Single,Married,Widow/er,Separated'],
            'employment_status'      => ['required', 'in:Employed,Unemployed'],
            'birth_date'             => ['required', 'date', 'before:today'],
            'birthplace_city'        => ['nullable', 'string', 'max:100'],
            'birthplace_province'    => ['nullable', 'string', 'max:100'],
            'birthplace_region'      => ['nullable', 'string', 'max:100'],

            'educational_attainment' => ['required', 'string', 'max:60'],

            // Parent/Guardian
            'parent_full_name'       => ['required', 'string', 'max:150'],
            'parent_address'         => ['nullable', 'string', 'max:200'],
            'parent_contact'         => ['required', 'string', 'max:30'],

            // Classifications
            'classifications'        => ['nullable', 'array'],
            'classifications.*'      => ['string', 'max:80'],
            'classification_other'   => ['nullable', 'string', 'max:150'],

            // Disability (PWD only)
            'disability_types'       => ['nullable', 'array'],
            'disability_types.*'     => ['string', 'max:80'],
            'disability_causes'      => ['nullable', 'array'],
            'disability_causes.*'    => ['string', 'max:30'],

            // NCAE
            'ncae_taken'             => ['nullable', 'boolean'],
            'ncae_where'             => ['nullable', 'string', 'max:150'],
            'ncae_when'              => ['nullable', 'string', 'max:30'],

            // Course
            'course_qualification'   => ['required', 'string', 'max:150'],
            'scholarship_type'       => ['nullable', 'string', 'max:50'],

            // Insurance Beneficiaries
            'beneficiary_1'          => ['nullable', 'string', 'max:200'],
            'beneficiary_2'          => ['nullable', 'string', 'max:200'],

            // Privacy
            'privacy_consent'        => ['required', 'accepted'],

            // Diploma course (TESDA fixed list)
            'diploma_course'         => ['required', 'in:CST (Computer Science Technology),CET (Computer Engineering Technology),EET (Electronics Engineering Technology),ICT (Information and Communications Technology)'],
        ];
    }

    public function messages(): array
    {
        return [
            'privacy_consent.accepted' => 'You must agree to the Privacy Disclaimer to proceed.',
        ];
    }
}
