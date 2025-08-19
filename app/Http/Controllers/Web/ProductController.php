<?php

namespace App\Http\Controllers\Web;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Color;
use App\Models\Coupon;
use App\Models\Size;
use App\Models\Wishlist;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{


    public function productByCategory($slug = '')
    {
        $data['title'] = 'Products Category Wise';

        // Fetch active categories with their active subcategories
        $data['categories'] = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc'); // Active subcategories ordered by position
        }])
        ->where('status', 1)->orderBy('order_by', 'asc') // Active categories ordered by order_by
        ->get();

        // Fetch the category by slug
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            abort(404, 'Category not found'); // Abort if category is not found
        }
        $data['category'] = $category;

        // Fetch the subcategories under this category
        $data['subCategory'] = SubCategory::where('category_id', $category->id)
                                          ->where('status', 1)
                                          ->get();

        // Fetch all products in the selected category with their variants, sizes, and colors
        $user_id = Auth::check() ? Auth::user()->id : null;

        $allProducts = Product::with([
            'variants.color',
            'variants.size' => function ($query) {
                $query->orderBy('sort', 'asc'); // Ensure sizes are sorted by 'sort'
            }
        ])
        ->where('category_id', $category->id)
        ->where('status', 1)
        ->get()
        ->map(function ($product) use ($user_id) {
            // Prepare size codes
            $product->sizeCodes = $product->variants->pluck('size.code')->unique()->toArray();

            // Prepare color data (color name => color code)
            $product->colorData = $product->variants->mapWithKeys(function ($variant) {
                return [$variant->color->name ?? 'Unknown' => $variant->color->code ?? 'Unknown'];
            })->toArray();

            // Check if the product is in the wishlist
            $product->isInWishlist = $user_id
                ? Wishlist::where('user_id', $user_id)
                    ->where('product_id', $product->id)
                    ->exists()
                : false;

            // Map variant data to include size, color, quantity, and prices
            $product->variantData = $product->variants->map(function ($variant) {
                return [
                    'color_code' => $variant->color->code ?? null,
                    'size_code' => $variant->size->code ?? null,
                    'quantity' => $variant->quantity,
                    'original_price' => $variant->original_price,
                    'price' => $variant->price,
                ];
            });
            return $product;
        });

        $data['allproducts'] = $allProducts;
        return view('web.products.product_by_category', $data);
    }

    public function productBySubCategory($slug = '', $subSlug = '')
    {
        $data['title'] = 'Products Sub-Category Wise';

        // Fetch active categories with their active subcategories for the sidebar
        $data['categories'] = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc'); // Active subcategories ordered by position
        }])
        ->where('status', 1)->orderBy('order_by', 'asc') // Active categories ordered by order_by
        ->get();

        // Fetch the category by slug
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            abort(404, 'Category not found'); // Abort if category is not found
        }
        $data['category'] = $category;

        // Fetch the subcategory by slug and validate that it belongs to the selected category
        $subCategory = SubCategory::where('slug', $subSlug)
            ->where('category_id', $category->id)
            ->where('status', 1)
            ->first();
        if (!$subCategory) {
            abort(404, 'Subcategory not found'); // Abort if subcategory is not found
        }
        $data['subCategory'] = $subCategory;

        // Fetch all products in the selected subcategory with their variants, sizes, and colors
        $user_id = Auth::check() ? Auth::user()->id : null;

        $allProducts = Product::with([
            'variants.color',
            'variants.size' => function ($query) {
                $query->orderBy('sort', 'asc'); // Ensure sizes are sorted by 'sort'
            }
        ])
        ->where('category_id', $category->id)
        ->where('subcategory_id', $subCategory->id)
        ->where('status', 1) // Only active products
        ->get()
        ->map(function ($product) use ($user_id) {
            // Prepare size codes
            $product->sizeCodes = $product->variants->pluck('size.code')->unique()->toArray();

            // Prepare color data (color name => color code)
            $product->colorData = $product->variants->mapWithKeys(function ($variant) {
                return [$variant->color->name ?? 'Unknown' => $variant->color->code ?? 'Unknown'];
            })->toArray();

            // Check if the product is in the wishlist
            $product->isInWishlist = $user_id
                ? Wishlist::where('user_id', $user_id)
                    ->where('product_id', $product->id)
                    ->exists()
                : false;

            // Map variant data to include size, color, quantity, and prices
            $product->variantData = $product->variants->map(function ($variant) {
                return [
                    'color_code' => $variant->color->code ?? null,
                    'size_code' => $variant->size->code ?? null,
                    'quantity' => $variant->quantity,
                    'original_price' => $variant->original_price,
                    'price' => $variant->price,
                ];
            });

            return $product;
        });

        $data['allproducts'] = $allProducts;

        // Render the view with products filtered by subcategory
        return view('web.products.product_by_category', $data);
    }


    public function product_Details($Slug)
    {
        $data['title'] = 'Product Details';

        // Fetch categories and subcategories for the sidebar
        $data['categories'] = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc');
        }])
        ->where('status', 1)
        ->orderBy('order_by', 'asc')
        ->get();

        // Fetch the product using the slug
        $product = Product::where('slug', $Slug)->firstOrFail();
       // \DB::enableQueryLog();
        $product->increment('views');
       // dd(\DB::getQueryLog());
        $data['product'] = $product;

        // Fetch reviews
        $data['reviews'] = Review::where('product_id', $product->id)->latest()->get();

        // Fetch product variants with associated color and size data
        $productVariants = ProductVariant::with(['color', 'size'])
            ->where('product_id', $product->id)
            ->get();

        if ($productVariants->isEmpty()) {
            $data['productVariants'] = [];
            $data['product']->sizeCodes = [];
            $data['product']->colorData = [];
            $data['defaultVariant'] = null;
        } else {
            $data['productVariants'] = $productVariants;

            // Get unique size codes
            $data['product']->sizeCodes = $productVariants->pluck('size.code')->unique()->toArray();

            // Get color data (name and code)
            $data['product']->colorData = $productVariants->mapWithKeys(function ($variant) {
                return $variant->color ? [$variant->color->name => $variant->color->code] : [];
            });

            // Set the default variant
            $data['defaultVariant'] = $productVariants->first();
        }

        // Check wishlist
        if (Auth::check()) {
            $data['isInWishlist'] = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->exists();
        } else {
            $data['isInWishlist'] = false;
        }

        // Size guide information
        $data['size_guider_Name'] = DB::table('size_catergory')
            ->select('title')
            ->where('id', $product->size_guider_id)
            ->first();

        $data['size_guider'] = DB::table('size_guider')
            ->where('size_cat_id', $product->size_guider_id)
            ->get();

        // Check coupon
        $data['coupons'] = Coupon::where('status', 1)
            ->whereDate('end_date', '>=', now()) // Check if the coupon is still valid
            ->first();

        return view('web.products.product_details', $data);
    }


    public function checkVariant(Request $request)
    {
        $productId = $request->input('product_id');
        $sizeId = $request->input('size_id');
        $colorId = $request->input('color_id');

        $variant = ProductVariant::where('product_id', $productId)
            ->where('size_id', $sizeId)
            ->where('color_id', $colorId)
            ->first();

        if ($variant) {
            $isInWishlist = false;

            // Check if the variant is in the wishlist for the authenticated user
            if (Auth::check()) {
                $isInWishlist = Wishlist::where('user_id', Auth::id())
                    ->where('product_id', $productId)
                    ->where('color_id', $colorId)
                    ->where('size_id', $sizeId)
                    ->exists();
            }

            return response()->json([
                'exists' => true,
                'quantity' => $variant->quantity,
                'price' => $variant->price,
                'original_price' => $variant->original_price,
                'isInWishlist' => $isInWishlist,
            ]);
        }

        return response()->json(['exists' => false]);
    }



}
