@extends('user.include.layout')

@section('content')
<style>.mf-card:hover{transform:none!important}</style>

<a href="{{ route('forms.index') }}" class="small text-decoration-none"><i class="bi bi-arrow-left"></i>Forms</a>

<div class="my-3">
    <h2 class="h4 mb-1">{{ $form->title }}</h2>
    <p class="text-muted small mb-0">{{ $responses->total() }} {{ \Illuminate\Support\Str::plural('response', $responses->total()) }}</p>
</div>

<div class="card mf-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Sr.No</th>
                    <th class="text-nowrap">Submitted</th>
                    @foreach ($columns as $col)
                        <th class="text-nowrap">{{ $col['label'] ?? 'Field' }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @forelse ($responses as $r)
                @php $byId = collect($r->data)->keyBy('id'); @endphp
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td class="text-nowrap text-muted small">{{ $r->created_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}</td>
                    @foreach ($columns as $col)
                        @php $v = $byId->get($col['id'])['value'] ?? ''; @endphp
                        <td>{{ is_array($v) ? implode(', ', $v) : $v }}</td>
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="{{ $columns->count() + 2 }}" class="text-center text-muted py-5">No responses yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $responses->links('pagination::bootstrap-5') }}</div>
@endsection