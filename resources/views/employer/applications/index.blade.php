@extends('layouts.dashboard')

@section('title', 'Applications — ' . $job->title)

@section('sidebar')
    <a href="{{ route('employer.dashboard') }}" class="sidebar-link">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
    </a>
    <a href="{{ route('employer.jobs.index') }}" class="sidebar-link active">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        My Jobs
    </a>
    <a href="{{ route('employer.jobs.create') }}" class="sidebar-link">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Post a Job
    </a>
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