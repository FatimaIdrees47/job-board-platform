<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\Job;

Schedule::call(function () {
    $expired = Job::where('status', 'active')
        ->whereNotNull('deadline')
        ->where('deadline', '<', now()->startOfDay())
        ->get();

    foreach ($expired as $job) {
        $job->update(['status' => 'expired']);
    }

    if ($expired->count() > 0) {
        \Illuminate\Support\Facades\Log::info("Auto-expired {$expired->count()} job listings.");
    }
})->daily()->name('jobs:auto-expire');