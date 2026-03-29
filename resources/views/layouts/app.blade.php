<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }} — TechJobs Pakistan</title>
    <meta name="description" content="{{ $metaDescription ?? 'Find top tech jobs in Pakistan. Laravel, React, Node.js, DevOps and remote roles for Pakistani developers.' }}">

    <!-- Open Graph -->
    <meta property="og:title"       content="{{ $title ?? config('app.name') }}">
    <meta property="og:description" content="{{ $metaDescription ?? '' }}">
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="{{ url()->current() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    {{-- Page-specific head content --}}
    {{ $head ?? '' }}
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar">
        <div class="navbar-inner">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="nav-logo">
                Tech<span>Jobs</span>
            </a>

            {{-- Nav Links --}}
            <div class="nav-links" style="flex:1; margin-left: 40px;">
                <a href="{{ route('home') }}"
                   class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    Browse Jobs
                </a>
                <a href="#"
                   class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}">
                    Companies
                </a>
                <a href="#"
                   class="nav-link {{ request()->routeIs('salaries.*') ? 'active' : '' }}">
                    Salaries
                </a>
            </div>

            {{-- Actions --}}
            <div style="display:flex; align-items:center; gap:10px;">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Sign in</a>
                    <a href="{{ route('register.type') }}" class="btn btn-primary btn-sm">
                        Post a Job
                    </a>
                @endguest

                @auth
                    {{-- Notification bell --}}
                    <button class="btn btn-ghost btn-sm" style="padding: 7px 10px;">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>

                    {{-- Avatar dropdown --}}
                    <div style="position:relative;" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="btn btn-ghost btn-sm"
                                style="gap:8px; padding: 6px 12px 6px 6px;">
                            <div style="width:28px;height:28px;border-radius:50%;background:var(--bg-elevated);
                                        border:1px solid var(--bg-muted);display:flex;align-items:center;
                                        justify-content:center;font-family:var(--font-display);font-size:12px;
                                        font-weight:600;color:var(--accent-bright);">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span style="font-size:13px;color:var(--text-secondary);">
                                {{ Str::words(auth()->user()->name, 1, '') }}
                            </span>
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open"
                             @click.outside="open = false"
                             x-transition
                             style="position:absolute;right:0;top:calc(100% + 8px);width:200px;
                                    background:var(--bg-elevated);border:1px solid var(--bg-muted);
                                    border-radius:var(--radius-lg);padding:6px;z-index:200;
                                    box-shadow:var(--shadow-card);">

                            @if(auth()->user()->hasRole('candidate'))
                                <a href="{{ route('candidate.dashboard') }}" class="dropdown-item">Dashboard</a>
                                <a href="#" class="dropdown-item">My Applications</a>
                                <a href="#" class="dropdown-item">Saved Jobs</a>
                            @endif
                            @if(auth()->user()->hasRole('employer'))
                                <a href="{{ route('employer.dashboard') }}" class="dropdown-item">Dashboard</a>
                                <a href="#" class="dropdown-item">My Job Listings</a>
                            @endif
                            @if(auth()->user()->hasRole('admin'))
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-item">Admin Panel</a>
                            @endif

                            <hr class="divider" style="margin:6px 0;">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;color:var(--danger);">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="container" style="margin-top:16px;">
            <div class="alert alert-success">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="container" style="margin-top:16px;">
            <div class="alert alert-danger">{{ session('error') }}</div>
        </div>
    @endif

    {{-- Main Content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer style="border-top:1px solid var(--bg-muted);margin-top:80px;padding:48px 0;">
        <div class="container">
            <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:48px;margin-bottom:40px;">
                <div>
                    <div class="nav-logo" style="margin-bottom:12px;">Tech<span>Jobs</span></div>
                    <p style="font-size:13px;max-width:280px;line-height:1.7;">
                        Pakistan's job board for developers and tech professionals.
                        Find remote and local opportunities at top companies.
                    </p>
                </div>
                <div>
                    <div style="font-size:12px;font-weight:500;color:var(--text-tertiary);
                                text-transform:uppercase;letter-spacing:0.08em;margin-bottom:16px;">
                        For Candidates
                    </div>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <a href="#" class="nav-link" style="font-size:13px;">Browse Jobs</a>
                        <a href="#" class="nav-link" style="font-size:13px;">Companies</a>
                        <a href="#" class="nav-link" style="font-size:13px;">Salary Guide</a>
                    </div>
                </div>
                <div>
                    <div style="font-size:12px;font-weight:500;color:var(--text-tertiary);
                                text-transform:uppercase;letter-spacing:0.08em;margin-bottom:16px;">
                        For Employers
                    </div>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <a href="#" class="nav-link" style="font-size:13px;">Post a Job</a>
                        <a href="#" class="nav-link" style="font-size:13px;">Featured Listings</a>
                        <a href="#" class="nav-link" style="font-size:13px;">Pricing</a>
                    </div>
                </div>
                <div>
                    <div style="font-size:12px;font-weight:500;color:var(--text-tertiary);
                                text-transform:uppercase;letter-spacing:0.08em;margin-bottom:16px;">
                        Company
                    </div>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <a href="#" class="nav-link" style="font-size:13px;">About</a>
                        <a href="#" class="nav-link" style="font-size:13px;">Contact</a>
                        <a href="#" class="nav-link" style="font-size:13px;">Privacy Policy</a>
                    </div>
                </div>
            </div>
            <div class="divider"></div>
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <p style="font-size:12px;">© {{ date('Y') }} TechJobs Pakistan. All rights reserved.</p>
                <p style="font-size:12px;">Built with Laravel 11 🇵🇰</p>
            </div>
        </div>
    </footer>

    @livewireScripts

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Page-specific scripts --}}
    {{ $scripts ?? '' }}

</body>
</html>