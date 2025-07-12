@extends('layouts.app')

@section('content')
<h3>Your Cart</h3>
@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
@if($cartItems->count())
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @foreach($cartItems as $item)
            @php $subtotal = ($item->product->sale_price ?: $item->product->price) * $item->quantity; $total += $subtotal; @endphp
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>
                    <form method="POST" action="{{ route('cart.update') }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="cart_id" value="{{ $item->id }}">
                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" style="width:60px;">
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </form>
                </td>
                <td>₹{{ $item->product->sale_price ?: $item->product->price }}</td>
                <td>₹{{ $subtotal }}</td>
                <td>
                    <form method="POST" action="{{ route('cart.remove') }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="cart_id" value="{{ $item->id }}">
                        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="mb-3">
    <h4>Total: ₹{{ $total }}</h4>
</div>
<a href="{{ route('checkout') }}" class="btn btn-success">Proceed to Checkout</a>
@else
<p>Your cart is empty.</p>
@endif
@endsection 