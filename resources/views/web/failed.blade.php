@extends('web.layout.layout', ['pageTitle' => $title])

@section('content')
<section class="blacksection">
    <div class="container">
        <div class="row">
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="checkout-form-main">
                    <h2>Payment Failed</h2>

                    <div class="row" style="justify-content: center;">
                        <div class="col-md-6">
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">Oh no!</h4>
                                <p>Your payment was unsuccessful. Please try again.</p>
                                <p>Order ID: <strong>{{ $orderid ?? 'N/A' }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="justify-content: center; margin-top: 20px;">
                        <div class="col-md-6 text-center">
                            <a href="{{ route('home') }}" class="btn btn-primary">Return to Home</a>
                            <a href="{{ route('checkout.index') }}" class="btn btn-warning">Try Again</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('sub-script')
<!-- Additional Scripts -->
@endpush
