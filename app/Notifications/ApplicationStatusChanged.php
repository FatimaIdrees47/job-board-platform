<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusChanged extends Notification implements ShouldQueue
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
        $job      = $this->application->job;
        $employer = $job->employer;
        $status   = $this->application->status_label;

        $message = match($this->application->status) {
            'reviewing'   => "Your application is being reviewed by the hiring team.",
            'shortlisted' => "Great news! You've been shortlisted for this position.",
            'interview'   => "Congratulations! The employer wants to schedule an interview with you.",
            'offered'     => "🎉 You've received a job offer! Log in to view the details.",
            'rejected'    => "After careful consideration, the employer has decided to move forward with other candidates.",
            default       => "Your application status has been updated.",
        };

        return (new MailMessage)
            ->subject("Application Update: {$job->title} at {$employer->company_name}")
            ->greeting("Hi {$notifiable->name},")
            ->line("Your application for **{$job->title}** at **{$employer->company_name}** has been updated.")
            ->line("**New Status: {$status}**")
            ->line($message)
            ->action('View Application', route('candidate.applications.show', $this->application))
            ->line('Good luck with your job search!')
            ->salutation('The TechJobs Pakistan Team');
    }
}