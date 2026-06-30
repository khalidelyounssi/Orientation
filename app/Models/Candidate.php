<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    /**
     * @var list<string>
     */
    public const STATUSES = [
        'pending',
        'predicted',
        'reviewed',
    ];

    /**
     * Features expected by the current XGBoost pipeline.
     *
     * @var list<string>
     */
    public const PREDICTIVE_FIELDS = [
        'DIV_CDE',
        'DEGREE_CDE',
        'MAJOR_1',
        'ACADEMIC_PROBATION',
        'HRS_ENROLLED',
        'PROBATION_HR_TOTAL',
        'HONORS_HR_TOTAL',
        'XFER_HRS_ATTEMPTED',
        'XFER_HRS_EARNED',
        'XFER_HRS_GPA',
        'CAREER_HRS_ATTEMPT',
        'CAREER_HRS_EARNED',
        'CAREER_HRS_GPA',
        'CAREER_QUAL_PTS',
        'CAREER_GPA',
        'REPEAT_CRS_COUNT',
        'FAILED_CRS_COUNT',
        'PASSED_CRS_COUNT',
        'ATTENDANCE_PERCENT',
        'TRM_HRS_ATTEMPT1',
        'TRM_HRS_EARNED1',
        'TRM_HRS_GPA1',
        'TRM_GPA1',
        'TRM_HRS_ATTEMPT2',
        'TRM_HRS_EARNED2',
        'TRM_HRS_GPA2',
        'TRM_GPA2',
        'TRM_HRS_ATTEMPT3',
        'TRM_HRS_EARNED3',
        'TRM_HRS_GPA3',
        'TRM_GPA3',
        'TRM_HRS_ATTEMPT4',
        'TRM_HRS_EARNED4',
        'TRM_HRS_GPA4',
        'TRM_GPA4',
        'TRM_HRS_ATTEMPT5',
        'TRM_HRS_EARNED5',
        'TRM_HRS_GPA5',
        'TRM_GPA5',
        'TRM_HRS_ATTEMPT6',
        'TRM_HRS_EARNED6',
        'TRM_HRS_GPA6',
        'TRM_GPA6',
        'TRM_HRS_ATTEMPT7',
        'TRM_HRS_EARNED7',
        'TRM_HRS_GPA7',
        'TRM_GPA7',
        'TRM_HRS_ATTEMPT8',
        'TRM_HRS_EARNED8',
        'TRM_HRS_GPA8',
        'TRM_GPA8',
        'TRM_HRS_ATTEMPT9',
        'TRM_HRS_EARNED9',
        'TRM_HRS_GPA9',
        'TRM_GPA9',
        'TRM_HRS_ATTEMPT10',
        'TRM_HRS_EARNED10',
        'TRM_HRS_GPA10',
        'TRM_GPA10',
        'CURRENT_BALANCE',
        'BALANCE_PAID_TO_DATE',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'marital_status',
        'application_mode',
        'application_order',
        'course',
        'daytime_evening_attendance',
        'previous_qualification',
        'previous_qualification_grade',
        'nacionality',
        'mothers_qualification',
        'fathers_qualification',
        'mothers_occupation',
        'fathers_occupation',
        'admission_grade',
        'displaced',
        'educational_special_needs',
        'debtor',
        'tuition_fees_up_to_date',
        'gender',
        'scholarship_holder',
        'age_at_enrollment',
        'international',
        'first_name',
        'last_name',
        'email',
        'phone',
        'status',
        'notes',
        'external_student_id',
        'academic_status',
        'predictive_data',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'previous_qualification_grade' => 'decimal:2',
            'admission_grade' => 'decimal:2',
            'displaced' => 'boolean',
            'educational_special_needs' => 'boolean',
            'debtor' => 'boolean',
            'tuition_fees_up_to_date' => 'boolean',
            'scholarship_holder' => 'boolean',
            'international' => 'boolean',
            'predictive_data' => 'array',
        ];
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    /**
     * @return array<string, string>
     */
    public static function predictiveFields(): array
    {
        return collect(self::PREDICTIVE_FIELDS)
            ->mapWithKeys(fn (string $field): array => [$field => str_replace('_', ' ', $field)])
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function predictionPayload(): array
    {
        $data = $this->predictive_data ?? [];

        return collect(self::PREDICTIVE_FIELDS)
            ->mapWithKeys(fn (string $field): array => [$field => $data[$field] ?? null])
            ->all();
    }
}
