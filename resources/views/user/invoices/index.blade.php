@extends('user.include.layout')
@section('content')

<style>
    .inv-wrap { max-width: 1200px; margin: 0 auto; }
    .inv-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; }
    .inv-header h4 { margin: 0; font-weight: 700; color: #1e293b; }
    .stat-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.75rem; margin-bottom: 1.25rem; }
    .stat-card {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem 1.1rem;
        text-decoration: none; color: inherit; transition: box-shadow .15s, border-color .15s;
    }
    .stat-card:hover { box-shadow: 0 4px 12px rgba(15,23,42,.08); }
    .stat-card.active { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,.15); }
    .stat-card .label { font-size: .75rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
    .stat-card .value { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin-top: .25rem; }
    .inv-table-wrap { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; }
    .inv-table { width: 100%; margin: 0; }
    .inv-table thead th { background: #f8fafc; color: #64748b; font-size: .72rem; text-transform: uppercase; letter-spacing: .04em; font-weight: 700; padding: .85rem 1rem; border-bottom: 1px solid #e2e8f0; }
    .inv-table tbody td { padding: .9rem 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; font-size: .9rem; }
    .badge-status { display: inline-flex; align-items: center; gap: .35rem; padding: .35rem .75rem; border-radius: 999px; font-size: .72rem; font-weight: 700; text-transform: uppercase; }
    .badge-status.draft { background: #fef3c7; color: #b45309; }
    .badge-status.sent { background: #dcfce7; color: #15803d; }
    .filter-bar { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1rem; }
</style>

<div class="inv-wrap">
    <div class="inv-header">
        <div>
            <h4>Invoices</h4>
            <p class="text-muted mb-0 small">Draft invoices are created automatically when a lead is hired. Set the amount, then send or resend.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="stat-cards">
        <a href="{{ route('invoices.index') }}" class="stat-card {{ !request('status') ? 'active' : '' }}">
            <div class="label">All</div>
            <div class="value">{{ $statusCounts['all'] ?? 0 }}</div>
        </a>
        <a href="{{ route('invoices.index', ['status' => 'draft']) }}" class="stat-card {{ request('status') === 'draft' ? 'active' : '' }}">
            <div class="label">Draft</div>
            <div class="value">{{ $statusCounts['draft'] ?? 0 }}</div>
        </a>
        <a href="{{ route('invoices.index', ['status' => 'sent']) }}" class="stat-card {{ request('status') === 'sent' ? 'active' : '' }}">
            <div class="label">Sent</div>
            <div class="value">{{ $statusCounts['sent'] ?? 0 }}</div>
        </a>
    </div>

    <form method="GET" action="{{ route('invoices.index') }}" class="filter-bar">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" style="max-width:280px" placeholder="Search name, email, invoice #">
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Search</button>
        @if(request('q'))
            <a href="{{ route('invoices.index', request()->only('status')) }}" class="btn btn-outline-secondary">Clear</a>
        @endif
    </form>

    <div class="inv-table-wrap">
        <div class="table-responsive">
            <table class="inv-table table mb-0">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Form / Lead</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Times sent</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td>
                                <strong>{{ $invoice->invoice_number }}</strong>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $invoice->customer_name ?: '—' }}</div>
                                <div class="text-muted small">{{ $invoice->customer_email ?: 'No email' }}</div>
                                @if($invoice->customer_phone)
                                    <div class="text-muted small">{{ $invoice->customer_phone }}</div>
                                @endif
                            </td>
                            <td>
                                <div>{{ $invoice->submission->customForm->form_name ?? 'Form' }}</div>
                                <div class="text-muted small">Lead #{{ $invoice->form_submission_id }}</div>
                            </td>
                            <td>
                                @if($invoice->amount !== null)
                                    @php $sym = (($invoice->currency ?? 'INR') === 'USD') ? '$' : '₹'; @endphp
                                    {{ $sym }}{{ number_format((float) $invoice->amount, 2) }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-status {{ $invoice->status }}">{{ $invoice->status }}</span>
                            </td>
                            <td class="text-muted small">
                                {{ (int) ($invoice->send_logs_count ?? 0) }}
                            </td>
                            <td class="text-muted small">{{ $invoice->created_at?->format('M d, Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> {{ $invoice->isDraft() ? 'Edit & Send' : 'View / Resend' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                No invoices yet. Hire a lead to auto-create a draft invoice.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $invoices->links() }}
    </div>
</div>

@endsection
