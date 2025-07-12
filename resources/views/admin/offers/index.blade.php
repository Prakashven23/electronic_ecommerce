@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Offers</h3>
    <a href="{{ route('admin.offers.create') }}" class="btn btn-primary">Add Offer</a>
</div>
@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Value</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Product</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($offers as $offer)
        <tr>
            <td>{{ $offer->id }}</td>
            <td>{{ $offer->name }}</td>
            <td>{{ ucfirst($offer->type) }}</td>
            <td>{{ $offer->value }}</td>
            <td>{{ $offer->start_date }}</td>
            <td>{{ $offer->end_date }}</td>
            <td>{{ $offer->product->name ?? '-' }}</td>
            <td>{{ $offer->category->name ?? '-' }}</td>
            <td>
                <a href="{{ route('admin.offers.edit', $offer) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.offers.destroy', $offer) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this offer?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection 