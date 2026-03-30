@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('sidebar')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-link active">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-link">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        My Applications
    </a>
    <a href="{{ route('candidate.saved-jobs') }}" class="sidebar-link">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
        Saved Jobs
    </a>
    <a href="{{ route('jobs.index') }}" class="sidebar-link">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        Browse Jobs
    </a>
@endsection

@section('content')

    {{-- Welcome --}}
    <div style="margin-bottom:32px;">
        <h2 style="margin-bottom:4px;">Welcome back, {{ Str::words(auth()->user()->name, 1, '') }} 👋</h2>
        <p style="font-size:14px;">Here's what's happening with your job search.</p>
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:32px;">
        <div class="card" style="padding:20px;text-align:center;">
            <div style="font-family:var(--font-display);font-size:32px;font-weight:800;color:var(--accent-bright);margin-bottom:4px;">
                {{ $stats['total_applications'] }}
            </div>
            <div style="font-size:13px;color:var(--text-secondary);">Total Applications</div>
        </div>
        <div class="card" style="padding:20px;text-align:center;">
            <div style="font-family:var(--font-display);font-size:32px;font-weight:800;color:var(--warning);margin-bottom:4px;">
                {{ $stats['under_review'] }}
            </div>
            <div style="font-size:13px;color:var(--text-secondary);">Under Review</div>
        </div>
        <div class="card" style="padding:20px;text-align:center;">
            <div style="font-family:var(--font-display);font-size:32px;font-weight:800;color:var(--success);margin-bottom:4px;">
                {{ $stats['shortlisted'] }}
            </div>
            <div style="font-size:13px;color:var(--text-secondary);">Shortlisted</div>
        </div>
        <div class="card" style="padding:20px;text-align:center;">
            <div style="font-family:var(--font-display);font-size:32px;font-weight:800;color:var(--spark);margin-bottom:4px;">
                {{ $stats['saved_jobs'] }}
            </div>
            <div style="font-size:13px;color:var(--text-secondary);">Saved Jobs</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

        {{-- Recent Applications --}}
        <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <h4>Recent Applications</h4>
                <a href="{{ route('candidate.applications.index') }}" style="font-size:13px;">View all →</a>
            </div>

            @if($recentApplications->isEmpty())
                <div class="card" style="padding:32px;text-align:center;">
                    <p style="font-size:14px;margin-bottom:16px;">No applications yet.</p>
                    <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-sm">Browse Jobs</a>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach($recentApplications as $application)
                        <div class="card" style="padding:16px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                                <div style="display:flex;align-items:center;gap:10px;min-width:0;">
                                    <div style="width:36px;height:36px;border-radius:var(--radius-md);background:var(--bg-elevated);border:1px solid var(--bg-muted);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:13px;font-weight:700;color:var(--accent-bright);flex-shrink:0;">
                                        {{ strtoupper(substr($application->job->employer->company_name, 0, 2)) }}
                                    </div>
                                    <div style="min-width:0;">
                                        <div style="font-size:14px;font-weight:500;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            {{ $application->job->title }}
                                        </div>
                                        <div style="font-size:12px;color:var(--text-tertiary);">
                                            {{ $application->applied_at->diffForHumans() }}
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

        {{-- Recommended Jobs --}}
        <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <h4>Latest Jobs</h4>
                <a href="{{ route('jobs.index') }}" style="font-size:13px;">Browse all →</a>
            </div>
            <div style="display:flex;flex-direction:column;gap:10px;">
                @foreach($recommendedJobs as $job)
                    <div class="card" style="padding:16px;cursor:pointer;"
                         onclick="window.location='{{ route('jobs.show', $job) }}'">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:var(--radius-md);background:var(--bg-elevated);border:1px solid var(--bg-muted);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:13px;font-weight:700;color:var(--accent-bright);flex-shrink:0;">
                                {{ strtoupper(substr($job->employer->company_name, 0, 2)) }}
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-size:14px;font-weight:500;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $job->title }}
                                </div>
                                <div style="font-size:12px;color:var(--text-tertiary);">
                                    {{ $job->employer->company_name }} · {{ $job->salary_display }}
                                </div>
                            </div>
                            @if($job->is_remote)
                                <span class="badge badge-purple" style="flex-shrink:0;">Remote</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

@endsection