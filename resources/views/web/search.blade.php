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

    <!-- <div class="tab" style="padding-left: 15%;">
        @foreach($subCategories as $subCategory)
            <button class="tablinks" data-tab="tab_{{ $subCategory->id }}">
                <img class="card-img-top tablink" src="{{ isImage('subcategories', $subCategory->image) }}"
                    style="width: 103px; height: 64px;" alt="Card image cap">
                <p>{{ $subCategory->name }}</p>
            </button>
        @endforeach
    </div> -->

    <div id="products">
        <div class="row mt-3">
        @if($products->count() > 0)
        @foreach($products as $product)
            @php
                $prowishlists = App\Models\Wishlist::where('product_id', $product->id)->where('user_id', Auth::user()->id)->first();
            @endphp
            <div class="col-md-3">
                <div class="product-single-card">
                    <div class="p-1">
                        <button type="button" class="wishlist-button pull-right" style="display:flex;" data-id="{{ $product->id }}">
                            @if($prowishlists)
                                <i class="fa fa-heart" style="font-size:24px;"></i>
                            @else
                                <i class="far fa-heart" style="font-size:24px;"></i>
                            @endif
                        </button>
                    </div>
                    <div class="product-top-area">
                        <div class="product-img">

                            <div class="first-view">
                                <img src="{{ isImage('products', $product->image_1) }}" alt="logo" class="img-fluid">
                            </div>
                            <div class="hover-view">
                                <img src="{{ isImage('products', $product->image_2) }}" alt="logo" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="product-info">
                        <h6 class="product-category"><a href="{{ route('web.product.product_details', $product->slug) }}">{{ $product->title }}</a></h6>
                        <h6 class="product-title text-truncate"><a href="javascript:void(0)">{{ $product->sub_title }}</a></h6>
                        <form action="{{route('cart.store')}}" method="post" onsubmit="event.preventDefault();addToCartFromt(this);">
                            @csrf
                            @php
                                $authPrice = getAuthCompanyPrice($product->id);
                                $specialPrice = !empty($authPrice) ? $authPrice : $product->price
                            @endphp
                            <input type="hidden" class="form-control" name="product_id" value="{{$product->id}}"/>
                            <input type="hidden" class="form-control" name="qty" value="1"/>
                            <input type="hidden" class="form-control" name="price" value="{{ $specialPrice }}"/>
                            <input type="hidden" class="form-control" name="part_number" value="{{ $product->part_number }}"/>
                            <input type="hidden" class="form-control" name="sku" value="{{ $product->sku }}"/>
                            <input type="hidden" class="form-control" name="color" value=""/>
                            <div class="d-flex flex-wrap align-items-center py-2">
                                @if(!empty($authPrice))
                                    <div class="old-price">
                                        ₹{{ number_format($product->price, 2, '.', '') }}
                                    </div>
                                    <div class="new-price">
                                        ₹{{ number_format($authPrice, 2, '.', '') }}
                                    </div>
                                @else
                                    <div class="new-price">
                                        ₹{{ number_format($product->price, 2, '.', '') }}
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex align-items-center add-to-cart">
                                @if(Auth::user())
                                    @php
                                        $model = App\Models\Cart::where('user_id',Auth::user()->id)->where('product_id',$product->id)->first();
                                        $btn_type = (!empty($model)) ? 'button' : 'submit';
                                        $btn_class = (!empty($model)) ? 'success' : 'primary';
                                    @endphp
                                    <button type="{{ $btn_type }}" class="button-{{ $btn_class }}">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        @else
            <h5 class="text-center">No Products Found</h5>
        @endif
        </div>

    </div>

    <!-- Pagination Links -->
     <div class="row m-2">
        <div class="d-flex justify-content-center">
            {{ $products->appends(['keyword' => $keyword])->links() }}
        </div>
     </div>


@endsection

@push('sub-script')
    <script>
        $(document).ready(function() {
            $(".tablinks").click(function() {
                var tabId = $(this).data("tab");
                $(".tabcontent").hide();
                $(".tablinks").removeClass("active");
                $("#" + tabId).show();
                $(this).addClass("active");
            });
            $(".tablinks").first().click(); // Auto-click the first tab
        });
    </script>
@endpush
