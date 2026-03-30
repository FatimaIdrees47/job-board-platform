<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\CandidateExperience;
use App\Models\CandidateEducation;

class CandidateProfile extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'headline',
        'bio',
        'location',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'profile_completion',
        'visibility',
        'is_open_to_work',
    ];

    protected function casts(): array
    {
        return [
            'is_open_to_work'    => 'boolean',
            'profile_completion' => 'integer',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(CandidateSkill::class, 'candidate_id');
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(CandidateExperience::class, 'candidate_id')
            ->orderBy('start_date', 'desc');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(CandidateEducation::class, 'candidate_id')
            ->orderBy('start_year', 'desc');
    }

    // ── Media Collections ──────────────────────────────────────────

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('cv')
            ->acceptsMimeTypes([
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ]);
    }

    // ── Helpers ────────────────────────────────────────────────────

    public function getPhotoUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('photo')
            ?: 'https://ui-avatars.com/api/?name=' . urlencode($this->user->name) . '&background=1A1A24&color=A78BFA&size=128';
    }

    public function getPrimaryCvAttribute()
    {
        return $this->getMedia('cv')->first();
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'candidate_id');
    }

    public function savedJobs(): HasMany
    {
        return $this->hasMany(SavedJob::class, 'candidate_id');
    }
}
