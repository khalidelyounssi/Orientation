<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'department',
        'faculty',
        'capacity',
        'required_subjects',
        'important_subjects',
        'minimum_average',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'minimum_average' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
