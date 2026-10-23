<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
        .submission-data {
            background-color: white;
            padding: 15px;
            margin-top: 15px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .field-item {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        .field-item:last-child {
            border-bottom: none;
        }
        .field-label {
            font-weight: bold;
            color: #495057;
        }
        .field-value {
            margin-top: 5px;
            color: #212529;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Form Submission</h2>
        </div>
        
        <div class="content">
            <p>Hello {{ $user_name }},</p>
            
            <p>You have received a new submission for your form: <strong>{{ $form_name }}</strong></p>

            @if(!empty($visitor_email))
                <p style="background-color: #e7f1ff; padding: 12px 15px; border-radius: 5px; border: 1px solid #b6d4fe;">
                    <strong>Reply directly to this email</strong> to contact the person who submitted the form.
                    @if(!empty($visitor_name))
                        <br>Visitor: <strong>{{ $visitor_name }}</strong> ({{ $visitor_email }})
                    @else
                        <br>Visitor email: <strong>{{ $visitor_email }}</strong>
                    @endif
                </p>
            @endif
            
            <div class="submission-data">
                <h3 style="margin-top: 0;">Submission Details:</h3>
                
                @foreach($submission_data as $key => $field)
                    <div class="field-item">
                        <div class="field-label">{{ $field['field_name'] }}:</div>
                        <div class="field-value">
                            @if(is_array($field['value']))
                                {{ implode(', ', $field['value']) }}
                            @else
                                {{ $field['value'] ?: '(empty)' }}
                            @endif
                        </div>
                    </div>
                @endforeach
                
                <div class="field-item" style="margin-top: 15px; padding-top: 15px; border-top: 2px solid #dee2e6;">
                    <div class="field-label">Submitted At:</div>
                    <div class="field-value">{{ $submitted_at }}</div>
                </div>
                
                @if($ip_address)
                    <div class="field-item">
                        <div class="field-label">IP Address:</div>
                        <div class="field-value">{{ $ip_address }}</div>
                    </div>
                @endif
            </div>
            
            <p style="margin-top: 20px;">
                <strong>Shortcode:</strong> {{ $shortcode_code }}
            </p>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from your portal.</p>
            @if(!empty($visitor_email))
                <p>Replies are sent directly to the form submitter.</p>
            @endif
        </div>
    </div>
</body>
</html>

