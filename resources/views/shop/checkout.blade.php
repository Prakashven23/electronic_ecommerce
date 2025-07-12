@extends('layouts.app')

@section('content')
<h3>Checkout</h3>
@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
<form id="checkout-form" method="POST" action="{{ url('checkout') }}">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <h5>Customer Details</h5>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" required></textarea>
            </div>
        </div>
        <div class="col-md-6">
            <h5>Order Summary</h5>
            @php $total = 0; @endphp
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        @php $subtotal = ($item->product->sale_price ?: $item->product->price) * $item->quantity; $total += $subtotal; @endphp
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ $item->product->sale_price ?: $item->product->price }}</td>
                            <td>₹{{ $subtotal }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <h4>Total: ₹{{ $total }}</h4>
            <button type="button" id="pay-btn" class="btn btn-success w-100 mt-3">Pay & Place Order (Razorpay)</button>
        </div>
    </div>
</form>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.getElementById('pay-btn').onclick = function(e) {
        e.preventDefault();
        const form = document.getElementById('checkout-form');
        const formData = new FormData(form);
        fetch("{{ url('checkout') }}", {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.order_id && data.amount) {
                const options = {
                    key: '{{ env('RAZORPAY_KEY_ID') }}',
                    amount: data.amount,
                    currency: 'INR',
                    name: 'Electronics Store',
                    description: 'Order Payment',
                    order_id: data.order_id,
                    handler: function (response) {
                        fetch("{{ route('razorpay.verify') }}", {
                            method: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'},
                            body: JSON.stringify({
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_signature: response.razorpay_signature,
                                order_id: data.db_order_id
                            })
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                form.submit();
                            } else {
                                alert('Payment verification failed!');
                            }
                        });
                    },
                    prefill: {
                        name: formData.get('name'),
                        email: formData.get('email'),
                        contact: formData.get('phone')
                    },
                    theme: { color: '#3399cc' }
                };
                const rzp = new Razorpay(options);
                rzp.open();
            } else {
                alert('Order creation failed!');
            }
        });
    };
</script>
@endsection 