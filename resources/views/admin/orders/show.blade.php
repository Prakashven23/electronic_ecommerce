@extends('layouts.admin')

@section('content')
<h3>Order #{{ $order->id }}</h3>
@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
<div class="mb-3">
    <strong>User:</strong> {{ $order->user->name ?? '-' }}<br>
    <strong>Email:</strong> {{ $order->user->email ?? '-' }}<br>
    <strong>Total:</strong> {{ $order->total }}<br>
    <strong>Status:</strong> {{ ucfirst($order->status) }}<br>
    <strong>Created At:</strong> {{ $order->created_at }}
</div>
<h5>Order Items</h5>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product->name ?? '-' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->price }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<form method="POST" action="{{ route('admin.orders.update', $order) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="status" class="form-label">Update Status</label>
        <select class="form-control" id="status" name="status">
            <option value="pending" @if($order->status=='pending') selected @endif>Pending</option>
            <option value="processing" @if($order->status=='processing') selected @endif>Processing</option>
            <option value="completed" @if($order->status=='completed') selected @endif>Completed</option>
            <option value="cancelled" @if($order->status=='cancelled') selected @endif>Cancelled</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update Status</button>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back</a>
</form>
@endsection 