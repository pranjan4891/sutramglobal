<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsletterSubscription;
use App\Mail\ThankYouForSubscribing; // Add this line
use Illuminate\Support\Facades\Mail;

class NewsletterSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions,email',
        ]);

        // Save the subscription to the database
        $subscription = NewsletterSubscription::create([
            'email' => $request->input('email')
        ]);

        // Send thank you email
        Mail::to($subscription->email)->send(new ThankYouForSubscribing($subscription->email));

        return response()->json(['message' => 'Subscribed successfully! Thank you for subscribing!']);
    }
}
