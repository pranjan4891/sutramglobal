@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')
<style>



img.card-img-top {
    height: 200px;
}
div#popup1 {
    z-index: 999;
}
.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7);
  display: none; /* Hide by default */
}

.overlay:target {
  display: block; /* Show when targeted */
}


.card-text {
    font-size: 14px;
}
.close {
  position: absolute;
  top: 10px;
  right: 15px;
  font-size: 30px;
  text-decoration: none;
  color: #333;
}

.box {
  width: 40%;
  margin: 0 auto;
  background: rgba(255,255,255,0.2);
  padding: 35px;
  border: 2px solid #fff;
  border-radius: 20px/50px;
  background-clip: padding-box;
  text-align: center;
}

.button {
  font-size: 1em;
  padding: 10px;
  color: #fff;
  border: 2px solid #06D85F;
  border-radius: 20px/50px;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease-out;
}
.button:hover {
  background: #06D85F;
}

.btn-success {
    color: #fff;
    background-color: #000000;
    border-color: #198754;
}


.popup {
    height: auto;
    margin: 110px auto;
    padding: 20px;
    background: #fff;
    border-radius: 5px;
    width: 60%;
    position: relative;
    transition: all 5sease-in-out;
}

.popup h2 {
  margin-top: 0;
  color: #333;
  font-family: Tahoma, Arial, sans-serif;
}
.popup .close {
    position: absolute;
    top: 0px;
    right: 30px;
    transition: all 200ms;
    font-size: 50px;
    font-weight: 400;
    text-decoration: none;
    color: #333;
}
.popup .close:hover {
  color: #06D85F;
}
.popup .content {
  max-height: 30%;

}

@media screen and (max-width: 700px){
    .popup .close {
    position: absolute;
    top: -21px;
    right: 15px;
    transition: all 200ms;
    font-size: 50px;
    font-weight: 400;
    text-decoration: none;
    color: #333;
}
  .box{
    width: 70%;
  }
  .popup{
    margin-top: 10px;
        height: auto;
        width: 90%;
  }
  .card-title {
    font-size: 12px;
    margin-bottom: .5rem;
}
img.card-img-top {
  height: 110px;
}
}


.colorboxs{
    margin:4px;
}
.fa-heart {
    font-size:22px;
}
.sizenum {
    padding: 10px;
    cursor: pointer;
    border: 1px solid #ccc;
    display: inline-block;
    margin-right: 5px;
}

.sizenum.selected {
    background-color: black;
    color: white;
    border-color: black;
}
.sizelist li:hover {
    background-color: #000000;
    color:white;
}
   /* Main Image Container */
    .main-image {
      position: relative;
      width: 100%;
      max-width: 400px; /* Adjust as needed */
      margin: 20px;
      display: inline-block;
    }

    .main-image img {
      width: 100%;
      display: block;
    }

    /* Magnified Area */
    .magnified-area {
      width: 400px;
      height: 400px;
      border: 1px solid #ddd;
      background-repeat: no-repeat;
      background-position: center;
      background-color: #fff;
      display: none;
      position: absolute;
      left: 420px; /* Distance from main image */
      top: 0;
      z-index: 9;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
    }
      @media (max-width: 576px) {
         .colorbox .btn {
        font-size: 14px;
        padding: 8px 12px;
    }
          .fa-heart {
    font-size: 20px;
    margin-right: 8px;
}


          .card-text span {
    font-size: 16px;
    margin-left: 6px;
}
          .font .card-title{
              font-size:16px;

          }
          .xyz {
    padding-top: 25px;
    padding-bottom: 40px;
}
              /* Magnified Area */
    .magnified-area {
      width: 150px;
      height: 150px;
      border: 1px solid #ddd;
      background-repeat: no-repeat;
      background-position: center;
      background-color: #fff;
      display: none;
      position: absolute;
      left: 100px; /* Distance from main image */
      top: 0;
      z-index: 9;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
    }
          .main-image img {
    width: 100%;
    padding: 5px;
    margin: -30px 0px 0px 23px;
}
.thumb-img {
    cursor: pointer;
    margin-bottom: 10px;
    z-index: 9;
  }
  .colorbox {
    position: sticky;
    bottom: 0;
    z-index: 9;
    background-color: #fff; /* Ensure a contrasting background for visibility */
    /*box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);*/
}

.colorbox .btn {
    flex: 1;
    margin: 0 5px;
}

@media (max-width: 768px) {
    .colorbox {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 9px;
    }
}


.nav-link {
    color: black;
    transition: color 0.3s;
}


</style>

<section class="blacksection">
   <div class="container">
      <div class="row">
      </div>
   </div>
</section>
<section>
<div class="container xyz">
  <div class="row">
    <!-- Left side - Images -->
    <div class="col-md-1 col-1">
      <div class="product-thumbnails d-flex flex-column">

        @if($product->image_2)
            <img src="{{ IsImage('products',  $product->image_2) }}" class="thumb-img" alt="Thumbnail 2" onclick="changeImage(this)">
        @endif
        @if($product->image_3)
            <img src="{{ IsImage('products',  $product->image_3) }}" class="thumb-img" alt="Thumbnail 3" onclick="changeImage(this)">
        @endif
        @if($product->image_4)
            <img src="{{ IsImage('products',  $product->image_4) }}" class="thumb-img" alt="Thumbnail 4" onclick="changeImage(this)">
        @endif
        @if($product->image_5)
            <img src="{{ IsImage('products',  $product->image_5) }}" class="thumb-img" alt="Thumbnail 5" onclick="changeImage(this)">
        @endif
        @if ($product->image_6)
            <img src="{{ IsImage('products',  $product->image_6) }}" class="thumb-img" alt="Thumbnail 6" onclick="changeImage(this)">
        @endif
        @if($product->image_7)
            <img src="{{ IsImage('products',  $product->image_7) }}" class="thumb-img" alt="Thumbnail 7" onclick="changeImage(this)">
        @endif
        @if($product->image_8)
            <img src="{{ IsImage('products',  $product->image_8) }}" class="thumb-img" alt="Thumbnail 8" onclick="changeImage(this)">
        @endif
      </div>
    </div>
    <!-- Center - Main Image -->
    <div class="col-md-5 col-9">
      <div class="main-image" id="image-container">
        <img id="mainProductImage" src="{{IsImage('products',  $product->image_2)}}" alt="{{ $product->title }}">
        <div class="magnified-area" id="magnified-area"></div>
      </div>
    </div>
    <!-- Right side - Product Details -->
    <div class="col-md-6">
        <div class="card-body p-1">
            <!-- Product Title and Subtitle -->
            <div class="d-flex titles pt-3">
                <div class="font">
                    <h5 class="card-title text-start">{{ $product->title ?? 'Product Title Not Available' }}</h5>
                    <h6 class="text-start">{{ $product->sub_title ?? '' }}</h6>
                </div>
                <div>
                    <i class="fa-heart toggle-heart {{ $isInWishlist ? 'fa-solid red-heart' : 'fa-regular' }}"
                    data-category-id="{{$product->category_id}}" data-product-id="{{ $product->id }}"></i>
                </div>
            </div>

            <!-- Product Prices -->
            <div class="d-flex titles">
                <div>
                    <p class="card-text">
                        @if ($defaultVariant)
                            {{-- <strike id="original-price">INR {{ $defaultVariant->original_price }}</strike> --}}
                            <span id="current-price" class="px-0">INR {{ $defaultVariant->original_price }}</span>
                        @else
                            <span id="current-price">Price Not Available</span>
                        @endif
                    </p>
                </div>
            </div>


            <!-- Product Views and Stock Status -->
            <div class="d-flex titles">
                <div class="m-0 p-0">
                    <p class="mt-2">{{ $product->views ?? 0 }} Customers viewed this product</p>
                </div>
                <div class="colorbox d-flex">
                    <p id="variant-stock-status">
                        {{ $defaultVariant && $defaultVariant->quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                    </p>
                </div>
            </div>
            <!--coupon code start -->

                    <div class="d-flex ">
                        @if ($coupons)
                            @if ($product->category_id )
                                    <div class="me-3 coupontitle">
                                    <i class="fa-solid fa-money-bill" style="font-size:30px;"></i>
                                    </div>
                                    <div class="pb-3">
                                        Use code <b>{{ $coupons->code }}</b> at checkout to get {{ $coupons->discount_value }}
                                        @if($coupons->discount_type == 'percentage')
                                            % off
                                        @else
                                            INR off
                                        @endif
                                    </div>

                            @else

                        <!--<div class="me-3 coupontitle">-->
                        <!--<i class="fa-solid fa-money-bill" style="font-size:30px; color: green"></i>-->
                        <!--</div>-->
                        <!--<div class="pb-3" style="color: green;font-weight: 600;">* Buy One Get One Free *</div>-->
                        @endif
                        @endif
                    </div>


            <!--coupon code end -->
            <!-- Color Selection -->
            @if ($product->category_id != 3 && $product->colorData->isNotEmpty())
                <p class="m-0">Color:
                    <span id="selected-color-name">
                        {{ $product->colorData->keys()->first() ?? 'Not Available' }}
                    </span>
                </p>
                <div class="colorbox d-flex ml-3 p-1">
                    @foreach ($product->colorData as $colorName => $colorCode)
                        @php
                            $colorVariant = $productVariants->firstWhere('color.code', $colorCode);
                        @endphp
                        <div class="div{{ $loop->index + 1 }} color-circle {{ $loop->first ? 'selected' : '' }}"
                            data-color-id="{{ $colorVariant->color->id ?? '' }}"
                            data-color-name="{{ $colorName }}"
                            data-color-code="{{ $colorCode }}"
                            style="background-color: {{ $colorCode }};"
                            onclick="selectColor(this)">
                        </div>
                    @endforeach
                </div>
            @endif


            <!-- Size Selection -->
            <div class="d-flex titles">
                <div class="coloroption">
                    <p class="mb-1 mt-2">Size:
                        <span id="selected-size">
                            {{ $product->sizeCodes[0] ?? 'Not Available' }}
                        </span>
                    </p>
                </div>
                @if ($product->category_id != 3)
                    <div class="titles colorbox2">
                        <a href="#" onclick="openSizeGuide()">Size Guide <i class="fa-solid fa-ruler-horizontal p-2"></i></a>
                    </div>
                @endif
            </div>
            <div class="colorbox d-flex ml-3 p-1">
                <ul class="sizelist m-0">
                    @foreach ($product->sizeCodes as $sizeCode)
                        @php
                            $sizeVariant = $productVariants->firstWhere('size.code', $sizeCode);
                        @endphp
                        <li class="sizenum {{ $loop->first ? 'selected' : '' }}"
                            data-size-id="{{ $sizeVariant->size->id ?? '' }}"
                            onclick="selectSize(this)">
                            {{ $sizeCode }}
                        </li>
                    @endforeach
                </ul>
            </div>



            <!-- Quantity Information -->
            <p id="available-quantity">
                Quantity Available:
                {{ $defaultVariant ? $defaultVariant->quantity : 'N/A' }}
            </p>

            <!-- Quantity Adjustment -->
            <div class="d-flex titles">
                <div class="coloroption pt-2">
                    <p class="m-0">Quantity</p>
                </div>
            </div>
            <div class="colorboxs d-flex">
                <div class="input-group">
                    <button class="btn btn-outline-secondary m-0" type="button" id="decrease">-</button>
                    <input type="text" id="quantity" class="text-center m-0" value="1" readonly>
                    <button class="btn btn-outline-secondary" type="button" id="increase">+</button>
                </div>
            </div>
            <p id="variant-availability" class="text-danger mt-2" style="display: none;">This product is currently out of stock.</p>

            <!-- Toast Container -->


            <div class="colorboxs">
                <div class="promise-product-page"></div>
                <div class="promise-company-features"></div>
                <script async src="https://sr-cdn.shiprocket.in/sr-promise/static/shopify-app.js?preview=0&uuid=f8054a40-6815-44ae-b895-f086b24f6b33"></script>
                <script>
                var Shopify = {
                    shop: ""
                }
                </script>
            </div>
            <!-- Add to Cart and Buy Now Buttons -->


    <a href="#" id="add-to-cart-btn">
       {{--<div class="btn btn-outline-dark"> --}}
                    <div class="btn btn-dark buybutton">

            <i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;&nbsp;ADD TO CART
        </div>
    </a>




                {{-- <a href="#" id="buy-now-btn" data-product-id="{{ $product->id }}" style="display: none;">
                    <div class="btn btn-dark buybutton">
                        <i class="fa fa-shopping-bag"></i>&nbsp;&nbsp;&nbsp;&nbsp;BUY NOW&nbsp;&nbsp;
                    </div>
                </a>--}}


            </div>



            <!-- Full Description and Return Policy -->
            <div class="pt-4">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                FULL DESCRIPTION
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                             data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <P>{!! $product->description !!}</P>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                DELIVERY & RETURN POLICY
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                             data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <P>{!! $product->short_desc !!}</P>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

   <!-- Modal for Pop-up Table -->
   <div id="sizeTableModal" class="modaling">
      <div class="modal__content">
         <span class="modal__close" onclick="closeSizeGuide()">&times;</span>
         <h5 class="text-center">{{@$size_guider_Name->title}}</h5>
         <p class="modal__title text-center">Size Guide</p>
         <table class="table table-bordered text-center">
            <thead>
               <tr>
                  <th></th>
                  <th>Chest</th>
                  <th>Length</th>
                @if(@$size_guider[0]->shoulder!='')
                  <th>Shoulder</th>
                @endif
                  <th>Sleeve Length</th>

                @if(@$size_guider[0]->waist!='')
                  <th>Waist Size</th>
                @endif
               </tr>
            </thead>
            <tbody>
                @if($size_guider)
                @foreach($size_guider as $val)
               <tr>
                  <td>{{$val->size}}</td>
                  <td>{{$val->chest}}</td>
                  <td>{{$val->length}}</td>
                  @if($val->shoulder!='')
                  <td>{{$val->shoulder}}</td>
                  @endif
                  <td>{{$val->sleeve}}</td>
                  @if($val->waist!='')
                  <td>{{$val->waist}}</td>
                  @endif

               </tr>
                @endforeach
                @endif

            </tbody>
         </table>
      </div>
   </div>
</section>
<section class="py-5">
   <div class="container">

      <div class="row">
        <div class="col-md-6">
            @php
                if($reviews->count()>0)
                {
                    $count = $reviews->count();
                }
                else
                {
                    $count ="No";
                }
            @endphp
           <h5 class="pb-2">{{  $count }} Reviews</h5>
           <div id="review-list">
              @include('web.partials.reviews_list', ['reviews' => $reviews])
           </div>
        </div>
        <div class="col-md-6">
            <form class="form-section" id="review-form">
                <h5>Add a Review</h5>
            <div id="success-message"></div>

                <div class="mb-3 d-flex">
                    <div class="form-label pt-2">Rating &nbsp;&nbsp;&nbsp;</div>
                    <div id="star-rating">
                        <span class="stars" data-value="1">&#9733;</span>
                        <span class="stars" data-value="2">&#9733;</span>
                        <span class="stars" data-value="3">&#9733;</span>
                        <span class="stars" data-value="4">&#9733;</span>
                        <span class="stars" data-value="5">&#9733;</span>
                    </div>
                    <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" id="rating" name="rating" value="">
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control" id="title" placeholder="Review Title">
                </div>

                <div class="mb-3">
                    <textarea class="form-control" id="review" rows="3" placeholder="Write your review here..."></textarea>
                </div>

                @if (Auth::check())
                    <!-- User is logged in, don't show name and email fields -->
                    <input type="hidden" id="name" name="name" value="{{ Auth::user()->name }}">
                    <input type="hidden" id="email" name="email" value="{{ Auth::user()->email }}">
                    <button type="submit" class="btn btn-dark">Comment</button>
                @else
                    <!-- User is not logged in, show name and email fields -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                        </div>
                    </div>
                @endif
            </form>
        </div>
     </div>

   </div>
</section>




<div id="popup1" class="overlay">
	<div class="popup text-center">
		<h2>Select Your Free Gift</h2>
		<br>
		<p>The Valentine Sale</p>
		<a class="close" href="#">&times;</a>
		<div class="content">
<div class="row">
    <!-- Product 1 -->
    <div class="col-6 col-md-3">
        <div class="card">
            <img src="../public/img/aqua.png" class="card-img-top" alt="Product 1">
            <div class="card-body text-center">
                <h5 class="card-title">AquaOcean</h5>
                <p class="card-text">Man Perfume</p>
                <button class="btn btn-success add-to-cart-gift" data-pro_id="{{ $product->id }}" data-id="47" >Add to Cart</button>
            </div>
        </div>
    </div>

    <!-- Product 2 -->
    <div class="col-6 col-md-3">
        <div class="card">
            <img src="../public/img/oud.png" class="card-img-top" alt="Product 2">
            <div class="card-body text-center">
                <h5 class="card-title">Oud Perfume</h5>
                <p class="card-text">Man Perfume</p>
                <button class="btn btn-success add-to-cart-gift" data-pro_id="{{ $product->id }}" data-id="48">Add to Cart</button>
            </div>
        </div>
    </div>

    <!-- Product 3 -->
    <div class="col-6 col-md-3">
        <div class="card">
            <img src="../public/img/Flor.png" class="card-img-top" alt="Product 3">
            <div class="card-body text-center">
                <h5 class="card-title">Flor Perfume</h5>
                <p class="card-text">Women</p>
                <button class="btn btn-success add-to-cart-gift" data-pro_id="{{ $product->id }}" data-id="45">Add to Cart</button>
            </div>
        </div>
    </div>

    <!-- Product 4 -->
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <img src="../public/img/Jass.png" class="card-img-top" alt="Product 4">
            <div class="card-body">
                <h5 class="card-title">Jass Perfume</h5>
                <p class="card-text">Woman Perfume</p>
                <button class="btn btn-success add-to-cart-gift" data-pro_id="{{ $product->id }}" data-id="46">Add to Cart</button>
            </div>
        </div>
    </div>
</div>
	    </div>
</div>
</div>







@endsection
@push('sub-script')
<style>
    .color-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 5px;
        cursor: pointer;
        border: 1px solid #ccc;
        transition: all 0.3s ease;
    }

    .color-circle:hover {
        border: 2px solid #000;
    }

    .color-circle.selected {
        border: 2px solid #000;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
    }

</style>

<script>
    $(document).ready(function () {
        $(".add-to-cart-gift").click(function () {
            var gift_product_id = $(this).data("id");
            var product_id = $(this).data("pro_id");

            $.ajax({
                url: "{{url('/cart/giftstore')}}", // Update with your actual route
                type: "POST",
                data: {
                    product_id: product_id,
                    gift_product_id: gift_product_id,
                    // size_id: null, // Change if applicable
                    // sku: null, // Change if applicable
                    // color_id: null, // Change if applicable
                    price: 0, // As requested
                    qty: 1,
                    _token: "{{ csrf_token() }}" // CSRF token for Laravel
                },
                success: function (response) {
                    if (response.status == 1) {
                        loadCartItems();

                        if (window.location.hash === "#popup1") {
                            history.replaceState(null, null, window.location.pathname); // Remove #popup1 from URL
                            window.location.reload(); // Reload the page
                        }
                    }else {
                        alert("Error adding to cart.");
                    }
                },
                error: function () {
                    alert("Something went wrong!");
                }
            });
        });
    });
</script>



<script>
    function selectSize(element) {
        // Remove 'selected' class from all items
        const sizeList = document.querySelectorAll('.sizelist .sizenum');
        sizeList.forEach(size => size.classList.remove('selected'));

        // Add 'selected' class to the clicked element
        element.classList.add('selected');
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
      const mainImage = document.getElementById("mainProductImage");
      const magnifiedArea = document.getElementById("magnified-area");
      const container = document.getElementById("image-container");

      container.addEventListener("mousemove", (e) => {
        const rect = mainImage.getBoundingClientRect();

        // Calculate mouse position relative to the image
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        // Ensure magnifier only works when inside the image
        if (x > 0 && x < rect.width && y > 0 && y < rect.height) {
          magnifiedArea.style.display = "block";

          // Set background image and position of the magnified area
          magnifiedArea.style.backgroundImage = `url(${mainImage.src})`;
          magnifiedArea.style.backgroundSize = `${mainImage.width * 2}px ${mainImage.height * 2}px`;
          magnifiedArea.style.backgroundPosition = `${-x * 2}px ${-y * 2}px`;
        } else {
          magnifiedArea.style.display = "none";
        }
      });

      container.addEventListener("mouseleave", () => {
        magnifiedArea.style.display = "none";
      });
    });
</script>
<script type="text/javascript">
    function openPopup() {
        window.location.href = "#popup1"; // Redirects to the popup section
    }
    $(document).ready(function() {
        // Star rating click event
        $('.stars').click(function() {
            let ratingValue = $(this).data('value');
            $('#rating').val(ratingValue);
            $('.stars').each(function() {
                if ($(this).data('value') <= ratingValue) {
                    $(this).html('&#9733;'); // Filled star
                } else {
                    $(this).html('&#9734;'); // Empty star
                }
            });
        });

        // AJAX form submission for review
        $('#review-form').submit(function(e) {
            e.preventDefault();

            let formData = {
                rating: $('#rating').val(),
                title: $('#title').val(),
                review: $('#review').val(),
                name: $('#name').val(),
                email: $('#email').val(),
                product_id: $('#product_id').val(),
            };

            $.ajax({
                url: "{{ route('review.store') }}", // Update with the correct route
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Reset the form
                        $('#review-form')[0].reset();
                        $('#rating').val('');
                        $('.stars').html('&#9734;'); // Reset star rating to empty

                        // Display success message using Toaster
                        toastr.success(response.message);

                        // Optionally update the reviews section or reload the page
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    }
                },
                error: function(xhr) {
                    // Parse response JSON to extract error messages
                    let errors = xhr.responseJSON.errors || {};
                    let errorMessage = xhr.responseJSON.message || "Error submitting review. Please check your inputs.";

                    // Display errors using Toaster
                    toastr.error(errorMessage);

                    // Optionally display individual field errors
                    $.each(errors, function(field, messages) {
                        messages.forEach(function(message) {
                            toastr.error(message);
                        });
                    });
                }
            });
        });
    });
</script>


<!-- JavaScript for dynamic behavior -->
<script type="text/javascript">
    $(document).ready(function () {
        const isColorRequired = {{ $product->category_id != 3 ? 'true' : 'false' }};
        let selectedColorName = $('.color-circle.selected').data('color-name') || null;
        let selectedColorId = $('.color-circle.selected').data('color-id') || null;
        let selectedSizeId = $('.sizelist .sizenum.selected').data('size-id') || null;
        let maxQuantity = {{ $defaultVariant ? $defaultVariant->quantity : 0 }}; // Default max quantity

        const alertDivcart = $('#alert-message-cart');


         // Function to dynamically update the maxQuantity and toggle buttons
        function updateMaxQuantity(newMaxQuantity) {
            maxQuantity = newMaxQuantity;
            const currentQty = parseInt($('#quantity').val());

            if (currentQty > maxQuantity) {
                $('#quantity').val(maxQuantity);
                showToast(`Quantity adjusted to match the available stock (${maxQuantity}).`, 'warning');
            }

                // Show or hide buttons based on the new max quantity
            toggleCartButtons(maxQuantity > 0);
        }

        // Initial stock check
        function initializeButtons() {
            if (maxQuantity > 0) {
                toggleCartButtons(true); // Show buttons if stock is available
            } else {
                toggleCartButtons(false); // Hide buttons if stock is unavailable
            }
        }


        // Initialize buttons on page load
        initializeButtons();

        function updateSelectedDetails() {
            $('#selected-color-name').text(selectedColorName || 'Not Available');
            $('#selected-size').text($('.sizelist .sizenum.selected').text() || 'Not Available');
        }

        // Function to select a color
        window.selectColor = function (el) {
            $('.color-circle').removeClass('selected'); // Deselect all colors
            $(el).addClass('selected'); // Select the clicked color
            selectedColorName = $(el).data('color-name'); // Update selected color name
            selectedColorId = $(el).data('color-id'); // Update selected color ID
            updateSelectedDetails();
            validateVariant();
        };

        // Function to select a size
        window.selectSize = function (el) {
            $('.sizenum').removeClass('selected'); // Deselect all sizes
            $(el).addClass('selected'); // Select the clicked size
            selectedSizeId = $(el).data('size-id'); // Update selected size ID
            updateSelectedDetails();
            validateVariant();
        };

        // Increase quantity
        $('#increase').on('click', function () {
            const currentQty = parseInt($('#quantity').val());
            if (currentQty < maxQuantity) {
                $('#quantity').val(currentQty + 1);
            } else {
                showToast('You cannot add more than the available quantity.', 'warning');
            }
        });

        // Decrease quantity
        $('#decrease').on('click', function () {
            const currentQty = parseInt($('#quantity').val());
            if (currentQty > 1) {
                $('#quantity').val(currentQty - 1);
            }
        });

        // Function to validate variant and update max quantity
        function validateVariant() {
            if (isColorRequired) {
                // If color is required (category_id != 3)
                if (!selectedColorName || !selectedSizeId) {
                    toastr.error('Please select both size and color.');
                    toggleCartButtons(false);
                    updateMaxQuantity(0);
                    return false;
                }
            } else {
                // If color is NOT required (category_id == 3)
                if (!selectedSizeId) {
                    toastr.error('Please select a size.');
                    toggleCartButtons(false);
                    updateMaxQuantity(0);
                    return false;
                }
            }

            $.ajax({
                url: "{{ route('product.checkVariant') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: "{{ $product->id }}",
                    color_id: selectedColorId,
                    size_id: selectedSizeId
                },
                success: function (response) {
                    if (response.exists) {
                        updateMaxQuantity(response.quantity);
                        updateStockStatus(response.quantity);

                        // Update variant details
                        $('.card-text span').text(`INR ${response.price}`);
                        $('.card-text strike').text(`INR ${response.original_price}`);
                        $('p:contains("Quantity Available")').text(`Quantity Available: ${response.quantity}`);

                        // Update wishlist icon dynamically
                        const wishlistIcon = $('.toggle-heart');
                        if (response.isInWishlist) {
                            wishlistIcon.addClass('fa-solid red-heart').removeClass('fa-regular');
                        } else {
                            wishlistIcon.addClass('fa-regular').removeClass('fa-solid red-heart');
                        }

                        if (response.quantity > 0) {
                            toggleCartButtons(true);
                            toastr.success('Product is available.');
                        } else {
                            toggleCartButtons(false);
                            toastr.error('Selected combination is out of stock.');
                        }
                    } else {
                        updateMaxQuantity(0);
                        toggleCartButtons(false);
                        updateStockStatus(0);
                        toastr.error('Selected combination is not available.');
                    }
                },
                error: function () {
                    updateMaxQuantity(0);
                    toggleCartButtons(false);
                    updateStockStatus(0);
                    toastr.error('Error occurred while validating the variant.');
                }
            });
        }

        // Function to display toast
        function showToast(message, type = 'info') {
            const toastId = `toast-${Date.now()}`;
            const toastHtml = `
                <div id="${toastId}" class="toast bg-${type} text-white" style="min-width: 250px; padding: 10px; border-radius: 5px; margin-top: 10px;">
                    ${message}
                </div>
            `;

            $('#toast-container').append(toastHtml);

            setTimeout(() => {
                $(`#${toastId}`).fadeOut(500, function () {
                    $(this).remove();
                });
            }, 3000); // Toast disappears after 3 seconds
        }

        // Function to toggle Add to Cart and Buy Now buttons
        function toggleCartButtons(show) {
            if (show) {
                $('#add-to-cart-btn').show();
                $('#buy-now-btn').show();
            } else {
                $('#add-to-cart-btn').hide();
                $('#buy-now-btn').hide();
            }
        }

        // Function to update the stock status dynamically
        function updateStockStatus(quantity) {
            const stockStatusElement = $('#variant-stock-status');
            if (quantity > 0) {
                stockStatusElement.text('In Stock').addClass('text-success').removeClass('text-danger');
            } else {
                stockStatusElement.text('Out of Stock').addClass('text-danger').removeClass('text-success');
            }
        }

        // Check if the user is logged in
        /* function checkLogin() {
            // Assuming user_id is stored in a global JavaScript variable or can be retrieved from a session
            var userId = {{ auth()->check() ? auth()->user()->id : 'null' }}; // Replace with your actual check if needed

            if (!userId) {
                toastr.error('Please log in to proceed.');
                return false;
            }
            return true;
        } */

        // Show or hide the quantity controls based on variant availability
        function toggleQuantityControls(isAvailable, quantity) {
            if (isAvailable) {
                $('.colorboxs').show();
                $('#variant-availability').hide();
                $('#quantity').val(Math.min($('#quantity').val(), quantity));
            } else {
                $('.colorboxs').hide();
                $('#variant-availability').show();
            }
        }

        // Call this function when a new variant is selected (example: validateVariant callback)
        function updateVariantAvailability(quantity) {
            const isAvailable = quantity > 0;
            toggleQuantityControls(isAvailable, quantity);
        }

        // Add to Cart functionality
        $('#add-to-cart-btn').on('click', function (e) {
            e.preventDefault();

            // Check if the user is logged in
            /* if (!checkLogin()) {
                return; // Stop further execution if not logged in
            } */

            // Check if the product requires both color and size selection
            var categoryId = "{{ $product->category_id }}";
            if (categoryId != 3) {
                if (!selectedColorId || !selectedSizeId) {
                    toastr.error('Please select both size and color before adding to cart.');
                    return;
                }
            } else {
                if (!selectedSizeId) {
                    toastr.error('Please select a size before adding to cart.');
                    return;
                }
            }

            var qty = $('#quantity').val();
            var product_id = "{{ $product->id }}";
            var priceText = $('#current-price').text(); // Use .text() to get the price
            var price = parseFloat(priceText.replace('INR', '').trim()); // Remove "INR" and convert to a number
            var t_price = price * qty;

            $.ajax({
                url: "{{ route('cart.store') }}", // Adjust the route if necessary
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: product_id,
                    size_id: selectedSizeId, // Pass the selected size ID
                    color_id: categoryId != 3 ? selectedColorId : null, // Pass the selected color ID only if required
                    qty: qty,
                    price: price
                },
                success: function (response) {
                    if (response.status === 1) {
                        toastr.success(response.message);
                        $('#cart-count').text(response.count); // Update the cart count

                        // Optionally update the cart sidebar or UI
                        setTimeout(function () {
                            cartSidebar.classList.add('open');
                            loadCartItems();
                        }, 1500);
                    } else {
                        toastr.error('Failed to add to cart. Please try again.');
                    }
                },
                error: function (xhr, status, error) {
                    toastr.error('Error occurred while adding the product to the cart.');
                    console.log('Error:', error);
                }
            });
        });

        // Buy Now functionality
        $('#buy-now-btn').on('click', function(e) {
            e.preventDefault();

            // Check if the user is logged in
            if (!checkLogin()) {
                return; // Stop further execution if not logged in
            }

            // Check if the product requires both color and size selection
            var categoryId = "{{ $product->category_id }}";
            if (categoryId != 3) {
                if (!selectedColorId || !selectedSizeId) {
                    toastr.error('Please select both size and color before adding to cart.');
                    return;
                }
            } else {
                if (!selectedSizeId) {
                    toastr.error('Please select a size before adding to cart.');
                    return;
                }
            }

            var qty = $('#quantity').val();
            var product_id = "{{ $product->id }}";
            var priceText = $('#current-price').text(); // Use .text() to get the price
            var price = parseFloat(priceText.replace('INR', '').trim()); // Remove "INR" and convert to a number
            var t_price = price * qty;

            $.ajax({
                url: "{{ route('cart.store') }}", // Adjust the route if necessary
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: product_id,
                    size_id: selectedSizeId, // Pass the selected size ID
                    color_id: categoryId != 3 ? selectedColorId : null, // Pass the selected color ID only if required
                    qty: qty,
                    price: price
                },
                success: function(response) {
                    if (response.status === 1) {
                        // Redirect to checkout page after successfully adding to cart
                        window.location.href = "{{ route('cart.checkout') }}";
                    } else {
                        toastr.error('Failed to add to cart. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Error occurred while processing the checkout.');
                    console.log('Error:', error);
                }
            });
        });

    });
</script>

<script type="text/javascript">
    $(document).on('click', '.toggle-heart', function () {
        if (!userId) {
            toastr.warning('Please log in first'); // Show login prompt as a toast
            return; // Stop execution if user is not logged in
        }

        var heartIcon = $(this);
        var productId = heartIcon.data('product-id');

        // Determine if the icon is currently in the "wishlisted" state
        var action = heartIcon.hasClass('fa-solid') ? 'remove' : 'add';

        // Get the category ID (Assuming it's stored as a data attribute or available in the DOM)
        var categoryId = heartIcon.data('category-id'); // Ensure this attribute exists in your HTML

        // Initialize color ID
        var selectedColorId = null;

        // Get the selected color ID only if category ID is not 3
        if (categoryId !== 3) {
            selectedColorId = $('.color-circle.selected').data('color-id');
            if (!selectedColorId) {
                selectedColorId = $('.color-circle').first().data('color-id'); // Default to the first color
            }
        }

        // Get the selected size ID
        var selectedSizeId = $('.sizenum.selected').data('size-id');
        if (!selectedSizeId) {
            selectedSizeId = $('.sizenum').first().data('size-id'); // Default to the first size
        }

        $.ajax({
            url: '{{ url("wishlist/toggle") }}', // Assuming toggle endpoint
            method: 'POST',
            data: {
                product_id: productId,
                color_id: selectedColorId, // Use color_id only if applicable
                size_id: selectedSizeId,   // Use size_id
                action: action,            // Send the action ('add' or 'remove')
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.status === 1) {
                    // Toggle icon and show message based on action
                    if (action === 'add') {
                        heartIcon.removeClass('fa-regular').addClass('fa-solid red-heart');
                        toastr.success('Added to wishlist');
                    } else {
                        heartIcon.removeClass('fa-solid red-heart').addClass('fa-regular');
                        toastr.success('Removed from wishlist');
                    }
                } else {
                    // Show error message
                    toastr.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                toastr.error('Error occurred while processing your request.');
                console.log('Error:', error);
            }
        });
    });
</script>



@endpush
