@extends('layouts.dashboard')

@section('title', 'Saved Jobs')

@section('sidebar')
    @include('candidate._sidebar')
@endsection

@section('content')

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
        <div>
            <h2 style="margin-bottom:4px;">Saved Jobs</h2>
            <p style="font-size:13px;">{{ $savedJobs->total() }} saved listings</p>
        </div>
        <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-sm">Browse More Jobs</a>
    </div>

    @if($savedJobs->isEmpty())
        <div style="text-align:center;padding:64px 24px;background:var(--bg-surface);
                    border:1px solid var(--bg-muted);border-radius:var(--radius-lg);">
            <div style="font-size:48px;margin-bottom:16px;">🔖</div>
            <h3 style="margin-bottom:8px;">No saved jobs yet</h3>
            <p style="font-size:14px;margin-bottom:20px;">
                Bookmark jobs you're interested in to apply later.
            </p>
            <a href="{{ route('jobs.index') }}" class="btn btn-primary">Browse Jobs</a>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:12px;">
            @foreach($savedJobs as $saved)
                <div class="card" style="padding:22px;">
                    <div style="display:flex;align-items:flex-start;
                                justify-content:space-between;gap:16px;">
                        <div style="display:flex;align-items:flex-start;gap:14px;flex:1;">
                            <div style="width:44px;height:44px;border-radius:var(--radius-md);
                                        background:var(--bg-elevated);border:1px solid var(--bg-muted);
                                        display:flex;align-items:center;justify-content:center;
                                        font-family:var(--font-display);font-size:15px;font-weight:700;
                                        color:var(--accent-bright);flex-shrink:0;">
                                {{ strtoupper(substr($saved->job->employer->company_name, 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-family:var(--font-display);font-size:16px;
                                            font-weight:600;margin-bottom:3px;">
                                    <a href="{{ route('jobs.show', $saved->job) }}"
                                       style="color:var(--text-primary);text-decoration:none;">
                                        {{ $saved->job->title }}
                                    </a>
                                </div>
                                <div style="font-size:13px;color:var(--text-secondary);margin-bottom:8px;">
                                    {{ $saved->job->employer->company_name }}
                                    @if($saved->job->location) · {{ $saved->job->location }} @endif
                                </div>
                                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                    <span class="badge badge-neutral">
                                        {{ ucfirst(str_replace('-', ' ', $saved->job->type)) }}
                                    </span>
                                    <span style="font-size:12px;color:var(--accent-bright);font-weight:500;">
                                        {{ $saved->job->salary_display }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div style="display:flex;gap:8px;flex-shrink:0;">
                            <a href="{{ route('jobs.apply', $saved->job) }}"
                               class="btn btn-primary btn-sm">Apply Now</a>
                            <form method="POST"
                                  action="{{ route('candidate.saved-jobs.toggle', $saved->job) }}">
                                @csrf
                                <button type="submit" class="btn btn-ghost btn-sm">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div style="margin-top:24px;">{{ $savedJobs->links() }}</div>
    @endif

@endsection