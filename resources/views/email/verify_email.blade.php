<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 24px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background-color: #f8f9fa; padding: 24px; border: 1px solid #dee2e6; border-top: none; }
        .btn-verify { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white !important; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 16px 0; }
        .btn-verify:hover { opacity: 0.95; color: white; }
        .footer { text-align: center; padding: 20px; color: #6c757d; font-size: 12px; }
        .muted { color: #6c757d; font-size: 14px; margin-top: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0;">Verify your email address</h2>
        </div>
        <div class="content">
            <p>Hello {{ $name }},</p>
            <p>Thanks for signing up. Please click the button below to verify your email and activate your account. After that, you can log in anytime.</p>
            <p style="text-align: center;">
                <a href="{{ $verifyUrl }}" class="btn-verify">Verify my email</a>
            </p>
            <p class="muted">If you didn't create an account, you can ignore this email.</p>
            <p class="muted">This link will expire in 24 hours. If it has expired, request a new verification email from the login page.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $siteName ?? config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
