@extends('employee.include.layout')
@section('content')
 
    <style>
        body {
            background: #f8faff;
            font-family: system-ui, -apple-system, BlinkMacSystemFont,
                         "Segoe UI", Roboto, Arial, sans-serif;
            color: #172554;
        }

        .dashboard {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px 50px;
        }

        /* Header */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .welcome-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #10204d;
        }

        .welcome-text {
            color: #7180a0;
            margin: 0;
            font-size: 14px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .search-box {
            width: 230px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            height: 42px;
            border: 1px solid #e4e8f2;
            border-radius: 24px;
            padding: 0 18px;
            font-size: 14px;
            outline: none;
        }

        .search-box input:focus {
            border-color: #6755f5;
            box-shadow: 0 0 0 3px rgba(103, 85, 245, .08);
        }

        .date-text {
            color: #657493;
            font-size: 13px;
            white-space: nowrap;
        }

        /* Stat Cards */
        .stat-card {
            background: #fff;
            border: 1px solid #e8ebf4;
            border-radius: 13px;
            padding: 17px;
            height: 100%;
            box-shadow: 0 2px 8px rgba(31, 45, 61, .03);
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #f0f3ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 13px;
            flex-shrink: 0;
        }

        .stat-icon i {
            font-size: 20px;
        }

        .stat-label {
            color: #697796;
            font-size: 12px;
            margin-bottom: 2px;
        }

        .stat-value {
            font-size: 25px;
            font-weight: 700;
            color: #142451;
            line-height: 1.1;
        }

        .stat-small {
            font-size: 11px;
            color: #7886a3;
            margin-left: 4px;
        }

        /* Common Card */
        .dashboard-card {
            background: #fff;
            border: 1px solid #e7ebf4;
            border-radius: 13px;
            box-shadow: 0 2px 8px rgba(31, 45, 61, .03);
        }

        .card-heading {
            padding: 17px 17px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-heading h5 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #142451;
        }

        .view-link {
            font-size: 12px;
            font-weight: 600;
            color: #6553f5;
            text-decoration: none;
        }

        /* Attendance */
        .attendance-table {
            width: 100%;
        }

        .attendance-table th {
            background: #f8f9fd;
            color: #60708f;
            font-size: 11px;
            font-weight: 600;
            padding: 11px 12px;
            border: none;
        }

        .attendance-table td {
            padding: 12px;
            font-size: 12px;
            color: #304266;
            border-top: 1px solid #edf0f6;
            vertical-align: middle;
        }

        .member-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #edf1ff;
            color: #5161dd;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 8px;
            font-size: 11px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
        }

        .status-badge::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .status-working {
            background: #eaf9f0;
            color: #159447;
        }

        .status-lunch {
            background: #fff5df;
            color: #dc9200;
        }

        .status-absent {
            background: #fff0f0;
            color: #ed4d4d;
        }

        .btn-view {
            border: 1px solid #c9c4ff;
            color: #6254ed;
            background: white;
            border-radius: 5px;
            padding: 5px 14px;
            font-size: 11px;
        }

        .btn-view:hover {
            background: #f5f3ff;
            color: #5545dc;
        }

        /* My Attendance */
        .my-attendance {
            padding: 18px;
        }

        .working-label {
            background: #eaf9f0;
            color: #159447;
            border-radius: 20px;
            padding: 5px 10px;
            font-size: 10px;
            font-weight: 600;
        }

        .check-time {
            text-align: center;
            padding: 25px 0 18px;
            border-bottom: 1px solid #e7ebf4;
        }

        .check-time strong {
            font-size: 24px;
            color: #101f4b;
        }

        .check-time span {
            display: block;
            font-size: 11px;
            color: #8290aa;
            margin-top: 3px;
        }

        .working-time {
            padding: 17px 0;
        }

        .working-time span {
            display: block;
            font-size: 12px;
            color: #71809e;
        }

        .working-time strong {
            font-size: 16px;
            color: #152650;
        }

        .attendance-actions {
            display: flex;
            gap: 8px;
        }

        .attendance-actions button {
            flex: 1;
            border: none;
            border-radius: 7px;
            height: 52px;
            font-size: 10px;
            font-weight: 700;
            color: #fff;
        }

        .btn-stop {
            background: #f04444;
        }

        .btn-lunch {
            background: #f39a0a;
        }

        .btn-checkout {
            background: #14a052;
        }

        .updated-time {
            text-align: center;
            color: #8591a8;
            font-size: 10px;
            margin-top: 10px;
        }

        /* Task Overview */
        .task-board {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 9px;
            padding: 0 17px 17px;
        }

        .task-column {
            background: #fafbfe;
            border: 1px solid #e7ebf4;
            border-radius: 8px;
            padding: 9px;
            min-height: 210px;
        }

        .task-column-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 9px;
        }

        .task-column-title {
            font-size: 11px;
            font-weight: 700;
            color: #172750;
        }

        .task-count {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid #e2e6ef;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 9px;
            color: #657493;
        }

        .task-item {
            background: #fff;
            border: 1px solid #e5e9f2;
            border-radius: 7px;
            padding: 10px;
            margin-bottom: 8px;
        }

        .task-title {
            font-size: 11px;
            font-weight: 700;
            color: #19305b;
            line-height: 1.4;
        }

        .task-meta {
            font-size: 9px;
            margin-top: 9px;
        }

        .priority-high {
            color: #ed4b4b;
        }

        .priority-medium {
            color: #e79a00;
        }

        .priority-low {
            color: #12a052;
        }

        .add-task {
            color: #6254ed;
            font-size: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-top: 3px;
        }

        /* Leaves */
        .leave-item {
            padding: 13px 17px;
            border-top: 1px solid #edf0f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .leave-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .leave-number {
            width: 31px;
            height: 31px;
            border-radius: 50%;
            background: #eef1ff;
            color: #5961e8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
        }

        .leave-name {
            font-size: 11px;
            font-weight: 700;
            color: #23365e;
        }

        .leave-detail {
            color: #71809b;
            font-size: 9px;
            margin-top: 2px;
        }

        .leave-buttons button {
            width: 31px;
            height: 31px;
            background: white;
            border-radius: 5px;
            margin-left: 5px;
        }

        .approve-btn {
            border: 1px solid #7bd8a1;
            color: #18a153;
        }

        .reject-btn {
            border: 1px solid #ff9a9a;
            color: #ed4d4d;
        }

        /* Announcements */
        .announcement {
            margin: 0 17px 17px;
            border: 1px solid #e4e8f1;
            border-radius: 8px;
            padding: 14px;
        }

        .announcement-icon {
            font-size: 22px;
            margin-right: 10px;
        }

        .announcement-title {
            font-size: 11px;
            font-weight: 700;
            color: #26385c;
        }

        .announcement-text {
            color: #74819c;
            font-size: 10px;
            line-height: 1.5;
            margin-top: 5px;
        }

        .announcement-date {
            color: #71809a;
            font-size: 9px;
            margin-top: 8px;
        }

        /* Team Members */
        .team-members {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 9px;
            padding: 0 17px 17px;
        }

        .team-member {
            border: 1px solid #e5e9f1;
            border-radius: 8px;
            padding: 11px;
        }

        .team-member-top {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .team-avatar {
            width: 31px;
            height: 31px;
            border-radius: 50%;
            background: #eef1ff;
            color: #5961e8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
        }

        .team-name {
            font-size: 10px;
            font-weight: 700;
            color: #25365b;
        }

        .team-role {
            font-size: 8px;
            color: #7b87a0;
            margin-top: 2px;
        }

        .online-status {
            font-size: 8px;
            margin-top: 10px;
        }

        .online {
            color: #13a052;
        }

        .lunch {
            color: #e89600;
        }

        .absent {
            color: #ed4d4d;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .dashboard-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .header-right {
                width: 100%;
            }

            .search-box {
                width: 100%;
                max-width: 300px;
            }

            .task-board,
            .team-members {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .dashboard {
                padding: 20px 12px;
            }

            .task-board,
            .team-members {
                grid-template-columns: repeat(2, 1fr);
            }

            .date-text {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .task-board,
            .team-members {
                grid-template-columns: 1fr;
            }

            .attendance-table {
                min-width: 650px;
            }

            .dashboard-header {
                margin-bottom: 18px;
            }

            .welcome-title {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

<div class="dashboard">

    <!-- Header -->
    <div class="dashboard-header">

        <div>
            <h1 class="welcome-title">
                Good Morning! 👋
            </h1>

            <p class="welcome-text">
                Here's what's happening with your team today.
            </p>
        </div>

        <div class="header-right">

            <div class="search-box">
                <input type="text" placeholder="Search anything...">
            </div>

            <div class="date-text">
                <i class="bi bi-calendar3"></i>
                18 August 2026, Tuesday
            </div>

        </div>

    </div>


    <!-- Statistics -->
    <!-- <div class="row g-3 mb-4">

        <div class="col-xl col-md-4 col-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">

                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div>
                        <div class="stat-label">Team Members</div>
                        <span class="stat-value">18</span>
                        <span class="stat-small">Total Members</span>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl col-md-4 col-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">

                    <div class="stat-icon">
                        <i class="bi bi-circle-fill text-success"></i>
                    </div>

                    <div>
                        <div class="stat-label">Present Today</div>
                        <span class="stat-value">15</span>
                        <span class="stat-small">On Time</span>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl col-md-4 col-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">

                    <div class="stat-icon">
                        <i class="bi bi-umbrella"></i>
                    </div>

                    <div>
                        <div class="stat-label">On Leave</div>
                        <span class="stat-value">2</span>
                        <span class="stat-small">Today</span>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl col-md-4 col-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">

                    <div class="stat-icon">
                        <i class="bi bi-square-fill"></i>
                    </div>

                    <div>
                        <div class="stat-label">Active Tasks</div>
                        <span class="stat-value">24</span>
                        <span class="stat-small">In Progress</span>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl col-md-4 col-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">

                    <div class="stat-icon">
                        <i class="bi bi-check-lg"></i>
                    </div>

                    <div>
                        <div class="stat-label">Completed Tasks</div>
                        <span class="stat-value">32</span>
                        <span class="stat-small">This Month</span>
                    </div>

                </div>
            </div>
        </div>

    </div> -->


    <!-- Attendance + My Attendance -->
    <div class="row g-3 mb-4">

        <!-- Today's Attendance -->
        <div class="col-lg-8">

            <div class="dashboard-card">

                <div class="card-heading">
                    <h5>Today's Attendance</h5>
                    <a href="#" class="view-link">View All</a>
                </div>

                <div class="table-responsive">

                    <table class="attendance-table">

                        <thead>
                            <tr>
                                <th>Team Member</th>
                                <th>Check In</th>
                                <th>Status</th>
                                <th>Working Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>
                                <td>
                                    <span class="member-number">01</span>
                                    Team Member 01
                                </td>
                                <td>09:02 AM</td>
                                <td>
                                    <span class="status-badge status-working">
                                        Working
                                    </span>
                                </td>
                                <td>04h 15m</td>
                                <td>
                                    <button class="btn-view">View</button>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="member-number">02</span>
                                    Team Member 02
                                </td>
                                <td>09:15 AM</td>
                                <td>
                                    <span class="status-badge status-lunch">
                                        Lunch
                                    </span>
                                </td>
                                <td>03h 10m</td>
                                <td>
                                    <button class="btn-view">View</button>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="member-number">03</span>
                                    Team Member 03
                                </td>
                                <td>08:55 AM</td>
                                <td>
                                    <span class="status-badge status-working">
                                        Working
                                    </span>
                                </td>
                                <td>04h 22m</td>
                                <td>
                                    <button class="btn-view">View</button>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="member-number">04</span>
                                    Team Member 04
                                </td>
                                <td>—</td>
                                <td>
                                    <span class="status-badge status-absent">
                                        Absent
                                    </span>
                                </td>
                                <td>—</td>
                                <td>
                                    <button class="btn-view">View</button>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="member-number">05</span>
                                    Team Member 05
                                </td>
                                <td>09:10 AM</td>
                                <td>
                                    <span class="status-badge status-working">
                                        Working
                                    </span>
                                </td>
                                <td>03h 40m</td>
                                <td>
                                    <button class="btn-view">View</button>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <!-- My Attendance -->
        <div class="col-lg-4">

            <div class="dashboard-card h-100">

                <div class="card-heading">

                    <h5>My Attendance</h5>

                    <span class="working-label">
                        ● Working
                    </span>

                </div>

                <div class="my-attendance">

                    <div class="check-time">
                        <strong>09:05 AM</strong>
                        <span>Check In Time</span>
                    </div>

                    <div class="working-time">
                        <span>Working Time</span>
                        <strong>04h 32m</strong>
                    </div>

                    <div class="attendance-actions">

                        <button class="btn-stop">
                            ■ STOP
                        </button>

                        <button class="btn-lunch">
                            ☕ LUNCH<br>START
                        </button>

                        <button class="btn-checkout">
                            ↗ CHECK<br>OUT
                        </button>

                    </div>

                    <div class="updated-time">
                        Last updated: 10:32 AM ↻
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Bottom Section -->
    <div class="row g-3">

        <!-- Left -->
        <div class="col-lg-8">

             <div class="dashboard-card mb-3">

                <!-- <div class="card-heading">
                    <h5>Task Overview</h5>
                    <a href="#" class="view-link">View All Tasks</a>
                </div> -->

                <!-- <div class="task-board">

                     <div class="task-column">

                        <div class="task-column-header">
                            <span class="task-column-title">Unassigned</span>
                            <span class="task-count">4</span>
                        </div>

                        <div class="task-item">
                            <div class="task-title">
                                Website Redesign
                            </div>

                            <div class="task-meta priority-high">
                                ● High&nbsp; 20 Aug 2026
                            </div>
                        </div>

                        <div class="task-item">
                            <div class="task-title">
                                SEO Audit
                            </div>

                            <div class="task-meta priority-medium">
                                ● Medium&nbsp; 22 Aug 2026
                            </div>
                        </div>

                        <a href="#" class="add-task">
                            + Add Task
                        </a>

                    </div>


                     <div class="task-column">

                        <div class="task-column-header">
                            <span class="task-column-title">To Do</span>
                            <span class="task-count">6</span>
                        </div>

                        <div class="task-item">
                            <div class="task-title">
                                Content Writing
                            </div>

                            <div class="task-meta priority-medium">
                                ● Medium&nbsp; 22 Aug 2026
                            </div>
                        </div>

                        <div class="task-item">
                            <div class="task-title">
                                Social Media Post Design
                            </div>

                            <div class="task-meta priority-low">
                                ● Low&nbsp; 25 Aug 2026
                            </div>
                        </div>

                        <a href="#" class="add-task">
                            + Add Task
                        </a>

                    </div>


                     <div class="task-column">

                        <div class="task-column-header">
                            <span class="task-column-title">In Progress</span>
                            <span class="task-count">5</span>
                        </div>

                        <div class="task-item">
                            <div class="task-title">
                                Landing Page Development
                            </div>

                            <div class="task-meta priority-high">
                                ● High&nbsp; 18 Aug 2026
                            </div>
                        </div>

                        <div class="task-item">
                            <div class="task-title">
                                Google Ads Campaign
                            </div>

                            <div class="task-meta priority-medium">
                                ● Medium&nbsp; 21 Aug 2026
                            </div>
                        </div>

                        <a href="#" class="add-task">
                            + Add Task
                        </a>

                    </div>


                     <div class="task-column">

                        <div class="task-column-header">
                            <span class="task-column-title">Review</span>
                            <span class="task-count">3</span>
                        </div>

                        <div class="task-item">
                            <div class="task-title">
                                Logo Design
                            </div>

                            <div class="task-meta priority-medium">
                                ● Medium&nbsp; 20 Aug 2026
                            </div>
                        </div>

                        <div class="task-item">
                            <div class="task-title">
                                UI/UX Design
                            </div>

                            <div class="task-meta priority-high">
                                ● High&nbsp; 22 Aug 2026
                            </div>
                        </div>

                        <a href="#" class="add-task">
                            + Add Task
                        </a>

                    </div>


                     <div class="task-column">

                        <div class="task-column-header">
                            <span class="task-column-title">Completed</span>
                            <span class="task-count">8</span>
                        </div>

                        <div class="task-item">
                            <div class="task-title">
                                Bug Fixing
                            </div>

                            <div class="task-meta priority-low">
                                ● Low&nbsp; 15 Aug 2026
                            </div>
                        </div>

                        <div class="task-item">
                            <div class="task-title">
                                Database Update
                            </div>

                            <div class="task-meta priority-low">
                                ● Low&nbsp; 14 Aug 2026
                            </div>
                        </div>

                        <a href="#" class="add-task">
                            + Add Task
                        </a>

                    </div>

                </div> -->

            </div>


            <!-- Team Members -->
            <!-- <div class="dashboard-card">

                <div class="card-heading">
                    <h5>Team Members</h5>
                    <a href="#" class="view-link">View All</a>
                </div>

                <div class="team-members">

                    <div class="team-member">

                        <div class="team-member-top">
                            <div class="team-avatar">01</div>

                            <div>
                                <div class="team-name">
                                    Team Member 01
                                </div>
                                <div class="team-role">
                                    Developer
                                </div>
                            </div>
                        </div>

                        <div class="online-status online">
                            ● Online
                        </div>

                    </div>


                    <div class="team-member">

                        <div class="team-member-top">
                            <div class="team-avatar">02</div>

                            <div>
                                <div class="team-name">
                                    Team Member 02
                                </div>
                                <div class="team-role">
                                    SEO Executive
                                </div>
                            </div>
                        </div>

                        <div class="online-status online">
                            ● Online
                        </div>

                    </div>


                    <div class="team-member">

                        <div class="team-member-top">
                            <div class="team-avatar">03</div>

                            <div>
                                <div class="team-name">
                                    Team Member 03
                                </div>
                                <div class="team-role">
                                    Designer
                                </div>
                            </div>
                        </div>

                        <div class="online-status online">
                            ● Online
                        </div>

                    </div>


                    <div class="team-member">

                        <div class="team-member-top">
                            <div class="team-avatar">04</div>

                            <div>
                                <div class="team-name">
                                    Team Member 04
                                </div>
                                <div class="team-role">
                                    Content Writer
                                </div>
                            </div>
                        </div>

                        <div class="online-status lunch">
                            ● Lunch
                        </div>

                    </div>


                    <div class="team-member">

                        <div class="team-member-top">
                            <div class="team-avatar">05</div>

                            <div>
                                <div class="team-name">
                                    Team Member 05
                                </div>
                                <div class="team-role">
                                    Marketing
                                </div>
                            </div>
                        </div>

                        <div class="online-status absent">
                            ● Absent
                        </div>

                    </div>

                </div>

            </div> -->

        </div>


        <!-- Right -->
        <div class="col-lg-4">

            <!-- Pending Leave Requests -->
            <div class="dashboard-card mb-3">

                <div class="card-heading">
                    <h5>Pending Leave Requests</h5>
                    <a href="#" class="view-link">View All</a>
                </div>

                <div class="leave-item">

                    <div class="leave-left">

                        <div class="leave-number">06</div>

                        <div>
                            <div class="leave-name">
                                Team Member 06
                            </div>

                            <div class="leave-detail">
                                18 Aug - 19 Aug (2 Days)
                            </div>

                            <div class="leave-detail">
                                Personal Leave
                            </div>
                        </div>

                    </div>

                    <div class="leave-buttons">
                        <button class="approve-btn">
                            ✓
                        </button>

                        <button class="reject-btn">
                            ×
                        </button>
                    </div>

                </div>


                <div class="leave-item">

                    <div class="leave-left">

                        <div class="leave-number">07</div>

                        <div>
                            <div class="leave-name">
                                Team Member 07
                            </div>

                            <div class="leave-detail">
                                21 Aug - 23 Aug (3 Days)
                            </div>

                            <div class="leave-detail">
                                Sick Leave
                            </div>
                        </div>

                    </div>

                    <div class="leave-buttons">
                        <button class="approve-btn">
                            ✓
                        </button>

                        <button class="reject-btn">
                            ×
                        </button>
                    </div>

                </div>

            </div>


            <!-- Announcements -->
            <div class="dashboard-card">

                <div class="card-heading">
                    <h5>Announcements</h5>
                    <a href="#" class="view-link">View All</a>
                </div>

                <div class="announcement">

                    <div class="d-flex">

                        <div class="announcement-icon">
                            📣
                        </div>

                        <div>

                            <div class="announcement-title">
                                Team Meeting
                            </div>

                            <div class="announcement-text">
                                Team meeting scheduled on 20 August
                                2026 at 11:00 AM in Conference Room.
                            </div>

                            <div class="announcement-date">
                                17 Aug 2026
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

 
@endsection