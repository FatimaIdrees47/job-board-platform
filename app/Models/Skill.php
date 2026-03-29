<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Skill extends Model
{
    protected $fillable = ['name', 'slug', 'category'];

    public function candidateSkills(): HasMany
    {
        return $this->hasMany(CandidateSkill::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Skill $skill) {
            if (empty($skill->slug)) {
                $skill->slug = Str::slug($skill->name);
            }
        });
    }
}