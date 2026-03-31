@extends('layouts.dashboard')

@section('title', 'My Applications')

@section('sidebar')
    @include('candidate._sidebar')
@endsection

@section('content')

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
        <div>
            <h2 style="margin-bottom:4px;">My Applications</h2>
            <p style="font-size:13px;">{{ $applications->total() }} total applications</p>
        </div>
        <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-sm">Browse Jobs</a>
    </div>

    @if($applications->isEmpty())
        <div style="text-align:center;padding:64px 24px;background:var(--bg-surface);
                    border:1px solid var(--bg-muted);border-radius:var(--radius-lg);">
            <div style="font-size:48px;margin-bottom:16px;">📋</div>
            <h3 style="margin-bottom:8px;">No applications yet</h3>
            <p style="font-size:14px;margin-bottom:20px;">
                Start applying to jobs that match your skills.
            </p>
            <a href="{{ route('jobs.index') }}" class="btn btn-primary">Browse Jobs</a>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:12px;">
            @foreach($applications as $application)
                <div class="card" style="padding:22px;">
                    <div style="display:flex;align-items:flex-start;
                                justify-content:space-between;gap:16px;">

                        <div style="display:flex;align-items:flex-start;gap:14px;flex:1;min-width:0;">
                            {{-- Company logo --}}
                            <div style="width:44px;height:44px;border-radius:var(--radius-md);
                                        background:var(--bg-elevated);border:1px solid var(--bg-muted);
                                        display:flex;align-items:center;justify-content:center;
                                        font-family:var(--font-display);font-size:15px;font-weight:700;
                                        color:var(--accent-bright);flex-shrink:0;">
                                {{ strtoupper(substr($application->job->employer->company_name, 0, 2)) }}
                            </div>

                            <div style="flex:1;min-width:0;">
                                <div style="font-family:var(--font-display);font-size:16px;
                                            font-weight:600;margin-bottom:3px;">
                                    <a href="{{ route('jobs.show', $application->job) }}"
                                       style="color:var(--text-primary);text-decoration:none;">
                                        {{ $application->job->title }}
                                    </a>
                                </div>
                                <div style="font-size:13px;color:var(--text-secondary);margin-bottom:10px;">
                                    {{ $application->job->employer->company_name }}
                                    @if($application->job->location)
                                        · {{ $application->job->location }}
                                    @endif
                                </div>
                                <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                                    <span class="status-badge {{ $application->status_color }}">
                                        {{ $application->status_label }}
                                    </span>
                                    <span style="font-size:12px;color:var(--text-tertiary);">
                                        Applied {{ $application->applied_at->diffForHumans() }}
                                    </span>
                                    @if($application->job->deadline)
                                        <span style="font-size:12px;color:var(--text-tertiary);">
                                            Deadline: {{ $application->job->deadline->format('M d, Y') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div style="display:flex;gap:8px;flex-shrink:0;">
                            <a href="{{ route('candidate.applications.show', $application) }}"
                               class="btn btn-ghost btn-sm">View</a>

                            @if($application->canBeWithdrawn())
                                <form method="POST"
                                      action="{{ route('candidate.applications.withdraw', $application) }}"
                                      onsubmit="return confirm('Withdraw this application?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Withdraw
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top:24px;">
            {{ $applications->links() }}
        </div>
    @endif

@endsection