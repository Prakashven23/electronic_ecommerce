@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6">
        @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" class="img-fluid" alt="{{ $product->name }}">
        @endif
    </div>
    <div class="col-md-6">
        <h3>{{ $product->name }}</h3>
        <p>{{ $product->description }}</p>
        <h4>₹{{ $product->sale_price ?: $product->price }}</h4>
        <form method="POST" action="{{ route('cart.add') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" required>
            </div>
            <button type="submit" class="btn btn-success">Add to Cart</button>
        </form>
    </div>
</div>
@endsection 