<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        // Base query — will be enhanced with Livewire filters in Phase 3
        $jobs = Job::with(['employer', 'category'])
            ->active()
            ->notExpired()
            ->when(
                $request->search,
                fn($q) =>
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%")
            )
            ->when(
                $request->category,
                fn($q) =>
                $q->where('category_id', $request->category)
            )
            ->when(
                $request->type,
                fn($q) =>
                $q->where('type', $request->type)
            )
            ->when(
                $request->experience,
                fn($q) =>
                $q->where('experience_level', $request->experience)
            )
            ->when(
                $request->remote,
                fn($q) =>
                $q->where('is_remote', true)
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('public.jobs.index', compact('jobs', 'categories'));
    }

    public function show(Job $job)
    {
        abort_if(
            ! $job->is_approved || $job->status !== 'active',
            404
        );

        $job->increment('views_count');
        $job->load(['employer', 'category', 'screeningQuestions']);

        $similarJobs = Job::with(['employer', 'category'])
            ->active()
            ->notExpired()
            ->where('category_id', $job->category_id)
            ->where('id', '!=', $job->id)
            ->take(4)
            ->get();

        $hasApplied = false;

        if (auth()->check() && auth()->user()->hasRole('candidate')) {
            $candidate = auth()->user()->candidateProfile;
            $hasApplied = \App\Models\Application::where('job_id', $job->id)
                ->where('candidate_id', $candidate->id)
                ->exists();
        }

        return view('public.jobs.show', compact('job', 'similarJobs', 'hasApplied'));
    }
}
