@extends('user.include.layout')
@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.clients.index') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left me-1"></i> Clients</a>
        <h1 class="h3 mt-2 mb-0">Client: {{ $client->name ?? $client->first_name . ' ' . $client->last_name }}</h1>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-2 text-muted">Name</dt>
                <dd class="col-sm-10">{{ $client->name ?? $client->first_name . ' ' . $client->last_name }}</dd>
                <dt class="col-sm-2 text-muted">Email</dt>
                <dd class="col-sm-10">{{ $client->email }}</dd>
                <dt class="col-sm-2 text-muted">Website</dt>
                <dd class="col-sm-10">{{ $client->website ?: '—' }}</dd>
                <dt class="col-sm-2 text-muted">Status</dt>
                <dd class="col-sm-10">
                    @if($client->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </dd>
            </dl>
            <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil me-1"></i> Edit Client</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0">
            <h5 class="mb-0">Shortcodes assigned to this client</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Form Name</th>
                            <th>Website (shortcode)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($client->shortcodes as $sc)
                        <tr data-shortcode-id="{{ $sc->id }}">
                            <td><code class="small">{{ $sc->code }}</code></td>
                            <td>{{ $sc->customForm->name ?? 'N/A' }}</td>
                            <td>{{ $sc->website ?: '—' }}</td>
                            <td>
                                <a href="{{ route('shortcode.render', $sc->code) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="View form"><i class="bi bi-eye"></i></a>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-1 shortcode-delete-btn" data-id="{{ $sc->id }}" data-code="{{ $sc->code }}" title="Delete shortcode"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-muted text-center py-4">No shortcodes assigned. Assign from <a href="{{ route('shortcodes.index') }}">Shortcodes</a>.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Submissions for this client</h5>
            <a href="{{ route('submissions.index') }}?client_id={{ $client->id }}" class="btn btn-sm btn-outline-primary">View all in Submissions</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Form</th>
                            <th>Submitted at</th>
                            <th>Preview</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $sub)
                        <tr>
                            <td>{{ $sub->customForm->name ?? 'N/A' }}</td>
                            <td>{{ $sub->created_at->format('Y-m-d H:i') }}</td>
                            <td class="small text-muted">
                                @if(!empty($sub->submission_data))
                                    @php $first = collect($sub->submission_data)->take(2); @endphp
                                    @foreach($first as $field)
                                        @php
                                            $val = $field['value'] ?? '';
                                            $val = is_array($val) ? implode(', ', $val) : $val;
                                            $val = strlen($val) > 30 ? substr($val, 0, 30) . '…' : $val;
                                        @endphp
                                        {{ $field['field_name'] ?? '' }}: {{ $val }}{{ $loop->last ? '' : ' · ' }}
                                    @endforeach
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('submissions.show', $sub) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-muted text-center py-4">No submissions yet. Submissions from this client's shortcodes will appear here and are also sent to the client by email.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($submissions->count() >= 50)
                <p class="small text-muted mb-0 mt-2">Showing latest 50. <a href="{{ route('submissions.index') }}">View all submissions</a> to see more.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
document.querySelectorAll('.shortcode-delete-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var code = this.getAttribute('data-code');
        if (!confirm('Delete shortcode "' + code + '"?')) return;
        var row = document.querySelector('tr[data-shortcode-id="' + id + '"]');
        var url = "{{ url('user/shortcodes') }}/" + id;
        var body = new URLSearchParams();
        body.append('_token', '{{ csrf_token() }}');
        body.append('_method', 'DELETE');
        fetch(url, { method: 'POST', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: body })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success && row) row.remove();
                else if (data.message) alert(data.message);
            })
            .catch(function() { alert('Error deleting shortcode.'); });
    });
});
</script>
@endsection
