@extends('layouts.app')

@section('title', $job->title . ' at ' . $job->employer->company_name)

@section('content')
<div class="container" style="padding-top:40px;padding-bottom:80px;">
    <div style="display:grid;grid-template-columns:1fr 340px;gap:28px;align-items:start;">

        {{-- Main Content --}}
        <div>

            {{-- Breadcrumb --}}
            <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-tertiary);margin-bottom:24px;">
                <a href="{{ route('home') }}" style="color:var(--text-tertiary);text-decoration:none;">Home</a>
                <span>›</span>
                <a href="{{ route('jobs.index') }}" style="color:var(--text-tertiary);text-decoration:none;">Jobs</a>
                <span>›</span>
                @if($job->category)
                    <a href="{{ route('jobs.index', ['category' => $job->category_id]) }}" style="color:var(--text-tertiary);text-decoration:none;">{{ $job->category->name }}</a>
                    <span>›</span>
                @endif
                <span style="color:var(--text-secondary);">{{ $job->title }}</span>
            </div>

            {{-- Job Header --}}
            <div class="card" style="padding:32px;margin-bottom:20px;">
                <div style="display:flex;align-items:flex-start;gap:16px;margin-bottom:24px;">

                    {{-- Logo --}}
                    <div style="width:64px;height:64px;border-radius:var(--radius-lg);background:var(--bg-elevated);border:1px solid var(--bg-muted);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:22px;font-weight:700;color:var(--accent-bright);flex-shrink:0;overflow:hidden;">
                        @if($job->employer->getFirstMediaUrl('logo'))
                            <img src="{{ $job->employer->getFirstMediaUrl('logo') }}" alt="{{ $job->employer->company_name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            {{ strtoupper(substr($job->employer->company_name, 0, 2)) }}
                        @endif
                    </div>

                    <div style="flex:1;">
                        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:8px;">
                            @if($job->is_featured)
                                <span class="badge badge-cyan">✦ Featured</span>
                            @endif
                            @if($job->is_remote)
                                <span class="badge badge-purple">Remote</span>
                            @endif
                            @if($job->is_hybrid)
                                <span class="badge badge-neutral">Hybrid</span>
                            @endif
                        </div>
                        <h1 style="font-size:28px;margin-bottom:6px;">{{ $job->title }}</h1>
                        <div style="display:flex;flex-wrap:wrap;gap:16px;font-size:14px;color:var(--text-secondary);">
                            <span>{{ $job->employer->company_name }}</span>
                            @if($job->location)
                                <span>📍 {{ $job->location }}</span>
                            @endif
                            <span>Posted {{ $job->created_at->diffForHumans() }}</span>
                            @if($job->deadline)
                                <span style="color:var(--warning);">⏰ Deadline: {{ $job->deadline->format('M d, Y') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Key details --}}
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;padding:20px;background:var(--bg-elevated);border-radius:var(--radius-md);">
                    <div style="text-align:center;">
                        <div style="font-size:11px;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;">Job Type</div>
                        <div style="font-size:14px;font-weight:500;color:var(--text-primary);">{{ ucfirst(str_replace('-', ' ', $job->type)) }}</div>
                    </div>
                    <div style="text-align:center;border-left:1px solid var(--bg-muted);">
                        <div style="font-size:11px;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;">Experience</div>
                        <div style="font-size:14px;font-weight:500;color:var(--text-primary);">{{ ucfirst($job->experience_level) }}</div>
                    </div>
                    <div style="text-align:center;border-left:1px solid var(--bg-muted);">
                        <div style="font-size:11px;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;">Salary</div>
                        <div style="font-size:14px;font-weight:500;color:var(--accent-bright);">{{ $job->salary_display }}</div>
                    </div>
                    <div style="text-align:center;border-left:1px solid var(--bg-muted);">
                        <div style="font-size:11px;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;">Applications</div>
                        <div style="font-size:14px;font-weight:500;color:var(--text-primary);">{{ $job->applications_count }}</div>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="card" style="padding:32px;margin-bottom:20px;">
                <h3 style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">About this role</h3>
                <div style="color:var(--text-secondary);line-height:1.8;font-size:15px;white-space:pre-wrap;">{{ $job->description }}</div>
            </div>

            {{-- Requirements --}}
            @if($job->requirements)
                <div class="card" style="padding:32px;margin-bottom:20px;">
                    <h3 style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">Requirements</h3>
                    <div style="color:var(--text-secondary);line-height:1.8;font-size:15px;white-space:pre-wrap;">{{ $job->requirements }}</div>
                </div>
            @endif

            {{-- Benefits --}}
            @if($job->benefits)
                <div class="card" style="padding:32px;margin-bottom:20px;">
                    <h3 style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">Benefits & Perks</h3>
                    <div style="color:var(--text-secondary);line-height:1.8;font-size:15px;white-space:pre-wrap;">{{ $job->benefits }}</div>
                </div>
            @endif

            {{-- Similar Jobs --}}
            @if($similarJobs->isNotEmpty())
                <div style="margin-top:32px;">
                    <h3 style="margin-bottom:20px;">Similar Jobs</h3>
                    <div style="display:flex;flex-direction:column;gap:12px;">
                        @foreach($similarJobs as $similarJob)
                            @include('public.jobs._card', ['job' => $similarJob])
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- Sidebar --}}
        <div style="position:sticky;top:80px;">

            {{-- Apply Card --}}
            <div class="card card-featured" style="padding:28px;margin-bottom:16px;">
                <div style="font-family:var(--font-display);font-size:22px;font-weight:700;color:var(--accent-bright);margin-bottom:4px;">
                    {{ $job->salary_display }}
                </div>
                <div style="font-size:13px;color:var(--text-tertiary);margin-bottom:20px;">
                    {{ $job->applications_count }} people applied
                </div>

                @if($job->deadline)
                    <div style="background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.2);border-radius:var(--radius-md);padding:10px 14px;font-size:13px;color:var(--warning);margin-bottom:16px;">
                        ⏰ Closes {{ $job->deadline->format('M d, Y') }}
                    </div>
                @endif

                @if(auth()->check() && auth()->user()->hasRole('candidate'))
                    @if($job->application_method === 'external')
                        <a href="{{ $job->external_url }}" target="_blank" class="btn btn-primary" style="width:100%;justify-content:center;">
                            Apply on Company Site →
                        </a>
                    @else
                        <a href="{{ route('jobs.apply', $job) }}" class="btn btn-primary" style="width:100%;justify-content:center;">
                            Apply Now
                        </a>
                    @endif
                @elseif(auth()->check() && auth()->user()->hasRole('employer'))
                    <div style="text-align:center;font-size:13px;color:var(--text-tertiary);">
                        You're logged in as an employer.
                    </div>
                @else
                    <a href="{{ route('register.candidate') }}" class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:10px;">
                        Sign up to Apply
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;">
                        Already have an account? Sign in
                    </a>
                @endif

                <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--bg-muted);">
                    <button onclick="navigator.clipboard.writeText(window.location.href);this.textContent='Copied!';" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;">
                        Copy Link
                    </button>
                </div>
            </div>

            {{-- Company Card --}}
            <div class="card" style="padding:24px;">
                <h4 style="margin-bottom:16px;font-size:15px;">About the Company</h4>

                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div style="width:44px;height:44px;border-radius:var(--radius-md);background:var(--bg-elevated);border:1px solid var(--bg-muted);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:16px;font-weight:700;color:var(--accent-bright);flex-shrink:0;">
                        {{ strtoupper(substr($job->employer->company_name, 0, 2)) }}
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:14px;">{{ $job->employer->company_name }}</div>
                        @if($job->employer->is_verified)
                            <span class="badge badge-success" style="font-size:10px;">✓ Verified</span>
                        @endif
                    </div>
                </div>

                @if($job->employer->description)
                    <p style="font-size:13px;line-height:1.7;margin-bottom:14px;">{{ Str::limit($job->employer->description, 150) }}</p>
                @endif

                <div style="display:flex;flex-direction:column;gap:8px;font-size:13px;color:var(--text-secondary);">
                    @if($job->employer->industry)
                        <div style="display:flex;gap:8px;">
                            <span style="color:var(--text-tertiary);width:80px;flex-shrink:0;">Industry</span>
                            <span>{{ $job->employer->industry }}</span>
                        </div>
                    @endif
                    @if($job->employer->size_range)
                        <div style="display:flex;gap:8px;">
                            <span style="color:var(--text-tertiary);width:80px;flex-shrink:0;">Size</span>
                            <span>{{ $job->employer->size_range }} employees</span>
                        </div>
                    @endif
                    @if($job->employer->location)
                        <div style="display:flex;gap:8px;">
                            <span style="color:var(--text-tertiary);width:80px;flex-shrink:0;">Location</span>
                            <span>{{ $job->employer->location }}</span>
                        </div>
                    @endif
                    @if($job->employer->website)
                        <div style="display:flex;gap:8px;">
                            <span style="color:var(--text-tertiary);width:80px;flex-shrink:0;">Website</span>
                            <a href="{{ $job->employer->website }}" target="_blank" style="font-size:13px;word-break:break-all;">
                                {{ parse_url($job->employer->website, PHP_URL_HOST) }}
                            </a>
                        </div>
                    @endif
                </div>

                @if($job->employer->website)
                    <a href="{{ $job->employer->website }}" target="_blank" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;margin-top:14px;">
                        Visit Company Website →
                    </a>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
