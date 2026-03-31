@extends('layouts.dashboard')
@section('title', 'Employer Management')
@section('sidebar')
    @include('admin._sidebar')
@endsection

@section('content')

    <div style="margin-bottom:28px;">
        <h2 style="margin-bottom:4px;">Employer Management</h2>
        <p style="font-size:13px;">{{ $employers->total() }} registered employers</p>
    </div>

    <div style="display:flex;flex-direction:column;gap:10px;">
        @foreach($employers as $employer)
            <div class="card" style="padding:20px 24px;">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;">

                    <div style="display:flex;align-items:center;gap:14px;flex:1;min-width:0;">
                        <div style="width:44px;height:44px;border-radius:var(--radius-md);
                                    background:var(--bg-elevated);border:1px solid var(--bg-muted);
                                    display:flex;align-items:center;justify-content:center;
                                    font-family:var(--font-display);font-size:16px;font-weight:700;
                                    color:var(--spark);flex-shrink:0;">
                            {{ strtoupper(substr($employer->company_name, 0, 2)) }}
                        </div>
                        <div style="min-width:0;">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px;">
                                <div style="font-size:15px;font-weight:600;color:var(--text-primary);">
                                    {{ $employer->company_name }}
                                </div>
                                @if($employer->is_verified)
                                    <span class="badge badge-success">✓ Verified</span>
                                @endif
                            </div>
                            <div style="font-size:13px;color:var(--text-secondary);">
                                {{ $employer->user->name }} · {{ $employer->user->email }}
                            </div>
                            <div style="font-size:12px;color:var(--text-tertiary);margin-top:2px;">
                                {{ $employer->jobs_count }} jobs posted
                                @if($employer->industry) · {{ $employer->industry }} @endif
                                @if($employer->location) · {{ $employer->location }} @endif
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;gap:8px;flex-shrink:0;">
                        @if($employer->is_verified)
                            <form method="POST" action="{{ route('admin.employers.unverify', $employer) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-ghost btn-sm">Remove Verification</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.employers.verify', $employer) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm"
                                        style="background:rgba(16,185,129,0.15);color:var(--success);
                                               border:1px solid rgba(16,185,129,0.3);">
                                    ✓ Verify Employer
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top:24px;">{{ $employers->links() }}</div>

@endsection