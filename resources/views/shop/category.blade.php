@extends('layouts.app')

@section('content')
<h3>Category: {{ $category->name }}</h3>
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
@endsection 