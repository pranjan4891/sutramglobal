@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')
<section class="blacksection">
    <div class="container">
        <div class="row">
        </div>
    </div>
</section>


<section class="my-4 py-4">
    <div class="container my-4 py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="text-center mb-4">Order Summary</h4>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Total Amount:</span>
                            <span><strong>₹{{ number_format($order->sub_total, 2) }}</strong></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Discount Amount:</span>
                            <span><strong>₹{{ number_format($order->discount_price, 2) }}</strong></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Grand Total:</span>
                            <span class="text-success"><strong>₹{{ number_format($order->gtotal, 2) }}</strong></span>
                        </div>
                        <div class="text-center">
                            <button id="rzp-button" class="btn btn-primary btn-block">Pay Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        const options = {
            "key": "{{ $key }}", // Razorpay API Key
            "amount": "{{ $amount }}", // Amount in paise
            "currency": "INR",
            "name": "{{ $name }}",
            "description": "Order Payment",
            "order_id": "{{ $razorpayOrderId }}", // Razorpay Order ID
            "handler": function(response) {
                // Handle successful payment
                fetch("{{ route('payment.verify') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_signature: response.razorpay_signature
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to success URL
                        window.location.href = data.redirect_url;
                    } else {
                        // Handle failure
                        alert(data.message || "Payment Verification Failed");
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                    alert("An error occurred during payment verification.");
                });
            },
            "prefill": {
                "name": "{{ $name }}",
                "email": "{{ $email }}",
                "contact": "{{ $phone }}"
            },
            "theme": {
                "color": "#3399cc"
            }
        };

        const rzp = new Razorpay(options);
        document.getElementById('rzp-button').onclick = function(e) {
            rzp.open();
            e.preventDefault();
        };
    </script>

@endsection


