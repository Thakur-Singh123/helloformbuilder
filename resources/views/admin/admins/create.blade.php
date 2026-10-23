@extends('user.include.layout')
@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.admins.index') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left me-1"></i> All Admins</a>
        <h1 class="h3 mt-2 mb-0">Create Admin (Company)</h1>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('alerts')
            <form method="POST" action="{{ route('admin.admins.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Company / Display Name *</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label">First Name</label><input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}"></div>
                    <div class="col-md-6 mb-3"><label class="form-label">Last Name</label><input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Password *</label>
                    <input type="password" class="form-control" name="password" required minlength="6">
                    @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3"><label class="form-label">Confirm Password *</label><input type="password" class="form-control" name="password_confirmation" required minlength="6"></div>
                <button type="submit" class="btn btn-primary">Create Admin</button>
                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
