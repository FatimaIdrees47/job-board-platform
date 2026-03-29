@extends('layouts.auth')
@section('title', 'Verify your email')
@section('content')

    <div style="text-align:center;margin-bottom:32px;">
        <div style="width:64px;height:64px;border-radius:var(--radius-lg);
                    background:rgba(109,40,217,0.15);border:1px solid rgba(167,139,250,0.2);
                    display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" style="color:var(--accent-bright);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h1 style="font-size:26px;margin-bottom:8px;">Check your email</h1>
        <p style="font-size:14px;max-width:320px;margin:0 auto;line-height:1.7;">
            We sent a verification link to
            <strong style="color:var(--text-primary);">{{ auth()->user()->email }}</strong>.
            Click the link to activate your account.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="padding:28px;text-align:center;">

        <p style="font-size:13px;margin-bottom:20px;">
            Didn't receive the email? Check your spam folder or request a new link.
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary"
                    style="width:100%;justify-content:center;">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" style="margin-top:12px;">
            @csrf
            <button type="submit" class="btn btn-ghost btn-sm"
                    style="width:100%;justify-content:center;">
                Sign out and use a different account
            </button>
        </form>

    </div>

@endsection