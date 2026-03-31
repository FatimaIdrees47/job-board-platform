@extends('layouts.dashboard')

@section('title', 'Edit Job')

@section('sidebar')
    @include('employer._sidebar')
@endsection

@section('content')

    <div style="max-width:860px;">

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
            <div>
                <h2 style="margin-bottom:4px;">Edit Job</h2>
                <p style="font-size:13px;">{{ $job->title }}</p>
            </div>
            <a href="{{ route('employer.jobs.index') }}" class="btn btn-ghost btn-sm">
                ← Back to listings
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom:24px;">
                <ul style="margin:0;padding-left:16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('employer.jobs.update', $job) }}">
            @csrf
            @method('PUT')

            {{-- ── Basic Info ── --}}
            <div class="card" style="padding:28px;margin-bottom:20px;">
                <h4 style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">
                    Basic Information
                </h4>

                <div style="margin-bottom:18px;">
                    <label class="form-label">Job Title *</label>
                    <input type="text" name="title"
                           class="form-input {{ $errors->has('title') ? 'error' : '' }}"
                           value="{{ old('title', $job->title) }}" required>
                    @error('title')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px;">
                    <div>
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-input" required>
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $job->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Job Type *</label>
                        <select name="type" class="form-input" required>
                            @foreach(['full-time' => 'Full-time', 'part-time' => 'Part-time', 'remote' => 'Remote', 'contract' => 'Contract', 'internship' => 'Internship', 'freelance' => 'Freelance'] as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('type', $job->type) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px;">
                    <div>
                        <label class="form-label">Experience Level *</label>
                        <select name="experience_level" class="form-input" required>
                            @foreach(['entry' => 'Entry Level', 'mid' => 'Mid Level', 'senior' => 'Senior', 'lead' => 'Lead', 'executive' => 'Executive'] as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('experience_level', $job->experience_level) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Application Deadline</label>
                        <input type="date" name="deadline" class="form-input"
                               value="{{ old('deadline', $job->deadline?->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>

            {{-- ── Location ── --}}
            <div class="card" style="padding:28px;margin-bottom:20px;">
                <h4 style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">
                    Location
                </h4>
                <div style="margin-bottom:16px;">
                    <label class="form-label">City / Location</label>
                    <input type="text" name="location" class="form-input"
                           value="{{ old('location', $job->location) }}"
                           placeholder="e.g. Karachi, Pakistan">
                </div>
                <div style="display:flex;gap:24px;">
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
                        <input type="checkbox" name="is_remote" value="1"
                               {{ old('is_remote', $job->is_remote) ? 'checked' : '' }}
                               style="accent-color:var(--accent-bright);">
                        Remote friendly
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
                        <input type="checkbox" name="is_hybrid" value="1"
                               {{ old('is_hybrid', $job->is_hybrid) ? 'checked' : '' }}
                               style="accent-color:var(--accent-bright);">
                        Hybrid option
                    </label>
                </div>
            </div>

            {{-- ── Salary ── --}}
            <div class="card" style="padding:28px;margin-bottom:20px;">
                <h4 style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">
                    Salary
                </h4>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label class="form-label">Minimum</label>
                        <input type="number" name="salary_min" class="form-input"
                               value="{{ old('salary_min', $job->salary_min) }}" min="0">
                    </div>
                    <div>
                        <label class="form-label">Maximum</label>
                        <input type="number" name="salary_max" class="form-input"
                               value="{{ old('salary_max', $job->salary_max) }}" min="0">
                    </div>
                    <div>
                        <label class="form-label">Currency</label>
                        <select name="salary_currency" class="form-input">
                            @foreach(['PKR', 'USD', 'GBP', 'AED'] as $currency)
                                <option value="{{ $currency }}"
                                    {{ old('salary_currency', $job->salary_currency) === $currency ? 'selected' : '' }}>
                                    {{ $currency }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label class="form-label">Period</label>
                        <select name="salary_period" class="form-input">
                            <option value="monthly" {{ old('salary_period', $job->salary_period) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly"  {{ old('salary_period', $job->salary_period) === 'yearly'  ? 'selected' : '' }}>Yearly</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex;gap:24px;">
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
                        <input type="checkbox" name="salary_negotiable" value="1"
                               {{ old('salary_negotiable', $job->salary_negotiable) ? 'checked' : '' }}
                               style="accent-color:var(--accent-bright);">
                        Salary negotiable
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
                        <input type="checkbox" name="show_salary" value="1"
                               {{ old('show_salary', $job->show_salary) ? 'checked' : '' }}
                               style="accent-color:var(--accent-bright);">
                        Show salary on listing
                    </label>
                </div>
            </div>

            {{-- ── Description ── --}}
            <div class="card" style="padding:28px;margin-bottom:20px;">
                <h4 style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">
                    Job Details
                </h4>
                <div style="margin-bottom:18px;">
                    <label class="form-label">Job Description *</label>
                    <textarea name="description" class="form-textarea {{ $errors->has('description') ? 'error' : '' }}"
                              rows="8" required>{{ old('description', $job->description) }}</textarea>
                    @error('description')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div style="margin-bottom:18px;">
                    <label class="form-label">Requirements</label>
                    <textarea name="requirements" class="form-textarea"
                              rows="5">{{ old('requirements', $job->requirements) }}</textarea>
                </div>
                <div>
                    <label class="form-label">Benefits</label>
                    <textarea name="benefits" class="form-textarea"
                              rows="4">{{ old('benefits', $job->benefits) }}</textarea>
                </div>
            </div>

            {{-- ── Application Method ── --}}
            <div class="card" style="padding:28px;margin-bottom:20px;">
                <h4 style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">
                    Application Method
                </h4>
                <div style="display:flex;gap:16px;margin-bottom:16px;">
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
                        <input type="radio" name="application_method" value="platform"
                               {{ old('application_method', $job->application_method) === 'platform' ? 'checked' : '' }}
                               style="accent-color:var(--accent-bright);">
                        Apply on TechJobs
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
                        <input type="radio" name="application_method" value="external"
                               {{ old('application_method', $job->application_method) === 'external' ? 'checked' : '' }}
                               style="accent-color:var(--accent-bright);">
                        External URL
                    </label>
                </div>
                @if(old('application_method', $job->application_method) === 'external')
                    <div>
                        <label class="form-label">External Application URL</label>
                        <input type="url" name="external_url" class="form-input"
                               value="{{ old('external_url', $job->external_url) }}">
                    </div>
                @endif
            </div>

            {{-- ── Screening Questions ── --}}
            <div class="card" style="padding:28px;margin-bottom:20px;" x-data="screeningQuestions()">
                <h4 style="margin-bottom:4px;">Screening Questions</h4>
                <p style="font-size:13px;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">
                    Optional. Max 5 questions.
                </p>
                <template x-for="(question, index) in questions" :key="index">
                    <div style="background:var(--bg-elevated);border:1px solid var(--bg-muted);
                                border-radius:var(--radius-md);padding:16px;margin-bottom:12px;">
                        <div style="display:flex;gap:12px;align-items:flex-start;">
                            <div style="flex:1;">
                                <input type="text"
                                       :name="`screening_questions[${index}][question]`"
                                       x-model="question.text"
                                       class="form-input"
                                       placeholder="e.g. How many years of Laravel experience do you have?">
                            </div>
                            <button type="button" @click="remove(index)"
                                    class="btn btn-danger btn-sm" style="flex-shrink:0;">Remove</button>
                        </div>
                        <label style="display:flex;align-items:center;gap:8px;font-size:13px;
                                      color:var(--text-secondary);cursor:pointer;margin-top:10px;">
                            <input type="checkbox"
                                   :name="`screening_questions[${index}][is_required]`"
                                   x-model="question.required"
                                   style="accent-color:var(--accent-bright);">
                            Required question
                        </label>
                    </div>
                </template>
                <button type="button" @click="add()"
                        x-show="questions.length < 5"
                        class="btn btn-secondary btn-sm">+ Add Question</button>
            </div>

            {{-- ── Status & Save ── --}}
            <div class="card" style="padding:24px;margin-bottom:20px;">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;">
                    <div>
                        <label class="form-label">Job Status</label>
                        <select name="status" class="form-input" style="width:auto;">
                            @foreach(['draft' => 'Draft', 'active' => 'Active', 'paused' => 'Paused', 'closed' => 'Closed'] as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('status', $job->status) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:flex;gap:10px;">
                        <a href="{{ route('employer.jobs.index') }}" class="btn btn-ghost">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>

        </form>
    </div>

@endsection

@section('scripts')
<script>
function screeningQuestions() {
    return {
        questions: @json($job->screeningQuestions->map(fn($q) => ['text' => $q->question, 'required' => $q->is_required])),
        add() {
            if (this.questions.length < 5) {
                this.questions.push({ text: '', required: true });
            }
        },
        remove(index) {
            this.questions.splice(index, 1);
        }
    }
}
</script>
@endsection