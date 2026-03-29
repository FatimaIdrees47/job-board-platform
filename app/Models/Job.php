<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Job extends Model
{
    use SoftDeletes;

    protected $table = 'job_listings';

    protected $fillable = [
        'employer_id',
        'category_id',
        'title',
        'slug',
        'type',
        'location',
        'is_remote',
        'is_hybrid',
        'salary_min',
        'salary_max',
        'salary_currency',
        'salary_period',
        'salary_negotiable',
        'show_salary',
        'experience_level',
        'description',
        'requirements',
        'benefits',
        'application_method',
        'external_url',
        'deadline',
        'status',
        'is_featured',
        'featured_until',
        'views_count',
        'applications_count',
        'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'is_remote'         => 'boolean',
            'is_hybrid'         => 'boolean',
            'salary_negotiable' => 'boolean',
            'show_salary'       => 'boolean',
            'is_featured'       => 'boolean',
            'is_approved'       => 'boolean',
            'deadline'          => 'date',
            'featured_until'    => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────

    public function employer(): BelongsTo
    {
        return $this->belongsTo(EmployerProfile::class, 'employer_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function screeningQuestions(): HasMany
    {
        return $this->hasMany(JobScreeningQuestion::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('is_approved', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
                     ->where('featured_until', '>', now());
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('deadline')
              ->orWhere('deadline', '>=', now());
        });
    }

    // ── Helpers ────────────────────────────────────────────────────

    public function getSalaryDisplayAttribute(): string
    {
        if (! $this->show_salary) {
            return 'Salary hidden';
        }

        if ($this->salary_negotiable && ! $this->salary_min) {
            return 'Negotiable';
        }

        $currency = $this->salary_currency;
        $period   = $this->salary_period === 'monthly' ? '/mo' : '/yr';

        if ($this->salary_min && $this->salary_max) {
            return $currency.' '.number_format($this->salary_min).' – '.number_format($this->salary_max).$period;
        }

        if ($this->salary_min) {
            return $currency.' '.number_format($this->salary_min).$period;
        }

        return $this->salary_negotiable ? 'Negotiable' : 'Not specified';
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->deadline && $this->deadline->isPast();
    }

    protected static function booted(): void
    {
        static::creating(function (Job $job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title).'-'.Str::random(6);
            }
        });
    }
}