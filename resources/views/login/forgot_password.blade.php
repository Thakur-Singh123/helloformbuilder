<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password – {{ $settings->seo_title ?? config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #1e7e72;
            --bg-light: #f8f9fa;
            --font-family: 'Inter', sans-serif;
        }
        body {
            font-family: var(--font-family);
            background-color: var(--bg-light);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-branding {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            height: 100%;
            min-height: 100vh;
            padding: 20px;
        }
        .auth-form-area {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: white;
            padding: 20px;
        }
        .form-card {
            max-width: 496px;
            width: 100%;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        .btn-primary-custom {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            font-weight: 600;
            border-radius: 8px;
        }
        .btn-primary-custom:hover {
            background-color: #1a645d;
            border-color: #1a645d;
            color: white;
        }
        @media (max-width: 768px) {
            .auth-branding { display: none; }
        }
    </style>
</head>
<body>
    <div class="container-fluid auth-container">
        <div class="row w-100 min-vh-100">
            <div class="col-md-6 d-none d-md-flex auth-branding">
                <div>
                    <i class="fas fa-cubes fa-4x mb-4"></i>
                    <h2 class="display-5 fw-bold mb-3">FormFlow Admin</h2>
                    <p class="lead">Your unified hub for form creation, project management, and team communication.</p>
                </div>
            </div>
            <div class="col-12 col-md-6 auth-form-area">
                <div class="form-card">
                    <h1 class="text-center mb-4 text-secondary">Forgot Password</h1>
                    <p class="text-muted text-center small mb-4">Enter your email and we'll send you a link to reset your password.</p>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $err)
                                <div>{{ $err }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('sendResetLink') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" name="email" id="email" class="form-control rounded-2" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email">
                        </div>
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary-custom btn-lg text-white">Send reset link</button>
                        </div>
                    </form>

                    <p class="text-center mt-4 mb-0">
                        <a href="{{ route('login') }}" class="text-decoration-none small text-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to login
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
