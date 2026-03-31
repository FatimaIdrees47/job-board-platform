<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Candidate\StoreApplicationRequest;
use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use App\Models\Job;
use App\Models\SavedJob;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    // ── Show application form ──────────────────────────────────────
    public function create(Job $job)
    {
        abort_if(! $job->is_approved || $job->status !== 'active', 404);

        $candidate = auth()->user()->candidateProfile;

        // Check if already applied
        $existingApplication = Application::where('job_id', $job->id)
            ->where('candidate_id', $candidate->id)
            ->first();

        if ($existingApplication) {
            return redirect()->route('candidate.applications.index')
                ->with('info', 'You have already applied to this job.');
        }

        $job->load('screeningQuestions', 'employer');
        $cvFiles = $candidate->getMedia('cv');

        return view('candidate.applications.create', compact('job', 'candidate', 'cvFiles'));
    }

    // ── Store application ──────────────────────────────────────────
    public function store(StoreApplicationRequest $request, Job $job)
    {
        abort_if(! $job->is_approved || $job->status !== 'active', 404);

        $candidate = auth()->user()->candidateProfile;

        // Prevent duplicate applications
        $exists = Application::where('job_id', $job->id)
            ->where('candidate_id', $candidate->id)
            ->exists();

        if ($exists) {
            return redirect()->route('candidate.applications.index')
                ->with('info', 'You have already applied to this job.');
        }

        $application = Application::create([
            'job_id'       => $job->id,
            'candidate_id' => $candidate->id,
            'cover_letter' => $request->cover_letter,
            'status'       => 'applied',
            'applied_at'   => now(),
        ]);

        // Save screening answers
        if ($request->filled('answers')) {
            foreach ($request->answers as $questionId => $answer) {
                if (! empty($answer)) {
                    $application->screeningAnswers()->create([
                        'question_id' => $questionId,
                        'answer'      => $answer,
                    ]);
                }
            }
        }

        // Record initial status history
        ApplicationStatusHistory::create([
            'application_id' => $application->id,
            'status'         => 'applied',
            'changed_by'     => auth()->id(),
        ]);

        // Increment application count on job
        $job->increment('applications_count');

        // Notify the employer
        $application->job->employer->user->notify(
            new \App\Notifications\NewApplicationReceived($application)
        );

        return redirect()->route('candidate.applications.index')
            ->with('success', 'Application submitted successfully! Good luck! 🎉');
    }

    // ── Candidate's applications list ──────────────────────────────
    public function index()
    {
        $candidate = auth()->user()->candidateProfile;

        $applications = Application::with(['job.employer', 'job.category'])
            ->where('candidate_id', $candidate->id)
            ->latest('applied_at')
            ->paginate(10);

        return view('candidate.applications.index', compact('applications'));
    }

    // ── Single application detail ──────────────────────────────────
    public function show(Application $application)
    {
        $this->authorizeApplication($application);
        $application->load(['job.employer', 'screeningAnswers.question', 'statusHistory.changedBy']);
        return view('candidate.applications.show', compact('application'));
    }

    // ── Withdraw application ───────────────────────────────────────
    public function withdraw(Application $application)
    {
        $this->authorizeApplication($application);

        abort_if(! $application->canBeWithdrawn(), 403, 'This application cannot be withdrawn.');

        $application->update([
            'status'       => 'withdrawn',
            'withdrawn_at' => now(),
        ]);

        ApplicationStatusHistory::create([
            'application_id' => $application->id,
            'status'         => 'withdrawn',
            'changed_by'     => auth()->id(),
            'note'           => 'Withdrawn by candidate.',
        ]);

        $application->job->decrement('applications_count');

        return back()->with('success', 'Application withdrawn successfully.');
    }

    // ── Save / unsave job ──────────────────────────────────────────
    public function toggleSave(Job $job)
    {
        $candidate = auth()->user()->candidateProfile;

        $saved = SavedJob::where('candidate_id', $candidate->id)
            ->where('job_id', $job->id)
            ->first();

        if ($saved) {
            $saved->delete();
            return back()->with('success', 'Job removed from saved list.');
        }

        SavedJob::create([
            'candidate_id' => $candidate->id,
            'job_id'       => $job->id,
        ]);

        return back()->with('success', 'Job saved successfully.');
    }

    // ── Saved jobs list ────────────────────────────────────────────
    public function savedJobs()
    {
        $candidate = auth()->user()->candidateProfile;

        $savedJobs = SavedJob::with(['job.employer', 'job.category'])
            ->where('candidate_id', $candidate->id)
            ->latest()
            ->paginate(10);

        return view('candidate.saved-jobs', compact('savedJobs'));
    }

    // ── Private helper ─────────────────────────────────────────────
    private function authorizeApplication(Application $application): void
    {
        $candidate = auth()->user()->candidateProfile;
        abort_if($application->candidate_id !== $candidate->id, 403);
    }
}
