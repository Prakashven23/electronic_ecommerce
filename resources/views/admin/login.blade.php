@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <h3 class="mb-4">Admin Login</h3>
        <form method="POST" action="{{ url('admin/login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif
            <button type="submit" class="btn btn-dark w-100">Login</button>
        </form>
    </div>
</div>
@endsection 