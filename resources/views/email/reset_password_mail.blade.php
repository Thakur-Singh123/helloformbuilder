<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 24px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background-color: #f8f9fa; padding: 24px; border: 1px solid #dee2e6; border-top: none; }
        .btn-reset { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white !important; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 16px 0; }
        .btn-reset:hover { opacity: 0.95; color: white; }
        .footer { text-align: center; padding: 20px; color: #6c757d; font-size: 12px; }
        .muted { color: #6c757d; font-size: 14px; margin-top: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0;">Reset your password</h2>
        </div>
        <div class="content">
            <p>Hello {{ $name ?? 'there' }},</p>
            <p>We received a request to reset the password for your account. Click the button below to choose a new password.</p>
            <p style="text-align: center;">
                <a href="{{ $resetUrl }}" class="btn-reset">Reset password</a>
            </p>
            <p class="muted">If you didn't request a password reset, you can safely ignore this email. Your password will stay the same.</p>
            <p class="muted">This link will expire after use. If you need a new link, use the "Forgot password" option on the login page again.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $sender_name ?? config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
