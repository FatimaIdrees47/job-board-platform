@extends('layouts.dashboard')

@section('title', 'Applications — ' . $job->title)

@section('sidebar')
    @include('employer._sidebar')
@endsection

@section('content')

    {{-- Header --}}
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:28px;">
        <div>
            <a href="{{ route('employer.jobs.index') }}"
               style="font-size:13px;color:var(--text-tertiary);text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:10px;">
                ← Back to listings
            </a>
            <h2 style="margin-bottom:4px;">{{ $job->title }}</h2>
            <p style="font-size:13px;">{{ $applications->total() }} total applications</p>
        </div>
    </div>

    {{-- Status filter tabs --}}
    <div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
        @php
            $statuses = ['all' => 'All', 'applied' => 'Applied', 'reviewing' => 'Reviewing', 'shortlisted' => 'Shortlisted', 'interview' => 'Interview', 'offered' => 'Offered', 'rejected' => 'Rejected'];
            $currentStatus = request('status', 'all');
        @endphp
        @foreach($statuses as $value => $label)
            <a href="{{ route('employer.jobs.applications.index', ['job' => $job, 'status' => $value === 'all' ? null : $value]) }}"
               class="btn btn-sm {{ $currentStatus === $value || ($value === 'all' && !request('status')) ? 'btn-primary' : 'btn-ghost' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if($applications->isEmpty())
        <div style="text-align:center;padding:64px 24px;background:var(--bg-surface);border:1px solid var(--bg-muted);border-radius:var(--radius-lg);">
            <div style="font-size:48px;margin-bottom:16px;">📭</div>
            <h3 style="margin-bottom:8px;">No applications yet</h3>
            <p style="font-size:14px;">Share your job listing to start receiving applications.</p>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:12px;">
            @foreach($applications as $application)
                <div class="card" style="padding:20px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;">

                        {{-- Candidate info --}}
                        <div style="display:flex;align-items:center;gap:14px;flex:1;min-width:0;">
                            <div style="width:44px;height:44px;border-radius:50%;background:var(--bg-elevated);border:1px solid var(--bg-muted);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:16px;font-weight:700;color:var(--accent-bright);flex-shrink:0;">
                                {{ strtoupper(substr($application->candidate->user->name, 0, 1)) }}
                            </div>
                            <div style="min-width:0;">
                                <div style="font-family:var(--font-display);font-size:15px;font-weight:600;color:var(--text-primary);margin-bottom:2px;">
                                    {{ $application->candidate->user->name }}
                                </div>
                                <div style="font-size:13px;color:var(--text-secondary);">
                                    {{ $application->candidate->headline ?? 'No headline set' }}
                                </div>
                                <div style="font-size:12px;color:var(--text-tertiary);margin-top:2px;">
                                    Applied {{ $application->applied_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        {{-- Status + Actions --}}
                        <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                            <span class="status-badge {{ $application->status_color }}">
                                {{ $application->status_label }}
                            </span>
                            <a href="{{ route('employer.jobs.applications.show', [$job, $application]) }}"
                               class="btn btn-secondary btn-sm">
                                View Profile
                            </a>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top:24px;">{{ $applications->links() }}</div>
    @endif

@endsection