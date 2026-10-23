@extends('user.include.layout')
@section('content')
<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Inter', sans-serif;
        padding-top: 20px;
    }

    .dashboard-container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .content-card {
        background-color: #ffffff;
        border-radius: 0.5rem;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    .submission-field {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 1rem;
        background-color: #f8f9fa;
    }

    .submission-field-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .submission-field-value {
        color: #212529;
        font-size: 1rem;
    }

    .info-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .modal-backdrop {
        z-index: 2000 !important;
    }

    #leadReplyModal {
        z-index: 2010 !important;
    }
</style>

<div class="dashboard-container">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('user_dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('submissions.index') }}">Form Submissions</a></li>
            <li class="breadcrumb-item active" aria-current="page">Submission Details</li>
        </ol>
    </nav>

    <div class="content-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0">Submission Details</h1>
            <div>
                <a href="{{ route('submissions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to List
                </a>
                @if($submission->visitor_email)
                    <button type="button"
                            class="btn btn-primary btn-sm js-reply-btn"
                            data-reply-url="{{ route('submissions.reply', $submission) }}"
                            data-to-email="{{ $submission->visitor_email }}"
                            data-to-name="{{ $submission->visitor_name ?? '' }}"
                            data-default-subject="Re: {{ $submission->customForm->name ?? 'Your enquiry' }}">
                        <i class="bi bi-reply me-1"></i> Reply
                    </button>
                @endif
                @can('delete', $submission)
                <form action="{{ route('submissions.destroy', $submission->id) }}" 
                      method="POST" 
                      class="d-inline"
                      onsubmit="return confirm('Are you sure you want to delete this submission?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </form>
                @endcan
            </div>
        </div>

        <!-- Visitor Information -->
        @php
            $visitorName = $submission->visitor_name;
            $visitorEmail = $submission->visitor_email;
            $visitorPhone = $submission->visitor_phone;
            $initials = $visitorName ? strtoupper(substr($visitorName, 0, 2)) : '??';
            $avatarColor = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4'][ord($initials[0] ?? 'A') % 6];
        @endphp
        <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <div class="visitor-avatar me-4" style="background: rgba(255, 255, 255, 0.2); width: 80px; height: 80px; font-size: 2rem;">
                                {{ $initials }}
                            </div>
                            <div>
                                <h3 class="mb-2 fw-bold">{{ $visitorName ?? 'Anonymous Visitor' }}</h3>
                                <div class="d-flex flex-wrap gap-3">
                                    @if($visitorEmail)
                                        <div>
                                            <i class="bi bi-envelope me-2"></i>
                                            <a href="mailto:{{ $visitorEmail }}" class="text-white text-decoration-none">
                                                {{ $visitorEmail }}
                                            </a>
                                        </div>
                                    @endif
                                    @if($visitorPhone)
                                        <div>
                                            <i class="bi bi-telephone me-2"></i>
                                            <a href="tel:{{ $visitorPhone }}" class="text-white text-decoration-none">
                                                {{ $visitorPhone }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="text-white-50 small mb-1">Submitted</div>
                        <div class="fw-bold">{{ $submission->created_at->format('M d, Y') }}</div>
                        <div class="text-white-50">{{ $submission->created_at->format('h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submission Information -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-3">
                            <i class="bi bi-info-circle me-2"></i>Submission Information
                        </h6>
                        <div class="mb-3">
                            <strong>Form Name:</strong><br>
                            <span class="text-primary fw-semibold">{{ $submission->customForm->name ?? 'N/A' }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Version:</strong><br>
                            <span class="badge bg-info">
                                Version {{ $submission->formVersion->version_number ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <strong>IP Address:</strong><br>
                            <span class="text-muted">{{ $submission->ip_address ?? 'N/A' }}</span>
                        </div>
                        @if($submission->user_agent)
                        <div class="mb-3">
                            <strong>User Agent:</strong><br>
                            <span class="text-muted small">{{ $submission->user_agent }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-3">
                            <i class="bi bi-person-badge me-2"></i>Form Owner Details
                        </h6>
                        @if($submission->admin)
                        <div class="mb-3">
                            <strong>Admin (Subscriber):</strong><br>
                            <span>{{ $submission->admin->first_name ?? $submission->admin->name ?? $submission->admin->email }}</span>
                        </div>
                        @endif
                        @if($submission->user)
                        <div class="mb-3">
                            <strong>Form Creator:</strong><br>
                            <span>{{ $submission->user->first_name ?? $submission->user->name ?? $submission->user->email }}</span>
                        </div>
                        @endif
                        @if($submission->shortcode)
                        <div class="mb-3">
                            <strong>Shortcode Used:</strong><br>
                            <code class="text-primary bg-light p-2 rounded d-inline-block">
                                [shortcode={{ $submission->shortcode->code }}]
                            </code>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Submission Data -->
        <div class="mb-4">
            <h5 class="mb-3">Form Data</h5>
            @if($submission->submission_data && count($submission->submission_data) > 0)
                @foreach($submission->submission_data as $fieldKey => $fieldData)
                <div class="submission-field">
                    <div class="submission-field-label">
                        {{ $fieldData['field_name'] ?? $fieldKey }}
                        @if(isset($fieldData['field_type']))
                            <span class="badge bg-secondary ms-2">{{ $fieldData['field_type'] }}</span>
                        @endif
                    </div>
                    <div class="submission-field-value">
                        @if(is_array($fieldData['value'] ?? null))
                            {{ implode(', ', $fieldData['value']) }}
                        @else
                            {{ $fieldData['value'] ?? 'N/A' }}
                        @endif
                    </div>
                </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>No form data available for this submission.
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="d-flex justify-content-end">
            <a href="{{ route('submissions.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
</div>
@stop

@section('footer')
{{-- Reply compose: send email to lead from admin panel --}}
<div class="modal fade" id="leadReplyModal" tabindex="-1" aria-labelledby="leadReplyModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="leadReplyForm" method="POST" action="#">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="leadReplyModalLabel">
                        <i class="bi bi-reply me-2"></i>Reply to Lead
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">To</label>
                        <input type="text" class="form-control" id="leadReplyToDisplay" readonly>
                        <input type="hidden" name="to_email" id="leadReplyToEmail">
                    </div>
                    <div class="mb-3">
                        <label for="leadReplySubject" class="form-label fw-semibold">Subject</label>
                        <input type="text" class="form-control" name="subject" id="leadReplySubject" required maxlength="255">
                    </div>
                    <div class="mb-0">
                        <label for="leadReplyMessage" class="form-label fw-semibold">Message</label>
                        <textarea class="form-control" name="message" id="leadReplyMessage" rows="8" required maxlength="10000" placeholder="Write your reply to the lead..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="leadReplySendBtn">
                        <i class="bi bi-send me-1"></i> Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    function initLeadReplyModal() {
        var modalEl = document.getElementById('leadReplyModal');
        var form = document.getElementById('leadReplyForm');
        if (!modalEl || !form || typeof bootstrap === 'undefined') return;

        if (modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }

        var modal = bootstrap.Modal.getOrCreateInstance(modalEl, {
            backdrop: true,
            keyboard: true,
            focus: true
        });

        var toDisplay = document.getElementById('leadReplyToDisplay');
        var toEmail = document.getElementById('leadReplyToEmail');
        var subjectEl = document.getElementById('leadReplySubject');
        var messageEl = document.getElementById('leadReplyMessage');
        var sendBtn = document.getElementById('leadReplySendBtn');

        document.querySelectorAll('.js-reply-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var url = this.getAttribute('data-reply-url');
                var email = this.getAttribute('data-to-email') || '';
                var name = this.getAttribute('data-to-name') || '';
                var subject = this.getAttribute('data-default-subject') || 'Re: Your enquiry';

                form.setAttribute('action', url);
                toEmail.value = email;
                toDisplay.value = name ? (name + ' <' + email + '>') : email;
                subjectEl.value = subject;
                messageEl.value = '';
                modal.show();
                setTimeout(function () { messageEl.focus(); }, 300);
            });
        });

        form.addEventListener('submit', function () {
            if (sendBtn) {
                sendBtn.disabled = true;
                sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending...';
            }
        });

        modalEl.addEventListener('hidden.bs.modal', function () {
            if (sendBtn) {
                sendBtn.disabled = false;
                sendBtn.innerHTML = '<i class="bi bi-send me-1"></i> Send Email';
            }
        });
    }

    if (document.readyState === 'complete') {
        initLeadReplyModal();
    } else {
        window.addEventListener('load', initLeadReplyModal);
    }
})();
</script>
@stop

