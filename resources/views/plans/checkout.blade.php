<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Upgrade to Pro | HelloFormBuilder</title>
<style>
:root{
  --navy:#0e2a6b;
  --primary:#1d4fc4;
  --primary-light:#2196f3;
  --muted:#64748b;
  --border:#d9e6fa;
}
*{ box-sizing:border-box; }
body{
  margin:0; min-height:100vh; display:flex; align-items:center; justify-content:center;
  padding:30px 16px; background:#eaf1ff; color:var(--navy);
  font-family:"Inter","Segoe UI",system-ui,-apple-system,Arial,sans-serif; line-height:1.5;
}
.card{
  width:min(890px,100%); display:grid; grid-template-columns:1fr 1.15fr;
  background:#fff; border-radius:20px; overflow:hidden;
  box-shadow:0 25px 60px rgba(14,42,107,.15);
}

/* Left panel */
.side{
  display:flex; flex-direction:column; justify-content:space-between;
  padding:32px 30px; color:#fff;
  background:linear-gradient(160deg,#0b2a7a 0%,#1440b8 55%,#1a4fd0 100%);
}
.logo{ font-size:19px; font-weight:800; letter-spacing:-.3px; }
.logo span{ color:#7cc4ff; }
.side h2{ font-size:30px; line-height:1.2; letter-spacing:-1px; margin:0 0 20px; font-weight:800; }
.side ul{ list-style:none; margin:0; padding:0; font-size:14px; }
.side li{ margin:10px 0; }
.side small{ font-size:12px; opacity:.8; }

/* Right panel */
.main{ padding:32px 36px; }
.back{
  display:inline-flex; align-items:center; gap:6px; padding:8px 14px;
  border:1px solid var(--border); border-radius:8px; background:#fff;
  color:var(--primary); font-size:14px; font-weight:600; text-decoration:none;
}
.back:hover{ background:#eff6ff; }
.main h1{ font-size:27px; letter-spacing:-.8px; margin:28px 0 4px; font-weight:800; }
.sub{ color:var(--muted); font-size:14px; margin:0 0 22px; }

.alert{
  background:#fff7ed; border:1px solid #fed7aa; color:#9a3412;
  padding:12px 14px; border-radius:10px; font-size:14px; margin-bottom:18px;
}

.plan-box{
  border:2px solid var(--primary); border-radius:14px; padding:18px 20px;
  background:#f7faff; display:flex; justify-content:space-between; align-items:center; gap:12px;
}
.plan-box .name{ font-size:18px; font-weight:800; }
.plan-box .desc{ color:var(--muted); font-size:13px; }
.plan-box .price{ text-align:right; }
.plan-box .price strong{ font-size:30px; letter-spacing:-1px; }
.plan-box .price span{ color:var(--muted); font-size:13px; }

.perks{ list-style:none; padding:0; margin:20px 0; font-size:14px; }
.perks li{ display:flex; gap:10px; margin:10px 0; }
.perks .tick{
  flex-shrink:0; width:20px; height:20px; border-radius:50%; background:#eff6ff;
  color:var(--primary); font-size:12px; font-weight:800; display:grid; place-items:center;
}

.account{
  font-size:13px; color:var(--muted); border-top:1px solid var(--border);
  padding-top:14px; margin-bottom:18px;
}
.account strong{ color:var(--navy); }

.pay-btn{
  width:100%; min-height:48px; border:0; border-radius:10px; cursor:pointer;
  color:#fff; font-size:15px; font-weight:700; font-family:inherit;
  background:linear-gradient(90deg,var(--primary),var(--primary-light));
  transition:.2s ease;
}
.pay-btn:hover{ filter:brightness(1.08); transform:translateY(-1px); }
.pay-btn:disabled{ opacity:.7; cursor:wait; transform:none; }

.secure{ text-align:center; color:var(--muted); font-size:12px; margin-top:14px; }

@media (max-width:760px){
  .card{ grid-template-columns:1fr; }
  .side{ padding:24px; gap:22px; }
  .side h2{ font-size:24px; }
  .main{ padding:26px 22px; }
}
</style>
</head>
<body>

<div class="card">

  <!-- Left panel -->
  <aside class="side">
    <div class="logo">Hello<span>Form</span>Builder</div>

    <div>
      <h2>Unlock unlimited<br>templates.</h2>
      <ul>
        <li>✓ Use every ready-made template</li>
        <li>✓ Invoices, team members &amp; tasks</li>
        <li>✓ Live chat &amp; no branding on forms</li>
      </ul>
    </div>

    <small>© {{ date('Y') }} HelloFormBuilder</small>
  </aside>

  <!-- Right panel -->
  <main class="main">
    <a href="{{ route('pricing') }}" class="back">‹ Back to plans</a>

    <h1>{{ auth()->user()->isExpired() ? 'Renew your plan' : 'Upgrade to Pro' }}</h1>
    <p class="sub">
      {{ auth()->user()->isExpired()
          ? 'Your Pro plan has expired. Renew to keep using all templates.'
          : 'Your free template has been used. Upgrade to keep building.' }}
    </p>

    @if(session('error'))
      <div class="alert">{{ session('error') }}</div>
    @endif

    <div class="plan-box">
      <div>
        <div class="name">Pro plan</div>
        <div class="desc">Billed monthly · Cancel anytime</div>
      </div>
      <div class="price"><strong>$49</strong> <span>/ month</span></div>
    </div>

    <ul class="perks">
      <li><span class="tick">✓</span><span>All form builder features &amp; templates</span></li>
      <li><span class="tick">✓</span><span>Invoice creation &amp; team management</span></li>
      <li><span class="tick">✓</span><span>Remove HelloFormBuilder branding</span></li>
    </ul>

    <div class="account">
      Signed in as <strong>{{ auth()->user()->email }}</strong>
    </div>

    <form method="POST" action="{{ route('plans.checkout') }}" onsubmit="this.querySelector('button').disabled = true; this.querySelector('button').textContent = 'Redirecting to Stripe…';">
      @csrf
      <button type="submit" class="pay-btn">Continue to secure payment</button>
    </form>

    <div class="secure">🔒 Secure payment powered by Stripe</div>
  </main>

</div>

</body>
</html>