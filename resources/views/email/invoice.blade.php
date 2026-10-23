<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #0d6efd; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background-color: #f8f9fa; padding: 20px; border: 1px solid #dee2e6; }
        .box { background: white; padding: 15px; border-radius: 5px; border: 1px solid #dee2e6; margin-top: 15px; }
        .row { margin-bottom: 10px; }
        .label { color: #6c757d; font-size: 12px; text-transform: uppercase; }
        .value { font-size: 16px; font-weight: 600; }
        .amount { font-size: 28px; color: #0d6efd; font-weight: 700; }
        .footer { text-align: center; color: #6c757d; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2 style="margin:0;">Invoice</h2>
        <div>{{ $invoice->invoice_number }}</div>
    </div>
    <div class="content">
        <p>Hi {{ $invoice->customer_name ?: 'there' }},</p>
        <p>Please find your invoice details below.</p>

        <div class="box">
            <div class="row">
                <div class="label">Amount Due</div>
                @php $sym = (($invoice->currency ?? 'INR') === 'USD') ? '$' : '₹'; @endphp
                <div class="amount">{{ $sym }}{{ number_format((float) $invoice->amount, 2) }}</div>
            </div>
            @if($invoice->description)
                <div class="row">
                    <div class="label">Description</div>
                    <div class="value" style="font-weight:400; white-space:pre-wrap;">{{ $invoice->description }}</div>
                </div>
            @endif
            <div class="row">
                <div class="label">Invoice Number</div>
                <div class="value">{{ $invoice->invoice_number }}</div>
            </div>
            <div class="row">
                <div class="label">Date</div>
                <div class="value">{{ now()->format('M d, Y') }}</div>
            </div>
        </div>

        <p style="margin-top:20px;">If you have any questions, just reply to this email.</p>
    </div>
    <div class="footer">Sent via LeadMatrix</div>
</div>
</body>
</html>
