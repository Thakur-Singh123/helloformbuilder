@extends('user.include.layout')
@section('content')

<style>
    .build-form-landing .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e2837;
        margin-bottom: 0.5rem;
    }
    .build-form-landing .page-subtitle {
        color: #6c757d;
        margin-bottom: 2rem;
    }
    .build-form-landing .option-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .build-form-landing .option-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        color: inherit;
    }
    .build-form-landing .option-card .card-body {
        padding: 2rem;
    }
    .build-form-landing .option-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        font-size: 1.5rem;
    }
    .build-form-landing .option-card h5 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #1e2837;
    }
    .build-form-landing .option-card p {
        color: #6c757d;
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }
    .build-form-landing .option-card .btn {
        font-weight: 600;
        padding: 0.5rem 1rem;
    }
</style>

<div class="container-fluid build-form-landing">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="page-title">Build Form</h1>
            <p class="page-subtitle">Choose an existing form to embed, create a new form, or manage your forms list.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Choose template → Create shortcode -->
        <div class="col-md-4">
            <a href="{{ route('shortcodes.create') }}" class="option-card card">
                <div class="card-body">
                    <div class="option-icon text-bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-code-square"></i>
                    </div>
                    <h5>Choose template</h5>
                    <p class="mb-0">Select an existing form and generate an embed code (shortcode) to use on your website, CMS, or any external page.</p>
                    <span class="btn btn-primary mt-3">
                        Choose template <i class="bi bi-arrow-right ms-1"></i>
                    </span>
                </div>
            </a>
        </div>

        <!-- Create template → New form -->
        <div class="col-md-4">
            <a href="{{ route('add_new_form') }}" class="option-card card">
                <div class="card-body">
                    <div class="option-icon text-bg-success bg-opacity-10 text-success">
                        <i class="bi bi-plus-square"></i>
                    </div>
                    <h5>Create template</h5>
                    <p class="mb-0">Create a new form from scratch: add fields, set validation, and publish. You can generate shortcodes after saving.</p>
                    <span class="btn btn-success mt-3">
                        Create template <i class="bi bi-arrow-right ms-1"></i>
                    </span>
                </div>
            </a>
        </div>

        <!-- Forms list -->
        <div class="col-md-4">
            <a href="{{ route('list_view') }}" class="option-card card">
                <div class="card-body">
                    <div class="option-icon text-bg-info bg-opacity-10 text-info">
                        <i class="bi bi-list-ul"></i>
                    </div>
                    <h5>Forms list</h5>
                    <p class="mb-0">View, edit, enable or disable, and manage all forms you have created.</p>
                    <span class="btn btn-info mt-3 text-white">
                        View forms list <i class="bi bi-arrow-right ms-1"></i>
                    </span>
                </div>
            </a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body py-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Tip:</strong> <strong>Choose template</strong> picks a form and generates an embed code. <strong>Create template</strong> builds a new form. <strong>Forms list</strong> shows all your created forms to edit or manage.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
