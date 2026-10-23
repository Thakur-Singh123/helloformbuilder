<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->site_title ?? config('app.name') }} - {{ Auth::user()->user_type === 'superadmin' ? 'Super Admin' : 'Admin' }} Dashboard</title>
    <link rel="icon" type="image/png" href="{{ url('public/') }}/{{ $settings->icon ?? 'favicon.ico' }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --sa-primary: #0f0c29;
            --sa-secondary: #302b63;
            --sa-accent: #e94560;
            --admin-primary: #023147;
            --admin-secondary: #607d8b;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            margin: 0;
            color: #333;
        }
        body.superadmin { background: linear-gradient(135deg, var(--sa-primary) 0%, var(--sa-secondary) 50%, #24243e 100%); }
        body.admin-role { background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-secondary) 100%); }
        .dashboard-header {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 1rem 1.5rem;
        }
        .dashboard-header .navbar-brand,
        .dashboard-header .text-white { color: #fff !important; }
        .dashboard-header .text-white-50 { color: rgba(255,255,255,0.8) !important; }
        .btn-logout {
            background: rgba(255,255,255,0.2);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            text-decoration: none;
        }
        .btn-logout:hover { background: rgba(255,255,255,0.3); color: #fff; }
        .main-content {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }
        .dashboard-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            border: none;
        }
        .dashboard-card h5 {
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 0.5rem;
        }
        .dashboard-card .lead {
            color: #6c757d;
            font-size: 0.95rem;
        }
        .card-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-cta:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .superadmin .card-cta {
            background: linear-gradient(135deg, var(--sa-primary) 0%, var(--sa-secondary) 100%);
            color: #fff;
            border: none;
        }
        .superadmin .card-cta:hover { color: #fff; opacity: 0.95; }
        .admin-role .card-cta {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-secondary) 100%);
            color: #fff;
            border: none;
        }
        .admin-role .card-cta:hover { color: #fff; opacity: 0.95; }
        .card-cta-desc {
            color: #6c757d;
            font-size: 0.875rem;
            margin-top: 0.75rem;
        }
        .welcome-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .superadmin .welcome-badge { background: rgba(233,69,96,0.2); color: #e94560; }
        .admin-role .welcome-badge { background: rgba(255,255,255,0.25); color: #fff; }
    </style>
</head>
<body class="{{ Auth::user()->user_type === 'superadmin' ? 'superadmin' : 'admin-role' }}">
    <header class="dashboard-header">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin_dashboard') }}" class="navbar-brand d-flex align-items-center gap-2 text-white">
                    <img src="{{ url('public/uploads/logo.png') }}" alt="Logo" height="36" onerror="this.style.display='none'">
                    <span class="fw-bold">{{ $settings->site_title ?? config('app.name') }}</span>
                </a>
                <span class="welcome-badge">{{ Auth::user()->user_type === 'superadmin' ? 'Super Admin' : 'Admin' }}</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white-50 small d-none d-sm-inline">Welcome, {{ Auth::user()->name ?? Auth::user()->email }}</span>
                <a href="#" class="btn-logout" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">Logout</a>
                <form id="admin-logout-form" action="{{ Auth::user()->user_type === 'superadmin' ? route('superadmin_logout') : route('admin_logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </div>
    </header>

    <main class="main-content">
        @include('alerts')

        <div class="dashboard-card">
            <h5><i class="fas fa-tachometer-alt me-2 text-primary"></i>Overview</h5>
            <p class="lead mb-4">Use the options below to manage your account and settings.</p>

            @if(Auth::user()->user_type === 'superadmin')
            <a href="{{ route('admin.admins.index') }}" class="card-cta">
                <i class="fas fa-building"></i> Manage Admins
            </a>
            <p class="card-cta-desc">View all Admins (companies), create, edit or deactivate. System-level settings.</p>
            @endif

            @if(Auth::user()->user_type === 'admin')
            <a href="{{ route('admin.clients.index') }}" class="card-cta">
                <i class="fas fa-users"></i> Clients
            </a>
            <p class="card-cta-desc">Website owners (basic info only). Create shortcodes and assign to clients.</p>
            @endif
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
