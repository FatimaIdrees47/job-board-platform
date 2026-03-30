<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;

class DashboardController extends Controller
{
    public function index()
    {
        $employer = auth()->user()->employerProfile;

        $stats = [
            'total_jobs'         => Job::where('employer_id', $employer->id)->count(),
            'active_jobs'        => Job::where('employer_id', $employer->id)->where('status', 'active')->count(),
            'total_applications' => Application::whereHas('job', fn($q) => $q->where('employer_id', $employer->id))->count(),
            'new_applications'   => Application::whereHas('job', fn($q) => $q->where('employer_id', $employer->id))
                                        ->where('status', 'applied')
                                        ->count(),
        ];

        $recentJobs = Job::where('employer_id', $employer->id)
            ->latest()
            ->take(5)
            ->get();

        $recentApplications = Application::with(['job', 'candidate.user'])
            ->whereHas('job', fn($q) => $q->where('employer_id', $employer->id))
            ->latest('applied_at')
            ->take(5)
            ->get();

        return view('employer.dashboard', compact('stats', 'recentJobs', 'recentApplications', 'employer'));
    }
}