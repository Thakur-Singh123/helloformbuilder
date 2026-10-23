@extends('user.include.layout')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header bg-white">
            <h5 class="mb-0">Assign Task to: <span class="text-primary">{{ $employee->name }}</span></h5>
        </div>
        <div class="card-body">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $employee->id }}">

                <div class="mb-3">
                    <label class="form-label">Task Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required placeholder="Enter task title">
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Enter task details..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" class="form-control">
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('admin.teams.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Assign Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection