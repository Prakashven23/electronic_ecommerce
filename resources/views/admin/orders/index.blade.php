@extends('layouts.admin')

@section('content')
<h3>Orders</h3>
@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Total</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->user->name ?? '-' }}</td>
            <td>{{ $order->total }}</td>
            <td>{{ ucfirst($order->status) }}</td>
            <td>{{ $order->created_at }}</td>
            <td>
                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">View</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection 