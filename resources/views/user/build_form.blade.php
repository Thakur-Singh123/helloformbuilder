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
        
        /* Custom styles for the RIGHT SIDEBAR DRAWER (replaces modal) */
        
        /* Backdrop overlay */
        .sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 1040; /* Below sidebar, above page content */
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s;
        }

        /* The actual sidebar panel */
        #editFormSidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: 90%; /* Default mobile width */
            max-width: 450px; /* Max width for desktop */
            height: 100%;
            background-color: #ffffff;
            z-index: 1050; /* Above backdrop */
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            transform: translateX(100%); /* Start off-screen */
            transition: transform 0.3s ease-out;
            display: flex; /* Flex container for full height content/footer */
            flex-direction: column;
        }

        /* State when open */
        .sidebar-open .sidebar-backdrop {
            opacity: 1;
            visibility: visible;
        }
        .sidebar-open #editFormSidebar {
            transform: translateX(0); /* Slide into view */
        }

        /* Customizing the inner content to look like the old modal */
        .sidebar-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #eee;
            flex-shrink: 0;
        }
        .sidebar-body {
            padding: 0 2rem;
            overflow-y: auto; /* Allow scrolling for long forms */
            flex-grow: 1;
        }
        .sidebar-footer {
            padding: 1rem 2rem 2rem;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            flex-shrink: 0;
        }
        .sidebar-title {
            font-weight: 600;
            margin-bottom: 0;
        }

        /* Form field group styling */
        .form-field-group {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #f8f9fa;
        }
        .form-field-group .form-control:focus,
        .form-field-group .form-select:focus {
            box-shadow: none;
            border-color: #86b7fe;
        }
        .form-field-group .btn-icon-only {
            padding: 0.375rem 0.625rem;
            border-radius: 0.25rem;
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            color: #6c757d;
        }
        .form-field-group .btn-icon-only:hover {
            background-color: #dee2e6;
        }

        .add-form-field-btn {
            border: 1px dashed #ced4da;
            background-color: transparent;
            color: #6c757d;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            width: 100%;
            transition: all 0.2s;
        }
        .add-form-field-btn:hover {
            background-color: #e9ecef;
            color: #495057;
        }

        /* Floating action button for notification (positioning adjusted slightly for clarity) */
        .fab-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: #28a745;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            z-index: 1030;
            cursor: pointer;
        }

        /* Ensure form label required field is visible */
        .required-field::after {
            content: " *";
            color: red;
            font-weight: bold;
        }

    </style>
</head>
<body>

<div class="dashboard-container">
    <!-- Breadcrumb Navigation Area -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Survey Forms</a></li>
            <li class="breadcrumb-item active" aria-current="page">Forms</li>
        </ol>
    </nav>
Sobia
    <div class="content-card">
        <h1 class="h4 mb-4">Forms</h1>

        <!-- Header Row: Add New Button and Search Bar -->
        <div class="row align-items-center mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <a href="{{route('add_new_form')}}" class="btn btn-primary rounded-3 shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Add New Form
                </a>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control rounded-start-3" placeholder="Search By Name" aria-label="Search By Name">
                    <button class="btn btn-outline-secondary rounded-end-3" type="button">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Forms Data Table -->
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th style="width: 5%;"><input class="form-check-input" type="checkbox"></th>
                        <th style="width: 25%;">Name</th>
                        <th style="width: 45%;">Form Fields</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 15%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1: Work-Life Balance Survey -->
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td class="fw-medium">Work-Life Balance Survey</td>
                        <td class="text-secondary small">
                            Do you feel you have a good work-life balance?<br>
                            How many hours do you work per week?<br>
                            Any suggestions for improving work-life balance?
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" checked>
                            </div>
                        </td>
                        <td>
                            <!-- ACTION: Changed from data-bs-toggle to JavaScript call -->
                            <button class="action-btn edit-form-btn" title="Edit" onclick="toggleSidebar(true)"><i class="bi bi-pencil-fill"></i></button>
                            <button class="action-btn" title="Delete"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>

                    <!-- Row 2: Workplace Safety Survey -->
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td class="fw-medium">Workplace Safety Survey</td>
                        <td class="text-secondary small">
                            Do you feel safe at work?<br>
                            Have you received safety training?<br>
                            Any suggestions for safety improvements?
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" checked>
                            </div>
                        </td>
                        <td>
                            <button class="action-btn edit-form-btn" title="Edit" onclick="toggleSidebar(true)"><i class="bi bi-pencil-fill"></i></button>
                            <button class="action-btn" title="Delete"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>

                    <!-- Row 3: Employee Feedback -->
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td class="fw-medium">Employee Feedback</td>
                        <td class="text-secondary small">
                            How satisfied are you with your job?<br>
                            Do you feel valued in the company?<br>
                            Any suggestions for improving the work culture?
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" checked>
                            </div>
                        </td>
                        <td>
                            <button class="action-btn edit-form-btn" title="Edit" onclick="toggleSidebar(true)"><i class="bi bi-pencil-fill"></i></button>
                            <button class="action-btn" title="Delete"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>

                    <!-- Row 4: Monthly Suggestions -->
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td class="fw-medium">Monthly Suggestions</td>
                        <td class="text-secondary small">
                            What's your name?<br>
                            Are you happy with current work?<br>
                            Any suggestion for improving the company growth?
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" checked>
                            </div>
                        </td>
                        <td>
                            <button class="action-btn edit-form-btn" title="Edit" onclick="toggleSidebar(true)"><i class="bi bi-pencil-fill"></i></button>
                            <button class="action-btn" title="Delete"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>
                    
                    <!-- (Add more rows here as needed) -->
                </tbody>
            </table>
        </div>
        
        <!-- Pagination and Items Per Page -->
        <div class="d-flex justify-content-end align-items-center pt-3">
            <!-- Items per page dropdown -->
            <div class="me-3 d-flex align-items-center small">
                <span class="me-2 text-muted">10 / page</span>
                <select class="form-select form-select-sm" style="width: auto;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous"><i class="bi bi-chevron-left"></i></a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next"><i class="bi bi-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
        </div>

    </div>
</div>

<!-- SIDEBAR BACKDROP -->
<div class="sidebar-backdrop" onclick="toggleSidebar(false)"></div>

<!-- EDIT FORM SIDEBAR (Replaced Modal Structure) -->
<div id="editFormSidebar">
    
    <!-- Sidebar Header -->
    <div class="sidebar-header d-flex justify-content-between align-items-center">
        <h5 class="sidebar-title" id="editFormSidebarLabel">Edit Form</h5>
        <button type="button" class="btn-close" aria-label="Close" onclick="toggleSidebar(false)"></button>
    </div>
    
    <!-- Sidebar Body (Content) -->
    <div class="sidebar-body">
        <form class="py-4">
            <div class="mb-4">
                <label for="formName" class="form-label required-field">Name</label>
                <input type="text" class="form-control" id="formName" value="Work-Life Balance Survey">
            </div>

            <div class="mb-4">
                <label class="form-label">Status</label>
                <div class="btn-group w-100 status-toggle" role="group" aria-label="Status toggle">
                    <input type="radio" class="btn-check" name="statusToggle" id="statusActive" autocomplete="off" checked>
                    <label class="btn btn-outline-primary" for="statusActive">Active</label>

                    <input type="radio" class="btn-check" name="statusToggle" id="statusInactive" autocomplete="off">
                    <label class="btn btn-outline-primary" for="statusInactive">Inactive</label>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Form Fields</label>
                <div id="formFieldsContainer">
                    <!-- Form Field 1 -->
                    <div class="d-flex align-items-center mb-3">
                        <input type="text" class="form-control me-2" value="Do you feel you have a good work-life balance?">
                        <select class="form-select me-2" style="width: 120px;">
                            <option selected>Input</option>
                            <option>Text Area</option>
                            <option>Checkbox</option>
                            <option>Radio</option>
                        </select>
                        <button type="button" class="btn btn-icon-only"><i class="bi bi-x"></i></button>
                    </div>
                    <!-- Form Field 2 -->
                    <div class="d-flex align-items-center mb-3">
                        <input type="text" class="form-control me-2" value="How many hours do you work per week?">
                        <select class="form-select me-2" style="width: 120px;">
                            <option selected>Input</option>
                            <option>Text Area</option>
                            <option>Checkbox</option>
                            <option>Radio</option>
                        </select>
                        <button type="button" class="btn btn-icon-only"><i class="bi bi-x"></i></button>
                    </div>
                    <!-- Form Field 3 -->
                    <div class="d-flex align-items-center mb-3">
                        <input type="text" class="form-control me-2" value="Any suggestions for improving work-life balance?">
                        <select class="form-select me-2" style="width: 120px;">
                            <option>Input</option>
                            <option selected>Text Area</option>
                            <option>Checkbox</option>
                            <option>Radio</option>
                        </select>
                        <button type="button" class="btn btn-icon-only"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                <button type="button" class="btn add-form-field-btn">
                    <i class="bi bi-plus-lg me-2"></i> Add Form Field
                </button>
            </div>
        </form>
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <button type="button" class="btn btn-secondary me-2" onclick="toggleSidebar(false)">Cancel</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</div>


<!-- Floating Action Button for Notification -->
<div class="fab-notification">
    <i class="bi bi-bell-fill"></i>
    <span class="badge rounded-pill">3</span>
</div>
@stop

       @section('footer')

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    /**
     * Toggles the visibility of the right sidebar.
     * @param {boolean} show - True to show the sidebar, False to hide it.
     */
    function toggleSidebar(show) {
        const body = document.body;
        
        if (show) {
            body.classList.add('sidebar-open');
        } else {
            body.classList.remove('sidebar-open');
        }
    }

    // Initialize listeners on DOM load
    document.addEventListener('DOMContentLoaded', function() {
        // Optional: Close sidebar if ESC key is pressed
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape" && document.body.classList.contains('sidebar-open')) {
                toggleSidebar(false);
            }
        });
        
        // This is where you would load form data, similar to the previous modal functionality
        const editButtons = document.querySelectorAll('.edit-form-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                // In a real application, you would fetch form data here before opening the sidebar
                console.log('Edit button clicked. Sidebar toggled.');
            });
        });
    });
</script>


       @stop