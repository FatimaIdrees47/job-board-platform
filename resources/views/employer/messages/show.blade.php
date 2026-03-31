@extends('layouts.dashboard')
@section('title', 'Conversation')
@section('sidebar')
    @include('employer._sidebar')
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