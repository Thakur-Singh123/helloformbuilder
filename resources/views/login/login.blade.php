@extends('layouts.auth')
@section('title', 'Log in')

@section('content')
<h1 class="text-2xl font-bold text-brand-navy">Welcome back</h1>
<p class="mt-1 text-sm text-slate-500">Log in to continue.</p>

<form method="POST" action="{{ route('user_login') }}" class="mt-6 space-y-4">
    @csrf
    <div>
        <label class="mb-1 block text-sm font-medium text-brand-navy">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required class="field @error('email') field-err @enderror">
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-brand-navy">Password</label>
        <div class="relative">
            <input id="pw" type="password" name="password" required class="field pr-14 @error('password') field-err @enderror">
            <button type="button" data-toggle-pw="pw" class="absolute inset-y-0 right-3 text-xs font-medium text-brand-deep">Show</button>
        </div>
        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center justify-between text-sm">
        <label class="flex items-center gap-2 text-slate-600"><input type="checkbox" name="remember" class="h-4 w-4 rounded border-brand-line"> Remember me</label>
        <a href="{{ route('forgot_password') }}" class="font-medium text-brand-deep underline">Forgot password?</a>
    </div>

    <button class="btn-grad w-full rounded-lg px-4 py-2.5 text-sm font-semibold text-white">Log in</button>
</form>

@if(session('unverified_email'))
    <form method="POST" action="{{ route('verification.resend') }}" class="mt-4 rounded-lg bg-brand-sky p-3 text-center text-sm">
        @csrf
        <input type="hidden" name="email" value="{{ session('unverified_email') }}">
        <button class="font-medium text-brand-deep underline">Resend verification email</button>
    </form>
@endif

<p class="mt-6 text-center text-sm text-slate-500">New here? <a href="{{ route('sign_up') }}" class="font-medium text-brand-deep underline">Create an account</a></p>
@endsection