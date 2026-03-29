<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EmployerProfile extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'company_name',
        'company_slug',
        'industry',
        'size_range',
        'website',
        'description',
        'location',
        'is_remote_friendly',
        'is_verified',
        'linkedin_url',
        'twitter_url',
        'overall_rating',
        'total_reviews',
    ];

    protected function casts(): array
    {
        return [
            'is_remote_friendly' => 'boolean',
            'is_verified'        => 'boolean',
            'overall_rating'     => 'decimal:2',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Media Collections ──────────────────────────────────────────

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
             ->singleFile()
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml']);

        $this->addMediaCollection('cover')
             ->singleFile()
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    // ── Helpers ────────────────────────────────────────────────────

    public function getLogoUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('logo')
            ?: 'https://ui-avatars.com/api/?name='.urlencode($this->company_name).'&background=1A1A24&color=22D3EE&size=128';
    }

    public static function generateSlug(string $companyName): string
    {
        $slug = Str::slug($companyName);
        $count = static::where('company_slug', 'like', $slug.'%')->count();

        return $count ? $slug.'-'.$count : $slug;
    }
}