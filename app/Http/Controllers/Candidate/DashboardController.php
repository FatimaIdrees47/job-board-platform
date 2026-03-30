<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\SavedJob;

class DashboardController extends Controller
{
    public function index()
    {
        $candidate = auth()->user()->candidateProfile;

        $stats = [
            'total_applications' => Application::where('candidate_id', $candidate->id)->count(),
            'under_review'       => Application::where('candidate_id', $candidate->id)->where('status', 'reviewing')->count(),
            'shortlisted'        => Application::where('candidate_id', $candidate->id)->where('status', 'shortlisted')->count(),
            'saved_jobs'         => SavedJob::where('candidate_id', $candidate->id)->count(),
        ];

        $recentApplications = Application::with(['job.employer', 'job.category'])
            ->where('candidate_id', $candidate->id)
            ->latest('applied_at')
            ->take(5)
            ->get();

        $recommendedJobs = Job::with(['employer', 'category'])
            ->active()
            ->notExpired()
            ->latest()
            ->take(4)
            ->get();

        return view('candidate.dashboard', compact('stats', 'recentApplications', 'recommendedJobs', 'candidate'));
    }
}