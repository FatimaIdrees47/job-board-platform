@extends('layouts.dashboard')
@section('title', 'Conversation')
@section('sidebar')
    <a href="{{ route('employer.dashboard') }}" class="sidebar-link">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
    </a>
    <a href="{{ route('employer.messages.index') }}" class="sidebar-link active">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        Messages
    </a>
@endsection

@section('content')
<div style="max-width:720px;">

    <a href="{{ route('employer.messages.index') }}"
       style="font-size:13px;color:var(--text-tertiary);text-decoration:none;
              display:inline-flex;align-items:center;gap:6px;margin-bottom:20px;">
        ← Back to messages
    </a>

    <div class="card" style="padding:20px;margin-bottom:16px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:44px;height:44px;border-radius:50%;background:var(--bg-elevated);
                        border:1px solid var(--bg-muted);display:flex;align-items:center;
                        justify-content:center;font-family:var(--font-display);font-size:16px;
                        font-weight:700;color:var(--accent-bright);">
                {{ strtoupper(substr($application->candidate->user->name, 0, 1)) }}
            </div>
            <div>
                <div style="font-size:15px;font-weight:600;">{{ $application->candidate->user->name }}</div>
                <div style="font-size:13px;color:var(--text-tertiary);">Re: {{ $application->job->title }}</div>
            </div>
            <div style="margin-left:auto;">
                <a href="{{ route('employer.jobs.applications.show', [$application->job, $application]) }}"
                   class="btn btn-ghost btn-sm">View Application →</a>
            </div>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:16px;">
        @forelse($messages as $message)
            @php $isMe = $message->sender_id === auth()->id(); @endphp
            <div style="display:flex;{{ $isMe ? 'justify-content:flex-end' : 'justify-content:flex-start' }}">
                <div style="max-width:75%;background:{{ $isMe ? 'rgba(109,40,217,0.2)' : 'var(--bg-elevated)' }};
                            border:1px solid {{ $isMe ? 'rgba(167,139,250,0.3)' : 'var(--bg-muted)' }};
                            border-radius:var(--radius-lg);padding:12px 16px;">
                    <div style="font-size:14px;color:var(--text-primary);line-height:1.6;margin-bottom:4px;">
                        {{ $message->body }}
                    </div>
                    <div style="font-size:11px;color:var(--text-tertiary);text-align:{{ $isMe ? 'right' : 'left' }};">
                        {{ $message->created_at->format('M d, h:i A') }}
                        @if($isMe && $message->read_at) · Read @endif
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align:center;padding:32px;color:var(--text-tertiary);font-size:14px;">
                No messages yet.
            </div>
        @endforelse
    </div>

    <div class="card" style="padding:20px;">
        <form method="POST" action="{{ route('employer.messages.send', $application) }}">
            @csrf
            <div style="margin-bottom:12px;">
                <textarea name="body" class="form-textarea" rows="3"
                          placeholder="Type your message..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Send Message</button>
        </form>
    </div>

</div>
@endsection