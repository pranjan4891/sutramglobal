<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\WebLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\ReviewController;
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Auth;


    Route::get('/', [CategoryController::class, 'index'])->name('home');

    Route::get('/about', [HomeController::class,'about'])->name('about');

    // Route for category
    Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

    Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');


    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::post('/subscribe', [NewsletterSubscriptionController::class, 'store'])->name('subscribe');

    Route::get('/clear-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        return '<h1>clear cache</h1>';
    });
    Auth::routes(['reset' => true]);
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Show the forgot password form
    Route::get('/forgot-password', [WebLoginController::class, 'showForgotPasswordForm'])->name('password.request');
    // Handle the forgot password request
    Route::post('/forgot-password', [WebLoginController::class, 'forgotPassword'])->name('password.email');
    require __DIR__ . '/admin.php';


    Route::get('user-login', [WebLoginController::class, 'login'])->name('weblogin');
    Route::get('signin', [WebLoginController::class, 'login_with_password'])->name('loginwithpassword');
    Route::post('/login-with-password', [WebLoginController::class, 'loginpass'])->name('loginpass');
    Route::get('user-register', [WebLoginController::class, 'webregister'])->name('webregister');
    Route::post('user-register', [WebLoginController::class, 'user_store'])->name('user_store');
    Route::get('otp-verification', [WebLoginController::class, 'otp_verification'])->name('otp_verification');
    Route::post('/otp-verify', [WebLoginController::class, 'otpVerify'])->name('otp.verify');
    Route::post('/resend-otp', [WebLoginController::class, 'resendOtp'])->name('resend_otp');
    Route::post('login-check', [WebLoginController::class, 'loginCheck'])->name('loginCheck');

    // Include this line for password reset routes



    Route::get('logout', [WebLoginController::class, 'logout'])->name('logout');

    /** Search Controller */
    Route::get('/search', [HomeController ::class, 'search'])->name('search');
    Route::get('/search-suggestions', [HomeController::class, 'searchSuggestions'])->name('search.suggestions');

    //Route::get('countries', [Controller::class,'get_country'])->name('get_country');
    Route::post('states', [Controller::class,'get_state'])->name('get_state');
    Route::post('cities', [Controller::class,'get_city'])->name('get_city');

    Route::get('/contact-us', [HomeController::class, 'contactus'])->name('contact');
    Route::post('/contact-us', [HomeController::class, 'contact_us'])->name('contact.store');

    Route::prefix('products')->group(function () {
        Route::get('/{slug?}', [ProductController::class, 'productByCategory'])->name('web.product.product_by_category');
        Route::get('/{slug}/{subSlug}', [ProductController::class, 'productBySubCategory'])->name('subcategory.products');

    });

    Route::get('/products-details/{productSlug}', [ProductController::class, 'product_Details'])->name('product.details');
    Route::post('/product/check-variant', [ProductController::class, 'checkVariant'])->name('product.checkVariant');


    Route::get('/wishlist', [HomeController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/store', [HomeController::class, 'storeWishlist'])->name('wishlist.store');
    Route::delete('/wishlist/{id}', [HomeController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/move-to-cart/{id}', [HomeController::class, 'moveToCart'])->name('wishlist.moveToCart');

    Route::post('/wishlist/toggle', [HomeController::class, 'toggle'])->name('wishlist.toggle');


    Route::post('/review/store', [ReviewController::class, 'submitReview'])->name('review.store')->middleware('auth');

    Route::group(['middleware' => 'auth'], function () {

         // user profile
         Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
         Route::post('home/updateProfile', [HomeController::class, 'updateProfile'])->name('profile.update');
         Route::post('home/changePassword', [HomeController::class, 'changePassword'])->name('password.change');

         //user address
         Route::get('/address', [HomeController::class, 'address'])->name('address');
         Route::get('/add-address', [HomeController::class, 'addAddress'])->name('addaddress');
         Route::get('/checkout/add-address', [HomeController::class, 'checkoutaddAddress'])->name('checkout.addaddress');
         Route::post('/address/store', [HomeController::class, 'storeAddress'])->name('address.store');
         Route::post('/checkout/address/store', [HomeController::class, 'checkoutstoreAddress'])->name('checkout.address.store');

         // Route to set an address as default
         Route::get('/address/set-default/{id}', [HomeController::class, 'setDefaultAddress'])->name('setDefaultAddress');

         // Route to delete an address
         Route::delete('address/delete/{id}', [HomeController::class, 'deleteAddress'])->name('deleteAddress');
         Route::get('address/edit/{id}', [HomeController::class, 'addressshow']);
         Route::post('address/update/{id}', [HomeController::class, 'addressupdate']);

         //order details user's
        Route::get('/order-details', [HomeController::class, 'getOrderDetails'])->name('order.details.ajax');

        Route::get('/coupons', [CartController::class, 'fetchCoupons'])->name('coupons.fetch');
        Route::post('/coupon/uses/save', [CartController::class, 'saveCouponUsage'])->name('coupon.uses.save');

        Route::prefix('cart')->group(function(){
            Route::get('checkout', [CartController::class,'checkout'])->name('cart.checkout');
            Route::get('customer/get-addresses', [CartController::class, 'getAddresses'])->name('cart.customer.get-addresses');
            Route::post('customer/set-default-address', [CartController::class, 'setDefaultAddress'])->name('cart.customer.set-default-address');
            Route::get('/get-items', [CartController::class, 'getCartItems'])->name('cart.get-items');
            Route::get('customer/get-default-address', [CartController::class, 'getDefaultAddress'])->name('cart.customer.get-default-address');
        });


        Route::post('/checkout/process', [OrderController::class, 'processOrder'])->name('checkout.process');
        Route::get('/payment/{encryptedOrderId}', [PaymentController::class, 'initiatePayment'])->name('payment.page');
        Route::post('/payment/verify', [PaymentController::class, 'verifyPayment'])->name('payment.verify');

        // Route for successful & failed payment
        Route::get('/order/success/{encryptedOrderId}', [OrderController::class, 'orderSuccess'])->name('order.success');
        Route::get('/order/failed/{encryptedOrderId}', [OrderController::class, 'orderFailed'])->name('order.failed');

        Route::get('/my-order', [OrderController::class, 'orderList'])->name('order.list');
        Route::get('/order-summary/{encryptedOrderId}', [OrderController::class, 'orderSummary'])->name('order.summary');
        Route::post('/order/cancel/{id}', [OrderController::class, 'cancelOrder'])->name('order.cancel');
        Route::post('/order/return/{itemId}', [OrderController::class, 'returnProduct'])->name('order.return');

        Route::get('/order/invoice/{id}', [OrderController::class, 'generateInvoice'])->name('order.invoice');


    });
    Route::prefix('cart')->group(function(){
        Route::post('store', [CartController::class,'store'])->name('cart.store');
        Route::post('giftstore', [CartController::class,'giftstore'])->name('cart.giftstore');
        Route::get('/get-mini-cart-list', [CartController::class, 'get_mini_cart_list'])->name('cart.get-mini-cart-list');
        Route::post('/update-cart', [CartController::class, 'update_cart'])->name('cart.update-cart');
        Route::post('/remove-cart', [CartController::class, 'remove_cart'])->name('cart.remove-cart');
    });








