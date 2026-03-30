@extends('layouts.app')

@section('title', 'Browse Jobs')

@section('content')

<div class="container section-gap">

    <div style="display:grid;grid-template-columns:280px 1fr;gap:28px;align-items:start;">

        {{-- ── Filters Sidebar ── --}}
        <div style="position:sticky;top:80px;">
            <div class="card" style="padding:24px;">
                <h4 style="margin-bottom:20px;">Filter Jobs</h4>

                <form action="{{ route('jobs.index') }}" method="GET">

                    <div style="margin-bottom:18px;">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-input"
                               value="{{ request('search') }}"
                               placeholder="Title, skill, keyword...">
                    </div>

                    <div style="margin-bottom:18px;">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-input">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom:18px;">
                        <label class="form-label">Job Type</label>
                        <select name="type" class="form-input">
                            <option value="">All Types</option>
                            @foreach(['full-time' => 'Full-time', 'part-time' => 'Part-time', 'remote' => 'Remote', 'contract' => 'Contract', 'internship' => 'Internship', 'freelance' => 'Freelance'] as $value => $label)
                                <option value="{{ $value }}"
                                    {{ request('type') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom:18px;">
                        <label class="form-label">Experience Level</label>
                        <select name="experience" class="form-input">
                            <option value="">All Levels</option>
                            @foreach(['entry' => 'Entry Level', 'mid' => 'Mid Level', 'senior' => 'Senior', 'lead' => 'Lead', 'executive' => 'Executive'] as $value => $label)
                                <option value="{{ $value }}"
                                    {{ request('experience') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom:24px;">
                        <label style="display:flex;align-items:center;gap:8px;
                                      font-size:14px;cursor:pointer;">
                            <input type="checkbox" name="remote" value="1"
                                   {{ request('remote') ? 'checked' : '' }}
                                   style="accent-color:var(--accent-bright);">
                            Remote only
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary"
                            style="width:100%;justify-content:center;">
                        Apply Filters
                    </button>

                    @if(request()->hasAny(['search', 'category', 'type', 'experience', 'remote']))
                        <a href="{{ route('jobs.index') }}"
                           class="btn btn-ghost btn-sm"
                           style="width:100%;justify-content:center;margin-top:8px;">
                            Clear Filters
                        </a>
                    @endif

                </form>
            </div>
        </div>

        {{-- ── Job Listings ── --}}
        <div>
            <div style="display:flex;justify-content:space-between;
                        align-items:center;margin-bottom:20px;">
                <div>
                    <h2 style="margin-bottom:4px;font-size:22px;">
                        {{ $jobs->total() }} {{ Str::plural('Job', $jobs->total()) }} Found
                    </h2>
                    @if(request()->hasAny(['search', 'category', 'type', 'experience', 'remote']))
                        <p style="font-size:13px;">Filtered results</p>
                    @endif
                </div>
            </div>

            @if($jobs->isEmpty())
                <div style="text-align:center;padding:64px 24px;
                            background:var(--bg-surface);border:1px solid var(--bg-muted);
                            border-radius:var(--radius-lg);">
                    <div style="font-size:48px;margin-bottom:16px;">🔍</div>
                    <h3 style="margin-bottom:8px;">No jobs found</h3>
                    <p style="font-size:14px;margin-bottom:20px;">
                        Try adjusting your filters or search terms.
                    </p>
                    <a href="{{ route('jobs.index') }}" class="btn btn-primary">Clear Filters</a>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:14px;">
                    @foreach($jobs as $job)
                        @include('public.jobs._card', ['job' => $job])
                    @endforeach
                </div>

                <div style="margin-top:32px;">
                    {{ $jobs->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

@endsection