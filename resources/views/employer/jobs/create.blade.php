@extends('layouts.dashboard')

@section('title', 'Post a Job')

@section('sidebar')
    @include('employer._sidebar')
@endsection

@section('content')

    <div style="max-width:860px;">

        <div style="margin-bottom:28px;">
            <h2 style="margin-bottom:4px;">Post a New Job</h2>
            <p style="font-size:13px;">Fill in the details below. You can save as draft or publish immediately.</p>
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

        <form method="POST" action="{{ route('employer.jobs.store') }}">
            @csrf

            {{-- ── Basic Info ── --}}
            <div class="card" style="padding:28px;margin-bottom:20px;">
                <h4 style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">
                    Basic Information
                </h4>

                <div style="margin-bottom:18px;">
                    <label class="form-label">Job Title *</label>
                    <input type="text" name="title" class="form-input {{ $errors->has('title') ? 'error' : '' }}"
                           value="{{ old('title') }}" placeholder="e.g. Senior Laravel Developer" required>
                    @error('title')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px;">
                    <div>
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-input {{ $errors->has('category_id') ? 'error' : '' }}" required>
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label">Job Type *</label>
                        <select name="type" class="form-input {{ $errors->has('type') ? 'error' : '' }}" required>
                            <option value="">Select type</option>
                            @foreach(['full-time' => 'Full-time', 'part-time' => 'Part-time', 'remote' => 'Remote', 'contract' => 'Contract', 'internship' => 'Internship', 'freelance' => 'Freelance'] as $value => $label)
                                <option value="{{ $value }}" {{ old('type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px;">
                    <div>
                        <label class="form-label">Experience Level *</label>
                        <select name="experience_level" class="form-input" required>
                            @foreach(['entry' => 'Entry Level', 'mid' => 'Mid Level', 'senior' => 'Senior', 'lead' => 'Lead', 'executive' => 'Executive'] as $value => $label)
                                <option value="{{ $value }}" {{ old('experience_level') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Application Deadline</label>
                        <input type="date" name="deadline" class="form-input"
                               value="{{ old('deadline') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        @error('deadline')<span class="form-error">{{ $message }}</span>@enderror
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
                           value="{{ old('location') }}" placeholder="e.g. Karachi, Pakistan">
                </div>

                <div style="display:flex;gap:24px;">
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
                        <input type="checkbox" name="is_remote" value="1"
                               {{ old('is_remote') ? 'checked' : '' }}
                               style="accent-color:var(--accent-bright);">
                        Remote friendly
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
                        <input type="checkbox" name="is_hybrid" value="1"
                               {{ old('is_hybrid') ? 'checked' : '' }}
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
                               value="{{ old('salary_min') }}" placeholder="50000" min="0">
                        @error('salary_min')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="form-label">Maximum</label>
                        <input type="number" name="salary_max" class="form-input"
                               value="{{ old('salary_max') }}" placeholder="100000" min="0">
                        @error('salary_max')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="form-label">Currency</label>
                        <select name="salary_currency" class="form-input">
                            <option value="PKR" {{ old('salary_currency', 'PKR') === 'PKR' ? 'selected' : '' }}>PKR</option>
                            <option value="USD" {{ old('salary_currency') === 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="GBP" {{ old('salary_currency') === 'GBP' ? 'selected' : '' }}>GBP</option>
                            <option value="AED" {{ old('salary_currency') === 'AED' ? 'selected' : '' }}>AED</option>
                        </select>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label class="form-label">Period</label>
                        <select name="salary_period" class="form-input">
                            <option value="monthly" {{ old('salary_period', 'monthly') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ old('salary_period') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>
                    </div>
                </div>

                <div style="display:flex;gap:24px;">
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
                        <input type="checkbox" name="salary_negotiable" value="1"
                               {{ old('salary_negotiable') ? 'checked' : '' }}
                               style="accent-color:var(--accent-bright);">
                        Salary negotiable
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
                        <input type="checkbox" name="show_salary" value="1"
                               {{ old('show_salary', true) ? 'checked' : '' }}
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
                    <label class="form-label">Job Description * <span style="font-size:11px;color:var(--text-tertiary);text-transform:none;">(min. 100 characters)</span></label>
                    <textarea name="description" class="form-textarea {{ $errors->has('description') ? 'error' : '' }}"
                              rows="8" placeholder="Describe the role, responsibilities, and what the candidate will be working on..."
                              required>{{ old('description') }}</textarea>
                    @error('description')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div style="margin-bottom:18px;">
                    <label class="form-label">Requirements</label>
                    <textarea name="requirements" class="form-textarea" rows="5"
                              placeholder="List the skills, experience, and qualifications required...">{{ old('requirements') }}</textarea>
                </div>

                <div>
                    <label class="form-label">Benefits</label>
                    <textarea name="benefits" class="form-textarea" rows="4"
                              placeholder="Health insurance, remote work, equity, flexible hours...">{{ old('benefits') }}</textarea>
                </div>
            </div>

            {{-- ── Application Method ── --}}
            <div class="card" style="padding:28px;margin-bottom:20px;">
                <h4 style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">
                    Application Method
                </h4>

                <div style="display:flex;gap:16px;margin-bottom:16px;" x-data="{ method: '{{ old('application_method', 'platform') }}' }">
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
                        <input type="radio" name="application_method" value="platform"
                               x-model="method"
                               {{ old('application_method', 'platform') === 'platform' ? 'checked' : '' }}
                               style="accent-color:var(--accent-bright);">
                        Apply on TechJobs (recommended)
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
                        <input type="radio" name="application_method" value="external"
                               x-model="method"
                               {{ old('application_method') === 'external' ? 'checked' : '' }}
                               style="accent-color:var(--accent-bright);">
                        External URL
                    </label>
                </div>

                <div x-show="method === 'external'" x-data="{ method: '{{ old('application_method', 'platform') }}' }">
                    <label class="form-label">External Application URL</label>
                    <input type="url" name="external_url" class="form-input"
                           value="{{ old('external_url') }}" placeholder="https://yourcompany.com/careers/apply">
                    @error('external_url')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- ── Screening Questions ── --}}
            <div class="card" style="padding:28px;margin-bottom:20px;" x-data="screeningQuestions()">
                <h4 style="margin-bottom:4px;padding-bottom:0;">Screening Questions</h4>
                <p style="font-size:13px;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--bg-muted);">
                    Optional — candidates must answer these before submitting. Max 5 questions.
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
                                    class="btn btn-danger btn-sm" style="flex-shrink:0;">
                                Remove
                            </button>
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
                        class="btn btn-secondary btn-sm">
                    + Add Question
                </button>
            </div>

            {{-- ── Publish ── --}}
            <div class="card" style="padding:24px;margin-bottom:20px;">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;">
                    <div>
                        <h4 style="margin-bottom:4px;">Ready to publish?</h4>
                        <p style="font-size:13px;">Active listings go to admin for approval before going live.</p>
                    </div>
                    <div style="display:flex;gap:10px;">
                        <button type="submit" name="status" value="draft" class="btn btn-ghost">
                            Save as Draft
                        </button>
                        <button type="submit" name="status" value="active" class="btn btn-primary">
                            Submit for Approval
                        </button>
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
        questions: @json(old('screening_questions') ? collect(old('screening_questions'))->map(fn($q) => ['text' => $q['question'] ?? '', 'required' => isset($q['is_required'])]) : []),
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