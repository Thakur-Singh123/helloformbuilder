@extends('user.include.layout')
@section('content')

<style>
    .container {
        width: 95%;
        max-width: 1280px;
        margin: 1.5rem auto;
        padding: 0;
    }

    .card {
        background-color: white;
        padding: 2rem;
        border: 1px solid #e9ecef;
        border-radius: 0.75rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }

    h5 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #333;
    }

    .btn-primary {
        background-color: #0d6efd;
        border: 1px solid #0d6efd;
        color: white;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border-radius: 0.5rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.2s;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
    }

    .btn-outline-primary {
        border: 1px solid #0d6efd;
        color: #0d6efd;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        background: transparent;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
    }

    .btn-outline-secondary {
        border: 1px solid #ced4da;
        color: #495057;
        padding: 0.5rem 0.9rem;
        border-radius: 0.375rem;
        background: #fff;
        transition: all 0.2s;
    }

    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        color: #212529;
    }

    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
        min-width: 980px;
    }

    th, td {
        padding: 1rem 0.75rem;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
        vertical-align: top;
    }

    th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }

    .code-cell {
        font-family: monospace;
        background-color: #f8f9fa;
        padding: 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }

    .copy-btn {
        background-color: #6c757d;
        color: white;
        border: none;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        cursor: pointer;
        font-size: 0.875rem;
        transition: background-color 0.2s;
    }

    .copy-btn:hover {
        background-color: #5c636a;
    }

    .embed-btn,
    .action-btn {
        border: 1px solid #ced4da;
        background: #fff;
        color: #495057;
        padding: 0.45rem 0.8rem;
        border-radius: 0.375rem;
        cursor: pointer;
        font-size: 0.875rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .embed-btn {
        border-color: #0d6efd;
        color: #0d6efd;
    }

    .embed-btn:hover,
    .action-btn:hover {
        background-color: #f8f9fa;
        color: #212529;
    }

    .summary-title {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.2rem;
    }

    .summary-muted {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .summary-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 0.65rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 0.35rem;
    }

    .badge-success-light {
        background: #d1e7dd;
        color: #0f5132;
    }

    .badge-primary-light {
        background: #e7f1ff;
        color: #0d6efd;
    }

    .badge-neutral {
        background: #f1f3f5;
        color: #495057;
    }

    .summary-subtext {
        font-size: 0.8rem;
        color: #6c757d;
        max-width: 220px;
        word-break: break-word;
    }

    .action-group {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        flex-wrap: wrap;
    }

    .icon-action {
        width: 34px;
        height: 34px;
        padding: 0;
        justify-content: center;
    }

    .icon-action.edit-form-link {
        color: #0d6efd;
        border-color: #0d6efd;
        text-decoration: none;
    }

    .icon-action.edit-form-link:hover {
        background-color: #e7f1ff;
        color: #0b5ed7;
        border-color: #0b5ed7;
    }

    .form-name-link {
        color: #212529;
        text-decoration: none;
        border-bottom: 1px dashed transparent;
        transition: color 0.2s, border-color 0.2s;
    }

    .form-name-link:hover {
        color: #0d6efd;
        border-bottom-color: #0d6efd;
    }

    .instructions-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 0.75rem;
        padding: 2rem;
        margin-bottom: 1.5rem;
    }

    .instructions-card h5 {
        color: white;
        margin-bottom: 1rem;
    }

    .instructions-card ol {
        margin-left: 1.5rem;
        line-height: 2;
    }

    .instructions-card li {
        margin-bottom: 0.5rem;
    }

    .platform-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 0.375rem;
        font-size: 0.875rem;
        margin: 0.25rem;
    }

    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #0d6efd;
        padding: 1rem;
        border-radius: 0.375rem;
        margin: 1rem 0;
    }

    .info-box strong {
        color: #0d6efd;
    }

    .btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
    }
    .btn-outline-danger:hover {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .overlay-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease;
        z-index: 1040;
    }

    .overlay-backdrop.is-open {
        opacity: 1;
        visibility: visible;
    }

    .overlay-panel {
        position: fixed;
        z-index: 1050;
        background: #fff;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.2);
        opacity: 0;
        visibility: hidden;
        transition: all 0.22s ease;
    }

    .overlay-panel.is-open {
        opacity: 1;
        visibility: visible;
    }

    .embed-modal {
        top: 50%;
        left: 50%;
        width: min(760px, calc(100vw - 32px));
        border-radius: 16px;
        transform: translate(-50%, -48%);
    }

    .embed-modal.is-open {
        transform: translate(-50%, -50%);
    }

    .drawer-panel {
        top: 0;
        right: 0;
        width: min(460px, 100vw);
        height: 100vh;
        transform: translateX(100%);
        display: flex;
        flex-direction: column;
    }

    .drawer-panel.is-open {
        transform: translateX(0);
    }

    .panel-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
    }

    .panel-title {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: #212529;
    }

    .panel-subtitle {
        color: #6c757d;
        font-size: 0.9rem;
        margin-top: 0.15rem;
    }

    .panel-body {
        padding: 1.5rem;
        overflow-y: auto;
        scrollbar-gutter: stable;
        flex: 1 1 auto;
        min-height: 0;
    }

    .drawer-panel {
        overflow: hidden;
    }

    .embed-modal .panel-body {
        max-height: calc(100vh - 140px);
    }

    #shortcodeDrawerForm {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-height: 0;
        overflow: hidden;
    }

    .drawer-panel .panel-body {
        overflow-y: scroll;
        overflow-x: hidden;
        max-height: calc(100vh - 170px);
        scrollbar-width: thin;
        scrollbar-color: #9aa6b2 #f1f3f5;
    }

    .panel-body::-webkit-scrollbar,
    .table-wrap::-webkit-scrollbar,
    .embed-code-box::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }

    .panel-body::-webkit-scrollbar-track,
    .table-wrap::-webkit-scrollbar-track,
    .embed-code-box::-webkit-scrollbar-track {
        background: #f1f3f5;
        border-radius: 999px;
    }

    .panel-body::-webkit-scrollbar-thumb,
    .table-wrap::-webkit-scrollbar-thumb,
    .embed-code-box::-webkit-scrollbar-thumb {
        background: #c1c9d2;
        border-radius: 999px;
        border: 2px solid #f1f3f5;
    }

    .panel-body::-webkit-scrollbar-thumb:hover,
    .table-wrap::-webkit-scrollbar-thumb:hover,
    .embed-code-box::-webkit-scrollbar-thumb:hover {
        background: #9aa6b2;
    }

    .panel-footer {
        padding: 1rem 1.5rem 1.5rem;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        flex-shrink: 0;
        background: #fff;
        position: sticky;
        bottom: 0;
        z-index: 2;
    }

    .panel-close {
        border: none;
        background: transparent;
        color: #6c757d;
        font-size: 1.5rem;
        line-height: 1;
        cursor: pointer;
    }

    .embed-section {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 1rem;
        background: #f8f9fa;
        margin-bottom: 1rem;
    }

    .embed-code-box {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 1rem;
        font-family: Consolas, "Courier New", monospace;
        font-size: 0.85rem;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.45rem;
    }

    .form-help {
        display: block;
        color: #6c757d;
        font-size: 0.82rem;
        margin-top: 0.35rem;
    }

    .drawer-alert {
        margin-bottom: 1rem;
    }

    .empty-value {
        color: #adb5bd;
        font-style: italic;
    }

    @media (max-width: 768px) {
        .card {
            padding: 1rem;
        }

        .panel-footer {
            flex-direction: column-reverse;
        }

        .panel-footer .btn-primary,
        .panel-footer .btn-outline-secondary {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="container">
    <!-- Instructions Card -->
    <div class="instructions-card">
        <h5><i class="bi bi-info-circle me-2"></i>How to Use Shortcodes</h5>
        <ol>
            <li><strong>Create a Shortcode:</strong> Click "Create New Shortcode" and select a form from the dropdown.</li>
            <li><strong>Choose Embed Method:</strong> 
                <span class="platform-badge">JavaScript Embed</span> - Best for HTML, React, Vue, Angular, and custom websites
                <span class="platform-badge">Iframe Embed</span> - Best for WordPress, Shopify, Wix, and CMS platforms
            </li>
            <li><strong>Copy the Code:</strong> Click "Show Embed Code" below, then copy the code snippet.</li>
            <li><strong>Paste on Your Website:</strong> Paste the code where you want the form to appear.</li>
            <li><strong>View Submissions:</strong> All form submissions will appear in your Submissions dashboard.</li>
        </ol>
    </div>

    <!-- Main Card -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h5 class="mb-0"><i class="bi bi-code-square me-2"></i>My Shortcodes</h5>
            @if(!currentUser()->isClient())
            <a href="{{ route('shortcodes.create') }}" class="btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Create New Shortcode
            </a>
            @endif
        </div>

        @if($shortcodes->count() > 0)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Form</th>
                            <th>Client</th>
                            <th>Website</th>
                            <th>After Submit</th>
                            <th>Embed</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shortcodes as $shortcode)
                            <tr data-shortcode-id="{{ $shortcode->id }}">
                                <td>
                                    <div class="summary-title">{{ $shortcode->code }}</div>
                                    <div class="summary-muted">Shortcode</div>
                                </td>
                                <td>
                                    @if(!currentUser()->isClient() && $shortcode->customForm)
                                        <a href="{{ route('edit_form', $shortcode->customForm->id) }}"
                                           class="form-name-link"
                                           title="Edit form fields and display">
                                            <div class="summary-title">{{ $shortcode->customForm->name }}</div>
                                        </a>
                                    @else
                                        <div class="summary-title">{{ $shortcode->customForm->name ?? 'N/A' }}</div>
                                    @endif
                                    <div class="summary-muted">Version {{ $shortcode->formVersion->version_number ?? 'N/A' }}</div>
                                </td>
                                <td class="shortcode-client-summary" data-id="{{ $shortcode->id }}">
                                    @if($shortcode->client)
                                        <div class="summary-title">{{ $shortcode->client->name ?: $shortcode->client->email }}</div>
                                        <div class="summary-muted">Assigned client</div>
                                    @else
                                        <span class="empty-value">No client assigned</span>
                                    @endif
                                </td>
                                <td class="shortcode-website-summary" data-id="{{ $shortcode->id }}">
                                    @if($shortcode->website)
                                        <div class="summary-title">{{ $shortcode->website }}</div>
                                        <div class="summary-muted">Target website</div>
                                    @else
                                        <span class="empty-value">No website</span>
                                    @endif
                                </td>
                                <td class="shortcode-success-summary" data-id="{{ $shortcode->id }}">
                                    @if($shortcode->success_action === 'redirect' && $shortcode->redirect_url)
                                        <span class="summary-badge badge-primary-light">Redirect</span>
                                        <div class="summary-subtext">{{ $shortcode->redirect_url }}</div>
                                    @else
                                        <span class="summary-badge badge-neutral">Message</span>
                                        <div class="summary-subtext">Show success message</div>
                                    @endif
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        class="embed-btn shortcode-embed-btn"
                                        data-code="{{ $shortcode->code }}"
                                        data-form-name="{{ $shortcode->customForm->name ?? 'Shortcode Form' }}"
                                    >
                                        <i class="bi bi-code-slash"></i> Embed
                                    </button>
                                </td>
                                <td>
                                    <div class="action-group">
                                        @if(!currentUser()->isClient())
                                            @if($shortcode->customForm)
                                            <a href="{{ route('edit_form', $shortcode->customForm->id) }}"
                                               class="action-btn icon-action edit-form-link"
                                               title="Edit form fields and display">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @endif
                                            <button
                                                type="button"
                                                class="action-btn shortcode-edit-btn"
                                                data-id="{{ $shortcode->id }}"
                                                data-code="{{ $shortcode->code }}"
                                                data-form-name="{{ $shortcode->customForm->name ?? 'Shortcode Form' }}"
                                                data-client-id="{{ $shortcode->client_id ?? '' }}"
                                                data-website="{{ $shortcode->website ?? '' }}"
                                                data-success-action="{{ $shortcode->success_action }}"
                                                data-redirect-url="{{ $shortcode->redirect_url ?? '' }}"
                                                data-button-color="{{ $shortcode->button_color }}"
                                                title="Edit shortcode settings"
                                            >
                                                <i class="bi bi-sliders"></i> Settings
                                            </button>
                                        @endif
                                        <a href="{{ route('shortcode.render', $shortcode->code) }}" target="_blank" class="action-btn icon-action" title="View form">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(!currentUser()->isClient())
                                            <button type="button" class="action-btn icon-action btn-outline-danger shortcode-delete-btn" data-id="{{ $shortcode->id }}" data-code="{{ $shortcode->code }}" title="Delete shortcode">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 3rem; color: #6c757d;">
                <i class="bi bi-code-square" style="font-size: 4rem; color: #dee2e6; margin-bottom: 1rem;"></i>
                @if(currentUser()->isClient())
                    <h5 style="color: #6c757d; margin-bottom: 0.5rem;">No shortcodes assigned to you yet</h5>
                    <p style="margin-bottom: 1.5rem;">Your admin will assign shortcodes to your account. You can then copy the embed code and view submissions here.</p>
                @else
                    <h5 style="color: #6c757d; margin-bottom: 0.5rem;">No shortcodes created yet</h5>
                    <p style="margin-bottom: 1.5rem;">Create your first shortcode to start embedding forms on your website.</p>
                    <a href="{{ route('shortcodes.create') }}" class="btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Create Your First Shortcode
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<div class="overlay-backdrop" id="overlayBackdrop"></div>

<div class="overlay-panel embed-modal" id="embedModal" aria-hidden="true">
    <div class="panel-header">
        <div>
            <h6 class="panel-title" id="embedModalTitle">Embed Code</h6>
            <div class="panel-subtitle">Copy the snippet that matches your website setup.</div>
        </div>
        <button type="button" class="panel-close" data-close-panel>&times;</button>
    </div>
    <div class="panel-body">
        <div class="embed-section">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong><i class="bi bi-filetype-js me-1"></i> JavaScript Embed</strong>
                <button type="button" class="copy-btn" id="copyJsEmbedBtn">Copy</button>
            </div>
            <div class="summary-muted mb-2">Best for HTML, React, Vue, Angular, Next.js, and custom websites.</div>
            <pre class="embed-code-box mb-0"><code id="embedJsCode"></code></pre>
        </div>

        <div class="embed-section mb-0">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong><i class="bi bi-window me-1"></i> Iframe Embed</strong>
                <button type="button" class="copy-btn" id="copyIframeEmbedBtn">Copy</button>
            </div>
            <div class="summary-muted mb-2">Best for WordPress, Shopify, Wix, Squarespace, and other CMS tools.</div>
            <pre class="embed-code-box mb-0"><code id="embedIframeCode"></code></pre>
        </div>
    </div>
</div>

@if(!currentUser()->isClient())
<div class="overlay-panel drawer-panel" id="shortcodeDrawer" aria-hidden="true">
    <div class="panel-header">
        <div>
            <h6 class="panel-title" id="drawerTitle">Shortcode Settings</h6>
            <div class="panel-subtitle" id="drawerSubtitle">Update shortcode settings.</div>
        </div>
        <button type="button" class="panel-close" data-close-panel>&times;</button>
    </div>
    <form id="shortcodeDrawerForm" class="d-flex flex-column h-100">
        <div class="panel-body">
            <div id="drawerAlert"></div>
            <input type="hidden" id="drawerShortcodeId">

            <div class="form-group">
                <label class="form-label">Shortcode Code</label>
                <div class="code-cell" id="drawerShortcodeCode"></div>
            </div>

            <div class="form-group">
                <label for="drawerClientId" class="form-label">Assign Client</label>
                <select class="form-select" id="drawerClientId">
                    <option value="">— None —</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}">{{ $c->name ?: $c->email }}</option>
                    @endforeach
                </select>
                <small class="form-help">Selected client can access related submissions.</small>
            </div>

            <div class="form-group">
                <label for="drawerWebsite" class="form-label">Website</label>
                <input type="text" class="form-control" id="drawerWebsite" placeholder="e.g. example.com">
                <small class="form-help">Optional note for where this shortcode is embedded.</small>
            </div>

            <div class="form-group">
                <label class="form-label">After Successful Submission</label>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="drawerRedirectToggle">
                    <label class="form-check-label" for="drawerRedirectToggle">
                        Redirect visitor to a URL instead of showing a success message
                    </label>
                </div>
                <div id="drawerRedirectWrap" style="display:none;">
                    <input type="url" class="form-control" id="drawerRedirectUrl" placeholder="https://example.com/thank-you">
                    <small class="form-help">Enter a full URL including `http://` or `https://`.</small>
                </div>
            </div>

            <div class="form-group">
                <label for="drawerButtonColor" class="form-label">Submit Button Color</label>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <input
                        type="color"
                        id="drawerButtonColor"
                        value="#0d6efd"
                        style="width: 3rem; height: 2.5rem; padding: 0.15rem; border: 1px solid #ced4da; border-radius: 0.375rem; background: #fff; cursor: pointer;"
                    >
                    <input
                        type="text"
                        class="form-control"
                        id="drawerButtonColorText"
                        value="#0d6efd"
                        maxlength="7"
                        placeholder="#0d6efd"
                        style="max-width: 140px;"
                    >
                </div>
                <small class="form-help">Color used for the public form submit button.</small>
            </div>
        </div>
        <div class="panel-footer">
            <button type="button" class="btn-outline-secondary" data-close-panel>Cancel</button>
            <button type="submit" class="btn-primary" id="drawerSaveBtn">Save Changes</button>
        </div>
    </form>
</div>
@endif

@endsection

@section('footer')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'}`;
    toast.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
    toast.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    var baseUrl = @json(url('/'));
    var overlayBackdrop = document.getElementById('overlayBackdrop');
    var embedModal = document.getElementById('embedModal');
    var shortcodeDrawer = document.getElementById('shortcodeDrawer');
    var embedJsCode = document.getElementById('embedJsCode');
    var embedIframeCode = document.getElementById('embedIframeCode');
    var embedModalTitle = document.getElementById('embedModalTitle');
    var activePanel = null;

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function buildIframeEmbedCode(baseUrl, code) {
        var iframeId = 'lm-iframe-' + code;
        var src = baseUrl + '/api/shortcode/' + code + '/html';
        var endScript = '<' + '/script>';
        var listener = '(function(){var id="' + iframeId + '";window.addEventListener("message",function(e){if(!e.data||e.data.type!=="leadmatrix-resize")return;var f=document.getElementById(id);if(f&&e.data.height){f.style.height=Math.max(120,e.data.height)+"px";}});})();';

        return '<iframe id="' + iframeId + '" src="' + src + '" width="100%" style="border:0;width:100%;display:block;overflow:hidden;" scrolling="no" frameborder="0"></iframe>\n<script>' + listener + endScript;
    }

    function copyText(text, successMessage) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                showToast(successMessage || 'Copied to clipboard!', 'success');
            }, function() {
                fallbackCopy(text, successMessage);
            });
        } else {
            fallbackCopy(text, successMessage);
        }
    }

    function fallbackCopy(text, successMessage) {
        var textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.left = '-9999px';
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showToast(successMessage || 'Copied to clipboard!', 'success');
    }

    function openPanel(panel) {
        closePanels();
        activePanel = panel;
        overlayBackdrop.classList.add('is-open');
        panel.classList.add('is-open');
        panel.setAttribute('aria-hidden', 'false');
    }

    function closePanels() {
        overlayBackdrop.classList.remove('is-open');
        document.querySelectorAll('.overlay-panel').forEach(function(panel) {
            panel.classList.remove('is-open');
            panel.setAttribute('aria-hidden', 'true');
        });
        activePanel = null;
    }

    function renderClientSummary(row, clientName) {
        var cell = row.querySelector('.shortcode-client-summary');
        if (!cell) return;
        if (clientName) {
            cell.innerHTML = '<div class="summary-title">' + escapeHtml(clientName) + '</div><div class="summary-muted">Assigned client</div>';
        } else {
            cell.innerHTML = '<span class="empty-value">No client assigned</span>';
        }
    }

    function renderWebsiteSummary(row, website) {
        var cell = row.querySelector('.shortcode-website-summary');
        if (!cell) return;
        if (website) {
            cell.innerHTML = '<div class="summary-title">' + escapeHtml(website) + '</div><div class="summary-muted">Target website</div>';
        } else {
            cell.innerHTML = '<span class="empty-value">No website</span>';
        }
    }

    function renderSuccessSummary(row, successAction, redirectUrl) {
        var cell = row.querySelector('.shortcode-success-summary');
        if (!cell) return;
        if (successAction === 'redirect' && redirectUrl) {
            cell.innerHTML = '<span class="summary-badge badge-primary-light">Redirect</span><div class="summary-subtext">' + escapeHtml(redirectUrl) + '</div>';
        } else {
            cell.innerHTML = '<span class="summary-badge badge-neutral">Message</span><div class="summary-subtext">Show success message</div>';
        }
    }

    overlayBackdrop.addEventListener('click', closePanels);
    document.querySelectorAll('[data-close-panel]').forEach(function(btn) {
        btn.addEventListener('click', closePanels);
    });
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && activePanel) {
            closePanels();
        }
    });

    document.querySelectorAll('.shortcode-embed-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var code = this.getAttribute('data-code');
            var formName = this.getAttribute('data-form-name') || 'Shortcode Form';
            var jsCode = '<script src="' + baseUrl + '/js/leadmatrix-embed.js"><' + '/script>\n<div data-leadmatrix-code="' + code + '"></div>';
            var iframeCode = buildIframeEmbedCode(baseUrl, code);

            embedModalTitle.textContent = 'Embed Code: ' + formName;
            embedJsCode.textContent = jsCode;
            embedIframeCode.textContent = iframeCode;
            document.getElementById('copyJsEmbedBtn').onclick = function() {
                copyText(jsCode, 'JavaScript embed copied!');
            };
            document.getElementById('copyIframeEmbedBtn').onclick = function() {
                copyText(iframeCode, 'Iframe embed copied!');
            };
            openPanel(embedModal);
        });
    });

    @if(!currentUser()->isClient())
    var drawerForm = document.getElementById('shortcodeDrawerForm');
    var drawerAlert = document.getElementById('drawerAlert');
    var drawerShortcodeId = document.getElementById('drawerShortcodeId');
    var drawerShortcodeCode = document.getElementById('drawerShortcodeCode');
    var drawerTitle = document.getElementById('drawerTitle');
    var drawerSubtitle = document.getElementById('drawerSubtitle');
    var drawerClientId = document.getElementById('drawerClientId');
    var drawerWebsite = document.getElementById('drawerWebsite');
    var drawerRedirectToggle = document.getElementById('drawerRedirectToggle');
    var drawerRedirectWrap = document.getElementById('drawerRedirectWrap');
    var drawerRedirectUrl = document.getElementById('drawerRedirectUrl');
    var drawerButtonColor = document.getElementById('drawerButtonColor');
    var drawerButtonColorText = document.getElementById('drawerButtonColorText');
    var drawerSaveBtn = document.getElementById('drawerSaveBtn');
    var currentEditButton = null;

    function setDrawerAlert(message, type) {
        if (!message) {
            drawerAlert.innerHTML = '';
            return;
        }
        drawerAlert.innerHTML = '<div class="alert alert-' + (type === 'danger' ? 'danger' : 'success') + ' drawer-alert">' + escapeHtml(message) + '</div>';
    }

    function isValidHexColor(value) {
        return /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(String(value || '').trim());
    }

    function syncDrawerButtonColor(fromPicker) {
        if (fromPicker) {
            drawerButtonColorText.value = drawerButtonColor.value;
            return;
        }
        var textVal = (drawerButtonColorText.value || '').trim();
        if (isValidHexColor(textVal)) {
            drawerButtonColor.value = textVal;
        }
    }

    function syncDrawerRedirect() {
        var enabled = drawerRedirectToggle.checked;
        drawerRedirectWrap.style.display = enabled ? '' : 'none';
        drawerRedirectUrl.required = enabled;
        if (!enabled) {
            drawerRedirectUrl.value = '';
        }
    }

    drawerRedirectToggle.addEventListener('change', syncDrawerRedirect);
    drawerButtonColor.addEventListener('input', function() { syncDrawerButtonColor(true); });
    drawerButtonColor.addEventListener('change', function() { syncDrawerButtonColor(true); });
    drawerButtonColorText.addEventListener('input', function() { syncDrawerButtonColor(false); });
    drawerButtonColorText.addEventListener('change', function() { syncDrawerButtonColor(false); });

    document.querySelectorAll('.shortcode-edit-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            currentEditButton = this;
            drawerShortcodeId.value = this.getAttribute('data-id');
            drawerShortcodeCode.textContent = this.getAttribute('data-code');
            drawerTitle.textContent = 'Shortcode Settings';
            drawerSubtitle.textContent = (this.getAttribute('data-form-name') || 'Shortcode Form') + ' settings';
            drawerClientId.value = this.getAttribute('data-client-id') || '';
            drawerWebsite.value = this.getAttribute('data-website') || '';
            drawerRedirectToggle.checked = (this.getAttribute('data-success-action') || 'message') === 'redirect';
            drawerRedirectUrl.value = this.getAttribute('data-redirect-url') || '';
            var buttonColor = this.getAttribute('data-button-color') || '#0d6efd';
            drawerButtonColor.value = isValidHexColor(buttonColor) ? buttonColor : '#0d6efd';
            drawerButtonColorText.value = drawerButtonColor.value;
            setDrawerAlert('');
            syncDrawerRedirect();
            openPanel(shortcodeDrawer);
        });
    });

    drawerForm.addEventListener('submit', function(event) {
        event.preventDefault();
        var id = drawerShortcodeId.value;
        var row = document.querySelector('tr[data-shortcode-id="' + id + '"]');
        var redirectEnabled = drawerRedirectToggle.checked;
        var redirectUrl = drawerRedirectUrl.value.trim();
        var website = drawerWebsite.value.trim();
        var clientId = drawerClientId.value || '';
        var buttonColor = (drawerButtonColor.value || '#0d6efd').trim();
        var body = new URLSearchParams();
        var url = "{{ url('user/shortcodes') }}/" + id;

        if (redirectEnabled && !redirectUrl) {
            setDrawerAlert('Redirect URL required.', 'danger');
            return;
        }

        setDrawerAlert('');
        drawerSaveBtn.disabled = true;
        drawerSaveBtn.textContent = 'Saving...';

        var settings = {
            success_action: redirectEnabled ? 'redirect' : 'message',
            button_color: buttonColor
        };
        if (redirectEnabled) {
            settings.redirect_url = redirectUrl;
        }

        body.append('_token', '{{ csrf_token() }}');
        body.append('_method', 'PUT');
        body.append('client_id', clientId);
        body.append('website', website);
        body.append('settings', JSON.stringify(settings));

        fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: body
        })
        .then(function(response) {
            return response.json().then(function(data) {
                return { ok: response.ok, data: data };
            });
        })
        .then(function(result) {
            var data = result.data || {};
            if (!result.ok || !data.success) {
                setDrawerAlert(data.message || 'Could not save shortcode settings.', 'danger');
                return;
            }

            var selectedOption = drawerClientId.options[drawerClientId.selectedIndex];
            var clientName = clientId && selectedOption ? selectedOption.text : '';
            renderClientSummary(row, clientName);
            renderWebsiteSummary(row, website);
            renderSuccessSummary(row, redirectEnabled ? 'redirect' : 'message', redirectUrl);

            if (currentEditButton) {
                currentEditButton.setAttribute('data-client-id', clientId);
                currentEditButton.setAttribute('data-website', website);
                currentEditButton.setAttribute('data-success-action', redirectEnabled ? 'redirect' : 'message');
                currentEditButton.setAttribute('data-redirect-url', redirectEnabled ? redirectUrl : '');
                currentEditButton.setAttribute('data-button-color', buttonColor);
            }

            showToast('Shortcode settings saved.', 'success');
            closePanels();
        })
        .catch(function() {
            setDrawerAlert('Something went wrong while saving.', 'danger');
        })
        .finally(function() {
            drawerSaveBtn.disabled = false;
            drawerSaveBtn.textContent = 'Save Changes';
        });
    });
    @endif

    document.querySelectorAll('.shortcode-delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var code = this.getAttribute('data-code');
            if (!confirm('Delete shortcode "' + code + '"? Embedded forms using this code will stop working.')) return;
            var row = document.querySelector('tr[data-shortcode-id="' + id + '"]');
            var url = "{{ url('user/shortcodes') }}/" + id;
            var body = new URLSearchParams();
            body.append('_token', '{{ csrf_token() }}');
            body.append('_method', 'DELETE');
            fetch(url, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: body
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    if (row) row.remove();
                    showToast('Shortcode deleted.', 'success');
                } else {
                    showToast(data.message || 'Could not delete.', 'danger');
                }
            })
            .catch(function() { showToast('Error deleting shortcode.', 'danger'); });
        });
    });
});
</script>
@endsection

