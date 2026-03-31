@extends('layouts.dashboard')
@section('title', 'Job Moderation')
@section('sidebar')
    @include('admin._sidebar')
@endsection

@section('content')

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
        <div>
            <h2 style="margin-bottom:4px;">Job Moderation</h2>
            <p style="font-size:13px;">{{ $jobs->total() }} total listings</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" style="display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
        <input type="text" name="search" class="form-input"
               value="{{ request('search') }}"
               placeholder="Search jobs..."
               style="max-width:240px;">
        <select name="status" class="form-input" style="width:auto;">
            <option value="">All Statuses</option>
            @foreach(['draft','active','paused','expired','closed'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <select name="approval" class="form-input" style="width:auto;">
            <option value="">All</option>
            <option value="pending" {{ request('approval') === 'pending' ? 'selected' : '' }}>Pending Approval</option>
            <option value="approved" {{ request('approval') === 'approved' ? 'selected' : '' }}>Approved</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('admin.jobs.index') }}" class="btn btn-ghost btn-sm">Clear</a>
    </form>

    @if($jobs->isEmpty())
        <div style="text-align:center;padding:48px;background:var(--bg-surface);border:1px solid var(--bg-muted);border-radius:var(--radius-lg);">
            <p style="font-size:14px;">No jobs found.</p>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach($jobs as $job)
                <div class="card" style="padding:20px 24px;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">
                        <div style="flex:1;min-width:0;">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;flex-wrap:wrap;">
                                <h4 style="margin:0;font-size:15px;">{{ $job->title }}</h4>
                                @if(!$job->is_approved && $job->status === 'active')
                                    <span class="badge badge-warning">Pending Approval</span>
                                @elseif($job->is_approved)
                                    <span class="badge badge-success">Approved</span>
                                @endif
                            </div>
                            <div style="font-size:13px;color:var(--text-secondary);margin-bottom:6px;">
                                {{ $job->employer->company_name }}
                                @if($job->location) · {{ $job->location }} @endif
                                · {{ $job->applications_count }} applications
                            </div>
                            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                @php
                                    $colors = ['draft'=>'badge-neutral','active'=>'badge-success','paused'=>'badge-warning','expired'=>'badge-danger','closed'=>'badge-neutral'];
                                @endphp
                                <span class="badge {{ $colors[$job->status] ?? 'badge-neutral' }}">{{ ucfirst($job->status) }}</span>
                                <span style="font-size:11px;color:var(--text-tertiary);">Posted {{ $job->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <div style="display:flex;gap:8px;flex-shrink:0;flex-wrap:wrap;justify-content:flex-end;">
                            @if(!$job->is_approved && $job->status === 'active')
                                <form method="POST" action="{{ route('admin.jobs.approve', $job) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            style="background:rgba(16,185,129,0.15);color:var(--success);border:1px solid rgba(16,185,129,0.3);"
                                            class="btn btn-sm">
                                        Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.jobs.reject', $job) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}"
                                  onsubmit="return confirm('Permanently delete this job?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div style="margin-top:24px;">{{ $jobs->links() }}</div>
    @endif

@endsection