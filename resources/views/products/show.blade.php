@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->id]) }}">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}" style="max-height: 400px;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="fas fa-image fa-5x text-muted"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Product Details -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">{{ $product->name }}</h2>
                    
                    @if($product->category)
                        <p class="text-muted">
                            <i class="fas fa-tag"></i> Category: 
                            <a href="{{ route('products.index', ['category' => $product->category->id]) }}" class="text-decoration-none">
                                {{ $product->category->name }}
                            </a>
                        </p>
                    @endif
                    
                    @if($product->featured)
                        <span class="badge bg-warning mb-3">
                            <i class="fas fa-star"></i> Featured Product
                        </span>
                    @endif
                    
                    <div class="mb-3">
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-decoration-line-through text-muted h4 mb-0">₹{{ number_format($product->price, 2) }}</span>
                                <span class="h2 text-success mb-0">₹{{ number_format($product->sale_price, 2) }}</span>
                                <span class="badge bg-danger">
                                    {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                                </span>
                            </div>
                        @else
                            <span class="h2 text-primary mb-0">₹{{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                    
                    @if($product->description)
                        <div class="mb-4">
                            <h5>Description</h5>
                            <p class="text-muted">{{ $product->description }}</p>
                        </div>
                    @endif
                    
                    <!-- Add to Cart Form -->
                    <form method="POST" action="{{ route('cart.add') }}" class="mb-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="99" required>
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Product Features -->
                    <div class="border-top pt-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <i class="fas fa-shipping-fast fa-2x text-primary mb-2"></i>
                                <div><small>Free Shipping</small></div>
                            </div>
                            <div class="col-4">
                                <i class="fas fa-undo fa-2x text-success mb-2"></i>
                                <div><small>Easy Returns</small></div>
                            </div>
                            <div class="col-4">
                                <i class="fas fa-shield-alt fa-2x text-warning mb-2"></i>
                                <div><small>Secure Payment</small></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3>Related Products</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        @if($relatedProduct->image)
                            <img src="{{ asset('storage/' . $relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}" style="height: 150px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="fas fa-image fa-2x text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                    <span class="text-decoration-line-through text-muted small">₹{{ number_format($relatedProduct->price, 2) }}</span>
                                    <span class="text-success">₹{{ number_format($relatedProduct->sale_price, 2) }}</span>
                                @else
                                    <span class="text-primary">₹{{ number_format($relatedProduct->price, 2) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-outline-primary btn-sm w-100">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 