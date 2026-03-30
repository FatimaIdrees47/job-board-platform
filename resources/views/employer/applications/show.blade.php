@extends('layouts.dashboard')

@section('title', 'Application — ' . $application->candidate->user->name)

@section('sidebar')
    <a href="{{ route('employer.dashboard') }}" class="sidebar-link">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
    </a>
    <a href="{{ route('employer.jobs.index') }}" class="sidebar-link active">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        My Jobs
    </a>
@endsection

@section('content')

    <div style="max-width:860px;">

        {{-- Back --}}
        <a href="{{ route('employer.jobs.applications.index', $job) }}"
           style="font-size:13px;color:var(--text-tertiary);text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:24px;">
            ← Back to applications
        </a>

        <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

            {{-- Left: Candidate Profile --}}
            <div>

                {{-- Candidate Header --}}
                <div class="card" style="padding:28px;margin-bottom:16px;">
                    <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px;">
                        <div style="width:64px;height:64px;border-radius:50%;background:var(--bg-elevated);border:1px solid var(--bg-muted);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:24px;font-weight:700;color:var(--accent-bright);flex-shrink:0;">
                            {{ strtoupper(substr($application->candidate->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 style="margin-bottom:4px;">{{ $application->candidate->user->name }}</h3>
                            <p style="font-size:14px;">{{ $application->candidate->headline ?? 'No headline' }}</p>
                            @if($application->candidate->location)
                                <p style="font-size:13px;color:var(--text-tertiary);">📍 {{ $application->candidate->location }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Links --}}
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        @if($application->candidate->linkedin_url)
                            <a href="{{ $application->candidate->linkedin_url }}" target="_blank" class="btn btn-ghost btn-sm">LinkedIn</a>
                        @endif
                        @if($application->candidate->github_url)
                            <a href="{{ $application->candidate->github_url }}" target="_blank" class="btn btn-ghost btn-sm">GitHub</a>
                        @endif
                        @if($application->candidate->portfolio_url)
                            <a href="{{ $application->candidate->portfolio_url }}" target="_blank" class="btn btn-ghost btn-sm">Portfolio</a>
                        @endif
                    </div>
                </div>

                {{-- Bio --}}
                @if($application->candidate->bio)
                <div class="card" style="padding:24px;margin-bottom:16px;">
                    <h4 style="margin-bottom:12px;">About</h4>
                    <p style="font-size:14px;line-height:1.7;">{{ $application->candidate->bio }}</p>
                </div>
                @endif

                {{-- Skills --}}
                @if($application->candidate->skills->isNotEmpty())
                <div class="card" style="padding:24px;margin-bottom:16px;">
                    <h4 style="margin-bottom:14px;">Skills</h4>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        @foreach($application->candidate->skills as $candidateSkill)
                            <span class="badge badge-purple">{{ $candidateSkill->skill->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Experience --}}
                @if($application->candidate->experiences->isNotEmpty())
                <div class="card" style="padding:24px;margin-bottom:16px;">
                    <h4 style="margin-bottom:16px;">Experience</h4>
                    <div style="display:flex;flex-direction:column;gap:16px;">
                        @foreach($application->candidate->experiences as $exp)
                            <div style="padding-bottom:16px;border-bottom:1px solid var(--bg-muted);">
                                <div style="font-size:15px;font-weight:600;color:var(--text-primary);margin-bottom:2px;">{{ $exp->role }}</div>
                                <div style="font-size:13px;color:var(--text-secondary);margin-bottom:4px;">{{ $exp->company }} @if($exp->location) · {{ $exp->location }} @endif</div>
                                <div style="font-size:12px;color:var(--text-tertiary);margin-bottom:6px;">{{ $exp->duration }}</div>
                                @if($exp->description)
                                    <p style="font-size:13px;line-height:1.6;">{{ $exp->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Education --}}
                @if($application->candidate->educations->isNotEmpty())
                <div class="card" style="padding:24px;margin-bottom:16px;">
                    <h4 style="margin-bottom:16px;">Education</h4>
                    <div style="display:flex;flex-direction:column;gap:14px;">
                        @foreach($application->candidate->educations as $edu)
                            <div>
                                <div style="font-size:15px;font-weight:600;color:var(--text-primary);margin-bottom:2px;">{{ $edu->degree }} @if($edu->field) in {{ $edu->field }} @endif</div>
                                <div style="font-size:13px;color:var(--text-secondary);">{{ $edu->institution }}</div>
                                <div style="font-size:12px;color:var(--text-tertiary);">{{ $edu->start_year }} – {{ $edu->is_current ? 'Present' : $edu->end_year }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Cover Letter --}}
                @if($application->cover_letter)
                <div class="card" style="padding:24px;margin-bottom:16px;">
                    <h4 style="margin-bottom:14px;">Cover Letter</h4>
                    <div style="font-size:14px;line-height:1.8;color:var(--text-secondary);white-space:pre-wrap;">{{ $application->cover_letter }}</div>
                </div>
                @endif

                {{-- Screening Answers --}}
                @if($application->screeningAnswers->isNotEmpty())
                <div class="card" style="padding:24px;">
                    <h4 style="margin-bottom:16px;">Screening Answers</h4>
                    <div style="display:flex;flex-direction:column;gap:16px;">
                        @foreach($application->screeningAnswers as $answer)
                            <div>
                                <div style="font-size:14px;font-weight:500;color:var(--text-primary);margin-bottom:6px;">{{ $answer->question->question }}</div>
                                <div style="font-size:14px;color:var(--text-secondary);background:var(--bg-elevated);border-radius:var(--radius-md);padding:12px 16px;line-height:1.6;">{{ $answer->answer }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            {{-- Right: Actions Sidebar --}}
            <div style="position:sticky;top:80px;">

                {{-- Current Status --}}
                <div class="card" style="padding:24px;margin-bottom:16px;">
                    <h4 style="margin-bottom:16px;font-size:15px;">Application Status</h4>
                    <div style="margin-bottom:16px;">
                        <span class="status-badge {{ $application->status_color }}">
                            {{ $application->status_label }}
                        </span>
                    </div>

                    {{-- Update Status --}}
                    <form method="POST" action="{{ route('employer.jobs.applications.update-status', [$job, $application]) }}">
                        @csrf @method('PATCH')
                        <div style="margin-bottom:10px;">
                            <label class="form-label">Move to</label>
                            <select name="status" class="form-input" style="margin-bottom:8px;">
                                @foreach(['reviewing' => 'Reviewing', 'shortlisted' => 'Shortlisted', 'interview' => 'Interview Scheduled', 'offered' => 'Offer Extended', 'rejected' => 'Rejected'] as $value => $label)
                                    <option value="{{ $value }}" {{ $application->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <textarea name="note" class="form-textarea" rows="2" placeholder="Optional note..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">
                            Update Status
                        </button>
                    </form>
                </div>

                {{-- Add Private Note --}}
                <div class="card" style="padding:24px;margin-bottom:16px;">
                    <h4 style="margin-bottom:14px;font-size:15px;">Private Notes</h4>
                    <form method="POST" action="{{ route('employer.jobs.applications.add-note', [$job, $application]) }}">
                        @csrf
                        <textarea name="note" class="form-textarea" rows="3" placeholder="Add a private note about this candidate..." required></textarea>
                        <button type="submit" class="btn btn-secondary btn-sm" style="width:100%;justify-content:center;margin-top:8px;">
                            Save Note
                        </button>
                    </form>
                </div>

                {{-- Timeline --}}
                <div class="card" style="padding:24px;">
                    <h4 style="margin-bottom:16px;font-size:15px;">Timeline</h4>
                    <div style="position:relative;padding-left:20px;">
                        <div style="position:absolute;left:6px;top:8px;bottom:8px;width:2px;background:var(--bg-muted);"></div>
                        @foreach($application->statusHistory as $history)
                            <div style="position:relative;margin-bottom:16px;">
                                <div style="position:absolute;left:-17px;top:4px;width:8px;height:8px;border-radius:50%;background:var(--accent-bright);border:2px solid var(--bg-base);"></div>
                                <div style="font-size:13px;font-weight:500;color:var(--text-primary);margin-bottom:2px;">
                                    {{ ucfirst($history->status) }}
                                </div>
                                @if($history->note)
                                    <div style="font-size:12px;color:var(--text-secondary);margin-bottom:2px;">{{ $history->note }}</div>
                                @endif
                                <div style="font-size:11px;color:var(--text-tertiary);">
                                    {{ $history->created_at->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection