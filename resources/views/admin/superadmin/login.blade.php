<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->site_title ?? config('app.name') }} - Super Admin</title>
    <link rel="icon" type="image/png" href="{{ url('public/') }}/{{ $settings->icon ?? 'favicon.ico' }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary-color: #0f0c29;
            --secondary-color: #302b63;
            --accent-color: #e94560;
        }
        body {
            font-family: 'Inter', sans-serif;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, #24243e 100%);
        }
        .auth-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .form-card {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            background: white;
            border-top: 4px solid var(--accent-color);
        }
        .superadmin-badge {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--accent-color);
            font-weight: 700;
            letter-spacing: 0.05em;
            padding: 0.35rem 0.75rem;
        }
        .btn-superadmin {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
            border-radius: 8px;
        }
        .btn-superadmin:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="form-card">
            <div class="text-center mb-4">
                <img class="img-fluid" alt="Logo" src="{{ url('public/uploads/logo/logo.png') }}" style="max-width:120px">
            </div>
            <p class="text-center mb-2">
                <span class="badge superadmin-badge">Super Admin</span>
            </p>
            <p class="text-center text-muted small mb-4">Platform administrator access only.</p>

            @include('alerts')

            <form method="POST" action="{{ route('superadmin_attempt_login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control rounded-2" name="email" id="email" value="{{ old('email') }}" placeholder="Email" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control rounded-2" name="password" id="password" placeholder="Password" required>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-superadmin btn-lg text-white">Super Admin Sign In</button>
                </div>
            </form>
            <p class="text-center mt-3 mb-0">
                <a href="{{ route('admin_login') }}" class="text-muted small text-decoration-none">Admin login</a>
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
