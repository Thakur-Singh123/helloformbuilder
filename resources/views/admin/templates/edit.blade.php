@extends('user.include.layout')
@section('content')
@include('admin.partials.notification')
<a href="{{ route('admin_templates.index') }}" class="small text-decoration-none"><i class="bi bi-arrow-left"></i> Templates</a>
<h2 class="h4 my-3">Edit template</h2>
@include('admin.templates.form', [
    'template' => $template,
    'action'   => route('admin_templates.update', $template->id),
    'method'   => 'PUT',
])
@endsection