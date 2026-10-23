@extends('user.include.layout')
@section('content')

    <!-- Main Dashboard Content -->
    <div class="container-fluid">
        
    +
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-1 fw-bold">Welcome back, {{ currentUser()->first_name ?? currentUser()->name }}! 👋</h2>
                                <p class="mb-0 opacity-75">Here's what's happening with your forms today.</p>
                            </div>
                            <div class="d-none d-md-block">
                                <i class="bi bi-graph-up-arrow" style="font-size: 4rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="row g-4 mb-4">
            
            <!-- Card 1: Active Forms -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" style="border-left: 4px solid #3b82f6 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 small fw-semibold text-uppercase">Active Forms</p>
                                <h2 class="mb-0 fw-bold text-primary">{{ number_format($activeForms) }}</h2>
                                <small class="text-muted">Currently active</small>
                            </div>
                            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                                <i class="bi bi-file-earmark-text-fill text-white"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('list_view') }}" class="text-decoration-none small">
                                View all forms <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Submissions -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" style="border-left: 4px solid #10b981 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 small fw-semibold text-uppercase">Total Submissions</p>
                                <h2 class="mb-0 fw-bold text-success">{{ number_format($totalSubmissions) }}</h2>
                                <small class="text-muted">All time</small>
                            </div>
                            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                <i class="bi bi-inboxes-fill text-white"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('submissions.index') }}" class="text-decoration-none small">
                                View submissions <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Shortcodes -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" style="border-left: 4px solid #f59e0b !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 small fw-semibold text-uppercase">Shortcodes</p>
                                <h2 class="mb-0 fw-bold text-warning">{{ number_format($totalShortcodes) }}</h2>
                                <small class="text-muted">Embed codes created</small>
                            </div>
                            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                <i class="bi bi-code-square text-white"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('shortcodes.index') }}" class="text-decoration-none small">
                                Manage shortcodes <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: Recent Submissions -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" style="border-left: 4px solid #8b5cf6 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 small fw-semibold text-uppercase">Last 7 Days</p>
                                <h2 class="mb-0 fw-bold text-purple">{{ number_format($submissionsLast7Days) }}</h2>
                                <small class="text-muted">New submissions</small>
                            </div>
                            <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                                <i class="bi bi-graph-up text-white"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('submissions.index') }}" class="text-decoration-none small">
                                View recent <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /Stats Row -->

        <!-- Main Content Row -->
        <div class="row g-4">
            
            <!-- Recent Submissions Card -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-clock-history me-2 text-primary"></i>Recent Submissions
                            </h5>
                            <a href="{{ route('submissions.index') }}" class="btn btn-sm btn-outline-primary">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($recentSubmissions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Form</th>
                                            <th>Submitted</th>
                                            <th>Time</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentSubmissions as $submission)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                                                <i class="bi bi-file-earmark-text text-primary"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-0">{{ $submission->customForm->name ?? 'N/A' }}</h6>
                                                            <small class="text-muted">
                                                                {{ count($submission->submission_data ?? []) }} fields
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success bg-opacity-10 text-success">
                                                        <i class="bi bi-check-circle me-1"></i>Received
                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $submission->created_at->diffForHumans() }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <a href="{{ route('submissions.show', $submission->id) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                <p class="text-muted mt-3 mb-0">No submissions yet. @if(currentUser()->isClient())Submissions from your shortcodes will appear here.@else Start creating forms to receive submissions!@endif</p>
                                @if(!currentUser()->isClient())
                                <a href="{{ route('add_new_form') }}" class="btn btn-primary mt-3">
                                    <i class="bi bi-plus-circle me-2"></i>Create Your First Form
                                </a>
                                @else
                                <a href="{{ route('shortcodes.index') }}" class="btn btn-outline-primary mt-3"><i class="bi bi-code-square me-2"></i>View shortcodes</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Recent Forms Card -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-lightning-charge me-2 text-warning"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if(!currentUser()->isClient())
                            <a href="{{ route('add_new_form') }}" class="btn btn-primary btn-lg rounded-pill">
                                <i class="bi bi-plus-circle-fill me-2"></i> Create New Form
                            </a>
                            <a href="{{ route('shortcodes.create') }}" class="btn btn-outline-primary rounded-pill">
                                <i class="bi bi-code-square me-2"></i> Generate Shortcode
                            </a>
                            @endif
                            <a href="{{ route('list_view') }}" class="btn btn-outline-secondary rounded-pill">
                                <i class="bi bi-list-ul me-2"></i> Manage Forms
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Forms -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-file-earmark-text me-2 text-info"></i>Recent Forms
                            </h5>
                            <a href="{{ route('list_view') }}" class="btn btn-sm btn-outline-primary">
                                All
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($recentForms->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentForms as $form)
                                    <a href="{{ route('edit_form', $form->id) }}" 
                                       class="list-group-item list-group-item-action border-0 px-0">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $form->name }}</h6>
                                                <small class="text-muted">
                                                    {{ $form->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <span class="badge bg-{{ $form->status === 'active' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $form->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($form->status) }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-file-earmark" style="font-size: 2rem; color: #dee2e6;"></i>
                                <p class="text-muted mt-2 mb-0 small">No forms yet</p>
                                @if(!currentUser()->isClient())
                                <a href="{{ route('add_new_form') }}" class="btn btn-sm btn-primary mt-2">
                                    Create Form
                                </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                @if(currentUser()->isSuperAdmin() && $recentLoginActivities->isNotEmpty())
                <!-- Recent User Logins (Super Admin only) -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-person-check me-2 text-success"></i>Recent User Logins
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>When</th>
                                        <th>IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentLoginActivities as $activity)
                                        @php $account = $activity->account(); @endphp
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $account ? trim(($account->first_name ?? $account->name ?? '—') . ' ' . ($account->last_name ?? '')) : '—' }}</strong>
                                                    <br><small class="text-muted">{{ $account->email ?? '—' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $activity->user_type === 'superadmin' ? 'danger' : ($activity->user_type === 'admin' ? 'primary' : 'secondary') }}">
                                                    {{ ucfirst($activity->user_type) }}
                                                </span>
                                            </td>
                                            <td><small>{{ $activity->logged_at->diffForHumans() }}</small></td>
                                            <td><small class="text-muted">{{ $activity->ip_address ?? '—' }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>
        <!-- /Main Content Row -->

    </div>
    <!-- /Main Dashboard Content -->

    <style>
        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .text-purple {
            color: #8b5cf6 !important;
        }
        
        .card {
            border-radius: 12px;
        }
        
        .table th {
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
        }
        
        .list-group-item-action:hover {
            background-color: #f8f9fa;
            border-radius: 8px;
        }
    </style>

@stop

@section('footer')
@stop
