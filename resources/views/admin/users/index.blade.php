@extends('layouts.dashboard')
@section('title', 'User Management')
@section('sidebar')
    @include('admin._sidebar')
@endsection

@section('content')

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
        <div>
            <h2 style="margin-bottom:4px;">User Management</h2>
            <p style="font-size:13px;">{{ $users->total() }} total users</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" style="display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
        <input type="text" name="search" class="form-input"
               value="{{ request('search') }}"
               placeholder="Search by name or email..."
               style="max-width:280px;">
        <select name="role" class="form-input" style="width:auto;">
            <option value="">All Roles</option>
            <option value="candidate" {{ request('role') === 'candidate' ? 'selected' : '' }}>Candidates</option>
            <option value="employer" {{ request('role') === 'employer' ? 'selected' : '' }}>Employers</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">Clear</a>
    </form>

    @if($users->isEmpty())
        <div style="text-align:center;padding:48px;background:var(--bg-surface);border:1px solid var(--bg-muted);border-radius:var(--radius-lg);">
            <p>No users found.</p>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach($users as $user)
                <div class="card" style="padding:18px 24px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;">
                        <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">
                            <div style="width:40px;height:40px;border-radius:50%;background:var(--bg-elevated);
                                        border:1px solid var(--bg-muted);display:flex;align-items:center;
                                        justify-content:center;font-family:var(--font-display);font-size:15px;
                                        font-weight:700;color:var(--accent-bright);flex-shrink:0;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div style="min-width:0;">
                                <div style="font-size:14px;font-weight:500;color:var(--text-primary);margin-bottom:2px;">
                                    {{ $user->name }}
                                </div>
                                <div style="font-size:12px;color:var(--text-tertiary);">
                                    {{ $user->email }} · Joined {{ $user->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>

                        <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                            @foreach($user->getRoleNames() as $role)
                                <span class="badge {{ $role === 'employer' ? 'badge-cyan' : 'badge-purple' }}">
                                    {{ ucfirst($role) }}
                                </span>
                            @endforeach

                            @if(!$user->is_active)
                                <span class="badge badge-danger">Suspended</span>
                            @endif

                            @if(!$user->email_verified_at)
                                <span class="badge badge-warning">Unverified</span>
                            @endif

                            <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-ghost' }}"
                                        onclick="return confirm('{{ $user->is_active ? 'Suspend' : 'Activate' }} this user?')">
                                    {{ $user->is_active ? 'Suspend' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div style="margin-top:24px;">{{ $users->links() }}</div>
    @endif

@endsection