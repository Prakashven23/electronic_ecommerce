@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Customer Details</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Name:</strong> {{ $customer->name }}
                </div>
                <div class="mb-3">
                    <strong>Email:</strong> {{ $customer->email }}
                </div>
                <div class="mb-3">
                    <strong>Phone:</strong> {{ $customer->phone ?? 'Not provided' }}
                </div>
                <div class="mb-3">
                    <strong>Joined:</strong> {{ $customer->created_at->format('M d, Y \a\t g:i A') }}
                </div>
                <div class="mb-3">
                    <strong>Total Orders:</strong> {{ $orders->count() }}
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order History</h5>
            </div>
            <div class="card-body">
                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>₹{{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5>No orders found</h5>
                        <p class="text-muted">This customer hasn't placed any orders yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 