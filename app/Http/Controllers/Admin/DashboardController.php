<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\EmployerProfile;
use App\Models\Job;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_jobs'            => Job::count(),
            'active_jobs'           => Job::where('status', 'active')->count(),
            'pending_approval'      => Job::where('status', 'active')->where('is_approved', false)->count(),
            'total_candidates'      => User::whereHas('roles', fn($q) => $q->where('name', 'candidate'))->count(),
            'total_employers'       => User::whereHas('roles', fn($q) => $q->where('name', 'employer'))->count(),
            'total_applications'    => Application::count(),
            'verified_employers'    => EmployerProfile::where('is_verified', true)->count(),
            'total_messages'        => Message::count(),
            // Trends (last 7 days vs previous 7 days)
            'new_users_this_week'   => User::where('created_at', '>=', now()->subDays(7))->count(),
            'new_jobs_this_week'    => Job::where('created_at', '>=', now()->subDays(7))->count(),
            'new_apps_this_week'    => Application::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        // Signups over last 30 days
        $signupData = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->keyBy('date');

        // Applications over last 30 days
        $applicationData = Application::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->keyBy('date');

        // Build chart labels (last 30 days)
        $labels = [];
        $signups = [];
        $applications = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('M d');
            $labels[] = $label;
            $signups[] = $signupData[$date]->count ?? 0;
            $applications[] = $applicationData[$date]->count ?? 0;
        }

        // Recent activity feed
        $recentActivity = collect();

        // Recent jobs
        Job::with('employer')->latest()->take(5)->get()->each(function($job) use (&$recentActivity) {
            $recentActivity->push([
                'type'    => 'job',
                'message' => "New job posted: {$job->title} by {$job->employer->company_name}",
                'time'    => $job->created_at,
                'color'   => 'var(--accent-bright)',
            ]);
        });

        // Recent signups
        User::latest()->take(5)->get()->each(function($user) use (&$recentActivity) {
            $role = $user->getRoleNames()->first() ?? 'user';
            $recentActivity->push([
                'type'    => 'user',
                'message' => "New {$role} registered: {$user->name}",
                'time'    => $user->created_at,
                'color'   => 'var(--spark)',
            ]);
        });

        // Recent applications
        Application::with(['candidate.user', 'job'])->latest()->take(5)->get()->each(function($app) use (&$recentActivity) {
            $recentActivity->push([
                'type'    => 'application',
                'message' => "{$app->candidate->user->name} applied to {$app->job->title}",
                'time'    => $app->created_at,
                'color'   => 'var(--success)',
            ]);
        });

        $recentActivity = $recentActivity->sortByDesc('time')->take(10)->values();

        $pendingJobs = Job::with('employer')
            ->where('status', 'active')
            ->where('is_approved', false)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'pendingJobs', 'recentActivity',
            'labels', 'signups', 'applications'
        ));
    }
}