@extends('user.include.layout')

@section('content')
<!-- SweetAlert2 CSS & JS (Delete popup ke liye) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .tasks-container {
        max-width: 1200px;
        margin: 0 auto;
        font-family: system-ui, -apple-system, sans-serif;
    }
    .task-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .task-table-wrap {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .task-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: .75rem;
        text-transform: uppercase;
        font-weight: 700;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .task-table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: .9rem;
        color: #334155;
    }
    .task-table tr:last-child td {
        border-bottom: none;
    }
</style>

<div class="tasks-container mt-3">
    
    <!-- Header -->
    <div class="task-header">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1e293b;">
                <i class="bi bi-list-task text-primary me-2"></i> Tasks for {{ $employee->name }}
            </h4>
            <p class="text-muted small mb-0">Manage all assigned tasks for this employee.</p>
        </div>
        <div class="d-flex gap-2">
            <!-- Back Button -->
            <a href="{{ route('admin.teams.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Teams
            </a>
            <!-- Assign New Task Button -->
            <a href="{{ route('tasks.create', $employee->id) }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Assign New Task
            </a>
        </div>
    </div>

    <!-- Task Table -->
    <div class="task-table-wrap shadow-sm">
        <div class="table-responsive">
            <table class="task-table table mb-0 table-hover">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">#</th> <!-- New Serial Number Column -->
                        <th width="20%">Task Title</th>
                        <th width="35%">Description</th>
                        <th width="15%" class="text-center">Status</th>
                        <th width="15%">Due Date</th>
                        <th width="10%" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <!-- Serial Number Logic with Pagination -->
                            <td class="text-center fw-bold text-muted">
                                {{ ($tasks->currentPage() - 1) * $tasks->perPage() + $loop->iteration }}
                            </td>
                            
                            <td class="fw-semibold text-dark">
                                {{ $task->title }}
                            </td>
                            <td class="text-muted small">
                                {{ $task->description ? \Illuminate\Support\Str::limit($task->description, 60) : '—' }}
                            </td>
                            <td class="text-center">
                                @if($task->status == 'pending')
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="bi bi-clock me-1"></i> Pending</span>
                                @elseif($task->status == 'in_progress')
                                    <span class="badge bg-info text-dark px-3 py-2 rounded-pill"><i class="bi bi-arrow-repeat me-1"></i> In Progress</span>
                                @elseif($task->status == 'completed')
                                    <span class="badge bg-success px-3 py-2 rounded-pill"><i class="bi bi-check2-circle me-1"></i> Completed</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ ucfirst($task->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($task->due_date)
                                    @php
                                        $dueDate = \Carbon\Carbon::parse($task->due_date);
                                        $isPast = $dueDate->isPast() && $task->status !== 'completed';
                                    @endphp
                                    <span class="fw-medium {{ $isPast ? 'text-danger' : 'text-dark' }}">
                                        {{ $dueDate->format('d M, Y') }}
                                    </span>
                                    @if($isPast)
                                        <div class="small text-danger fw-bold" style="font-size: 0.7rem;">OVERDUE</div>
                                    @endif
                                @else
                                    <span class="text-muted small">No Date</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <!-- Edit Button -->
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Task">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    
                                    <!-- Delete Button (Handled by SweetAlert below) -->
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-task-btn" title="Delete Task">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <!-- Colspan 5 se 6 kar diya gaya hai kyunki ek column add hua hai -->
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted mb-3">
                                    <i class="bi bi-clipboard-x" style="font-size: 2.5rem; color: #cbd5e1;"></i>
                                </div>
                                <h6 class="text-dark fw-bold">No tasks found</h6>
                                <p class="small text-muted mb-3">This employee has no tasks assigned yet.</p>
                                <a href="{{ route('tasks.create', $employee->id) }}" class="btn btn-sm btn-primary">
                                    Assign First Task
                                </a>
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

<!-- SweetAlert Delete Confirmation Script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButtons = document.querySelectorAll('.delete-task-btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this task deletion!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626', // Red color
                    cancelButtonColor: '#64748b',  // Grey color
                    confirmButtonText: '<i class="bi bi-trash"></i> Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection