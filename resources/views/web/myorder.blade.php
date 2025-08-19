@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')
<section class="blacksection">
   <div class="container">
      <div class="row"></div>
   </div>
</section>

<!-- Product Section Start -->
<section class="pt-5 pb-2">
   <div class="container">
      <div class="text-center pb-3">
         <h2>MY ORDERS</h2>
         <p>Order now for premium quality products and exceptional service, guaranteed.</p>
      </div>
   </div>
</section>

<section class="py-2">
   <div class="container">
      @forelse($orders as $order)
      <div class="row text-center text-md-start pt-5 align-items-center">
         <!-- Order Delivery and Date -->
         <div class="col-md-6 text-center text-md-start">
            <div class="d-flex flex-column flex-md-row align-items-center">
               <div></div>
               <div class="mx-md-2 mt-2 mt-md-0">
                  <i class="fa fa-gift" aria-hidden="true"></i>
                  <h6 class="m-0">Payment Status: {{ ucfirst($order->payment_status) }}</h6>
                  <h6 class="m-0">Order Status: {{ ucfirst($order->order_status) }}</h6>
                  <p>{{ \Carbon\Carbon::parse($order->updated_at)->format('d M Y, h:i A') }}</p>
               </div>
            </div>
         </div>
         <div class="col-md-6 ordersummary text-center text-md-end mt-3 mt-md-0">
            <h6 class="m-0">#{{ $order->unique_order_id }}</h6>
            <p>Order Date: {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</p>
            <p>Order Time: {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}</p>
         </div>

         <!-- Order Items -->
         @foreach($order->items as $item)
         <div class="col-md-6 text-center text-md-start">
            <div class="d-flex flex-column flex-md-row mb-4 align-items-center">
               <div class="me-md-3 mb-3 mb-md-0">
                  <img src="{{ isImage('products/',$item->image) }}"
                     alt="{{ $item->title }}"
                     class=""
                     style="height:200px; object-fit:cover;">
               </div>
               <div class="review-details text-center text-md-start">
                  <div class="titles pt-3">
                     <h5 class="card-title pb-3">{{ $item->title }}</h5>

                     <p>Size: <span>{{ $item->sizecode }}</span></p>
                     @if($item->colorname)
                     <p>Color: <span>{{ $item->colorname }}</span></p>
                     @endif
                  </div>
                  <div class=" flex-md-row align-items-center">
                   @if($item->total_price == 0.00)
                        <p class="card-text">Free Gift</p>
                    @else
                        <p class="card-text">INR {{ number_format($item->total_price, 2) }}</p>
                        <p class="card-text">Qty: {{ $item->qty }}</p>
                    @endif

                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-6 text-center text-md-end">
            @if ($order->order_status == 'delivered')
            <div class="d-flex justify-content-center justify-content-md-end">
               <a href="{{ route('product.details', ['productSlug' => $item->product->slug]) }}" class="btn p-0 mt-2"
                  style="border-bottom:1px solid black; font-size:12px; border-radius:0px;">RATE & REVIEW</a>
            </div>
            @if ($item->is_return==0)
            <a href="javascript:void(0);"
                onclick="returnProduct({{ $item->id }})">
                <div class="btn btn-dark my-3">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Return&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </a>
            @else
            <p>In Process to Return</p>
            @endif
             @endif
            <a href="{{ route('product.details', ['productSlug' => $item->product->slug]) }}">
               <div class="btn btn-dark my-3">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BUY AGAIN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               </div>
            </a>
         </div>
         @endforeach

         <!-- Order Actions -->
         <div class="container">
            <div class="row mt-3 justify-content-end">
               @if ($order->order_status == 'delivered')
               <div class="col-md-2 text-center">
                  <a href="{{route('order.invoice', ['id' => $order->id])}}">
                     <div class="btn btn-outline-dark w-100 mb-2" style="font-size: 14px;"><i class="fa fa-file-pdf" aria-hidden="true"></i> INVOICE</div>
                  </a>
               </div>
               @endif

               @if ($order->order_status == 'inprocess' && now()->diffInMinutes($order->created_at) <= 60)
                    <div class="col-md-2 text-center">
                        <div class="btn btn-outline-dark w-100 cancel-order-btn"
                                style="font-size: 14px;"
                                data-id="{{ $order->id }}"
                                data-url="{{ route('order.cancel', ['id' => $order->id]) }}">
                            ORDER CANCEL
                        </div>
                    </div>
                @endif

               <div class="col-md-2 text-center">
                    <a href="{{ route('order.summary', ['encryptedOrderId' => Crypt::encryptString($order->id)]) }}">
                        <div class="btn btn-outline-dark w-100" style="font-size: 14px;">ORDER SUMMARY</div>
                    </a>
               </div>
            </div>
         </div>
      </div>
      <hr>
      @empty
      <div class="row">
         <div class="col-md-12 text-center py-5">
            <h4>No Orders Found</h4>
         </div>
      </div>
      @endforelse
   </div>
</section>
@endsection


@push('sub-script')
<script>
$(document).ready(function () {
    $('.cancel-order-btn').on('click', function (e) {
        e.preventDefault();

        const orderId = $(this).data('id'); // Use data attribute to get the order ID
        const cancelUrl = $(this).data('url'); // Use data-url attribute for the route

        if (!confirm('Are you sure you want to cancel this order?')) {
            return;
        }

        $.ajax({
            url: cancelUrl, // Use the route passed from Blade
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}", // Include CSRF token
                order_status: 'cancelled' // Set status
            },
            success: function (response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    setTimeout(() => {
                        location.reload(); // Reload after success
                    }, 1500);
                } else {
                    toastr.error(response.message || 'Failed to cancel the order.');
                }
            },
            error: function (xhr, status, error) {
                toastr.error('An error occurred while canceling the order.');
                console.error('Error:', error);
            }
        });
    });

});

</script>
<script>
    function returnProduct(itemId) {
        if (confirm('Are you sure you want to return this product?')) {
            $.ajax({
                url: "{{ url('/order/return') }}/" + itemId,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function (response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);

                        // Optionally, reload the page or update the UI
                        location.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr) {
                    toastr.error('An error occurred while processing your request.');
                    console.error(xhr.responseText);
                }
            });
        }
    }
</script>

@endpush
