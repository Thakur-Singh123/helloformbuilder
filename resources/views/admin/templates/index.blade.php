@extends('user.include.layout')
@section('content')
<style>.at-card:hover{transform:none!important}</style>
@include('admin.partials.notification')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h2 class="h4 mb-1">Form Templates</h2>
        <p class="text-muted small mb-0">Templates shown in the public gallery. Create new ones here.</p>
    </div>
    <a href="{{ route('admin_templates.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> New template</a>
</div>
<div class="card at-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Sr.No</th>
                    <th>Template</th>
                    <th>Category</th>
                    <th>Fields</th>
                    <th>Used</th>
                    <th>Popular</th>
                    <th>Active</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($templates as $t)
                <tr>
                    <td class="text-muted">{{ $templates->firstItem() + $loop->index }}.</td>
                    <td>
                        <div class="fw-semibold">{{ $t->title }}</div>
                        <div class="text-muted small">{{ \Illuminate\Support\Str::limit($t->description, 60) }}</div>
                    </td>
                    <td>{{ $t->category->name ?? '—' }}</td>
                    <td>{{ $t->fields_count }}</td>
                    <td>{{ $t->uses_count }}</td>
                    @foreach (['is_popular', 'is_active'] as $col)
                        <td>
                            <form method="POST" action="{{ route('admin_templates.toggle', $t->id) }}">
                                @csrf
                                <input type="hidden" name="field" value="{{ $col }}">
                                <button class="btn btn-sm {{ $t->$col ? 'btn-success' : 'btn-outline-secondary' }}">{{ $t->$col ? 'Yes' : 'No' }}</button>
                            </form>
                        </td>
                    @endforeach
                    <td class="text-end text-nowrap">
                        <a href="{{ route('templates.show', $t) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
                        <a href="{{ route('admin_templates.edit', $t->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                       <form method="POST" action="{{ route('admin_templates.destroy', $t->id) }}" class="d-inline js-delete-form"
                            data-name="{{ $t->title }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-5">No templates yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@if ($templates->hasPages())
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
        <div class="text-muted small">
            Showing {{ $templates->firstItem() }}–{{ $templates->lastItem() }} of {{ $templates->total() }} templates
        </div>
        {{ $templates->links('pagination::bootstrap-5') }}
    </div>
@endif
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
<script>
document.querySelectorAll('.js-delete-form').forEach(function (form) {
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Delete this template?',
            text: '"' + form.dataset.name + '" will be removed from the Template. This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            reverseButtons: false,
            focusCancel: true
        }).then(function (result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endsection
