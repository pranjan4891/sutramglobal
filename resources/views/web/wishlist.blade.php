@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')
<style>
   .remove{
   background-color: transparent;
   color: black;
   font-size: 12px;
   border-radius: 0px;
   padding: 2px;
   }
   .wishname{
   width:200px;
   font-size: 16px;
   }
   .wishname h5{
   font-size: 16px;
   }
   @media (max-width: 768px) {
   .review-details {
   text-align: center !important;
   }
   .d-md-flex {
   flex-direction: column !important;
   }
   .buy-now-btn, .add-to-cart-btn {
   width: 100%;
   }
   }
</style>
<section class="blacksection">
   <div class="container">
      <div class="row">
      </div>
   </div>
</section>
<!-- product section start -->
<section class="py-5">
   <div class="container">
      <div class="row text-center my-4">
         <div class="text-center pb-3">
            <h2>MY WISHLIST ITEMS</h2>
            <p>Save your favorites for later with our Wishlist – shop smarter, faster, and hassle-free!</p>
         </div>
      </div>
      @if($wishlists->isEmpty())
      <p class="text-center">Your wishlist is empty.</p>
      @else
        @foreach($wishlists as $wishlist)
            <div class="row text-center mb-5 align-items-center">
                <!-- Product Image & Details -->
                <div class="col-md-5 text-center text-md-start">
                    <div class="d-md-flex mb-4 justify-content-center justify-content-md-start">
                        <div class="me-md-3 mb-3 mb-md-0">
                            @if($wishlist->product)
                            <img src="{{ asset('public/uploads/products/'.$wishlist->product->image_1) }}"
                                alt="{{ $wishlist->product->title }}"
                                style="height:200px;">
                            @else
                            <p>Product image not available</p>
                            @endif
                        </div>
                        <div class="review-details text-center text-md-start">
                            <div class="titles pt-3">
                                <h5 class="card-title pb-3">
                                    {{ $wishlist->product ? $wishlist->product->title : 'Product not available' }}
                                </h5>
                                <p>Size: <span>{{ $wishlist->size ? $wishlist->size->code : 'N/A' }}</span></p>
                                @if ($wishlist->product->category_id != 3)
                                <p>Color: <span>{{ $wishlist->color ? $wishlist->color->name : 'N/A' }}</span></p>

                                @endif
                            </div>
                            @if($wishlist->variant_price)
                            <div class="d-flex justify-content-center justify-content-md-start">
                                <p class="card-text me-3">
                                    <strike>INR {{ number_format($wishlist->variant_original_price) }}</strike>
                                </p>
                                <p class="card-text">INR {{ number_format($wishlist->variant_price) }}</p>
                            </div>
                            @else
                            <p>Price not available</p>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Stock Status -->
                <div class="col-md-3 mt-3 mt-md-5 text-center">
                    @if($wishlist->product)
                    <h4 class="text-center" style="{{ $wishlist->is_in_stock ? 'color:green;' : 'color:red;' }}">
                        {{ $wishlist->is_in_stock ? 'In Stock' : 'Out of Stock' }}
                    </h4>
                    @else
                    <h4 class="text-center" style="color:red;">Product unavailable</h4>
                    @endif
                </div>
                <!-- Buttons -->
                <div class="col-md-4 mt-3 mt-md-0 text-center text-md-end">
                    @if($wishlist->product && $wishlist->is_in_stock)
                   {{-- <button class="btn btn-dark my-3 buy-now-btn"
                            data-product-id="{{ $wishlist->product->id }}"
                            data-size="{{ $wishlist->size_id }}"
                            data-color="{{ $wishlist->color_id }}"
                            data-price="{{ $wishlist->variant_price }}">
                        <i class="fa fa-shopping-bag"></i>&nbsp;&nbsp;&nbsp;&nbsp;BUY NOW&nbsp;&nbsp;&nbsp;&nbsp;
                    </button>--}}
                    <br>
                    <button class="btn btn-outline-dark add-to-cart-btn"
                            data-product-id="{{ $wishlist->product->id }}"
                            data-size="{{ $wishlist->size_id }}"
                            data-color="{{ $wishlist->color_id }}"
                            data-price="{{ $wishlist->variant_price }}">
                        <i class="fa fa-shopping-cart"></i>&nbsp;ADD TO CART
                    </button>
                    @else
                        <p class="text-danger mt-3"></p>
                    @endif
                    <div>
                        <form action="{{ route('wishlist.remove', $wishlist->id) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="remove btn btn-link p-0">
                                REMOVE FROM WISHLIST
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
      @endif
   </div>
</section>
@endsection
@push('sub-script')
<script>
   // Check on page load if cart sidebar should open
    $(document).ready(function () {
        if (sessionStorage.getItem('openCartSidebar')) {
            sessionStorage.removeItem('openCartSidebar'); // Remove the flag
            cartSidebar.classList.add('open');
            loadCartItems(); // Function to reload cart sidebar items
        }
    });
</script>

<script>
    $(document).ready(function () {
        // Add to Cart functionality
        $('.add-to-cart-btn').on('click', function (e) {
            e.preventDefault();

            var button = $(this);

            // Collect data attributes from the button
            var productId = button.data('product-id');
            var sizeId = button.data('size');
            var colorId = button.data('color');
            var price = button.data('price');
            var qty = 1; // Assuming a quantity of 1 for wishlist items
           // var sku = button.data('sku'); // Add SKU if it's part of the button data attributes

            // Perform AJAX request
            $.ajax({
                url: "{{ route('cart.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId,
                    size_id: sizeId,
                    color_id: colorId,
                    price: price,
                    qty: qty,
                  //  sku: sku // Include SKU if applicable
                },
                success: function (response) {
                    console.log('Response:', response);
                    if (response.status === 1) {
                        // Display success message
                        toastr.success(response.message);

                        // Update cart count
                        $('#cart-count').text(response.count);

                         // Save flag to sessionStorage to open cart sidebar after reload
                         sessionStorage.setItem('openCartSidebar', true);

                        // Optional: Reload page or perform additional actions
                        setTimeout(function () {
                            location.reload();
                        }, 3000); // Adjust delay to match Toastr timing
                    } else {
                        toastr.error('Failed to add to cart.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    toastr.error('An error occurred while adding the product to the cart.');
                }
            });
        });

        // Buy Now functionality
        $('.buy-now-btn').on('click', function (e) {
            e.preventDefault();

            var button = $(this);

            // Collect data attributes from the button
            var productId = button.data('product-id');
            var sizeId = button.data('size');
            var colorId = button.data('color');
            var price = button.data('price');
            var qty = 1; // Assuming a quantity of 1 for wishlist items
          //  var sku = button.data('sku'); // Add SKU if it's part of the button data attributes

            // Perform AJAX request
            $.ajax({
                url: "{{ route('cart.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId,
                    size_id: sizeId,
                    color_id: colorId,
                    price: price,
                    qty: qty,
                   // sku: sku // Include SKU if applicable
                },
                success: function (response) {
                    console.log('Response:', response);
                    if (response.status === 1) {
                        // Display success message
                        toastr.success(response.message);

                        // Redirect to the checkout page
                        setTimeout(function () {
                            window.location.href = "{{ route('cart.checkout') }}";
                        }, 3000); // Adjust delay to match Toastr timing
                    } else {
                        toastr.error('Failed to proceed to checkout.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    toastr.error('An error occurred while proceeding to checkout.');
                }
            });
        });
    });
</script>

@endpush
