@extends('layouts.site')

@section('title', 'Pricing Plans | HelloFormBuilder')

@section('meta')
    <meta name="description" content="Choose the right HelloFormBuilder plan. Start free or upgrade to Pro for $49 per month.">
@endsection

@push('head')
<style>
.pricing-page{
  --primary:#2F6FED;
  --primary-dark:#1D4FC4;
  --text:#0e2a6b;
  --muted:#64748b;
  --border:#d9e6fa;
  color:var(--text);
  line-height:1.6;
}
.pricing-page *{ box-sizing:border-box; }
.pricing-page h1,
.pricing-page h2,
.pricing-page h3{ font-weight:700; }

.pricing-page .pr-container{ width:min(1100px, calc(100% - 40px)); margin:0 auto; }

.pricing-page .pr-hero{ text-align:center; padding:64px 0 52px; }
.pricing-page .eyebrow{
  display:inline-block; padding:6px 14px; border-radius:30px;
  background:#dbeafe; color:var(--primary-dark); font-size:13px; font-weight:700;
}
.pricing-page h1{
  font-size:clamp(34px, 5vw, 54px); font-weight:800; letter-spacing:-2px; line-height:1.12; margin:22px 0 16px;
}
.pricing-page .pr-hero p{ max-width:600px; margin:0 auto; color:var(--muted); font-size:17px; }

.pricing-page .pr-plans{ padding-top:48px; }
.pricing-page .pricing-grid{
  display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:25px;
  max-width:820px; margin:0 auto 55px; align-items:stretch;
}
.pricing-page .plan{
  position:relative; background:#fff; padding:34px; border:1px solid var(--border);
  border-radius:22px; box-shadow:0 10px 35px rgba(31,57,110,.045);
}
.pricing-page .plan.pro{ border:2px solid var(--primary); box-shadow:0 18px 50px rgba(47,111,237,.14); }
.pricing-page .plan-label{ color:var(--muted); font-weight:600; font-size:14px; }
.pricing-page .popular{
  display:inline-block; color:#fff; background:var(--primary); padding:5px 12px;
  border-radius:20px; font-size:12px; font-weight:800; margin-bottom:14px;
}
.pricing-page .plan h2{ margin:7px 0 0; font-size:25px; letter-spacing:-.5px; }
.pricing-page .price{ display:flex; align-items:baseline; gap:7px; margin:14px 0 2px; }
.pricing-page .price strong{ font-size:47px; line-height:1.25; letter-spacing:-2px; }
.pricing-page .price span,
.pricing-page .billing{ color:var(--muted); font-size:14px; }
.pricing-page .plan-description{ color:var(--muted); font-size:14px; margin:5px 0 24px; }
.pricing-page .plan hr{ border:0; border-top:1px solid var(--border); margin:22px 0; height:0; }
.pricing-page .feature-title{
  font-size:13px; color:var(--muted); font-weight:700; text-transform:uppercase; letter-spacing:.6px; margin-bottom:15px;
}
.pricing-page .features{ list-style:none; padding:0; margin:0; }
.pricing-page .features li{ display:flex; align-items:flex-start; gap:11px; margin:14px 0; font-size:14px; }
.pricing-page .check{
  display:inline-grid; flex-shrink:0; place-items:center; width:21px; height:21px;
  color:var(--primary); background:#eff6ff; border-radius:50%; font-size:13px; font-weight:800;
}
.pricing-page .branding-note{
  margin-top:22px; background:#f1f5f9; border:1px solid var(--border); padding:13px;
  border-radius:10px; color:#475569; font-size:12px; text-align:center;
}
.pricing-page .branding-note strong{ color:#334155; }
.pricing-page .pr-btn{
  display:flex; justify-content:center; align-items:center; width:100%; min-height:49px;
  margin-top:25px; padding:12px 18px; border:1px solid var(--primary); border-radius:10px;
  background:#fff; color:var(--primary); font-size:15px; font-weight:700; text-decoration:none;
  cursor:pointer; transition:.2s ease;
}
.pricing-page .pr-btn:hover{ background:#eff6ff; }
.pricing-page .pr-btn-primary{ color:#fff; background:var(--primary); }
.pricing-page .pr-btn-primary:hover{ background:var(--primary-dark); }
.pricing-page .trust{ text-align:center; color:var(--muted); font-size:13px; padding-bottom:60px; }

.pricing-page .benefits{ background:#fff; padding:65px 0; border-top:1px solid var(--border); }
.pricing-page .benefits h2{ text-align:center; font-size:30px; letter-spacing:-1px; margin:0 0 30px; }
.pricing-page .benefit-grid{ display:grid; grid-template-columns:repeat(3, 1fr); gap:22px; }
.pricing-page .benefit{ padding:25px; border:1px solid var(--border); border-radius:16px; }
.pricing-page .benefit-icon{ font-size:25px; }
.pricing-page .benefit h3{ margin:12px 0 6px; font-size:18px; }
.pricing-page .benefit p{ color:var(--muted); font-size:14px; margin:0; }
.pricing-page .badge-row{ display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:14px; }
.pricing-page .badge-row .popular{ margin-bottom:0; }
.pricing-page .status-badge{ display:inline-block; padding:5px 12px; border-radius:20px; font-size:12px; font-weight:800; }
.pricing-page .status-badge.is-active{ background:#dcfce7; color:#15803d; }
.pricing-page .status-badge.is-expired{ background:#fee2e2; color:#b91c1c; }

.pricing-page .faq{ max-width:760px; margin:0 auto; padding:65px 0; }
.pricing-page .faq h2{ text-align:center; font-size:30px; margin:0 0 28px; }
.pricing-page details{ background:#fff; border:1px solid var(--border); border-radius:12px; margin-bottom:12px; padding:17px 20px; }
.pricing-page summary{ cursor:pointer; font-weight:700; }
.pricing-page details p{ color:var(--muted); font-size:14px; margin:10px 0 0; }

@media (max-width:650px){
  .pricing-page .pr-container{ width:min(100% - 28px, 520px); }
  .pricing-page .pr-hero{ padding:44px 0 36px; }
  .pricing-page .pricing-grid{ grid-template-columns:1fr; gap:20px; }
  .pricing-page .plan{ padding:27px 23px; }
  .pricing-page .benefit-grid{ grid-template-columns:1fr; }
  .pricing-page h1{ letter-spacing:-1.3px; }
}
</style>
@endpush

@section('content')
<div class="pricing-page">
  @if(session('error'))
    <div class="pr-container" style="padding-top:24px">
      <div style="background:#fff7ed;border:1px solid #fed7aa;color:#9a3412;padding:14px 18px;border-radius:12px;font-size:14px;text-align:center">
        {{ session('error') }}
      </div>
    </div>
  @endif
  <section class="site-hero">
    <div class="pr-container pr-hero">
      <span class="eyebrow">Simple, transparent pricing</span>
      <h1>Everything your business<br>needs in one place.</h1>
      <p>Create forms, manage invoices, assign tasks, and work with your team. Start for free and upgrade when you're ready.</p>
    </div>
  </section>

  <section class="pr-container pr-plans" id="pricing">
    <div class="pricing-grid">

      <!-- FREE PLAN -->
      <article class="plan">
        <span class="plan-label">Get started</span>
        <h2>Free</h2>
        <div class="price"><strong>$0</strong></div>
        <div class="billing">Forever free</div>
        <p class="plan-description">Essential tools to get your workflow started.</p>

        @auth
          <a href="{{ route('templates.index') }}" class="pr-btn">Browse Templates</a>
        @else
          <a href="{{ route('sign_up') }}" class="pr-btn">Get Started Free</a>
        @endauth

        <hr>
        <div class="feature-title">What's included</div>
        <ul class="features">
          <li><span class="check">✓</span><span>Create up to 3 forms</span></li>
          <li><span class="check">✓</span><span>1 invoice creation</span></li>
          <li><span class="check">✓</span><span>Add 1 team member</span></li>
          <li><span class="check">✓</span><span>1 task assignment</span></li>
          <li><span class="check">✓</span><span>Basic form builder</span></li>
          <li><span class="check">✓</span><span>Form submissions</span></li>
          <li><span class="check">✓</span><span>Essential form management</span></li>
        </ul>

        <div class="branding-note">
          <strong>Made with HelloFormBuilder</strong><br>
          Branding displayed on published forms
        </div>
      </article>

      <!-- PRO PLAN -->
      <article class="plan pro">
      <div class="badge-row">
          <span class="popular">ALL FEATURES INCLUDED</span>
          @auth
            @if(auth()->user()->isPro())
              <span class="status-badge is-active">● Active</span>
            @elseif(auth()->user()->isExpired())
              <span class="status-badge is-expired">● Expired</span>
            @endif
          @endauth
        </div>
        <h2>Pro</h2>
        <div class="price"><strong>$49</strong><span>/ month</span></div>
        <div class="billing">Billed monthly · Cancel anytime</div>
        <p class="plan-description">Everything you need to grow and manage your business.</p>
        @auth
          @if(auth()->user()->isPro())
            <span class="pr-btn" style="cursor:default;opacity:.7">✓ Active until {{ auth()->user()->plan_ends_at->format('d M Y') }}</span>
          @elseif(auth()->user()->isExpired())
            <a href="{{ route('plans.checkout.page') }}" class="pr-btn pr-btn-primary">Renew your plan →</a>
          @else
            <a href="{{ route('plans.checkout.page') }}" class="pr-btn pr-btn-primary">Upgrade to Pro →</a>
          @endif
        @else
          <a href="{{ route('sign_up', ['plan' => 'pro']) }}" class="pr-btn pr-btn-primary">Upgrade to Pro →</a>
        @endauth
        @auth
        @if(auth()->user()->isExpired())
          <p style="color:#b91c1c;font-size:13px;margin:10px 0 0;text-align:center">
            Your plan expired{{ auth()->user()->plan_ends_at ? ' on '.auth()->user()->plan_ends_at->format('d M Y') : '' }}. Please renew your plan.
          </p>
        @endif
        @endauth
        <hr>
        <div class="feature-title">Everything in one plan</div>
        <ul class="features">
          <li><span class="check">✓</span><span>All form builder features</span></li>
          <li><span class="check">✓</span><span>Invoice creation</span></li>
          <li><span class="check">✓</span><span>Team member management</span></li>
          <li><span class="check">✓</span><span>Task assignments</span></li>
          <li><span class="check">✓</span><span>Live chat included</span></li>
          <li><span class="check">✓</span><span>Remove HelloFormBuilder branding</span></li>
          <li><span class="check">✓</span><span>Access to all Pro features</span></li>
        </ul>

        <div class="branding-note" style="background:#eff6ff;border-color:#bfdbfe;color:#1d4ed8">
          <strong>✓ Your brand, your forms</strong><br>
          No “Made with HelloFormBuilder” badge
        </div>
      </article>

    </div>
    <div class="trust">Simple pricing · Secure forms · Upgrade whenever you need</div>
  </section>

  <section class="benefits">
    <div class="pr-container">
      <h2>More than just a form builder</h2>
      <div class="benefit-grid">
        <div class="benefit">
          <div class="benefit-icon">📝</div>
          <h3>Create forms</h3>
          <p>Build forms to collect information and manage submissions in one convenient workspace.</p>
        </div>
        <div class="benefit">
          <div class="benefit-icon">🧾</div>
          <h3>Manage invoices</h3>
          <p>Keep invoice creation and business workflows organized alongside your forms.</p>
        </div>
        <div class="benefit">
          <div class="benefit-icon">👥</div>
          <h3>Work as a team</h3>
          <p>Coordinate team members and task assignments with tools designed for your workflow.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="pr-container">
    <div class="faq">
      <h2>Frequently asked questions</h2>

      <details>
        <summary>Is HelloFormBuilder free?</summary>
        <p>Yes. The Free plan includes 3 forms, 1 invoice creation, 1 team member, and 1 task assignment.</p>
      </details>

      <details>
        <summary>What does the Pro plan cost?</summary>
        <p>The Pro plan costs $49 per month and includes access to all available features.</p>
      </details>

      <details>
        <summary>Will my free forms show HelloFormBuilder branding?</summary>
        <p>Yes. Published forms on the Free plan display a “Made with HelloFormBuilder” badge. Upgrade to Pro to remove it.</p>
      </details>

      <details>
        <summary>Does Pro include live chat?</summary>
        <p>Yes. Live chat is included in the Pro plan. It is not available on the Free plan.</p>
      </details>

      <details>
        <summary>Can I upgrade later?</summary>
        <p>Yes. You can upgrade from Free to Pro when you need additional features.</p>
      </details>
    </div>
  </section>

</div>
@endsection