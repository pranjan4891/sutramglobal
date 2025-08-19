<?php

namespace App\Http\Controllers\Web;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;

use App\Models\SubCategory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;


class OrderController extends Controller
{
    //

    public function processOrder(Request $request)
    {
        // Validate the input
        $request->validate([
            'address_id' => 'required|exists:customer_addresses,id', // Validate address
        ]);

        $userId = Auth::id();

        // Retrieve cart items
        $cartItems = Cart::where('user_id', $userId)->with('product')->get();
        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty!',
            ], 400); // Bad request
        }

        try {
            // Retrieve the selected address
            $address = DB::table('customer_addresses')->where('id', $request->address_id)->first();
            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid address selected.',
                ], 400);
            }

            // Calculate the subtotal

            $subTotal = $cartItems->sum(fn($item) => $item->price * $item->qty);
            $discount = 0;
            $grandTotal = 0;
            $deliveryCharges = 0; // Modify as needed
            $couponId = null;
            
            // Check for a valid coupon within the last 2 minutes
            $couponUsage = DB::table('coupon_uses')->where('user_id', $userId)->where('created_at', '>=', Carbon::now()->subMinutes(2))->latest()->first();


            if ($couponUsage) {
                $coupon = DB::table('coupons')->where('id', $couponUsage->coupon_id)->first();
                if ($coupon) {
                    $couponId = $coupon->id;

                    // Calculate the total cart amount
                    
                    // Convert allowed category IDs to an array (if not null/empty)
                    $allowedCategories = !empty($coupon->allow_category) ? explode(',', $coupon->allow_category) : null;
                    
                            $totalEligibleAmount = 0;
                    
                            foreach ($cartItems as $item) {
                                // Get the product category
                                $product = Product::find($item->product_id);
                                $itemTotal = $item->price * $item->qty;
                    
                                // If allow_category is NULL, apply coupon to all products
                                if ($allowedCategories === null || ($product && in_array($product->category_id, $allowedCategories))) {
                                    $totalEligibleAmount += $itemTotal;
                    
                                    // Apply discount based on coupon type
                                    if ($coupon->discount_type === 'percentage') {
                                        $discount += ($itemTotal * $coupon->discount_value) / 100;
                                    } elseif ($coupon->discount_type === 'fixed') {
                                        $discount = min($coupon->discount_value, $totalEligibleAmount);
                                    }
                                }
                            }
                        }
                    }
                    
            // Calculate the grand total
            $deliveryCharges = 0; // Static value or calculate dynamically
            $grandTotal = $subTotal - $discount + $deliveryCharges;




            // Generate a unique order ID
            $lastOrder = Order::latest()->first();
            $lastOrderId = $lastOrder ? $lastOrder->id : 0;
            $uniqueOrderId = 'ORDSG' . str_pad($lastOrderId + 1, 10, '0', STR_PAD_LEFT);

            $phone = $address->alternate_mobile ?? $address->mobile ?? '';

            // Create the order
            $order = Order::create([
                'unique_order_id' => $uniqueOrderId,
                'customer_id' => $userId,
                'name' => $address->name ?? 'Guest',
                'email' => $address->email ?? 'guest@example.com',
                'phone' => $phone,
                'country' => $address->country ?? 'India',
                'state' => $address->state ?? '',
                'city' => $address->city ?? '',
                'zip' => $address->zip_code ?? '',
                'address' => $address->address1 ?? '',
                'date' => now(),
                'sub_total' => $subTotal,
                'discount_price' => $discount,
                'shipping_charge' => $deliveryCharges,
                'gtotal' => $grandTotal,
                'coupon_id' => $couponId, // Save applied coupon ID
                'cgst' => 0,
                'sgst' => 0,
                'igst' => 0,
                'payment_method' => 'none',
                'payment_status' => 'pending',
                'order_status' => 'pending',
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                $colorName = $cartItem->color_id ? DB::table('colors')->where('id', $cartItem->color_id)->value('name') : null;
                $sizeCode = $cartItem->size_id ? DB::table('sizes')->where('id', $cartItem->size_id)->value('code') : null;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'title' => $cartItem->product->title ?? 'N/A',
                    'image' => $cartItem->product->image_1 ?? 'default.png',
                    'sku' => $cartItem->product->sku ?? '',
                    'color_id' => $cartItem->color_id ?? null,
                    'colorname' => $colorName,
                    'size_id' => $cartItem->size_id ?? null,
                    'sizecode' => $sizeCode,
                    'price' => $cartItem->price ?? 0,
                    'qty' => $cartItem->qty ?? 0,
                    'total_price' => ($cartItem->price ?? 0) * ($cartItem->qty ?? 0),
                ]);
            }

            // Encrypt the order ID for the redirect URL
            $encryptedOrderId = Crypt::encryptString($order->id);

            // Return JSON response with the payment page redirect URL
            return response()->json([
                'success' => true,
                'redirect_url' => route('payment.page', ['encryptedOrderId' => $encryptedOrderId]),
            ]);

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Order Processing Error: ', ['error' => $e->getMessage()]);

            // Return a failure response
            return response()->json([
                'success' => false,
                'message' => 'Failed to process the order. Please try again.',
            ], 500);
        }
    }
    public function processOrder_BKP(Request $request)
    {
        // Validate the input
        $request->validate([
            'address_id' => 'required|exists:customer_addresses,id', // Validate address
        ]);

        $userId = Auth::id();

        // Retrieve cart items
        $cartItems = Cart::where('user_id', $userId)->with('product')->get();
        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty!',
            ], 400); // Bad request
        }

        try {
            // Retrieve the selected address
            $address = DB::table('customer_addresses')->where('id', $request->address_id)->first();
            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid address selected.',
                ], 400);
            }

            // Calculate the subtotal
            $subTotal = $cartItems->sum(fn($item) => $item->price * $item->qty);

            // Check for a valid coupon within the last 2 minutes
            $couponUsage = DB::table('coupon_uses')->where('user_id', $userId)->where('created_at', '>=', Carbon::now()->subMinutes(2))->latest()->first();

            $discount = 0; // Initialize discount
            $couponId = null;

            if ($couponUsage) {
                $coupon = DB::table('coupons')->where('id', $couponUsage->coupon_id)->first();

                if ($coupon) {
                    $couponId = $coupon->id;
                    // Calculate discount based on coupon type
                    if ($coupon->discount_type === 'percentage') {
                        $discount = round(($subTotal * $coupon->discount_value) / 100);
                    } elseif ($coupon->discount_type === 'fixed') {
                        $discount = min($coupon->discount_value, $subTotal);
                    }
                }
                
                
            }

            // Calculate the grand total
            $deliveryCharges = 0; // Static value or calculate dynamically
            $grandTotal = $subTotal - $discount + $deliveryCharges;




            // Generate a unique order ID
            $lastOrder = Order::latest()->first();
            $lastOrderId = $lastOrder ? $lastOrder->id : 0;
            $uniqueOrderId = 'ORDSG' . str_pad($lastOrderId + 1, 10, '0', STR_PAD_LEFT);

            $phone = $address->alternate_mobile ?? $address->mobile ?? '';

            // Create the order
            $order = Order::create([
                'unique_order_id' => $uniqueOrderId,
                'customer_id' => $userId,
                'name' => $address->name ?? 'Guest',
                'email' => $address->email ?? 'guest@example.com',
                'phone' => $phone,
                'country' => $address->country ?? 'India',
                'state' => $address->state ?? '',
                'city' => $address->city ?? '',
                'zip' => $address->zip_code ?? '',
                'address' => $address->address1 ?? '',
                'date' => now(),
                'sub_total' => $subTotal,
                'discount_price' => $discount,
                'shipping_charge' => $deliveryCharges,
                'gtotal' => $grandTotal,
                'coupon_id' => $couponId, // Save applied coupon ID
                'cgst' => 0,
                'sgst' => 0,
                'igst' => 0,
                'payment_method' => 'none',
                'payment_status' => 'pending',
                'order_status' => 'pending',
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                $colorName = $cartItem->color_id ? DB::table('colors')->where('id', $cartItem->color_id)->value('name') : null;
                $sizeCode = $cartItem->size_id ? DB::table('sizes')->where('id', $cartItem->size_id)->value('code') : null;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'title' => $cartItem->product->title ?? 'N/A',
                    'image' => $cartItem->product->image_1 ?? 'default.png',
                    'sku' => $cartItem->product->sku ?? '',
                    'color_id' => $cartItem->color_id ?? null,
                    'colorname' => $colorName,
                    'size_id' => $cartItem->size_id ?? null,
                    'sizecode' => $sizeCode,
                    'price' => $cartItem->price ?? 0,
                    'qty' => $cartItem->qty ?? 0,
                    'total_price' => ($cartItem->price ?? 0) * ($cartItem->qty ?? 0),
                ]);
            }

            // Encrypt the order ID for the redirect URL
            $encryptedOrderId = Crypt::encryptString($order->id);

            // Return JSON response with the payment page redirect URL
            return response()->json([
                'success' => true,
                'redirect_url' => route('payment.page', ['encryptedOrderId' => $encryptedOrderId]),
            ]);

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Order Processing Error: ', ['error' => $e->getMessage()]);

            // Return a failure response
            return response()->json([
                'success' => false,
                'message' => 'Failed to process the order. Please try again.',
            ], 500);
        }
    }



    public function orderSuccess($encryptedOrderId)
    {
        try {
            // Decrypt the encrypted order ID
            $orderId = Crypt::decryptString($encryptedOrderId);
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Invalid order ID.');
        }

        $order = Order::with('items')->find($orderId);
        $userId = Auth::id();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        // Update order payment status and order status
        $order->update([
            'payment_status' => 'paid',
            'order_status' => 'inprocess',
        ]);

        // Decrement product quantities based on product_id, color_id, and size_id
        foreach ($order->items as $item) {
            $variant = ProductVariant::where('product_id', $item->product_id)
                ->where('color_id', $item->color_id)
                ->where('size_id', $item->size_id)
                ->first();

            if ($variant) {
                $variant->decrement('quantity', $item->qty);
            }
        }

        // Clear user's cart
        Cart::where('user_id', $userId)->delete();

        // Fetch categories for navigation or UI
        $title = 'Order Success';
        $categories = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc');
        }])
        ->where('status', 1)->orderBy('order_by', 'asc')
        ->get();

        // Send notifications only if not already sent
        if (!$order->notification_sent) {
            $emailData = [
                'userinfo' => [
                    'name' => $order->name,
                ],
                'order' => $order,
                'item' => $order->items,
            ];

            // Send order confirmation email
            Mail::send('emails.order_success', ['data' => $emailData], function ($message) use ($order) {
                $message->to(Auth::user()->email, $order->name)
                    ->subject('Order Confirmation - ' . $order->unique_order_id);
            });

            // Send order success text message
            $this->sendOrderSuccessMessage(Auth::user()->mobile, $order->unique_order_id);

            $adminMobile = '9289090121';
            // Send order details to admin
            $this->sendAdminOrderSuccessMessage($adminMobile, $order->unique_order_id);

            // Mark notification as sent
            $order->update(['notification_sent' => true]);
        }

        return view('web.success', [
            'order' => $order,
            'title' => $title,
            'categories' => $categories
        ]);
    }


    private function sendOrderSuccessMessage($mobile, $orderId)
    {
        $api_url = 'https://nimbusit.biz/Api/smsapi/JsonPost';

        // Prepare the message using the approved template
        $message = "Thank you for shopping with us! Your order #{$orderId} has been successfully placed. For more information, reach us at support@sutramglobal.com.";

        // API payload
        $data = [
            "FORMAT" => "1",
            "USERNAME" => "devicediskapibiz", // Replace with your Nimbus username
            "PASSWORD" => "vwmc8457VW",       // Replace with your Nimbus password
            "SENDERID" => "SUTRAM",           // Replace with your sender ID
            "TEXTTYPE" => "TEXT",
            "SMSTEXT" => $message,
            "TemplateID" => "1707173702875873931", // Updated Template ID
            "EntityID" => "1701173276915726387",   // Updated Entity ID
            "MOBLIST" => [$mobile],                // Add the user's mobile number
        ];

        // Convert payload to JSON
        $json_data = json_encode($data);

        // Send SMS using cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'data=' . urlencode($json_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        // Log the response for debugging purposes
        Log::info('Nimbus SMS API Response: ' . $response);
    }
    private function sendAdminOrderSuccessMessage($mobile, $orderId)
    {
        $api_url = 'https://nimbusit.biz/Api/smsapi/JsonPost';

        // Prepare the message using the approved template
        $message = "New Order Received! Order ID: #{$orderId} has been placed. Please process and prepare for shipping. -Sutramglobal";

        // API payload
        $data = [
            "FORMAT" => "1",
            "USERNAME" => "devicediskapibiz", // Replace with your Nimbus username
            "PASSWORD" => "vwmc8457VW",       // Replace with your Nimbus password
            "SENDERID" => "SUTRAM",           // Replace with your sender ID
            "TEXTTYPE" => "TEXT",
            "SMSTEXT" => $message,
            "TemplateID" => "1707173744409267535", // Updated Template ID
            "EntityID" => "1701173276915726387",   // Updated Entity ID
            "MOBLIST" => [$mobile],                // Add the user's mobile number
        ];

        // Convert payload to JSON
        $json_data = json_encode($data);

        // Send SMS using cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'data=' . urlencode($json_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        // Log the response for debugging purposes
        Log::info('Nimbus SMS API Response: ' . $response);
    }

    public function orderFailed($encryptedOrderId)
    {
        try {
            // Decrypt the encrypted order ID
            $orderId = Crypt::decryptString($encryptedOrderId);
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Invalid order ID.');
        }

        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        // Update order payment status and order status
        $order->update([
            'payment_status' => 'pending',
            'order_status' => 'failed',
        ]);

        $title = 'Order Failed';
        $categories = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories
        }])
        ->where('status', 1)->orderBy('order_by', 'asc') // Only active categories
        ->get();

        return view('web.failed', [
            'order' => $order,
            'title' => $title,
            'categories' => $categories
        ]);
    }


    public function orderList()
    {
        $title = 'Order List';

        // Fetch active categories with subcategories
        $categories = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories
        }])
        ->where('status', 1)->orderBy('order_by', 'asc') // Only active categories
        ->get();

        // Fetch orders with their associated items
        $orders = Order::where('customer_id', auth()->user()->id)
            ->with('items') // Assuming the relationship is defined
            ->orderBy('created_at', 'desc')
            ->get();

        return view('web.myorder', [
            'title' => $title,
            'categories' => $categories,
            'orders' => $orders
        ]);
    }

    public function orderSummary($encryptedOrderId)
    {
        try {
            // Decrypt the order ID
            $orderId = Crypt::decryptString($encryptedOrderId);
        } catch (\Exception $e) {
            // Handle invalid or tampered encrypted ID
            return redirect()->route('home')->with('error', 'Invalid order ID.');
        }

        $title = 'Order Summary';

        // Fetch active categories and subcategories
        $categories = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories
        }])
        ->where('status', 1)
        ->orderBy('order_by', 'asc') // Only active categories
        ->get();

        // Fetch the order and its items
        $order = Order::with('items')->find($orderId);

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        return view('web.order_summary', [
            'title' => $title,
            'categories' => $categories,
            'order' => $order
        ]);
    }


    public function cancelOrder(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            if ($order->order_status !== 'inprocess') {
                return response()->json(['status' => 'error', 'message' => 'Only in-process orders can be cancelled.']);
            }

            $order->order_status = $request->order_status; // Set to 'cancelled'
            $order->save();

            return response()->json(['status' => 'success', 'message' => 'Order has been cancelled successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to cancel the order.']);
        }
    }
    public function returnProduct(Request $request, $itemId)
    {
        // Find the order item by its ID
        $orderItem = OrderItem::find($itemId);

        if (!$orderItem) {
            return response()->json(['status' => 'error', 'message' => 'Order item not found.'], 404);
        }

        // Check if the item is already marked as returned
        if ($orderItem->is_return) {
            return response()->json(['status' => 'error', 'message' => 'This item has already been returned.'], 400);
        }

        // Update the is_return field
        $orderItem->is_return = 1;
        $orderItem->save();

        return response()->json(['status' => 'success', 'message' => 'The product has been marked as returned.']);
    }


    public function generateInvoice($id)
    {
        // Fetch the order details
        $order = Order::where('id', $id)->first();

        if (!$order) {
            return redirect()->route('admin.orders.list')->with('error', 'Order not found.');
        }

        // Fetch related order items
        $orderItems = OrderItem::where('order_id', $order->id)->get();

        // Prepare data for the invoice
        $invoiceData = [
            'unique_order_id' => $order->unique_order_id,
            'name' => $order->name,
            'phone' => $order->phone,
            'address' => $order->address,
            'city' => $order->city,
            'state' => $order->state,
            'country' => $order->country,
            'zip' => $order->zip,
            'sub_total' => $order->sub_total,
            'discount_price' => $order->discount_price,
            'shipping_charge' => $order->shipping_charge,
            'cgst' => $order->cgst,
            'sgst' => $order->sgst,
            'igst' => $order->igst,
            'gtotal' => $order->gtotal,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'order_status' => $order->order_status,
            'date' => $order->created_at,
            'products' => $orderItems,
            'logourl'=>"https://sutramglobal.com/public/web/imagrs/SutramBlack.png"
        ];

        // Load the invoice view
        $pdf = PDF::loadView('admin.order.invoice', ['invoice' => $invoiceData]);

        // Return the PDF for download
        return $pdf->download('invoice-' . $order->unique_order_id . '.pdf');
    }


}
