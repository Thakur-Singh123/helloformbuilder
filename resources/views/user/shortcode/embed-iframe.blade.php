@php
    $fieldsWithStep = $fields->map(function ($f) {
        $meta = is_string($f['metadata'] ?? '{}') ? (json_decode($f['metadata'] ?? '{}', true) ?: []) : (array) ($f['metadata'] ?? []);
        $f['step'] = (int) ($meta['step'] ?? 1);
        return $f;
    });
    $steps = $fieldsWithStep->pluck('step')->unique()->sort()->values();
    $byStep = $fieldsWithStep->groupBy('step');
    $isMultiStep = $steps->count() > 1;

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
        : ($formButton['color'] ?? ($shortcode->button_color ?? '#0d6efd'));
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->title ?? $form->heading ?? $form->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { margin: 0; padding: 1rem; background-color: #f8f9fa; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; overflow: hidden; }
        html { overflow: hidden; }
        .form-container { max-width: 100%; background: white; padding: 1.5rem; border-radius: 0.5rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { font-weight: 500; margin-bottom: 0.5rem; color: #495057; }
        .required-star { color: #dc3545; }
        .btn-submit { padding: 0.75rem 1.25rem; font-weight: 600; background-color: {{ $buttonColor }} !important; border-color: {{ $buttonColor }} !important; color: #fff !important; }
        .btn-submit:hover, .btn-submit:focus { filter: brightness(0.92); background-color: {{ $buttonColor }} !important; border-color: {{ $buttonColor }} !important; color: #fff !important; }
        .lm-btn-next { background-color: {{ $buttonColor }} !important; border-color: {{ $buttonColor }} !important; color: #fff !important; }
        .lm-step-panel { display: none; }
        .lm-step-panel.active { display: block; }
        .lm-step-pills { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
        .lm-step-pill { padding: 0.35rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; background: #e9ecef; color: #6c757d; }
        .lm-step-pill.active { background: {{ $buttonColor }}; color: #fff; }
        .lm-nav-buttons { display: flex; gap: 0.75rem; margin-top: 1.25rem; justify-content: space-between; flex-wrap: wrap; }
        .choice-group .form-check { margin-bottom: 0.35rem; }
    </style>
</head>
<body>
    <div class="form-container">
        {!! $form->renderDisplayTextHtml('heading', $form->heading ?? $form->name) !!}
        {!! $form->renderDisplayTextHtml('title', $form->title) !!}
        {!! $form->renderDisplayTextHtml('description', $form->description) !!}
        <div id="alertContainer"></div>

        <form id="shortcodeForm">
            @csrf
            @if($isMultiStep)
                <div class="lm-step-pills" id="stepPills">
                    @foreach($steps as $stepNum)
                        <span class="lm-step-pill {{ $loop->first ? 'active' : '' }}" data-step="{{ $stepNum }}">Step {{ $stepNum }}</span>
                    @endforeach
                </div>
                @foreach($steps as $stepNum)
                    <div class="lm-step-panel {{ $loop->first ? 'active' : '' }}" data-step="{{ $stepNum }}">
                        @foreach($byStep[$stepNum] ?? [] as $field)
                            @include('user.shortcode.partials.form-field', ['field' => $field])
                        @endforeach
                    </div>
                @endforeach
                <div class="lm-nav-buttons">
                    <button type="button" class="btn btn-secondary lm-btn-prev" style="display: none;">Previous</button>
                    <button type="button" class="btn btn-primary lm-btn-next">Next</button>
                    <button type="submit" class="btn btn-submit lm-btn-submit" style="display: none;">{{ $buttonText }}</button>
                </div>
            @else
                @foreach($fields as $field)
                    @include('user.shortcode.partials.form-field', ['field' => $field])
                @endforeach
                <button type="submit" class="btn btn-submit mt-3 w-100">{{ $buttonText }}</button>
            @endif
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        function lmNotifyParentHeight() {
            var height = Math.ceil(
                Math.max(
                    document.documentElement.scrollHeight || 0,
                    document.body ? document.body.scrollHeight : 0
                )
            );

            if (window.parent && window.parent !== window) {
                window.parent.postMessage({ type: 'leadmatrix-resize', height: height }, '*');
            }
        }

        function lmScheduleHeightUpdates() {
            lmNotifyParentHeight();
            setTimeout(lmNotifyParentHeight, 50);
            setTimeout(lmNotifyParentHeight, 250);
        }

        $(document).ready(function() {
            lmScheduleHeightUpdates();
            window.addEventListener('load', lmScheduleHeightUpdates);
            window.addEventListener('resize', lmNotifyParentHeight);

            if (window.ResizeObserver && document.body) {
                new ResizeObserver(lmNotifyParentHeight).observe(document.body);
            }

            @if($isMultiStep)
            var totalSteps = {{ $steps->count() }};
            var currentStep = 1;
            function updateStepUI() {
                $('.lm-step-panel').removeClass('active').hide();
                $('.lm-step-panel[data-step="' + currentStep + '"]').addClass('active').show();
                $('.lm-step-pill').removeClass('active');
                $('.lm-step-pill[data-step="' + currentStep + '"]').addClass('active');
                $('.lm-btn-prev').toggle(currentStep > 1);
                $('.lm-btn-next').toggle(currentStep < totalSteps);
                $('.lm-btn-submit').toggle(currentStep === totalSteps);
                lmScheduleHeightUpdates();
            }
            $('.lm-btn-prev').on('click', function() {
                if (currentStep > 1) { currentStep--; updateStepUI(); }
            });
            function panelStepIsValid($panel) {
                var valid = true;

                $panel.find('[data-choice-group]').each(function() {
                    var $group = $(this);
                    if (String($group.data('required')) !== '1') {
                        return;
                    }
                    if ($group.data('choice-type') === 'multiple') {
                        if ($group.find('input[type="checkbox"]:checked').length === 0) {
                            valid = false;
                        }
                    } else if ($group.find('input[type="radio"]:checked').length === 0) {
                        valid = false;
                    }
                });

                $panel.find('input:not([type=checkbox]):not([type=radio]), textarea, select').each(function() {
                    var $el = $(this);
                    if ($el.prop('required') && !String($el.val() || '').trim()) {
                        valid = false;
                    }
                });

                $panel.find('input[type=checkbox]:not([name$="[]"])').each(function() {
                    var $el = $(this);
                    if ($el.prop('required') && !$el.is(':checked')) {
                        valid = false;
                    }
                });

                return valid;
            }

            $('.lm-btn-next').on('click', function() {
                var $panel = $('.lm-step-panel[data-step="' + currentStep + '"]');
                if (panelStepIsValid($panel) && currentStep < totalSteps) {
                    currentStep++;
                    updateStepUI();
                } else if (!panelStepIsValid($panel)) {
                    $('#alertContainer').html('<div class="alert alert-warning">Please fill in all required fields.</div>');
                    lmScheduleHeightUpdates();
                }
            });
            updateStepUI();
            @endif

            $('#shortcodeForm').on('submit', function(e) {
                e.preventDefault();
                $('#alertContainer').html('');

                var formData = $(this).serialize();
                var submitBtn = $(this).find('button[type="submit"]');
                var originalText = submitBtn.text();

                submitBtn.prop('disabled', true).text('Submitting...');

                $.ajax({
                    url: '{{ route("shortcode.submit", $shortcode->code) }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            if (response.redirect_url) {
                                try {
                                    window.top.location.href = response.redirect_url;
                                } catch (e) {
                                    window.location.href = response.redirect_url;
                                }
                                return;
                            }

                            $('#alertContainer').html(
                                '<div class="alert alert-success">' + (response.message || 'Form submitted successfully!') + '</div>'
                            );
                            $('#shortcodeForm')[0].reset();
                            @if($isMultiStep)
                            currentStep = 1;
                            updateStepUI();
                            @endif
                            lmScheduleHeightUpdates();
                        } else {
                            $('#alertContainer').html(
                                '<div class="alert alert-danger">' + (response.message || 'Error submitting form') + '</div>'
                            );
                            lmScheduleHeightUpdates();
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = 'An error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        $('#alertContainer').html(
                            '<div class="alert alert-danger">' + errorMessage + '</div>'
                        );
                        lmScheduleHeightUpdates();
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).text(originalText);
                        lmScheduleHeightUpdates();
                    }
                });
            });
        });
    </script>
</body>
</html>

