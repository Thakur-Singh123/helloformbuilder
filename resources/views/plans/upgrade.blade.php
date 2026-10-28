@extends('layouts.site')

@section('content')
<div class="container text-center" style="max-width:520px;margin:60px auto;">
    @if(session('error'))
        <div class="alert alert-warning">{{ session('error') }}</div>
    @endif

    <h2>Upgrade to Pro</h2>
    <p>You’ve used your free template. Upgrade to Pro for unlimited templates!</p>
    <h3>$49 <small>/month</small></h3>

    <form method="POST" action="{{ route('plans.checkout') }}">
        @csrf
        <button type="submit" class="btn btn-primary btn-lg">Upgrade Now</button>
    </form>
</div>
@endsection