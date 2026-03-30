@extends('layouts.dashboard')

@section('title', 'My Job Listings')

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

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
        <div>
            <h2 style="margin-bottom:4px;">My Job Listings</h2>
            <p style="font-size:13px;">{{ $jobs->total() }} total listings</p>
        </div>
        <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Post a Job
        </a>
    </div>

    @if($jobs->isEmpty())
        <div style="text-align:center;padding:64px 24px;background:var(--bg-surface);
                    border:1px solid var(--bg-muted);border-radius:var(--radius-lg);">
            <div style="width:56px;height:56px;border-radius:var(--radius-lg);
                        background:rgba(109,40,217,0.1);border:1px solid rgba(167,139,250,0.2);
                        display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:var(--accent-bright)">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 style="margin-bottom:8px;">No job listings yet</h3>
            <p style="font-size:14px;margin-bottom:20px;">Post your first job and start receiving applications.</p>
            <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary">Post Your First Job</a>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:12px;">
            @foreach($jobs as $job)
                <div class="card" style="padding:20px 24px;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">

                        {{-- Job info --}}
                        <div style="flex:1;min-width:0;">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;flex-wrap:wrap;">
                                <h4 style="margin:0;font-size:16px;">{{ $job->title }}</h4>
                                @if($job->is_featured)
                                    <span class="badge badge-cyan">✦ Featured</span>
                                @endif
                            </div>
                            <div style="display:flex;gap:16px;flex-wrap:wrap;margin-bottom:10px;">
                                <span style="font-size:12px;color:var(--text-tertiary);">
                                    {{ $job->category?->name ?? 'Uncategorized' }}
                                </span>
                                <span style="font-size:12px;color:var(--text-tertiary);">
                                    {{ ucfirst(str_replace('-', ' ', $job->type)) }}
                                </span>
                                @if($job->location)
                                    <span style="font-size:12px;color:var(--text-tertiary);">
                                        {{ $job->location }}
                                    </span>
                                @endif
                                <span style="font-size:12px;color:var(--text-tertiary);">
                                    {{ $job->views_count }} views
                                </span>
                            </div>
                            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                @php
                                    $statusColors = [
                                        'draft'   => 'badge-neutral',
                                        'active'  => 'badge-success',
                                        'paused'  => 'badge-warning',
                                        'expired' => 'badge-danger',
                                        'closed'  => 'badge-neutral',
                                    ];
                                @endphp
                                <span class="badge {{ $statusColors[$job->status] ?? 'badge-neutral' }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                                @if(! $job->is_approved && $job->status === 'active')
                                    <span class="badge badge-warning">Pending Approval</span>
                                @endif
                                @if($job->deadline)
                                    <span style="font-size:11px;color:var(--text-tertiary);">
                                        Deadline: {{ $job->deadline->format('M d, Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div style="display:flex;gap:8px;align-items:center;flex-shrink:0;flex-wrap:wrap;justify-content:flex-end;">

                            <a href="{{ route('employer.jobs.applications.index', $job) }}"
                               class="btn btn-secondary btn-sm">
                                Applications
                                @if($job->applications_count > 0)
                                    <span style="background:var(--accent-glow);color:#fff;border-radius:var(--radius-full);padding:1px 7px;font-size:11px;margin-left:4px;">
                                        {{ $job->applications_count }}
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('employer.jobs.edit', $job) }}"
                               class="btn btn-ghost btn-sm">Edit</a>

                            <form method="POST"
                                  action="{{ route('employer.jobs.toggle-status', $job) }}"
                                  style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-ghost btn-sm">
                                    {{ $job->status === 'active' ? 'Pause' : 'Activate' }}
                                </button>
                            </form>

                            <form method="POST"
                                  action="{{ route('employer.jobs.duplicate', $job) }}"
                                  style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-ghost btn-sm">Duplicate</button>
                            </form>

                            <form method="POST"
                                  action="{{ route('employer.jobs.destroy', $job) }}"
                                  style="display:inline;"
                                  onsubmit="return confirm('Delete this job listing?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top:24px;">
            {{ $jobs->links() }}
        </div>
    @endif

@endsection