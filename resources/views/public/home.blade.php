@extends('layouts.app')

@section('title', 'Find Your Next Tech Role in Pakistan')

@section('content')

{{-- ── Hero ── --}}
<section style="padding:80px 0 60px;position:relative;overflow:hidden;">

    {{-- Background glows --}}
    <div style="position:absolute;top:-100px;right:-100px;width:600px;height:600px;
                background:radial-gradient(circle,rgba(109,40,217,0.1) 0%,transparent 70%);
                pointer-events:none;"></div>
    <div style="position:absolute;bottom:-80px;left:-80px;width:400px;height:400px;
                background:radial-gradient(circle,rgba(34,211,238,0.06) 0%,transparent 70%);
                pointer-events:none;"></div>

    <div class="container" style="position:relative;z-index:1;">

        {{-- Headline --}}
        <div style="text-align:center;max-width:720px;margin:0 auto 40px;">
            <div class="badge badge-purple" style="margin-bottom:16px;">
                🇵🇰 Built for Pakistani Tech Talent
            </div>
            <h1 style="font-size:52px;font-weight:800;line-height:1.1;
                       letter-spacing:-0.03em;margin-bottom:20px;">
                Find Your Next<br>
                <span style="color:var(--accent-bright);">Tech Role.</span>
            </h1>
            <p style="font-size:18px;color:var(--text-secondary);line-height:1.7;max-width:520px;margin:0 auto;">
                {{ number_format($stats['jobs']) }} live jobs at
                {{ number_format($stats['companies']) }} companies —
                from Karachi to remote roles across the globe.
            </p>
        </div>

        {{-- Search bar --}}
        <form action="{{ route('jobs.index') }}" method="GET"
              style="max-width:680px;margin:0 auto;">
            <div style="background:var(--bg-surface);border:1px solid var(--bg-muted);
                        border-radius:var(--radius-xl);padding:6px 6px 6px 20px;
                        display:flex;align-items:center;gap:12px;
                        transition:border-color 0.2s,box-shadow 0.2s;"
                 onfocus-within="this.style.borderColor='rgba(167,139,250,0.4)'">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" style="color:var(--text-tertiary);flex-shrink:0;">
                    <circle cx="11" cy="11" r="8" stroke-width="2"/>
                    <path d="M21 21l-4.35-4.35" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <input type="text" name="search"
                       value="{{ request('search') }}"
                       placeholder="Job title, skill, or company..."
                       style="flex:1;background:none;border:none;outline:none;
                              font-family:var(--font-body);font-size:15px;
                              color:var(--text-primary);">
                <div style="width:1px;height:24px;background:var(--bg-muted);flex-shrink:0;"></div>
                <select name="category"
                        style="background:none;border:none;outline:none;
                               font-family:var(--font-body);font-size:14px;
                               color:var(--text-secondary);padding:0 8px;cursor:pointer;">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm" style="flex-shrink:0;">
                    Search Jobs
                </button>
            </div>
        </form>

        {{-- Quick filters --}}
        <div style="display:flex;justify-content:center;gap:10px;margin-top:16px;flex-wrap:wrap;">
            @foreach(['remote' => '🌍 Remote', 'full-time' => '💼 Full-time', 'internship' => '🎓 Internship', 'contract' => '📋 Contract'] as $type => $label)
                <a href="{{ route('jobs.index', ['type' => $type]) }}"
                   class="badge badge-neutral"
                   style="padding:6px 14px;font-size:12px;cursor:pointer;text-decoration:none;">
                    {{ $label }}
                </a>
            @endforeach
        </div>

    </div>
</section>

{{-- ── Stats Banner ── --}}
<section style="border-top:1px solid var(--bg-muted);border-bottom:1px solid var(--bg-muted);
                background:var(--bg-surface);padding:28px 0;">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0;text-align:center;">
            <div style="padding:0 24px;border-right:1px solid var(--bg-muted);">
                <div style="font-family:var(--font-display);font-size:32px;font-weight:800;
                            color:var(--accent-bright);margin-bottom:4px;">
                    {{ number_format($stats['jobs']) }}+
                </div>
                <div style="font-size:13px;color:var(--text-secondary);">Live Job Listings</div>
            </div>
            <div style="padding:0 24px;border-right:1px solid var(--bg-muted);">
                <div style="font-family:var(--font-display);font-size:32px;font-weight:800;
                            color:var(--spark);margin-bottom:4px;">
                    {{ number_format($stats['companies']) }}+
                </div>
                <div style="font-size:13px;color:var(--text-secondary);">Companies Hiring</div>
            </div>
            <div style="padding:0 24px;">
                <div style="font-family:var(--font-display);font-size:32px;font-weight:800;
                            color:var(--success);margin-bottom:4px;">
                    {{ number_format($stats['candidates']) }}+
                </div>
                <div style="font-size:13px;color:var(--text-secondary);">Registered Candidates</div>
            </div>
        </div>
    </div>
</section>

{{-- ── Featured Jobs ── --}}
@if($featuredJobs->isNotEmpty())
<section class="section-gap">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;">
            <div>
                <div class="badge badge-cyan" style="margin-bottom:8px;">✦ Featured</div>
                <h2 style="margin:0;">Featured Jobs</h2>
            </div>
            <a href="{{ route('jobs.index') }}" class="btn btn-ghost btn-sm">View all jobs →</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
            @foreach($featuredJobs as $job)
                @include('public.jobs._card', ['job' => $job, 'featured' => true])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── Categories ── --}}
<section class="section-gap" style="background:var(--bg-surface);
           border-top:1px solid var(--bg-muted);border-bottom:1px solid var(--bg-muted);">
    <div class="container">
        <div style="text-align:center;margin-bottom:40px;">
            <h2 style="margin-bottom:8px;">Browse by Category</h2>
            <p style="font-size:15px;">Find opportunities in your area of expertise</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;">
            @foreach($categories as $category)
                <a href="{{ route('jobs.index', ['category' => $category->id]) }}"
                   style="text-decoration:none;">
                    <div class="card" style="padding:20px;text-align:center;
                                             transition:all 0.2s;cursor:pointer;">
                        <div style="font-size:28px;margin-bottom:10px;">{{ $category->icon }}</div>
                        <div style="font-family:var(--font-display);font-size:14px;
                                    font-weight:600;color:var(--text-primary);margin-bottom:4px;">
                            {{ $category->name }}
                        </div>
                        <div style="font-size:12px;color:var(--text-tertiary);">
                            {{ $category->jobs_count }} {{ Str::plural('job', $category->jobs_count) }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Recent Jobs ── --}}
<section class="section-gap">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;">
            <h2 style="margin:0;">Latest Opportunities</h2>
            <a href="{{ route('jobs.index') }}" class="btn btn-ghost btn-sm">View all →</a>
        </div>
        @if($recentJobs->isEmpty())
            <div style="text-align:center;padding:48px;background:var(--bg-surface);
                        border:1px solid var(--bg-muted);border-radius:var(--radius-lg);">
                <p>No jobs posted yet. Check back soon!</p>
            </div>
        @else
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
                @foreach($recentJobs as $job)
                    @include('public.jobs._card', ['job' => $job])
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- ── CTA ── --}}
<section style="background:var(--bg-surface);border-top:1px solid var(--bg-muted);padding:80px 0;">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">

            {{-- For candidates --}}
            <div class="card card-featured" style="padding:36px;">
                <div style="font-size:32px;margin-bottom:16px;">👩‍💻</div>
                <h3 style="margin-bottom:8px;">Looking for a job?</h3>
                <p style="font-size:14px;margin-bottom:24px;line-height:1.7;">
                    Create your profile, upload your CV, and apply to hundreds of tech roles in Pakistan.
                </p>
                <a href="{{ route('register.candidate') }}" class="btn btn-primary">
                    Create Candidate Profile
                </a>
            </div>

            {{-- For employers --}}
            <div class="card" style="padding:36px;border-color:rgba(34,211,238,0.2);
                                     background:linear-gradient(135deg,rgba(34,211,238,0.04),var(--bg-surface));">
                <div style="font-size:32px;margin-bottom:16px;">🏢</div>
                <h3 style="margin-bottom:8px;">Hiring tech talent?</h3>
                <p style="font-size:14px;margin-bottom:24px;line-height:1.7;">
                    Post your job listing and reach thousands of qualified Pakistani developers and remote workers.
                </p>
                <a href="{{ route('register.employer') }}" class="btn btn-spark">
                    Post a Job →
                </a>
            </div>

        </div>
    </div>
</section>

@endsection