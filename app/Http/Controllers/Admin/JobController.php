<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $jobs = Job::with('employer')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->approval, function($q) use ($request) {
                if ($request->approval === 'pending') {
                    $q->where('is_approved', false)->where('status', 'active');
                } elseif ($request->approval === 'approved') {
                    $q->where('is_approved', true);
                }
            })
            ->when($request->search, fn($q) =>
                $q->where('title', 'like', "%{$request->search}%")
            )
            ->latest()
            ->paginate(20);

        return view('admin.jobs.index', compact('jobs'));
    }

    public function approve(Job $job)
    {
        $job->update(['is_approved' => true]);
        return back()->with('success', "Job \"{$job->title}\" approved and now live.");
    }

    public function reject(Job $job)
    {
        $job->update(['status' => 'closed', 'is_approved' => false]);
        return back()->with('success', "Job \"{$job->title}\" rejected.");
    }

    public function destroy(Job $job)
    {
        $job->delete();
        return back()->with('success', 'Job listing deleted.');
    }
}