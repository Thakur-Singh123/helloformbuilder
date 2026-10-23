@extends('user.include.layout')
@section('content')
<style>
    .submission-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-left: 4px solid #3b82f6;
    }
    
    .submission-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }
    
    .submission-card.new {
        border-left-color: #10b981;
        background: linear-gradient(to right, rgba(16, 185, 129, 0.05), transparent);
    }
    
    .submission-card.viewed {
        background-color: #ffffff;
        border-left-color: #dee2e6;
    }
    
    .visitor-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.2rem;
        color: white;
    }
    
    .visitor-info {
        flex: 1;
    }
    
    .visitor-name {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }
    
    .visitor-details {
        font-size: 0.875rem;
        color: #6b7280;
    }
    
    .visitor-details i {
        width: 16px;
        margin-right: 0.5rem;
    }
    
    .form-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        background: #eff6ff;
        color: #1e40af;
    }
    
    .message-preview {
        color: #6b7280;
        font-size: 0.875rem;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .new-badge {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: #10b981;
        color: white;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-weight: 600;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.85rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        border: 1px solid transparent;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
    }

    .status-badge .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .status-badge.pending {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        color: #92400e;
        border-color: #fcd34d;
    }

    .status-badge.pending .status-dot {
        background: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.25);
        animation: status-pulse 2s infinite;
    }

    .status-badge.hired {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        color: #166534;
        border-color: #86efac;
    }

    .status-badge.hired .status-dot {
        background: #22c55e;
    }

    .status-badge.cancelled {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        color: #991b1b;
        border-color: #fca5a5;
    }

    .status-badge.cancelled .status-dot {
        background: #ef4444;
    }

    @keyframes status-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.15); }
    }

    .submission-card.status-pending:not(.new) {
        border-left-color: #f59e0b;
        background: linear-gradient(to right, rgba(245, 158, 11, 0.04), #fff);
    }

    .submission-card.status-hired {
        border-left-color: #22c55e;
        background: linear-gradient(to right, rgba(34, 197, 94, 0.05), #fff);
    }

    .submission-card.status-cancelled {
        border-left-color: #ef4444;
        background: linear-gradient(to right, rgba(239, 68, 68, 0.03), #fafafa);
        opacity: 0.95;
    }

    .card-status-wrap {
        position: absolute;
        top: 1rem;
        left: 1rem;
        z-index: 2;
    }

    .submission-card.has-new-badge .card-status-wrap {
        top: 2.6rem;
    }

    .lead-stats-row {
        margin-bottom: 1.25rem;
    }

    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 1.1rem 1.25rem;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        border: 1px solid #e2e8f0;
        text-decoration: none;
        color: inherit;
        display: block;
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        color: inherit;
    }

    .stat-card.active {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }

    .stat-card .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-bottom: 0.65rem;
    }

    .stat-card.all .stat-icon { background: #eff6ff; color: #2563eb; }
    .stat-card.pending .stat-icon { background: #fffbeb; color: #d97706; }
    .stat-card.hired .stat-icon { background: #f0fdf4; color: #16a34a; }
    .stat-card.cancelled .stat-icon { background: #fef2f2; color: #dc2626; }

    .stat-card .stat-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0.15rem;
    }

    .stat-card .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1;
        color: #0f172a;
    }

    .status-filter-bar {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 0.5rem;
        display: inline-flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
        margin-bottom: 1.25rem;
    }

    .status-filter-tab {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.55rem 1rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        color: #64748b;
        transition: all 0.15s ease;
    }

    .status-filter-tab:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .status-filter-tab.active {
        color: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
    }

    .status-filter-tab.active.all { background: #3b82f6; }
    .status-filter-tab.active.pending { background: #f59e0b; }
    .status-filter-tab.active.hired { background: #22c55e; }
    .status-filter-tab.active.cancelled { background: #ef4444; }

    .status-filter-tab .tab-count {
        background: rgba(255, 255, 255, 0.25);
        padding: 0.1rem 0.45rem;
        border-radius: 999px;
        font-size: 0.75rem;
        min-width: 1.5rem;
        text-align: center;
    }

    .status-filter-tab:not(.active) .tab-count {
        background: #f1f5f9;
        color: #475569;
    }

    .lead-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        justify-content: flex-end;
        align-items: center;
    }

    .lead-actions .btn-action-hire,
    .lead-actions .btn-action-cancel {
        font-weight: 600;
        border-radius: 8px;
        padding: 0.4rem 0.85rem;
    }

    .status-done-label {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        padding: 0.35rem 0.65rem;
        background: #f8fafc;
        border-radius: 8px;
    }

    .status-done-label.hired { color: #15803d; background: #f0fdf4; }
    .status-done-label.cancelled { color: #b91c1c; background: #fef2f2; }

    /* Leads table */
    .leads-table-wrap {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .leads-table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .leads-table {
        width: 100%;
        min-width: 980px;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .leads-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.9rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .leads-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #eef2f7;
        color: #334155;
        font-size: 0.9rem;
    }

    .leads-table tbody tr:last-child td {
        border-bottom: none;
    }

    .leads-table tbody tr:hover {
        background: #f8fafc;
    }

    .leads-table tbody tr.row-new {
        background: #fffbeb;
    }

    .leads-table tbody tr.row-new:hover {
        background: #fef3c7;
    }

    .leads-table .col-lead { min-width: 220px; }
    .leads-table .col-form { min-width: 160px; }
    .leads-table .col-message { min-width: 180px; max-width: 280px; }
    .leads-table .col-date { min-width: 120px; white-space: nowrap; }
    .leads-table .col-status { min-width: 130px; }
    .leads-table .col-actions {
        min-width: 280px;
        width: 280px;
    }

    .lead-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .visitor-avatar-sm {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        color: #fff;
        flex-shrink: 0;
    }

    .lead-cell-name {
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.15rem;
        line-height: 1.3;
    }

    .lead-cell-meta {
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.4;
    }

    .lead-cell-meta a {
        color: #475569;
        text-decoration: none;
    }

    .lead-cell-meta a:hover {
        color: #2563eb;
    }

    .new-pill {
        display: inline-block;
        background: #10b981;
        color: #fff;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.15rem 0.45rem;
        border-radius: 4px;
        margin-left: 0.35rem;
        vertical-align: middle;
    }

    .form-name-cell {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.2rem;
    }

    .form-version-cell {
        font-size: 0.78rem;
        color: #94a3b8;
    }

    .message-cell {
        color: #64748b;
        font-size: 0.85rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.45;
    }

    .date-cell-main {
        font-weight: 600;
        color: #334155;
        font-size: 0.85rem;
    }

    .date-cell-sub {
        font-size: 0.78rem;
        color: #94a3b8;
        margin-top: 0.15rem;
    }

    .action-buttons {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        justify-content: flex-end;
        gap: 0.4rem;
    }

    .action-buttons form {
        margin: 0;
        flex-shrink: 0;
    }

    .action-buttons .btn {
        white-space: nowrap;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.45rem 0.75rem;
        border-radius: 8px;
        line-height: 1.2;
    }

    .action-buttons .btn-view {
        background: #3b82f6;
        border-color: #3b82f6;
        color: #fff;
    }

    .action-buttons .btn-view:hover {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
    }

    .action-buttons .btn-hire {
        background: #22c55e;
        border-color: #22c55e;
        color: #fff;
    }

    .action-buttons .btn-hire:hover {
        background: #16a34a;
        border-color: #16a34a;
        color: #fff;
    }

    .action-buttons .btn-cancel {
        background: #fff;
        border: 1px solid #ef4444;
        color: #ef4444;
    }

    .action-buttons .btn-cancel:hover {
        background: #ef4444;
        color: #fff;
    }

    .action-buttons .btn-delete {
        background: #fff;
        border: 1px solid #e2e8f0;
        color: #64748b;
        padding: 0.45rem 0.55rem;
    }

    .action-buttons .btn-delete:hover {
        background: #fef2f2;
        border-color: #fca5a5;
        color: #dc2626;
    }

    @media (max-width: 768px) {
        .status-filter-bar {
            width: 100%;
            justify-content: stretch;
        }

        .status-filter-tab {
            flex: 1 1 auto;
            justify-content: center;
        }
    }

    .lead-action-modal .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
        overflow: hidden;
    }

    .lead-action-modal .modal-header {
        border-bottom: 1px solid #eef2f7;
        padding: 1.25rem 1.5rem 1rem;
    }

    .lead-action-modal .modal-body {
        padding: 1rem 1.5rem 1.25rem;
    }

    .lead-action-modal .modal-footer {
        border-top: 1px solid #eef2f7;
        padding: 1rem 1.5rem 1.25rem;
        gap: 0.5rem;
    }

    .lead-action-modal .modal-icon {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 0.75rem;
    }

    .lead-action-modal .modal-icon.hire { background: #dcfce7; color: #16a34a; }
    .lead-action-modal .modal-icon.cancel { background: #fee2e2; color: #dc2626; }
    .lead-action-modal .modal-icon.delete { background: #fef2f2; color: #b91c1c; }

    .lead-action-modal .lead-name-highlight {
        font-weight: 700;
        color: #0f172a;
    }

    /* Keep modal above sidebar + page stacking contexts */
    .modal-backdrop {
        z-index: 2000 !important;
    }

    .lead-action-modal,
    #leadReplyModal {
        z-index: 2010 !important;
    }
</style>

@php $isAdmin = currentUser()->isAdmin(); @endphp

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1 fw-bold">{{ $isAdmin ? 'Leads' : 'Form Submissions' }}</h2>
            <p class="text-muted mb-0">
                @if($isAdmin)
                    Manage leads from shortcode forms - hire or cancel each one.
                @else
                    View and manage all messages received through your embedded forms
                @endif
            </p>
        </div>
        <div class="text-end">
            @if(isset($unreadCount) && $unreadCount > 0)
                <span class="badge bg-success bg-opacity-10 text-success mb-2 d-block">
                    <i class="bi bi-bell-fill me-1"></i> {{ $unreadCount }} unread submission(s)
                </span>
            @endif
            <div class="text-muted small">
                Total: <strong>{{ $submissions->total() }}</strong> submission(s)
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($isAdmin)
    <div class="row g-3 lead-stats-row">
        <div class="col-6 col-md-3">
            <a href="{{ route('submissions.index', request()->except('status', 'page')) }}"
               class="stat-card all {{ !request('status') ? 'active' : '' }}">
                <div class="stat-icon"><i class="bi bi-collection"></i></div>
                <div class="stat-label">Total Leads</div>
                <div class="stat-value">{{ $statusCounts['all'] ?? 0 }}</div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('submissions.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}"
               class="stat-card pending {{ request('status') === 'pending' ? 'active' : '' }}">
                <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-label">Pending</div>
                <div class="stat-value">{{ $statusCounts['pending'] ?? 0 }}</div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('submissions.index', array_merge(request()->except('page'), ['status' => 'hired'])) }}"
               class="stat-card hired {{ request('status') === 'hired' ? 'active' : '' }}">
                <div class="stat-icon"><i class="bi bi-person-check"></i></div>
                <div class="stat-label">Hired</div>
                <div class="stat-value">{{ $statusCounts['hired'] ?? 0 }}</div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('submissions.index', array_merge(request()->except('page'), ['status' => 'cancelled'])) }}"
               class="stat-card cancelled {{ request('status') === 'cancelled' ? 'active' : '' }}">
                <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
                <div class="stat-label">Cancelled</div>
                <div class="stat-value">{{ $statusCounts['cancelled'] ?? 0 }}</div>
            </a>
        </div>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('submissions.index') }}" id="submissionsFilterForm" class="row g-3 align-items-end">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(isset($clients) && $clients->isNotEmpty())
                <div class="col-md-3 col-lg-2">
                    <label for="client_id" class="form-label small fw-semibold mb-1">Client</label>
                    <select name="client_id" id="client_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Clients</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name ?? $c->email }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                @if(isset($shortcodes) && $shortcodes->isNotEmpty())
                <div class="col-md-3 col-lg-2">
                    <label for="shortcode_id" class="form-label small fw-semibold mb-1">Shortcode</label>
                    <select name="shortcode_id" id="shortcode_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Shortcodes</option>
                        @foreach($shortcodes as $sc)
                            <option value="{{ $sc->id }}" {{ request('shortcode_id') == $sc->id ? 'selected' : '' }}>
                                {{ $sc->code }} ({{ $sc->customForm->name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                @if($forms->count() > 0)
                <div class="col-md-3 col-lg-2">
                    <label for="form_id" class="form-label small fw-semibold mb-1">Form</label>
                    <select name="form_id" id="form_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Forms</option>
                        @foreach($forms as $form)
                            <option value="{{ $form->id }}" {{ request('form_id') == $form->id ? 'selected' : '' }}>
                                {{ $form->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-2">
                    <label for="date_from" class="form-label small fw-semibold mb-1">From date</label>
                    <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label small fw-semibold mb-1">To date</label>
                    <input type="date" name="date_to" id="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" onchange="this.form.submit()">
                </div>
                @if(request()->hasAny(['form_id','client_id','shortcode_id','date_from','date_to','status']))
                <div class="col-md-2">
                    <a href="{{ route('submissions.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-x-circle me-1"></i> Clear filters
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Submissions List -->
    @if($submissions->count() > 0)
        <div class="leads-table-wrap">
            <div class="leads-table-responsive">
                <table class="leads-table">
                    <thead>
                        <tr>
                            <th class="col-lead">Lead</th>
                            <th class="col-form">Form</th>
                            <th class="col-message">Message</th>
                            <th class="col-date">Submitted</th>
                            @if($isAdmin)
                                <th class="col-status">Status</th>
                            @endif
                            <th class="col-actions text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $submission)
                            @php
                                $isNew = $submission->read_at === null;
                                $visitorName = $submission->visitor_name;
                                $visitorEmail = $submission->visitor_email;
                                $visitorPhone = $submission->visitor_phone;
                                $visitorMessage = $submission->visitor_message;
                                $initials = $visitorName ? strtoupper(substr($visitorName, 0, 2)) : '??';
                                $avatarColor = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4'][ord($initials[0] ?? 'A') % 6];
                                $status = $submission->status ?? 'pending';
                            @endphp
                            <tr class="{{ $isNew ? 'row-new' : '' }}">
                                <td class="col-lead">
                                    <div class="lead-cell">
                                        <div class="visitor-avatar-sm" style="background: {{ $avatarColor }};">{{ $initials }}</div>
                                        <div>
                                            <div class="lead-cell-name">
                                                {{ $visitorName ?? 'Anonymous' }}
                                                @if($isNew)<span class="new-pill">NEW</span>@endif
                                            </div>
                                            <div class="lead-cell-meta">
                                                @if($visitorEmail)
                                                    <div><i class="bi bi-envelope me-1"></i><a href="mailto:{{ $visitorEmail }}">{{ $visitorEmail }}</a></div>
                                                @endif
                                                @if($visitorPhone)
                                                    <div><i class="bi bi-telephone me-1"></i><a href="tel:{{ $visitorPhone }}">{{ $visitorPhone }}</a></div>
                                                @endif
                                                @if(!$visitorEmail && !$visitorPhone)
                                                    <span class="text-muted">No contact info</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="col-form">
                                    <div class="form-name-cell">{{ $submission->customForm->name ?? 'N/A' }}</div>
                                    @if($submission->formVersion)
                                        <div class="form-version-cell">Version {{ $submission->formVersion->version_number }}</div>
                                    @endif
                                </td>
                                <td class="col-message">
                                    @if($visitorMessage)
                                        <div class="message-cell">{{ $visitorMessage }}</div>
                                    @else
                                        <span class="text-muted small">{{ count($submission->submission_data ?? []) }} field(s)</span>
                                    @endif
                                </td>
                                <td class="col-date">
                                    <div class="date-cell-main">{{ $submission->created_at->format('M d, Y') }}</div>
                                    <div class="date-cell-sub">{{ $submission->created_at->format('h:i A') }}</div>
                                </td>
                                @if($isAdmin)
                                    <td class="col-status">
                                        <span class="status-badge {{ $status }}">
                                            <span class="status-dot"></span>
                                            @if($status === 'pending')
                                                Pending
                                            @elseif($status === 'hired')
                                                Hired
                                            @else
                                                Cancelled
                                            @endif
                                        </span>
                                    </td>
                                @endif
                                <td class="col-actions">
                                    <div class="action-buttons">
                                        <a href="{{ route('submissions.show', $submission->id) }}" class="btn btn-view">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        @if($visitorEmail)
                                            <button type="button"
                                                    class="btn btn-view js-reply-btn"
                                                    data-submission-id="{{ $submission->id }}"
                                                    data-reply-url="{{ route('submissions.reply', $submission) }}"
                                                    data-to-email="{{ $visitorEmail }}"
                                                    data-to-name="{{ $visitorName ?? '' }}"
                                                    data-default-subject="Re: {{ $submission->customForm->name ?? 'Your enquiry' }}">
                                                <i class="bi bi-reply"></i> Reply
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-view" disabled title="No email on this lead">
                                                <i class="bi bi-reply"></i> Reply
                                            </button>
                                        @endif
                                        @if($isAdmin && $status === 'pending')
                                            <form id="hire-form-{{ $submission->id }}" action="{{ route('submissions.hire', $submission) }}" method="POST">
                                                @csrf
                                                <button type="button"
                                                        class="btn btn-hire js-lead-action-btn"
                                                        data-action="hire"
                                                        data-form-id="hire-form-{{ $submission->id }}"
                                                        data-lead-name="{{ $visitorName ?? 'this lead' }}">
                                                    <i class="bi bi-check-lg"></i> Hire
                                                </button>
                                            </form>
                                            <form id="cancel-form-{{ $submission->id }}" action="{{ route('submissions.cancel', $submission) }}" method="POST">
                                                @csrf
                                                <button type="button"
                                                        class="btn btn-cancel js-lead-action-btn"
                                                        data-action="cancel"
                                                        data-form-id="cancel-form-{{ $submission->id }}"
                                                        data-lead-name="{{ $visitorName ?? 'this lead' }}">
                                                    <i class="bi bi-x-lg"></i> Cancel
                                                </button>
                                            </form>
                                        @elseif($isAdmin && $status === 'hired')
                                            @php
                                                $leadInvoice = $submission->invoice
                                                    ?? \App\Models\Invoice::where('form_submission_id', $submission->id)->first();
                                            @endphp
                                            @if($leadInvoice)
                                                <a href="{{ route('invoices.edit', $leadInvoice) }}" class="btn btn-hire">
                                                    <i class="bi bi-receipt"></i> Invoice / Resend
                                                </a>
                                            @else
                                                <a href="{{ route('invoices.index') }}" class="btn btn-view">
                                                    <i class="bi bi-receipt"></i> Invoices
                                                </a>
                                            @endif
                                        @endif
                                        @can('delete', $submission)
                                            <form id="delete-form-{{ $submission->id }}" action="{{ route('submissions.destroy', $submission->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        class="btn btn-delete js-lead-action-btn"
                                                        title="Delete"
                                                        data-action="delete"
                                                        data-form-id="delete-form-{{ $submission->id }}"
                                                        data-lead-name="{{ $visitorName ?? 'this lead' }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $submissions->links() }}
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #dee2e6;"></i>
                <h5 class="mt-3 mb-2">{{ $isAdmin ? 'No leads found' : 'No submissions found' }}</h5>
                <p class="text-muted mb-4">
                    @if(request()->hasAny(['form_id','client_id','shortcode_id','date_from','date_to','status']))
                        No submissions match the current filters. Try changing client, shortcode, form, or date range.
                    @else
                        Start creating forms and generating shortcodes to receive submissions!
                    @endif
                </p>
                @if(request()->hasAny(['form_id','client_id','shortcode_id','date_from','date_to','status']))
                    <a href="{{ route('submissions.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-1"></i> View All Submissions
                    </a>
                @else
                    <a href="{{ route('add_new_form') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Create Your First Form
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

@stop

@section('footer')
{{-- Modal must live near body end so backdrop/click works above page layout --}}
<div class="modal fade lead-action-modal" id="leadActionModal" tabindex="-1" aria-labelledby="leadActionModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="leadActionModalLabel">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="modal-icon hire" id="leadActionModalIcon">
                    <i class="bi bi-check-lg"></i>
                </div>
                <p class="mb-1 fs-5 fw-semibold" id="leadActionModalTitle">Are you sure?</p>
                <p class="text-muted mb-0" id="leadActionModalMessage"></p>
            </div>
            <div class="modal-footer justify-content-center border-0 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">No, Go Back</button>
                <button type="button" class="btn btn-primary px-4" id="leadActionConfirmBtn">Yes, Confirm</button>
            </div>
        </div>
    </div>
</div>

{{-- Reply compose: send email to lead from admin panel --}}
<div class="modal fade lead-action-modal" id="leadReplyModal" tabindex="-1" aria-labelledby="leadReplyModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
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

<script>
(function () {
    function initLeadActionModal() {
        var modalEl = document.getElementById('leadActionModal');
        if (!modalEl || typeof bootstrap === 'undefined') return;

        // Move modal to <body> so dark backdrop sits behind popup, not over it
        if (modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }

        var modal = bootstrap.Modal.getOrCreateInstance(modalEl, {
            backdrop: true,
            keyboard: true,
            focus: true
        });

        var iconEl = document.getElementById('leadActionModalIcon');
        var titleEl = document.getElementById('leadActionModalTitle');
        var messageEl = document.getElementById('leadActionModalMessage');
        var confirmBtn = document.getElementById('leadActionConfirmBtn');
        var pendingFormId = null;

        var actionConfig = {
            hire: {
                iconClass: 'hire',
                icon: 'bi-check-lg',
                title: 'Mark as Hired?',
                message: function (name) {
                    return 'Are you sure you want to hire <span class="lead-name-highlight">' + name + '</span>? This lead will be marked as hired.';
                },
                btnClass: 'btn-success',
                btnText: 'Yes, Hire'
            },
            cancel: {
                iconClass: 'cancel',
                icon: 'bi-x-lg',
                title: 'Cancel this lead?',
                message: function (name) {
                    return 'Are you sure you want to cancel <span class="lead-name-highlight">' + name + '</span>? This action can be reviewed later.';
                },
                btnClass: 'btn-danger',
                btnText: 'Yes, Cancel'
            },
            delete: {
                iconClass: 'delete',
                icon: 'bi-trash',
                title: 'Delete submission?',
                message: function (name) {
                    return 'Are you sure you want to permanently delete the submission from <span class="lead-name-highlight">' + name + '</span>? This cannot be undone.';
                },
                btnClass: 'btn-danger',
                btnText: 'Yes, Delete'
            }
        };

        document.querySelectorAll('.js-lead-action-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var action = this.getAttribute('data-action');
                var formId = this.getAttribute('data-form-id');
                var leadName = this.getAttribute('data-lead-name') || 'this lead';
                var config = actionConfig[action];
                if (!config || !formId) return;

                pendingFormId = formId;
                iconEl.className = 'modal-icon ' + config.iconClass;
                iconEl.innerHTML = '<i class="bi ' + config.icon + '"></i>';
                titleEl.textContent = config.title;
                messageEl.innerHTML = config.message(leadName);
                confirmBtn.className = 'btn px-4 ' + config.btnClass;
                confirmBtn.textContent = config.btnText;
                modal.show();
            });
        });

        confirmBtn.addEventListener('click', function () {
            if (!pendingFormId) return;
            var form = document.getElementById(pendingFormId);
            if (form) form.submit();
        });

        modalEl.addEventListener('hidden.bs.modal', function () {
            pendingFormId = null;
        });
    }

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

    function initAll() {
        initLeadActionModal();
        initLeadReplyModal();
    }

    // Bootstrap loads AFTER footer in layout, so wait until window load
    if (document.readyState === 'complete') {
        initAll();
    } else {
        window.addEventListener('load', initAll);
    }
})();
</script>
@stop
