<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta tags for character encoding and responsive viewport -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token for security -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- External CSS and Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Base styles */
        body {
            background: #f8f9fa;
        }
        /* Sidebar styles */
        .sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #fff;
            border-right: 1px solid #e9ecef;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.5rem 0.75rem 1.5rem 0.75rem;
        }
        /* Navigation link styles */
        .sidebar .nav-link {
            color: #2c3e50;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-radius: 6px;
            margin-bottom: 0.5rem;
        }
        /* Active and hover states for nav links */
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: #e9ecef;
            color: #3498db;
        }
        /* Logout button positioning */
        .sidebar .logout {
            margin-top: auto;
        }
        /* Sidebar header styling */
        .sidebar .sidebar-header {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 2rem;
            color: #3498db;
        }
        /* Mobile responsive styles */
        @media (max-width: 991.98px) {
            .sidebar {
                display: none;
            }
            .offcanvas-sidebar {
                width: 250px !important;
            }
        }
        /* Main content area styles */
        .main-content {
            margin-left: 250px;
            padding: 2rem 1rem 1rem 1rem;
        }
        /* Mobile responsive main content */
        @media (max-width: 991.98px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Sidebar navigation -->
        <div class="sidebar d-none d-lg-flex flex-column">
            <div>
                <div class="sidebar-header">Dispatch</div>
                <!-- Main navigation menu -->
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->is('home') ? 'active' : '' }}" href="{{ url('/home') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a class="nav-link {{ request()->is('services*') ? 'active' : '' }}" href="{{ route('services.index') }}"><i class="bi bi-gear"></i> Services</a>
                    <a class="nav-link {{ request()->is('branches*') ? 'active' : '' }}" href="{{ route('branches.index') }}"><i class="bi bi-diagram-3"></i> Branches</a>
                    <a class="nav-link {{ request()->is('incidents*') ? 'active' : '' }}" href="{{ route('incidents.index') }}"><i class="bi bi-exclamation-triangle"></i> Incidents</a>
                    <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i class="bi bi-people"></i> Users</a>
                </nav>
            </div>
            <!-- Logout form -->
            <form class="logout" id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</button>
            </form>
        </div>
        <!-- Main content area where page content will be rendered -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>
    <!-- Bootstrap JavaScript bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
