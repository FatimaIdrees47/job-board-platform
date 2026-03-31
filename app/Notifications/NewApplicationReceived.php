<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplicationReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Application $application
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $job       = $this->application->job;
        $candidate = $this->application->candidate->user;

        return (new MailMessage)
            ->subject("New Application: {$job->title}")
            ->greeting("Hi {$notifiable->name},")
            ->line("You have received a new application for **{$job->title}**.")
            ->line("**Applicant:** {$candidate->name}")
            ->line("**Applied:** {$this->application->applied_at->format('M d, Y \a\t h:i A')}")
            ->action('View Application', route('employer.jobs.applications.show', [$job, $this->application]))
            ->salutation('The TechJobs Pakistan Team');
    }
}