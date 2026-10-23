@extends('layouts.site')

@section('title', 'HelloFormBuilder vs Alternatives | Compare Tools')

@section('meta')
    <meta name="description" content="Compare HelloFormBuilder with Jotform, Typeform, Google Forms, Zoho Forms and Fillout. Explore forms, embedding, invoices, tasks, teams and live chat.">
@endsection

@push('head')
<style>
html{scroll-behavior:smooth}
.why-page{
  --blue:#2F6FED; --blue-dark:#1D4FC4; --dark:#0e2a6b; --muted:#64748b;
  --line:#d9e6fa; --green:#059669;
  color:var(--dark); line-height:1.65;
}
.why-page *{ box-sizing:border-box; }
.why-page h1,.why-page h2,.why-page h3{ font-weight:700; }
.why-page .wp-container{ width:min(1160px, calc(100% - 36px)); margin:0 auto; }

.why-page .wp-hero{ text-align:center; padding:64px 0 48px; }
.why-page .eyebrow{
  display:inline-block; background:#dbeafe; color:#1d4ed8; border-radius:30px;
  padding:5px 12px; font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:.5px;
}
.why-page h1{ font-size:clamp(35px,5vw,55px); font-weight:800; letter-spacing:-2px; line-height:1.12; margin:20px 0 16px; }
.why-page .wp-hero p{ max-width:720px; margin:0 auto; color:var(--muted); font-size:16px; }

.why-page .btn{
  display:inline-flex; justify-content:center; align-items:center; padding:12px 19px;
  border-radius:9px; border:1px solid var(--line); background:#fff; color:var(--dark);
  text-decoration:none; font-size:14px; font-weight:750; cursor:pointer;
}
.why-page .btn.primary{ background:var(--blue); color:#fff; border-color:var(--blue); }
.why-page .btn.primary:hover{ background:var(--blue-dark); }
.why-page .actions{ display:flex; justify-content:center; gap:12px; flex-wrap:wrap; margin-top:25px; }

.why-page .section{ padding:62px 0; }
.why-page .soft{ background:#fff; border-top:1px solid var(--line); border-bottom:1px solid var(--line); }
.why-page .heading{ text-align:center; margin-bottom:28px; }
.why-page .heading h2{ font-size:clamp(27px,3.5vw,35px); letter-spacing:-1px; line-height:1.2; margin:0 0 9px; }
.why-page .heading p{ color:var(--muted); font-size:14px; margin:0; }

.why-page .competitors{ display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:14px; }
.why-page .competitor{
  display:flex; align-items:center; gap:12px; text-align:left; width:100%;
  padding:16px; background:#fff; border:1px solid var(--line); border-radius:13px;
  cursor:pointer; color:var(--dark); font:inherit; transition:.18s;
}
.why-page .competitor:hover{ border-color:#a8c2ff; transform:translateY(-1px); }
.why-page .competitor.active{ border:2px solid var(--blue); background:#f1f6ff; padding:15px; box-shadow:0 5px 18px rgba(47,111,237,.1); }
.why-page .brand{
  width:42px; height:42px; flex-shrink:0; border-radius:11px; background:#eff6ff; color:#1d4ed8;
  display:grid; place-items:center; font-size:19px; font-weight:850;
}
.why-page .competitor.active .brand{ background:var(--blue); color:#fff; }
.why-page .competitor strong{ display:block; font-size:14px; }
.why-page .competitor small{ display:block; font-size:11px; color:var(--muted); margin-top:2px; }
.why-page .arrow{ margin-left:auto; color:var(--blue); font-size:19px; }

.why-page .comparison{
  margin-top:30px; background:#fff; border:1px solid var(--line); border-radius:17px;
  overflow:hidden; box-shadow:0 10px 35px rgba(23,37,84,.03);
}
.why-page .comparison-head{ padding:25px 27px; background:linear-gradient(120deg,#f3f7ff,#fff); border-bottom:1px solid var(--line); }
.why-page .comparison-head h2{ margin:0 0 5px; font-size:25px; letter-spacing:-.6px; }
.why-page .comparison-head p{ margin:0; color:var(--muted); font-size:13px; }
.why-page .plan-pills{ display:flex; gap:8px; flex-wrap:wrap; margin-top:13px; }
.why-page .plan-pills span{ background:#fff; border:1px solid var(--line); padding:5px 10px; border-radius:20px; font-size:11px; font-weight:700; }

.why-page .table-scroll{ overflow-x:auto; }
.why-page table{ width:100%; border-collapse:collapse; min-width:620px; }
.why-page th,.why-page td{ padding:14px 19px; text-align:left; border-bottom:1px solid var(--line); font-size:13px; vertical-align:middle; }
.why-page th{ font-size:12px; text-transform:uppercase; letter-spacing:.4px; color:#475569; background:#fff; }
.why-page th:nth-child(2),.why-page td:nth-child(2){ background:#f1f6ff; }
.why-page th:nth-child(2){ color:#1d4ed8; }
.why-page th:first-child{ width:31%; }
.why-page .group td{ background:#f1f5f9!important; font-size:11px; font-weight:850; text-transform:uppercase; letter-spacing:.6px; color:#475569; padding:10px 19px; }
.why-page .feature{ font-weight:650; color:#334155; }
.why-page .value{ font-size:12px; color:#475569; }
.why-page .yes{ color:var(--green); font-weight:850; }
.why-page .limit{ display:inline-block; background:#dbeafe; color:#1d4ed8; border-radius:6px; padding:3px 7px; font-size:11px; font-weight:750; }
.why-page .table-note{ padding:15px 20px; color:var(--muted); font-size:11px; background:#fff; }

.why-page .details-grid{ display:grid; grid-template-columns:1fr 1fr; gap:16px; padding:24px 27px; border-top:1px solid var(--line); }
.why-page .detail-card{ border:1px solid var(--line); border-radius:12px; padding:17px; }
.why-page .detail-card h3{ margin:0 0 9px; font-size:15px; }
.why-page .detail-card p{ font-size:13px; color:var(--muted); margin:0; }
.why-page .cta-inline{
  padding:22px 27px; background:#f8faff; border-top:1px solid var(--line);
  display:flex; align-items:center; justify-content:space-between; gap:20px;
}
.why-page .cta-inline strong{ font-size:15px; }
.why-page .cta-inline p{ font-size:12px; color:var(--muted); margin:3px 0 0; }

.why-page .steps{ display:grid; grid-template-columns:repeat(3,1fr); gap:18px; }
.why-page .step{ border:1px solid var(--line); background:#fff; border-radius:13px; padding:23px; }
.why-page .step b{ display:grid; place-items:center; width:34px; height:34px; border-radius:9px; background:#dbeafe; color:#1d4ed8; }
.why-page .step h3{ font-size:16px; margin:13px 0 5px; }
.why-page .step p{ font-size:13px; color:var(--muted); margin:0; }

.why-page .faq{ max-width:780px; margin:0 auto; }
.why-page details{ background:#fff; border:1px solid var(--line); border-radius:11px; padding:16px 19px; margin-bottom:11px; }
.why-page summary{ cursor:pointer; font-weight:700; font-size:14px; }
.why-page details p{ font-size:13px; color:var(--muted); margin:10px 0 0; }

.why-page .final-cta{ padding:65px 0; }
.why-page .ctabox{ text-align:center; background:linear-gradient(125deg,#0e2a6b,#2F6FED); border-radius:20px; padding:48px 25px; color:#fff; }
.why-page .ctabox h2{ font-size:clamp(27px,4vw,38px); line-height:1.2; margin:0 0 12px; color:#fff; }
.why-page .ctabox p{ max-width:600px; margin:0 auto 22px; color:#dbeafe; font-size:14px; }
.why-page .ctabox .btn{ background:#fff; color:#1d4ed8; border-color:#fff; }

@media (max-width:760px){
  .why-page .competitors{ grid-template-columns:repeat(2,minmax(0,1fr)); }
  .why-page .steps{ grid-template-columns:1fr; }
  .why-page .details-grid{ padding:17px; grid-template-columns:1fr; }
  .why-page .comparison-head{ padding:21px; }
  .why-page .cta-inline{ align-items:flex-start; flex-direction:column; }
}
@media (max-width:500px){
  .why-page .wp-container{ width:calc(100% - 26px); }
  .why-page .wp-hero{ padding:44px 0 36px; }
  .why-page h1{ letter-spacing:-1.3px; }
  .why-page .wp-hero p{ font-size:14px; }
  .why-page .section{ padding:43px 0; }
  .why-page .competitors{ grid-template-columns:1fr; }
  .why-page .comparison{ margin-top:20px; }
  .why-page th,.why-page td{ padding:12px 11px; }
  .why-page .comparison-head h2{ font-size:21px; }
  .why-page .final-cta{ padding:45px 0; }
  .why-page .ctabox{ padding:36px 18px; }
}
</style>
@endpush

@section('content')
<div class="why-page">

<section class="site-hero">
 <div class="wp-container wp-hero">
  <span class="eyebrow">Compare your options</span>
  <h1>Find the right form builder<br>for your business</h1>
  <p>Compare HelloFormBuilder with popular alternatives. Explore form creation, website embedding, invoices, task management, team collaboration and live chat in one interactive comparison hub.</p>
  <div class="actions">
   <a href="#alternatives" class="btn primary">Compare Alternatives ↓</a>
   <a href="{{ route('pricing') }}" class="btn">View Pricing</a>
  </div>
 </div>
</section>

<section class="section" id="alternatives">
 <div class="wp-container">
  <div class="heading">
   <h2>Choose a competitor to compare</h2>
   <p>Click any option to display its comparison instantly on this page.</p>
  </div>

  <div class="competitors" id="competitors">
   <button class="competitor active" data-key="jotform" aria-pressed="true">
    <span class="brand">J</span><span><strong>HelloFormBuilder vs Jotform</strong><small>Online form builder</small></span><span class="arrow">›</span>
   </button>
   <button class="competitor" data-key="typeform" aria-pressed="false">
    <span class="brand">T</span><span><strong>HelloFormBuilder vs Typeform</strong><small>Interactive forms &amp; surveys</small></span><span class="arrow">›</span>
   </button>
   <button class="competitor" data-key="google" aria-pressed="false">
    <span class="brand">G</span><span><strong>HelloFormBuilder vs Google Forms</strong><small>Forms &amp; response collection</small></span><span class="arrow">›</span>
   </button>
   <button class="competitor" data-key="zoho" aria-pressed="false">
    <span class="brand">Z</span><span><strong>HelloFormBuilder vs Zoho Forms</strong><small>Forms &amp; workflow automation</small></span><span class="arrow">›</span>
   </button>
   <button class="competitor" data-key="fillout" aria-pressed="false">
    <span class="brand">F</span><span><strong>HelloFormBuilder vs Fillout</strong><small>Forms &amp; integrations</small></span><span class="arrow">›</span>
   </button>
   <button class="competitor" data-key="paperform" aria-pressed="false">
    <span class="brand">P</span><span><strong>HelloFormBuilder vs Paperform</strong><small>Forms &amp; payments</small></span><span class="arrow">›</span>
   </button>
  </div>

  <section class="comparison" aria-live="polite" aria-atomic="true">
   <div class="comparison-head">
    <h2 id="comparisonTitle">HelloFormBuilder vs Jotform</h2>
    <p id="comparisonIntro">Compare form creation and business workflow capabilities.</p>
    <div class="plan-pills">
     <span>HelloFormBuilder Free: $0</span>
     <span>HelloFormBuilder Pro: $49/month</span>
    </div>
   </div>
   <div class="table-scroll">
    <table>
     <thead>
      <tr><th>Feature</th><th>HelloFormBuilder</th><th id="competitorColumn">Jotform</th></tr>
     </thead>
     <tbody id="comparisonRows"></tbody>
    </table>
   </div>
   <div class="table-note" id="comparisonNote">
    Competitor capabilities may vary by plan. Check the vendor's current documentation before making a purchase.
   </div>
   <div class="details-grid">
    <article class="detail-card">
     <h3 id="strengthTitle">Where HelloFormBuilder may fit</h3>
     <p id="strengthText"></p>
    </article>
    <article class="detail-card">
     <h3 id="alternativeTitle">What to evaluate</h3>
     <p id="alternativeText"></p>
    </article>
   </div>
   <div class="cta-inline">
    <div><strong>Bring your business workflows together.</strong><p>Start with Free or explore the full HelloFormBuilder feature set.</p></div>
    <a href="{{ route('sign_up') }}" class="btn primary">Get Started Free →</a>
   </div>
  </section>
 </div>
</section>

<section class="section soft">
 <div class="wp-container">
  <div class="heading">
   <h2>What can you compare?</h2>
   <p>Evaluate the features that matter to your daily operations.</p>
  </div>
  <div class="steps">
   <article class="step"><b>01</b><h3>Forms &amp; embedding</h3><p>Create forms, publish them and embed them into your website.</p></article>
   <article class="step"><b>02</b><h3>Business workflows</h3><p>Compare invoice creation, task assignments and team management.</p></article>
   <article class="step"><b>03</b><h3>Communication &amp; branding</h3><p>Evaluate live chat availability and the “Made with HelloFormBuilder” Free-plan badge.</p></article>
  </div>
 </div>
</section>

<section class="section">
 <div class="wp-container">
  <div class="heading"><h2>Frequently asked questions</h2></div>
  <div class="faq">
   <details><summary>What is HelloFormBuilder?</summary><p>HelloFormBuilder brings form creation and embedding together with invoice creation, task management, team management and live chat.</p></details>
   <details><summary>How much does HelloFormBuilder cost?</summary><p>The Free plan includes 3 forms, 1 invoice creation, 1 team member and 1 task assignment. Pro costs $49 per month and includes all available features.</p></details>
   <details><summary>Does HelloFormBuilder include live chat?</summary><p>Live chat is included in the Pro plan. It is not included in the Free plan.</p></details>
   <details><summary>Can I remove HelloFormBuilder branding?</summary><p>Published forms on the Free plan display a “Made with HelloFormBuilder” badge. Pro removes this branding.</p></details>
   <details><summary>Are competitor features and prices guaranteed to be current?</summary><p>No. Competitor features, plan limits and pricing can change. Confirm details with each vendor before making a decision.</p></details>
  </div>
 </div>
</section>

<section class="final-cta">
 <div class="wp-container">
  <div class="ctabox">
   <h2>More than forms. A better way to work.</h2>
   <p>Manage forms, invoices, tasks, team members and live chat in one platform with HelloFormBuilder.</p>
   <a href="{{ route('sign_up') }}" class="btn">Start for Free →</a>
  </div>
 </div>
</section>

</div>
@endsection

@push('scripts')
@verbatim
<script>
const features = [
 {group:"Forms & publishing",name:"Form creation",hfb:"Included",competitors:{jotform:"Included",typeform:"Included",google:"Included",zoho:"Included",fillout:"Included",paperform:"Included"}},
 {group:"Forms & publishing",name:"Website form embedding",hfb:"Included",competitors:{jotform:"Available; check plan",typeform:"Available",google:"Available",zoho:"Available",fillout:"Available; check plan",paperform:"Available; check plan"}},
 {group:"Forms & publishing",name:"Form submissions & responses",hfb:"Included",competitors:{jotform:"Plan limits apply",typeform:"Plan limits apply",google:"Available",zoho:"Plan limits apply",fillout:"Plan limits apply",paperform:"Plan limits apply"}},
 {group:"Business management",name:"Invoice creation",hfb:"Included",competitors:{jotform:"Evaluate your invoice workflow",typeform:"Check native features or integrations",google:"Usually requires another workflow",zoho:"Evaluate Zoho Forms and related apps",fillout:"Check native features or integrations",paperform:"Evaluate your invoice workflow"}},
 {group:"Business management",name:"Task assignment & management",hfb:"Included",competitors:{jotform:"Check workflow requirements",typeform:"Evaluate integrations",google:"Usually requires another workflow",zoho:"Task assignment is documented",fillout:"Evaluate integrations",paperform:"Evaluate integrations"}},
 {group:"Business management",name:"Team management",hfb:"Included",competitors:{jotform:"Plan-dependent",typeform:"Plan-dependent",google:"Depends on Google account/workspace",zoho:"Plan and ecosystem dependent",fillout:"Check current plan",paperform:"Check current plan"}},
 {group:"Communication",name:"Live chat",hfb:"Pro",competitors:{jotform:"Check current offering",typeform:"Check current offering",google:"Not a core Forms feature",zoho:"Check current offering",fillout:"Check current offering",paperform:"Check current offering"}},
 {group:"Branding",name:"Remove platform branding",hfb:"Pro",competitors:{jotform:"Plan-dependent",typeform:"Plan-dependent",google:"Check current options",zoho:"Plan-dependent",fillout:"Plan-dependent",paperform:"Plan-dependent"}}
];

const competitors = {
 jotform:{name:"Jotform",intro:"Compare form creation, publishing and business workflow capabilities.",strength:"HelloFormBuilder combines forms with invoice creation, task management, team management and live chat. The Free plan has clear limits, while Pro is $49/month.",alternative:"Jotform offers a broad form-building feature set. Compare form limits, submissions, payments, integrations, branding and collaboration against your specific needs.",url:"https://www.jotform.com/pricing/"},
 typeform:{name:"Typeform",intro:"Compare interactive form experiences with a broader business workflow platform.",strength:"Consider HelloFormBuilder if you want forms and business management tools together in one subscription.",alternative:"Typeform emphasizes conversational forms, sharing, integrations and workflow automation. Compare respondent experience, response limits, analytics and integrations.",url:"https://www.typeform.com/platform-overview/"},
 google:{name:"Google Forms",intro:"Compare straightforward form creation with forms plus business workflow tools.",strength:"HelloFormBuilder combines forms with invoice creation, task management, team management and Pro live chat.",alternative:"Google Forms is useful for straightforward data collection and collaboration within Google tools. Compare the total setup needed for invoices, tasks and customer communication.",url:"https://www.google.com/forms/about/"},
 zoho:{name:"Zoho Forms",intro:"Compare form building, workflow automation and related business tools.",strength:"HelloFormBuilder is worth evaluating if you prefer your forms, invoices, tasks, team management and live chat in one product.",alternative:"Zoho Forms documents form embedding, task assignment, approvals, integrations and other automation features. Compare those capabilities with your required workflow.",url:"https://www.zoho.com/forms/features.html"},
 fillout:{name:"Fillout",intro:"Compare form creation and integrations with an all-in-one workflow approach.",strength:"HelloFormBuilder brings form tools together with invoice creation, team and task management, plus live chat on Pro.",alternative:"Review Fillout's current form features, integrations, response limits, branding and team capabilities to establish the best fit for your workflow.",url:"https://www.fillout.com/"},
 paperform:{name:"Paperform",intro:"Compare form-led workflows and payments with a wider business toolkit.",strength:"HelloFormBuilder may suit teams seeking forms, invoices, task management, team management and live chat in one platform.",alternative:"Evaluate Paperform's form design, payments, calculations, integrations, response limits and branding against your requirements.",url:"https://paperform.co/"}
};

const rows = document.getElementById("comparisonRows");
const title = document.getElementById("comparisonTitle");
const intro = document.getElementById("comparisonIntro");
const column = document.getElementById("competitorColumn");
const strength = document.getElementById("strengthText");
const alternative = document.getElementById("alternativeText");
const note = document.getElementById("comparisonNote");

function hfbValue(feature) {
  if (feature.name === "Form creation") return '<span class="limit">3 Free / all Pro</span>';
  if (feature.name === "Invoice creation") return '<span class="limit">1 Free / Pro included</span>';
  if (feature.name === "Task assignment & management") return '<span class="limit">1 Free assignment / Pro included</span>';
  if (feature.name === "Team management") return '<span class="limit">1 Free member / Pro included</span>';
  if (feature.name === "Live chat") return '<span class="value">Pro only</span>';
  if (feature.name === "Remove platform branding") return '<span class="value">Pro only</span>';
  return '<span class="yes">✓</span> <span class="value">Free & Pro</span>';
}

function competitorValue(value) {
  return '<span class="value">' + value + '</span>';
}

function render(key) {
  const item = competitors[key];
  title.textContent = "HelloFormBuilder vs " + item.name;
  intro.textContent = item.intro;
  column.textContent = item.name;
  strength.textContent = item.strength;
  alternative.textContent = item.alternative;

  rows.innerHTML = "";
  let lastGroup = "";
  features.forEach(feature => {
    if (feature.group !== lastGroup) {
      const groupRow = document.createElement("tr");
      groupRow.className = "group";
      const groupCell = document.createElement("td");
      groupCell.colSpan = 3;
      groupCell.textContent = feature.group;
      groupRow.appendChild(groupCell);
      rows.appendChild(groupRow);
      lastGroup = feature.group;
    }
    const tr = document.createElement("tr");
    const name = document.createElement("td");
    name.className = "feature";
    name.textContent = feature.name;
    const hfb = document.createElement("td");
    hfb.innerHTML = hfbValue(feature);
    const other = document.createElement("td");
    other.innerHTML = competitorValue(feature.competitors[key]);
    tr.append(name,hfb,other);
    rows.appendChild(tr);
  });

  note.textContent = "Comparison with " + item.name + ": competitor capabilities and availability can vary by plan and change over time. Review the vendor's current documentation before making a purchase.";
  document.querySelectorAll(".why-page .competitor").forEach(button => {
    const active = button.dataset.key === key;
    button.classList.toggle("active",active);
    button.setAttribute("aria-pressed",String(active));
  });
}

document.querySelectorAll(".why-page .competitor").forEach(button => {
  button.addEventListener("click", () => {
    render(button.dataset.key);
    if (window.innerWidth < 760) {
      document.querySelector(".why-page .comparison").scrollIntoView({behavior:"smooth",block:"start"});
    }
  });
});
render("jotform");
</script>
@endverbatim
@endpush