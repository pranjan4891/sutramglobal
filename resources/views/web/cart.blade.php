@extends('web.layout.layout', ['pageTitle' => $title])
@section('contant')

<style>
        .cart-table th, .cart-table td {
            vertical-align: middle;
        }
        .cart-table img {
            width: 50px;
            height: 50px;
        }
        .quantity-input {
            width: 60px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        .quantity-buttons {
            display: flex;
            align-items: center;
        }
        .btn-quantity {
            padding: 0;
            width: 25px;
            height: 25px;
            line-height: 25px;
            border: 1px solid #ddd;
            background: #f8f9fa;
            font-size: 14px;
        }
        .total-price {
            font-weight: bold;
            color: #333;
        }
        .summary {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            background: #f8f9fa;
            margin-top: 0px;
        }
        .summary h4 {
            font-weight: bold;
        }
    </style>




    <div class="container mt-5" style="padding-bottom:100px">
    <h2 class="mb-4">Shopping Cart</h2>
    <div class="row">
        <div class="col-lg-8 col-md-12 mb-4">
            <div class="table-responsive">
                <table class="table cart-table">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Cart items will be appended here -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="summary">
                <h4>Summary</h4>
                <p>Total Items: <span class="cart_items_count">0</span></p>
                <p>Subtotal: ₹<span id="cart-total">500.00</span></p>
                <a href="{{ route('cart.checkout') }}" class="btn btn-primary btn-block">Proceed to Checkout</a>
            </div>
        </div>
    </div>
</div>


@endsection

@push('sub-script')

<script>
    function get_cart_list() {
        $.ajax({
            url: '{{ route("cart.get_cart_list") }}',
            method: 'post',
            dataType: 'json',
            data: {
                _token: "{{ csrf_token() }}",
                'user_id': "{{ Auth::user()->id }}"
            },
            success: function(response) {
                if (response.error) {
                    console.log(response.error);
                } else {
                    $('tbody').html(response.html);
                    $('.cart_items_count').text(response.count);
                    $('#cart-total').text(response.total);
                }
            }
        });
    }
    get_cart_list();
    // Example functions for quantity buttons
    function decreaseQuantity(button) {
        var $input = $(button).closest('.quantity-buttons').find('.quantity-input');
            var val = parseInt($input.val(), 10);
            if (val > 1) {
                $input.val(val - 1).trigger('change');
            }
    }

    function increaseQuantity(button) {
        var $input = $(button).closest('.quantity-buttons').find('.quantity-input');
            var val = parseInt($input.val(), 10);
            $input.val(val + 1).trigger('change');
    }
    function update_cart_items(e,id)
    {
        updated_qty = $(e).parent().find('input').val();
        $.ajax({
            url: '{{ route("cart.update") }}',
            type: 'post',
            data: {
                _token: "{{ csrf_token() }}",
                'id': id,
                'updated_qty':updated_qty
            },
            success:function(data){
                toastr.success('Cart Update Successfully!');
                get_cart_list();
            }
        });
    }
    function remove_cart_items(id)
    {
        $.ajax({
            url: '{{route("cart.delete")}}',
            method: 'post',
            data: {
                _token: "{{ csrf_token() }}",
                'id': id
            },
            success:function(data){
                toastr.success('Product Removed Successfully!');
                get_cart_list();
            }
        });
    };
</script>

@endpush
