@extends('employee.include.layout')

@section('content')
<style>
    .task-container {
        max-width: 1200px;
        margin: 0 auto;
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    .task-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 700;
        padding: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }
    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        color: #334155;
    }
    .badge-pending { background-color: #fef9c3; color: #854d0e; padding: 5px 10px; border-radius: 6px; font-weight: 600; font-size: 0.8rem; }
    .badge-progress { background-color: #e0f2fe; color: #0284c7; padding: 5px 10px; border-radius: 6px; font-weight: 600; font-size: 0.8rem; }
    .badge-completed { background-color: #dcfce7; color: #166534; padding: 5px 10px; border-radius: 6px; font-weight: 600; font-size: 0.8rem; }
</style>

<div class="task-container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="bi bi-list-check text-primary me-2"></i>My Tasks</h4>
            <p class="text-muted mb-0 small">View all the tasks assigned to you.</p>
        </div>
    </div>

    <div class="task-card overflow-hidden">
        <div class="table-responsive">
            <table class="table mb-0 table-hover">
                <thead>
                    <tr>
                        <th width="30%">Task Title</th>
                        <th width="40%">Description</th>
                        <th width="15%">Due Date</th>
                        <th width="15%" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <td class="fw-semibold text-dark">
                                {{ $task->title }}
                            </td>
                            <td>
                                <span class="text-muted small">
                                    {{ $task->description ? \Illuminate\Support\Str::limit($task->description, 60) : 'No description provided.' }}
                                </span>
                            </td>
                            <td>
                                @if($task->due_date)
                                    @php
                                        $dueDate = \Carbon\Carbon::parse($task->due_date);
                                        $isOverdue = $dueDate->isPast() && $task->status !== 'completed';
                                    @endphp
                                    <span class="fw-medium {{ $isOverdue ? 'text-danger' : 'text-dark' }}">
                                        <i class="bi bi-calendar-event me-1"></i> {{ $dueDate->format('d M, Y') }}
                                    </span>
                                    @if($isOverdue)
                                        <div class="small text-danger mt-1 fw-bold">Overdue</div>
                                    @endif
                                @else
                                    <span class="text-muted small">No Date</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($task->status == 'pending')
                                    <span class="badge-pending"><i class="bi bi-clock me-1"></i> Pending</span>
                                @elseif($task->status == 'in_progress')
                                    <span class="badge-progress"><i class="bi bi-arrow-repeat me-1"></i> In Progress</span>
                                @elseif($task->status == 'completed')
                                    <span class="badge-completed"><i class="bi bi-check2-circle me-1"></i> Completed</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($task->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted mb-2">
                                    <i class="bi bi-clipboard-x" style="font-size: 2rem;"></i>
                                </div>
                                <h6>No tasks assigned yet!</h6>
                                <p class="small mb-0">You currently have no pending tasks.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($tasks->hasPages())
        <div class="mt-4 d-flex justify-content-end">
            {{ $tasks->links() }}
        </div>
    @endif
</div>
@endsection