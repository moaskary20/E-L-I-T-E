<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" />
    <title>@yield('title', 'Admin Portal | Elite Physio Clinics')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    @stack('head')
</head>
<body>
    <div class="admin-shell" x-data="{ sidebarOpen: false }">
        <aside class="admin-sidebar" :class="{ 'open': sidebarOpen }">
            <div class="admin-sidebar-brand">
                <div class="admin-sidebar-logo">
                    <span class="admin-logo-mark">EP</span>
                </div>
                <div class="admin-sidebar-brand-text">
                    <span class="admin-sidebar-title">Elite Physio</span>
                    <span class="admin-sidebar-badge">Admin Portal</span>
                </div>
                <button type="button" class="admin-sidebar-close" @click="sidebarOpen = false" aria-label="Close menu">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <nav class="admin-sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" @click="sidebarOpen = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.appointments.index') }}" class="admin-sidebar-link {{ request()->routeIs('admin.appointments.*') && !request()->routeIs('admin.appointments.create') ? 'active' : '' }}" @click="sidebarOpen = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                    <span>Appointments</span>
                </a>
                <a href="{{ route('admin.appointments.create') }}" class="admin-sidebar-link {{ request()->routeIs('admin.appointments.create') ? 'active' : '' }}" @click="sidebarOpen = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                    <span>New Booking</span>
                </a>
                <a href="{{ route('admin.availability.index') }}" class="admin-sidebar-link {{ request()->routeIs('admin.availability.*') ? 'active' : '' }}" @click="sidebarOpen = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>Availability</span>
                </a>
                <a href="{{ route('admin.hours.index') }}" class="admin-sidebar-link {{ request()->routeIs('admin.hours.*') ? 'active' : '' }}" @click="sidebarOpen = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                    <span>Working Hours</span>
                </a>
                <a href="{{ route('admin.seo.index') }}" class="admin-sidebar-link {{ request()->routeIs('admin.seo.*') ? 'active' : '' }}" @click="sidebarOpen = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="M11 8v6"/><path d="M8 11h6"/></svg>
                    <span>SEO / Search Console</span>
                </a>
            </nav>

            <div class="admin-sidebar-footer">
                <div class="admin-sidebar-user">
                    <div class="admin-sidebar-avatar">{{ strtoupper(substr(auth()->user()->email ?? 'A', 0, 1)) }}</div>
                    <div class="admin-sidebar-user-info">
                        <span class="admin-sidebar-user-email">{{ auth()->user()->email }}</span>
                        <span class="admin-sidebar-user-role">Administrator</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="admin-sidebar-logout" title="Sign out">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                    </button>
                </form>
            </div>
        </aside>

        <div class="admin-overlay" x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak></div>

        <div class="admin-main">
            <header class="admin-topbar">
                <button type="button" class="admin-topbar-menu" @click="sidebarOpen = true" aria-label="Open menu">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                </button>
                <div class="admin-topbar-right">
                    <span class="admin-topbar-greeting">Welcome back</span>
                </div>
            </header>
            <main class="admin-content">
                @if(session('success'))
                    <div class="admin-success" style="margin-bottom: 16px;">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="admin-error" style="margin-bottom: 16px;">{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    @stack('scripts')
    <style>[x-cloak] { display: none !important; }</style>
</body>
</html>
