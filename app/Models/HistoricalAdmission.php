<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricalAdmission extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'bac_type',
        'bac_year',
        'general_average',
        'math_score',
        'physics_score',
        'french_score',
        'english_score',
        'computer_science_score',
        'biology_score',
        'economics_score',
        'has_repeated_year',
        'number_of_repeated_years',
        'first_choice',
        'second_choice',
        'third_choice',
        'preferred_domain',
        'admitted_program',
        'followed_program',
        'recommended_program',
        'first_year_result',
        'final_status',
        'gpa',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'general_average' => 'decimal:2',
            'math_score' => 'decimal:2',
            'physics_score' => 'decimal:2',
            'french_score' => 'decimal:2',
            'english_score' => 'decimal:2',
            'computer_science_score' => 'decimal:2',
            'biology_score' => 'decimal:2',
            'economics_score' => 'decimal:2',
            'gpa' => 'decimal:2',
            'has_repeated_year' => 'boolean',
        ];
    }
}
