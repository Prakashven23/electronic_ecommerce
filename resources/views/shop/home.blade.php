@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Categories</h2>
        <div class="row">
            @foreach($categories as $category)
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <a href="{{ route('category', $category->id) }}">{{ $category->name }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <h2>Latest Products</h2>
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">₹{{ $product->sale_price ?: $product->price }}</p>
                            <a href="{{ route('product', $product->id) }}" class="btn btn-primary">View</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection 