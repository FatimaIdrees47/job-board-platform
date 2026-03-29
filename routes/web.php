<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Employer\JobController;

// ── Public ────────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match(true) {
            $user->hasRole('admin')     => redirect()->route('admin.dashboard'),
            $user->hasRole('employer')  => redirect()->route('employer.dashboard'),
            $user->hasRole('candidate') => redirect()->route('candidate.dashboard'),
            default                     => view('welcome'),
        };
    }
    return view('welcome');
})->name('home');

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
        Route::get('/dashboard', fn() => 'Employer Dashboard — coming soon')->name('dashboard');
        Route::resource('jobs', JobController::class);
        Route::post('jobs/{job}/duplicate', [JobController::class, 'duplicate'])->name('jobs.duplicate');
        Route::patch('jobs/{job}/toggle-status', [JobController::class, 'toggleStatus'])->name('jobs.toggle-status');
    });

// ── Candidate routes ──────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:candidate'])
    ->prefix('candidate')
    ->name('candidate.')
    ->group(function () {
        Route::get('/dashboard', fn() => 'Candidate Dashboard — coming soon')->name('dashboard');
    });