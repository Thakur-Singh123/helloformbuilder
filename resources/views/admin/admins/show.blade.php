@extends('user.include.layout')
@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.admins.index') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left me-1"></i> All Admins</a>
        <h1 class="h3 mt-2 mb-1">{{ $admin->name ?? $admin->first_name . ' ' . $admin->last_name }}</h1>
        <p class="text-muted small mb-0">{{ $admin->email }}</p>
    </div>
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            @include('alerts')
            <h5 class="card-title">Summary</h5>
            <div class="row">
                <div class="col-md-3"><strong>Users (under this admin)</strong><br>{{ $usersCount }}</div>
                <div class="col-md-3"><strong>Forms</strong><br>{{ $formsCount }}</div>
                <div class="col-md-3"><strong>Submissions</strong><br>{{ $submissionsCount }}</div>
                <div class="col-md-3"><strong>Status</strong><br>@if($admin->is_active)<span class="badge bg-success">Active</span>@else<span class="badge bg-secondary">Inactive</span>@endif</div>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h5 class="card-title">Recent forms (up to 20)</h5>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="table-light"><tr><th>Form</th><th>Status</th><th>Created</th></tr></thead>
                    <tbody>
                        @forelse($forms as $f)
                        <tr><td>{{ $f->name }}</td><td>{{ $f->status ?? '—' }}</td><td>{{ $f->created_at ? $f->created_at->format('M j, Y') : '—' }}</td></tr>
                        @empty
                        <tr><td colspan="3" class="text-muted">No forms.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Recent submissions (last 10)</h5>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="table-light"><tr><th>Form</th><th>Submitted</th></tr></thead>
                    <tbody>
                        @forelse($recentSubmissions as $s)
                        <tr><td>{{ $s->customForm->name ?? '—' }}</td><td>{{ $s->created_at ? $s->created_at->format('M j, Y H:i') : '—' }}</td></tr>
                        @empty
                        <tr><td colspan="2" class="text-muted">No submissions.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
