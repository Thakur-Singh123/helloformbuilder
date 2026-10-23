@extends('user.include.layout')
@section('content')

<style>
    .container {
        width: 95%;
        max-width: 900px;
        margin: 1.5rem auto;
        padding: 0;
    }

    .card {
        background-color: white;
        padding: 2rem;
        border: 1px solid #e9ecef;
        border-radius: 0.75rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.08);
    }

    h5 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #333;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #495057;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #ced4da;
        border-radius: 0.5rem;
        box-sizing: border-box;
        font-size: 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .btn-primary {
        background-color: #0d6efd;
        border: 1px solid #0d6efd;
        color: white;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 0.5rem;
    }

    .alert-success {
        background-color: #d1e7dd;
        border: 1px solid #badbcc;
        color: #0f5132;
    }

    .alert-danger {
        background-color: #f8d7da;
        border: 1px solid #f5c2c7;
        color: #842029;
    }

    .code-display {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        margin-top: 1rem;
        font-family: monospace;
        word-break: break-all;
    }

    .btn-outline-primary {
        border: 1px solid #0d6efd;
        color: #0d6efd;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        background: transparent;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
    }

    .copy-btn {
        background-color: #6c757d;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        cursor: pointer;
        font-size: 0.875rem;
        transition: background-color 0.2s;
        margin-top: 0.5rem;
    }

    .copy-btn:hover {
        background-color: #5c636a;
    }
    .copy-btn.copied {
        background-color: #198754;
        color: white;
        font-size: 0.8rem;
    }
    .copy-btn.copied .copy-feedback-text {
        font-size: 0.8rem;
    }

    code {
        white-space: pre-wrap;
        word-wrap: break-word;
    }
</style>

<div class="container">
    <!-- Instructions Card -->
    <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; margin-bottom: 1.5rem;">
        <h5 style="color: white; margin-bottom: 1rem;">
            <i class="bi bi-info-circle me-2"></i>Quick Guide: Creating a Shortcode
        </h5>
        <ol style="margin-left: 1.5rem; line-height: 2;">
            <li>Select an active form from the dropdown below</li>
            <li>Click "Create Shortcode" to generate your embed code</li>
            <li>Copy the JavaScript or Iframe embed code</li>
            <li>Paste it on your website where you want the form to appear</li>
        </ol>
        <div style="margin-top: 1rem; padding: 1rem; background: rgba(255,255,255,0.1); border-radius: 0.5rem;">
            <strong>Which embed method should I use?</strong><br>
            <span style="font-size: 0.9rem;">
                <strong>JavaScript Embed:</strong> Best for HTML, React, Vue, Angular, Next.js, and custom websites<br>
                <strong>Iframe Embed:</strong> Best for WordPress, Shopify, Wix, Squarespace, Drupal, and other CMS platforms
            </span>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card">
        <h5 class="mb-4"><i class="bi bi-plus-circle me-2"></i>Create New Shortcode</h5>

        <div id="alertContainer"></div>

        <form id="shortcodeForm">
            <!-- Form Selection -->
            <div class="form-group">
                <label for="custom_form_id" class="form-label">
                    <span style="color: #dc3545;">*</span> Select Form
                </label>
                <select class="form-select" name="custom_form_id" id="custom_form_id" required>
                    <option value="">-- Select a Form --</option>
                    @foreach($forms as $form)
                        <option value="{{ $form->id }}">{{ $form->name }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Only active forms are shown. Make sure your form is set to "Active" status.</small>
            </div>

            @if(isset($clients) && $clients->isNotEmpty())
            <div class="form-group">
                <label for="client_id" class="form-label">Assign to Client (Website Owner)</label>
                <select class="form-select" name="client_id" id="client_id">
                    <option value="">-- No specific client --</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}">{{ $c->name ?? $c->email }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Submissions from this shortcode will be visible to the selected client and they will receive an email per submission.</small>
            </div>
            @endif

            <!-- Form preview (shown when a form is selected) -->
            <div id="formPreviewWrap" class="form-group" style="display: none; margin-top: 1.5rem;">
                <label class="form-label"><i class="bi bi-eye me-1"></i> Form preview</label>
                <div id="formPreview" class="border rounded p-3 bg-light" style="max-height: 320px; overflow-y: auto;">
                    <!-- Filled by JS from selected form fields -->
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">After Successful Submission</label>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="enable_redirect">
                    <label class="form-check-label" for="enable_redirect">
                        Redirect visitor to another URL instead of showing the success message
                    </label>
                </div>
                <div id="redirectUrlWrap" style="display: none;">
                    <input
                        type="url"
                        class="form-control"
                        id="redirect_url"
                        placeholder="https://example.com/thank-you"
                    >
                    <small class="text-muted">Enter the full URL including `http://` or `https://`.</small>
                </div>
            </div>

            <div class="form-group">
                <label for="button_text" class="form-label">Submit Button Text</label>
                <input
                    type="text"
                    class="form-control"
                    id="button_text"
                    value="Submit"
                    maxlength="50"
                    placeholder="Submit"
                >
                <small class="text-muted">This is the text shown on the form submit button on your website.</small>
            </div>

            <div class="form-group">
                <label for="button_color" class="form-label">Submit Button Color</label>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <input
                        type="color"
                        id="button_color"
                        value="#0d6efd"
                        style="width: 3rem; height: 2.5rem; padding: 0.15rem; border: 1px solid #ced4da; border-radius: 0.375rem; background: #fff; cursor: pointer;"
                    >
                    <input
                        type="text"
                        class="form-control"
                        id="button_color_text"
                        value="#0d6efd"
                        maxlength="7"
                        placeholder="#0d6efd"
                        style="max-width: 140px;"
                    >
                </div>
                <small class="text-muted">Choose the color for the form submit button on your website.</small>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Create Shortcode
                </button>
                <a href="{{ route('build_form') }}" class="btn-outline-primary" style="margin-left: 0.5rem; text-decoration: none;">
                    <i class="bi bi-arrow-left me-1"></i> Back to Build Form
                </a>
                <a href="{{ route('shortcodes.index') }}" class="btn-outline-primary" style="margin-left: 0.5rem; text-decoration: none;">
                    <i class="bi bi-code-square me-1"></i> Shortcodes list
                </a>
            </div>
        </form>

        <!-- Generated Shortcode Display -->
        <div id="shortcodeResult" style="display: none; margin-top: 2rem;">
            <div class="code-display" style="background: #d1e7dd; border-color: #badbcc;">
                <strong style="color: #0f5132;"><i class="bi bi-check-circle me-1"></i>Shortcode Created Successfully!</strong><br>
                <small class="text-muted">Your shortcode code:</small><br>
                <code id="shortcodeCode" style="display: block; margin-top: 0.5rem; font-size: 1rem; font-weight: 600;"></code>
                <button class="copy-btn" onclick="copyToClipboard('shortcodeCode', this)" style="margin-top: 0.5rem;">
                    <i class="bi bi-clipboard me-1"></i> Copy Code
                </button>
            </div>
            
            <div class="code-display" style="margin-top: 1rem;">
                <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                    <strong><i class="bi bi-filetype-js me-1"></i> JavaScript Embed</strong>
                    <span class="badge bg-success ms-2">Recommended</span>
                </div>
                <small class="text-muted d-block mb-2">Use this for: HTML websites, React, Vue, Angular, Next.js, and custom-built sites</small>
                <code id="jsEmbedCode" style="display: block; margin-top: 0.5rem; padding: 1rem; background: #ffffff; border: 1px solid #dee2e6; border-radius: 0.375rem; font-family: 'Courier New', monospace; word-break: break-all;"></code>
                <button class="copy-btn" onclick="copyToClipboard('jsEmbedCode', this)" style="margin-top: 0.5rem;">
                    <i class="bi bi-clipboard me-1"></i> Copy JavaScript Code
                </button>
            </div>
            
            <div class="code-display" style="margin-top: 1rem;">
                <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                    <strong><i class="bi bi-window me-1"></i> Iframe Embed</strong>
                    <span class="badge bg-info ms-2">Universal</span>
                </div>
                <small class="text-muted d-block mb-2">Use this for: WordPress, Shopify, Wix, Squarespace, Drupal, Joomla, and other CMS platforms</small>
                <code id="iframeEmbedCode" style="display: block; margin-top: 0.5rem; padding: 1rem; background: #ffffff; border: 1px solid #dee2e6; border-radius: 0.375rem; font-family: 'Courier New', monospace; word-break: break-all;"></code>
                <button class="copy-btn" onclick="copyToClipboard('iframeEmbedCode', this)" style="margin-top: 0.5rem;">
                    <i class="bi bi-clipboard me-1"></i> Copy Iframe Code
                </button>
            </div>
        </div>
    </div>
</div>

<script>
window.formsForPreview = {!! json_encode($formsForPreview) !!};
window.selectedFormId = {{ isset($selectedFormId) && $selectedFormId ? (int) $selectedFormId : 'null' }};
</script>

@endsection

@section('footer')
<script>
(function() {
$(document).ready(function() {
    function buildIframeEmbedCode(baseUrl, code) {
        var iframeId = 'lm-iframe-' + code;
        var src = baseUrl + '/api/shortcode/' + code + '/html';
        var endScript = '<' + '/script>';
        var listener = '(function(){var id="' + iframeId + '";window.addEventListener("message",function(e){if(!e.data||e.data.type!=="leadmatrix-resize")return;var f=document.getElementById(id);if(f&&e.data.height){f.style.height=Math.max(120,e.data.height)+"px";}});})();';

        return '<iframe id="' + iframeId + '" src="' + src + '" width="100%" style="border:0;width:100%;display:block;overflow:hidden;" scrolling="no" frameborder="0"></iframe>\n<script>' + listener + endScript;
    }

    function escapeEmbedHtml(html) {
        return html
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function toggleRedirectUrl() {
        var enabled = $('#enable_redirect').is(':checked');
        $('#redirectUrlWrap').toggle(enabled);
        $('#redirect_url').prop('required', enabled);
    }

    function isValidHexColor(value) {
        return /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(String(value || '').trim());
    }

    function syncButtonColorInputs(fromPicker) {
        if (fromPicker) {
            $('#button_color_text').val($('#button_color').val());
            return;
        }
        var textVal = ($('#button_color_text').val() || '').trim();
        if (isValidHexColor(textVal)) {
            $('#button_color').val(textVal);
        }
    }

    $('#button_color').on('input change', function() {
        syncButtonColorInputs(true);
    });
    $('#button_color_text').on('input change', function() {
        syncButtonColorInputs(false);
    });

    function renderFormPreview(formId) {
        if (!formId || !window.formsForPreview) {
            $('#formPreviewWrap').hide();
            return;
        }
        var form = window.formsForPreview.find(function(f) { return f.id == formId; });
        if (!form || !form.fields || !form.fields.length) {
            $('#formPreviewWrap').hide();
            return;
        }
        var html = '<p class="text-muted small mb-3"><strong>' + (form.name || 'Form') + '</strong> — ' + form.fields.length + ' field(s)</p>';
        form.fields.forEach(function(field) {
            var label = (field.is_required ? '<span class="text-danger">*</span> ' : '') + (field.field_name || field.field_key);
            var input = field.field_type === 'Text Area'
                ? '<textarea class="form-control form-control-sm" rows="2" readonly placeholder="' + (field.field_name || '') + '"></textarea>'
                : '<input type="text" class="form-control form-control-sm" readonly placeholder="' + (field.field_name || '') + '">';
            html += '<div class="mb-2"><label class="form-label small mb-1">' + label + '</label>' + input + '</div>';
        });
        $('#formPreview').html(html);
        $('#formPreviewWrap').show();
    }

    $('#custom_form_id').on('change', function() {
        renderFormPreview($(this).val());
    });
    $('#enable_redirect').on('change', toggleRedirectUrl);

    if (window.selectedFormId) {
        $('#custom_form_id').val(window.selectedFormId);
        renderFormPreview(window.selectedFormId);
    } else if ($('#custom_form_id').val()) {
        renderFormPreview($('#custom_form_id').val());
    }
    toggleRedirectUrl();

    $('#shortcodeForm').on('submit', function(e) {
        e.preventDefault();

        // Clear previous alerts
        $('#alertContainer').html('');

        // Get form data
        const formData = {
            custom_form_id: $('#custom_form_id').val(),
            client_id: $('#client_id').length ? $('#client_id').val() || null : null,
            _token: '{{ csrf_token() }}'
        };
        const redirectEnabled = $('#enable_redirect').is(':checked');
        const redirectUrl = ($('#redirect_url').val() || '').trim();
        const buttonColor = ($('#button_color').val() || '#0d6efd').trim();
        const buttonText = ($('#button_text').val() || 'Submit').trim() || 'Submit';

        // Validate
        if (!formData.custom_form_id) {
            showAlert('Please select a form.', 'danger');
            return;
        }

        if (redirectEnabled && !redirectUrl) {
            showAlert('Please enter a redirect URL.', 'danger');
            return;
        }

        const settings = {
            success_action: redirectEnabled ? 'redirect' : 'message',
            button_color: buttonColor,
            button_text: buttonText
        };
        if (redirectEnabled) {
            settings.redirect_url = redirectUrl;
        }
        formData.settings = JSON.stringify(settings);

        // Submit via AJAX
        $.ajax({
            type: 'POST',
            url: '{{ route("shortcodes.store") }}',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Display the generated shortcode
                    $('#shortcodeCode').text(response.shortcode.code);
                    
                    // Display JavaScript embed code - store both escaped (for display) and unescaped (for copy)
                    const baseUrl = '{{ url("/") }}';
                    const jsCodeEscaped = '&lt;script src="' + baseUrl + '/js/leadmatrix-embed.js"&gt;&lt;/script&gt;\n&lt;div data-leadmatrix-code="' + response.shortcode.code + '"&gt;&lt;/div&gt;';
                    var endScript = '<' + '/script>';
                    const jsCodeActual = '<script src="' + baseUrl + '/js/leadmatrix-embed.js">' + endScript + '\n<div data-leadmatrix-code="' + response.shortcode.code + '"></div>';
                    $('#jsEmbedCode').html(jsCodeEscaped);
                    $('#jsEmbedCode').data('actual-code', jsCodeActual);
                    
                    // Display iframe embed code - store both escaped (for display) and unescaped (for copy)
                    const iframeCodeActual = buildIframeEmbedCode(baseUrl, response.shortcode.code);
                    const iframeCodeEscaped = escapeEmbedHtml(iframeCodeActual);
                    $('#iframeEmbedCode').html(iframeCodeEscaped);
                    $('#iframeEmbedCode').data('actual-code', iframeCodeActual);
                    
                    $('#shortcodeResult').show();
                    showAlert('Shortcode created successfully! Copy the embed code below to use on any website.', 'success');
                    
                    // Scroll to the result
                    $('html, body').animate({
                        scrollTop: $('#shortcodeResult').offset().top - 100
                    }, 500);
                    
                    // Reset form
                    $('#shortcodeForm')[0].reset();
                    $('#button_color').val('#0d6efd');
                    $('#button_color_text').val('#0d6efd');
                    $('#button_text').val('Submit');
                    toggleRedirectUrl();
                } else {
                    showAlert(response.message || 'Failed to create shortcode.', 'danger');
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Something went wrong. Please try again.';
                showAlert(errorMsg, 'danger');
            }
        });
    });

    function showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type}">
                <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
            </div>
        `;
        $('#alertContainer').html(alertHtml);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            $('#alertContainer').fadeOut(300, function() {
                $(this).html('').show();
            });
        }, 5000);
    }

    function copyToClipboard(elementId, btn) {
        const element = document.getElementById(elementId);
        if (!element) return;
        var text = '';

        if ($(element).data('actual-code')) {
            text = $(element).data('actual-code');
        } else if (elementId === 'shortcodeCode') {
            text = element.textContent || element.innerText || '';
        } else {
            var htmlContent = element.innerHTML;
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = htmlContent;
            text = tempDiv.textContent || tempDiv.innerText || '';
        }

        if (!text) {
            showAlert('Nothing to copy.', 'danger');
            return;
        }

        function showCopiedFeedback(button) {
            if (!button) return;
            var originalHtml = button.innerHTML;
            button.disabled = true;
            button.classList.add('copied');
            button.innerHTML = '<span class="copy-feedback-text">Text copied.</span>';
            setTimeout(function() {
                button.innerHTML = originalHtml;
                button.classList.remove('copied');
                button.disabled = false;
            }, 2000);
        }

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                showCopiedFeedback(btn);
            }).catch(function() {
                fallbackCopy(text, btn);
            });
        } else {
            fallbackCopy(text, btn);
        }

        function fallbackCopy(str, button) {
            var textarea = document.createElement('textarea');
            textarea.value = str;
            textarea.style.position = 'fixed';
            textarea.style.left = '-9999px';
            textarea.style.top = '0';
            document.body.appendChild(textarea);
            textarea.focus();
            textarea.select();
            try {
                document.execCommand('copy');
                showCopiedFeedback(button);
            } catch (e) {
                showAlert('Copy failed. Please select and copy manually.', 'danger');
            }
            document.body.removeChild(textarea);
        }
    }

    // Expose to global scope so onclick="copyToClipboard(...)" works
    window.copyToClipboard = copyToClipboard;
});
})();
</script>
@endsection

