@extends('layouts.admin')

@section('content')
<h3>Site Settings</h3>
@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    <div class="mb-3">
        <label for="maintenance" class="form-label">Maintenance Mode</label>
        <select class="form-control" id="maintenance" name="maintenance">
            <option value="off" @if(!$maintenance) selected @endif>OFF</option>
            <option value="on" @if($maintenance) selected @endif>ON</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection 