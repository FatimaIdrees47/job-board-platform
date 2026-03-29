@extends('layouts.auth')
@section('title', 'Create an account')
@section('content')

    <div style="text-align:center;margin-bottom:32px;">
        <h1 style="font-size:28px;margin-bottom:8px;">Join TechJobs Pakistan</h1>
        <p style="font-size:14px;">How do you want to use TechJobs?</p>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

        <a href="{{ route('register.candidate') }}" style="text-decoration:none;display:block;">
            <div class="card" style="padding:28px 20px;text-align:center;cursor:pointer;">
                <div style="width:56px;height:56px;border-radius:var(--radius-lg);
                            background:rgba(109,40,217,0.15);border:1px solid rgba(167,139,250,0.2);
                            display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" style="color:var(--accent-bright);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div style="font-family:var(--font-display);font-size:16px;font-weight:600;margin-bottom:8px;">
                    I'm a Candidate
                </div>
                <p style="font-size:12px;line-height:1.6;">
                    Looking for a job, internship, or remote opportunity
                </p>
            </div>
        </a>

        <a href="{{ route('register.employer') }}" style="text-decoration:none;display:block;">
            <div class="card" style="padding:28px 20px;text-align:center;cursor:pointer;">
                <div style="width:56px;height:56px;border-radius:var(--radius-lg);
                            background:rgba(34,211,238,0.1);border:1px solid rgba(34,211,238,0.2);
                            display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" style="color:var(--spark);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div style="font-family:var(--font-display);font-size:16px;font-weight:600;margin-bottom:8px;">
                    I'm an Employer
                </div>
                <p style="font-size:12px;line-height:1.6;">
                    Posting jobs and hiring Pakistani tech talent
                </p>
            </div>
        </a>

    </div>

    <div style="text-align:center;margin-top:24px;font-size:13px;color:var(--text-secondary);">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </div>

@endsection