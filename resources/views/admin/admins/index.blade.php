@extends('user.include.layout')
@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Admins</h1>
            <p class="text-muted small mb-0">Companies using the software. Create, edit or deactivate.</p>
        </div>
        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Create Admin</a>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('alerts')
            <form method="GET" action="{{ route('admin.admins.index') }}" class="mb-4">
                <div class="input-group" style="max-width: 320px;">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i> Search</button>
                    @if(request('search'))
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary">Clear</a>
                    @endif
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                             
                            <th>Clients</th>
                            <th>Forms</th>
                            <th>Submissions</th>
                            <th>Joined</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins as $a)
                        <tr>
                            <td>{{ $a['name'] ?: $a['email'] }}</td>
                            <td>{{ $a['email'] }}</td>
                            <td>
                                @if($a['is_active'])
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                             <td>{{ $a['clients_count'] ?? 0 }}</td>
                            <td>{{ $a['forms_count'] }}</td>
                            <td>{{ $a['submissions_count'] }}</td>
                            <td>{{ $a['created_at'] ? $a['created_at']->format('M j, Y') : '—' }}</td>
                            <td>
                                <a href="{{ route('admin.admins.show', $a['id']) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('admin.admins.edit', $a['id']) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                @if($a['is_active'])
                                    <form action="{{ route('admin.admins.deactivate', $a['id']) }}" method="POST" class="d-inline">@csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning" onclick="return confirm('Deactivate this Admin?');">Deactivate</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.admins.activate', $a['id']) }}" method="POST" class="d-inline">@csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">Activate</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.admins.destroy', $a['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('Permanently delete this Admin and ALL related data (clients, forms, shortcodes, submissions, users)? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-muted text-center py-4">No admins found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                <div class="text-muted small">
                    Showing {{ $admins->firstItem() ?? 0 }} to {{ $admins->lastItem() ?? 0 }} of {{ $admins->total() }} admins
                </div>
                <div>
                    {{ $admins->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
