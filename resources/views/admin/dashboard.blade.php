@extends('layouts.admin')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $totalOrders }}</h4>
                        <p class="card-text">Total Orders</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">₹{{ number_format($totalRevenue, 2) }}</h4>
                        <p class="card-text">Total Revenue</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-rupee-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $totalCustomers }}</h4>
                        <p class="card-text">Total Customers</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $totalProducts }}</h4>
                        <p class="card-text">Total Products</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Orders -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Orders</h5>
            </div>
            <div class="card-body">
                @if($recentOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                                    <td>₹{{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No orders yet.</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Best Sellers -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Best Selling Products</h5>
            </div>
            <div class="card-body">
                @if($bestSellers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Sold</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bestSellers as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->total_sold ?? 0 }}</td>
                                    <td>₹{{ number_format($product->price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No sales data available.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Orders by Status -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Orders by Status</h5>
            </div>
            <div class="card-body">
                @if($ordersByStatus->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ordersByStatus as $status)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $status->status === 'completed' ? 'success' : ($status->status === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($status->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $status->count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No orders yet.</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Monthly Revenue -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">This Month's Revenue</h5>
            </div>
            <div class="card-body text-center">
                <h2 class="text-success">₹{{ number_format($monthlyRevenue, 2) }}</h2>
                <p class="text-muted">Total revenue for {{ now()->format('F Y') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Sales Chart -->
@if($monthlySales->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Monthly Sales (Last 6 Months)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Orders</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlySales as $sale)
                            <tr>
                                <td>{{ \Carbon\Carbon::createFromDate($sale->year, $sale->month, 1)->format('M Y') }}</td>
                                <td>{{ $sale->order_count }}</td>
                                <td>₹{{ number_format($sale->total_sales, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection 