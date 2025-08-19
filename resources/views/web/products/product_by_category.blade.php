@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')
<style>
   .tab button {
   background-color: inherit;
   border: none;
   outline: none;
   cursor: pointer;
   padding: 14px 16px;
   transition: 0.3s;
   font-size: 17px;
   }
   .tab button:hover {
   background-color: #ddd;
   }
   .tab button.active {
   background-color: #ccc;
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
      <div class="row">
         <div class="text-center pb-3 ">
            <h2>BEST PRODUCTS FOR YOU</h2>
            <p>Redefine confidence with premium clothing and perfumes that leave a lasting impression wherever you go.</p>
         </div>
         @if ($allproducts->count() > 0)
            @foreach ($allproducts as $product)
                <div class="col-md-4 pb-4">
                    <div class="card">
                        <!-- Product Detail Page Link -->
                        <a href="{{ route('product.details', ['productSlug' => $product->slug]) }}">
                            <!-- Dynamically load product image -->
                            <img src="{{ isImage('products', $product->image_1) }}" class="card-img-top" alt="{{ $product->title }}">
                        </a>
                        <div class="card-body p-0">
                            <div class="d-flex titles pt-3">
                                <!-- Product title -->
                                <div>
                                    <h5 class="card-title text-start">{{ $product->title }}</h5>
                                    <h6 class="text-start">{{ $product->sub_title }}</h6>
                                </div>
                                <!-- Wishlist icon -->
                                <div>
                                    <i class="fa-heart toggle-heart {{ $product->isInWishlist ? 'fa-solid red-heart' : 'fa-regular' }}"
                                        data-product-id="{{ $product->id }}"
                                        data-product-color-id="{{ $product->variants->first()->color->id ?? '' }}"
                                        data-product-size-id="{{ $product->variants->first()->size->id ?? '' }}">
                                    </i>
                                </div>
                            </div>
                            <div id="alert-message{{ $product->id }}" class="alert alert-success d-none" role="alert"></div>

                            <!-- Price display -->
                            <div class="d-flex">
                                @php
                                    // Get the first variant with quantity > 0
                                    $firstAvailableVariant = $product->variantData->first(function ($variant) {
                                        return $variant['quantity'] > 0;
                                    });
                                @endphp
                                @if ($firstAvailableVariant)
                                    <!-- Original price (with strike through) -->
                                    {{-- <div>
                                        <p class="card-text"><strike>INR {{ $firstAvailableVariant['original_price'] }}</strike></p>
                                    </div> --}}
                                    <!-- Discounted price -->
                                    <div class="">
                                        <p class="card-text">INR {{ $firstAvailableVariant['original_price'] }}</p>
                                    </div>
                                @else
                                    <!-- No available variants -->
                                    <div class="px-3">
                                        <p class="card-text text-muted">Out of stock</p>
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex titles">
                                <!-- List of available sizes -->
                                <div>
                                    <ul class="sizelist">
                                        @foreach($product->sizeCodes as $sizeCode)
                                            <li>{{ $sizeCode }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <!-- Display available colors as colored boxes -->
                                @if($product->category_id != '3')
                                    <div class="colorbox d-flex ml-3">
                                        @foreach($product->colorData as $colorName => $colorHex)
                                            <div class="div{{ $loop->index + 1 }}" style="background-color: {{ $colorHex }}" title="{{ $colorName }}"></div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Hidden fields to store the first available color and size for the wishlist functionality -->
                            <input type="hidden" class="first-color" value="{{ !empty($product->colorData) ? array_key_first($product->colorData) : '' }}">
                            <input type="hidden" class="first-size" value="{{ !empty($product->sizeCodes) && count($product->sizeCodes) > 0 ? $product->sizeCodes[0] : '' }}">
                        </div>
                    </div>
                </div>
            @endforeach

         @else
         <h5 class="text-center">No Products Found</h5>
         @endif
      </div>
   </div>
</section>
@endsection
@push('sub-script')

<script type="text/javascript">
 $(document).on('click', '.toggle-heart', function() {
    var heartIcon = $(this);

    if (!userId) {
        toastr.warning('Please log in first');
        return;
    }

    var productId = heartIcon.data('product-id');
    var colorId = heartIcon.data('product-color-id'); // Pass color_id
    var sizeId = heartIcon.data('product-size-id'); // Pass size_id
    var action = heartIcon.hasClass('fa-solid') ? 'remove' : 'add';

    $.ajax({
        url: '{{ url("wishlist/toggle") }}',
        method: 'POST',
        data: {
            product_id: productId,
            color_id: colorId,
            size_id: sizeId,
            action: action,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status === 1) {
                if (action === 'add') {
                    heartIcon.removeClass('fa-regular').addClass('fa-solid red-heart');
                    toastr.success(response.message);
                } else {
                    heartIcon.removeClass('fa-solid red-heart').addClass('fa-regular');
                    toastr.success(response.message);
                }
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr, status, error) {
            toastr.error('An error occurred. Please try again.');
            console.log('Error:', error);
        }
    });
});

</script>

@endpush
