@extends('layouts.auth')
@section('title', 'Check your email')

@section('content')
<div class="text-center">
    <div class="btn-grad mx-auto flex h-14 w-14 items-center justify-center rounded-full text-2xl text-white">✉</div>
    <h1 class="mt-5 text-2xl font-bold text-brand-navy">Check your email</h1>
    <p class="mt-2 text-sm text-slate-500">
        We sent a verification link to
        <strong class="text-brand-navy">{{ $email ?? 'your email address' }}</strong>.
        Click it to verify your account and continue.
    </p>
    <p class="mt-4 text-xs text-slate-400">Didn't get it? Check your spam folder.</p>
    <a href="{{ $loginUrl ?? route('login') }}" class="mt-6 inline-block text-sm font-medium text-brand-deep underline">Back to log in</a>
</div>
@endsection