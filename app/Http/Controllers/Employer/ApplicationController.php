<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use App\Models\Job;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    // ── All applications for a job ─────────────────────────────────
    public function index(Job $job)
    {
        $this->authorizeJob($job);

        $job->load('category');

        $applications = Application::with(['candidate.user'])
            ->where('job_id', $job->id)
            ->when(request('status'), fn($q) => $q->where('status', request('status')))
            ->latest('applied_at')
            ->paginate(15);

        return view('employer.applications.index', compact('job', 'applications'));
    }

    // ── Single application detail ──────────────────────────────────
    public function show(Job $job, Application $application)
    {
        $this->authorizeJob($job);
        $this->authorizeApplication($job, $application);

        $application->load([
            'candidate.user',
            'candidate.skills.skill',
            'candidate.experiences',
            'candidate.educations',
            'screeningAnswers.question',
            'statusHistory.changedBy',
        ]);

        return view('employer.applications.show', compact('job', 'application'));
    }

    // ── Update application status ──────────────────────────────────
    public function updateStatus(Request $request, Job $job, Application $application)
    {
        $this->authorizeJob($job);
        $this->authorizeApplication($job, $application);

        $request->validate([
            'status' => ['required', 'in:reviewing,shortlisted,interview,offered,rejected'],
            'note'   => ['nullable', 'string', 'max:500'],
        ]);

        $application->update(['status' => $request->status]);

        ApplicationStatusHistory::create([
            'application_id' => $application->id,
            'status'         => $request->status,
            'changed_by'     => auth()->id(),
            'note'           => $request->note,
        ]);

        // Notify the candidate
        $application->candidate->user->notify(
            new \App\Notifications\ApplicationStatusChanged($application)
        );

        return back()->with('success', 'Application status updated to ' . ucfirst($request->status) . '.');
    }

    // ── Add private note ───────────────────────────────────────────
    public function addNote(Request $request, Job $job, Application $application)
    {
        $this->authorizeJob($job);
        $this->authorizeApplication($job, $application);

        $request->validate([
            'note' => ['required', 'string', 'max:1000'],
        ]);

        // Store note in status history as internal
        ApplicationStatusHistory::create([
            'application_id' => $application->id,
            'status'         => $application->status,
            'changed_by'     => auth()->id(),
            'note'           => '📝 ' . $request->note,
        ]);

        return back()->with('success', 'Note added.');
    }

    // ── Private helpers ────────────────────────────────────────────
    private function authorizeJob(Job $job): void
    {
        abort_if(
            $job->employer_id !== auth()->user()->employerProfile->id,
            403
        );
    }

    private function authorizeApplication(Job $job, Application $application): void
    {
        abort_if($application->job_id !== $job->id, 403);
    }
}
