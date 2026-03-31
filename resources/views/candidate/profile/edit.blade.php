@extends('layouts.dashboard')

@section('title', 'My Profile')

@section('sidebar')
    @include('candidate._sidebar')
@endsection

@section('content')

<div style="max-width:800px;">

    {{-- Header --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;"
         x-data="{ editing: {{ session('editing') ? 'true' : 'false' }} }">
        <div>
            <h2 style="margin-bottom:4px;">My Profile</h2>
            <p style="font-size:13px;">Complete your profile to increase your chances of getting hired.</p>
        </div>
        <button @click="editing = !editing" class="btn btn-secondary btn-sm">
            <span x-text="editing ? 'View Profile' : 'Edit Profile'"></span>
        </button>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:20px;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:20px;">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <div x-data="{ editing: false }">

        {{-- ── VIEW MODE ── --}}
        <div x-show="!editing">

            {{-- Profile Card --}}
            <div class="card" style="padding:28px;margin-bottom:20px;">
                <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
                    <div style="width:72px;height:72px;border-radius:50%;background:var(--bg-elevated);
                                border:1px solid var(--bg-muted);display:flex;align-items:center;
                                justify-content:center;font-family:var(--font-display);font-size:26px;
                                font-weight:700;color:var(--accent-bright);flex-shrink:0;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 style="margin-bottom:4px;">{{ auth()->user()->name }}</h3>
                        @if($candidate->headline)
                            <p style="font-size:15px;color:var(--text-secondary);margin-bottom:4px;">{{ $candidate->headline }}</p>
                        @endif
                        @if($candidate->location)
                            <p style="font-size:13px;color:var(--text-tertiary);">📍 {{ $candidate->location }}</p>
                        @endif
                        @if($candidate->is_open_to_work)
                            <span class="badge badge-success" style="margin-top:6px;">Open to work</span>
                        @endif
                    </div>
                </div>

                {{-- Profile completion --}}
                <div style="margin-bottom:16px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                        <span style="font-size:13px;color:var(--text-secondary);">Profile completion</span>
                        <span style="font-size:13px;font-weight:600;color:var(--accent-bright);">{{ $candidate->profile_completion }}%</span>
                    </div>
                    <div style="background:var(--bg-muted);border-radius:var(--radius-full);height:6px;">
                        <div style="background:linear-gradient(90deg,var(--accent-glow),var(--accent-bright));border-radius:var(--radius-full);height:6px;width:{{ $candidate->profile_completion }}%;"></div>
                    </div>
                </div>

                {{-- Links --}}
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    @if($candidate->linkedin_url)
                        <a href="{{ $candidate->linkedin_url }}" target="_blank" class="btn btn-ghost btn-sm">LinkedIn →</a>
                    @endif
                    @if($candidate->github_url)
                        <a href="{{ $candidate->github_url }}" target="_blank" class="btn btn-ghost btn-sm">GitHub →</a>
                    @endif
                    @if($candidate->portfolio_url)
                        <a href="{{ $candidate->portfolio_url }}" target="_blank" class="btn btn-ghost btn-sm">Portfolio →</a>
                    @endif
                </div>
            </div>

            {{-- Bio --}}
            @if($candidate->bio)
            <div class="card" style="padding:24px;margin-bottom:20px;">
                <h4 style="margin-bottom:12px;">About</h4>
                <p style="font-size:14px;line-height:1.8;">{{ $candidate->bio }}</p>
            </div>
            @endif

            {{-- Skills --}}
            @if($candidate->skills->isNotEmpty())
            <div class="card" style="padding:24px;margin-bottom:20px;">
                <h4 style="margin-bottom:14px;">Skills</h4>
                <div style="display:flex;flex-wrap:wrap;gap:8px;">
                    @foreach($candidate->skills as $cs)
                        <span class="badge badge-purple">{{ $cs->skill->name }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Experience --}}
            @if($candidate->experiences->isNotEmpty())
            <div class="card" style="padding:24px;margin-bottom:20px;">
                <h4 style="margin-bottom:16px;">Experience</h4>
                <div style="display:flex;flex-direction:column;gap:16px;">
                    @foreach($candidate->experiences as $exp)
                        <div style="padding-bottom:16px;border-bottom:1px solid var(--bg-muted);">
                            <div style="font-size:15px;font-weight:600;color:var(--text-primary);margin-bottom:2px;">{{ $exp->role }}</div>
                            <div style="font-size:13px;color:var(--text-secondary);margin-bottom:2px;">{{ $exp->company }} @if($exp->location) · {{ $exp->location }} @endif</div>
                            <div style="font-size:12px;color:var(--text-tertiary);margin-bottom:6px;">{{ $exp->duration }}</div>
                            @if($exp->description)
                                <p style="font-size:13px;line-height:1.6;color:var(--text-secondary);">{{ $exp->description }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Education --}}
            @if($candidate->educations->isNotEmpty())
            <div class="card" style="padding:24px;margin-bottom:20px;">
                <h4 style="margin-bottom:16px;">Education</h4>
                <div style="display:flex;flex-direction:column;gap:14px;">
                    @foreach($candidate->educations as $edu)
                        <div>
                            <div style="font-size:15px;font-weight:600;color:var(--text-primary);margin-bottom:2px;">{{ $edu->degree }} @if($edu->field) in {{ $edu->field }} @endif</div>
                            <div style="font-size:13px;color:var(--text-secondary);">{{ $edu->institution }}</div>
                            <div style="font-size:12px;color:var(--text-tertiary);">{{ $edu->start_year }} – {{ $edu->is_current ? 'Present' : $edu->end_year }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- CV --}}
            @if($candidate->getMedia('cv')->isNotEmpty())
            <div class="card" style="padding:24px;margin-bottom:20px;">
                <h4 style="margin-bottom:14px;">CV / Resume</h4>
                @foreach($candidate->getMedia('cv') as $cv)
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                background:var(--bg-elevated);border:1px solid var(--bg-muted);
                                border-radius:var(--radius-md);padding:12px 16px;">
                        <div>
                            <div style="font-size:14px;font-weight:500;">{{ $cv->file_name }}</div>
                            <div style="font-size:12px;color:var(--text-tertiary);">{{ round($cv->size / 1024) }} KB</div>
                        </div>
                        <a href="{{ $cv->getUrl() }}" target="_blank" class="btn btn-ghost btn-sm">Download</a>
                    </div>
                @endforeach
            </div>
            @endif

            <button @click="editing = true" class="btn btn-primary">Edit Profile</button>

        </div>

        {{-- ── EDIT MODE ── --}}
        <div x-show="editing">

            {{-- Basic Info --}}
            <div class="card" style="padding:28px;margin-bottom:20px;">
                <h4 style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">Basic Information</h4>
                <form method="POST" action="{{ route('candidate.profile.update') }}">
                    @csrf
                    <div style="margin-bottom:16px;">
                        <label class="form-label">Professional Headline</label>
                        <input type="text" name="headline" class="form-input"
                               value="{{ old('headline', $candidate->headline) }}"
                               placeholder="e.g. Senior Laravel Developer | 5 years experience">
                    </div>
                    <div style="margin-bottom:16px;">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-textarea" rows="4"
                                  placeholder="Tell employers about yourself...">{{ old('bio', $candidate->bio) }}</textarea>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                        <div>
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-input"
                                   value="{{ old('location', $candidate->location) }}"
                                   placeholder="Karachi, Pakistan">
                        </div>
                        <div>
                            <label class="form-label">Profile Visibility</label>
                            <select name="visibility" class="form-input">
                                <option value="public" {{ $candidate->visibility === 'public' ? 'selected' : '' }}>Public</option>
                                <option value="employers" {{ $candidate->visibility === 'employers' ? 'selected' : '' }}>Employers only</option>
                                <option value="private" {{ $candidate->visibility === 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
                        <div>
                            <label class="form-label">LinkedIn</label>
                            <input type="url" name="linkedin_url" class="form-input"
                                   value="{{ old('linkedin_url', $candidate->linkedin_url) }}"
                                   placeholder="https://linkedin.com/in/...">
                        </div>
                        <div>
                            <label class="form-label">GitHub</label>
                            <input type="url" name="github_url" class="form-input"
                                   value="{{ old('github_url', $candidate->github_url) }}"
                                   placeholder="https://github.com/...">
                        </div>
                        <div>
                            <label class="form-label">Portfolio</label>
                            <input type="url" name="portfolio_url" class="form-input"
                                   value="{{ old('portfolio_url', $candidate->portfolio_url) }}"
                                   placeholder="https://yourportfolio.com">
                        </div>
                    </div>
                    <div style="margin-bottom:20px;">
                        <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
                            <input type="checkbox" name="is_open_to_work" value="1"
                                   {{ $candidate->is_open_to_work ? 'checked' : '' }}
                                   style="accent-color:var(--accent-bright);">
                            Open to work
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>

            {{-- Skills --}}
            <div class="card" style="padding:28px;margin-bottom:20px;">
                <h4 style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">Skills</h4>
                <form method="POST" action="{{ route('candidate.profile.skills') }}">
                    @csrf
                    @foreach($skills as $category => $categorySkills)
                        <div style="margin-bottom:14px;">
                            <div style="font-size:11px;font-weight:500;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:8px;">{{ $category }}</div>
                            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                                @foreach($categorySkills as $skill)
                                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;background:var(--bg-elevated);border:1px solid var(--bg-muted);border-radius:var(--radius-full);padding:4px 12px;">
                                        <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                                               {{ $candidate->skills->pluck('skill_id')->contains($skill->id) ? 'checked' : '' }}
                                               style="accent-color:var(--accent-bright);">
                                        {{ $skill->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary btn-sm" style="margin-top:8px;">Update Skills</button>
                </form>
            </div>

            {{-- Experience --}}
            <div class="card" style="padding:28px;margin-bottom:20px;">
                <h4 style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">Work Experience</h4>

                @if($candidate->experiences->isNotEmpty())
                    <div style="margin-bottom:20px;display:flex;flex-direction:column;gap:10px;">
                        @foreach($candidate->experiences as $exp)
                            <div style="background:var(--bg-elevated);border:1px solid var(--bg-muted);border-radius:var(--radius-md);padding:14px 16px;display:flex;justify-content:space-between;align-items:flex-start;">
                                <div>
                                    <div style="font-size:14px;font-weight:600;">{{ $exp->role }} at {{ $exp->company }}</div>
                                    <div style="font-size:12px;color:var(--text-tertiary);">{{ $exp->duration }}</div>
                                </div>
                                <form method="POST" action="{{ route('candidate.profile.experience.destroy', $exp) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Remove?')">Remove</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('candidate.profile.experience.store') }}">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                        <div>
                            <label class="form-label">Job Title *</label>
                            <input type="text" name="role" class="form-input" placeholder="Laravel Developer" required>
                        </div>
                        <div>
                            <label class="form-label">Company *</label>
                            <input type="text" name="company" class="form-input" placeholder="Company name" required>
                        </div>
                        <div>
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-input" placeholder="Karachi">
                        </div>
                        <div>
                            <label class="form-label">Start Date *</label>
                            <input type="date" name="start_date" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-input">
                        </div>
                        <div style="display:flex;align-items:flex-end;padding-bottom:4px;">
                            <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
                                <input type="checkbox" name="is_current" value="1" style="accent-color:var(--accent-bright);">
                                Currently working here
                            </label>
                        </div>
                    </div>
                    <div style="margin-bottom:14px;">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea" rows="2" placeholder="Describe your role..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-secondary btn-sm">+ Add Experience</button>
                </form>
            </div>

            {{-- Education --}}
            <div class="card" style="padding:28px;margin-bottom:20px;">
                <h4 style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">Education</h4>

                @if($candidate->educations->isNotEmpty())
                    <div style="margin-bottom:20px;display:flex;flex-direction:column;gap:10px;">
                        @foreach($candidate->educations as $edu)
                            <div style="background:var(--bg-elevated);border:1px solid var(--bg-muted);border-radius:var(--radius-md);padding:14px 16px;display:flex;justify-content:space-between;align-items:flex-start;">
                                <div>
                                    <div style="font-size:14px;font-weight:600;">{{ $edu->degree }} @if($edu->field) in {{ $edu->field }} @endif</div>
                                    <div style="font-size:13px;color:var(--text-secondary);">{{ $edu->institution }}</div>
                                    <div style="font-size:12px;color:var(--text-tertiary);">{{ $edu->start_year }} – {{ $edu->is_current ? 'Present' : $edu->end_year }}</div>
                                </div>
                                <form method="POST" action="{{ route('candidate.profile.education.destroy', $edu) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Remove?')">Remove</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('candidate.profile.education.store') }}">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                        <div>
                            <label class="form-label">Degree *</label>
                            <input type="text" name="degree" class="form-input" placeholder="BS Software Engineering" required>
                        </div>
                        <div>
                            <label class="form-label">Institution *</label>
                            <input type="text" name="institution" class="form-input" placeholder="FAST NUCES" required>
                        </div>
                        <div>
                            <label class="form-label">Field of Study</label>
                            <input type="text" name="field" class="form-input" placeholder="Computer Science">
                        </div>
                        <div>
                            <label class="form-label">Start Year *</label>
                            <input type="number" name="start_year" class="form-input" placeholder="2018" min="1950" max="{{ date('Y') }}" required>
                        </div>
                        <div>
                            <label class="form-label">End Year</label>
                            <input type="number" name="end_year" class="form-input" placeholder="2022" min="1950" max="{{ date('Y') + 6 }}">
                        </div>
                        <div style="display:flex;align-items:flex-end;padding-bottom:4px;">
                            <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
                                <input type="checkbox" name="is_current" value="1" style="accent-color:var(--accent-bright);">
                                Currently studying
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-secondary btn-sm">+ Add Education</button>
                </form>
            </div>

            {{-- CV Upload --}}
            <div class="card" style="padding:28px;margin-bottom:20px;">
                <h4 style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">CV / Resume</h4>

                @if($candidate->getMedia('cv')->isNotEmpty())
                    <div style="margin-bottom:16px;display:flex;flex-direction:column;gap:8px;">
                        @foreach($candidate->getMedia('cv') as $cv)
                            <div style="background:var(--bg-elevated);border:1px solid var(--bg-muted);border-radius:var(--radius-md);padding:12px 16px;display:flex;align-items:center;justify-content:space-between;">
                                <div>
                                    <div style="font-size:14px;font-weight:500;">{{ $cv->file_name }}</div>
                                    <div style="font-size:12px;color:var(--text-tertiary);">{{ round($cv->size / 1024) }} KB · {{ $cv->created_at->diffForHumans() }}</div>
                                </div>
                                <a href="{{ $cv->getUrl() }}" target="_blank" class="btn btn-ghost btn-sm">Download</a>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('candidate.profile.cv.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div style="margin-bottom:14px;">
                        <label class="form-label">Upload new CV (PDF, DOC, DOCX — max 5MB)</label>
                        <input type="file" name="cv" class="form-input" accept=".pdf,.doc,.docx" required>
                        @error('cv')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Upload CV</button>
                </form>
            </div>

            <button @click="editing = false" class="btn btn-ghost">← Back to Profile View</button>

        </div>
    </div>

</div>

@endsection