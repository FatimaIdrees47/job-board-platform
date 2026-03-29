@extends('layouts.auth')
@section('title', 'Create candidate account')
@section('content')

    <div style="text-align:center;margin-bottom:32px;">
        <span class="badge badge-purple" style="margin-bottom:12px;">Candidate</span>
        <h1 style="font-size:26px;margin-bottom:8px;">Create your profile</h1>
        <p style="font-size:14px;">Find your next tech role in Pakistan</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:20px;">
            <ul style="margin:0;padding-left:16px;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="padding:32px;">
        <form method="POST" action="{{ route('register.candidate.store') }}">
            @csrf

            <div style="margin-bottom:18px;">
                <label class="form-label">Full Name</label>
                <input type="text" name="name"
                       class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                       value="{{ old('name') }}" placeholder="Fatima Idrees" required autofocus>
                @error('name')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom:18px;">
                <label class="form-label">Email Address</label>
                <input type="email" name="email"
                       class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                       value="{{ old('email') }}" placeholder="you@example.com" required>
                @error('email')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom:18px;">
                <label class="form-label">Password</label>
                <input type="password" name="password"
                       class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                       placeholder="Min. 8 characters" required>
                @error('password')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom:24px;">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation"
                       class="form-input" placeholder="Repeat your password" required>
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:flex;align-items:flex-start;gap:10px;
                              font-size:13px;color:var(--text-secondary);cursor:pointer;">
                    <input type="checkbox" name="terms" required
                           style="accent-color:var(--accent-bright);margin-top:2px;flex-shrink:0;">
                    <span>I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary"
                    style="width:100%;justify-content:center;">
                Create Candidate Account
            </button>
        </form>
    </div>

    <div style="text-align:center;margin-top:20px;font-size:13px;color:var(--text-secondary);">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
        · <a href="{{ route('register.employer') }}">Join as Employer</a>
    </div>

@endsection