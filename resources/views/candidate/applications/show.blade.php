@extends('layouts.dashboard')

@section('title', 'Application — ' . $application->job->title)

@section('sidebar')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-link">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-link active">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        My Applications
    </a>
    <a href="{{ route('candidate.saved-jobs') }}" class="sidebar-link">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
        Saved Jobs
    </a>
@endsection

@section('content')

    <div style="max-width:720px;">

        <a href="{{ route('candidate.applications.index') }}"
           style="font-size:13px;color:var(--text-tertiary);text-decoration:none;
                  display:inline-flex;align-items:center;gap:6px;margin-bottom:24px;">
            ← Back to applications
        </a>

        {{-- Job header --}}
        <div class="card" style="padding:28px;margin-bottom:20px;">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
                <div style="width:52px;height:52px;border-radius:var(--radius-lg);
                            background:var(--bg-elevated);border:1px solid var(--bg-muted);
                            display:flex;align-items:center;justify-content:center;
                            font-family:var(--font-display);font-size:18px;font-weight:700;
                            color:var(--accent-bright);flex-shrink:0;">
                    {{ strtoupper(substr($application->job->employer->company_name, 0, 2)) }}
                </div>
                <div>
                    <h3 style="margin-bottom:4px;">{{ $application->job->title }}</h3>
                    <p style="font-size:14px;">{{ $application->job->employer->company_name }}</p>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                <span class="status-badge {{ $application->status_color }}">
                    {{ $application->status_label }}
                </span>
                <span style="font-size:13px;color:var(--text-tertiary);">
                    Applied {{ $application->applied_at->format('M d, Y \a\t h:i A') }}
                </span>
            </div>

            @if($application->canBeWithdrawn())
                <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--bg-muted);">
                    <form method="POST"
                          action="{{ route('candidate.applications.withdraw', $application) }}"
                          onsubmit="return confirm('Are you sure you want to withdraw this application?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-danger btn-sm">
                            Withdraw Application
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Status History --}}
        <div class="card" style="padding:28px;margin-bottom:20px;">
            <h4 style="margin-bottom:20px;">Application Timeline</h4>

            <div style="position:relative;padding-left:24px;">
                <div style="position:absolute;left:7px;top:8px;bottom:8px;
                            width:2px;background:var(--bg-muted);"></div>

                @foreach($application->statusHistory as $history)
                    <div style="position:relative;margin-bottom:20px;">
                        <div style="position:absolute;left:-20px;top:4px;
                                    width:10px;height:10px;border-radius:50%;
                                    background:var(--accent-bright);
                                    border:2px solid var(--bg-base);"></div>
                        <div style="font-size:14px;font-weight:500;
                                    color:var(--text-primary);margin-bottom:2px;">
                            {{ ucfirst(str_replace('_', ' ', $history->status)) }}
                        </div>
                        @if($history->note)
                            <div style="font-size:13px;color:var(--text-secondary);margin-bottom:2px;">
                                {{ $history->note }}
                            </div>
                        @endif
                        <div style="font-size:12px;color:var(--text-tertiary);">
                            {{ $history->created_at->format('M d, Y \a\t h:i A') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Cover Letter --}}
        @if($application->cover_letter)
        <div class="card" style="padding:28px;margin-bottom:20px;">
            <h4 style="margin-bottom:16px;">Your Cover Letter</h4>
            <div style="color:var(--text-secondary);line-height:1.8;
                        font-size:14px;white-space:pre-wrap;">
                {{ $application->cover_letter }}
            </div>
        </div>
        @endif

        {{-- Screening Answers --}}
        @if($application->screeningAnswers->isNotEmpty())
        <div class="card" style="padding:28px;">
            <h4 style="margin-bottom:20px;">Your Answers</h4>
            <div style="display:flex;flex-direction:column;gap:20px;">
                @foreach($application->screeningAnswers as $answer)
                    <div>
                        <div style="font-size:14px;font-weight:500;
                                    color:var(--text-primary);margin-bottom:6px;">
                            {{ $answer->question->question }}
                        </div>
                        <div style="font-size:14px;color:var(--text-secondary);
                                    line-height:1.7;background:var(--bg-elevated);
                                    border-radius:var(--radius-md);padding:12px 16px;">
                            {{ $answer->answer }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

@endsection