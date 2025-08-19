<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;  // This imports the base controller class
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    public function submitReview(Request $request)
    {
        // Validation rules
        $rules = [
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'review' => 'required|string|max:1000',
            'product_id' => 'required|exists:products,id', // Ensure product exists
        ];

        if (!Auth::check()) {
            // Additional validation for guests
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|max:255';
        }

        $request->validate($rules);

        $productId = $request->input('product_id');
        $userId = Auth::id();

        // Check if the user has an order with this product that is marked as delivered
        $orderWithProduct = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.customer_id', $userId)
            ->where('order_items.product_id', $productId)
            ->where('orders.order_status', 'delivered') // Ensure order is delivered
            ->exists();

        if (!$orderWithProduct) {
            return response()->json([
                'success' => false,
                'message' => 'You can only review products you have purchased and that have been delivered.',
            ], 403);
        }

        // Create a new review
        $review = new Review();
        $review->rating = $request->input('rating');
        $review->title = $request->input('title');
        $review->review = $request->input('review');
        $review->product_id = $productId;

        if (Auth::check()) {
            $review->user_id = $userId;
            $review->name = Auth::user()->first_name . ' ' . Auth::user()->last_name;
            $review->email = Auth::user()->email;
        } else {
            $review->name = $request->input('name');
            $review->email = $request->input('email');
        }

        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully!',
        ]);
    }



    // Method to fetch product reviews (for dynamic loading)
    public function getProductReviews($productId)
    {
        // Fetch reviews related to the specific product
        $reviews = Review::where('product_id', $productId)->get();

        // Return the reviews as JSON for the front-end
        return response()->json($reviews);
    }
}


