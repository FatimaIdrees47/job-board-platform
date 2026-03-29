@extends('layouts.auth')

@section('title', 'Sign in')

@section('content')

    <div style="text-align:center;margin-bottom:32px;">
        <h1 style="font-size:28px;margin-bottom:8px;">Welcome back</h1>
        <p style="font-size:14px;">Sign in to your TechJobs account</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:20px;">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card" style="padding:32px;">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div style="margin-bottom:20px;">
                <label class="form-label">Email Address</label>
                <input type="email"
                       name="email"
                       class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                       value="{{ old('email') }}"
                       placeholder="you@example.com"
                       autofocus required>
            </div>

            <div style="margin-bottom:10px;">
                <label class="form-label">Password</label>
                <input type="password"
                       name="password"
                       class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                       placeholder="••••••••"
                       required>
            </div>

            <div style="display:flex;justify-content:space-between;
                        align-items:center;margin-bottom:24px;">
                <label style="display:flex;align-items:center;gap:8px;
                              font-size:13px;color:var(--text-secondary);cursor:pointer;">
                    <input type="checkbox" name="remember"
                           style="accent-color:var(--accent-bright);">
                    Remember me
                </label>
                <a href="#" style="font-size:13px;">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-primary"
                    style="width:100%;justify-content:center;">
                Sign in
            </button>
        </form>
    </div>

    <div style="margin-top:24px;display:flex;flex-direction:column;gap:10px;">
        <div style="text-align:center;font-size:13px;color:var(--text-secondary);">
            Don't have an account?
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <a href="{{ route('register.candidate') }}"
               class="btn btn-secondary"
               style="justify-content:center;font-size:13px;">
                Join as Candidate
            </a>
            <a href="{{ route('register.employer') }}"
               class="btn btn-ghost"
               style="justify-content:center;font-size:13px;">
                Join as Employer
            </a>
        </div>
    </div>

@endsection