<div class="card {{ $featured ?? false ? 'card-featured' : '' }}"
     style="padding:22px;cursor:pointer;"
     onclick="window.location='{{ route('jobs.show', $job) }}'">

    {{-- Top row --}}
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
        <div style="display:flex;align-items:center;gap:12px;">
            {{-- Company logo --}}
            <div style="width:44px;height:44px;border-radius:var(--radius-md);
                        background:var(--bg-elevated);border:1px solid var(--bg-muted);
                        display:flex;align-items:center;justify-content:center;
                        font-family:var(--font-display);font-size:16px;font-weight:700;
                        color:var(--accent-bright);flex-shrink:0;overflow:hidden;">
                @if($job->employer->getFirstMediaUrl('logo'))
                    <img src="{{ $job->employer->getFirstMediaUrl('logo') }}"
                         alt="{{ $job->employer->company_name }}"
                         style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ strtoupper(substr($job->employer->company_name, 0, 2)) }}
                @endif
            </div>
            <div>
                <div style="font-family:var(--font-display);font-size:16px;
                            font-weight:600;color:var(--text-primary);margin-bottom:2px;">
                    {{ $job->title }}
                </div>
                <div style="font-size:13px;color:var(--text-secondary);">
                    {{ $job->employer->company_name }}
                    @if($job->location) · {{ $job->location }} @endif
                </div>
            </div>
        </div>
        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
            @if($featured ?? false)
                <span class="badge badge-cyan">✦ Featured</span>
            @endif
            @if($job->is_remote)
                <span class="badge badge-purple">Remote</span>
            @endif
        </div>
    </div>

    {{-- Tags --}}
    <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px;">
        <span class="badge badge-neutral">{{ ucfirst(str_replace('-', ' ', $job->type)) }}</span>
        <span class="badge badge-neutral">{{ ucfirst($job->experience_level) }}</span>
        @if($job->category)
            <span class="badge badge-neutral">{{ $job->category->name }}</span>
        @endif
    </div>

    {{-- Footer --}}
    <div style="display:flex;justify-content:space-between;align-items:center;
                padding-top:12px;border-top:1px solid var(--bg-muted);">
        <div style="font-family:var(--font-display);font-size:14px;
                    font-weight:600;color:var(--accent-bright);">
            {{ $job->salary_display }}
        </div>
        <div style="font-size:12px;color:var(--text-tertiary);">
            {{ $job->created_at->diffForHumans() }}
        </div>
    </div>

</div>