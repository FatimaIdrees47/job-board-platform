@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('sidebar')
    @include('employer._sidebar')
@endsection

@section('content')

    {{-- Welcome --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;">
        <div>
            <h2 style="margin-bottom:4px;">Welcome back, {{ Str::words(auth()->user()->name, 1, '') }} 👋</h2>
            <p style="font-size:14px;">{{ $employer->company_name }} · Dashboard</p>
        </div>
        <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary">
            + Post a Job
        </a>
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:32px;">
        <div class="card" style="padding:20px;text-align:center;">
            <div style="font-family:var(--font-display);font-size:32px;font-weight:800;color:var(--accent-bright);margin-bottom:4px;">
                {{ $stats['total_jobs'] }}
            </div>
            <div style="font-size:13px;color:var(--text-secondary);">Total Jobs</div>
        </div>
        <div class="card" style="padding:20px;text-align:center;">
            <div style="font-family:var(--font-display);font-size:32px;font-weight:800;color:var(--success);margin-bottom:4px;">
                {{ $stats['active_jobs'] }}
            </div>
            <div style="font-size:13px;color:var(--text-secondary);">Active Jobs</div>
        </div>
        <div class="card" style="padding:20px;text-align:center;">
            <div style="font-family:var(--font-display);font-size:32px;font-weight:800;color:var(--spark);margin-bottom:4px;">
                {{ $stats['total_applications'] }}
            </div>
            <div style="font-size:13px;color:var(--text-secondary);">Total Applications</div>
        </div>
        <div class="card" style="padding:20px;text-align:center;">
            <div style="font-family:var(--font-display);font-size:32px;font-weight:800;color:var(--warning);margin-bottom:4px;">
                {{ $stats['new_applications'] }}
            </div>
            <div style="font-size:13px;color:var(--text-secondary);">New Applications</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

        {{-- Recent Jobs --}}
        <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <h4>Recent Job Listings</h4>
                <a href="{{ route('employer.jobs.index') }}" style="font-size:13px;">View all →</a>
            </div>
            @if($recentJobs->isEmpty())
                <div class="card" style="padding:32px;text-align:center;">
                    <p style="font-size:14px;margin-bottom:16px;">No jobs posted yet.</p>
                    <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary btn-sm">Post Your First Job</a>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach($recentJobs as $job)
                        <div class="card" style="padding:16px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                                <div style="min-width:0;">
                                    <div style="font-size:14px;font-weight:500;color:var(--text-primary);margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $job->title }}
                                    </div>
                                    <div style="font-size:12px;color:var(--text-tertiary);">
                                        {{ $job->applications_count }} applications · {{ $job->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                @php
                                    $statusColors = ['draft' => 'badge-neutral', 'active' => 'badge-success', 'paused' => 'badge-warning', 'expired' => 'badge-danger', 'closed' => 'badge-neutral'];
                                @endphp
                                <span class="badge {{ $statusColors[$job->status] ?? 'badge-neutral' }}" style="flex-shrink:0;">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent Applications --}}
        <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <h4>Recent Applications</h4>
            </div>
            @if($recentApplications->isEmpty())
                <div class="card" style="padding:32px;text-align:center;">
                    <p style="font-size:14px;">No applications received yet.</p>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach($recentApplications as $application)
                        <div class="card" style="padding:16px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                                <div style="display:flex;align-items:center;gap:10px;min-width:0;">
                                    <div style="width:36px;height:36px;border-radius:50%;background:var(--bg-elevated);border:1px solid var(--bg-muted);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:13px;font-weight:700;color:var(--accent-bright);flex-shrink:0;">
                                        {{ strtoupper(substr($application->candidate->user->name, 0, 1)) }}
                                    </div>
                                    <div style="min-width:0;">
                                        <div style="font-size:14px;font-weight:500;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            {{ $application->candidate->user->name }}
                                        </div>
                                        <div style="font-size:12px;color:var(--text-tertiary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            {{ $application->job->title }}
                                        </div>
                                    </div>
                                </div>
                                <span class="status-badge {{ $application->status_color }}" style="flex-shrink:0;">
                                    {{ $application->status_label }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

@endsection