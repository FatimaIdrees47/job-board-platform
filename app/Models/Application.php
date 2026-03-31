<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'job_id',
        'candidate_id',
        'cv_path',
        'cover_letter',
        'status',
        'applied_at',
        'withdrawn_at',
    ];

    protected function casts(): array
    {
        return [
            'applied_at'   => 'datetime',
            'withdrawn_at' => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(CandidateProfile::class, 'candidate_id');
    }

    public function screeningAnswers(): HasMany
    {
        return $this->hasMany(ApplicationScreeningAnswer::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(ApplicationStatusHistory::class)
            ->orderBy('created_at', 'desc');
    }

    // ── Helpers ────────────────────────────────────────────────────

    public function canBeWithdrawn(): bool
    {
        return in_array($this->status, ['applied', 'reviewing']);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'applied'     => 'status-applied',
            'reviewing'   => 'status-reviewing',
            'shortlisted' => 'status-shortlisted',
            'interview'   => 'status-interview',
            'offered'     => 'status-offered',
            'rejected'    => 'status-rejected',
            'withdrawn'   => 'status-withdrawn',
            default       => 'status-applied',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'applied'     => 'Applied',
            'reviewing'   => 'Under Review',
            'shortlisted' => 'Shortlisted',
            'interview'   => 'Interview Scheduled',
            'offered'     => 'Offer Extended',
            'rejected'    => 'Rejected',
            'withdrawn'   => 'Withdrawn',
            default       => 'Applied',
        };
    }

    public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function unreadMessagesFor(int $userId): int
    {
        return $this->messages()
            ->where('receiver_id', $userId)
            ->whereNull('read_at')
            ->count();
    }
}
