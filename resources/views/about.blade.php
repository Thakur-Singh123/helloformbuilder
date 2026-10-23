@extends('layouts.site')

@section('title', 'About Us | HelloFormBuilder')

@section('meta')
    <meta name="description" content="Discover HelloFormBuilder, an all-in-one platform for creating forms, embedding them on websites, managing invoices, assigning tasks, collaborating with teams and live chat.">
@endsection

@push('head')
<style>
html{scroll-behavior:smooth}
.about-page{
  --primary:#2F6FED; --primary-dark:#1D4FC4; --navy:#0e2a6b;
  --text:#334155; --muted:#64748b; --border:#d9e6fa; --light:#f5f8ff;
  color:var(--navy); line-height:1.7;
}
.about-page *{ box-sizing:border-box; }
.about-page h1,.about-page h2,.about-page h3{ font-weight:700; }
.about-page .ab-container{ width:min(1140px, calc(100% - 40px)); margin:0 auto; }

/* Buttons */
.about-page .btn{
  display:inline-flex; justify-content:center; align-items:center; gap:8px;
  padding:12px 21px; border:1px solid var(--border); border-radius:10px; background:#fff; color:var(--navy);
  text-decoration:none; font-size:14px; font-weight:750; transition:.2s ease;
}
.about-page .btn-primary{ color:#fff; background:var(--primary); border-color:var(--primary); }
.about-page .btn-primary:hover{ background:var(--primary-dark); }
.about-page .btn-secondary:hover{ background:var(--light); }

/* Hero */
.about-page .ab-hero{ padding:72px 0 70px; text-align:center; }
.about-page .eyebrow{
  display:inline-block; background:#dbeafe; color:var(--primary-dark); border-radius:30px;
  padding:6px 14px; font-size:12px; font-weight:800; letter-spacing:.6px; text-transform:uppercase;
}
.about-page h1{
  max-width:850px; margin:23px auto 20px; font-size:clamp(36px,5.2vw,60px);
  font-weight:800; letter-spacing:-2.5px; line-height:1.1;
}
.about-page .ab-hero p{ max-width:720px; margin:0 auto; color:var(--muted); font-size:17px; }
.about-page .hero-actions{ display:flex; justify-content:center; flex-wrap:wrap; gap:12px; margin-top:29px; }

/* Sections */
.about-page .section{ padding:78px 0; }
.about-page .section-light{ background:#fff; border-top:1px solid var(--border); border-bottom:1px solid var(--border); }
.about-page .section-heading{ max-width:760px; margin:0 auto 38px; text-align:center; }
.about-page .section-heading h2{ font-size:clamp(28px,3.5vw,39px); letter-spacing:-1.2px; line-height:1.2; margin:0 0 14px; }
.about-page .section-heading p{ color:var(--muted); font-size:15px; margin:0; }

/* Story */
.about-page .story-grid{ display:grid; grid-template-columns:1.05fr .95fr; align-items:center; gap:60px; }
.about-page .story-copy h2{ font-size:clamp(29px,3.5vw,39px); line-height:1.2; letter-spacing:-1.2px; margin:0 0 22px; }
.about-page .story-copy p{ color:var(--muted); font-size:15px; margin:0 0 17px; }
.about-page .story-visual{
  min-height:350px; border:1px solid #dce7ff; border-radius:23px; padding:25px;
  background:linear-gradient(145deg,#edf4ff,#fff); display:flex; align-items:center; justify-content:center;
}
.about-page .dashboard{
  width:100%; background:#fff; border:1px solid var(--border); border-radius:15px; padding:21px;
  box-shadow:0 20px 45px rgba(23,37,84,.08);
}
.about-page .dashboard-top{
  display:flex; justify-content:space-between; align-items:center; gap:12px;
  padding-bottom:17px; border-bottom:1px solid var(--border); font-size:13px; font-weight:800;
}
.about-page .status{ padding:4px 9px; color:#047857; background:#d1fae5; border-radius:20px; font-size:10px; }
.about-page .dashboard-grid{ display:grid; grid-template-columns:repeat(2,1fr); gap:12px; margin-top:17px; }
.about-page .dash-item{ padding:16px 13px; background:#f8faff; border:1px solid #edf1f8; border-radius:10px; }
.about-page .dash-icon{ font-size:22px; }
.about-page .dash-item strong{ display:block; margin-top:9px; font-size:13px; }
.about-page .dash-item span{ font-size:11px; color:var(--muted); }

/* Mission */
.about-page .mission-grid{ display:grid; grid-template-columns:repeat(2,1fr); gap:22px; }
.about-page .mission-card{ padding:30px; border:1px solid var(--border); background:#fff; border-radius:17px; }
.about-page .mission-icon{
  display:grid; place-items:center; width:49px; height:49px; border-radius:13px;
  background:#dbeafe; color:var(--primary); font-size:23px;
}
.about-page .mission-card h3{ margin:19px 0 9px; font-size:21px; }
.about-page .mission-card p{ color:var(--muted); font-size:14px; margin:0; }

/* Feature cards */
.about-page .features{ display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:20px; }
.about-page .feature-card{ padding:25px; border:1px solid var(--border); border-radius:15px; background:#fff; transition:.2s; }
.about-page .feature-card:hover{ border-color:#b8ccff; transform:translateY(-3px); box-shadow:0 12px 28px rgba(23,37,84,.05); }
.about-page .feature-icon{ display:grid; place-items:center; width:48px; height:48px; border-radius:13px; background:#eff6ff; font-size:23px; }
.about-page .feature-card h3{ margin:17px 0 8px; font-size:18px; }
.about-page .feature-card p{ margin:0; color:var(--muted); font-size:14px; }

/* Values */
.about-page .values{ display:grid; grid-template-columns:repeat(4,1fr); gap:17px; }
.about-page .value-card{ border-top:3px solid var(--primary); padding:22px 18px; background:var(--light); border-radius:5px 5px 12px 12px; }
.about-page .value-card h3{ font-size:16px; margin:0 0 9px; }
.about-page .value-card p{ font-size:13px; color:var(--muted); margin:0; }

/* CTA */
.about-page .cta-section{ padding:75px 0; }
.about-page .cta-box{
  text-align:center; padding:57px 25px; border-radius:23px; color:#fff;
  background:linear-gradient(130deg,#0e2a6b,#2F6FED);
}
.about-page .cta-box h2{ max-width:700px; margin:0 auto 14px; font-size:clamp(29px,4vw,43px); line-height:1.2; letter-spacing:-1.2px; color:#fff; }
.about-page .cta-box p{ max-width:620px; margin:0 auto 25px; color:#dbeafe; font-size:15px; }
.about-page .cta-box .btn{ color:var(--primary-dark); background:#fff; border-color:#fff; }

@media (max-width:850px){
  .about-page .story-grid{ grid-template-columns:1fr; gap:35px; }
  .about-page .story-visual{ min-height:310px; }
  .about-page .features{ grid-template-columns:repeat(2,1fr); }
  .about-page .values{ grid-template-columns:repeat(2,1fr); }
}
@media (max-width:560px){
  .about-page .ab-container{ width:calc(100% - 28px); }
  .about-page .ab-hero{ padding:48px 0; }
  .about-page h1{ letter-spacing:-1.5px; }
  .about-page .ab-hero p{ font-size:15px; }
  .about-page .section{ padding:52px 0; }
  .about-page .mission-grid,.about-page .features,.about-page .values{ grid-template-columns:1fr; }
  .about-page .mission-card,.about-page .feature-card{ padding:22px; }
  .about-page .story-visual{ padding:15px; min-height:270px; }
  .about-page .dashboard{ padding:14px; }
  .about-page .cta-box{ padding:40px 19px; }
}
</style>
@endpush

@section('content')
<div class="about-page">

<!-- HERO -->
<section class="site-hero">
  <div class="ab-container ab-hero">
    <span class="eyebrow">About HelloFormBuilder</span>
    <h1>Make everyday business work simpler.</h1>
    <p>
      HelloFormBuilder brings form creation, website embedding, invoice
      creation, task management, team collaboration and live chat together
      in one convenient platform.
    </p>
    <div class="hero-actions">
      <a href="{{ route('sign_up') }}" class="btn btn-primary">Get Started Free →</a>
      <a href="{{ route('templates.index') }}" class="btn btn-secondary">Browse Templates</a>
    </div>
  </div>
</section>

<!-- OUR STORY -->
<section class="section">
  <div class="ab-container story-grid">
    <div class="story-copy">
      <h2>One platform. More connected workflows.</h2>
      <p>
        Running a business often means switching between different tools
        to collect information, prepare invoices, assign work, coordinate
        with team members and communicate with customers.
      </p>
      <p>
        HelloFormBuilder brings these activities together in a single
        workspace, helping individuals and businesses organize everyday
        work with fewer disconnected processes.
      </p>
      <p>
        Whether you're getting started on your own or managing a growing
        team, our goal is to make your workflows easier to manage.
      </p>
    </div>

    <div class="story-visual">
      <div class="dashboard">
        <div class="dashboard-top">
          <span>HelloFormBuilder Workspace</span>
          <span class="status">All in one place</span>
        </div>
        <div class="dashboard-grid">
          <div class="dash-item">
            <div class="dash-icon">📝</div>
            <strong>Forms</strong>
            <span>Create and collect</span>
          </div>
          <div class="dash-item">
            <div class="dash-icon">🧾</div>
            <strong>Invoices</strong>
            <span>Manage billing</span>
          </div>
          <div class="dash-item">
            <div class="dash-icon">✓</div>
            <strong>Tasks</strong>
            <span>Assign and organize</span>
          </div>
          <div class="dash-item">
            <div class="dash-icon">👥</div>
            <strong>Team</strong>
            <span>Work together</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- MISSION AND VISION -->
<section class="section section-light">
  <div class="ab-container">
    <div class="section-heading">
      <h2>Our mission and vision</h2>
      <p>We believe business tools should make work simpler, not more complicated.</p>
    </div>

    <div class="mission-grid">
      <article class="mission-card">
        <div class="mission-icon">🎯</div>
        <h3>Our Mission</h3>
        <p>
          To make useful business tools accessible in one place, helping
          users create forms, organize invoices, manage tasks and collaborate
          with their teams through a straightforward experience.
        </p>
      </article>

      <article class="mission-card">
        <div class="mission-icon">🌐</div>
        <h3>Our Vision</h3>
        <p>
          To build a practical, connected workspace where businesses can
          manage more of their everyday operations without relying on a
          collection of disconnected applications.
        </p>
      </article>
    </div>
  </div>
</section>

<!-- WHAT WE OFFER -->
<section class="section">
  <div class="ab-container">
    <div class="section-heading">
      <h2>Everything working together</h2>
      <p>
        Discover the tools that help bring your forms and business
        workflows into one workspace.
      </p>
    </div>

    <div class="features">
      <article class="feature-card">
        <div class="feature-icon">📝</div>
        <h3>Form Creation</h3>
        <p>Create forms to collect information, receive submissions and organize the data your business needs.</p>
      </article>

      <article class="feature-card">
        <div class="feature-icon">🔗</div>
        <h3>Website Embedding</h3>
        <p>Embed forms on your website so visitors can submit information through your existing online presence.</p>
      </article>

      <article class="feature-card">
        <div class="feature-icon">🧾</div>
        <h3>Invoice Creation</h3>
        <p>Create invoices and keep your billing workflow organized alongside your other business activities.</p>
      </article>

      <article class="feature-card">
        <div class="feature-icon">✓</div>
        <h3>Task Management</h3>
        <p>Assign tasks and organize responsibilities to help keep work moving across your business.</p>
      </article>

      <article class="feature-card">
        <div class="feature-icon">👥</div>
        <h3>Team Management</h3>
        <p>Bring team members into your workflow and coordinate work through a shared platform.</p>
      </article>

      <article class="feature-card">
        <div class="feature-icon">💬</div>
        <h3>Live Chat</h3>
        <p>Access live chat with the Pro plan to support customer communication through your available chat features.</p>
      </article>
    </div>
  </div>
</section>

<!-- VALUES -->
<section class="section section-light">
  <div class="ab-container">
    <div class="section-heading">
      <h2>What guides us</h2>
      <p>The principles behind the experience we aim to create.</p>
    </div>

    <div class="values">
      <article class="value-card">
        <h3>Simplicity</h3>
        <p>Make everyday workflows straightforward and approachable.</p>
      </article>
      <article class="value-card">
        <h3>Practicality</h3>
        <p>Focus on tools that support real business activities.</p>
      </article>
      <article class="value-card">
        <h3>Connected Work</h3>
        <p>Bring related activities together in one convenient workspace.</p>
      </article>
      <article class="value-card">
        <h3>Continuous Improvement</h3>
        <p>Keep looking for ways to improve usability and the overall experience.</p>
      </article>
    </div>
  </div>
</section>

<!-- WHO IT IS FOR -->
<section class="section">
  <div class="ab-container">
    <div class="section-heading">
      <h2>Built for everyday business needs</h2>
      <p>HelloFormBuilder can support a range of workflows, depending on the features your business uses.</p>
    </div>

    <div class="features">
      <article class="feature-card">
        <div class="feature-icon">💼</div>
        <h3>Small Businesses</h3>
        <p>Organize forms, invoices and tasks without adding unnecessary complexity.</p>
      </article>
      <article class="feature-card">
        <div class="feature-icon">🚀</div>
        <h3>Freelancers</h3>
        <p>Collect client information and organize work through connected tools.</p>
      </article>
      <article class="feature-card">
        <div class="feature-icon">🤝</div>
        <h3>Growing Teams</h3>
        <p>Coordinate team activities and manage business workflows in one place.</p>
      </article>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="ab-container">
    <div class="cta-box">
      <h2>Ready to simplify your workflow?</h2>
      <p>
        Start with the Free plan, explore the tools and upgrade to Pro
        when you need access to the full feature set.
      </p>
      <a href="{{ route('sign_up') }}" class="btn">Get Started Free →</a>
    </div>
  </div>
</section>

</div>
@endsection