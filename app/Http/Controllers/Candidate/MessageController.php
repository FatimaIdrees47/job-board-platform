<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $candidate = auth()->user()->candidateProfile;

        $applications = Application::with([
            'job.employer',
            'messages' => fn($q) => $q->latest()->limit(1),
        ])
        ->where('candidate_id', $candidate->id)
        ->whereHas('messages')
        ->latest()
        ->get();

        $totalUnread = Message::where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->count();

        return view('candidate.messages.index', compact('applications', 'totalUnread'));
    }

    public function show(Application $application)
    {
        abort_if($application->candidate_id !== auth()->user()->candidateProfile->id, 403);

        // Mark messages as read
        Message::where('application_id', $application->id)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $application->messages()->with('sender')->get();
        $application->load('job.employer');

        return view('candidate.messages.show', compact('application', 'messages'));
    }

    public function send(Request $request, Application $application)
    {
        abort_if($application->candidate_id !== auth()->user()->candidateProfile->id, 403);

        $request->validate(['body' => ['required', 'string', 'max:2000']]);

        $employerUserId = $application->job->employer->user_id;

        Message::create([
            'application_id' => $application->id,
            'sender_id'      => auth()->id(),
            'receiver_id'    => $employerUserId,
            'body'           => $request->body,
        ]);

        return back()->with('success', 'Message sent.');
    }
}