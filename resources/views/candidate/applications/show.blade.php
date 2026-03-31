@extends('layouts.dashboard')

@section('title', 'Application — ' . $application->job->title)

@section('sidebar')
    @include('candidate._sidebar')
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