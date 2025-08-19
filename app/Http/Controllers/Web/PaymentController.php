<?php

namespace App\Http\Controllers\Web;

use Razorpay\Api\Api;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class PaymentController extends Controller
{
    /**
     * Initiates Razorpay payment for an order.
     */
    public function initiatePayment($encryptedOrderId)
    {
        try {
            // Decrypt the order ID
            $orderId = Crypt::decryptString($encryptedOrderId);

            $order = Order::findOrFail($orderId);

            // Fetch categories
            $categories = Category::with(['subcategories' => function ($query) {
                $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories
            }])
            ->where('status', 1)->orderBy('order_by', 'asc') // Only active categories
            ->get();

            if (!class_exists('Razorpay\Api\Api')) {
                throw new \Exception('Razorpay SDK not found. Ensure it is installed.');
            }

            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            $amount = $order->gtotal * 100; // Amount in paise

            $razorpayOrder = $api->order->create([
                'receipt' => (string) $orderId,
                'amount' => $amount,
                'currency' => 'INR',
                'payment_capture' => 1,
            ]);

            $order->update(['razorpay_order_id' => $razorpayOrder->id]);
            $title = 'Pay Now';
            return view('payment.razorpay', [
                'key' => env('RAZORPAY_KEY'),
                'amount' => $amount,
                'name' => $order->name,
                'razorpayOrderId' => $razorpayOrder->id,
                'orderId' => $order->id,
                'email' => $order->email,
                'phone' => $order->phone,
                'title' => $title,
                'order' => $order,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to initiate Razorpay payment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to initiate payment: ' . $e->getMessage());
        }
    }


    /**
     * Verifies Razorpay payment after completion.
     */
    public function verifyPayment(Request $request)
    {
        try {
            // Initialize Razorpay API
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            // Validate Razorpay signature
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];
            $api->utility->verifyPaymentSignature($attributes);

            // Fetch the order using Razorpay order ID
            $order = Order::where('razorpay_order_id', $request->razorpay_order_id)->firstOrFail();

            // Update the order status to "paid"
            $order->update([
                'payment_status' => 'paid',
                'payment_method' => 'razorpay',
            ]);

            // Return JSON response for success
            return response()->json([
                'success' => true,
                'redirect_url' => route('order.success', ['encryptedOrderId' => Crypt::encryptString($order->id)]),
            ]);
        } catch (\Exception $e) {
            // Return JSON response for failure
            return response()->json([
                'success' => false,
                'message' => 'Payment Verification Failed: ' . $e->getMessage(),
            ]);
        }
    }

}
