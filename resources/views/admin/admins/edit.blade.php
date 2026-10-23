@extends('user.include.layout')
@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.admins.index') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left me-1"></i> All Admins</a>
        <h1 class="h3 mt-2 mb-0">Edit Admin</h1>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('alerts')
            <form method="POST" action="{{ route('admin.admins.update', $admin->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Company / Display Name *</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $admin->name) }}" required>
                    @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label">First Name</label><input type="text" class="form-control" name="first_name" value="{{ old('first_name', $admin->first_name) }}"></div>
                    <div class="col-md-6 mb-3"><label class="form-label">Last Name</label><input type="text" class="form-control" name="last_name" value="{{ old('last_name', $admin->last_name) }}"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email', $admin->email) }}" required>
                    @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password (leave blank to keep)</label>
                    <input type="password" class="form-control" name="password" minlength="6">
                    @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3"><label class="form-label">Confirm New Password</label><input type="password" class="form-control" name="password_confirmation" minlength="6"></div>
                <button type="submit" class="btn btn-primary">Update Admin</button>
                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
