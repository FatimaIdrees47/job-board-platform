@extends('layouts.app')

@section('title', 'Apply — ' . $job->title)

@section('content')

<div class="container" style="padding-top:40px;padding-bottom:80px;max-width:760px;">

    {{-- Header --}}
    <div style="margin-bottom:32px;">
        <a href="{{ route('jobs.show', $job) }}"
           style="font-size:13px;color:var(--text-tertiary);text-decoration:none;
                  display:inline-flex;align-items:center;gap:6px;margin-bottom:16px;">
            ← Back to job
        </a>
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="width:52px;height:52px;border-radius:var(--radius-lg);
                        background:var(--bg-elevated);border:1px solid var(--bg-muted);
                        display:flex;align-items:center;justify-content:center;
                        font-family:var(--font-display);font-size:18px;font-weight:700;
                        color:var(--accent-bright);flex-shrink:0;">
                {{ strtoupper(substr($job->employer->company_name, 0, 2)) }}
            </div>
            <div>
                <h2 style="margin-bottom:4px;font-size:22px;">{{ $job->title }}</h2>
                <p style="font-size:14px;">{{ $job->employer->company_name }}
                    @if($job->location) · {{ $job->location }} @endif
                </p>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:24px;">
            <ul style="margin:0;padding-left:16px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('jobs.apply.store', $job) }}">
        @csrf

        {{-- Step 1: CV --}}
        <div class="card" style="padding:28px;margin-bottom:20px;">
            <h4 style="margin-bottom:6px;">Your CV</h4>
            <p style="font-size:13px;margin-bottom:20px;padding-bottom:16px;
                      border-bottom:1px solid var(--bg-muted);">
                Select which CV to send with your application.
            </p>

            @if($cvFiles->isEmpty())
                <div style="background:var(--bg-elevated);border:1px solid var(--bg-muted);
                            border-radius:var(--radius-md);padding:20px;text-align:center;">
                    <p style="font-size:14px;margin-bottom:12px;">
                        You haven't uploaded a CV yet.
                    </p>
                    <a href="#" class="btn btn-secondary btn-sm">Upload CV</a>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach($cvFiles as $cv)
                        <label style="display:flex;align-items:center;gap:12px;
                                      background:var(--bg-elevated);border:1px solid var(--bg-muted);
                                      border-radius:var(--radius-md);padding:14px 16px;cursor:pointer;">
                            <input type="radio" name="cv_media_id"
                                   value="{{ $cv->id }}"
                                   {{ $loop->first ? 'checked' : '' }}
                                   style="accent-color:var(--accent-bright);flex-shrink:0;">
                            <div style="flex:1;">
                                <div style="font-size:14px;font-weight:500;
                                            color:var(--text-primary);margin-bottom:2px;">
                                    {{ $cv->file_name }}
                                </div>
                                <div style="font-size:12px;color:var(--text-tertiary);">
                                    {{ round($cv->size / 1024) }} KB ·
                                    Uploaded {{ $cv->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Step 2: Cover Letter --}}
        <div class="card" style="padding:28px;margin-bottom:20px;"
             x-data="{ count: {{ strlen(old('cover_letter', '')) }} }">
            <h4 style="margin-bottom:6px;">Cover Letter</h4>
            <p style="font-size:13px;margin-bottom:20px;padding-bottom:16px;
                      border-bottom:1px solid var(--bg-muted);">
                Optional but recommended. Tell the employer why you're a great fit.
            </p>

            <textarea name="cover_letter"
                      class="form-textarea"
                      rows="8"
                      maxlength="3000"
                      placeholder="Dear Hiring Manager,&#10;&#10;I am excited to apply for the {{ $job->title }} position at {{ $job->employer->company_name }}..."
                      x-on:input="count = $event.target.value.length">{{ old('cover_letter') }}</textarea>

            <div style="display:flex;justify-content:space-between;margin-top:8px;">
                <span style="font-size:12px;color:var(--text-tertiary);">
                    Make it personal and specific to this role
                </span>
                <span style="font-size:12px;"
                      :style="count > 2800 ? 'color:var(--warning)' : 'color:var(--text-tertiary)'">
                    <span x-text="count"></span>/3000
                </span>
            </div>
        </div>

        {{-- Step 3: Screening Questions --}}
        @if($job->screeningQuestions->isNotEmpty())
        <div class="card" style="padding:28px;margin-bottom:20px;">
            <h4 style="margin-bottom:6px;">Screening Questions</h4>
            <p style="font-size:13px;margin-bottom:20px;padding-bottom:16px;
                      border-bottom:1px solid var(--bg-muted);">
                The employer has {{ $job->screeningQuestions->count() }}
                {{ Str::plural('question', $job->screeningQuestions->count()) }}
                for applicants.
            </p>

            <div style="display:flex;flex-direction:column;gap:20px;">
                @foreach($job->screeningQuestions->sortBy('sort_order') as $question)
                    <div>
                        <label class="form-label">
                            {{ $loop->iteration }}. {{ $question->question }}
                            @if($question->is_required)
                                <span style="color:var(--danger);">*</span>
                            @endif
                        </label>
                        <textarea name="answers[{{ $question->id }}]"
                                  class="form-textarea"
                                  rows="3"
                                  {{ $question->is_required ? 'required' : '' }}
                                  placeholder="Your answer...">{{ old("answers.{$question->id}") }}</textarea>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Step 4: Review & Submit --}}
        <div class="card" style="padding:24px;">
            <div style="display:flex;justify-content:space-between;
                        align-items:center;flex-wrap:wrap;gap:16px;">
                <div>
                    <h4 style="margin-bottom:4px;">Ready to apply?</h4>
                    <p style="font-size:13px;">
                        Applying to <strong style="color:var(--text-primary);">{{ $job->title }}</strong>
                        at <strong style="color:var(--text-primary);">{{ $job->employer->company_name }}</strong>
                    </p>
                </div>
                <div style="display:flex;gap:10px;">
                    <a href="{{ route('jobs.show', $job) }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        Submit Application 🚀
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

@endsection