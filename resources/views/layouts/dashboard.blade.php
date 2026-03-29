<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} — TechJobs Pakistan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body style="min-height:100vh;display:flex;background:var(--bg-base);">

    {{-- Sidebar --}}
    <aside style="width:240px;flex-shrink:0;background:var(--bg-surface);
                  border-right:1px solid var(--bg-muted);
                  display:flex;flex-direction:column;
                  position:fixed;top:0;left:0;bottom:0;z-index:50;">

        {{-- Logo --}}
        <div style="padding:20px 20px 16px;border-bottom:1px solid var(--bg-muted);">
            <a href="{{ route('home') }}" class="nav-logo" style="font-size:18px;">
                Tech<span>Jobs</span>
            </a>
        </div>

        {{-- Nav --}}
        <nav style="flex:1;padding:16px 10px;overflow-y:auto;">
            {{ $sidebar }}
        </nav>

        {{-- User info at bottom --}}
        <div style="padding:14px;border-top:1px solid var(--bg-muted);">
            <div style="display:flex;align-items:center;gap:10px;padding:10px;
                        border-radius:var(--radius-md);background:var(--bg-elevated);">
                <div style="width:32px;height:32px;border-radius:50%;
                            background:var(--bg-subtle);border:1px solid var(--bg-muted);
                            display:flex;align-items:center;justify-content:center;
                            font-family:var(--font-display);font-size:13px;font-weight:600;
                            color:var(--accent-bright);flex-shrink:0;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13px;font-weight:500;color:var(--text-primary);
                                white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ auth()->user()->name }}
                    </div>
                    <div style="font-size:11px;color:var(--text-tertiary);
                                white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ auth()->user()->email }}
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin-top:6px;">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm"
                        style="width:100%;justify-content:center;margin-top:4px;">
                    Sign out
                </button>
            </form>
        </div>
    </aside>

    {{-- Main area --}}
    <div style="flex:1;margin-left:240px;display:flex;flex-direction:column;min-height:100vh;">

        {{-- Topbar --}}
        <header style="height:60px;background:var(--bg-surface);
                       border-bottom:1px solid var(--bg-muted);
                       display:flex;align-items:center;justify-content:space-between;
                       padding:0 28px;position:sticky;top:0;z-index:40;">
            <div style="font-family:var(--font-display);font-size:18px;font-weight:600;">
                {{ $title ?? 'Dashboard' }}
            </div>
            <div style="display:flex;align-items:center;gap:12px;">
                {{-- Notification bell --}}
                <button class="btn btn-ghost btn-sm" style="padding:7px 10px;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </button>
                {{-- Role badge --}}
                @if(auth()->user()->hasRole('admin'))
                    <span class="badge badge-danger">Admin</span>
                @elseif(auth()->user()->hasRole('employer'))
                    <span class="badge badge-cyan">Employer</span>
                @else
                    <span class="badge badge-purple">Candidate</span>
                @endif
            </div>
        </header>

        {{-- Page content --}}
        <main style="flex:1;padding:32px 28px;">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:24px;">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger" style="margin-bottom:24px;">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    @livewireScripts
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{ $scripts ?? '' }}
</body>
</html>