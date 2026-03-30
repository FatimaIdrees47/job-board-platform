<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Employer\JobController;
use App\Http\Controllers\Employer\DashboardController as EmployerDashboardController;
use App\Http\Controllers\Employer\ApplicationController as EmployerApplicationController;
use App\Http\Controllers\Candidate\ApplicationController;
use App\Http\Controllers\Candidate\DashboardController as CandidateDashboardController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\JobController as PublicJobController;

// ── Public ────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [PublicJobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job:slug}', [PublicJobController::class, 'show'])->name('jobs.show');

// ── Guest only ────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showTypeSelector'])->name('register.type');
    Route::get('/register/candidate', [RegisterController::class, 'showCandidateForm'])->name('register.candidate');
    Route::post('/register/candidate', [RegisterController::class, 'storeCandidate'])->name('register.candidate.store');
    Route::get('/register/employer', [RegisterController::class, 'showEmployerForm'])->name('register.employer');
    Route::post('/register/employer', [RegisterController::class, 'storeEmployer'])->name('register.employer.store');
});

// ── Email Verification ────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        $user = $request->user();

        $redirect = match(true) {
            $user->hasRole('admin')     => route('admin.dashboard'),
            $user->hasRole('employer')  => route('employer.dashboard'),
            $user->hasRole('candidate') => route('candidate.dashboard'),
            default                     => route('home'),
        };

        return redirect($redirect)->with('success', 'Email verified successfully!');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Verification link sent to your email.');
    })->middleware('throttle:6,1')->name('verification.send');
});

// ── Authenticated ─────────────────────────────────────────────────────────
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── Admin routes ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', fn() => 'Admin Dashboard — coming soon')->name('dashboard');
    });

// ── Employer routes ───────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:employer'])
    ->prefix('employer')
    ->name('employer.')
    ->group(function () {
        Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('dashboard');
        Route::resource('jobs', JobController::class);
        Route::post('jobs/{job}/duplicate', [JobController::class, 'duplicate'])->name('jobs.duplicate');
        Route::patch('jobs/{job}/toggle-status', [JobController::class, 'toggleStatus'])->name('jobs.toggle-status');

        // Employer application management
        Route::get('jobs/{job}/applications', [EmployerApplicationController::class, 'index'])->name('jobs.applications.index');
        Route::get('jobs/{job}/applications/{application}', [EmployerApplicationController::class, 'show'])->name('jobs.applications.show');
        Route::patch('jobs/{job}/applications/{application}/status', [EmployerApplicationController::class, 'updateStatus'])->name('jobs.applications.update-status');
        Route::post('jobs/{job}/applications/{application}/note', [EmployerApplicationController::class, 'addNote'])->name('jobs.applications.add-note');
    });

// ── Candidate routes ──────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:candidate'])
    ->prefix('candidate')
    ->name('candidate.')
    ->group(function () {
        Route::get('/dashboard', [CandidateDashboardController::class, 'index'])->name('dashboard');

        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::patch('/applications/{application}/withdraw', [ApplicationController::class, 'withdraw'])->name('applications.withdraw');

        Route::get('/saved-jobs', [ApplicationController::class, 'savedJobs'])->name('saved-jobs');
        Route::post('/saved-jobs/{job}/toggle', [ApplicationController::class, 'toggleSave'])->name('saved-jobs.toggle');
    });

// ── Apply routes ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/jobs/{job:slug}/apply', [ApplicationController::class, 'create'])->name('jobs.apply');
    Route::post('/jobs/{job:slug}/apply', [ApplicationController::class, 'store'])->name('jobs.apply.store');
});