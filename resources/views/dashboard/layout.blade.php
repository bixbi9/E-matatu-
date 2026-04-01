<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-Matatu | @yield('pageTitle', 'Dashboard')</title>
    <link href="{{ asset('css/dashboard-pages.css') }}" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    @php
        $userName = auth()->user()->name ?? 'Fleet Manager';
        $statusMessage = match (session('status')) {
            'profile-updated' => 'Profile updated successfully.',
            'password-updated' => 'Password updated successfully.',
            'verification-link-sent' => 'A new verification link has been sent to your email address.',
            default => session('status'),
        };
        $navItems = [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'bx bxs-dashboard'],
            ['label' => 'Matatus', 'route' => 'matatus', 'icon' => 'bx bxs-bus'],
            ['label' => 'Crew', 'route' => 'crew', 'icon' => 'bx bxs-group'],
            ['label' => 'Inspections', 'route' => 'inspection', 'icon' => 'bx bxs-report'],
            ['label' => 'Maintenance', 'route' => 'maintenance', 'icon' => 'bx bxs-wrench'],
            ['label' => 'Insurance', 'route' => 'insurance', 'icon' => 'bx bxs-shield'],
        ];
    @endphp

    <div class="dashboard-shell">
        <aside class="sidebar">
            <a class="brand" href="{{ route('dashboard') }}">
                <div class="brand-topline">
                    <span class="brand-mark">e</span>
                    <div class="traffic-lights" aria-hidden="true">
                        <span class="traffic-dot stop"></span>
                        <span class="traffic-dot wait"></span>
                        <span class="traffic-dot go"></span>
                    </div>
                </div>
                <h1>e-Matatu System</h1>
                <p>Fleet, crew, and route operations in one dashboard.</p>
            </a>

            <nav class="nav-group" aria-label="Dashboard navigation">
                <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                    <i class='bx bxs-user'></i>
                    <span>My Profile</span>
                </a>

                @foreach ($navItems as $item)
                    <a class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
                        <i class="{{ $item['icon'] }}"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="sidebar-footer">
                <div class="user-chip">
                    <span class="user-avatar">{{ strtoupper(substr($userName, 0, 1)) }}</span>
                    <div>
                        <strong>{{ $userName }}</strong>
                        <div class="muted">Signed in</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout-button" type="submit">Log Out</button>
                </form>
            </div>
        </aside>

        <div class="page-shell">
            <header class="topbar">
                <div>
                    <p class="page-kicker">@yield('pageKicker', 'Operations')</p>
                    <h1 class="page-title">@yield('pageTitle', 'Dashboard')</h1>
                    @hasSection('pageSubtitle')
                        <p class="page-subtitle">@yield('pageSubtitle')</p>
                    @endif
                </div>

                <div class="topbar-actions">
                    <div class="traffic-lights topbar-lights" aria-label="System status lights">
                        <span class="traffic-dot stop"></span>
                        <span class="traffic-dot wait"></span>
                        <span class="traffic-dot go"></span>
                    </div>
                    <a class="action-link" href="{{ config('digital_matatus.source_url') }}" target="_blank" rel="noreferrer">
                        <i class='bx bx-map'></i>
                        <span>Digital Matatus Map</span>
                    </a>
                    <a class="action-link primary" href="{{ route('matatus') }}">
                        <i class='bx bxs-bus'></i>
                        <span>View Route Assignments</span>
                    </a>
                </div>
            </header>

            <main>
                @if ($statusMessage)
                    <div class="flash-card success-card">
                        <i class='bx bxs-check-circle'></i>
                        <span>{{ $statusMessage }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="flash-card error-card">
                        <i class='bx bxs-error-circle'></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (isset($fleetConnectionOk) && ! $fleetConnectionOk)
                    <div class="flash-card error-card">
                        <i class='bx bxs-data'></i>
                        <span>{{ $fleetConnectionMessage }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

            <p class="page-footer">
                Route labels are aligned to the public Digital Matatus map and wall map reference for Nairobi matatu services.
            </p>
        </div>
    </div>
</body>
</html>
