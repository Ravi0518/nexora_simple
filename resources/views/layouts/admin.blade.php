<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexora Admin | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --nexora-green: #00FF66;
            --dark-bg: #07120B; /* Matches your screenshot background */
            --card-bg: #111A13;
            --sidebar-bg: #0A140E;
        }

        body {
            background-color: var(--dark-bg);
            color: white;
            font-family: 'Inter', sans-serif;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            background-color: var(--sidebar-bg);
            border-right: 1px solid #1A2E20;
            padding-top: 20px;
        }

        .sidebar-brand {
            padding: 20px 25px;
            font-size: 24px;
            font-weight: bold;
            color: var(--nexora-green);
            text-decoration: none;
            display: block;
        }

        .nav-link {
            color: #8E9A92;
            padding: 12px 25px;
            display: flex;
            align-items: center;
            transition: 0.3s;
            text-decoration: none;
        }

        .nav-link i { margin-right: 12px; width: 20px; }

        .nav-link:hover, .nav-link.active {
            color: var(--nexora-green);
            background-color: #16261B;
            border-left: 4px solid var(--nexora-green);
        }

        /* Content Area */
        .main-content {
            margin-left: 260px;
            padding: 40px;
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid #1A2E20;
            border-radius: 12px;
            color: white;
        }

        .btn-success {
            background-color: var(--nexora-green);
            border: none;
            color: black;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">NEXORA_</a>
        
        <nav class="nav flex-column mt-4">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="fas fa-users"></i> Users
            </a>
            <a class="nav-link {{ request()->is('admin/snakes*') ? 'active' : '' }}" href="{{ route('snakes.index') }}">
                <i class="fas fa-biohazard"></i> Snakes
            </a>
            <a class="nav-link {{ request()->routeIs('admin.incidents') ? 'active' : '' }}" href="{{ route('admin.incidents') }}">
                <i class="fas fa-satellite-dish"></i> Sighting API
            </a>
            <a class="nav-link {{ request()->routeIs('admin.requests') ? 'active' : '' }}" href="{{ route('admin.requests') }}">
                <i class="fas fa-ambulance"></i> Help Requests
            </a>
            
            <hr class="border-secondary mx-3 my-2">
            <small class="text-muted px-4 mb-2 text-uppercase fw-bold" style="font-size: 0.75rem;">Enthusiast Ops</small>
            
            <a class="nav-link {{ request()->routeIs('admin.enthusiasts.map') ? 'active' : '' }}" href="{{ route('admin.enthusiasts.map') }}">
                <i class="fas fa-map-location-dot"></i> Live Map
            </a>
            <a class="nav-link {{ request()->routeIs('admin.incidents.dispatch') ? 'active' : '' }}" href="{{ route('admin.incidents.dispatch') }}">
                <i class="fas fa-truck-fast"></i> Dispatch List
            </a>
            <a class="nav-link {{ request()->routeIs('admin.catch_reports') ? 'active' : '' }}" href="{{ route('admin.catch_reports') }}">
                <i class="fas fa-clipboard-check"></i> Catch Reports
            </a>

            <div class="mt-5 px-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">Logout</button>
                </form>
            </div>
        </nav>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>