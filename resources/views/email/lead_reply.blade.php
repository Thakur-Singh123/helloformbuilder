<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f4f6;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;width:100%;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                    <tr>
                        <td style="background:#3b82f6;color:#ffffff;padding:20px 24px;text-align:center;">
                            @if(!empty($form_name))
                                <h2 style="margin:0;font-size:20px;font-weight:700;">Reply regarding {{ $form_name }}</h2>
                            @else
                                <h2 style="margin:0;font-size:20px;font-weight:700;">Message from our team</h2>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 16px 0;font-size:15px;line-height:1.5;">Hello {{ $visitor_name }},</p>
                            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:16px;font-size:15px;line-height:1.6;white-space:pre-wrap;">{{ $message_body }}</div>
                            <p style="margin:20px 0 0 0;font-size:15px;line-height:1.5;">
                                Regards,<br>
                                {{ $sender_name }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 24px;text-align:center;color:#6b7280;font-size:12px;border-top:1px solid #e5e7eb;">
                            You can reply directly to this email.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>