@extends('user.include.layout')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm" style="max-width: 600px; margin: 0 auto;">
        
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Task</h5>
            
            {{-- Delete Task Button --}}
            <form action="#" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i> Delete Task
                </button>
            </form>
        </div>

        <div class="card-body">
            <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                @csrf 
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Task Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ $task->title }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ $task->description }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in-progress" {{ $task->status == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '' }}">
                    </div>
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('admin.teams.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection