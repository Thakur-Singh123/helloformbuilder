<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select account – {{ $settings->seo_title ?? config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #1e7e72;
            --bg-light: #f8f9fa;
            --font-family: 'Inter', sans-serif;
        }
        body {
            font-family: var(--font-family);
            background-color: var(--bg-light);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-card {
            max-width: 480px;
            width: 100%;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            background: white;
        }
        .btn-account {
            width: 100%;
            text-align: left;
            padding: 1rem 1.25rem;
            margin-bottom: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: white;
            transition: background-color 0.2s, border-color 0.2s;
        }
        .btn-account:hover {
            background-color: #e7f1ff;
            border-color: var(--secondary-color);
        }
        .btn-account strong { display: block; color: #333; }
        .btn-account small { color: #6c757d; }
    </style>
</head>
<body>
    <div class="form-card">
        <h1 class="text-center mb-2 text-secondary h4">Which account do you want to open?</h1>
        <p class="text-center text-muted small mb-4">You have client access from more than one organization. Choose one to continue.</p>

        @foreach($clients as $client)
            @php
                $adminName = $client->admin
                    ? (trim(($client->admin->first_name ?? '') . ' ' . ($client->admin->last_name ?? '')) ?: $client->admin->name ?? $client->admin->email ?? 'Account')
                    : 'Account';
            @endphp
            <form action="{{ route('login.select_client') }}" method="POST" class="d-inline-block w-100">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <button type="submit" class="btn-account">
                    <strong>{{ $adminName }}</strong>
                    <small>View shortcodes and submissions for this organization</small>
                </button>
            </form>
        @endforeach

        <hr class="my-4">
        <p class="text-center mb-0">
            <a href="{{ route('login') }}" class="text-muted small">← Back to login</a>
        </p>
    </div>
</body>
</html>
