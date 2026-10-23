 @extends('user.include.layout')

@section('content')

<style>
    .team-wrap {
        max-width: 1200px;
        margin: 0 auto;
        font-family: system-ui, -apple-system, "Segoe UI", Roboto,
            "Helvetica Neue", Arial, sans-serif;
    }

    .team-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .team-header h4 {
        margin: 0;
        font-weight: 700;
        color: #1e293b;
    }

    .stat-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: .75rem;
        margin-bottom: 1.25rem;
    }

    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1.1rem;
    }

    .stat-card.active-card {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59,130,246,.15);
    }

    .stat-card .label {
        font-size: .75rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .stat-card .value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        margin-top: .25rem;
    }

    .filter-bar {
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
        margin-bottom: 1.25rem;
    }

    .team-table-wrap {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .team-table {
        width: 100%;
        margin: 0;
    }

    .team-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        font-weight: 700;
        padding: .85rem 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .team-table tbody td {
        padding: .9rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: .9rem;
        color: #334155;
    }

    .team-table tbody tr:last-child td {
        border-bottom: none;
    }

    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: .85rem;
        flex-shrink: 0;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        padding: .35rem .75rem;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 700;
    }

    .badge-status.active {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-status.inactive {
        background: #f1f5f9;
        color: #475569;
    }

    /* New CSS for Tasks Visualization */
    .badge-task {
        background: #e0f2fe;
        color: #0284c7;
        padding: .25rem .6rem;
        border-radius: 6px;
        font-size: .75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 140px;
    }

    .badge-task-count {
        background: #f1f5f9;
        color: #475569;
        padding: .25rem .5rem;
        border-radius: 6px;
        font-size: .75rem;
        font-weight: 700;
    }
</style>


<div class="team-wrap">
    @include('admin.partials.notification')
    {{-- Header --}}
    <div class="team-header">
        <div>
            <h4>Team Management</h4>
            <p class="text-muted mb-0 small">
                Manage your team members, profiles, and system access.
            </p>
        </div>

        <a href="{{ route('admin.teams.create') }}"
           class="btn btn-primary shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i>
            Add Employee
        </a>
    </div>

    {{-- Statistics --}}
    <div class="stat-cards">
        <div class="stat-card active-card">
            <div class="label">All Employees</div>
            <div class="value">{{ $employees->count() }}</div>
        </div>

        <div class="stat-card">
            <div class="label">Active</div>
            <div class="value">
                {{ $employees->where('is_active', 1)->count() }}
            </div>
        </div>

        <div class="stat-card">
            <div class="label">Inactive</div>
            <div class="value">
                {{ $employees->where('is_active', 0)->count() }}
            </div>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET"
          action="{{ route('admin.teams.index') }}"
          class="filter-bar">

        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="form-control"
               style="max-width:300px"
               placeholder="Search name, email, or profile...">

        <button class="btn btn-primary" type="submit">
            <i class="bi bi-search me-1"></i>
            Search
        </button>

        @if(request('search'))
            <a href="{{ route('admin.teams.index') }}"
               class="btn btn-outline-secondary">
                Clear
            </a>
        @endif
    </form>

    {{-- Listing --}}
    <div class="team-table-wrap">
        <div class="table-responsive">
            <table class="team-table table mb-0">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Profile</th>
                        <th>Status</th>
                        <th>Assigned Tasks</th> <!-- NEW: Added Tasks Column -->
                        <th>Added On</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            {{-- Employee --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($employee->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold"
                                             style="color:#0f172a;">
                                            {{ $employee->name }}
                                        </div>
                                        <div class="text-muted small">
                                            {{ $employee->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Profile --}}
                            <td>
                                <span class="fw-medium">
                                    {{ $employee->profile ?? '—' }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($employee->is_active)
                                    <span class="badge-status active">
                                        Active
                                    </span>
                                @else
                                    <span class="badge-status inactive">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                             {{-- Tasks Visualization --}}
                                <td>
                                    @if(isset($employee->tasks) && $employee->tasks->count() > 0)

                                        <a href="{{ route('teams.tasks', $employee->id) }}"
                                        class="btn btn-sm btn-outline-info rounded-pill px-3">
                                            <i class="bi bi-list-task"></i>
                                            View Tasks ({{ $employee->tasks->count() }})
                                        </a>

                                    @else

                                        <span class="text-muted small">
                                            <i class="bi bi-info-circle"></i>
                                            No active tasks
                                        </span>

                                    @endif
                                </td>

                            {{-- Created --}}
                            <td class="text-muted small">
                                {{ $employee->created_at
                                    ? $employee->created_at->format('M d, Y')
                                    : '—'
                                }}
                            </td>

                            {{-- Actions --}}
                            <td class="text-end">
                                <div class="btn-group">
                                    
                                    {{-- NEW: ASSIGN TASK BUTTON --}}
                                    {{-- You can adjust the route name 'admin.tasks.create' to match your actual route setup --}}
                                    <a href="{{ route('tasks.create', $employee->id) }}"
                                        class="btn btn-sm btn-outline-success"
                                        title="Assign Task">
                                            <i class="bi bi-plus-circle"></i> Assign Task
                                    </a>

                                    {{-- EDIT --}}
                                    <a href="{{ route('admin.teams.edit', $employee->id) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Edit Employee">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>

                                    {{-- DELETE --}}
                                    <form method="POST"
                                          action="{{ route('admin.teams.destroy', $employee->id) }}"
                                          onsubmit="return confirm('Are you sure you want to remove this employee?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Remove Employee">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="text-center py-4 text-muted">
                                No employees found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($employees->hasPages())
        <div class="mt-4">
            {{ $employees->withQueryString()->links() }}
        </div>
    @endif
</div>

 

@endsection