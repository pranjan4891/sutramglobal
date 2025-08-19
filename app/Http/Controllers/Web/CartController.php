<?php

namespace App\Http\Controllers\Web;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Country;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\Coupon;
use App\Models\CouponUse;
use App\Models\SubCategory;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $product_id = $request->product_id;
        $size = $request->size_id;
        $sku = $request->sku;
        $color = $request->color_id;
        $price = $request->price;
        $qty = $request->qty;

        $user_id = Auth::id();
        $session_id = session()->getId();

        // Identify the cart by either user_id or session_id
        $cartQuery = Cart::where('product_id', $product_id)
                        ->where('size_id', $size)
                        ->where('color_id', $color);

        if ($user_id) {
            $cartQuery->where('user_id', $user_id);
        } else {
            $cartQuery->where('session_id', $session_id);
        }

        $cart = $cartQuery->first();

        if ($cart) {
            $cart->qty += $qty;
        } else {
            $cart = new Cart;
            $cart->user_id = $user_id;
            $cart->session_id = $session_id;
            $cart->product_id = $product_id;
            $cart->size_id = $size;
            $cart->sku = $sku;
            $cart->color_id = $color;
            $cart->price = $price;
            $cart->qty = $qty;
        }

        $cart->save();

        if ($user_id) {
            $wishlist = Wishlist::where('user_id', $user_id)
                                ->where('product_id', $product_id)
                                ->where('size_id', $size)
                                ->where('color_id', $color)
                                ->first();

            if ($wishlist) {
                $wishlist->delete();
            }
        }

        $cartCount = $user_id
            ? Cart::where('user_id', $user_id)->count()
            : Cart::where('session_id', $session_id)->count();

        return response()->json([
            'count' => $cartCount,
            'status' => 1,
            'message' => 'Success! Added to cart.',
        ]);
    }



    public function giftstore(Request $request)
    {
        // dd($request->input());
        $giftProduct_id = $request->gift_product_id;
        $product_id = $request->product_id;
        // $size = $request->size_id;
        // $sku = $request->sku;
        // $color = $request->color_id;
        $price = $request->price;
        $qty = $request->qty;
        $user_id = Auth::id();

        // Find existing cart item with the same product_id, size, and color
        $cart = Cart::where(['user_id'=> $user_id,'product_id'=> $giftProduct_id, 'parent_id'=>$product_id])->first();

        if ($cart) {
            // If item exists, update quantity
            $cart->qty = $qty;  // Add the quantity
        } else {
            // If not, create a new cart item
            $cart = new Cart;
            $cart->user_id = $user_id;
            $cart->product_id = $giftProduct_id;
            $cart->parent_id = $product_id;
            // $cart->size_id = $size;
            // $cart->sku = $sku;
            // $cart->color_id = $color;
            $cart->price = 0.00;
            $cart->type = '1';
            $cart->qty = 1;
        }

        $cart->save();


        // Return response as JSON with updated cart count
        $result = [
            'count' => Cart::where('user_id', $user_id)->count(),
            'status' => 1,
            'message' => 'Success! Added to cart successfully.',
        ];

        return response()->json($result);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $qty = $request->updated_qty;
        $cart = Cart::where('id', $id)->update(['qty' => $qty]);
        return response()->json('success');
    }

    public function delete(Request $request, Cart $cart)
    {
        $id = $request->id;
        $cart = Cart::where('id', $id)->first();
        $cart->delete();
        return response()->json('success');
    }


    public function get_mini_cart_list(Request $request)
    {
        $user_id = Auth::id();
        $session_id = session()->getId();

        $carts = $user_id
            ? Cart::where('user_id', $user_id)
                ->with('product', 'color', 'size')
                ->get()
            : Cart::where('session_id', $session_id)
                ->with('product', 'color', 'size')
                ->get();

        $html = '';
        $total = 0;
        $totalItems = 0;

        if ($carts->isNotEmpty()) {
            foreach ($carts as $cart) {
                $variant = ProductVariant::where('product_id', $cart->product_id)
                    ->where('color_id', $cart->color_id)
                    ->where('size_id', $cart->size_id)
                    ->first();

                $availableStock = $variant ? $variant->quantity : 0;

                $cart->qty = min($cart->qty, $availableStock);
                $cart->save();

                $qty = $cart->qty;
                $price = $cart->price;
                $productPrice = $price * $qty;
                $total += $productPrice;
                $totalItems += $qty;

                $html .= '<div class="cart-item" data-id="' . $cart->id . '">';
                $html .= '<img src="' . isImage('products', @$cart->product->image_2) . '" alt="Product Image">';
                $html .= '<div class="item-details">';
                $html .= '<div class="d-flex spacecart">';
                $html .= '<div><p class="item-name">' . $cart->product->title . '</p></div>';
                $html .= '<div><i class="fas fa-trash delete-btn" title="Remove" onclick="removeCartItem(' . $cart->id . ')"></i></div>';
                $html .= '</div>';

                if ($cart->type == '0') {
                    $html .= '<p>Size : <span>' . ($cart->size ? $cart->size->code : 'N/A') . '</span></p>';
                }

                if ($cart->color) {
                    $html .= '<p>Color : <span>' . $cart->color->name . '</span></p>';
                }

                if ($cart->type == '0') {
                    $html .= '<div class="d-flex spacecart">';
                    $html .= '<div class="quantity-control">';
                    $html .= '<button onclick="updateCartItemQty(this, ' . $cart->id . ', false)">-</button>';
                    $html .= '<span class="item-quantity">' . $qty . '</span>';
                    $html .= '<button onclick="updateCartItemQty(this, ' . $cart->id . ', true)">+</button>';
                    $html .= '</div>';
                    $html .= '<div>INR ' . number_format($productPrice, 2) . '</div>';
                    $html .= '</div>';
                } else {
                    $html .= '<div class="d-flex spacecart">Free Gift</div>';
                }

                $html .= '</div></div>';
            }
            $html .= '<hr>';
        } else {
            $html .= '<p>Your cart is empty!</p>';
        }

        return response()->json([
            'html' => $html,
            'totalItems' => $totalItems,
            'subtotal' => number_format($total, 2),
        ]);
    }



    public function updateCartItemQty(Request $request)
    {
        $cartId = $request->cart_id;
        $newQty = $request->qty;

        // Fetch the cart item and related product variant
        $cart = Cart::with(['product', 'color', 'size'])->find($cartId);

        if (!$cart) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }

        $variant = ProductVariant::where('product_id', $cart->product_id)
            ->where('color_id', $cart->color_id)
            ->where('size_id', $cart->size_id)
            ->first();

        if (!$variant) {
            return response()->json(['error' => 'Product variant not found'], 404);
        }

        // Validate the new quantity against the available stock
        if ($newQty > $variant->quantity) {
            return response()->json([
                'error' => 'Requested quantity exceeds available stock',
                'maxQty' => $variant->quantity,
            ], 422);
        }

        // Update the cart quantity
        $cart->qty = $newQty;
        $cart->save();

        return response()->json(['success' => true, 'message' => 'Cart updated successfully']);
    }


    public function update_cart(Request $request)
    {
        $cart = Cart::find($request->cart_id);
        if ($cart) {
            $cart->qty = $request->qty;
            $cart->save();
        }
        return response()->json(['success' => true]);
    }

    public function remove_cart(Request $request)
    {
        Cart::destroy($request->cart_id);
        return response()->json(['success' => true]);
    }

    public function checkout()
    {
        // Redirect guest users to login before checkout
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to proceed to checkout.');
        }

        $data['title'] = 'Checkout';

        // Get authenticated user's cart
        $data['carts'] = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();

        // Get active categories and subcategories
        $data['categories'] = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc');
        }])
        ->where('status', 1)
        ->orderBy('order_by', 'asc')
        ->get();

        return view('web.checkout', $data);
    }

    public function getAddresses(Request $request)
    {
        $userId = auth()->id(); // Get the authenticated user's ID
        $addresses = CustomerAddress::where('user_id', $userId)->get(); // Fetch addresses for the logged-in user

        // Return the data as JSON
        return response()->json($addresses);
    }

    public function setDefaultAddress(Request $request)
    {
        $userId = Auth::id();
        $selectedAddressId = $request->input('address_id');

        // Reset all addresses for the user to have status 0
        CustomerAddress::where('user_id', $userId)->update(['status' => 0]);

        // Set the selected address to have status 1 (default)
        $updated = CustomerAddress::where('id', $selectedAddressId)
                ->where('user_id', $userId)
                ->update(['status' => 1]);

        if ($updated) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'error' => 'Could not update address.']);
        }
    }

    public function getDefaultAddress()
    {
        $userId = Auth::id();
        $defaultAddress = CustomerAddress::where('user_id', $userId)->where('status', 1)->first();

        return response()->json($defaultAddress);
    }

    public function getCartItems()
    {
        $userId = Auth::id();

        // Fetch cart items with product, color, and size details
        $cartItems = Cart::where('user_id', $userId)
            ->with(['product', 'color', 'size']) // Assuming relationships are defined in the Cart model
            ->get();

        // Map each cart item with relevant product, color, and size details
        $items = $cartItems->map(function ($cartItem) {
            return [
                'name' => $cartItem->product->title, // Product title
                'price' => $cartItem->price,
                'quantity' => $cartItem->qty,
                'sizeCode' => $cartItem->size ? $cartItem->size->code : 'N/A', // Size code or 'N/A' if not available
                'colorName' => $cartItem->color ? $cartItem->color->name : 'N/A', // Color name or 'N/A' if not available
                'image_url' => asset('public/uploads/products/' . $cartItem->product->image_1), // Image URL
                'type' => $cartItem->type,
            ];
        });

        return response()->json(['items' => $items]);
    }

    public function success($orderId)
    {
        $data['title'] = 'Payment Success';
        $data['orderid'] = $orderId;
        return view('web.success',$data);
    }

    public function failed($orderId)
    {
        $data['title'] = 'Payment Failed';
        $data['orderid'] = $orderId;
        return view('web.failed',$data);
    }

    public function fetchCoupons()
    {
         // Fetch active coupons where the end date is greater than or equal to today
        $coupons = Coupon::where('status', 1)
        ->whereDate('end_date', '>=', now()) // Check if the coupon is still valid
        ->get();

        // Return the results as a JSON response
        return response()->json($coupons);
    }
    // Coupon By Suresh Yadav--------------------------------
         public function saveCouponUsage(Request $request)
            {
                // Validate the incoming request
                $validated = $request->validate([
                    'coupon_id' => 'required|exists:coupons,id',
                    'user_id'   => 'required|exists:users,id',
                ]);

                // Get Coupon Details
                $coupon = Coupon::where('id', $validated['coupon_id'])
                                ->where('status', 1)
                                ->whereDate('start_date', '<=', now())
                                ->whereDate('end_date', '>=', now())
                                ->first();

                if (!$coupon) {
                    return response()->json(['message' => 'Invalid or expired coupon.'], 400);
                }

                // Convert allowed category IDs to an array (if not null/empty)
                $allowedCategories = !empty($coupon->allow_category) ? explode(',', $coupon->allow_category) : null;

                // Get Cart Items for the User
                $cartItems = Cart::where('user_id', $validated['user_id'])->get();

                $totalCartAmount = 0;
                $discountAmount = 0;
                $totalEligibleAmount = 0;

                foreach ($cartItems as $item) {
                    $totalCartAmount += $item->price * $item->qty; // Total cart value

                    // Get the product category
                    $product = Product::find($item->product_id);

                    // If allow_category is NULL, apply coupon to all products
                    if ($allowedCategories === null || ($product && in_array($product->category_id, $allowedCategories))) {
                        $itemTotal = $item->price * $item->qty;
                        $totalEligibleAmount += $itemTotal;

                        if ($coupon->discount_type == 'percentage') {
                            $discountAmount += ($itemTotal * $coupon->discount_value) / 100;
                        }
                    }
                }

                // Check if the coupon is applicable
                if ($totalEligibleAmount == 0) {
                    // return response()->json(['message' => 'Coupon is not applicable to the selected products.'], 400);
                    return response()->json(['message' => 'Sorry, this coupon is applicable only on purchase of Perfume bottles.'], 400);
                }

                // Calculate remaining total after discount
                $remainingTotal = $totalCartAmount - $discountAmount;

                // Save the coupon usage
                $couponUse = new CouponUse();
                $couponUse->coupon_id = $validated['coupon_id'];
                $couponUse->user_id = $validated['user_id'];
                $couponUse->save();

                return response()->json([
                    'message'         => 'Coupon applied successfully.',
                    'discount'        => number_format($discountAmount, 2),
                    'remaining_total' => number_format($remainingTotal, 2)
                ], 200);
            }


    // End Coupon By Suresh Yadav--------------------------------


    public function saveCouponUsage_BKP(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'coupon_id' => 'required|exists:coupons,id',
            'user_id'   => 'required|exists:users,id',
        ]);

        // Save the coupon usage
        $couponUse = new CouponUse();
        $couponUse->coupon_id = $validated['coupon_id'];
        $couponUse->user_id = $validated['user_id'];
        $couponUse->save();

        return response()->json(['message' => 'Coupon usage recorded successfully.'], 200);
    }

}
