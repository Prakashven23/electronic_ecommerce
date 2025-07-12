@extends('layouts.admin')

@section('content')
<h3>Add Category</h3>
<form method="POST" action="{{ route('admin.categories.store') }}">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection 