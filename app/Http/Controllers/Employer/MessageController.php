<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $employer = auth()->user()->employerProfile;

        $applications = Application::with([
            'job',
            'candidate.user',
            'messages' => fn($q) => $q->latest()->limit(1),
        ])
        ->whereHas('job', fn($q) => $q->where('employer_id', $employer->id))
        ->whereHas('messages')
        ->latest()
        ->get();

        $totalUnread = Message::where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->count();

        return view('employer.messages.index', compact('applications', 'totalUnread'));
    }

    public function show(Application $application)
    {
        $employer = auth()->user()->employerProfile;
        abort_if($application->job->employer_id !== $employer->id, 403);

        Message::where('application_id', $application->id)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $application->messages()->with('sender')->get();
        $application->load('job', 'candidate.user');

        return view('employer.messages.show', compact('application', 'messages'));
    }

    public function send(Request $request, Application $application)
    {
        $employer = auth()->user()->employerProfile;
        abort_if($application->job->employer_id !== $employer->id, 403);

        $request->validate(['body' => ['required', 'string', 'max:2000']]);

        $candidateUserId = $application->candidate->user_id;

        Message::create([
            'application_id' => $application->id,
            'sender_id'      => auth()->id(),
            'receiver_id'    => $candidateUserId,
            'body'           => $request->body,
        ]);

        return back()->with('success', 'Message sent.');
    }
}