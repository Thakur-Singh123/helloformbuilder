@extends('user.include.layout') 
@section('content')
<style>
   .client-form-wrap {
      max-width: 900px;
      margin: 0 auto;
   }

   .client-page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
   }

   .client-page-header h4 {
      margin: 0;
      color: #1e293b;
      font-weight: 700;
   }

   .client-page-header p {
      margin: 4px 0 0;
      color: #64748b;
      font-size: 0.9rem;
   }

   .client-card {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
      overflow: hidden;
   }

   .client-card-header {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid #e2e8f0;
      background: #f8fafc;
   }

   .client-card-header h5 {
      margin: 0;
      font-size: 1rem;
      font-weight: 700;
      color: #1e293b;
   }

   .client-card-header p {
      margin: 0.25rem 0 0;
      color: #64748b;
      font-size: 0.82rem;
   }

   .client-card-body {
      padding: 1.5rem;
   }

   .form-section {
      margin-bottom: 1.75rem;
   }

   .form-section:last-child {
      margin-bottom: 0;
   }

   .section-title {
      font-size: 0.92rem;
      font-weight: 700;
      color: #334155;
      margin-bottom: 1rem;
      padding-bottom: 0.65rem;
      border-bottom: 1px solid #e2e8f0;
   }

   .form-label {
      font-size: 0.84rem;
      font-weight: 600;
      color: #475569;
      margin-bottom: 0.4rem;
   }

   .required {
      color: #dc3545;
   }

   .form-control {
      min-height: 44px;
      border-color: #cbd5e1;
      border-radius: 8px;
      font-size: 0.9rem;
   }

   .form-control:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.12);
   }

   textarea.form-control {
      min-height: 90px;
      resize: vertical;
   }

   .field-help {
      display: block;
      margin-top: 0.35rem;
      color: #94a3b8;
      font-size: 0.75rem;
   }

   .password-note {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 0.75rem 1rem;
      color: #64748b;
      font-size: 0.78rem;
      margin-top: 1rem;
   }

   .password-note i {
      color: #3b82f6;
      margin-right: 0.35rem;
   }

   .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 0.75rem;
      padding: 1.25rem 1.5rem;
      border-top: 1px solid #e2e8f0;
      background: #f8fafc;
   }

   .btn-cancel {
      border: 1px solid #cbd5e1;
      background: #fff;
      color: #475569;
      padding: 0.6rem 1.15rem;
      border-radius: 8px;
      font-size: 0.88rem;
      font-weight: 600;
      text-decoration: none;
   }

   .btn-cancel:hover {
      background: #f8fafc;
      color: #334155;
   }

   .btn-save {
      border: none;
      background: #3b82f6;
      color: #fff;
      padding: 0.6rem 1.25rem;
      border-radius: 8px;
      font-size: 0.88rem;
      font-weight: 600;
   }

   .btn-save:hover {
      background: #2563eb;
   }

   @media (max-width: 576px) {
      .client-card-body {
         padding: 1rem;
      }

      .form-actions {
         padding: 1rem;
         flex-direction: column-reverse;
      }

      .btn-cancel,
      .btn-save {
         width: 100%;
         text-align: center;
      }
   }
   .password-wrap {
      position: relative;
   }

   .password-wrap .form-control {
      padding-right: 44px;
   }

   .toggle-password {
      position: absolute;
      top: 50%;
      right: 12px;
      transform: translateY(-50%);
      background: none;
      border: none;
      padding: 0;
      color: #94a3b8;
      cursor: pointer;
      font-size: 1.1rem;
      line-height: 1;
   }

   .toggle-password:hover {
      color: #3b82f6;
   }
</style>

<div class="client-form-wrap">
   @include('admin.partials.notification')
   <div class="client-page-header">
      <div>
         <h4>Add Employee</h4>
         <p>Create a new employee account and provide login access.</p>
      </div>

      <a href="{{ route('admin.teams.create') }}" class="btn btn-outline-secondary btn-sm">
         <i class="bi bi-arrow-left me-1"></i>
         Back to Task Manager
      </a>
   </div>

   <div class="client-card">
      <div class="client-card-header">
         <h5>
            <i class="bi bi-person-plus me-2"></i>
            Employee Information
         </h5>
         <p>Enter the employees's basic information and login credentials.</p>
      </div>

      <form method="POST" action="{{ route('admin.teams.store') }}">
         @csrf

         <!-- @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the following errors:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif -->

         <div class="client-card-body">
            <!-- Basic Information -->
            <div class="form-section">
               <div class="section-title">Basic Information</div>

               <div class="row g-3">
                  <!-- <div class="col-md-6">
                            <label class="form-label">
                                Employee Name <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name') }}"
                                placeholder="Enter employee name"
                                required
                            >
                        </div> -->

                  <!-- <div class="col-md-6">
                            <label class="form-label">
                                Website
                            </label>

                            <input
                                type="text"
                                name="website"
                                class="form-control"
                                value="{{ old('website') }}"
                                placeholder="https://example.com"
                            >

                            <span class="field-help">
                                Optional website associated with this employee.
                            </span>
                        </div> -->

                  <div class="col-md-6">
                     <label class="form-label"> First Name </label>

                     <input
                        type="text"
                        name="first_name"
                        class="form-control"
                        value="{{ old('first_name') }}"
                        placeholder="Enter first name"
                     />
                  </div>

                  <div class="col-md-6">
                     <label class="form-label"> Last Name </label>

                     <input
                        type="text"
                        name="last_name"
                        class="form-control"
                        value="{{ old('last_name') }}"
                        placeholder="Enter last name"
                     />
                  </div>
               </div>
            </div>

            <!-- Login Information -->
            <div class="form-section">
               <div class="section-title">Login Information</div>

               <div class="row g-3">
                  <div class="col-md-12">
                     <label class="form-label"> Email Address <span class="required">*</span> </label>

                     <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        placeholder="client@example.com"
                        required
                     />

                     <span class="field-help"> This email will be used by the employee to log in. </span>
                  </div>

                  <div class="col-md-6">
                     <label class="form-label"> Password <span class="required">*</span> </label>

                     <div class="password-wrap">
                        <input
                           type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="Enter password"
                           autocomplete="new-password"
                           required
                        />
                        <button type="button" class="toggle-password" data-target="password" aria-label="Show password">
                           <i class="bi bi-eye"></i>
                        </button>
                     </div>
                  </div>

                  <div class="col-md-6">
                     <label class="form-label"> Confirm Password <span class="required">*</span> </label>

                     <div class="password-wrap">
                        <input
                           type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           class="form-control"
                           placeholder="Confirm password"
                           autocomplete="new-password"
                           required
                        />
                        <button
                           type="button"
                           class="toggle-password"
                           data-target="password_confirmation"
                           aria-label="Show password"
                        >
                           <i class="bi bi-eye"></i>
                        </button>
                     </div>
                  </div>
               </div>

               <div class="password-note">
                  <i class="bi bi-info-circle"></i>
                  A welcome email containing the employee's login details will be sent after the account is created.
               </div>
            </div>
         </div>

         <div class="form-actions">
            <a href="{{ route('admin.teams.index') }}" class="btn-cancel"> Cancel </a>

            <button type="submit" class="btn-save">
               <i class="bi bi-check-lg me-1"></i>
               Create Employee login
            </button>
         </div>
      </form>
   </div>
</div>
<script>
   document.querySelectorAll(".toggle-password").forEach(function (btn) {
      btn.addEventListener("click", function () {
         var input = document.getElementById(this.dataset.target);
         var icon = this.querySelector("i");
         var show = input.type === "password";

         input.type = show ? "text" : "password";
         icon.classList.toggle("bi-eye", !show);
         icon.classList.toggle("bi-eye-slash", show);
         this.setAttribute("aria-label", show ? "Hide password" : "Show password");
      });
   });
</script>
@endsection
