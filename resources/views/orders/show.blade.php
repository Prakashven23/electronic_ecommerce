@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Order #{{ $order->id }}</h2>
    <div class="mb-3">
        <strong>Date:</strong> {{ $order->created_at->format('M d, Y g:i A') }}<br>
        <strong>Status:</strong> <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($order->status) }}</span><br>
        <strong>Total:</strong> ₹{{ number_format($order->total, 2) }}
    </div>
    <h5>Order Items</h5>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Product deleted' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->price, 2) }}</td>
                    <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Back to Orders</a>
</div>
@endsection 