@extends('user.include.layout')
@section('content')

<style>
    .inv-edit { max-width: 720px; margin: 0 auto; }
    .inv-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.5rem; }
    .inv-meta { background: #f8fafc; border-radius: 10px; padding: 1rem; margin-bottom: 1.25rem; font-size: .9rem; }
    .badge-status { display: inline-flex; padding: .35rem .75rem; border-radius: 999px; font-size: .72rem; font-weight: 700; text-transform: uppercase; }
    .badge-status.draft { background: #fef3c7; color: #b45309; }
    .badge-status.sent { background: #dcfce7; color: #15803d; }
    /* Custom confirm: avoids Bootstrap backdrop z-index conflicts in dashboard layout */
    .send-confirm-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 99999;
        background: rgba(15, 23, 42, 0.55);
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .send-confirm-overlay.is-open { display: flex; }
    .send-confirm-box {
        background: #fff;
        border-radius: 14px;
        width: 100%;
        max-width: 420px;
        padding: 1.5rem 1.5rem 1.25rem;
        box-shadow: 0 20px 50px rgba(0,0,0,.25);
        text-align: center;
        position: relative;
    }
    .send-confirm-box .modal-icon {
        width: 52px; height: 52px; border-radius: 50%;
        background: #dcfce7; color: #15803d;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 1.4rem; margin-bottom: .75rem;
    }
    .send-confirm-box .btn-close-x {
        position: absolute; top: .75rem; right: .75rem;
        border: 0; background: transparent; font-size: 1.25rem;
        line-height: 1; color: #64748b; cursor: pointer;
    }
    .send-confirm-actions { display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap; margin-top: 1.25rem; }
    .send-history { margin-top: 1.5rem; border-top: 1px solid #e2e8f0; padding-top: 1.25rem; }
    .send-history h6 { font-weight: 700; margin-bottom: .75rem; color: #0f172a; }
    .send-history-list { list-style: none; padding: 0; margin: 0; }
    .send-history-list li {
        display: flex; justify-content: space-between; align-items: flex-start; gap: .75rem;
        padding: .75rem 0; border-bottom: 1px solid #f1f5f9; font-size: .875rem;
    }
    .send-history-list li:last-child { border-bottom: 0; }
    .send-history .badge-send {
        display: inline-flex; padding: .2rem .55rem; border-radius: 999px;
        font-size: .68rem; font-weight: 700; text-transform: uppercase;
    }
    .send-history .badge-send.first { background: #dbeafe; color: #1d4ed8; }
    .send-history .badge-send.resend { background: #ffedd5; color: #c2410c; }
</style>

<div class="inv-edit">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <a href="{{ route('invoices.index') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left"></i> Back to Invoices</a>
            <h4 class="mb-0 mt-1 fw-bold">{{ $invoice->invoice_number }}</h4>
        </div>
        <span class="badge-status {{ $invoice->status }}">{{ $invoice->status }}</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="inv-card">
        <div class="inv-meta">
            <div><strong>Lead:</strong> #{{ $invoice->form_submission_id }}
                 — <a href="{{ route('submissions.show', $invoice->form_submission_id) }}">View lead</a>
            </div>
            <div><strong>Form:</strong> {{ $invoice->submission->customForm->form_name ?? '—' }}</div>
            @if($invoice->sent_at)
                <div><strong>Sent at:</strong> {{ $invoice->sent_at->format('M d, Y g:i A') }}</div>
            @endif
        </div>

        <form method="POST" action="{{ route('invoices.update', $invoice) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Customer name</label>
                    <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $invoice->customer_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Customer email</label>
                    <input type="email" name="customer_email" class="form-control" value="{{ old('customer_email', $invoice->customer_email) }}">
                    <div class="form-text">Required before sending.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Customer phone</label>
                    <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone', $invoice->customer_phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Amount</label>
                    @php
                        $selectedCurrency = old('currency', $invoice->currency ?? 'INR');
                        $currencySymbol = $selectedCurrency === 'USD' ? '$' : '₹';
                    @endphp
                    <div class="input-group">
                        <select name="currency" id="invoiceCurrency" class="form-select" style="max-width: 110px;">
                            <option value="INR" {{ $selectedCurrency === 'INR' ? 'selected' : '' }}>₹ INR</option>
                            <option value="USD" {{ $selectedCurrency === 'USD' ? 'selected' : '' }}>$ USD</option>
                        </select>
                        <span class="input-group-text" id="currencySymbol">{{ $currencySymbol }}</span>
                        <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ old('amount', $invoice->amount) }}">
                    </div>
                    <div class="form-text">Required before sending. Choose Rupees or Dollars.</div>
                </div>
                <div class="col-12">
                    <label class="form-label">Description / notes</label>
                    <textarea name="description" rows="4" class="form-control" placeholder="Service details, terms, etc.">{{ old('description', $invoice->description) }}</textarea>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save
                </button>
            </div>
        </form>

        <hr class="my-4">

        <form method="POST" action="{{ route('invoices.send', $invoice) }}" id="sendInvoiceForm">
            @csrf
            <button type="button" class="btn btn-success" id="sendInvoiceBtn"
                {{ (!$invoice->customer_email || $invoice->amount === null) ? 'disabled' : '' }}>
                <i class="bi bi-send"></i>
                {{ $invoice->isSent() ? 'Resend Invoice Email' : 'Send Invoice Email' }}
            </button>
            @if(!$invoice->customer_email || $invoice->amount === null)
                <div class="text-muted small mt-2">Save email + amount to enable send button.</div>
            @elseif($invoice->isSent())
                <div class="text-muted small mt-2">Already sent{{ $invoice->sent_at ? ' on '.$invoice->sent_at->format('M d, Y g:i A') : '' }}. You can resend anytime.</div>
            @endif
        </form>

        <!-- <div class="send-history">
            <h6><i class="bi bi-clock-history"></i> Send History
                <span class="text-muted fw-normal">({{ $invoice->sendLogs->count() }})</span>
            </h6>
            @if($invoice->sendLogs->count())
                <ul class="send-history-list">
                    @foreach($invoice->sendLogs as $log)
                        @php $sym = (($log->currency ?? 'INR') === 'USD') ? '$' : '₹'; @endphp
                        <li>
                            <div>
                                <span class="badge-send {{ $log->is_resend ? 'resend' : 'first' }}">
                                    {{ $log->is_resend ? 'Resend' : 'First send' }}
                                </span>
                                <div class="mt-1">
                                    To <strong>{{ $log->sent_to_email }}</strong>
                                    @if($log->amount !== null)
                                        · {{ $sym }}{{ number_format((float) $log->amount, 2) }}
                                    @endif
                                </div>
                            </div>
                            <div class="text-muted text-nowrap small">
                                {{ $log->created_at?->format('M d, Y g:i A') }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted small mb-0">
                    No send history yet. When you send or resend this invoice, each email will appear here.
                </p>
            @endif
        </div> -->
    </div>
</div>

{{-- Custom confirm overlay (moved to body via JS so black layer never blocks buttons) --}}
<div class="send-confirm-overlay" id="sendInvoiceModal" aria-hidden="true">
    <div class="send-confirm-box" role="dialog" aria-modal="true" aria-labelledby="sendInvoiceModalLabel">
        <button type="button" class="btn-close-x" id="cancelSendInvoiceX" aria-label="Close">&times;</button>
        <div class="modal-icon">
            <i class="bi bi-envelope-check"></i>
        </div>
        <h5 class="fw-bold mb-2" id="sendInvoiceModalLabel">
            {{ $invoice->isSent() ? 'Resend invoice email?' : 'Send invoice email?' }}
        </h5>
        <p class="text-muted mb-0">
            This will email invoice <strong>{{ $invoice->invoice_number }}</strong>
            to <strong>{{ $invoice->customer_email ?: 'the customer' }}</strong>.
        </p>
        <div class="send-confirm-actions">
            <button type="button" class="btn btn-outline-secondary px-4" id="cancelSendInvoice">Cancel</button>
            <button type="button" class="btn btn-success px-4" id="confirmSendInvoice">
                <i class="bi bi-send"></i> {{ $invoice->isSent() ? 'Resend Email' : 'Send Email' }}
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    var currencySelect = document.getElementById('invoiceCurrency');
    var currencySymbol = document.getElementById('currencySymbol');
    if (currencySelect && currencySymbol) {
        currencySelect.addEventListener('change', function () {
            currencySymbol.textContent = this.value === 'USD' ? '$' : '₹';
        });
    }

    var overlay = document.getElementById('sendInvoiceModal');
    var openBtn = document.getElementById('sendInvoiceBtn');
    var form = document.getElementById('sendInvoiceForm');
    if (!overlay) return;

    // Keep overlay on <body> so dashboard z-index/transform cannot trap it
    if (overlay.parentElement !== document.body) {
        document.body.appendChild(overlay);
    }

    function openModal() {
        overlay.classList.add('is-open');
        overlay.setAttribute('aria-hidden', 'false');
    }
    function closeModal() {
        overlay.classList.remove('is-open');
        overlay.setAttribute('aria-hidden', 'true');
    }

    openBtn && openBtn.addEventListener('click', function (e) {
        e.preventDefault();
        if (openBtn.disabled) return;
        openModal();
    });

    document.getElementById('cancelSendInvoice')?.addEventListener('click', closeModal);
    document.getElementById('cancelSendInvoiceX')?.addEventListener('click', closeModal);

    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closeModal();
    });

    document.getElementById('confirmSendInvoice')?.addEventListener('click', function () {
        closeModal();
        form && form.submit();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && overlay.classList.contains('is-open')) closeModal();
    });
})();
</script>

@endsection
