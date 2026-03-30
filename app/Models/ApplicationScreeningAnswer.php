<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationScreeningAnswer extends Model
{
    protected $fillable = [
        'application_id',
        'question_id',
        'answer',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(JobScreeningQuestion::class, 'question_id');
    }
}