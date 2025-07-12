@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Checkout</h2>
    
    <div class="row">
        <!-- Order Summary -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    @foreach ($cart as $item)
                        <div>
                            <strong>{{ $item['product']->name }}</strong><br>
                            Qty: {{ $item['quantity'] }}<br>
                            @if ($item['applied_offer'])
                                <span class="badge bg-success">
                                    Offer Applied: {{ $item['applied_offer']->id }}
                                    (
                                    @if ($item['applied_offer']->discount_type === 'percentage')
                                        {{ rtrim(rtrim($item['applied_offer']->discount_value, '0'), '.') }}%
                                        (&#8377;{{ number_format($item['discount_value'], 2) }})
                                    @else
                                        &#8377;{{ number_format($item['applied_offer']->discount_value, 2) }}
                                    @endif
                                    )
                                </span>
                            @endif
                            <br>
                            <span style="text-decoration: line-through; color: #888;">&#8377;{{ number_format($item['original_price'], 2) }}</span>
                            <span style="color: #28a745; font-weight: bold;">&#8377;{{ number_format($item['sale_price'], 2) }}</span>
                            <br>Total: &#8377;{{ number_format($item['total'], 2) }}
                        </div>
                        <hr>
                    @endforeach
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₹{{ number_format($subtotal, 2) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Discount:</span>
                        <span id="discount-amount">₹{{ number_format($discount, 2) }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong id="total-amount">₹{{ number_format($total, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Checkout Form -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Shipping Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
                        @csrf
                        
                        <!-- Customer Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone *</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Shipping Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">State *</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state') }}" required>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="pincode" class="form-label">Pincode *</label>
                                <input type="text" class="form-control @error('pincode') is-invalid @enderror" id="pincode" name="pincode" value="{{ old('pincode') }}" required>
                                @error('pincode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card"></i> Proceed to Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 