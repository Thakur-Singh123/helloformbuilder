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
        font-size: .9rem;
    }

    .client-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, .06);
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
        margin: .25rem 0 0;
        color: #64748b;
        font-size: .82rem;
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
        font-size: .92rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 1rem;
        padding-bottom: .65rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .form-label {
        font-size: .84rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: .4rem;
    }

    .required {
        color: #dc3545;
    }

    .form-control {
        min-height: 44px;
        border-color: #cbd5e1;
        border-radius: 8px;
        font-size: .9rem;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 .2rem rgba(59, 130, 246, .12);
    }

    .field-help {
        display: block;
        margin-top: .35rem;
        color: #94a3b8;
        font-size: .75rem;
    }

    .account-status {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .75rem 1rem;
        border-radius: 8px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #15803d;
        font-size: .82rem;
        font-weight: 600;
    }

    .account-status.inactive {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #64748b;
    }

    .password-note {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: .75rem 1rem;
        color: #64748b;
        font-size: .78rem;
        margin-top: 1rem;
    }

    .password-note i {
        color: #3b82f6;
        margin-right: .35rem;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: .75rem;
        padding: 1.25rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .btn-cancel {
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #475569;
        padding: .6rem 1.15rem;
        border-radius: 8px;
        font-size: .88rem;
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
        padding: .6rem 1.25rem;
        border-radius: 8px;
        font-size: .88rem;
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
</style>


<div class="client-form-wrap">

    <!-- Page Header -->
    <div class="client-page-header">

        <div>
            <h4>Edit Employee</h4>
            <p>Update the employee's account and profile information.</p>
        </div>

        <a href="{{ route('admin.teams.index') }}"
           class="btn btn-outline-secondary btn-sm">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Team
        </a>

    </div>


    <div class="client-card">

        <!-- Header -->
        <div class="client-card-header">

            <h5>
                <i class="bi bi-person-gear me-2"></i>
                Employee Information
            </h5>

            <p>
                Update the employee's basic information and login settings.
            </p>

        </div>


        <!-- Form -->

        <form method="POST"
              action="{{ route('admin.teams.update', $employee->id) }}">

            @csrf
            @method('PUT')


            <div class="client-card-body">


                <!-- Basic Information -->

                <div class="form-section">

                    <div class="section-title">
                        Basic Information
                    </div>


                    <div class="row g-3">


                        <!-- Employee Name -->

                        <!-- <div class="col-md-6">

                            <label class="form-label">
                                Employee Name <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name', $employee->name) }}"
                                placeholder="Enter employee name"
                                required
                            >

                        </div> -->


                        <!-- First Name -->

                        <div class="col-md-6">

                            <label class="form-label">
                                First Name
                            </label>

                            <input
                                type="text"
                                name="first_name"
                                class="form-control"
                                value="{{ old('first_name', $employee->first_name) }}"
                                placeholder="Enter first name"
                            >

                        </div>


                        <!-- Last Name -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Last Name
                            </label>

                            <input
                                type="text"
                                name="last_name"
                                class="form-control"
                                value="{{ old('last_name', $employee->last_name) }}"
                                placeholder="Enter last name"
                            >

                        </div>


                        <!-- Employee Profile -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Employee Profile
                            </label>

                            <select
                                name="profile"
                                class="form-control"
                            >

                                <option value="">Select Profile</option>

                                <option value="Laravel Developer"
                                    {{ old('profile', $employee->profile ?? '') == 'Laravel Developer' ? 'selected' : '' }}>
                                    Laravel Developer
                                </option>

                                <option value="Frontend Developer"
                                    {{ old('profile', $employee->profile ?? '') == 'Frontend Developer' ? 'selected' : '' }}>
                                    Frontend Developer
                                </option>

                                <option value="Backend Developer"
                                    {{ old('profile', $employee->profile ?? '') == 'Backend Developer' ? 'selected' : '' }}>
                                    Backend Developer
                                </option>

                                <option value="UI/UX Designer"
                                    {{ old('profile', $employee->profile ?? '') == 'UI/UX Designer' ? 'selected' : '' }}>
                                    UI/UX Designer
                                </option>

                                <option value="QA Engineer"
                                    {{ old('profile', $employee->profile ?? '') == 'QA Engineer' ? 'selected' : '' }}>
                                    QA Engineer
                                </option>

                                <option value="Project Manager"
                                    {{ old('profile', $employee->profile ?? '') == 'Project Manager' ? 'selected' : '' }}>
                                    Project Manager
                                </option>

                                <option value="Other"
                                    {{ old('profile', $employee->profile ?? '') == 'Other' ? 'selected' : '' }}>
                                    Other
                                </option>

                            </select>

                            <span class="field-help">
                                Select the employee's job profile.
                            </span>

                        </div>

                    </div>

                </div>


                <!-- Login Information -->

                <div class="form-section">

                    <div class="section-title">
                        Login Information
                    </div>


                    <div class="row g-3">


                        <!-- Email -->

                        <div class="col-md-12">

                            <label class="form-label">
                                Email Address <span class="required">*</span>
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email', $employee->email) }}"
                                placeholder="employee@example.com"
                                required
                            >

                            <span class="field-help">
                                This email address is used by the employee to log in.
                            </span>

                        </div>


                        <!-- Password -->

                        <div class="col-md-6">

                            <label class="form-label">
                                New Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Leave blank to keep current password"
                            >

                        </div>


                        <!-- Confirm Password -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Confirm New Password
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Confirm new password"
                            >

                        </div>

                    </div>


                    <div class="password-note">

                        <i class="bi bi-info-circle"></i>

                        Leave the password fields blank if you do not want
                        to change the employee's current password.

                    </div>

                </div>


                <!-- Account Status -->

                <div class="form-section">

                    <div class="section-title">
                        Account Status
                    </div>


                    <div class="account-status {{ !$employee->is_active ? 'inactive' : '' }}">

                        @if($employee->is_active)

                            <i class="bi bi-check-circle-fill"></i>

                            Account is Active

                        @else

                            <i class="bi bi-pause-circle-fill"></i>

                            Account is Inactive

                        @endif

                    </div>


                    <span class="field-help">

                        Account activation/deactivation can be managed
                        from the Team Management listing.

                    </span>

                </div>


            </div>


            <!-- Footer -->

            <div class="form-actions">

                <a href="{{ route('admin.teams.index') }}"
                   class="btn-cancel">

                    Cancel

                </a>


                <button type="submit"
                        class="btn-save">

                    <i class="bi bi-check-lg me-1"></i>

                    Update Employee

                </button>

            </div>

        </form>

    </div>

</div>

@endsection