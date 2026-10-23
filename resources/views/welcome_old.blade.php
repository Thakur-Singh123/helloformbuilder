<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName }} – {{ $tagline }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --accent: #0ea5e9;
            --dark: #0f172a;
            --muted: #64748b;
            --bg-soft: #f8fafc;
            --radius: 1rem;
            --shadow: 0 4px 6px -1px rgba(0,0,0,.08), 0 2px 4px -2px rgba(0,0,0,.06);
            --shadow-lg: 0 20px 25px -5px rgba(0,0,0,.1), 0 8px 10px -6px rgba(0,0,0,.1);
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--dark);
            background: var(--bg-soft);
            overflow-x: hidden;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        .animate-delay-1 { animation-delay: 0.1s; opacity: 0; }
        .animate-delay-2 { animation-delay: 0.2s; opacity: 0; }
        .animate-delay-3 { animation-delay: 0.3s; opacity: 0; }
        .animate-delay-4 { animation-delay: 0.4s; opacity: 0; }
        .animate-float { animation: float 4s ease-in-out infinite; }

        /* Navbar */
        .navbar-welcome {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(12px);
            box-shadow: 0 1px 0 rgba(0,0,0,.05);
            padding: 0.75rem 0;
        }
        .navbar-welcome .navbar-brand {
            font-weight: 800;
            font-size: 1.35rem;
            color: var(--primary) !important;
        }
        .navbar-welcome .nav-link {
            font-weight: 500;
            color: var(--dark) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem;
        }
        .navbar-welcome .nav-link:hover { background: var(--bg-soft); color: var(--primary) !important; }
        .navbar-welcome .btn-cta {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white !important;
            font-weight: 600;
            padding: 0.5rem 1.25rem !important;
            border-radius: 0.5rem;
            border: none;
        }
        .navbar-welcome .btn-cta:hover { opacity: 0.95; color: white !important; }

        /* Hero */
        .hero-welcome {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 5rem 0 4rem;
            position: relative;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 200% 200%;
            animation: gradientShift 12s ease infinite;
            color: white;
        }
        .hero-welcome::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }
        .hero-welcome .container { position: relative; z-index: 1; }
        .hero-welcome h1 {
            font-weight: 800;
            font-size: clamp(2rem, 5vw, 3.25rem);
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .hero-welcome .lead {
            font-size: 1.1rem;
            opacity: 0.95;
            max-width: 540px;
        }
        .hero-card {
            background: rgba(255,255,255,0.98);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            padding: 2rem;
            color: var(--dark);
        }
        .hero-card .form-control, .hero-card .form-select {
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            padding: 0.65rem 1rem;
        }
        .hero-card .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
        }
        .hero-card .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            font-weight: 600;
            padding: 0.75rem;
            border-radius: 0.5rem;
        }
        .hero-card .btn-success:hover { opacity: 0.95; }

        /* Features */
        .section-title {
            font-weight: 800;
            font-size: clamp(1.75rem, 4vw, 2.25rem);
            color: var(--dark);
        }
        .feature-card {
            background: white;
            border-radius: var(--radius);
            padding: 1.75rem;
            height: 100%;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0,0,0,0.04);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        .feature-card .bi {
            font-size: 2.25rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        .feature-card h5 { font-weight: 700; color: var(--dark); }
        .feature-card p { color: var(--muted); font-size: 0.95rem; margin-bottom: 0; }

        /* Plans / Pricing */
        .plans-section { padding: 4rem 0; }
        .plan-card {
            background: white;
            border-radius: var(--radius);
            padding: 2rem;
            height: 100%;
            box-shadow: var(--shadow);
            border: 2px solid transparent;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            position: relative;
        }
        .plan-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        .plan-card--highlighted {
            border-color: var(--primary);
            box-shadow: 0 0 0 1px var(--primary), var(--shadow-lg);
        }
        .plan-card--highlighted::before {
            content: 'Popular';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            letter-spacing: 0.02em;
        }
        .plan-card .plan-name {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        .plan-card .plan-price {
            font-weight: 800;
            font-size: 2.5rem;
            color: var(--primary);
            line-height: 1.2;
        }
        .plan-card .plan-price span { font-size: 1rem; font-weight: 600; color: var(--muted); }
        .plan-card .plan-desc { color: var(--muted); font-size: 0.9rem; margin-bottom: 1.5rem; min-height: 2.5em; }
        .plan-card .plan-features {
            list-style: none;
            padding: 0;
            margin: 0 0 1.5rem 0;
        }
        .plan-card .plan-features li {
            padding: 0.4rem 0;
            padding-left: 1.5rem;
            position: relative;
            color: var(--dark);
            font-size: 0.95rem;
        }
        .plan-card .plan-features li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.65em;
            width: 0.5rem;
            height: 0.5rem;
            background: var(--primary);
            border-radius: 50%;
        }
        .plan-card .btn-plan {
            width: 100%;
            font-weight: 600;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 2px solid var(--primary);
            background: transparent;
            color: var(--primary);
            transition: background 0.2s ease, color 0.2s ease;
        }
        .plan-card .btn-plan:hover { background: var(--primary); color: white; }
        .plan-card--highlighted .btn-plan {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            border-color: transparent;
        }
        .plan-card--highlighted .btn-plan:hover { opacity: 0.95; color: white; }

        /* Stats strip */
        .stats-strip {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            padding: 2.5rem 0;
        }
        .stats-strip .stat-item { text-align: center; }
        .stats-strip .stat-value { font-weight: 800; font-size: 1.25rem; }
        .stats-strip .stat-label { font-size: 0.85rem; opacity: 0.9; }

        /* Subscription */
        .subscribe-section {
            background: linear-gradient(180deg, #f1f5f9 0%, #e2e8f0 100%);
            padding: 4rem 0;
        }
        .subscribe-card {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            padding: 2.5rem;
            max-width: 560px;
            margin: 0 auto;
        }
        .subscribe-card .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
        }
        .subscribe-card .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
        }
        .subscribe-card .btn-subscribe {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            font-weight: 600;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            white-space: nowrap;
        }
        .subscribe-card .btn-subscribe:hover { opacity: 0.95; color: white; }
        #subscribeMessage { font-size: 0.9rem; }

        /* CTA */
        .cta-section {
            background: var(--dark);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }
        .cta-section .btn-light {
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
        }

        /* Footer */
        .footer-welcome {
            background: #0f172a;
            color: #94a3b8;
            padding: 2rem 0;
            font-size: 0.9rem;
        }
        .footer-welcome a { color: #cbd5e1; text-decoration: none; }
        .footer-welcome a:hover { color: white; }

        /* Responsive */
        @media (max-width: 991.98px) {
            .hero-welcome { min-height: auto; padding: 4rem 0 3rem; }
            .hero-welcome .hero-card { margin-top: 2rem; }
        }
        @media (max-width: 991.98px) {
            .plan-card--highlighted { margin-top: 0.5rem; }
        }
        @media (max-width: 575.98px) {
            .subscribe-card .input-group { flex-direction: column; }
            .subscribe-card .btn-subscribe { width: 100%; }
            .plan-card .plan-price { font-size: 2rem; }
        }

        /* Scroll-triggered animation */
        .reveal { opacity: 0; transform: translateY(20px); }
        .reveal.revealed { animation: fadeInUp 0.6s ease-out forwards; }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; }
            .hero-welcome { animation: none; }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-welcome fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">{{ $appName }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#plans">Plans</a></li>
                    <li class="nav-item"><a class="nav-link" href="#subscribe">Newsletter</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-cta" href="{{ route('login') }}">Log in</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-welcome">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <h1 class="animate-fade-in-up animate-delay-1 mb-3">{{ $tagline }}</h1>
                    <p class="lead animate-fade-in-up animate-delay-2 mb-4">{{ $heroSubtext }}</p>
                    <div class="animate-fade-in-up animate-delay-3">
                        <a href="#features" class="btn btn-light btn-lg rounded-pill px-4 shadow-sm fw-semibold">
                            See how it works <i class="bi bi-arrow-down ms-2"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="hero-card animate-fade-in-up animate-delay-4">
                        <h4 class="fw-bold mb-4 text-center">Start free</h4>
                        @if($errors->any())
                            <div class="alert alert-danger py-2 mb-3 small" role="alert">
                                @foreach($errors->all() as $err)
                                    <div>{{ $err }}</div>
                                @endforeach
                            </div>
                        @endif
                        <form method="POST" action="{{ route('user_register') }}" id="registerForm">
                            @csrf
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label for="first_name" class="form-label small fw-semibold">First name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required placeholder="John" value="{{ old('first_name') }}">
                                </div>
                                <div class="col-6">
                                    <label for="last_name" class="form-label small fw-semibold">Last name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required placeholder="Doe" value="{{ old('last_name') }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label small fw-semibold">Email</label>
                                <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" name="email" required placeholder="you@example.com" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label small fw-semibold">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required minlength="6" placeholder="••••••••">
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label small fw-semibold">Confirm password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6" placeholder="••••••••">
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" id="registerTerms" name="registerTerms" required>
                                <label class="form-check-label small text-muted" for="registerTerms">I agree to the <a href="#" class="text-primary">Terms</a></label>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Create account</button>
                            <p class="text-center small text-muted mt-3 mb-0">Already have an account? <a href="{{ route('login') }}" class="text-primary fw-semibold">Log in</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="stats-strip py-4">
        <div class="container">
            <div class="row g-4">
                @foreach($stats as $stat)
                <div class="col-md-4 stat-item">
                    <div class="stat-value">{{ $stat['value'] }}</div>
                    <div class="stat-label">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="features" class="py-5 py-md-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">Features that fit your workflow</h2>
            <div class="row g-4">
                @foreach($features as $index => $feature)
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card reveal">
                        <i class="bi {{ $feature['icon'] }}"></i>
                        <h5>{{ $feature['title'] }}</h5>
                        <p>{{ $feature['description'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="plans" class="plans-section py-5">
        <div class="container">
            <h2 class="section-title text-center mb-2">Simple, transparent pricing</h2>
            <p class="text-center text-muted mb-5">Choose the plan that fits your needs. All plans include our core features.</p>
            <div class="row g-4 justify-content-center">
                @foreach($plans as $plan)
                <div class="col-md-6 col-lg-4">
                    <div class="plan-card reveal {{ !empty($plan['highlighted']) ? 'plan-card--highlighted' : '' }}">
                        <div class="plan-name">{{ $plan['name'] }}</div>
                        <div class="plan-price">${{ $plan['price'] }}<span>{{ $plan['period'] }}</span></div>
                        <p class="plan-desc">{{ $plan['description'] ?? '' }}</p>
                        <ul class="plan-features">
                            @foreach($plan['features'] ?? [] as $feature)
                            <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                        @if(!empty($plan['cta']))
                        <a href="{{ ($plan['cta'] ?? '') === 'Contact sales' ? '#' : '#registerForm' }}" class="btn btn-plan">{{ $plan['cta'] }}</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h3 class="fw-bold mb-2">Ready to grow your leads?</h3>
            <p class="opacity-90 mb-4">Join teams who use {{ $appName }} to capture and manage form submissions.</p>
            <a href="#registerForm" class="btn btn-light btn-lg">Get started free</a>
        </div>
    </section>

    <section id="subscribe" class="subscribe-section">
        <div class="container">
            <div class="subscribe-card reveal">
                <div class="text-center mb-4">
                    <i class="bi bi-envelope-heart text-primary" style="font-size: 2.5rem;"></i>
                    <h4 class="fw-bold mt-2 mb-1">{{ $subscribeTitle ?? 'Stay in the loop' }}</h4>
                    <p class="text-muted mb-0">{{ $subscribeDescription ?? 'Tips, updates, and product news. No spam.' }}</p>
                </div>
                <form id="subscribeForm" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-12 col-sm">
                        <label for="subscriber_email" class="form-label small visually-hidden">Email</label>
                        <input type="email" class="form-control" id="subscriber_email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="col-12 col-sm-auto">
                        <button type="submit" class="btn btn-subscribe w-100 w-sm-auto" id="subscribeBtn">Subscribe</button>
                    </div>
                </form>
                <div id="subscribeMessage" class="mt-3 text-center"></div>
            </div>
        </div>
    </section>

    <footer class="footer-welcome">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="me-3">Privacy</a>
                    <a href="#">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        (function() {
            var subscribeForm = document.getElementById('subscribeForm');
            var subscribeMessage = document.getElementById('subscribeMessage');
            var subscribeBtn = document.getElementById('subscribeBtn');
            if (subscribeForm) {
                subscribeForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    subscribeMessage.textContent = '';
                    subscribeMessage.className = 'mt-3 text-center';
                    var btnText = subscribeBtn.textContent;
                    subscribeBtn.disabled = true;
                    subscribeBtn.textContent = 'Sending...';
                    var formData = new FormData(subscribeForm);
                    fetch('{{ route("newsletter.subscribe") }}', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    }).then(function(r) { return r.json(); }).then(function(data) {
                        subscribeMessage.textContent = data.message || 'Thank you!';
                        subscribeMessage.classList.add(data.success ? 'text-success' : 'text-danger');
                        if (data.success) subscribeForm.reset();
                    }).catch(function() {
                        subscribeMessage.textContent = 'Something went wrong. Try again.';
                        subscribeMessage.classList.add('text-danger');
                    }).finally(function() {
                        subscribeBtn.disabled = false;
                        subscribeBtn.textContent = btnText;
                    });
                });
            }
            var reveals = document.querySelectorAll('.reveal');
            function checkReveal() {
                reveals.forEach(function(el) {
                    var top = el.getBoundingClientRect().top;
                    if (top < window.innerHeight - 80) el.classList.add('revealed');
                });
            }
            window.addEventListener('scroll', checkReveal);
            checkReveal();
        })();
    </script>
</body>
</html>
