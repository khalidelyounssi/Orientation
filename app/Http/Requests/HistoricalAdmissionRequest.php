<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HistoricalAdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'bac_type' => ['required', 'string', 'max:255'],
            'bac_year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'general_average' => ['required', 'numeric', 'between:0,20'],
            'math_score' => ['nullable', 'numeric', 'between:0,20'],
            'physics_score' => ['nullable', 'numeric', 'between:0,20'],
            'french_score' => ['nullable', 'numeric', 'between:0,20'],
            'english_score' => ['nullable', 'numeric', 'between:0,20'],
            'computer_science_score' => ['nullable', 'numeric', 'between:0,20'],
            'biology_score' => ['nullable', 'numeric', 'between:0,20'],
            'economics_score' => ['nullable', 'numeric', 'between:0,20'],
            'has_repeated_year' => ['nullable', 'boolean'],
            'number_of_repeated_years' => ['nullable', 'integer', 'min:0'],
            'first_choice' => ['nullable', 'string', 'max:255'],
            'second_choice' => ['nullable', 'string', 'max:255'],
            'third_choice' => ['nullable', 'string', 'max:255'],
            'preferred_domain' => ['nullable', 'string', 'max:255'],
            'admitted_program' => ['nullable', 'string', 'max:255'],
            'followed_program' => ['nullable', 'string', 'max:255'],
            'recommended_program' => ['nullable', 'string', 'max:255'],
            'first_year_result' => ['nullable', 'string', 'max:255'],
            'final_status' => ['nullable', 'string', 'max:255'],
            'gpa' => ['nullable', 'numeric', 'between:0,20'],
        ];
    }
}
