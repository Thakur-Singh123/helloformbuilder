@extends('user.include.layout')
        @section('content')

    <!-- Font Awesome for icons (fas fa-trash-alt, etc.) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        /* --- General Styling and Layout --- */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .container {
            width: 95%;
            max-width: 900px;
            margin: 1.5rem auto;
            padding: 0;
        }

        .card {
            background-color: white;
            padding: 2rem;
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.08);
        }

        h5 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .form-section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .required-star {
            color: #dc3545;
            margin-right: 0.25rem;
        }

        /* --- Form Elements --- */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
            border-radius: 0.5rem;
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        .form-control:focus {
            border-color: #0d6efd;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        /* --- Display text style panels --- */
        .text-style-panel {
            margin-top: 0.75rem;
            padding: 0.85rem 1rem;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
        }

        .text-style-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 0.75rem 1rem;
        }

        .text-style-field-wide {
            grid-column: 1 / -1;
        }

        .text-style-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.35rem;
        }

        .form-control-sm {
            padding: 0.45rem 0.65rem;
            font-size: 0.875rem;
        }

        .color-palette-row {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            flex-wrap: wrap;
        }

        .color-swatch {
            width: 1.65rem;
            height: 1.65rem;
            border-radius: 9999px;
            border: 2px solid #fff;
            box-shadow: 0 0 0 1px #ced4da;
            cursor: pointer;
            padding: 0;
        }

        .color-swatch.active {
            box-shadow: 0 0 0 2px #0d6efd;
        }

        .style-color-picker {
            width: 2rem;
            height: 2rem;
            padding: 0;
            border: none;
            background: none;
            cursor: pointer;
        }

        .align-buttons {
            display: flex;
            gap: 0.35rem;
            flex-wrap: wrap;
        }

        .btn-align {
            padding: 0.35rem 0.65rem;
            font-size: 0.8rem;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            background: #fff;
            cursor: pointer;
        }

        .btn-align.active {
            background: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }

        .style-toggle-buttons {
            display: flex;
            gap: 0.35rem;
            flex-wrap: wrap;
        }

        .btn-style-toggle {
            min-width: 2.25rem;
            padding: 0.35rem 0.65rem;
            font-size: 0.85rem;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            background: #fff;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-style-toggle.active {
            background: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }

        .btn-style-toggle.style-underline {
            text-decoration: underline;
        }

        .btn-style-toggle.style-italic {
            font-style: italic;
        }

        /* --- Status Buttons --- */
        .status-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .btn-status {
            padding: 0.6rem 1.25rem;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 0.5rem;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.2s;
        }
        .btn-status.active {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        .btn-status:not(.active) {
            background-color: #e9ecef;
            border-color: #ced4da;
            color: #495057;
        }
        .btn-status:not(.active):hover {
            background-color: #e2e6ea;
            border-color: #dae0e5;
        }

        /* --- Dynamic Field Rows --- */
        .form-field-block {
            margin-bottom: 1rem;
        }

        .form-field-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-field-row .form-control {
            flex-grow: 1;
        }

        .select-options-panel {
            display: none;
            margin-top: 0.75rem;
            padding: 0.75rem 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
        }

        .select-options-panel .options-heading {
            font-size: 0.85rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .select-option-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .select-option-row .form-control {
            flex-grow: 1;
        }

        .remove-option-btn {
            padding: 0.5rem 0.65rem;
            background-color: #e9ecef;
            color: #6c757d;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            flex-shrink: 0;
        }

        .remove-option-btn:hover {
            background-color: #dee2e6;
            color: #495057;
        }

        .btn-add-select-option {
            margin-top: 0.25rem;
            padding: 0.4rem 0.75rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: #0d6efd;
            background: white;
            border: 1px dashed #0d6efd;
            border-radius: 0.375rem;
            cursor: pointer;
        }

        .btn-add-select-option:hover {
            background-color: #e7f1ff;
        }

        .selection-mode-row {
            margin-bottom: 0.75rem;
        }

        .selection-mode-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.35rem;
        }

        .btn-selection-mode {
            padding: 0.4rem 0.9rem;
            font-size: 0.85rem;
            font-weight: 500;
            border-radius: 0.375rem;
            cursor: pointer;
            border: 1px solid #ced4da;
            background-color: #e9ecef;
            color: #495057;
            transition: all 0.2s;
        }

        .btn-selection-mode.active {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }

        .remove-field-btn {
            padding: 0.75rem;
            background-color: #f8d7da;
            color: #dc3545;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.2s;
            flex-shrink: 0;
        }
        .remove-field-btn:hover {
            background-color: #f1b0b7;
        }

        .field-required-toggle {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            flex-shrink: 0;
            margin: 0;
            padding: 0 0.5rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: #495057;
            cursor: pointer;
            white-space: nowrap;
            user-select: none;
        }

        .field-required-toggle input {
            cursor: pointer;
        }

        /* --- Dropdown Styling (Pure CSS/JS) --- */
        .field-type-dropdown {
            position: relative;
            width: 160px;
            flex-shrink: 0;
        }

        .dropdown-toggle {
            width: 100%;
            height: 44px; /* Matches form-control height */
            padding: 0 1rem;
            background-color: white;
            border: 1px solid #ced4da;
            border-radius: 0.5rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: border-color 0.2s;
        }

        .dropdown-toggle:hover {
            background-color: #f8f9fa;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            min-width: 100%;
            padding: 0.5rem 0;
            margin-top: 0.25rem;
            background-color: #fff;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            list-style: none;
            display: none; /* Controlled by JS */
        }
        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            display: block;
            padding: 0.5rem 1rem;
            color: #212529;
            cursor: pointer;
            transition: background-color 0.2s, color 0.2s;
        }

        .dropdown-item:hover {
            background-color: #0d6efd;
            color: white;
        }

        /* --- Add Field Button --- */
        #addFieldBtn {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 1rem;
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 2px dashed #ced4da;
            background-color: #f8f9fa;
            color: #6c757d;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.2s, color 0.2s, border-color 0.2s;
            font-size: 1rem;
        }
        #addFieldBtn:hover {
            background-color: #e9ecef;
            color: #495057;
            border-color: #adb5bd;
        }

        /* --- Footer Buttons --- */
        .footer-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            padding-top: 1.5rem;
            margin-top: 2rem;
            border-top: 1px solid #e9ecef;
        }

        .btn-primary, .btn-secondary {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.2s, border-color 0.2s, color 0.2s;
            min-width: 100px;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: 1px solid #0d6efd;
            color: white;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        .btn-secondary {
            background-color: white;
            border: 1px solid #6c757d;
            color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #f8f9fa;
            border-color: #5c636a;
            color: #5c636a;
        }

        /* --- Responsive Adjustments (Mobile) --- */
        @media (max-width: 640px) {
            .container {
                margin: 0.5rem auto;
            }
            .card {
                padding: 1rem;
                border-radius: 0;
                box-shadow: none;
            }
            .form-field-row {
                flex-direction: column;
                align-items: stretch;
            }

            .select-option-row {
                flex-direction: column;
                align-items: stretch;
            }

            .remove-option-btn {
                width: 100%;
            }

            .field-type-dropdown {
                width: 100%;
                margin-top: 0.5rem;
                margin-bottom: 0.5rem;
            }
            .remove-field-btn {
                width: 100%;
            }
            .footer-buttons {
                flex-direction: column-reverse;
            }
            .footer-buttons button {
                width: 100%;
            }
        }

        /* Success / error popup */
        .lm-popup-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease;
            z-index: 1040;
        }
        .lm-popup-backdrop.is-open {
            opacity: 1;
            visibility: visible;
        }
        .lm-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            z-index: 1050;
            width: min(440px, calc(100vw - 32px));
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.2);
            padding: 1.75rem 1.5rem 1.25rem;
            text-align: center;
            opacity: 0;
            visibility: hidden;
            transform: translate(-50%, -48%);
            transition: all 0.22s ease;
        }
        .lm-popup.is-open {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%);
        }
        .lm-popup-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 1rem;
        }
        .lm-popup-icon.success {
            background: #d1e7dd;
            color: #198754;
        }
        .lm-popup-icon.error {
            background: #f8d7da;
            color: #dc3545;
        }
        .lm-popup-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 0.5rem;
        }
        .lm-popup-message {
            color: #6c757d;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 1.25rem;
        }
        .lm-popup-btn {
            min-width: 120px;
            padding: 0.65rem 1.25rem;
            font-weight: 600;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
        }
        .lm-popup-btn.success {
            background: #198754;
            color: #fff;
        }
        .lm-popup-btn.error {
            background: #dc3545;
            color: #fff;
        }

        .page-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .back-to-forms-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.55rem 1rem;
            border: 1px solid #ced4da;
            border-radius: 0.5rem;
            background: #fff;
            color: #495057;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .back-to-forms-btn:hover {
            background: #f8f9fa;
            color: #0d6efd;
            border-color: #0d6efd;
        }

    </style>
 

    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0" style="font-size: 0.9rem;">
                <li class="breadcrumb-item"><a href="{{ route('user_dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('build_form') }}">Build Form</a></li>
                <li class="breadcrumb-item"><a href="{{ route('list_view') }}">Forms</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>

        <div class="page-topbar">
            <div></div>
            <a href="{{ route('list_view') }}" class="back-to-forms-btn">
                <i class="bi bi-list-ul"></i> View Forms List
            </a>
        </div>

        <div class="card">
            <h5 class="mb-4">Edit template: <span id="currentFormName">{{ $form->name }}</span></h5>
            <p class="text-muted small mb-4">Update public heading, title, description styling and form fields.</p>

            @php
                $displaySettings = $form->getDisplaySettings();
                $stylePalette = ['#333333', '#0d6efd', '#198754', '#dc3545', '#fd7e14', '#6f42c1', '#6c757d', '#000000'];
                $styleLineHeights = ['1.0', '1.2', '1.4', '1.5', '1.6', '1.8', '2.0'];
                $formTextFields = [
                    ['key' => 'heading', 'id' => 'formHeading', 'label' => 'Heading', 'type' => 'input', 'placeholder' => 'Main heading shown on the public form', 'value' => $form->heading ?? ''],
                    ['key' => 'title', 'id' => 'formTitle', 'label' => 'Title', 'type' => 'input', 'placeholder' => 'Subtitle shown below the heading', 'value' => $form->title ?? ''],
                    ['key' => 'description', 'id' => 'formDescription', 'label' => 'Description', 'type' => 'textarea', 'placeholder' => 'Optional text below the title', 'value' => $form->description ?? ''],
                ];
            @endphp

            <!-- Form Name Section -->
            <div class="form-group">
                <label for="formName" class="form-label">
                    <span class="required-star">*</span> Name
                </label>
                <input type="text" class="form-control" name="name" id="formName" value="{{ $form->name }}">
                <small class="text-muted">Internal name (shown in your dashboard list only — not on the public form)</small>
            </div>

            <h6 class="form-section-title">Public form display</h6>
            <p class="text-muted small mb-3">These appear on the embedded form for visitors. Style each with tag, color, alignment, italic and underline.</p>

            @foreach($formTextFields as $textField)
                @php
                    $styleKey = $textField['key'];
                    $settings = $displaySettings[$styleKey];
                    $styleTags = $styleKey === 'description'
                        ? ['p' => 'Paragraph (P)', 'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6']
                        : ['h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6'];
                @endphp
                <div class="form-group">
                    <label for="{{ $textField['id'] }}" class="form-label">{{ $textField['label'] }}</label>
                    @if($textField['type'] === 'textarea')
                        <textarea class="form-control" name="{{ $textField['key'] }}" id="{{ $textField['id'] }}" rows="2" placeholder="{{ $textField['placeholder'] }}">{{ $textField['value'] }}</textarea>
                    @else
                        <input type="text" class="form-control" name="{{ $textField['key'] }}" id="{{ $textField['id'] }}" value="{{ $textField['value'] }}" placeholder="{{ $textField['placeholder'] }}">
                    @endif

                    <div class="text-style-panel" data-style-key="{{ $styleKey }}">
                        <div class="text-style-grid">
                            <div class="text-style-field">
                                <label class="text-style-label">Tag</label>
                                <select class="form-control form-control-sm style-tag">
                                    @foreach($styleTags as $tagValue => $tagLabel)
                                        <option value="{{ $tagValue }}" {{ ($settings['tag'] ?? '') === $tagValue ? 'selected' : '' }}>{{ $tagLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-style-field">
                                <label class="text-style-label">Line height</label>
                                <select class="form-control form-control-sm style-line-height">
                                    @foreach($styleLineHeights as $lh)
                                        <option value="{{ $lh }}" {{ ($settings['line_height'] ?? '1.5') === $lh ? 'selected' : '' }}>{{ $lh }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-style-field text-style-field-wide">
                                <label class="text-style-label">Color</label>
                                <div class="color-palette-row">
                                    @foreach($stylePalette as $color)
                                        <button type="button"
                                            class="color-swatch {{ ($settings['color'] ?? '') === $color ? 'active' : '' }}"
                                            data-color="{{ $color }}"
                                            style="background-color: {{ $color }};"
                                            title="{{ $color }}"
                                            aria-label="Color {{ $color }}"></button>
                                    @endforeach
                                    <input type="color" class="style-color-picker" value="{{ $settings['color'] ?? '#333333' }}" title="Custom color">
                                </div>
                                <input type="hidden" class="style-color" value="{{ $settings['color'] ?? '#333333' }}">
                            </div>
                            <div class="text-style-field">
                                <label class="text-style-label">Alignment</label>
                                <div class="align-buttons" role="group">
                                    @foreach(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'] as $alignValue => $alignLabel)
                                        <button type="button"
                                            class="btn-align {{ ($settings['align'] ?? 'left') === $alignValue ? 'active' : '' }}"
                                            data-align="{{ $alignValue }}">{{ $alignLabel }}</button>
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-style-field">
                                <label class="text-style-label">Text style</label>
                                <div class="style-toggle-buttons" role="group">
                                    <button type="button"
                                        class="btn-style-toggle style-italic {{ !empty($settings['italic']) ? 'active' : '' }}"
                                        data-style="italic"
                                        title="Italic">I</button>
                                    <button type="button"
                                        class="btn-style-toggle style-underline {{ !empty($settings['underline']) ? 'active' : '' }}"
                                        data-style="underline"
                                        title="Underline">U</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Status Section -->
            <div class="form-group">
                <label class="form-label">Status</label>
                <div class="status-buttons" role="group" id="statusButtons">
                    <button type="button" data-status="active" class="btn-status {{ $form->status === 'active' ? 'active' : '' }}">
                        Active
                    </button>
                    <button type="button" data-status="inactive" class="btn-status {{ $form->status === 'inactive' ? 'active' : '' }}">
                        Inactive
                    </button>
                </div>
            </div>

            @if(currentUser()->isSuperAdmin() || currentUser()->isAdmin())
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="is_global" id="is_global" value="1" {{ !empty($form->is_global) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_global">Share as template for all admins (appears in <strong>Choose template</strong> for every admin)</label>
                </div>
            </div>
            @endif

            <div class="form-group">
                <label class="form-label" for="button_text">Submit Button Text</label>
                <input type="text" class="form-control" id="button_text" value="{{ $form->getSubmitButtonSettings()['text'] }}" maxlength="50" placeholder="Submit">
                <small class="text-muted">Text shown on the form submit button.</small>
            </div>

            <div class="form-group">
                <label class="form-label" for="button_color">Submit Button Color</label>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <input type="color" id="button_color" value="{{ $form->getSubmitButtonSettings()['color'] }}" style="width: 3rem; height: 2.5rem; padding: 0.15rem; border: 1px solid #ced4da; border-radius: 0.375rem; background: #fff; cursor: pointer;">
                    <input type="text" class="form-control" id="button_color_text" value="{{ $form->getSubmitButtonSettings()['color'] }}" maxlength="7" placeholder="#0d6efd" style="max-width: 140px;">
                </div>
                <small class="text-muted">Color of the form submit button.</small>
            </div>

            <h6 class="form-section-title">Form Fields</h6>

            <!-- Form Field Container (Dynamic Content) -->
            <div id="formFieldContainer">
                <!-- Fields injected here by JS -->
            </div>

            <!-- Add Field Button -->
            <button type="button" id="addFieldBtn">
                <i class="fas fa-plus-lg me-2"></i> Add Form Field
            </button>

            <!-- Footer Buttons -->
            <div class="footer-buttons">
                <button type="button" class="btn-secondary" onclick="window.location.href='{{ route('list_view') }}'">
                    <i class="bi bi-list-ul me-1"></i> Back to Forms
                </button>
                <button type="button" class="btn-primary" id="updateFormBtn">
                    <i class="fas fa-edit me-1"></i> Update Form
                </button>
            </div>
        </div>
    </div>

    <div class="lm-popup-backdrop" id="lmPopupBackdrop" aria-hidden="true"></div>
    <div class="lm-popup" id="lmPopup" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="lm-popup-icon success" id="lmPopupIcon">
            <i class="bi bi-check-lg"></i>
        </div>
        <div class="lm-popup-title" id="lmPopupTitle">Success</div>
        <div class="lm-popup-message" id="lmPopupMessage"></div>
        <button type="button" class="lm-popup-btn success" id="lmPopupBtn">OK</button>
    </div>

    <!-- Hidden Field Template -->
    <template id="fieldRowTemplate">
        <div class="form-field-block">
            <div class="form-field-row">
                <input type="text" class="form-control field-name-input" placeholder="Field Name (e.g., Email Address)">

                <div class="field-type-dropdown">
                    <button type="button" class="dropdown-toggle" aria-expanded="false">
                        <span class="selected-type">Input</span>
                        <i class="fas fa-chevron-down text-xs ml-2"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" data-value="Input">Input</a></li>
                        <li><a class="dropdown-item" data-value="Text Area">Text Area</a></li>
                        <li><a class="dropdown-item" data-value="Number">Number</a></li>
                        <li><a class="dropdown-item" data-value="Checkbox">Checkbox</a></li>
                        <li><a class="dropdown-item" data-value="Date">Date</a></li>
                        <li><a class="dropdown-item" data-value="Select">Select (dropdown)</a></li>
                    </ul>
                </div>

                <label class="field-required-toggle" title="Mark this field as required">
                    <input type="checkbox" class="field-required-checkbox">
                    <span>Required</span>
                </label>

                <button type="button" class="remove-field-btn" title="Remove Field">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            <div class="select-options-panel" aria-label="Field choices">
                <div class="selection-mode-row">
                    <div class="options-heading">Selection mode</div>
                    <div class="selection-mode-buttons" role="group">
                        <button type="button" class="btn-selection-mode active" data-mode="single">Single</button>
                        <button type="button" class="btn-selection-mode" data-mode="multiple">Multiple</button>
                    </div>
                </div>
                <div class="options-heading options-heading-choices">Choice labels</div>
                <div class="select-option-list"></div>
                <button type="button" class="btn-add-select-option">
                    <i class="fas fa-plus"></i> Add choice
                </button>
            </div>
        </div>
    </template>
    @stop    
    @section('footer')
    <script>
    function showLmPopup(options) {
        var backdrop = document.getElementById('lmPopupBackdrop');
        var popup = document.getElementById('lmPopup');
        var icon = document.getElementById('lmPopupIcon');
        var title = document.getElementById('lmPopupTitle');
        var message = document.getElementById('lmPopupMessage');
        var btn = document.getElementById('lmPopupBtn');
        var type = options.type || 'success';

        icon.className = 'lm-popup-icon ' + type;
        icon.innerHTML = type === 'success'
            ? '<i class="bi bi-check-lg"></i>'
            : '<i class="bi bi-exclamation-lg"></i>';
        title.textContent = options.title || (type === 'success' ? 'Success' : 'Error');
        message.textContent = options.message || '';
        btn.className = 'lm-popup-btn ' + type;
        btn.textContent = options.buttonText || (type === 'success' ? 'View Forms List' : 'OK');

        backdrop.classList.add('is-open');
        popup.classList.add('is-open');
        backdrop.setAttribute('aria-hidden', 'false');
        popup.setAttribute('aria-hidden', 'false');

        var closePopup = function() {
            backdrop.classList.remove('is-open');
            popup.classList.remove('is-open');
            backdrop.setAttribute('aria-hidden', 'true');
            popup.setAttribute('aria-hidden', 'true');
            btn.removeEventListener('click', onBtnClick);
            backdrop.removeEventListener('click', onBtnClick);
            if (typeof options.onClose === 'function') {
                options.onClose();
            }
        };

        var onBtnClick = function() { closePopup(); };
        btn.addEventListener('click', onBtnClick);
        backdrop.addEventListener('click', onBtnClick);
    }
    </script>
    <script>
    (function () {
        if (window.LMFormDisplayStyle) { return; }
        function syncSwatchActive(panel, color) {
            panel.querySelectorAll('.color-swatch').forEach(function (swatch) {
                swatch.classList.toggle('active', swatch.getAttribute('data-color').toLowerCase() === color.toLowerCase());
            });
        }
        function initDisplayStylePanels(root) {
            (root || document).querySelectorAll('.text-style-panel').forEach(function (panel) {
                if (panel.dataset.lmStyleInit === '1') { return; }
                panel.dataset.lmStyleInit = '1';
                var colorInput = panel.querySelector('.style-color');
                var colorPicker = panel.querySelector('.style-color-picker');
                panel.querySelectorAll('.color-swatch').forEach(function (swatch) {
                    swatch.addEventListener('click', function () {
                        var color = swatch.getAttribute('data-color');
                        colorInput.value = color;
                        if (colorPicker) { colorPicker.value = color; }
                        syncSwatchActive(panel, color);
                    });
                });
                if (colorPicker) {
                    colorPicker.addEventListener('input', function () {
                        colorInput.value = colorPicker.value;
                        syncSwatchActive(panel, colorPicker.value);
                    });
                }
                panel.querySelectorAll('.align-buttons .btn-align').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        panel.querySelectorAll('.align-buttons .btn-align').forEach(function (b) { b.classList.remove('active'); });
                        btn.classList.add('active');
                    });
                });
                panel.querySelectorAll('.btn-style-toggle').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        btn.classList.toggle('active');
                    });
                });
            });
        }
        function collectDisplaySettings(root) {
            var out = {};
            (root || document).querySelectorAll('.text-style-panel').forEach(function (panel) {
                var key = panel.getAttribute('data-style-key');
                if (!key) { return; }
                var alignBtn = panel.querySelector('.align-buttons .btn-align.active');
                out[key] = {
                    tag: panel.querySelector('.style-tag').value,
                    color: panel.querySelector('.style-color').value,
                    align: alignBtn ? alignBtn.getAttribute('data-align') : 'left',
                    line_height: panel.querySelector('.style-line-height').value,
                    italic: panel.querySelector('.style-italic')?.classList.contains('active') ? 1 : 0,
                    underline: panel.querySelector('.style-underline')?.classList.contains('active') ? 1 : 0,
                };
            });
            var buttonText = document.getElementById('button_text');
            var buttonColor = document.getElementById('button_color');
            var buttonColorText = document.getElementById('button_color_text');
            var colorVal = ((buttonColorText && buttonColorText.value) || (buttonColor && buttonColor.value) || '#0d6efd').trim();
            out.submit_button = {
                text: ((buttonText && buttonText.value) || 'Submit').trim() || 'Submit',
                color: colorVal
            };
            return out;
        }
        window.LMFormDisplayStyle = { init: initDisplayStylePanels, collect: collectDisplaySettings };
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () { initDisplayStylePanels(document); });
        } else {
            initDisplayStylePanels(document);
        }
    })();
    </script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    const fieldContainer = document.getElementById('formFieldContainer');
    const fieldTemplate = document.getElementById('fieldRowTemplate');
    const addFieldBtn = document.getElementById('addFieldBtn');
    const updateFormBtn = document.getElementById('updateFormBtn');
    const statusButtons = document.querySelectorAll('.btn-status');

    let formStatus = '{{ $form->status }}';

    function isValidHexColor(value) {
        return /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(String(value || '').trim());
    }
    $('#button_color').on('input change', function() {
        $('#button_color_text').val($(this).val());
    });
    $('#button_color_text').on('input change', function() {
        var textVal = ($(this).val() || '').trim();
        if (isValidHexColor(textVal)) {
            $('#button_color').val(textVal);
        }
    });

    const CHOICE_FIELD_TYPES = ['Select', 'Checkbox'];

    function getFieldType(block) {
        return block.querySelector('.selected-type').textContent.trim();
    }

    function isChoiceFieldType(type) {
        return CHOICE_FIELD_TYPES.indexOf(type) !== -1;
    }

    function getDefaultSelectionMode(type) {
        return type === 'Checkbox' ? 'multiple' : 'single';
    }

    function updateChoicePanelHeading(block, type) {
        const heading = block.querySelector('.options-heading-choices');
        if (!heading) {
            return;
        }
        if (type === 'Select') {
            heading.textContent = 'Choice labels (shown in the dropdown)';
        } else if (type === 'Checkbox') {
            heading.textContent = 'Choice labels (shown as checkboxes on the form)';
        }
    }

    function setSelectionMode(block, mode) {
        const normalizedMode = mode === 'multiple' ? 'multiple' : 'single';
        block.querySelectorAll('.btn-selection-mode').forEach((btn) => {
            btn.classList.toggle('active', btn.getAttribute('data-mode') === normalizedMode);
        });
    }

    function getSelectionMode(block) {
        const activeBtn = block.querySelector('.btn-selection-mode.active');
        return activeBtn ? activeBtn.getAttribute('data-mode') : 'single';
    }

    function syncSelectionModeRow(block, type) {
        const selectionModeRow = block.querySelector('.selection-mode-row');
        if (!selectionModeRow) {
            return;
        }
        selectionModeRow.style.display = type === 'Checkbox' ? 'block' : 'none';
    }

    function syncChoiceOptionsPanel(block) {
        const type = getFieldType(block);
        const panel = block.querySelector('.select-options-panel');
        const optionList = block.querySelector('.select-option-list');
        const showPanel = isChoiceFieldType(type);

        panel.style.display = showPanel ? 'block' : 'none';
        if (!showPanel) {
            return;
        }

        updateChoicePanelHeading(block, type);
        syncSelectionModeRow(block, type);

        if (optionList.children.length === 0) {
            addSelectOptionRow(block, '');
            addSelectOptionRow(block, '');
        }
    }

    function addSelectOptionRow(block, label) {
        const optionList = block.querySelector('.select-option-list');
        const optRow = document.createElement('div');
        optRow.className = 'select-option-row';
        optRow.innerHTML =
            '<input type="text" class="form-control select-option-label" placeholder="Choice label" value="">' +
            '<button type="button" class="remove-option-btn" title="Remove choice" aria-label="Remove choice">' +
            '<i class="fas fa-times"></i></button>';
        optRow.querySelector('.select-option-label').value = label;
        optRow.querySelector('.remove-option-btn').addEventListener('click', () => {
            optRow.remove();
            if (block.querySelector('.select-option-list').children.length === 0) {
                addSelectOptionRow(block, '');
            }
        });
        optionList.appendChild(optRow);
    }

    function addFormField(initialName = '', initialType = 'Input', initialMetadata = null, initialRequired = false) {
        const block = fieldTemplate.content.cloneNode(true).querySelector('.form-field-block');
        const row = block.querySelector('.form-field-row');

        const metadata = (initialMetadata && typeof initialMetadata === 'object') ? initialMetadata : {};
        const initialLabels = Array.isArray(metadata.options) ? metadata.options : [];
        const initialMode = metadata.allow_multiple ? 'multiple' : (metadata.allow_multiple === false ? 'single' : getDefaultSelectionMode(initialType));

        row.querySelector('.field-name-input').value = initialName;
        row.querySelector('.selected-type').textContent = initialType;
        row.querySelector('.field-required-checkbox').checked = !!initialRequired;
        setSelectionMode(block, initialMode);

        const dropdownToggle = row.querySelector('.dropdown-toggle');
        const dropdownMenu = row.querySelector('.dropdown-menu');

        if (isChoiceFieldType(initialType) && initialLabels.length) {
            initialLabels.forEach((label) => addSelectOptionRow(block, label));
        }

        block.querySelectorAll('.btn-selection-mode').forEach((btn) => {
            btn.addEventListener('click', () => {
                setSelectionMode(block, btn.getAttribute('data-mode'));
            });
        });

        dropdownToggle.addEventListener('click', (e) => {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu !== dropdownMenu) menu.classList.remove('show');
            });
            dropdownMenu.classList.toggle('show');
            e.stopPropagation();
        });

        row.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', () => {
                const previousType = getFieldType(block);
                const newType = item.getAttribute('data-value');
                row.querySelector('.selected-type').textContent = newType;
                dropdownMenu.classList.remove('show');
                if (isChoiceFieldType(newType) && (!isChoiceFieldType(previousType) || previousType !== newType)) {
                    setSelectionMode(block, getDefaultSelectionMode(newType));
                }
                syncChoiceOptionsPanel(block);
            });
        });

        block.querySelector('.btn-add-select-option').addEventListener('click', () => {
            addSelectOptionRow(block, '');
        });

        row.querySelector('.remove-field-btn').addEventListener('click', () => {
            block.remove();
        });

        syncChoiceOptionsPanel(block);
        fieldContainer.appendChild(block);
    }

    function collectChoiceMetadata(block, fieldName, fieldType) {
        const labels = [];
        block.querySelectorAll('.select-option-label').forEach((inp) => {
            const value = inp.value.trim();
            if (value) {
                labels.push(value);
            }
        });

        if (labels.length === 0) {
            alert(fieldType + ' field "' + fieldName + '" needs at least one choice label.');
            return { failed: true };
        }

        const allowMultiple = fieldType === 'Checkbox' && getSelectionMode(block) === 'multiple';

        return {
            failed: false,
            metadata: {
                options: labels,
                allow_multiple: allowMultiple,
            },
        };
    }

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.field-type-dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
        }
    });

    @php
        $existingFields = ($form->fields ?? collect())->sortBy('order')->map(function ($f) {
            $meta = $f->metadata;
            if (is_string($meta)) {
                $meta = json_decode($meta, true) ?: [];
            }
            if (!is_array($meta)) {
                $meta = [];
            }
            $fieldMetadata = null;
            if (in_array($f->field_type, ['Select', 'Checkbox'], true)) {
                $fieldMetadata = [
                    'options' => $meta['options'] ?? [],
                    'allow_multiple' => $f->field_type === 'Checkbox'
                        ? (array_key_exists('allow_multiple', $meta) ? !empty($meta['allow_multiple']) : true)
                        : false,
                ];
            }
            return [
                'field_name' => $f->field_name,
                'field_type' => $f->field_type,
                'is_required' => !empty($f->is_required),
                'metadata' => $fieldMetadata,
            ];
        })->values();
    @endphp

    const existingFields = @json($existingFields);

    if (existingFields.length > 0) {
        existingFields.forEach((f) => addFormField(f.field_name, f.field_type, f.metadata || null, !!f.is_required));
    } else {
        addFormField('name', 'Input');
        addFormField('message', 'Text Area');
        addFormField('phone number', 'Input');
    }

    addFieldBtn.addEventListener('click', () => addFormField());

    statusButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            statusButtons.forEach(btn => btn.classList.remove('active'));
            e.currentTarget.classList.add('active');
            formStatus = e.currentTarget.getAttribute('data-status');
        });
    });

    updateFormBtn.addEventListener('click', () => {
    const formName = document.getElementById('formName').value;
    const formHeading = document.getElementById('formHeading').value;
    const formTitle = document.getElementById('formTitle').value;
    const formDescription = document.getElementById('formDescription').value;
    const fields = [];
    let fieldValidationFailed = false;

    fieldContainer.querySelectorAll('.form-field-block').forEach((block, index) => {
        const row = block.querySelector('.form-field-row');
        const nameInput = row.querySelector('.field-name-input').value.trim();
        const typeText = row.querySelector('.selected-type').textContent;

        if (!nameInput) {
            return;
        }

        let metadata = null;
        if (isChoiceFieldType(typeText)) {
            const choiceResult = collectChoiceMetadata(block, nameInput, typeText);
            if (choiceResult.failed) {
                fieldValidationFailed = true;
                return;
            }
            metadata = choiceResult.metadata;
        }

        fields.push({
            field_name: nameInput,
            field_key: nameInput.toLowerCase().replace(/\s+/g, "_"),
            field_type: typeText,
            is_required: row.querySelector('.field-required-checkbox').checked ? 1 : 0,
            metadata: metadata,
            order: index + 1
        });
    });

    if (fieldValidationFailed) {
        return;
    }

    if (!formName) {
        showLmPopup({
            type: 'error',
            title: 'Validation Error',
            message: 'Please enter a name for the form.'
        });
        return;
    }

    var postData = {
            _token: "{{ csrf_token() }}",
            name: formName,
            heading: formHeading,
            title: formTitle,
            description: formDescription,
            display_settings: JSON.stringify(window.LMFormDisplayStyle.collect()),
            status: formStatus,
            fields: JSON.stringify(fields),
        };
        var isGlobalEl = document.getElementById('is_global');
        if (isGlobalEl && isGlobalEl.checked) {
            postData.is_global = '1';
        }

    $.ajax({
        type: "POST",
        url: "{{ route('custom_form_update', $form->id) }}",
        data: postData,
        success: function(response) {
            showLmPopup({
                type: 'success',
                title: 'Form Updated',
                message: response.message || 'Form updated successfully. Your existing embed code will now show the updated form.',
                onClose: function() {
                    window.location.href = "{{ route('list_view') }}";
                }
            });
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            var errorMsg = 'Something went wrong!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            showLmPopup({
                type: 'error',
                title: 'Update Failed',
                message: errorMsg
            });
        }
    });
    });
</script>

@stop
