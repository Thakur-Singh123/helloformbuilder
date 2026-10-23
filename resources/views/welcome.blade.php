@extends('layouts.site')

@section('title', 'Home | HelloFormBuilder')

@section('plain', '1')

@push('head')
<style>
:root{
  --navy:#16274F;
  --navy-deep:#0F1B38;
  --blue:#2F6FED;
  --blue-deep:#1D4FC4;
  --gold:#F0A93A;
  --gold-deep:#D6900F;
  --ink:#1B2230;
  --steel:#5B6472;
  --mist:#F7F8FA;
  --line:#E2E5EA;
  --white:#fff;
  --font-display:'Sora', sans-serif;
  --font-body:'Inter', sans-serif;
  --font-mono:'JetBrains Mono', monospace;
}
*{box-sizing:border-box; margin:0; padding:0;}
body{ font-family:var(--font-body); color:var(--steel); line-height:1.6; }
h1,h2,h3,h4{ font-family:var(--font-display); color:var(--ink); line-height:1.15; letter-spacing:-0.02em; }
a{ color:inherit; text-decoration:none; }
img{ max-width:100%; }
.wrap{ max-width:1200px; margin:0 auto; padding:0 32px; }
.eyebrow{ font-family:var(--font-mono); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.08em; color:var(--blue); font-weight:500; display:inline-flex; align-items:center; gap:8px; margin-bottom:16px;}
.eyebrow::before{ content:""; width:18px; height:1px; background:var(--blue); }

.btn{ display:inline-flex; align-items:center; gap:8px; font-weight:600; padding:11px 22px; border-radius:7px; font-size:0.87rem; border:1px solid transparent; cursor:pointer; }
.btn-primary{ background:var(--blue); color:#fff; }
.btn-primary:hover{ background:var(--blue-deep); }
.btn-outline{ border-color:var(--line); color:var(--ink); background:#fff; }
.btn-outline:hover{ border-color:var(--blue); color:var(--blue); }

/* hero */
.hero{
  padding:80px 0;
  background:linear-gradient(115deg, #EEF3FC 0%, #F5F7FB 30%, #FBF7ED 65%, #EEF3FC 100%);
  position:relative; overflow:hidden;
}
.hero::before{
  content:""; position:absolute; inset:0;
  background:repeating-linear-gradient(90deg, rgba(22,39,79,0.03) 0 1px, transparent 1px 90px);
  pointer-events:none;
}
.hero-inner{ display:grid; grid-template-columns:1.15fr 0.85fr; gap:60px; align-items:center; position:relative; }
.hero h1{ font-size:3.6rem; margin-bottom:24px; text-align:left; }
.hero h1 .accent{ color:var(--blue); }
.hero p.lead{ font-size:1.15rem; color:var(--steel); max-width:50ch; margin:0 0 32px; text-align:left; }
.hero-actions{ display:flex; gap:14px; flex-wrap:wrap; margin-bottom:36px;}

.trust-badge{ display:flex; align-items:center; gap:16px; }
.trust-badge .stars{ flex-shrink:0; width:56px; height:56px; border-radius:50%; background:#fff; display:flex; align-items:center; justify-content:center; font-family:var(--font-display); font-weight:700; color:var(--blue); font-size:0.95rem; box-shadow:0 8px 20px -8px rgba(22,39,79,0.2);}
.trust-badge .text{ font-size:0.85rem; color:var(--ink); line-height:1.4;}
.trust-badge .text b{ display:block; }
.trust-badge .text a{ color:var(--blue); font-weight:600; font-size:0.82rem; }

/* signup card */
.signup-card{
  background:rgba(255,255,255,0.9); backdrop-filter:blur(6px);
  border-radius:20px; padding:36px 34px; box-shadow:0 32px 64px -24px rgba(22,39,79,0.22);
  border:1px solid rgba(255,255,255,0.6);
}
.signup-card h3{ text-align:center; font-size:1.3rem; margin-bottom:24px; }
.signup-field{
  width:100%; padding:14px 18px; margin-bottom:12px; border-radius:24px; border:1px solid var(--line);
  font-family:var(--font-body); font-size:0.92rem; background:#fff; color:var(--ink);
}
.signup-field::placeholder{ color:#9BA5A0; }
.signup-field:focus{ outline:none; border-color:var(--blue); box-shadow:0 0 0 3px rgba(47,111,237,0.14); }
.terms-row{ display:flex; align-items:flex-start; gap:8px; font-size:0.78rem; color:var(--steel); margin-bottom:20px; }
.terms-row input{ margin-top:3px; flex-shrink:0; }
.terms-row a{ color:var(--blue); font-weight:600; text-decoration:underline; }
.signup-submit{
  width:100%; background:var(--blue); color:#fff; font-weight:700; font-size:0.92rem; letter-spacing:0.03em;
  padding:15px; border:none; border-radius:24px; text-transform:uppercase; cursor:pointer; display:block; text-align:center;
}
.signup-submit:hover{ background:var(--blue-deep); }
.alert.alert-danger.py-2.mb-3.small{ color:red; display:flex; font-size:14px; }
.invalid-feedback{ color:red; font-size:14px; margin-bottom:10px; }

/* pipeline */
.pipeline-wrap{ background:transparent; padding:20px 0 100px; }
.pipeline{ display:grid; grid-template-columns:repeat(5,1fr); gap:0; position:relative; max-width:1080px; margin:0 auto; }
.pipeline::before{
  content:""; position:absolute; top:34px; left:60px; right:60px; height:2px;
  background:repeating-linear-gradient(90deg, var(--line) 0 6px, transparent 6px 12px);
  z-index:0;
}
.pnode{ position:relative; z-index:1; text-align:center; padding:0 10px; }
.pnode .circle{
  width:68px; height:68px; border-radius:14px; background:var(--white); border:2px solid var(--line);
  display:flex; align-items:center; justify-content:center; margin:0 auto 16px; font-size:1.5rem;
  transition:border-color .15s ease, transform .15s ease;
}
.pnode:hover .circle{ border-color:var(--blue); transform:translateY(-2px); }
.pnode .step{ font-family:var(--font-mono); font-size:0.68rem; color:var(--gold-deep); display:block; margin-bottom:6px; }
.pnode h4{ font-size:0.95rem; margin-bottom:4px; }
.pnode p{ font-size:0.8rem; color:var(--steel); max-width:16ch; margin:0 auto; }

/* logos strip */
.logos-strip{ background:transparent; padding:36px 0; border-top:1px solid var(--line); border-bottom:1px solid var(--line);}
.logos-strip .wrap{ display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:24px; }
.logos-strip .label{ font-size:0.78rem; color:#8B958E; text-transform:uppercase; letter-spacing:0.06em; font-family:var(--font-mono); flex-shrink:0;}
.logos-strip .logo-set{ display:flex; gap:40px; flex-wrap:wrap; font-family:var(--font-display); font-weight:700; color:var(--ink); font-size:1.05rem;}
.logo-set img{ max-width:140px; max-height:70px; width:auto; height:auto; object-fit:contain; display:block; }

/* section */
.section{ padding:96px 0; }
.section-head{ max-width:640px; margin:0 auto 56px; text-align:center; }
.section-head h2{ font-size:2.3rem; margin-bottom:14px; }
.section-head p{ font-size:1.02rem; color:var(--steel); }
.feature-list{ list-style:none; }
.feature-list li{ display:flex; align-items:flex-start; gap:10px; font-size:0.94rem; color:var(--ink); margin-bottom:12px; }
.feature-list li::before{ content:"✓"; color:var(--blue); font-weight:700; flex-shrink:0; }

/* showcase */
.showcase{ position:relative; overflow:hidden; padding:100px 0; }
.showcase.sc-a{ background:linear-gradient(135deg, #EAF0FC 0%, #F5F8FE 100%); }
.showcase.sc-b{ background:linear-gradient(135deg, #FBF3E5 0%, #FFFAF1 100%); }
.showcase.sc-c{ background:linear-gradient(135deg, #EEF1FA 0%, #F8F9FD 100%); }
.showcase.sc-d{ background:linear-gradient(135deg, #E9F0FB 0%, #F5F8FE 100%); }
.showcase .blob{ position:absolute; border-radius:50%; filter:blur(10px); opacity:0.5; z-index:0; }
.showcase.sc-a .blob{ width:340px; height:340px; background:var(--blue); opacity:0.10; top:-80px; right:-80px; }
.showcase.sc-b .blob{ width:300px; height:300px; background:var(--gold); opacity:0.16; bottom:-100px; left:-60px; }
.showcase.sc-c .blob{ width:320px; height:320px; background:var(--navy); opacity:0.08; top:-60px; left:40%; }
.showcase.sc-d .blob{ width:300px; height:300px; background:var(--blue); opacity:0.10; bottom:-80px; right:10%; }

.showcase-row{ display:grid; grid-template-columns:1fr 1fr; gap:64px; align-items:center; position:relative; z-index:1; }
.showcase-row.reverse{ direction:rtl; }
.showcase-row.reverse > *{ direction:ltr; }
.showcase-row .pill{
  display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,0.7); border:1px solid rgba(22,39,79,0.08);
  padding:6px 14px; border-radius:20px; font-family:var(--font-mono); font-size:0.72rem; color:var(--ink); margin-bottom:18px; font-weight:500;
}
.showcase-row .pill .n{ width:18px; height:18px; border-radius:50%; background:var(--blue); color:#fff; display:flex; align-items:center; justify-content:center; font-size:0.65rem; font-weight:700; font-family:var(--font-body);}
.showcase-row h3{ font-size:2.1rem; margin-bottom:18px; }
.showcase-row p{ font-size:1rem; margin-bottom:22px; max-width:44ch;}

.browser-mock{
  background:#fff; border-radius:16px; overflow:hidden;
  box-shadow:0 40px 80px -30px rgba(22,39,79,0.25), 0 0 0 1px rgba(22,39,79,0.04);
  transform:perspective(1400px) rotateY(-4deg) rotateX(1deg);
  transition:transform .3s ease;
}
.showcase-row.reverse .browser-mock{ transform:perspective(1400px) rotateY(4deg) rotateX(1deg); }
.browser-mock:hover{ transform:perspective(1400px) rotateY(0deg) rotateX(0deg); }
.browser-chrome{ display:flex; align-items:center; gap:6px; padding:12px 16px; background:#F3F4F7; border-bottom:1px solid var(--line); }
.browser-chrome span{ width:10px; height:10px; border-radius:50%; }
.browser-chrome .r{ background:#FF6B5B; } .browser-chrome .y{ background:#FFC24B; } .browser-chrome .g{ background:#3FCB7C; }
.browser-chrome .url{ margin-left:12px; background:#fff; border-radius:6px; padding:4px 12px; font-family:var(--font-mono); font-size:0.68rem; color:#8B958E; flex:1; }
.browser-body{ padding:26px; }

.form-field-mock{ border:1px solid var(--line); border-radius:8px; padding:12px 14px; margin-bottom:10px; display:flex; justify-content:space-between; align-items:center; font-size:0.85rem; background:#FCFCFD;}
.form-field-mock .tag{ font-family:var(--font-mono); font-size:0.68rem; background:rgba(47,111,237,0.08); color:var(--blue); padding:3px 8px; border-radius:5px;}
.form-field-mock.filled{ border-color:var(--blue); background:rgba(47,111,237,0.04); }

.invoice-mock .row{ display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px dashed var(--line); font-size:0.85rem; }
.invoice-mock .row:last-child{ border-bottom:none; font-weight:700; color:var(--ink); font-size:1.05rem; }
.invoice-mock .status{ display:inline-block; background:rgba(47,111,237,0.1); color:var(--blue); font-size:0.7rem; padding:3px 9px; border-radius:5px; font-family:var(--font-mono);}
.invoice-total-badge{ background:linear-gradient(135deg, var(--navy), var(--blue)); color:#fff; border-radius:10px; padding:16px 18px; margin-bottom:16px; display:flex; justify-content:space-between; align-items:center;}
.invoice-total-badge .amt{ font-family:var(--font-display); font-size:1.5rem; font-weight:700; }

.task-cols{ display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
.task-col .col-title{ font-size:0.72rem; text-transform:uppercase; letter-spacing:0.05em; color:#8B958E; margin-bottom:10px; font-weight:600;}
.task-card{ background:#FCFCFD; border:1px solid var(--line); border-radius:8px; padding:10px; margin-bottom:8px; font-size:0.78rem; color:var(--ink); border-left:3px solid var(--gold);}
.task-card.done{ border-left-color:var(--blue); opacity:0.75; }
.task-card .who{ display:flex; align-items:center; gap:6px; margin-top:8px; font-size:0.7rem; color:var(--steel);}
.task-card .avatar{ width:16px; height:16px; border-radius:50%; background:var(--blue); display:inline-block;}

.team-list .member{ display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--line); font-size:0.85rem;}
.team-list .member:last-child{ border-bottom:none;}
.team-list .who{ display:flex; align-items:center; gap:10px;}
.team-list .avatar{ width:28px; height:28px; border-radius:50%; background:linear-gradient(135deg, var(--blue), var(--navy)); flex-shrink:0;}
.team-list .role{ font-family:var(--font-mono); font-size:0.68rem; color:#8B958E; background:#F3F4F7; padding:3px 9px; border-radius:12px;}

/* stats */
.stats-band{ background:var(--navy); color:#fff; padding:56px 0;}
.stats-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:24px; text-align:center;}
.stat .num{ font-family:var(--font-display); font-size:2.4rem; font-weight:700; color:var(--gold);}
.stat .label{ font-size:0.8rem; color:#AEBBDB; text-transform:uppercase; letter-spacing:0.05em; margin-top:6px;}

/* testimonials */
.testi-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:22px; }
.testi{ background:var(--white); border:1px solid var(--line); border-radius:12px; padding:26px;}
.testi .quote-mark{ font-family:var(--font-display); font-size:2.2rem; color:var(--gold-deep); line-height:1; display:block; margin-bottom:10px;}
.testi p{ font-size:0.92rem; color:var(--ink); margin-bottom:18px;}
.testi .who{ display:flex; align-items:center; gap:10px;}
.testi .avatar{ width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg, var(--blue), var(--navy));}
.testi .who .name{ font-size:0.85rem; font-weight:600; color:var(--ink);}
.testi .who .role{ font-size:0.78rem; color:var(--steel);}

/* cta */
.cta-band{ background:var(--navy-deep); color:#fff; padding:80px 0; text-align:center;}
.cta-band h2{ color:#fff; font-size:2.2rem; margin-bottom:16px;}
.cta-band p{ color:#A9B2AB; max-width:44ch; margin:0 auto 32px;}
.cta-actions{ display:flex; gap:14px; justify-content:center; flex-wrap:wrap;}

.btn{ font-family:inherit; }

.demo-modal{
  position:fixed; inset:0; z-index:100; display:none; align-items:center; justify-content:center;
  padding:20px; background:rgba(15,27,56,.75);
}
.demo-modal.open{ display:flex; }
.demo-box{
  position:relative; width:min(960px,100%); background:#000; border-radius:14px; overflow:hidden;
  box-shadow:0 30px 80px rgba(0,0,0,.5);
}
.demo-box video{ display:block; width:100%; max-height:80vh; background:#000; object-fit:contain; }
.demo-close{
  position:absolute; top:8px; right:10px; z-index:2; width:36px; height:36px; border:0;
  border-radius:50%; background:rgba(255,255,255,.92); color:#0f1b38; font-size:22px; line-height:1; cursor:pointer;
}

@media (max-width: 960px){
  .showcase-row, .showcase-row.reverse{ grid-template-columns:1fr; direction:ltr; }
  .browser-mock, .showcase-row.reverse .browser-mock{ transform:none; }
  .hero-inner{ grid-template-columns:1fr; }
  .pipeline{ grid-template-columns:repeat(2,1fr); gap:32px; }
  .pipeline::before{ display:none; }
  .stats-grid, .testi-grid{ grid-template-columns:1fr; }
  .hero h1{ font-size:2.2rem; }
}
</style>
@endpush

@section('content')

<section class="hero">
  <div class="wrap hero-inner">
    <div>
      <span class="eyebrow">Forms · Invoices · Tasks · Team</span>
      <h1>Turn every form reply into <span class="accent">finished work</span>.</h1>
      <p class="lead">Build a form, embed it anywhere, and watch each response flow straight into an invoice, a task, and the right person on your team — automatically.</p>
      <div class="hero-actions">
        <a href="{{ route('sign_up') }}" class="btn btn-primary">Start Free →</a>
      <button type="button" class="btn btn-outline" onclick="openDemo()">▶ Watch Demo</button>
      </div>
      <div class="trust-badge">
        <div class="stars">4.9<br>★</div>
        <div class="text"><b>Loved by 1,000+ teams</b>Rated 4.9/5 by agencies and freelancers across the US · <a href="#">See reviews »</a></div>
      </div>
    </div>

    <div class="signup-card">

      <h3>Get started!</h3>

      @if($errors->any())
        <div class="alert alert-danger py-2 mb-3 small" role="alert">
          @foreach($errors->all() as $err)
            <div>{{ $err }}</div>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('user_register') }}" id="registerForm">
        @csrf

        <input type="text" class="signup-field" id="first_name" name="first_name" placeholder="First Name" value="{{ old('first_name') }}" required>

        <input type="text" class="signup-field" id="last_name" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}" required>

        <input type="email" class="signup-field {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" name="email" placeholder="Work Email" value="{{ old('email') }}" required>

        @error('email')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        <input type="password" class="signup-field" id="password" name="password" placeholder="Password" minlength="6" required>

        <input type="password" class="signup-field" id="confirm_password" name="confirm_password" placeholder="Confirm Password" minlength="6" required>

        <label class="terms-row">
          <input type="checkbox" id="registerTerms" name="registerTerms" value="1" required>
          <span>
            I agree to the
            <a href="#">Terms of Service</a>
            and
            <a href="#">Privacy Policy</a>.
          </span>
        </label>

        <button type="submit" class="signup-submit">Get Started</button>

      </form>
    </div>
  </div>
</section>

<div class="pipeline-wrap">
  <div class="wrap">
    <div class="pipeline">
      <div class="pnode"><span class="step">01</span><div class="circle">📝</div><h4>Build Form</h4><p>Drag-and-drop, embed anywhere</p></div>
      <div class="pnode"><span class="step">02</span><div class="circle">📩</div><h4>Get Response</h4><p>Instant email &amp; dashboard alert</p></div>
      <div class="pnode"><span class="step">03</span><div class="circle">🧾</div><h4>Send Invoice</h4><p>One click from the reply</p></div>
      <div class="pnode"><span class="step">04</span><div class="circle">✅</div><h4>Assign Task</h4><p>Routed to the right teammate</p></div>
      <div class="pnode"><span class="step">05</span><div class="circle">🏁</div><h4>Mark Complete</h4><p>Team closes the loop</p></div>
    </div>
  </div>
</div>

<div class="logos-strip">
  <div class="wrap">
    <span class="label">Our Valuable Clients:</span>

    <div class="logo-set">
      <img src="{{ url('public/uploads/clients/c1.jpeg') }}" alt="Bookmycleaning">
      <img src="{{ url('public/uploads/clients/c2.jpeg') }}" alt="I Live to Paint">
      <img src="{{ url('public/uploads/clients/c3.jpeg') }}" alt="Creative Contractors OKGN Ltd.">
      <img src="{{ url('public/uploads/clients/c4.jpeg') }}" alt="AGSC Security Care">
      <img src="{{ url('public/uploads/clients/c5.jpeg') }}" alt="Security Guarding London">
      <img src="{{ url('public/uploads/clients/c6.jpeg') }}" alt="Berkshire Heat">
    </div>
  </div>
</div>

<section class="section" style="padding-bottom:0;">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow" style="justify-content:center;">How It Works</span>
      <h2>One form. A complete workflow.</h2>
      <p>Everything downstream of a form reply — billing, task routing, team visibility — happens without leaving HelloFormBuilder.</p>
    </div>
  </div>
</section>

<section class="showcase sc-a">
  <div class="blob"></div>
  <div class="wrap showcase-row">
    <div>
      <span class="pill"><span class="n">1</span>FORM BUILDER</span>
      <h3>Build forms your customers actually finish</h3>
      <p>Drag-and-drop fields, conditional logic, and a style editor that matches your site — then embed with a single line of code.</p>
      <ul class="feature-list">
        <li>20+ field types, including file upload and signature</li>
        <li>Conditional logic and multi-step forms</li>
        <li>Embed via script tag, iframe, or shareable link</li>
      </ul>
      <a href="{{ route('templates.index') }}" class="btn btn-primary">Explore Form Builder →</a>
    </div>
    <div class="browser-mock">
      <div class="browser-chrome"><span class="r"></span><span class="y"></span><span class="g"></span><div class="url">yoursite.com/contact</div></div>
      <div class="browser-body">
        <div class="form-field-mock filled"><span>Full Name</span><span class="tag">text</span></div>
        <div class="form-field-mock filled"><span>Email Address</span><span class="tag">email</span></div>
        <div class="form-field-mock"><span>Project Budget</span><span class="tag">select</span></div>
        <div class="form-field-mock"><span>Upload Brief</span><span class="tag">file</span></div>
        <div class="form-field-mock"><span>Message</span><span class="tag">textarea</span></div>
      </div>
    </div>
  </div>
</section>

<section class="showcase sc-b">
  <div class="blob"></div>
  <div class="wrap showcase-row reverse">
    <div>
      <span class="pill"><span class="n">2</span>INVOICING</span>
      <h3>Reply comes in — invoice goes out</h3>
      <p>Turn any form response into a professional invoice in one click, pre-filled with the submitter's details. Track paid, pending, and overdue in one place.</p>
      <ul class="feature-list">
        <li>Auto-filled from form response data</li>
        <li>Send by email with online payment link</li>
        <li>Real-time paid / pending / overdue status</li>
      </ul>
      <a href="#" class="btn btn-primary">Explore Invoicing →</a>
    </div>
    <div class="browser-mock">
      <div class="browser-chrome"><span class="r"></span><span class="y"></span><span class="g"></span><div class="url">app.helloformbuilder.com/invoices/2041</div></div>
      <div class="browser-body">
        <div class="invoice-total-badge"><span>INV-2041</span><span class="amt">$450.00</span></div>
        <div class="invoice-mock">
          <div class="row"><span>Client</span><span>Jordan Smith</span></div>
          <div class="row"><span>Service</span><span>Website Consultation</span></div>
          <div class="row"><span>Due Date</span><span>Aug 20, 2026</span></div>
          <div class="row"><span>Status</span><span class="status">Sent</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="showcase sc-c">
  <div class="blob"></div>
  <div class="wrap showcase-row">
    <div>
      <span class="pill"><span class="n">3</span>TASKS &amp; TEAM</span>
      <h3>Every response becomes an owned task</h3>
      <p>Auto-create a task from a form reply, assign it to a team member, and track it through to done — no separate task tool needed.</p>
      <ul class="feature-list">
        <li>Auto-create tasks from form submissions</li>
        <li>Assign to any team member with one click</li>
        <li>Assignees mark tasks complete themselves</li>
      </ul>
      <a href="#" class="btn btn-primary">Explore Tasks →</a>
    </div>
    <div class="browser-mock">
      <div class="browser-chrome"><span class="r"></span><span class="y"></span><span class="g"></span><div class="url">app.helloformbuilder.com/tasks</div></div>
      <div class="browser-body">
        <div class="task-cols">
          <div class="task-col"><div class="col-title">To Do</div>
            <div class="task-card">Call new lead — Jordan S.<div class="who"><span class="avatar"></span>Priya</div></div>
          </div>
          <div class="task-col"><div class="col-title">In Progress</div>
            <div class="task-card">Prep proposal — Fernbridge<div class="who"><span class="avatar"></span>Marcus</div></div>
          </div>
          <div class="task-col"><div class="col-title">Done</div>
            <div class="task-card done">Send invoice — INV-2038<div class="who"><span class="avatar"></span>Priya</div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="showcase sc-d">
  <div class="blob"></div>
  <div class="wrap showcase-row reverse">
    <div>
      <span class="pill"><span class="n">4</span>TEAM</span>
      <h3>Add your team, keep everyone in sync</h3>
      <p>Invite teammates, control what they can see, and give every response a clear owner from the moment it lands.</p>
      <ul class="feature-list">
        <li>Unlimited team members on paid plans</li>
        <li>Per-member task and form visibility</li>
        <li>Activity log for every form, invoice and task</li>
      </ul>
      <a href="#" class="btn btn-primary">Explore Team Plans →</a>
    </div>
    <div class="browser-mock">
      <div class="browser-chrome"><span class="r"></span><span class="y"></span><span class="g"></span><div class="url">app.helloformbuilder.com/team</div></div>
      <div class="browser-body">
        <div class="team-list">
          <div class="member"><span class="who"><span class="avatar"></span>Priya Anand</span><span class="role">Admin</span></div>
          <div class="member"><span class="who"><span class="avatar"></span>Marcus Lee</span><span class="role">Member</span></div>
          <div class="member"><span class="who"><span class="avatar"></span>Sofia Ruiz</span><span class="role">Member</span></div>
          <div class="member"><span class="who"><span class="avatar"></span>Devon Clarke</span><span class="role">Billing</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="stats-band">
  <div class="wrap stats-grid">
    <div class="stat"><div class="num">12K+</div><div class="label">Forms Created</div></div>
    <div class="stat"><div class="num">2.4M</div><div class="label">Responses Collected</div></div>
    <div class="stat"><div class="num">$8.1M</div><div class="label">Invoiced Through HFB</div></div>
    <div class="stat"><div class="num">99.9%</div><div class="label">Uptime</div></div>
  </div>
</div>

<section class="section">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow" style="justify-content:center;">Testimonials</span>
      <h2>Teams who closed the loop</h2>
    </div>
    <div class="testi-grid">
      <div class="testi"><span class="quote-mark">&ldquo;</span><p>We replaced three separate tools with HelloFormBuilder. A lead fills our contact form and by the time I open Slack, the invoice is already half-drafted.</p><div class="who"><span class="avatar"></span><div><div class="name">Priya Anand</div><div class="role">Founder, Northwind Studio — Austin, TX</div></div></div></div>
      <div class="testi"><span class="quote-mark">&ldquo;</span><p>Task assignment from form replies changed how our support team works. Nothing falls through anymore.</p><div class="who"><span class="avatar"></span><div><div class="name">Marcus Lee</div><div class="role">Ops Lead, Brightpath — Denver, CO</div></div></div></div>
      <div class="testi"><span class="quote-mark">&ldquo;</span><p>Simple to set up, and the invoicing alone paid for the subscription in the first week.</p><div class="who"><span class="avatar"></span><div><div class="name">Sofia Ruiz</div><div class="role">Freelance Consultant — Miami, FL</div></div></div></div>
    </div>
  </div>
</section>

<section class="cta-band">
  <div class="wrap">
    <h2>Ready to close the loop on every form reply?</h2>
    <p>Join teams turning form responses into invoices and finished tasks — automatically.</p>
    <div class="cta-actions">
      <a href="{{ route('sign_up') }}" class="btn btn-primary">Start Free →</a>
      <a href="#" class="btn" style="border:1px solid #444; color:#fff;">Book a Demo</a>
    </div>
  </div>
</section>
<div class="demo-modal" id="demoModal" role="dialog" aria-modal="true" aria-label="Product demo video">
  <div class="demo-box">
    <button type="button" class="demo-close" onclick="closeDemo()" aria-label="Close">&times;</button>
    <video id="demoVideo" controls playsinline preload="none">
      <source src="{{ url('public/uploads/demo/demo.mp4') }}" type="video/mp4">
      Your browser does not support the video tag.
    </video>
  </div>
</div>
@endsection
@push('scripts')
<script>
(function () {
  var modal = document.getElementById('demoModal');
  var video = document.getElementById('demoVideo');

  window.openDemo = function () {
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
    video.play().catch(function () {});
  };

  window.closeDemo = function () {
    video.pause();
    video.currentTime = 0;
    modal.classList.remove('open');
    document.body.style.overflow = '';
  };

  modal.addEventListener('click', function (e) { if (e.target === modal) closeDemo(); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeDemo(); });
})();
</script>
@endpush