@extends('layouts.dashboard')
@section('title', 'Messages')
@section('sidebar')
    @include('employer._sidebar')
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