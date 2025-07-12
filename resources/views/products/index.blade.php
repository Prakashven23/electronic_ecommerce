@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Categories</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('products.index') }}" class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                            All Products
                        </a>
                        @foreach($categories as $category)
                        <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                           class="list-group-item list-group-item-action {{ request('category') == $category->id ? 'active' : '' }}">
                            {{ $category->name }}
                            <span class="badge bg-secondary float-end">{{ $category->products_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-md-9">
            <!-- Search and Results Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    @if(request('category'))
                        {{ $categories->find(request('category'))->name ?? 'Products' }}
                    @else
                        All Products
                    @endif
                </h2>
                <div class="text-muted">
                    {{ $products->total() }} products found
                </div>
            </div>
            
            <!-- Search Form -->
            <form method="GET" action="{{ route('products.index') }}" class="mb-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search products..." value="{{ request('search') }}">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
            
            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="row">
                    @foreach($products as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <span class="text-decoration-line-through text-muted">₹{{ number_format($product->price, 2) }}</span>
                                            <span class="h5 text-success mb-0">₹{{ number_format($product->sale_price, 2) }}</span>
                                        @else
                                            <span class="h5 text-primary mb-0">₹{{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                    @if($product->featured)
                                        <span class="badge bg-warning">
                                            <i class="fas fa-star"></i> Featured
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                    <form method="POST" action="{{ route('cart.add') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h4>No products found</h4>
                    <p class="text-muted">Try adjusting your search criteria or browse all products.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="fas fa-home"></i> Browse All Products
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 