<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Dashboard Design</title>
    <!-- Load Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Load Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Custom CSS for the Dashboard Layout */
        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #1e2837; /* Dark Blue/Gray */
            --sidebar-link-hover: #34495e;
            --main-bg: #f4f7f9;
            --primary-color: #3b82f6; /* Blue */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--main-bg);
            overflow-x: hidden;
        }
        #wrapper {
            min-height: 100vh;
        }

        /* 1. Sidebar Styles */
        #sidebar-wrapper {
            width: var(--sidebar-width);
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            position: fixed;
            top: 0;
            left: 0;
            transition: margin 0.3s ease-out;
            z-index: 1030; /* Above regular content */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar-heading {
            padding: 1rem;
            color: #f8f9fa;
            font-size: 1.25rem;
            font-weight: 700;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .list-group-item {
            background-color: transparent;
            color: #adb5bd; /* Light gray text */
            border: none;
            padding: 0.75rem 1rem;
            transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out;
            border-radius: 8px; /* Rounded corners for links */
            margin: 4px 10px;
            cursor: pointer;
        }

        /* Sidebar nav: keep dark hover (override Bootstrap's white list-group-item-action hover) */
        #sidebar-wrapper .list-group-item.active,
        #sidebar-wrapper .list-group-item:hover,
        #sidebar-wrapper .list-group-item:focus {
            background-color: var(--sidebar-link-hover) !important;
            color: #fff !important;
        }
        
        .list-group-item i {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }

        /* Logout button styling */
        .logout-link {
            position: absolute;
            bottom: 20px;
            left: 10px;
            right: 10px;
        }

        /* 2. Spacer: reserves space for fixed sidebar so main content starts after it */
        #sidebar-spacer {
            width: var(--sidebar-width);
            flex-shrink: 0;
        }
        /* 3. Main Content - always offset so fixed sidebar never overlaps */
        #page-content-wrapper {
            flex: 1 1 auto;
            min-width: 0;
            box-sizing: border-box;
            min-height: 100vh;
            padding: 20px;
            margin-left: var(--sidebar-width);
            position: relative;
            z-index: 1;
            overflow-x: auto;
            transition: width 0.3s ease-out, margin-left 0.3s ease-out;
        }
        /* Hide spacer; margin on content handles offset */
        #sidebar-spacer {
            display: none !important;
        }

        /* Content Header/Nav */
        .navbar {
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            background-color: white;
            padding: 0.5rem 1rem;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
        }

        /* 4. Mobile Responsiveness */
        @media (max-width: 992px) {
            #sidebar-wrapper {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            #page-content-wrapper {
                width: 100%;
                margin-left: 0;
            }
            .toggled #sidebar-wrapper {
                margin-left: 0;
            }
            .toggled #page-content-wrapper {
                margin-left: var(--sidebar-width);
            }
        }
        
        /* Utility for sidebar toggle state */
        .menu-toggle-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            transition: background-color 0.15s;
        }
        
        .menu-toggle-btn:hover {
            background-color: #316dcb;
            color: white;
        }
    </style>
</head>
<body>

    <div class="d-flex w-100" id="wrapper">
        <!-- Spacer for fixed sidebar (keeps content to the right) -->
        <div id="sidebar-spacer" class="d-none d-lg-block" aria-hidden="true"></div>
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="bi bi-gear-wide-connected"></i> App Dashboard
            </div>
            <div class="list-group list-group-flush pt-3">
                
                <!-- Dashboard Link -->
                <a href="{{ route('employee.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
 

               
                    <a href="{{ route('employee.tasks') }}" class="list-group-item list-group-item-action {{ request()->routeIs('employee.tasks') ? 'active' : '' }}">
                        <i class="bi bi-list-check"></i> 
                        <span>My Tasks</span>
                    </a>
     

 
              
        
                <a href="{{ route('employee.attendance') }}" class="list-group-item list-group-item-action {{ request()->routeIs('attendance*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i> Attendance
                </a>
          
                
       
                <a href="{{ route('shortcodes.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('shortcodes.*') ? 'active' : '' }}">
                    <i class="bi bi-code-square"></i> Leave
                </a>
                
             
                <a href="{{ route('admin.teams.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.teams.*') ? 'active' : '' }}">
                    <i class="bi bi-code-square"></i> Messages
                </a>
 
 
         

            </div>

            <!-- Logout Link (Fixed to the bottom) -->
            <div class="logout-link">
                @php
                    $logoutRoute = currentUser()->user_type === 'superadmin' ? 'superadmin_logout' : 'logout';
                @endphp
                <a href="{{ route($logoutRoute) }}" 
                   class="list-group-item list-group-item-action text-danger"
                   onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
                <form id="sidebar-logout-form" action="{{ route($logoutRoute) }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            
            @unless(request()->routeIs('admin.clients.index'))
            <!-- Main Content Header/Navbar (Overview) - hidden on Clients list page -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4">
                <div class="container-fluid">
                    <button class="menu-toggle-btn d-lg-none" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="h3 mb-0 ms-3 d-none d-sm-block text-gray-800">Overview</h1>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-person-circle text-primary"></i>
                                    </div>
                                    <span>{{ currentUser()->first_name ?? currentUser()->name ?? 'User' }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdown">
                                    <li>
                                        <h6 class="dropdown-header">
                                            <small class="text-muted">{{ currentUser()->email }}</small>
                                        </h6>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('user_account') }}"><i class="bi bi-person me-2"></i> Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('change_user_password') }}"><i class="bi bi-gear me-2"></i> Change Password</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        @php
                                            $navLogoutRoute = currentUser()->user_type === 'superadmin' ? 'superadmin_logout' : 'logout';
                                        @endphp
                                        <a class="dropdown-item text-danger" href="{{ route($navLogoutRoute) }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </a>
                                        <form id="logout-form" action="{{ route($navLogoutRoute) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            @endunless

            @yield('content')

             </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Load jQuery + Bootstrap before page footer scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    @yield('footer')

    <script>
        // Toggle the sidebar when the button is clicked
        const sidebarToggle = document.getElementById('sidebarToggle');
        const wrapper = document.getElementById('wrapper');
        
        // Function to set the initial state based on screen size
        function checkWidth() {
            if (window.innerWidth < 992) {
                // On mobile, start hidden
                wrapper.classList.remove('toggled');
            } else {
                // On desktop, start visible
                wrapper.classList.add('toggled');
            }
        }

        // Event listener for the toggle button
        sidebarToggle.addEventListener('click', (e) => {
            e.preventDefault();
            wrapper.classList.toggle('toggled');
        });
        
        // Optional: Run check on load and resize
        window.addEventListener('load', checkWidth);
        window.addEventListener('resize', checkWidth);

    </script>

</body>
</html>
