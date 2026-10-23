@extends('user.include.layout')

@section('content')
<style>.af-card:hover{transform:none!important}</style>

@php
    $nm = fn ($u) => $u ? (trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')) ?: ($u->name ?? $u->email)) : '—';
@endphp

<div class="mb-3">
    <h2 class="h4 mb-1">Client Template Forms</h2>
    <p class="text-muted small mb-0">Forms your clients created from templates and the responses they received.</p>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card af-card"><div class="card-body">
            <div class="text-muted small text-uppercase">Clients</div>
            <div class="h3 mb-0 text-primary">{{ $stats['clients'] }}</div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card af-card"><div class="card-body">
            <div class="text-muted small text-uppercase">Forms created</div>
            <div class="h3 mb-0 text-success">{{ $stats['forms'] }}</div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card af-card"><div class="card-body">
            <div class="text-muted small text-uppercase">Responses received</div>
            <div class="h3 mb-0 text-warning">{{ $stats['responses'] }}</div>
        </div></div>
    </div>
</div>

<form method="GET" class="card af-card mb-3">
    <div class="card-body d-flex flex-wrap gap-2 align-items-end">
        <div>
            <label class="form-label small fw-semibold mb-1">Client</label>
            <select name="client" class="form-select" style="min-width:260px">
                <option value="">All clients</option>
                @foreach ($owners as $o)
                    <option value="{{ $o->id }}" @selected(request('client') == $o->id)>
                        {{ $nm($o) }} ({{ $o->email }})@if($o->id === currentUser()->id) — You @endif
                    </option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary">Filter</button>
        <a href="{{ route('admin_forms.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="card af-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Sr.No</th>
                    <th>Client</th>
                    <th>Form</th>
                    <th>Status</th>
                    <th>Responses</th>
                    <th>Last response</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($forms as $f)
                <tr>
                    <td class="text-muted">{{ $loop->iteration }}.</td>
                    <td>
                        @if ($f->user_id === currentUser()->id)
                            <span class="text-muted small">You</span>
                        @else
                            <div class="fw-semibold">{{ $nm($f->user) }}</div>
                            <div class="text-muted small">{{ $f->user->email ?? '' }}</div>
                        @endif
                    </td>
                    <td class="fw-semibold">{{ $f->title }}</td>
                    <td><span class="badge {{ $f->status === 'published' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($f->status) }}</span></td>
                    <td>{{ $f->responses_count }}</td>
                    <td class="text-muted small text-nowrap">
                        {{ $f->responses_max_created_at ? \Carbon\Carbon::parse($f->responses_max_created_at)->timezone('Asia/Kolkata')->format('d M Y, h:i A') : '—' }}
                    </td>
                    <td class="text-muted small text-nowrap">{{ $f->created_at?->timezone('Asia/Kolkata')->format('d M Y') }}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin_forms.responses', $f) }}" class="btn btn-sm btn-outline-primary">view</a>
                        <!-- @if ($f->status === 'published')
                            <a href="{{ route('forms.public', $f->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Open</a>
                        @endif
                        @if ($f->user_id === currentUser()->id)
                            <a href="{{ route('forms.edit', $f) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        @endif -->
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-5">No template forms yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection