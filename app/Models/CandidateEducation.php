<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateEducation extends Model
{
    protected $fillable = [
        'candidate_id',
        'institution',
        'degree',
        'field',
        'start_year',
        'end_year',
        'is_current',
    ];

    protected function casts(): array
    {
        return [
            'start_year' => 'integer',
            'end_year'   => 'integer',
            'is_current' => 'boolean',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(CandidateProfile::class, 'candidate_id');
    }
}