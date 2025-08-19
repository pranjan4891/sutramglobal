<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\MastersController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\DashboardController;

Route::group(['prefix' => 'admin'], function () {

    Route::get('/', [LoginController::class, 'index'])->name('admin.login');
    Route::post('/', [LoginController::class, 'loginCheck'])->name('admin.loginCheck');
    Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logout');
    Route::get('forgot-password', [LoginController::class, 'create'])->name('admin.password_request');
    Route::post('/forgot-password_check', [LoginController::class, 'forgetPasswordCheck'])->name('admin.forgetPasswordCheck');

    Route::group(['middleware' => 'auth:admin'], function () {


        Route::post('get-subcategory', [Controller::class, 'get_subcategory'])->name('admin.get_subcategory');


        Route::prefix('/dashboard')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
        });

        Route::prefix('/contact-us')->group(function () {
            Route::get('/', [DashboardController::class, 'contactUs'])->name('admin.dashboard.contactUs');
            Route::post('list-contact', [DashboardController::class, 'getcontactUsList'])->name('admin.dashboard.getcontactUsList');
            Route::post('delete-contact', [DashboardController::class,'deleteContactUs'])->name('admin.dashboard.deleteContactUs');
        });

        Route::prefix('news-letter')->group(function () {
            Route::get('/', [DashboardController::class, 'newsLatter'])->name('admin.dashboard.newsLatter');
            Route::post('list', [DashboardController::class, 'getNewsLatterList'])->name('admin.dashboard.getNewsLatterList');
            Route::post('delete', [DashboardController::class,'deleteNewsLatter'])->name('admin.dashboard.deleteNewsLatter');
        });

        Route::prefix('/wishlists')->group(function () {
            Route::get('/', [DashboardController::class, 'wishlist'])->name('admin.wishlist');
            Route::post('/getWishlists', [DashboardController::class, 'getWishlist'])->name('admin.wishlists.getWishlists');
            Route::match(['get', 'post'], '/sendMail/{id}', [DashboardController::class, 'sendMail'])->name('admin.wishlists.sendMail');
        });

        Route::prefix('cart-products')->group(function () {
            Route::get('/', [DashboardController::class, 'cartProducts'])->name('admin.dashboard.cartProducts');
            Route::post('get-cart-products', [DashboardController::class, 'getCartProducts'])->name('admin.dashboard.getCartProducts');
        });



        Route::prefix('products')->group(function () {
            // Product listing and management
            Route::get('/', [ProductController::class, 'index'])->name('admin.products');
            Route::match(['GET', 'POST'], 'list', [ProductController::class, 'getProductList'])->name('admin.product.getProductList');
            Route::get('get-subcategories', [ProductController::class, 'getSubcategories'])->name('admin.product.getSubcategories');

            // Product creation and updates
            Route::post('save', [ProductController::class, 'store'])->name('admin.product.store');
            Route::get('manage/{id?}', [ProductController::class, 'manage'])->name('admin.product.manage');

            // Status and trending updates
            Route::post('status', [ProductController::class, 'changeStatus'])->name('admin.product.changeStatus');
            Route::post('trending', [ProductController::class, 'changeTrends'])->name('admin.product.changeTrends');

            // Product deletion
            Route::post('delete', [ProductController::class, 'delete'])->name('admin.product.delete');

            // Product variants
            Route::get('{product_id}/variants', [ProductController::class, 'variants'])->name('admin.product.variants');
            Route::post('variants-list', [ProductController::class, 'getProductVariants'])->name('admin.product.getProductVariants');
            Route::get('/{product_id}/manage-variants/{id?}', [ProductController::class, 'manageVariants'])->name('admin.product.manageVariants');
            Route::post('variant-save', [ProductController::class, 'variantStore'])->name('admin.product.variantStore');
            Route::post('variants-status', [ProductController::class, 'variantStatus'])->name('admin.product.variantStatus');
            Route::post('variants-delete', [ProductController::class, 'variantDelete'])->name('admin.product.variantDelete');
        });


        Route::prefix('orders')->group(function () {
            Route::get('/', [OrdersController::class, 'index'])->name('admin.orders');
            Route::post('list', [OrdersController::class, 'getOderList'])->name('admin.order.getOderList');
            Route::get('/view/{id}', [OrdersController::class, 'OrderViewId'])->name('admin.orders.view');
            Route::post('status', [OrdersController::class, 'changeStatus'])->name('admin.order.changeStatus');
            Route::post('delete', [OrdersController::class, 'delete'])->name('admin.order.delete');
            Route::put('/{id}/update-payment-status', [OrdersController::class, 'updatePaymentStatus'])->name('admin.orders.updatePaymentStatus');
            Route::put('/{id}/update-order-status', [OrdersController::class, 'updateOrderStatus'])->name('admin.orders.updateOrderStatus');
            Route::get('/{id}/invoice', [OrdersController::class, 'generateInvoice'])->name('admin.order.invoice');

        });


        Route::prefix('blogs')->group(function () {
            Route::get('/', [BlogController::class, 'index'])->name('admin.blogs');
            Route::post('list', [BlogController::class, 'getBlogList'])->name('admin.blog.getBlogList');
            Route::get('add', [BlogController::class, 'add'])->name('admin.blog.add');
            Route::post('store', [BlogController::class, 'Store'])->name('admin.blog.store');
            Route::get('edit/{slug}', [BlogController::class, 'edit'])->name('admin.blog.edit');
            Route::post('active-status', [BlogController::class, 'isActive'])->name('admin.blog.isActive');
            Route::post('home-status', [BlogController::class, 'isHome'])->name('admin.blog.isHome');
            Route::post('delete', [BlogController::class,'delete'])->name('admin.blog.delete');


        });

        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.users');
            Route::post('list-users', [UserController::class, 'getUsersLists'])->name('admin.users.getUsersLists');
            Route::post('/status', [UserController::class, 'changeStatus'])->name('admin.user.changeStatus');
            Route::post('/delete', [UserController::class, 'delete'])->name('admin.user.delete');
        });

        Route::prefix('coupons')->group(function () {
            Route::get('/', [CouponController::class, 'index'])->name('admin.coupons');
            Route::post('list', [CouponController::class, 'getList'])->name('admin.coupons.getList');
            Route::get('add', [CouponController::class, 'add'])->name('admin.coupons.add');
            Route::post('store', [CouponController::class, 'store'])->name('admin.coupons.store');
            Route::get('edit/{id}', [CouponController::class, 'edit'])->name('admin.coupons.edit');
            Route::post('status', [CouponController::class, 'status'])->name('admin.coupons.status');
            Route::post('delete', [CouponController::class, 'delete'])->name('admin.coupons.delete');
        });


        Route::prefix('masters')->group(function () {

            Route::prefix('colors')->group(function () {
                Route::get('/', [ColorController::class, 'index'])->name('admin.colors');
                Route::post('list', [ColorController::class, 'getList'])->name('admin.colors.getList');
                Route::get('add', [ColorController::class, 'add'])->name('admin.colors.add');
                Route::post('store', [ColorController::class, 'store'])->name('admin.colors.store');
                Route::get('edit/{id}', [ColorController::class, 'edit'])->name('admin.colors.edit');
                Route::post('status', [ColorController::class, 'status'])->name('admin.colors.status');
                Route::post('delete', [ColorController::class,'delete'])->name('admin.colors.delete');
            });
            Route::prefix('sizes')->group(function () {
                Route::get('/', [SizeController::class, 'index'])->name('admin.sizes');
                Route::post('list', [SizeController::class, 'getList'])->name('admin.sizes.getList');
                Route::get('add', [SizeController::class, 'add'])->name('admin.sizes.add');
                Route::post('store', [SizeController::class, 'store'])->name('admin.sizes.store');
                Route::get('edit/{id}', [SizeController::class, 'edit'])->name('admin.sizes.edit');
                Route::post('status', [SizeController::class, 'status'])->name('admin.sizes.status');
                Route::post('delete', [SizeController::class,'delete'])->name('admin.sizes.delete');
            });

            Route::prefix('categories')->group(function () {
                Route::get('/', [MastersController::class, 'category'])->name('admin.masters.category');
                Route::post('list', [MastersController::class, 'getCategoryList'])->name('admin.masters.getCategoryList');
                Route::post('store', [MastersController::class, 'categoryStore'])->name('admin.masters.categoryStore');
                Route::get('edit/{id}', [MastersController::class, 'categoryEdit'])->name('admin.masters.categoryEdit');
                Route::post('delete', [MastersController::class, 'categoryDelete'])->name('admin.masters.categoryDelete');
                Route::post('status', [MastersController::class, 'categoryStatus'])->name('admin.masters.categoryStatus');

            });
            Route::prefix('sub-categories')->group(function () {
                Route::get('/', [MastersController::class, 'subCategory'])->name('admin.masters.subCategory');
                Route::post('list', [MastersController::class, 'getSubCategoryList'])->name('admin.masters.getSubCategoryList');
                Route::post('store', [MastersController::class, 'subCategoryStore'])->name('admin.masters.subCategoryStore');
                Route::get('edit/{id}', [MastersController::class, 'subCategoryEdit'])->name('admin.masters.subCategoryEdit');
                Route::post('delete', [MastersController::class, 'subCategoryDelete'])->name('admin.masters.subCategoryDelete');
                Route::post('status', [MastersController::class, 'subCategoryStatus'])->name('admin.masters.subCategoryStatus');

            });



            Route::prefix('/sliders')->group(function () {
                Route::get('/', [MastersController::class, 'slider'])->name('admin.masters.slider');
                Route::post('list', [MastersController::class, 'getSliderList'])->name('admin.masters.getSliderList');
                Route::post('store', [MastersController::class, 'sliderStore'])->name('admin.masters.sliderStore');
                Route::get('edit/{id}', [MastersController::class, 'sliderEdit'])->name('admin.masters.sliderEdit');
                Route::post('delete', [MastersController::class, 'sliderDelete'])->name('admin.masters.sliderDelete');
                Route::post('status', [MastersController::class, 'sliderStatus'])->name('admin.masters.sliderStatus');

            });

        });

        Route::group(['prefix'=>'settings'],function(){
            Route::get('/', [SettingController::class,'index'])->name('admin.settings');
            Route::post('update', [SettingController::class,'update'])->name('admin.settings.update');
            Route::post('update-logo', [SettingController::class,'updateLogo'])->name('admin.settings.updateLogo');
        });

        Route::prefix('/pages')->group(function () {
            Route::get('/', [PageController::class, 'index'])->name('admin.pages');
            Route::post('list', [PageController::class,'getPageList'])->name('admin.page.getPageList');
            Route::get('manage/{id?}', [PageController::class,'manage'])->name('admin.page.manage');
            Route::post('update', [PageController::class,'update'])->name('admin.page.update');
            Route::post('status', [PageController::class,'status'])->name('admin.page.status');
            Route::post('delete', [PageController::class,'delete'])->name('admin.page.delete');

        });

    });

});

