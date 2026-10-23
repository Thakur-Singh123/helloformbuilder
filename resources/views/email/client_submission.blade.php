<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #198754; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background-color: #f8f9fa; padding: 20px; border: 1px solid #dee2e6; }
        .submission-data { background-color: white; padding: 15px; margin-top: 15px; border-radius: 5px; border: 1px solid #dee2e6; }
        .field-item { margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e9ecef; }
        .field-item:last-child { border-bottom: none; }
        .field-label { font-weight: bold; color: #495057; }
        .field-value { margin-top: 5px; color: #212529; }
        .reply-box { background: #e7f5ff; padding: 15px; border-radius: 5px; margin-top: 15px; }
        .footer { text-align: center; padding: 20px; color: #6c757d; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header"><h2>New submission on your website</h2></div>
        <div class="content">
            <p>Hello {{ $client_name }},</p>
            <p>A new form submission was received for <strong>{{ $form_name }}</strong> (from your website).</p>
            <div class="submission-data">
                <h3 style="margin-top: 0;">Submission details</h3>
                @foreach($submission_data as $key => $field)
                    <div class="field-item">
                        <div class="field-label">{{ $field['field_name'] ?? $key }}:</div>
                        <div class="field-value">
                            @if(is_array($field['value'] ?? null)) {{ implode(', ', $field['value']) }} @else {{ $field['value'] ?? '(empty)' }} @endif
                        </div>
                    </div>
                @endforeach
                <div class="field-item" style="margin-top: 15px; padding-top: 15px; border-top: 2px solid #dee2e6;">
                    <div class="field-label">Submitted at</div>
                    <div class="field-value">{{ $submitted_at }}</div>
                </div>
            </div>
            @if(!empty($visitor_email))
            <div class="reply-box">
                <strong>Reply to visitor:</strong> You can reply to this submission by emailing <a href="mailto:{{ $visitor_email }}">{{ $visitor_email }}</a>.
            </div>
            @endif
        </div>
        <div class="footer">This is an automated notification. View all submissions in your dashboard.</div>
    </div>
</body>
</html>
