@extends('user.include.layout')
@section('content')
    <div class="container-fluid px-0 ps-lg-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div class="min-w-0">
                <h1 class="h3 mb-1">Clients</h1>
                <p class="text-muted small mb-0">Website owners (basic info only). Assign shortcodes to clients.</p>
            </div>
            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary flex-shrink-0"><i class="bi bi-plus-lg me-1"></i> Add Client</a>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @include('alerts')
                <div class="table-responsive overflow-x-auto">
                    <table class="table table-hover align-middle mb-0" style="min-width: 600px;">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 120px;">Name</th>
                                <th style="min-width: 140px;">Email</th>
                                <th style="min-width: 120px;">Website</th>
                                <th style="min-width: 80px;">Status</th>
                                <th style="min-width: 140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $c)
                            <tr data-client-id="{{ $c->id }}">
                                <td>{{ $c->name ?? $c->first_name . ' ' . $c->last_name }}</td>
                                <td>{{ $c->email }}</td>
                                <td>{{ $c->website ?: '—' }}</td>
                                <td>
                                    @if($c->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.clients.show', $c->id) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.clients.edit', $c->id) }}" class="btn btn-sm btn-outline-secondary ms-1" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-1 client-delete-btn" data-id="{{ $c->id }}" data-name="{{ $c->name ?? $c->email }}" title="Delete"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-muted text-center py-4">No clients yet. Add a client to assign shortcodes.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
<script>
document.querySelectorAll('.client-delete-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var name = this.getAttribute('data-name');
        if (!confirm('Delete client "' + name + '"? Their shortcode assignments will be cleared.')) return;
        var row = document.querySelector('tr[data-client-id="' + id + '"]');
        var url = "{{ url('admin/clients') }}/" + id;
        var body = new URLSearchParams();
        body.append('_token', '{{ csrf_token() }}');
        body.append('_method', 'DELETE');
        fetch(url, { method: 'POST', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: body })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success && row) row.remove();
                else if (data.message) alert(data.message);
            })
            .catch(function() { alert('Error deleting client.'); });
    });
});
</script>
@endsection
