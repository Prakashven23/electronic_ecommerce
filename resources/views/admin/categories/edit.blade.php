@extends('layouts.admin')

@section('content')
<h3>Edit Category</h3>
<form method="POST" action="{{ route('admin.categories.update', $category) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description">{{ $category->description }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection 