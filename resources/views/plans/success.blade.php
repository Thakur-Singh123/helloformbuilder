<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Payment Successful | HelloFormBuilder</title>
<style>
:root{
  --navy:#0e2a6b;
  --primary:#1d4fc4;
  --primary-light:#2196f3;
  --muted:#64748b;
  --border:#d9e6fa;
  --green:#16a34a;
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
.main{ padding:40px 36px; text-align:center; }

.tick{
  width:84px; height:84px; margin:0 auto 20px; border-radius:50%;
  background:#dcfce7; display:grid; place-items:center;
  animation:pop .5s cubic-bezier(.2,1.4,.4,1) both;
}
.tick svg{ width:44px; height:44px; }
.tick path{
  fill:none; stroke:var(--green); stroke-width:4; stroke-linecap:round; stroke-linejoin:round;
  stroke-dasharray:50; stroke-dashoffset:50; animation:draw .5s .35s ease forwards;
}
@keyframes pop{ from{ transform:scale(.3); opacity:0; } to{ transform:scale(1); opacity:1; } }
@keyframes draw{ to{ stroke-dashoffset:0; } }

.main h1{ font-size:28px; letter-spacing:-.8px; margin:0 0 6px; font-weight:800; }
.sub{ color:var(--muted); font-size:14px; margin:0 0 24px; }

.receipt{
  text-align:left; border:1px solid var(--border); border-radius:14px;
  background:#f7faff; padding:6px 20px; margin-bottom:24px;
}
.row{
  display:flex; justify-content:space-between; gap:12px; padding:12px 0;
  font-size:14px; border-bottom:1px solid var(--border);
}
.row:last-child{ border-bottom:0; }
.row span{ color:var(--muted); }
.row strong{ color:var(--navy); text-align:right; word-break:break-all; }
.badge{
  display:inline-block; background:#dcfce7; color:var(--green);
  padding:2px 10px; border-radius:20px; font-size:12px; font-weight:800;
}

.btn{
  display:flex; align-items:center; justify-content:center; width:100%; min-height:48px;
  border-radius:10px; font-size:15px; font-weight:700; text-decoration:none; transition:.2s ease;
}
.btn-primary{ color:#fff; background:linear-gradient(90deg,var(--primary),var(--primary-light)); }
.btn-primary:hover{ filter:brightness(1.08); transform:translateY(-1px); }
.btn-light{ margin-top:12px; color:var(--primary); background:#fff; border:1px solid var(--border); }
.btn-light:hover{ background:#eff6ff; }

.note{ color:var(--muted); font-size:12px; margin-top:16px; }

@media (max-width:760px){
  .card{ grid-template-columns:1fr; }
  .side{ padding:24px; gap:22px; }
  .side h2{ font-size:24px; }
  .main{ padding:30px 22px; }
}
</style>
</head>
<body>

<div class="card">

  <!-- Left panel -->
  <aside class="side">
    <div class="logo">Hello<span>Form</span>Builder</div>

    <div>
      <h2>Welcome to Pro 🎉</h2>
      <ul>
        <li>✓ Unlimited ready-made templates</li>
        <li>✓ Invoices, team members &amp; tasks</li>
        <li>✓ Live chat &amp; no branding on forms</li>
      </ul>
    </div>

    <small>© {{ date('Y') }} HelloFormBuilder</small>
  </aside>

  <!-- Right panel -->
  <main class="main">
    <div class="tick">
      <svg viewBox="0 0 48 48"><path d="M12 25 L21 34 L36 15"/></svg>
    </div>

    <h1>Payment successful!</h1>
    <p class="sub">Thank you! Your Pro plan is now active. You can use all templates and features.</p>

    <div class="receipt">
      <div class="row"><span>Plan</span><strong>Pro · Monthly</strong></div>
      <div class="row"><span>Amount paid</span><strong>{{ $amount }} {{ $currency }}</strong></div>
      <div class="row"><span>Account</span><strong>{{ auth()->user()->email }}</strong></div>
      <div class="row"><span>Valid until</span><strong>{{ $ends_at }}</strong></div>
      <div class="row"><span>Status</span><strong><span class="badge">Active</span></strong></div>
    </div>

    <a href="{{ route('templates.index') }}" class="btn btn-primary">Start using templates →</a>
    <a href="{{ route('user_dashboard') }}" class="btn btn-light">Go to dashboard</a>

    <div class="note">A payment receipt has been sent to your email by Stripe.</div>
  </main>

</div>

</body>
</html>