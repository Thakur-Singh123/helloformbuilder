<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->title ?? $form->heading ?? $form->name }} - Form Submission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @php
        $shortcodeSettings = $shortcode->settings;
        if (is_string($shortcodeSettings)) {
            $shortcodeSettings = json_decode($shortcodeSettings, true) ?: [];
        }
        if (!is_array($shortcodeSettings)) {
            $shortcodeSettings = [];
        }
        $formButton = method_exists($form, 'getSubmitButtonSettings')
            ? $form->getSubmitButtonSettings()
            : ['text' => 'Submit', 'color' => '#0d6efd'];
        $buttonText = !empty($shortcodeSettings['button_text'])
            ? $shortcodeSettings['button_text']
            : ($formButton['text'] ?? 'Submit');
        $buttonColor = !empty($shortcodeSettings['button_color'])
            ? $shortcodeSettings['button_color']
            : ($formButton['color'] ?? '#0d6efd');
    @endphp
    <style>
        body {
            background-color: #f8f9fa;
            padding: 2rem 0;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.08);
        }
        h2 {
            margin-bottom: 0;
            color: inherit;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #495057;
        }
        .required-star {
            color: #dc3545;
        }
        .btn-submit {
            width: 100%;
            padding: 0.75rem;
            font-weight: 600;
            background-color: {{ $buttonColor }} !important;
            border-color: {{ $buttonColor }} !important;
            color: #fff !important;
        }
        .btn-submit:hover,
        .btn-submit:focus {
            filter: brightness(0.92);
            background-color: {{ $buttonColor }} !important;
            border-color: {{ $buttonColor }} !important;
            color: #fff !important;
        }
        .alert {
            margin-bottom: 1rem;
        }
        .choice-group .form-check {
            margin-bottom: 0.35rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            {!! $form->renderDisplayTextHtml('heading', $form->heading ?? $form->name) !!}
            {!! $form->renderDisplayTextHtml('title', $form->title) !!}
            {!! $form->renderDisplayTextHtml('description', $form->description) !!}

            <div id="alertContainer"></div>

            <form id="shortcodeForm">
                @csrf

                @foreach($fields as $field)
                    @include('user.shortcode.partials.form-field', ['field' => $field])
                @endforeach

                <button type="submit" class="btn btn-primary btn-submit">
                    {{ $buttonText }}
                </button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#shortcodeForm').on('submit', function(e) {
            e.preventDefault();

            $('#alertContainer').html('');

            const formData = $(this).serialize();
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.text();

            submitBtn.prop('disabled', true).text('Submitting...');

            $.ajax({
                type: 'POST',
                url: '{{ route("shortcode.submit", $shortcode->code) }}',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        if (response.redirect_url) {
                            window.location.href = response.redirect_url;
                            return;
                        }

                        $('#alertContainer').html(
                            '<div class="alert alert-success">' + response.message + '</div>'
                        );
                        $('#shortcodeForm')[0].reset();
                    } else {
                        $('#alertContainer').html(
                            '<div class="alert alert-danger">' + response.message + '</div>'
                        );
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Something went wrong. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    $('#alertContainer').html(
                        '<div class="alert alert-danger">' + errorMsg + '</div>'
                    );
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text(originalText);
                }
            });
        });
    });
    </script>
</body>
</html>
