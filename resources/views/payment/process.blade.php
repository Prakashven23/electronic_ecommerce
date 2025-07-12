@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Order Payment</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Order #{{ $order->id }}</h5>
                            <p><strong>Name:</strong> {{ $order->name }}</p>
                            <p><strong>Email:</strong> {{ $order->email }}</p>
                            <p><strong>Phone:</strong> {{ $order->phone }}</p>
                            <p><strong>Address:</strong> {{ $order->address }}, {{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</p>
                        </div>
                        <div class="col-md-6">
                            <div class="text-end">
                                <h5>Payment Summary</h5>
                                <p><strong>Subtotal:</strong> ₹{{ number_format($order->subtotal, 2) }}</p>
                                @if($order->discount > 0)
                                    <p><strong>Discount:</strong> -₹{{ number_format($order->discount, 2) }}</p>
                                @endif
                                <h4><strong>Total (Payable):</strong> <span class="text-success">₹{{ number_format($order->total, 2) }}</span></h4>
                            </div>
                        </div>
                    </div>

                    <hr>
                    
                    <div class="text-center">
                        <button type="button" id="pay-btn" class="btn btn-primary btn-lg">
                            <i class="fas fa-credit-card me-2"></i>Pay with Razorpay
                        </button>
                    </div>
                    
                    <div id="payment-status" class="mt-3" style="display: none;">
                        <div class="alert alert-info">
                            <i class="fas fa-spinner fa-spin me-2"></i>
                            <span id="status-message">Processing payment...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const payBtn = document.getElementById('pay-btn');
    const statusDiv = document.getElementById('payment-status');
    const statusMessage = document.getElementById('status-message');
    
    payBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Show loading state
        payBtn.disabled = true;
        payBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        statusDiv.style.display = 'block';
        statusMessage.textContent = 'Creating payment order...';
        
        // Create Razorpay order
        fetch("{{ route('razorpay.order') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                amount: {{ $order->total }}, // Always use the sale price (total)
                order_id: {{ $order->id }}
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            if (data.id) {
                statusMessage.textContent = 'Opening payment gateway...';
                
                // Configure Razorpay options
                var options = {
                    key: '{{ env('RAZORPAY_KEY_ID') }}',
                    amount: data.amount,
                    currency: data.currency,
                    name: 'TechStore',
                    description: 'Order #{{ $order->id }} Payment',
                    order_id: data.id,
                    handler: function (response) {
                        statusMessage.textContent = 'Verifying payment...';
                        
                        // Verify payment
                        fetch("{{ route('razorpay.verify') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_signature: response.razorpay_signature,
                                order_id: {{ $order->id }}
                            })
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                statusDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>Payment successful! Redirecting...</div>';
                                setTimeout(() => {
                                    window.location.href = "{{ route('thankyou') }}";
                                }, 2000);
                            } else {
                                statusDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Payment verification failed: ' + (res.error || 'Unknown error') + '</div>';
                                payBtn.disabled = false;
                                payBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Pay with Razorpay';
                            }
                        })
                        .catch(error => {
                            console.error('Verification error:', error);
                            statusDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Payment verification failed. Please try again.</div>';
                            payBtn.disabled = false;
                            payBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Pay with Razorpay';
                        });
                    },
                    prefill: {
                        name: '{{ $order->name }}',
                        email: '{{ $order->email }}',
                        contact: '{{ $order->phone }}'
                    },
                    theme: {
                        color: '#6777ef'
                    },
                    modal: {
                        ondismiss: function() {
                            statusDiv.style.display = 'none';
                            payBtn.disabled = false;
                            payBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Pay with Razorpay';
                        }
                    }
                };
                
                var rzp = new Razorpay(options);
                rzp.open();
            } else {
                throw new Error('Unable to create payment order');
            }
        })
        .catch(error => {
            console.error('Payment error:', error);
            statusDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Error: ' + error.message + '</div>';
            payBtn.disabled = false;
            payBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Pay with Razorpay';
        });
    });
});
</script>
@endsection 