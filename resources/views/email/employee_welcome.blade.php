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
            background: linear-gradient(135deg, #1e7e72 0%, #007bff 100%);
            color: white;
            padding: 24px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            background-color: #f8f9fa;
            padding: 24px;
            border: 1px solid #dee2e6;
            border-top: none;
        }

        .credentials {
            background-color: white;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            margin: 16px 0;
        }

        .credentials dt {
            font-weight: bold;
            color: #495057;
            margin-top: 10px;
        }

        .credentials dd {
            margin-left: 0;
            font-family: monospace;
        }

        .btn-login {
            display: inline-block;
            background: linear-gradient(135deg, #1e7e72 0%, #007bff 100%);
            color: white !important;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 16px 0;
        }

        .btn-login:hover {
            opacity: 0.95;
            color: white;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-size: 12px;
        }

        .muted {
            color: #6c757d;
            font-size: 14px;
            margin-top: 12px;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- Header -->
        <div class="header">

            <h2 style="margin:0;">
                Your employee account is ready
            </h2>

        </div>


        <!-- Content -->
        <div class="content">

            <p>
                Hello {{ $employee_name }},
            </p>

            <p>
                Your employee account has been created successfully.
                You can now sign in to access your employee dashboard,
                assigned tasks, and other team activities.
            </p>


            <!-- Login Button -->
            <p style="text-align: center;">

                <a href="{{ $login_url }}" class="btn-login">
                    Log in to your account
                </a>

            </p>


            <!-- Credentials -->
            <p>
                <strong>Your sign-in details:</strong>
            </p>

            <div class="credentials">

                <dl>

                    <dt>Email</dt>

                    <dd>
                        {{ $employee_email }}
                    </dd>


                    <dt>Password</dt>

                    <dd>
                        {{ $password }}
                    </dd>

                </dl>

            </div>


            <p class="muted">
                Please keep your login details safe.
                You can change your password after logging in
                through your account settings or by using
                Forgot Password on the login page.
            </p>

        </div>


        <!-- Footer -->
        <div class="footer">

            {{ $site_name ?? config('app.name') }}.
            This is an automated message.

        </div>

    </div>

</body>
</html>