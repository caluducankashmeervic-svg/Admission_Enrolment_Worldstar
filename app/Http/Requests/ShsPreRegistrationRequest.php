<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShsPreRegistrationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // Personal
            'last_name'           => ['required', 'string', 'max:100'],
            'first_name'          => ['required', 'string', 'max:100'],
            'middle_name'         => ['nullable', 'string', 'max:100'],
            'gender'              => ['required', 'in:Male,Female,Other'],
            'birth_date'          => ['required', 'date', 'before:today'],
            'nationality'         => ['required', 'string', 'max:50'],
            'religion'            => ['nullable', 'string', 'max:50'],

            // Contact
            'landline'            => ['nullable', 'string', 'max:30'],
            'mobile'              => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'facebook'            => ['nullable', 'string', 'max:100'],
            'address_line'        => ['required', 'string', 'max:200'],
            'city'                => ['required', 'string', 'max:100'],
            'province'            => ['required', 'string', 'max:100'],
            'email'               => ['nullable', 'email', 'max:150'],

            // Parents
            'father_name'         => ['nullable', 'string', 'max:150'],
            'father_occupation'   => ['nullable', 'string', 'max:100'],
            'father_contact'      => ['nullable', 'string', 'max:30'],
            'mother_name'         => ['nullable', 'string', 'max:150'],
            'mother_occupation'   => ['nullable', 'string', 'max:100'],
            'mother_contact'      => ['nullable', 'string', 'max:30'],

            // Educational background
            'junior_high_school'  => ['required', 'string', 'max:200'],
            'junior_high_address' => ['nullable', 'string', 'max:200'],
            'year_graduated'      => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'school_type'         => ['nullable', 'in:Private,Public'],
            'track_category'      => ['required', 'in:Academic Track,Tech-Pro Track'],
            'track_program'       => ['required', 'in:Arts, Social Sciences, and Humanities,Business and Entrepreneurship,Science, Technology, Engineering & Mathematics (Health & Non-Health),Automotive and Small Engine Technologies,Business, Hospitality, and Tourism Bundle,Creative Arts and Design Technologies Bundle,ICT support and Computer Programming Technologies Bundle,Industrial Arts Bundle'],
            'varsity_player'      => ['nullable', 'boolean'],
            'school_dancer'       => ['nullable', 'boolean'],
            'planning_college'    => ['nullable', 'boolean'],

            'family_income'       => ['nullable', 'string', 'max:50'],
            'financial_assistance'=> ['nullable', 'boolean'],

            // Referral
            'referral_sources'    => ['nullable', 'array'],
            'referral_sources.*'  => ['string', 'max:50'],
            'referral_other'      => ['nullable', 'string', 'max:150'],
        ];
    }
}
