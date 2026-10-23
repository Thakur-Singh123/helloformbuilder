<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->site_title ?? config('app.name') }} - Admin</title>
    <link rel="icon" type="image/png" href="{{ url('public/') }}/{{ $settings->icon ?? 'favicon.ico' }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary-color: #023147;
            --secondary-color: #607d8b;
        }
        body {
            font-family: 'Inter', sans-serif;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
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
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            background: white;
            border-top: 4px solid var(--primary-color);
        }
        .admin-badge {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
        }
        .btn-admin {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
            border-radius: 8px;
        }
        .btn-admin:hover {
            background-color: #034a6b;
            border-color: #034a6b;
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
                <span class="badge admin-badge">Admin</span>
            </p>
            <p class="text-center text-muted small mb-4">Company / Admin login.</p>

            @include('alerts')

            <form method="POST" action="{{ route('attempt_login') }}">
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
                    <button type="submit" class="btn btn-admin btn-lg text-white">Admin Sign In</button>
                </div>
            </form>
            <p class="text-center mt-3 mb-0">
                <a href="{{ route('superadmin_login') }}" class="text-muted small text-decoration-none">Super Admin login</a>
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
