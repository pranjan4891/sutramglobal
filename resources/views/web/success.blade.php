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
                        <h4 class="text-center mb-4">Payment Success</h4>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Transaction Id:</span>
                            <span><strong>{{ $order->razorpay_order_id}}</strong></span>
                        </div>
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
                            <p class="mb-2">Thank you for shopping with us! We’ll send you a confirmation email shortly.</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


