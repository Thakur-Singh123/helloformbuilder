@extends('user.include.layout')
        @section('content')
    <style>
        /* Custom CSS for base layout */
        body {
            background-color: #f8f9fa; /* Light gray background */
            font-family: 'Inter', sans-serif;
            padding-top: 20px;
        }

        .dashboard-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Styling for the main content card/area */
        .content-card {
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        /* Table specific styles */
        .table thead th {
            font-weight: 600;
            color: #343a40;
            border-top: none;
            border-bottom: 2px solid #dee2e6;
        }
        .table td {
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
            padding: 1.5rem 0.75rem; /* Increased padding for row height */
        }
        .table tr:last-child td {
            border-bottom: none; /* Remove bottom border from last row */
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Action buttons style */
        .action-btn {
            color: #6c757d;
            border: 1px solid #ced4da;
            background-color: #f8f9fa;
            padding: 0.5rem 0.6rem;
            border-radius: 0.375rem;
            margin-left: 5px;
            transition: all 0.2s;
        }
        .action-btn:hover {
            background-color: #e9ecef;
            color: #343a40;
        }

        .action-btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            color: #6c757d;
            border: 1px solid #ced4da;
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            margin-left: 0.35rem;
            transition: all 0.2s;
            text-decoration: none;
        }
        .action-btn-icon:hover {
            background-color: #e9ecef;
            color: #343a40;
        }
        .action-btn-icon.edit-form-btn:hover {
            color: #0d6efd;
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }
        .action-btn-icon.delete-form-btn:hover {
            color: #dc3545;
            border-color: #dc3545;
            background-color: #fde8ea;
        }

        </style>
    </head>
    <body>

    <div class="dashboard-container">
        <!-- Breadcrumb Navigation Area -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.9rem;">
                <li class="breadcrumb-item"><a href="{{ route('user_dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('build_form') }}">Build Form</a></li>
                <li class="breadcrumb-item active" aria-current="page">Forms</li>
            </ol>
        </nav>
     
        <div class="content-card">
            <h1 class="h4 mb-4">Forms</h1>

            <!-- Header Row: Add New Button and Search Bar -->
            <div class="row align-items-center mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    @if(!currentUser()->isClient())
                    <a href="{{route('add_new_form')}}" class="btn btn-primary rounded-3 shadow-sm">
                        <i class="bi bi-plus-lg me-2"></i> Add New Form
                    </a>
                    @else
                    <p class="text-muted small mb-0">Forms that have received submissions on your shortcodes (read-only).</p>
                    @endif
                </div>
                <div class="col-md-6">
                    <form method="GET" action="{{ route('list_view') }}" class="input-group">
                        <input type="text" name="search" class="form-control rounded-start-3" placeholder="Search by form name" value="{{ request('search') }}" aria-label="Search">
                        <button class="btn btn-outline-secondary rounded-end-3" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                        @if(request('search'))
                        <a href="{{ route('list_view') }}" class="btn btn-outline-secondary ms-1">Clear</a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Forms Data Table -->
            @if($forms->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            @if(!currentUser()->isClient())<th style="width: 4%;"><input class="form-check-input" type="checkbox"></th>@endif
                            <th style="width: 14%;">Name</th>
                            <th style="width: 16%;">Public display</th>
                            <th style="width: 18%;">Form Fields</th>
                            <th style="width: {{ currentUser()->isClient() ? '24%' : '20%' }};">Shortcode</th>
                            <th style="width: 10%;">Status</th>
                            <th style="width: 16%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($forms as $form)
                            <tr data-form-id="{{ $form->id }}">
                                @if(!currentUser()->isClient())<td><input class="form-check-input" type="checkbox"></td>@endif
                                <td class="fw-medium">{{ $form->name }}</td>
                                <td class="small text-secondary">
                                    @if($form->heading)
                                        <div><strong>Heading:</strong> {{ $form->heading }}</div>
                                    @endif
                                    @if($form->title)
                                        <div><strong>Title:</strong> {{ $form->title }}</div>
                                    @endif
                                    @if($form->description)
                                        <div class="text-truncate" style="max-width: 180px;" title="{{ $form->description }}"><strong>Desc:</strong> {{ Str::limit($form->description, 60) }}</div>
                                    @endif
                                    @if(!$form->heading && !$form->title && !$form->description)
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-secondary small">
                                    @if($form->fields->count() > 0)
                                        @foreach($form->fields->sortBy('order')->take(5) as $field)
                                            <span class="badge bg-light text-dark border me-1 mb-1">{{ $field->field_name }}</span>
                                        @endforeach
                                        @if($form->fields->count() > 5)
                                            <span class="text-muted">+{{ $form->fields->count() - 5 }} more</span>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-secondary small">
                                    @php $primaryShortcode = $form->shortcodes->sortBy('id')->first(); @endphp
                                    @if($primaryShortcode)
                                        <div class="shortcode-row mb-2">
                                            <code class="d-inline-block bg-light px-2 py-1 rounded small me-1" title="Embed code: {{ $primaryShortcode->code }}">{{ $primaryShortcode->code }}</code>
                                            @if(currentUser()->isClient())
                                            <div class="btn-group btn-group-sm mt-1">
                                                <button type="button" class="btn btn-outline-secondary btn-sm py-0 copy-embed-btn" data-type="js" data-code="{{ $primaryShortcode->code }}" title="Copy JavaScript">JS</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm py-0 copy-embed-btn" data-type="iframe" data-code="{{ $primaryShortcode->code }}" title="Copy Iframe">Iframe</button>
                                            </div>
                                            @else
                                            <div class="mt-1">
                                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 copy-embed-btn" data-type="js" data-code="{{ $primaryShortcode->code }}" title="Copy JS embed">Copy JS</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 copy-embed-btn" data-type="iframe" data-code="{{ $primaryShortcode->code }}" title="Copy Iframe">Copy Iframe</button>
                                            </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if(!currentUser()->isClient())
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggle-status"
                                            type="checkbox"
                                            role="switch"
                                            data-id="{{ $form->id }}"
                                            data-status="{{ $form->status }}"
                                            {{ $form->status === 'active' ? 'checked' : '' }}>
                                    </div>
                                    @else
                                    <span class="badge bg-{{ $form->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($form->status) }}</span>
                                    @endif
                                </td>

                                <td>
                                    @if(!currentUser()->isClient())
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('edit_form', $form->id) }}"
                                           class="action-btn-icon edit-form-btn"
                                           title="Edit form"
                                           aria-label="Edit form {{ $form->name }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button"
                                                class="action-btn-icon delete-form-btn border-0"
                                                onclick="deleteForm({{ $form->id }})"
                                                title="Delete form"
                                                aria-label="Delete form {{ $form->name }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    @else
                                    <span class="text-muted small">View only</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-file-earmark-text display-4 text-muted"></i>
                <h5 class="mt-3 mb-2">No forms found</h5>
                <p class="text-muted mb-3">
                    @if(request('search'))
                        No forms match "{{ request('search') }}". Try a different search.
                    @elseif(currentUser()->isClient())
                        Forms that receive submissions on your shortcodes will appear here.
                    @else
                        Create your first form to get started.
                    @endif
                </p>
                @if(request('search'))
                    <a href="{{ route('list_view') }}" class="btn btn-outline-primary">Clear search</a>
                @elseif(currentUser()->isClient())
                    <a href="{{ route('shortcodes.index') }}" class="btn btn-outline-primary"><i class="bi bi-code-square me-1"></i> View shortcodes</a>
                    <a href="{{ route('submissions.index') }}" class="btn btn-primary ms-2"><i class="bi bi-envelope me-1"></i> View submissions</a>
                @else
                    <a href="{{ route('add_new_form') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add New Form</a>
                @endif
            </div>
            @endif
        </div>
    </div>

    @stop

       @section('footer')

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
var baseUrl = "{{ url('/') }}";
var apiShortcodeHtml = "{{ route('api.shortcode.html', '__CODE__') }}".replace('__CODE__', '');

function copyCode(element) {
    var text = element.textContent || element.innerText;
    copyToClipboard(text);
}
function copyToClipboard(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function() { showCopyFeedback(); }, function() { fallbackCopy(text); });
    } else { fallbackCopy(text); }
}
function fallbackCopy(text) {
    var ta = document.createElement('textarea');
    ta.value = text;
    document.body.appendChild(ta);
    ta.select();
    try { document.execCommand('copy'); showCopyFeedback(); } catch (e) { alert('Copy failed'); }
    document.body.removeChild(ta);
}
function showCopyFeedback() {
    var t = document.createElement('span');
    t.className = 'text-success small ms-1';
    t.textContent = 'Copied!';
    document.body.appendChild(t);
    setTimeout(function() { t.remove(); }, 2000);
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.copy-embed-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var code = this.getAttribute('data-code');
            var type = this.getAttribute('data-type');
            var text;
            if (type === 'js') {
                text = '<script src="' + baseUrl + '/js/leadmatrix-embed.js"><\/script>\n<div data-leadmatrix-code="' + code + '"></div>';
            } else {
                text = '<iframe src="' + baseUrl + '/api/shortcode/' + code + '/html" width="100%" height="600" frameborder="0"></iframe>';
            }
            copyToClipboard(text);
            var label = this.textContent;
            this.textContent = 'Copied!';
            setTimeout(function() { btn.textContent = label; }, 1500);
        });
    });
});
</script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        /**
         * Delete form function
         */
        function deleteForm(formId) {
            if (!confirm('Are you sure you want to delete this form? This action cannot be undone.')) {
                return;
            }

            $.ajax({
                url: "{{ url('user/form') }}/" + formId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success || response.message) {
                        alert('Form deleted successfully!');
                        location.reload();
                    } else {
                        alert('Form deleted successfully!');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        alert('Form not found.');
                    } else if (xhr.status === 403) {
                        alert('You do not have permission to delete this form.');
                    } else {
                        alert('An error occurred while deleting the form. Please try again.');
                    }
                }
            });
        }

        /**
         * Toggle form status (status-only — does not touch form fields)
         */
        document.addEventListener('DOMContentLoaded', function() {
            const toggleSwitches = document.querySelectorAll('.toggle-status');
            
            toggleSwitches.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const formId = this.getAttribute('data-id');
                    const isChecked = this.checked;
                    const newStatus = isChecked ? 'active' : 'inactive';
                    
                    $.ajax({
                        url: "{{ route('custom_form_status', ':id') }}".replace(':id', formId),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: newStatus,
                        },
                        success: function(response) {
                            console.log('Form status updated to:', newStatus);
                        },
                        error: function(xhr) {
                            toggle.checked = !isChecked;
                            const errorMsg = xhr.responseJSON?.message || 'Failed to update form status. Please try again.';
                            alert(errorMsg);
                        }
                    });
                });
            });
        });
    </script>


       @stop