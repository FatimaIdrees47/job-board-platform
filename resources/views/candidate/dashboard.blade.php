@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('sidebar')
    @include('candidate._sidebar')
@endsection

@section('content')

    {{-- Welcome --}}
    <div style="margin-bottom:32px;">
        <h2 style="margin-bottom:4px;">Welcome back, {{ Str::words(auth()->user()->name, 1, '') }} 👋</h2>
        <p style="font-size:14px;">Here's what's happening with your job search.</p>
    </div>

    {{-- Profile completion banner --}}
    @if($candidate->profile_completion < 80)
        <div class="alert alert-info" style="margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;">
            <span>Your profile is {{ $candidate->profile_completion }}% complete. Complete it to stand out to employers.</span>
            <a href="{{ route('candidate.profile.edit') }}" class="btn btn-primary btn-sm" style="flex-shrink:0;margin-left:16px;">
                Complete Profile
            </a>
        </div>
    @endif

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