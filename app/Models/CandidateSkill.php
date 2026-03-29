<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateSkill extends Model
{
    protected $fillable = [
        'candidate_id',
        'skill_id',
        'years_experience',
        'level',
    ];

    protected function casts(): array
    {
        return [
            'years_experience' => 'integer',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(CandidateProfile::class, 'candidate_id');
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}