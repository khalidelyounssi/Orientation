<?php

namespace App\Http\Requests;

use App\Models\Candidate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CandidateRequest extends FormRequest
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
            'marital_status' => ['nullable', 'integer'],
            'application_mode' => ['nullable', 'integer'],
            'application_order' => ['nullable', 'integer'],
            'course' => ['nullable', 'integer'],
            'daytime_evening_attendance' => ['nullable', 'integer'],
            'previous_qualification' => ['nullable', 'integer'],
            'previous_qualification_grade' => ['nullable', 'numeric'],
            'nacionality' => ['nullable', 'integer'],
            'mothers_qualification' => ['nullable', 'integer'],
            'fathers_qualification' => ['nullable', 'integer'],
            'mothers_occupation' => ['nullable', 'integer'],
            'fathers_occupation' => ['nullable', 'integer'],
            'admission_grade' => ['nullable', 'numeric'],
            'displaced' => ['nullable', 'boolean'],
            'educational_special_needs' => ['nullable', 'boolean'],
            'debtor' => ['nullable', 'boolean'],
            'tuition_fees_up_to_date' => ['nullable', 'boolean'],
            'gender' => ['nullable', 'integer'],
            'scholarship_holder' => ['nullable', 'boolean'],
            'age_at_enrollment' => ['nullable', 'integer', 'min:0'],
            'international' => ['nullable', 'boolean'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'status' => ['nullable', Rule::in(Candidate::STATUSES)],
            'notes' => ['nullable', 'string'],
            'external_student_id' => ['nullable', 'string', 'max:255'],
            'academic_status' => ['nullable', 'string', 'max:255'],
            'predictive_data' => ['nullable', 'array'],
            'predictive_data.*' => ['nullable'],
        ];
    }
}
