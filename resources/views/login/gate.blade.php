@extends('layouts.auth')
@section('title', 'Continue')

@section('content')
<h1 class="text-2xl font-bold text-brand-navy">Continue with this template</h1>
<p class="mt-1 text-sm text-slate-500">Enter your email. We'll log you in or create your account.</p>

<form method="POST" action="{{ route('template.gate.check') }}" class="mt-6 space-y-4">
    @csrf
    <div>
        <label class="mb-1 block text-sm font-medium text-brand-navy">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com" class="field @error('email') field-err @enderror">
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <button class="btn-grad w-full rounded-lg px-4 py-2.5 text-sm font-semibold text-white">Continue</button>
</form>
@endsection