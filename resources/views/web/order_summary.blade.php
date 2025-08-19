@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')
<section class="blacksection">
    <div class="container">
        <div class="row">
        </div>
    </div>
</section>
<!-- profile section start -->
<section class="py-5 ">
    <div class="container-fluid px-5">
    <h2 class="text-center">ORDER SUMMARY</h2>
        {{-- <div class="row my-5">
                   <div class="col-md-6 ordersummary">
                      <div class="d-flex">
                        <div>
                            <i class="fa fa-gift" aria-hidden="true"></i>
                        </div>
                        <div class="mx-2">
                            <h6 class="m-0">Delivered</h6>
                            <P>16 September 2024</P>
                        </div>
                      </div>
                   </div>
                   <div class="col-md-6 ordersummary">
                       <div class="text-end">
                          <h6 class="m-0"># 405-6152287-4538740</h6>
                         <P>Order Date : 16 September 2024</P>
                       </div>
                   </div>
                    <div class="col-md-4">
                        <div class="d-flex mb-4">
                        <div class="me-3">
                            <img src="images/wishlist.png" alt="Customer Image" class="img-fluid" style="height:200px;">
                        </div>
                        <div class="review-details text-start">
                            <div class=" titles pt-3">
                            <div>
                                <h5 class="card-title pb-3">POLO - WAFFLE</h5>
                            </div>
                            <p>Size : <span>32</span>
                            </p>
                            <p>color : <span> Gray</span>
                            </p>
                            </div>
                            <div class="d-flex">
                            <div>
                                <p class="card-text">
                                <strike>INR 5244</strike>
                                </p>
                            </div>
                            <div class="px-3">
                                <p class="card-text">INR 5244</p>
                            </div>
                            </div>
                        </div>
                        </div>
                </div>
                <div class="col-md-4 borderorder">
                    <p>Aryan Kumar</p>
                    <p>Mobile : 8299314643</p>
                    <p>IMS Engineering College, NH-24 Adhyatmik Nagar, Near Dasna Ghaziabad, Uttar Pradesh GHAZIABAD, UTTAR PRADESH - 201015</p>
                </div>
                <div class="col-md-4">
                    <p>Payment Method</p>
                    <p><i class="fa fa-credit-card" aria-hidden="true"></i>&nbsp;Mastercard ending in XXXX</p>
                    <div class="d-flex titles">
                        <div>
                            <p>Sub Total</P>
                        </div>
                        <div class="colorbox d-flex  ml-3">
                            <p>INR 9400</P>
                        </div>
                    </div>
                    <div class="d-flex titles">
                        <div>
                            <p>Delivery Charges</P>
                        </div>
                        <div class="colorbox d-flex  ml-3">
                            <p>INR 900</p>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex titles">
                        <div>
                            <h5>TOTAL</h5>
                        </div>
                        <div class="colorbox d-flex  ml-3">
                            <p>INR 900</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-end mt-5">
                    <div class="orderbutton">
                        <div class="colorbox orderbutton">
                            <div class="btn btn-outline-dark">
                            RATE & REVIEW
                            </div>
                            <div class="btn btn-dark buybutton">
                            ORDER DETAIL
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

                <!-- Order Header -->
                <div class="row my-5 text-center text-md-start">
                    <!-- Order Status and Delivery Date -->
                    <div class="col-md-6 ordersummary d-sm-flex justify-content-sm-start justify-content-center ">
                        <div class="mb-2 mb-md-0 me-md-3">
                            <i class="fa fa-gift" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h6 class="m-0">{{ ucfirst($order->order_status) }}</h6>
                            <p>{{ \Carbon\Carbon::parse($order->updated_at)->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                    <!-- Order ID and Date -->
                    <div class="col-md-6 ordersummary d-flex flex-column align-items-center align-items-md-end">
                        <h6 class="m-0">#{{ $order->unique_order_id }}</h6>
                        <p>Order Date: {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</p>
                        <p>Order Time: {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}</p>
                    </div>
                </div>
                <!-- Order Items -->
                <div class="row">
                    <div class="col-md-4">
    @foreach ($order->items as $item)
    <div class="d-flex flex-column flex-md-row align-items-center mb-4 text-center text-md-start">
        <div class="me-md-3 mb-3 mb-md-0">
            <img src="{{ isImage('products/', $item->image) }}"
                 alt="{{ $item->title }}"
                 class="img-fluid"
                 style="height:200px; object-fit:cover;">
        </div>
        <div class="review-details">
            <div class="titles pt-3">
                <h5 class="card-title pb-3">{{ $item->title }}</h5>
                <p>Size: <span>{{ $item->sizecode }}</span></p>
                <p>Color: <span>{{ $item->colorname }}</span></p>
            </div>
            <div class="d-flex flex-column flex-md-row align-items-center">
                <p class="card-text mb-2 mb-md-0">INR {{ number_format($item->total_price, 2) }}</p>
                <p class="card-text ms-md-3">Qty: {{ $item->qty }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>


                    <!-- Delivery Address -->
                    <div class="col-md-4 borderorder">
                        <p>{{ $order->name }}</p>
                        <p>Mobile: {{ $order->phone }}</p>
                        <p>{{ $order->address }}, {{ $order->city }}, {{ $order->state }}, {{ $order->country }} - {{ $order->zip }}</p>
                    </div>

                    <!-- Payment Details -->
                    <div class="col-md-4">
                        <p>Payment Method</p>
                        <p><i class="fa fa-credit-card" aria-hidden="true"></i>&nbsp;{{ $order->payment_method }}</p>
                        <div class="d-flex titles">
                            <div>
                                <p>Sub Total</p>
                            </div>
                            <div class="colorbox d-flex ml-3">
                                <p>INR {{ number_format($order->sub_total, 2) }}</p>
                            </div>
                        </div>
                        <div class="d-flex titles">
                            <div>
                                <p>Discount Amount</p>
                            </div>
                            <div class="colorbox d-flex ml-3">
                                <p>INR {{ number_format($order->discount_price, 2) }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex titles">
                            <div>
                                <h5>TOTAL</h5>
                            </div>
                            <div class="colorbox d-flex ml-3">
                                <p>INR {{ number_format($order->gtotal, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row">
                    <div class="col-md-12 text-end mt-5">
                        <div class="orderbutton">
                            <div class="colorbox orderbutton">
                                {{-- <a href="" class="btn btn-outline-dark">
                                    RATE & REVIEW
                                </a> --}}
                                @if ($order->order_status == 'Delivered')
                                    <a href="{{route('order.invoice', ['id' => $order->id])}}" class="btn btn-dark buybutton">
                                        Invoice
                                    </a>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>


    </div>
</section>
<!-- profile section end -->

@endsection
