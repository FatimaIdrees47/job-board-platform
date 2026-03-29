<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\CandidateProfile;
use App\Models\EmployerProfile;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'last_active_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_active_at'    => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }


    public function candidateProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CandidateProfile::class);
    }

    public function employerProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(EmployerProfile::class);
    }
}
