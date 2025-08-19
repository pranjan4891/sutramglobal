@extends('admin.layout.layout', ['pageTitle' => $action . ' ' . $title])
@section('contant')
<div class="container-fluid">
    <style>
        .product_image {
            height: 300px;
            width: -webkit-fill-available;
            object-fit: fill;
        }
    </style>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header py-3">

                        <h4 class="card-title mt-1"><i class="fab fa-product-hunt bigfonts"></i>
                            {{ !empty($title) ? $title : '' }}
                        </h4>
                        <div class="card-tools">
                            <a href="{{ route('admin.orders') }}" class="btn btn-secondary pull-right">Back</a>
                            <a href="{{ route('admin.order.invoice', ['id' => $order->id]) }}" class="btn btn-secondary pull-right">Download Invoice</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                    <label>Order Details:</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group d-flex ">
                                    <label for="order_id">Order ID :-</label>
                                   <p>&nbsp;&nbsp; {{$order->unique_order_id }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group d-flex ">
                                    <label for="order_id">Transaction ID :-</label>
                                   <p>&nbsp;&nbsp; {{$order->razorpay_order_id }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group d-flex ">
                                    <label for="tracking_id">Tracking ID :- </label>
                                   <p>&nbsp;&nbsp; {{$order->tracking_id }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group d-flex ">
                                    <label for="name">Name :- </label>
                                    <p>&nbsp;&nbsp;{{ $order->name }}</p>
                                </div>
                            </div>

                            {{-- <div class="col-md-4">
                                <div class="form-group d-flex ">
                                    <label for="email">Email :- </label>
                                    <p>&nbsp;&nbsp;{{ $order->email }}</p>
                                </div>
                            </div> --}}
                            <div class="col-md-4">
                                <div class="form-group d-flex ">
                                    <label for="date">Order Date :- </label>
                                   <p>&nbsp;&nbsp; {{$order->created_at }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group d-flex ">
                                    <label for="sub_total">Sub Total :- </label>
                                   <p>&nbsp;&nbsp; {{$order->sub_total }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group d-flex ">
                                    <label for="discount_price">Discount Price :- </label>
                                   <p>&nbsp;&nbsp; {{$order->discount_price }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group d-flex ">
                                    <label for="shipping_charge">Shipping Charge :- </label>
                                   <p>&nbsp;&nbsp; {{$order->shipping_charge }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group d-flex ">
                                    <label for="gtotal">Grand Total :- </label>
                                   <p>&nbsp;&nbsp; {{$order->gtotal }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group d-flex ">
                                    <label for="cgst">CGST :- </label>
                                   <p>&nbsp;&nbsp; {{$order->cgst }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group d-flex ">
                                    <label for="sgst">SGST :- </label>
                                   <p>&nbsp;&nbsp; {{$order->sgst }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group d-flex ">
                                    <label for="igst">IGST :- </label>
                                   <p>&nbsp;&nbsp; {{$order->igst }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group d-flex ">
                                    <label for="payment_method">Payment Method :- </label>
                                   <p>&nbsp;&nbsp; {{$order->payment_method }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group d-flex">
                                    <label for="payment_status">Payment Status :- </label>
                                    <select name="payment_status" id="payment_status" class="form-control" data-order-id="{{ $order->id }}">
                                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                        <option value="cancel" {{ $order->payment_status == 'cancel' ? 'selected' : '' }}>Cancel</option>
                                        <option value="return" {{ $order->payment_status == 'return' ? 'selected' : '' }}>Return</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group d-flex">
                                    <label for="order_status">Order Status :- </label>
                                    <select name="order_status" id="order_status" class="form-control" data-order-id="{{ $order->id }}">
                                        <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="inprocess" {{ $order->order_status == 'inprocess' ? 'selected' : '' }}>In Process</option>
                                        <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="return" {{ $order->order_status == 'return' ? 'selected' : '' }}>Return</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                    <label>Shipping Address</label>
                                </div>
                                <div class=" d-flex ">
                                    <label for="phone">Name :- &nbsp;</label>
                                    <p>{{ $order->name }}</p>
                                </div>
                                <div class=" d-flex ">
                                    <label for="phone">Phone :- &nbsp;</label>
                                    <p>{{ $order->phone }}</p>
                                </div>
                                <div class=" d-flex ">
                                    <label for="address">Address :- &nbsp;</label>
                                    <p>
                                        {{ $order->address }},
                                        {{ $order->city }},
                                        {{ $order->state }},
                                        {{ $order->country }}
                                    </p>
                                </div>
                                <div class=" d-flex ">
                                    <label for="address">Pin Code :- </label>
                                    <p>&nbsp;{{ $order->zip }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                    <label>Billing Details</label>
                                </div>
                                <div class=" d-flex ">
                                    <label for="phone">Name :- &nbsp;</label>
                                    <p>{{ $order->name }}</p>
                                </div>
                                <div class=" d-flex ">
                                    <label for="phone">Phone :- &nbsp;</label>
                                    <p>{{ $order->phone }}</p>
                                </div>
                                <div class=" d-flex ">
                                    <label for="address">Address :- &nbsp;</label>
                                    <p>
                                        {{ $order->address }},
                                        {{ $order->city }},
                                        {{ $order->state }},
                                        {{ $order->country }}
                                    </p>
                                </div>
                                <div class=" d-flex ">
                                    <label for="address">Pin Code :- </label>
                                    <p>&nbsp;{{ $order->zip }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group bg-lightblue disabled color-palette px-2 pt-1">
                                    <label>Product Details</label>
                                </div>
                            </div>
                            <div class="card-body">

                                <table id="table-data" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>SKU</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Size</th>
                                            <th>Color</th>
                                            <th>Qtty</th>
                                            <th>Rate</th>
                                            <th>Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($order->products)
                                            @foreach ($order->products as $key => $product)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $product->sku }}</td>
                                                    <td><img src="{{ asset('public/uploads/products/'.$product->image) }} " width="50" alt=""></td>
                                                    <td>{{ $product->title }}</td>
                                                    <td>{{ $product->sizecode }}</td>
                                                    <td>{{ $product->colorname }}</td>
                                                    <td>{{ $product->qty }}</td>
                                                    <td>{{ $product->price }}</td>
                                                    <td>{{ $product->price * $product->qty }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8" class="text-center">No Product Found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>SKU</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Size</th>
                                            <th>Color</th>
                                            <th>Qtty</th>
                                            <th>Rate</th>
                                            <th>Total Price</th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

</div>
@endsection
@push('sub-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Payment status change
        $('#payment_status').on('change', function() {
            var paymentStatus = $(this).val();
            var orderId = $(this).data('order-id');

            $.ajax({
                url: '{{ route("admin.orders.updatePaymentStatus", ":id") }}'.replace(':id', orderId),
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    payment_status: paymentStatus
                },
                success: function(response) {
                    alert(response.message);
                },
                error: function(xhr) {
                    alert('Error updating payment status');
                }
            });
        });

        // Order status change
        $('#order_status').on('change', function() {
            var orderStatus = $(this).val();
            var orderId = $(this).data('order-id');

            $.ajax({
                url: '{{ route("admin.orders.updateOrderStatus", ":id") }}'.replace(':id', orderId),
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    order_status: orderStatus
                },
                success: function(response) {
                    alert(response.message);
                },
                error: function(xhr) {
                    alert('Error updating order status');
                }
            });
        });
    });
</script>
@endpush
