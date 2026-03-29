@extends('layouts.auth')
@section('title', 'Create employer account')
@section('content')

    <div style="text-align:center;margin-bottom:32px;">
        <span class="badge badge-cyan" style="margin-bottom:12px;">Employer</span>
        <h1 style="font-size:26px;margin-bottom:8px;">Start hiring today</h1>
        <p style="font-size:14px;">Post jobs and find Pakistan's top tech talent</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:20px;">
            <ul style="margin:0;padding-left:16px;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="padding:32px;">
        <form method="POST" action="{{ route('register.employer.store') }}">
            @csrf

            <div style="margin-bottom:18px;">
                <label class="form-label">Your Full Name</label>
                <input type="text" name="name"
                       class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                       value="{{ old('name') }}" placeholder="Ahmed Khan" required autofocus>
                @error('name')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom:18px;">
                <label class="form-label">Company Name</label>
                <input type="text" name="company_name"
                       class="form-input {{ $errors->has('company_name') ? 'error' : '' }}"
                       value="{{ old('company_name') }}" placeholder="Acme Technologies" required>
                @error('company_name')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom:18px;">
                <label class="form-label">Work Email</label>
                <input type="email" name="email"
                       class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                       value="{{ old('email') }}" placeholder="ahmed@acmetech.com" required>
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
                Create Employer Account
            </button>
        </form>
    </div>

    <div style="text-align:center;margin-top:20px;font-size:13px;color:var(--text-secondary);">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
        · <a href="{{ route('register.candidate') }}">Join as Candidate</a>
    </div>

@endsection