@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')

<section>
    <div class="container-fluid p-0">
        <div class="row m-0">
            <div class="col-md-12 p-0">
                <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner">
                        @if (count($banners) == 0)
                            <div class="carousel-item">
                                <img src="{{ asset('public/web') }}/images/slider2.jpg" class="d-block w-100" alt="Slide 2">

                            </div>
                        @else
                            @foreach($banners as $index => $banner)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('public/coded-slider/'. $banner->image ) }}" class="d-block w-100" alt="{{ $banner->title }}">
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>




<!-- product section start -->
<section class="py-4">
    <div class="container-fluid">
        <div class="row tabforindex">
            <div class="text-center">
                <h2>BEST PRODUCTS FOR YOU</h2>
            </div>
            <ul class="nav nav-tabs text-center" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab" data-bs-target="#tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">MEN</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab2-tab" data-bs-toggle="tab" data-bs-target="#tab2" type="button" role="tab" aria-controls="tab2" aria-selected="false">WOMEN</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab3-tab" data-bs-toggle="tab" data-bs-target="#tab3" type="button" role="tab" aria-controls="tab3" aria-selected="false">PERFUME</button>
                </li>
            </ul>
            <!-- Tab content -->
            <div class="tab-content mt-3" id="myTabContent">
                <!-- Men Category Products -->
                <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                    <div class="container-fluid">
                        <div class="row pt-3">
                            @foreach($menProducts as $product)
                            <div class="col-md-3 col-6">
                                <div class="card">
                                    <a href="{{ route('product.details', ['productSlug' => $product->slug]) }}">
                                        <img src="{{ isImage('products', $product->image_1) }}" class="card-img-top" alt="{{ $product->name }}">
                                    </a>
                                    <div class="card-body p-0">
                                        <div class="d-flex titles pt-3">
                                            <div class="cardtitlefont">
                                                <h5 class="card-title text-start ">{{ $product->title }}</h5>
                                                <h6 class="text-start newsize">{{ $product->sub_title }}</h6>
                                            </div>
                                            <div>
                                                <i class="fa-heart toggle-heart {{ $product->isInWishlist ? 'fa-solid red-heart' : 'fa-regular' }}"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-color-id="{{ $product->variants->first()->color->id ?? '' }}"
                                                    data-product-size-id="{{ $product->variants->first()->size->id ?? '' }}">
                                                 </i>

                                            </div>
                                        </div>
                                        <div id="alert-message{{ $product->id }}" class="alert alert-success d-none" role="alert"></div>

                                        <div class="d-flex">
                                            <div>
                                                {{-- <p class="card-text">
                                                    <strike>INR {{ $product->originalPrices }}</strike>
                                                </p> --}}
                                            </div>
                                            <div class="">
                                              <p class="card-text">INR {{ $product->originalPrices[0] ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="d-flex titles">
                                            <div>
                                                <ul class="sizelist">
                                                    @if($product->sizeCodes)
                                                        @foreach($product->sizeCodes as $sizeCode)
                                                            <li>{{ $sizeCode }}</li>
                                                        @endforeach
                                                    @else
                                                        <li>No size available</li>
                                                    @endif
                                                </ul>
                                            </div>

                                            <div class="colorbox d-flex ml-3">
                                                @if($product->colorData)
                                                    @foreach($product->colorData as $colorName => $colorHex)
                                                        <div class="div{{ $loop->index + 1 }}" style="background-color: {{ $colorHex }}" title="{{ $colorName }}"></div>
                                                    @endforeach
                                                @else
                                                    <div>No color available</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-center pt-5">
                            <a href="{{ url('/products/men') }}">
                                <div class="btn btn-dark">Explore More</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                    <div class="container-fluid">
                        <div class="row pt-3">
                            @foreach($womenProducts as $product)
                            <div class="col-md-3 col-6">
                                <div class="card">
                                    <a href="{{ route('product.details', ['productSlug' => $product->slug]) }}">
                                        <img src="{{ isImage('products', $product->image_1) }}" class="card-img-top" alt="{{ $product->name }}">
                                    </a>
                                    <div class="card-body p-0">
                                        <div class="d-flex titles pt-3">
                                            <div class="cardtitlefont">
                                                <h5 class="card-title text-start ">{{ $product->title }}</h5>
                                                <h6 class="text-start newsize">{{ $product->sub_title }}</h6>
                                            </div>
                                            <div>
                                                <i class="fa-heart toggle-heart {{ $product->isInWishlist ? 'fa-solid red-heart' : 'fa-regular' }}"
                                                   data-product-id="{{ $product->id }}"
                                                   data-product-color="{{ $product->colorData ? array_key_first($product->colorData) : '' }}"
                                                   data-product-size="{{ $product->sizeCodes ? $product->sizeCodes[0] : '' }}">
                                                </i>
                                            </div>
                                        </div>
                                        <div id="alert-message{{ $product->id }}" class="alert alert-success d-none" role="alert"></div>
                                        <div class="d-flex">
                                            <div>

                                            </div>
                                            <div class="">
                                              <p class="card-text">INR {{ $product->originalPrices[0] ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="d-flex titles">
                                            <div>
                                                <ul class="sizelist">
                                                    @if($product->sizeCodes)
                                                        @foreach($product->sizeCodes as $sizeCode)
                                                            <li>{{ $sizeCode }}</li>
                                                        @endforeach
                                                    @else
                                                        <li>No size available</li>
                                                    @endif
                                                </ul>
                                            </div>

                                            <div class="colorbox d-flex ml-3">
                                                @if($product->colorData)
                                                    @foreach($product->colorData as $colorName => $colorHex)
                                                        <div class="div{{ $loop->index + 1 }}" style="background-color: {{ $colorHex }}" title="{{ $colorName }}"></div>
                                                    @endforeach
                                                @else
                                                    <div>No color available</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-center pt-5">
                            <a href="{{ url('/products/women') }}">
                                <div class="btn btn-dark">Explore More</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
                    <div class="container-fluid">
                        <div class="row pt-3">
                            @foreach($perfumeProducts as $product)
                            <div class="col-md-3 col-6">
                                <div class="card">
                                    <a href="{{ route('product.details', ['productSlug' => $product->slug]) }}">
                                        <img src="{{ isImage('products', $product->image_1) }}" class="card-img-top" alt="{{ $product->name }}">
                                    </a>
                                    <div class="card-body p-0">
                                        <div class="d-flex titles pt-3">
                                            <div class="cardtitlefont">
                                                <h5 class="card-title text-start ">{{ $product->title }}</h5>
                                                <h6 class="text-start newsize">{{ $product->sub_title }}</h6>
                                            </div>
                                            <div>
                                                <i class="fa-heart toggle-heart {{ $product->isInWishlist ? 'fa-solid red-heart' : 'fa-regular' }}"
                                                   data-product-id="{{ $product->id }}"
                                                   data-product-color="{{ $product->colorData ? array_key_first($product->colorData) : '' }}"
                                                   data-product-size="{{ $product->sizeCodes ? $product->sizeCodes[0] : '' }}">
                                                </i>
                                            </div>
                                        </div>
                                        <div id="alert-message{{ $product->id }}" class="alert alert-success d-none" role="alert"></div>
                                        <div class="d-flex">
                                            <div>
                                                {{-- <p class="card-text">
                                                    <strike>INR {{ $product->originalPrices }}</strike>
                                                </p> --}}
                                            </div>
                                            <div class="">
                                              <p class="card-text">INR {{ $product->originalPrices[0] ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="d-flex titles">
                                            <div>
                                                <ul class="sizelist">
                                                    @if($product->sizeCodes)
                                                        @foreach($product->sizeCodes as $sizeCode)
                                                            <li>{{ $sizeCode }}</li>
                                                        @endforeach

                                                    @endif
                                                </ul>
                                            </div>

                                            <!-- <div class="colorbox d-flex ml-3">
                                                @if($product->colorData)
                                                    @foreach($product->colorData as $colorName => $colorHex)
                                                        <div class="div{{ $loop->index + 1 }}" style="background-color: {{ $colorHex }}" title="{{ $colorName }}"></div>
                                                    @endforeach

                                                @endif
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-center pt-5">
                            <a href="{{ url('/products/perfume') }}">
                                <div class="btn btn-dark">Explore More</div>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>
<!-- product section end  -->

<!-- perfume section start -->
<a href="{{ url('/products/perfume') }}"><section class="background my-4">
   <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!--<div class="bottom-right-text">-->
                <!--SUTRAMGLOBAL PERFUMES-->
                <!--</div>-->
            </div>
        </div>
    </div>
</section></a>
<!-- perfume section end  -->

<!-- trending product slider start-->

<section>
    <div class="container py-4">
        <div class="row">
            <div class="text-center">
                <h2>TRENDING PRODUCTS</h2>
                <p>Unleash your style with our trending t-shirts! From bold designs to timeless classics, we’ve got the perfect fit for every vibe.</p>
            </div>
            <div class="col">
                <div class="owl-carousel owl-theme">
                    @if($trends && count($trends) > 0)
                        @foreach($trends as $trend)
                            <div class="item">
                                <a href="{{ route('product.details', ['productSlug' => $trend->slug]) }}">
                                    <img src="{{ isImage('products', $trend->image_1) }}" alt="{{ $trend->name }}" class="img-fluid">
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="item">
                            <a href="#">
                                <img src="https://via.placeholder.com/150" alt="Product 1" class="img-fluid">
                            </a>
                        </div>

                    @endif

                </div>
            </div>
        </div>
    </div>
</section>

<!-- for test  -->
@endsection
@push('sub-script')
<script type="text/javascript">

    let currentIndex = 1;
    const images = document.querySelectorAll('.slider-container img');
    const middleHeight = 300; // Height for the middle image
    const smallHeight = 200;  // Height for other images

    function updateSlider() {
        images.forEach((img, index) => {
            img.classList.remove('middle');
            img.style.height = smallHeight + 'px'; // Reset all images to small size
            img.style.opacity = '0.7';  // Lower opacity for non-middle images
        });

        // Animate the middle image to grow larger
        images[currentIndex].classList.add('middle');
        images[currentIndex].style.height = middleHeight + 'px'; // Larger size for the middle image
        images[currentIndex].style.opacity = '1';    // Full opacity for the middle image
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % images.length;
        updateSlider();
    }

    // Auto slide every 3 seconds
    setInterval(nextSlide, 3000);
    updateSlider(); // Initial call to set the first middle image

</script>
<script>
    $(document).on('click', '.toggle-heart', function() {
        var heartIcon = $(this);

        if (!userId) {
            toastr.warning('Please log in first');
            return;
        }

        var productId = heartIcon.data('product-id');
        var colorId = heartIcon.data('product-color-id') || null; // Set to null if blank
        var sizeId = heartIcon.data('product-size-id') || null;  // Set to null if blank
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

<script>
    $(document).ready(function(){
        $(".owl-carousel").owlCarousel({
            items: 1,                // Default: Show 1 item
            loop: true,              // Loop the items
            margin: 10,              // Margin between items
            nav: true,               // Show navigation buttons
            dots: true,              // Show dots for navigation
            autoplay: true,          // Enable auto sliding
            autoplayTimeout: 3000,   // Set the time between slides (in milliseconds)
            autoplayHoverPause: true, // Pause the auto sliding when hovered
            responsive: {
                0: {
                    items: 1         // 1 item for small screens
                },
                600: {
                    items: 2         // 2 items for medium screens
                },
                1000: {
                    items: 4         // 3 items for large screens
                }
            }
        });
    });
</script>

@endpush
