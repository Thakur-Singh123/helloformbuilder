<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'HelloFormBuilder')</title>
    @yield('meta')
    <link rel="icon" type="image/png" href="{{ asset('public/images/hfb-icon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('public/images/hfb-icon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{brand:{navy:'#0e2a6b',blue:'#1e88ff',deep:'#1747c9',sky:'#f1f7ff',line:'#d9e6fa'}}}}}</script>

    <style>
        body{
            font-family:'Inter',sans-serif;
            background:#f1f7ff;
            color:#1e293b;
            -webkit-font-smoothing:antialiased;
            min-height:100vh;
            display:flex;
            flex-direction:column;
        }
        .site-content{flex:1 0 auto}
        h1,h2,h3{font-family:'Sora',sans-serif;letter-spacing:-0.02em}
        .btn-grad{background-image:linear-gradient(135deg,#1747c9,#1e9bff)}
        .btn-grad:hover{filter:brightness(1.08)}

        .site-hero{background:linear-gradient(to bottom,#fff,rgba(219,234,254,.7));border-bottom:1px solid #d9e6fa}
        .site-container{max-width:72rem;margin:0 auto;padding:2.5rem 1rem}

        .site-header{position:sticky;top:0;z-index:50;background:#fff;border-bottom:1px solid #E2E5EA}
        .site-header__inner{display:flex;align-items:center;justify-content:space-between;gap:16px;max-width:1400px;margin:0 auto;padding:16px 24px}
        .site-logo img{width:280px;max-width:100%;height:auto;display:block}
        .site-nav{display:flex;gap:6px}
        .site-nav a{font-size:.85rem;font-weight:500;color:#1B2230;padding:8px 12px;border-radius:6px;white-space:nowrap;text-decoration:none}
        .site-nav a:hover{color:#2F6FED;background:rgba(47,111,237,.06)}
        .site-nav a.active{color:#2F6FED}
        .site-login{display:inline-flex;align-items:center;gap:8px;background:#2F6FED;color:#fff;font-weight:600;font-size:.87rem;padding:11px 22px;border-radius:7px;text-decoration:none;flex-shrink:0}
        .site-login:hover{background:#1D4FC4}
      
        .site-menu-toggle {
            display: none;
        }

        @media (max-width: 960px) { .site-logo img {width: 200px;}.site-menu-toggle {display: inline-flex; align-items: center;justify-content: center;border: 1px solid #E2E5EA; background: #fff;color: #1B2230;border-radius: 6px;width: 40px;height: 40px;font-size: 23px;cursor: pointer;flex-shrink: 0; }.site-nav {display: none;}.site-nav.mobile-open {display:flex;position:absolute;top:100%;left:0;right:0;flex-direction:column;gap:4px;padding:12px 16px;background: #fff;border-top: 1px solid #E2E5EA;border-bottom: 1px solid #E2E5EA; box-shadow: 0 8px 16px rgba(0,0,0,.06);}.site-nav.mobile-open a {padding: 10px 12px;}.site-header__inner {position: relative;}}
        .site-footer{background:#fff;border-top:1px solid #E2E5EA;padding:64px 0 28px;color:#5B6472}
        .site-footer__wrap{max-width:1200px;margin:0 auto;padding:0 32px}
        .site-footer__grid{display:grid;grid-template-columns:1.5fr 1fr 1fr 1fr;gap:40px;margin-bottom:48px}
        .site-footer h4{font-family:'Sora',sans-serif;font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:#1B2230;margin-bottom:16px;font-weight:600}
        .site-footer ul{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:10px;font-size:.88rem}
        .site-footer a{color:inherit;text-decoration:none}
        .site-footer a:hover{color:#2F6FED}
        .site-footer__brand{display:flex;align-items:center;gap:8px;font-family:'Sora',sans-serif;font-weight:700;font-size:1.15rem;color:#1B2230;margin-bottom:14px}
        .site-footer__brand i{width:9px;height:9px;border-radius:2px;background:#F0A93A;display:inline-block}
        .site-footer__bottom{border-top:1px solid #E2E5EA;padding-top:22px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px;font-size:.8rem;color:#8B958E}
        .site-footer__mono{font-family:'JetBrains Mono',monospace;letter-spacing:.02em}
        @media (max-width:960px){.site-footer__grid{grid-template-columns:1fr 1fr}}
    </style>
    @stack('head')
</head>
<body>

<header class="site-header">
    <div class="site-header__inner">
        <a href="{{ url('/') }}" class="site-logo">
            <img src="{{ url('public/uploads/logo/logo.png') }}" alt="HelloFormBuilder">
        </a>

        <button type="button"
                class="site-menu-toggle"
                id="siteMenuToggle"
                aria-label="Open menu"
                aria-expanded="false"
                aria-controls="siteNav">
            ☰
        </button>

        <nav class="site-nav" id="siteNav" aria-label="Main">
            <a href="{{ url('/') }}"
            class="{{ request()->is('/') ? 'active' : '' }}">
                Home
            </a>

            <a href="{{ url('about') }}"
            class="{{ request()->is('about') ? 'active' : '' }}">
                About
            </a>

            <a href="{{ route('templates.index') }}"
            class="{{ request()->routeIs('templates.*') ? 'active' : '' }}">
                Templates
            </a>

            <a href="{{ url('pricing') }}"
            class="{{ request()->is('pricing') ? 'active' : '' }}">
                Pricing
            </a>

            <a href="{{ url('why-us') }}"
            class="{{ request()->is('why-us') ? 'active' : '' }}">
                Why Us
            </a>
        </nav>

        @auth
            <a href="{{ route('user_dashboard') }}" class="site-login">Dashboard →</a>
        @else
            <a href="{{ route('login') }}" class="site-login">Log in →</a>
        @endauth
    </div>
</header>

<div class="site-content">
    @yield('content')
</div>

<footer class="site-footer">
    <div class="site-footer__wrap">
        <div class="site-footer__grid">
            <div>
                <div class="site-footer__brand"><i></i>HelloFormBuilder</div>
                <p style="max-width:32ch;font-size:.9rem">Forms that don't stop at the reply — build, invoice, and get the work done in one place.</p>
            </div>
            <div><h4>Product</h4><ul>
                <li><a href="{{ route('templates.index') }}">Form Templates</a></li>
                <li><a href="#">Invoicing</a></li>
                <li><a href="#">Tasks</a></li>
                <li><a href="{{ url('pricing') }}">Pricing</a></li>
            </ul></div>
            <div><h4>Company</h4><ul>
                <li><a href="{{ url('about') }}">About</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Careers</a></li>
            </ul></div>
            <div><h4>Account</h4><ul>
                <li><a href="{{ route('login') }}">Log In</a></li>
                <li><a href="{{ route('sign_up') }}">Sign Up</a></li>
                <li><a href="#">Help Center</a></li>
            </ul></div>
        </div>
        <div class="site-footer__bottom">
            <span>© {{ date('Y') }} HelloFormBuilder, Inc. All rights reserved.</span>
            <span class="site-footer__mono">HFB · FORMS → INVOICES → TASKS</span>
        </div>
    </div>
</footer>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('siteMenuToggle');
    const nav = document.getElementById('siteNav');
    if (!toggle || !nav) return;
    toggle.addEventListener('click', function () {
        const open = nav.classList.toggle('mobile-open');

        toggle.innerHTML = open ? '&times;' : '&#9776;';
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute(
            'aria-label',
            open ? 'Close menu' : 'Open menu'
        );
    });
});
</script>
@stack('scripts')
</body>
</html>