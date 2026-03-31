@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')
@section('sidebar')
    @include('admin._sidebar')
@endsection

@section('content')

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;">
        <div>
            <h2 style="margin-bottom:4px;">Admin Dashboard</h2>
            <p style="font-size:13px;">Platform overview and moderation.</p>
        </div>
        @if($stats['pending_approval'] > 0)
            <a href="{{ route('admin.jobs.index', ['approval' => 'pending']) }}"
               class="btn btn-primary">
                {{ $stats['pending_approval'] }} jobs pending approval
            </a>
        @endif
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:32px;">
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
            <div style="font-family:var(--font-display);font-size:32px;font-weight:800;color:var(--warning);margin-bottom:4px;">
                {{ $stats['pending_approval'] }}
            </div>
            <div style="font-size:13px;color:var(--text-secondary);">Pending Approval</div>
        </div>
        <div class="card" style="padding:20px;text-align:center;">
            <div style="font-family:var(--font-display);font-size:32px;font-weight:800;color:var(--spark);margin-bottom:4px;">
                {{ $stats['total_candidates'] }}
            </div>
            <div style="font-size:13px;color:var(--text-secondary);">Candidates</div>
        </div>
        <div class="card" style="padding:20px;text-align:center;">
            <div style="font-family:var(--font-display);font-size:32px;font-weight:800;color:var(--info);margin-bottom:4px;">
                {{ $stats['total_employers'] }}
            </div>
            <div style="font-size:13px;color:var(--text-secondary);">Employers</div>
        </div>
        <div class="card" style="padding:20px;text-align:center;">
            <div style="font-family:var(--font-display);font-size:32px;font-weight:800;color:var(--accent-pop);margin-bottom:4px;">
                {{ $stats['total_applications'] }}
            </div>
            <div style="font-size:13px;color:var(--text-secondary);">Applications</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

        {{-- Pending Jobs --}}
        <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <h4>Pending Approval</h4>
                <a href="{{ route('admin.jobs.index', ['approval' => 'pending']) }}" style="font-size:13px;">View all →</a>
            </div>
            @if($pendingJobs->isEmpty())
                <div class="card" style="padding:24px;text-align:center;">
                    <p style="font-size:14px;color:var(--success);">✓ All jobs are reviewed</p>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach($pendingJobs as $job)
                        <div class="card" style="padding:16px;">
                            <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
                                <div style="min-width:0;">
                                    <div style="font-size:14px;font-weight:500;color:var(--text-primary);margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $job->title }}
                                    </div>
                                    <div style="font-size:12px;color:var(--text-tertiary);">
                                        {{ $job->employer->company_name }}
                                    </div>
                                </div>
                                <div style="display:flex;gap:6px;flex-shrink:0;">
                                    <form method="POST" action="{{ route('admin.jobs.approve', $job) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm"
                                                style="background:rgba(16,185,129,0.15);color:var(--success);border:1px solid rgba(16,185,129,0.3);">
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.jobs.reject', $job) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent Users --}}
        <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <h4>Recent Users</h4>
                <a href="{{ route('admin.users.index') }}" style="font-size:13px;">View all →</a>
            </div>
            <div style="display:flex;flex-direction:column;gap:10px;">
                @foreach($recentUsers as $user)
                    <div class="card" style="padding:14px 16px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                            <div style="display:flex;align-items:center;gap:10px;min-width:0;">
                                <div style="width:32px;height:32px;border-radius:50%;background:var(--bg-elevated);
                                            border:1px solid var(--bg-muted);display:flex;align-items:center;
                                            justify-content:center;font-family:var(--font-display);font-size:13px;
                                            font-weight:700;color:var(--accent-bright);flex-shrink:0;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div style="min-width:0;">
                                    <div style="font-size:13px;font-weight:500;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $user->name }}
                                    </div>
                                    <div style="font-size:11px;color:var(--text-tertiary);">
                                        {{ $user->getRoleNames()->first() }} · {{ $user->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            @if(!$user->is_active)
                                <span class="badge badge-danger">Suspended</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

@endsection