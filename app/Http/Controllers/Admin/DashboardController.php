<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\EmployerProfile;
use App\Models\Job;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_jobs'         => Job::count(),
            'active_jobs'        => Job::where('status', 'active')->count(),
            'pending_approval'   => Job::where('status', 'active')->where('is_approved', false)->count(),
            'total_candidates'   => User::whereHas('roles', fn($q) => $q->where('name', 'candidate'))->count(),
            'total_employers'    => User::whereHas('roles', fn($q) => $q->where('name', 'employer'))->count(),
            'total_applications' => Application::count(),
        ];

        $pendingJobs = Job::with('employer')
            ->where('status', 'active')
            ->where('is_approved', false)
            ->latest()
            ->take(5)
            ->get();

        $recentJobs = Job::with('employer')
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::whereHas('roles', fn($q) =>
            $q->whereIn('name', ['candidate', 'employer'])
        )
        ->latest()
        ->take(5)
        ->get();

        return view('admin.dashboard', compact('stats', 'pendingJobs', 'recentJobs', 'recentUsers'));
    }
}