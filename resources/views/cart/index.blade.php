@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Shopping Cart</h2>
    
    @if(count($cart) > 0)
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        @foreach($cart as $item)
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-2">
                                @if($item['product']->image)
                                    <img src="{{ asset('storage/' . $item['product']->image) }}" class="img-fluid rounded" alt="{{ $item['product']->name }}">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-1">{{ $item['product']->name }}</h6>
                                <small class="text-muted">{{ $item['product']->category->name ?? 'Uncategorized' }}</small>
                            </div>
                            <div class="col-md-2">
                                @if($item['sale_price'] < $item['original_price'])
                                    <span class="text-decoration-line-through text-muted">₹{{ number_format($item['original_price'], 2) }}</span><br>
                                    <span class="fw-bold text-success">₹{{ number_format($item['sale_price'], 2) }}</span>
                                @else
                                    <span class="text-primary">₹{{ number_format($item['sale_price'], 2) }}</span>
                                @endif
                            </div>
                            <div class="col-md-2">
                                <form method="POST" action="{{ route('cart.update') }}" class="d-flex align-items-center">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control form-control-sm me-2" style="width: 60px;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-1">
                                <span class="fw-bold">₹{{ number_format($item['subtotal'], 2) }}</span>
                            </div>
                            <div class="col-md-1">
                                <form method="POST" action="{{ route('cart.remove') }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this item from cart?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>₹{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong class="text-primary">₹{{ number_format($total, 2) }}</strong>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i> Proceed to Checkout
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h4>Your cart is empty</h4>
            <p class="text-muted">Add some products to your cart to get started.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i> Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection 