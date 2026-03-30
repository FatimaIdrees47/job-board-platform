<?php

namespace App\Http\Requests\Candidate;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasRole('candidate');
    }

    public function rules(): array
    {
        return [
            'cover_letter' => ['nullable', 'string', 'max:3000'],
            'cv_media_id'  => ['nullable', 'integer'],
            'answers'      => ['nullable', 'array'],
            'answers.*'    => ['nullable', 'string', 'max:1000'],
        ];
    }
}