<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use PDF;
use Illuminate\Support\Carbon;
use App\Services\ShiprocketService;
use Illuminate\Support\Facades\Log;

class OrdersController extends Controller
{
    public function index()
    {
        $data['title'] = 'Order';
        $data['action'] = 'List';
        return view('admin.order.list', $data);
    }

    public function getOderList(Request $request)
    {
        $columns = ['id', 'unique_order_id', 'name', 'order_status', 'payment_status', 'gtotal', 'date'];

        $query = Order::select('id', 'unique_order_id', 'name', 'order_status', 'payment_status', 'gtotal', 'date');

        $totalRecords = Order::count();

        if (!empty($request->input('search.value'))) {
            $searchValue = $request->input('search.value');
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'LIKE', "%$searchValue%")
                    ->orWhere('unique_order_id', 'LIKE', "%$searchValue%");
            });
        }

        $filteredRecords = $query->count();

        if ($request->has('order')) {
            $columnIndex = $request->input('order.0.column');
            $sortDirection = $request->input('order.0.dir');
            $query->orderBy($columns[$columnIndex], $sortDirection);
        } else {
            $query->orderBy('unique_order_id', 'desc');
        }

        if ($request->has('length') && $request->input('length') != -1) {
            $query->limit($request->input('length'))->offset($request->input('start'));
        }

        $orders = $query->get();

        $data = [];
        foreach ($orders as $i => $order) {
            $data[] = [
                'id' => $i + 1,
                'unique_order_id' => $order->unique_order_id,
                'name' => $order->name,
                'order_status' => $order->order_status,
                'payment_status' => $order->payment_status,
                'gtotal' => number_format($order->gtotal, 2),
                'date' => Carbon::parse($order->date)->format('Y-m-d'),
                'action' => '<a href="' . route('admin.orders.view', ['id' => $order->id]) . '" class="btn btn-warning btn-sm m-1">View</a>
                             <button class="btn btn-danger btn-sm m-1 btn_delete" data-id="' . $order->id . '">Delete</button>',
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $data,
        ]);
    }

    public function delete(Request $request)
    {
        $id = $request->id;

        try {
            // Find the order
            $order = Order::find($id);

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found.']);
            }

            // Delete associated order items
            OrderItem::where('order_id', $id)->delete();

            // Delete the order itself
            $order->delete();

            return response()->json(['success' => true, 'message' => 'Order deleted successfully.']);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json(['success' => false, 'message' => 'Failed to delete order.', 'error' => $e->getMessage()]);
        }
    }


    public function OrderViewId($id)
    {
        // Fetch order by ID
        $data['order'] = Order::find($id);

        // Check if order exists
        if (!$data['order']) {
            return redirect()->route('admin.orders.list')->with('error', 'Order not found.');
        }

        // Fetch order items and assign to order object
        $data['order']->products = OrderItem::where('order_id', $data['order']->id)->get();

        // Set title and action for the view
        $data['title'] = 'Order';
        $data['action'] = 'Details';

        // Return the view with the data
        return view('admin.order.orderview', $data);
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update the payment status
        $order->payment_status = $request->input('payment_status');
        $order->save();

        return response()->json(['message' => 'Payment status updated successfully']);
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

    // protected $shiprocketService;
    protected $shiprocketService;

    public function __construct(ShiprocketService $shiprocketService)
    {
        $this->shiprocketService = $shiprocketService;
    }
   /**
     * Update the status of an order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::with('items')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $request->validate([
            'order_status' => 'required|in:pending,inprocess,shipped,delivered,return',
        ]);

        $order->order_status = $request->input('order_status');
        $order->save();

        Log::info("Order ID {$order->id} status updated to {$order->order_status}");

        if ($order->order_status === 'shipped') {
            try {
                $fullName = $order->name;
                $nameParts = explode(' ', $fullName, 2);
                $firstName = $nameParts[0] ?? 'Customer';
                $lastName = $nameParts[1] ?? '';

                $email = User::where('id', $order->user_id)->value('email') ?? 'test@example.com';
                $phoneNumbers = explode(',', $order->phone);
                $phone = trim($phoneNumbers[0]);

                $orderData = [
                    'order_id' => $order->unique_order_id,
                    'order_date' => $order->created_at->format('m/d/Y'),
                    'pickup_location' => 'Primary',
                    'billing_customer_name' => $firstName,
                    'billing_last_name' => $lastName,
                    'billing_address' => $order->address,
                    'billing_city' => $order->city,
                    'billing_pincode' => $order->zip,
                    'billing_state' => $order->state,
                    'billing_country' => 'India',
                    'billing_email' => $email,
                    'billing_phone' => $phone,
                    'shipping_is_billing' => true,
                    'order_items' => $order->items->map(function ($item) {
                        return [
                            'name' => $item->title,
                            'sku' => $item->sku,
                            'units' => $item->qty,
                            'selling_price' => number_format($item->price, 2, '.', ''),
                        ];
                    })->toArray(),
                    'payment_method' => $order->payment_method ?? 'Prepaid',
                    'sub_total' => number_format($order->sub_total, 2, '.', ''),
                    'length' => 10,
                    'breadth' => 10,
                    'height' => 10,
                    'weight' => 0.5,
                ];

                Log::info('Creating Shiprocket Order with Payload:', $orderData);

                $response = $this->shiprocketService->createOrder($orderData);

                // Log the full response for debugging
                Log::info('Shiprocket Order Creation Response:', $response);

                if (!empty($response['success']) && $response['success']) {
                    // Assuming Shiprocket returns 'awb_code' or 'shipment_id' for tracking
                    $trackingId = $response['awb_code'] ?? $response['shipment_id'] ?? null;

                    if ($trackingId) {
                        // Update the tracking_id in the orders table
                        Order::where('unique_order_id', $order->unique_order_id)->update(['tracking_id' => $trackingId]);
                        Log::info("Shiprocket order created successfully for Order ID {$order->unique_order_id}. Tracking ID: {$trackingId}");
                    } else {
                        Log::warning("Tracking ID not returned for Order ID {$order->unique_order_id}");
                    }

                    return response()->json(['message' => 'Order status updated and Shiprocket order created successfully']);
                } else {
                    $errorMessage = $response['message'] ?? 'Unknown error';
                    Log::error("Shiprocket order creation failed for Order ID {$order->unique_order_id}: " . $errorMessage);
                    return response()->json(['message' => 'Order status updated, but failed to create Shiprocket order', 'error' => $response], 500);
                }
            } catch (\Exception $e) {
                Log::error("Error while integrating with Shiprocket for Order ID {$order->unique_order_id}: " . $e->getMessage());
                return response()->json(['message' => 'Error while integrating with Shiprocket: ' . $e->getMessage()], 500);
            }
        }

        return response()->json(['message' => 'Order status updated successfully']);
    }




}
