<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sign in') — TechJobs Pakistan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles()
</head>
<body style="min-height:100vh;display:flex;flex-direction:column;justify-content:center;
             background:var(--bg-base);position:relative;overflow-x:hidden;">

    {{-- Background glow effects --}}
    <div style="position:fixed;top:-120px;right:-120px;width:500px;height:500px;
                background:radial-gradient(circle,rgba(109,40,217,0.12) 0%,transparent 70%);
                pointer-events:none;z-index:0;"></div>
    <div style="position:fixed;bottom:-100px;left:-80px;width:400px;height:400px;
                background:radial-gradient(circle,rgba(34,211,238,0.06) 0%,transparent 70%);
                pointer-events:none;z-index:0;"></div>

    {{-- Logo --}}
    <div style="position:fixed;top:0;left:0;right:0;padding:20px 28px;z-index:10;">
        <a href="{{ route('home') }}" class="nav-logo">Tech<span>Jobs</span></a>
    </div>

    {{-- Auth Card --}}
    <div style="position:relative;z-index:1;width:100%;max-width:460px;
                margin:0 auto;padding:28px;">
        @yield('content')
    </div>

    {{-- Footer note --}}
    <div style="text-align:center;padding:24px;position:relative;z-index:1;">
        <p style="font-size:12px;">© {{ date('Y') }} TechJobs Pakistan</p>
    </div>

    @livewireScripts
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>