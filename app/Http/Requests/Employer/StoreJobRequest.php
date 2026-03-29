<?php

namespace App\Http\Requests\Employer;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasRole('employer');
    }

    public function rules(): array
    {
        return [
            'title'              => ['required', 'string', 'max:255'],
            'category_id'        => ['required', 'exists:categories,id'],
            'type'               => ['required', 'in:full-time,part-time,remote,contract,internship,freelance'],
            'location'           => ['nullable', 'string', 'max:255'],
            'is_remote'          => ['boolean'],
            'is_hybrid'          => ['boolean'],
            'salary_min'         => ['nullable', 'integer', 'min:0'],
            'salary_max'         => ['nullable', 'integer', 'min:0', 'gte:salary_min'],
            'salary_currency'    => ['required', 'string', 'max:10'],
            'salary_period'      => ['required', 'in:monthly,yearly'],
            'salary_negotiable'  => ['boolean'],
            'show_salary'        => ['boolean'],
            'experience_level'   => ['required', 'in:entry,mid,senior,lead,executive'],
            'description'        => ['required', 'string', 'min:100'],
            'requirements'       => ['nullable', 'string'],
            'benefits'           => ['nullable', 'string'],
            'application_method' => ['required', 'in:platform,external'],
            'external_url'       => ['nullable', 'url', 'required_if:application_method,external'],
            'deadline'           => ['nullable', 'date', 'after:today'],
            'status'             => ['required', 'in:draft,active'],
            'screening_questions'          => ['nullable', 'array', 'max:5'],
            'screening_questions.*.question'    => ['required_with:screening_questions', 'string', 'max:500'],
            'screening_questions.*.is_required' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'description.min'          => 'Job description must be at least 100 characters.',
            'salary_max.gte'           => 'Maximum salary must be greater than minimum salary.',
            'external_url.required_if' => 'External URL is required when application method is external.',
            'deadline.after'           => 'Application deadline must be a future date.',
        ];
    }
}