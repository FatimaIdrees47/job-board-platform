@extends('layouts.dashboard')
@section('title', 'Messages')
@section('sidebar')
    <a href="{{ route('employer.dashboard') }}" class="sidebar-link">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
    </a>
    <a href="{{ route('employer.jobs.index') }}" class="sidebar-link">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        My Jobs
    </a>
    <a href="{{ route('employer.messages.index') }}" class="sidebar-link active">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        Messages
        @if($totalUnread > 0)
            <span style="background:var(--danger);color:#fff;border-radius:var(--radius-full);
                         padding:1px 7px;font-size:11px;margin-left:auto;">
                {{ $totalUnread }}
            </span>
        @endif
    </a>
@endsection

@section('content')
<div style="max-width:720px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
        <div>
            <h2 style="margin-bottom:4px;">Messages</h2>
            <p style="font-size:13px;">Conversations with candidates.</p>
        </div>
    </div>

    @if($applications->isEmpty())
        <div style="text-align:center;padding:64px 24px;background:var(--bg-surface);
                    border:1px solid var(--bg-muted);border-radius:var(--radius-lg);">
            <div style="font-size:48px;margin-bottom:16px;">💬</div>
            <h3 style="margin-bottom:8px;">No messages yet</h3>
            <p style="font-size:14px;">Start a conversation from an applicant's profile.</p>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach($applications as $application)
                @php $lastMessage = $application->messages->first(); @endphp
                @php $unread = $application->unreadMessagesFor(auth()->id()); @endphp
                <a href="{{ route('employer.messages.show', $application) }}" style="text-decoration:none;">
                    <div class="card" style="padding:20px;{{ $unread ? 'border-color:rgba(167,139,250,0.4)' : '' }}">
                        <div style="display:flex;align-items:center;gap:14px;">
                            <div style="width:44px;height:44px;border-radius:50%;background:var(--bg-elevated);
                                        border:1px solid var(--bg-muted);display:flex;align-items:center;
                                        justify-content:center;font-family:var(--font-display);font-size:16px;
                                        font-weight:700;color:var(--accent-bright);flex-shrink:0;">
                                {{ strtoupper(substr($application->candidate->user->name, 0, 1)) }}
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                                    <div style="font-size:14px;font-weight:{{ $unread ? '600' : '500' }};color:var(--text-primary);">
                                        {{ $application->candidate->user->name }}
                                    </div>
                                    <div style="font-size:12px;color:var(--text-tertiary);">
                                        {{ $lastMessage?->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <div style="font-size:13px;color:var(--text-tertiary);">Re: {{ $application->job->title }}</div>
                                @if($lastMessage)
                                    <div style="font-size:13px;color:var(--text-secondary);
                                                white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:4px;">
                                        {{ Str::limit($lastMessage->body, 80) }}
                                    </div>
                                @endif
                            </div>
                            @if($unread)
                                <div style="width:10px;height:10px;border-radius:50%;background:var(--accent-bright);flex-shrink:0;"></div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection