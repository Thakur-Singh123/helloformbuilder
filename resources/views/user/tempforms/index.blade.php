@extends('user.include.layout')

@section('content')
<style>.mf-card:hover{transform:none!important}</style>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h2 class="h4 mb-1">Forms</h2>
        <p class="text-muted small mb-0">Forms you created from templates and the responses they received.</p>
    </div>
    <a href="{{ route('templates.index') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> New from template</a>
</div>

<div class="card mf-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Sr.No</th>
                    <th>Form</th>
                    <th>Status</th>
                    <th>Responses</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($forms as $f)
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td class="fw-semibold">{{ $f->title }}</td>
                    <td>
                        <span class="badge {{ $f->status === 'published' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($f->status) }}</span>
                    </td>
                    <td>{{ $f->responses_count }}</td>
                    <td class="text-muted small">{{ optional($f->created_at)->format('d M Y') }}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('forms.responses', $f) }}" class="btn btn-sm btn-outline-primary">view</a>
                        <!-- <a href="{{ route('forms.edit', $f) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        @if ($f->status === 'published')
                            <a href="{{ route('forms.public', $f->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Open</a>
                        @endif -->
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        You haven't created any forms yet.
                        <a href="{{ route('templates.index') }}">Pick a template</a> to start.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection