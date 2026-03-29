<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Public home (placeholder for now)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Role-protected dashboard placeholders
Route::middleware(['auth', 'verified'])->group(function () {

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn() => 'Admin Dashboard — coming soon')->name('dashboard');
    });

    Route::middleware('role:employer')->prefix('employer')->name('employer.')->group(function () {
        Route::get('/dashboard', fn() => 'Employer Dashboard — coming soon')->name('dashboard');
    });

    Route::middleware('role:candidate')->prefix('candidate')->name('candidate.')->group(function () {
        Route::get('/dashboard', fn() => 'Candidate Dashboard — coming soon')->name('dashboard');
    });

});