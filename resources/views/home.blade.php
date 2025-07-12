@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="jumbotron bg-primary text-white p-5 rounded mb-4">
        <h1 class="display-4">Welcome to Our Electronics Store</h1>
        <p class="lead">Discover the latest gadgets and electronics at amazing prices.</p>
        <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">Shop Now</a>
    </div>
    <div class="category-tab-bar d-flex align-items-center mb-4" style="overflow-x: auto; background: #23242a;">
        @foreach($categories as $category)
            <a href="#category-{{ $category->id }}"
               class="category-tab px-4 py-2 text-uppercase fw-bold text-white me-1"
               style="text-decoration: none; background: #23242a; border-bottom: 3px solid transparent; transition: background 0.2s, border 0.2s;"
               onmouseover="this.style.background='#111';"
               onmouseout="this.style.background='#23242a';">
                {{ $category->name }}
            </a>
        @endforeach
    </div>
    <!-- Categories Section -->
    <!-- <section class="mb-5">
        <h2 class="mb-4">Shop by Category</h2>
        <div class="row">
            @foreach($categories as $category)
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text">{{ $category->products_count }} products</p>
                        <a href="{{ route('products.index', ['category' => $category->id]) }}" class="btn btn-primary">Browse</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section> -->

    <!-- Best Sellers Section -->
    @if($bestSellers->count() > 0)
    <section class="mb-5">
        <h2 class="mb-4 text-primary fw-bold">Best Sellers</h2>
        <div id="bestSellerCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($bestSellers->chunk(3) as $chunkIndex => $productChunk)
                <div class="carousel-item @if($chunkIndex == 0) active @endif">
                    <div class="row g-4">
                        @foreach($productChunk as $product)
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-lg position-relative best-seller-card" style="background: linear-gradient(135deg, #f8fafc 60%, #e0e7ff 100%); transition: transform 0.2s;">
                                <span class="position-absolute top-0 start-0 badge bg-danger rounded-end px-3 py-2" style="z-index:2; font-size: 0.95rem;">Best Seller</span>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top rounded-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center rounded-top" style="height: 200px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title text-primary fw-bold">{{ $product->name }}</h5>
                                    <p class="card-text text-muted small">{{ Str::limit($product->description, 80) }}</p>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="h5 text-success mb-0">₹{{ number_format($product->price, 2) }}</span>
                                        <!-- <span class="badge bg-info text-dark">{{ $product->total_sold ?? 0 }} sold</span> -->
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-0">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-gradient btn-sm w-100 fw-semibold">View Details</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            <button class="carousel-control-prev custom-carousel-btn" type="button" data-bs-target="#bestSellerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: #6366f1; border-radius: 50%;"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next custom-carousel-btn" type="button" data-bs-target="#bestSellerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: #6366f1; border-radius: 50%;"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>
    <style>
        .best-seller-card:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 8px 32px 0 rgba(99,102,241,0.15);
        }
        .btn-gradient {
            background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
            color: #fff;
            border: none;
        }
        .btn-gradient:hover {
            background: linear-gradient(90deg, #60a5fa 0%, #6366f1 100%);
            color: #fff;
        }
        .custom-carousel-btn {
            filter: drop-shadow(0 2px 8px #6366f155);
        }
    </style>
    @endif

    <!-- Category Tab Bar -->
  

    <!-- Category-wise Products Section -->
    @foreach($categories as $category)
        @if($category->products->count() > 0)
        <section class="mb-5" id="category-{{ $category->id }}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0 text-dark fw-bold">{{ $category->name }}</h3>
                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="btn btn-outline-primary btn-sm fw-semibold">View All</a>
            </div>
            <div class="row">
                @foreach($category->products->take(3) as $product)
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-0 shadow-sm">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 180px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="fas fa-image fa-2x text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ $product->name }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($product->description, 60) }}</p>
                            <span class="h6 text-success">₹{{ number_format($product->price, 2) }}</span>
                        </div>
                        <div class="card-footer bg-white border-0 d-flex gap-2">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm w-100">View Details</a>
                            <form action="{{ route('cart.add') }}" method="POST" class="w-100">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-success btn-sm w-100"><i class="fas fa-cart-plus"></i> Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif
    @endforeach

    <style>
    .category-tab-bar {
        white-space: nowrap;
        border-bottom: 1px solid #444;
        padding-left: 10px;
        padding-right: 10px;
    }
    .category-tab {
        display: inline-block;
        color: #fff;
        font-size: 1rem;
        border-radius: 0;
        margin-bottom: -1px;
    }
    .category-tab.active,
    .category-tab:focus {
        border-bottom: 3px solid #fff !important;
        background: #111 !important;
    }
    </style>
    <script>
        document.querySelectorAll('.category-tab').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
                document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>

    <!-- Featured Products Section -->
    @if($featuredProducts->count() > 0)
    <section class="mb-5">
        <h2 class="mb-4">Featured Products</h2>
        <div class="row">
            @foreach($featuredProducts as $product)
            <div class="col-md-3 mb-3">
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
                            <span class="h5 text-primary mb-0">₹{{ number_format($product->price, 2) }}</span>
                            <span class="badge bg-warning">Featured</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-sm w-100">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Active Offers Section -->
   
</div>
@endsection 