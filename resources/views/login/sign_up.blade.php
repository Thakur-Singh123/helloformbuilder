@extends('layouts.auth')
@section('title', 'Sign up')

@section('content')
<h1 class="text-2xl font-bold text-brand-navy">Create your account</h1>
<p class="mt-1 text-sm text-slate-500">Sign up free to use templates and start collecting responses.</p>

<form method="POST" action="{{ route('user_register') }}" class="mt-6 space-y-4">
    @csrf
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="mb-1 block text-sm font-medium text-brand-navy">First name</label>
            <input name="first_name" value="{{ old('first_name') }}" required class="field">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-brand-navy">Last name</label>
            <input name="last_name" value="{{ old('last_name') }}" required class="field">
        </div>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-brand-navy">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required class="field @error('email') field-err @enderror">
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }} <a href="{{ route('login') }}" class="font-medium underline">Log in instead</a></p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-brand-navy">Password</label>
        <div class="relative">
            <input id="pw" type="password" name="password" required minlength="6" autocomplete="new-password" class="field pr-14 @error('password') field-err @enderror">
            <button type="button" data-toggle-pw="pw" class="absolute inset-y-0 right-3 text-xs font-medium text-brand-deep">Show</button>
        </div>
        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-brand-navy">Confirm password</label>
        <div class="relative">
            <input id="cpw" type="password" name="confirm_password" required minlength="6" autocomplete="new-password" class="field pr-14 @error('confirm_password') field-err @enderror">
            <button type="button" data-toggle-pw="cpw" class="absolute inset-y-0 right-3 text-xs font-medium text-brand-deep">Show</button>
        </div>
        @error('confirm_password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <label class="flex items-start gap-2 text-sm text-slate-600">
        <input type="checkbox" name="terms" required class="mt-0.5 h-4 w-4 rounded border-brand-line"> I accept the Terms and Conditions and Privacy Policy.
    </label>

    <button class="btn-grad w-full rounded-lg px-4 py-2.5 text-sm font-semibold text-white">Create account</button>
</form>

<p class="mt-6 text-center text-sm text-slate-500">Already have an account? <a href="{{ route('login') }}" class="font-medium text-brand-deep underline">Log in</a></p>
@endsection