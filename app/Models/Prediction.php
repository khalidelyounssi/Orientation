<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'candidate_id',
        'prediction_label',
        'is_dropout_risk',
        'dropout_probability',
        'dropout_probability_percent',
        'threshold',
        'risk_level',
        'recommendation',
        'model_name',
        'model_version',
        'api_response',
        'predicted_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_dropout_risk' => 'boolean',
            'dropout_probability' => 'decimal:4',
            'dropout_probability_percent' => 'decimal:2',
            'threshold' => 'decimal:2',
            'api_response' => 'array',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function predictedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'predicted_by');
    }
}
