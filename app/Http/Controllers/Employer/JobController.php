<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\StoreJobRequest;
use App\Http\Requests\Employer\UpdateJobRequest;
use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $employer = auth()->user()->employerProfile;

        $jobs = Job::where('employer_id', $employer->id)
            ->withCount('screeningQuestions')
            ->latest()
            ->paginate(15);

        return view('employer.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('employer.jobs.create', compact('categories'));
    }

    public function store(StoreJobRequest $request)
    {
        $employer = auth()->user()->employerProfile;

        $job = Job::create([
            'employer_id'        => $employer->id,
            'category_id'        => $request->category_id,
            'title'              => $request->title,
            'type'               => $request->type,
            'location'           => $request->location,
            'is_remote'          => $request->boolean('is_remote'),
            'is_hybrid'          => $request->boolean('is_hybrid'),
            'salary_min'         => $request->salary_min,
            'salary_max'         => $request->salary_max,
            'salary_currency'    => $request->salary_currency ?? 'PKR',
            'salary_period'      => $request->salary_period,
            'salary_negotiable'  => $request->boolean('salary_negotiable'),
            'show_salary'        => $request->boolean('show_salary', true),
            'experience_level'   => $request->experience_level,
            'description'        => $request->description,
            'requirements'       => $request->requirements,
            'benefits'           => $request->benefits,
            'application_method' => $request->application_method,
            'external_url'       => $request->external_url,
            'deadline'           => $request->deadline,
            'status'             => $request->status,
        ]);

        if ($request->filled('screening_questions')) {
            foreach ($request->screening_questions as $index => $q) {
                if (! empty($q['question'])) {
                    $job->screeningQuestions()->create([
                        'question'    => $q['question'],
                        'is_required' => isset($q['is_required']),
                        'sort_order'  => $index,
                    ]);
                }
            }
        }

        $message = $request->status === 'active'
            ? 'Job posted successfully! It will be visible after admin approval.'
            : 'Job saved as draft.';

        return redirect()->route('employer.jobs.index')
            ->with('success', $message);
    }

    public function show(Job $job)
    {
        $this->authorizeJob($job);
        $job->load('screeningQuestions', 'category');
        return view('employer.jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        $this->authorizeJob($job);
        $categories = Category::orderBy('name')->get();
        $job->load('screeningQuestions');
        return view('employer.jobs.edit', compact('job', 'categories'));
    }

    public function update(UpdateJobRequest $request, Job $job)
    {
        $this->authorizeJob($job);

        $job->update([
            'category_id'        => $request->category_id,
            'title'              => $request->title,
            'type'               => $request->type,
            'location'           => $request->location,
            'is_remote'          => $request->boolean('is_remote'),
            'is_hybrid'          => $request->boolean('is_hybrid'),
            'salary_min'         => $request->salary_min,
            'salary_max'         => $request->salary_max,
            'salary_currency'    => $request->salary_currency,
            'salary_period'      => $request->salary_period,
            'salary_negotiable'  => $request->boolean('salary_negotiable'),
            'show_salary'        => $request->boolean('show_salary', true),
            'experience_level'   => $request->experience_level,
            'description'        => $request->description,
            'requirements'       => $request->requirements,
            'benefits'           => $request->benefits,
            'application_method' => $request->application_method,
            'external_url'       => $request->external_url,
            'deadline'           => $request->deadline,
            'status'             => $request->status,
        ]);

        $job->screeningQuestions()->delete();

        if ($request->filled('screening_questions')) {
            foreach ($request->screening_questions as $index => $q) {
                if (! empty($q['question'])) {
                    $job->screeningQuestions()->create([
                        'question'    => $q['question'],
                        'is_required' => isset($q['is_required']),
                        'sort_order'  => $index,
                    ]);
                }
            }
        }

        return redirect()->route('employer.jobs.index')
            ->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $this->authorizeJob($job);
        $job->delete();

        return redirect()->route('employer.jobs.index')
            ->with('success', 'Job listing deleted.');
    }

    public function duplicate(Job $job)
    {
        $this->authorizeJob($job);

        $newJob = $job->replicate();
        $newJob->title              = $job->title.' (Copy)';
        $newJob->status             = 'draft';
        $newJob->is_featured        = false;
        $newJob->featured_until     = null;
        $newJob->views_count        = 0;
        $newJob->applications_count = 0;
        $newJob->is_approved        = false;
        $newJob->slug               = null;
        $newJob->save();

        foreach ($job->screeningQuestions as $question) {
            $newJob->screeningQuestions()->create([
                'question'    => $question->question,
                'is_required' => $question->is_required,
                'sort_order'  => $question->sort_order,
            ]);
        }

        return redirect()->route('employer.jobs.edit', $newJob)
            ->with('success', 'Job duplicated. Edit and publish when ready.');
    }

    public function toggleStatus(Job $job)
    {
        $this->authorizeJob($job);

        $job->status = $job->status === 'active' ? 'paused' : 'active';
        $job->save();

        return back()->with('success', 'Job status updated.');
    }

    private function authorizeJob(Job $job): void
    {
        $employer = auth()->user()->employerProfile;

        abort_if(
            $job->employer_id !== $employer->id,
            403,
            'You do not own this job listing.'
        );
    }
}