<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\EmployerProfile;
use App\Models\Job;

class HomeController extends Controller
{
    public function index()
    {
        // Featured jobs — approved, active, featured
        $featuredJobs = Job::with(['employer', 'category'])
            ->active()
            ->featured()
            ->notExpired()
            ->latest()
            ->take(6)
            ->get();

        // Recent jobs — approved, active, not expired
        $recentJobs = Job::with(['employer', 'category'])
            ->active()
            ->notExpired()
            ->latest()
            ->take(8)
            ->get();

        // Categories with job counts
        $categories = Category::withCount([
            'jobs' => fn($q) => $q->where('status', 'active')->where('is_approved', true)
        ])
        ->orderByDesc('jobs_count')
        ->take(12)
        ->get();

        // Top hiring companies
        $topCompanies = EmployerProfile::withCount([
            'jobs' => fn($q) => $q->where('status', 'active')->where('is_approved', true)
        ])
        ->where('is_verified', true)
        ->orderByDesc('jobs_count')
        ->take(6)
        ->get();

        // Platform stats
        $stats = [
            'jobs'       => Job::active()->notExpired()->count(),
            'companies'  => EmployerProfile::whereHas('jobs', fn($q) => $q->where('status', 'active'))->count(),
            'candidates' => \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'candidate'))->count(),
        ];

        return view('public.home', compact(
            'featuredJobs',
            'recentJobs',
            'categories',
            'topCompanies',
            'stats'
        ));
    }
}