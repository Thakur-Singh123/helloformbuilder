@extends('employee.include.layout')

@section('content')
 
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f7f9fc; font-family: Arial, Helvetica, sans-serif; color: #172554; }

        /* App Sidebar */
        .app-sidebar { position: fixed; left: 0; top: 0; bottom: 0; width: 250px; background: #1d2939; color: #fff; z-index: 1000; overflow-y: auto; }
        .sidebar-brand { height: 85px; padding: 0 25px; display: flex; align-items: center; border-bottom: 1px solid rgba(255,255,255,.08); }
        .sidebar-brand-icon { font-size: 23px; margin-right: 8px; }
        .sidebar-brand-text { font-size: 20px; font-weight: 700; }
        .sidebar-menu { padding: 20px 12px; }
        .sidebar-menu a { display: flex; align-items: center; gap: 14px; padding: 13px 16px; margin-bottom: 5px; color: #c4ccd8; text-decoration: none; border-radius: 9px; font-size: 14px; font-weight: 500; transition: .2s ease; }
        .sidebar-menu a i { width: 20px; font-size: 17px; }
        .sidebar-menu a:hover { background: rgba(255,255,255,.06); color: #fff; }
        .sidebar-menu a.active { background: #6352f5; color: #fff; box-shadow: 0 5px 15px rgba(99,82,245,.25); }
        .sidebar-divider { height: 1px; background: rgba(255,255,255,.08); margin: 15px 5px; }
        .logout-link { color: #ff5757 !important; }

        /* Main Area */
        .main-content { padding: 28px 38px; }
        .top-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
        .welcome-title { font-size: 28px; font-weight: 700; margin: 0; color: #10204d; }
        .welcome-subtitle { margin: 5px 0 0; color: #7180a0; font-size: 14px; }
        .header-right { display: flex; align-items: center; gap: 20px; }
        .search-box { width: 230px; }
        .search-box input { height: 42px; border: 1px solid #e2e7f0; border-radius: 25px; padding: 0 18px; font-size: 13px; background: #fff; }
        .search-box input:focus { box-shadow: 0 0 0 3px rgba(99,82,245,.08); border-color: #6352f5; }
        .date-info { color: #687793; font-size: 13px; white-space: nowrap; }

        /* Common Card */
        .dashboard-card { background: #fff; border: 1px solid #e6eaf1; border-radius: 13px; box-shadow: 0 2px 8px rgba(30,45,70,.03); }
        .stat-card { padding: 17px; height: 100%; }
        .stat-icon { width: 43px; height: 43px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #eff2ff; color: #172554; margin-right: 13px; }
        .stat-label { color: #657493; font-size: 12px; margin-bottom: 2px; }
        .stat-value { font-size: 25px; font-weight: 700; color: #142451; }
        .stat-description { font-size: 11px; color: #7886a3; margin-left: 5px; }

        /* Card Header */
        .card-header-custom { padding: 17px; display: flex; justify-content: space-between; align-items: center; }
        .card-title-custom { margin: 0; font-size: 15px; font-weight: 700; color: #142451; }
        .view-link { color: #6254ed; font-size: 12px; font-weight: 600; text-decoration: none; }
        .view-link:hover { color: #4939dc; }

        /* Attendance Table */
        .attendance-table { margin: 0; }
        .attendance-table thead th { background: #f8f9fc; border: none; color: #60708f; font-size: 11px; font-weight: 600; padding: 11px 15px; }
        .attendance-table tbody td { border-top: 1px solid #edf0f5; padding: 12px 15px; color: #304266; font-size: 12px; vertical-align: middle; }
        .member-number { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; background: #eef1ff; color: #5961e8; font-size: 11px; font-weight: 600; margin-right: 8px; }
        .status { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 20px; font-size: 10px; font-weight: 600; }
        .status-working { color: #159447; background: #eaf9f0; }
        .status-lunch { color: #dc9200; background: #fff5df; }
        .status-absent { color: #ed4d4d; background: #fff0f0; }
        .btn-view { background: #fff; border: 1px solid #c9c4ff; color: #6254ed; padding: 5px 14px; border-radius: 5px; font-size: 11px; }

        /* My Attendance */
        .my-attendance { padding: 0 18px 18px; }
        .working-badge { background: #eaf9f0; color: #159447; padding: 5px 10px; border-radius: 20px; font-size: 10px; font-weight: 600; }
        .check-time { text-align: center; padding: 28px 0 20px; border-bottom: 1px solid #e7ebf4; }
        .check-time strong { display: block; font-size: 25px; color: #101f4b; }
        .check-time span { font-size: 11px; color: #8290aa; }
        .working-time { padding: 17px 0; }
        .working-time small { display: block; color: #71809e; font-size: 11px; }
        .working-time strong { font-size: 16px; }
        .attendance-actions { display: flex; gap: 8px; }
        .attendance-actions button { flex: 1; height: 52px; border: 0; border-radius: 7px; color: #fff; font-size: 10px; font-weight: 700; }
        .btn-stop { background: #f04444; }
        .btn-lunch { background: #f39a0a; }
        .btn-checkout { background: #14a052; }
        .updated-time { text-align: center; color: #8591a8; font-size: 10px; margin-top: 10px; }

        /* Responsive */
        @media (max-width: 992px) {
            .app-sidebar { width: 220px; }
            .app-main { margin-left: 220px; }
            .main-content { padding: 25px 20px; }
            .header-right { display: none; }
        }
        @media (max-width: 768px) {
            .app-sidebar { position: relative; width: 100%; height: auto; }
            .sidebar-menu { display: flex; overflow-x: auto; }
            .sidebar-menu a { white-space: nowrap; }
            .app-main { margin-left: 0; }
            .main-content { padding: 20px 15px; }
            .welcome-title { font-size: 23px; }
        }
    </style>

 <main class="app-main">
    <div class="main-content">
        <div class="row g-3">
            
            <!-- Today's Attendance Table -->
            <div class="col-lg-8">
                <div class="dashboard-card">
                    <div class="card-header-custom">
                        <h5 class="card-title-custom">Today's Attendance</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table attendance-table">
                            <thead>
                                <tr>
                                    <th>Team Member</th>
                                    <th>Check In</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teamMembers as $index => $member)
                                    @php 
                                        $memberAtt = $member->attendances->first(); 
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="member-number">{{ sprintf("%02d", $index + 1) }}</span>
                                            {{ $member->name ?? $member->first_name . ' ' . $member->last_name }}
                                        </td>
                                        <td>
                                            {{ $memberAtt && $memberAtt->check_in ? $memberAtt->check_in->format('h:i A') : '—' }}
                                        </td>
                                        <td>
                                            @if(!$memberAtt || $memberAtt->status == 'not_started')
                                                <span class="status status-absent">● Not Started</span>
                                            @elseif($memberAtt->status == 'working')
                                                <span class="status status-working">● Working</span>
                                            @elseif($memberAtt->status == 'on_lunch')
                                                <span class="status status-lunch">● On Lunch</span>
                                            @elseif($memberAtt->status == 'completed')
                                                <span class="status" style="color: blue; background: #e0f0ff;">● Checked Out</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn-view" disabled style="opacity: 0.5;">View</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- My Attendance Panel (Upwork Style Control) -->
            <div class="col-lg-4">
                <div class="dashboard-card h-100">
                    <div class="card-header-custom">
                        <h5 class="card-title-custom">My Attendance</h5>
                        
                        @if($attendance->status == 'working')
                            <span class="working-badge">● Working</span>
                        @elseif($attendance->status == 'on_lunch')
                            <span class="working-badge" style="background:#fff5df; color:#dc9200;">● On Lunch</span>
                        @elseif($attendance->status == 'completed')
                            <span class="working-badge" style="background:#e0f0ff; color:blue;">● Checked Out</span>
                        @else
                            <span class="working-badge" style="background:#fff0f0; color:#ed4d4d;">● Not Started</span>
                        @endif

                    </div>

                    <div class="my-attendance">
                        <div class="check-time">
                            <strong>{{ $attendance->check_in ? $attendance->check_in->format('h:i A') : '--:--' }}</strong>
                            <span>Check In Time</span>
                        </div>

                        <div class="working-time">
                            <small>Total Working Time (Lunch Excluded)</small>
                            <strong>{{ $calculatedTime }}</strong>
                        </div>

                        <!-- FORMS FOR BUTTONS -->
                        <div class="attendance-actions" style="margin-top: 15px;">
                            
                            @if($attendance->status == 'not_started')
                                <!-- Start Button -->
                                <form action="{{ route('employee.attendance.action') }}" method="POST" style="width: 100%;">
                                    @csrf
                                    <input type="hidden" name="action" value="start">
                                    <button type="submit" style="width: 100%; height: 52px; border:0; border-radius:7px; color:#fff; background:#14a052; font-weight:bold; cursor:pointer;">
                                        ▶ START WORK
                                    </button>
                                </form>

                            @elseif($attendance->status == 'working')
                                <!-- Lunch and Check-Out Buttons -->
                                <form action="{{ route('employee.attendance.action') }}" method="POST" style="flex:1;">
                                    @csrf
                                    <input type="hidden" name="action" value="lunch_start">
                                    <button type="submit" style="width:100%; height:52px; border:0; border-radius:7px; color:#fff; background:#f39a0a; font-weight:bold; cursor:pointer;">
                                        ☕ LUNCH START
                                    </button>
                                </form>

                                <form action="{{ route('employee.attendance.action') }}" method="POST" style="flex:1;">
                                    @csrf
                                    <input type="hidden" name="action" value="checkout">
                                    <button type="submit" onclick="return confirm('Are you sure you want to Check Out for the day?')" style="width:100%; height:52px; border:0; border-radius:7px; color:#fff; background:#f04444; font-weight:bold; cursor:pointer;">
                                        ■ CHECK OUT
                                    </button>
                                </form>

                            @elseif($attendance->status == 'on_lunch')
                                <!-- End Lunch Button -->
                                <form action="{{ route('employee.attendance.action') }}" method="POST" style="width: 100%;">
                                    @csrf
                                    <input type="hidden" name="action" value="lunch_end">
                                    <button type="submit" style="width: 100%; height: 52px; border:0; border-radius:7px; color:#fff; background:#6352f5; font-weight:bold; cursor:pointer;">
                                        ▶ END LUNCH & RESUME
                                    </button>
                                </form>
                                
                            @elseif($attendance->status == 'completed')
                                <!-- Disabled Button After Checkout -->
                                <button disabled style="width: 100%; height: 52px; border:0; border-radius:7px; color:#fff; background:#a3a3a3; font-weight:bold; cursor:not-allowed;">
                                    SHIFT COMPLETED FOR TODAY
                                </button>
                            @endif

                        </div>

                        <div class="updated-time">
                            Last updated: {{ now()->format('h:i A') }} ↻
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection