<?php

namespace App\Http\Controllers\Web;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Contact;
use App\Models\Size;
use App\Models\Color;
use App\Models\Slider;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;



class HomeController extends Controller
{

    public function index()
    {
        // Prepare data for the home page
        $data['title'] = 'Home';
        $data['categories'] = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories
        }])
            ->where('status', 1)->orderBy('order_by', 'asc') // Only active categories
            ->get();

        $data['banners'] = Slider::where('is_active', 1)->orderBy('order_by', 'asc')->get();

        // Check if the user is logged in
        $user_id = Auth::check() ? Auth::user()->id : null;

        // Fetch Men Products
       // Fetch Men Products
        $menProducts = Product::with(['variants.color', 'variants.size' => function ($query) {
            $query->orderBy('sort', 'asc'); // Order sizes by sort ascending
        }])
        ->where('category_id', 1)
        ->where('status', 1)
        ->inRandomOrder()
        ->take(4)
        ->get()
        ->map(function ($product) use ($user_id) {
            $product->sizeCodes = $product->variants->pluck('size.code')->unique()->toArray(); // Get unique size codes
            $product->colorData = $product->variants->mapWithKeys(function ($variant) {
                return [$variant->color->name ?? 'Unknown' => $variant->color->code ?? 'Unknown'];
            })->toArray(); // Map color names to their codes
            $product->originalPrices = $product->variants->pluck('original_price')->unique()->toArray(); // Get original prices from variants
            $product->isInWishlist = $user_id
                ? Wishlist::where('user_id', $user_id)
                    ->where('product_id', $product->id)
                    ->exists()
                : false;
            return $product;
        });

        // Fetch Women Products
        $womenProducts = Product::with(['variants.color', 'variants.size'])
        ->where('category_id', 2)
        ->where('status', 1)
        ->inRandomOrder()
        ->take(4)
        ->get()
        ->map(function ($product) use ($user_id) {
            $product->sizeCodes = $product->variants->pluck('size.code')->unique()->toArray(); // Get unique size codes
            $product->colorData = $product->variants->mapWithKeys(function ($variant) {
                return [$variant->color->name ?? 'Unknown' => $variant->color->code ?? 'Unknown'];
            })->toArray(); // Map color names to their codes
            $product->originalPrices = $product->variants->pluck('original_price')->unique()->toArray(); // Get original prices from variants
            $product->isInWishlist = $user_id
                ? Wishlist::where('user_id', $user_id)
                    ->where('product_id', $product->id)
                    ->exists()
                : false;
            return $product;
        });

        // Fetch Perfume Products
        $perfumeProducts = Product::with(['variants.size'])
        ->where('category_id', 3)
        ->where('status', 1)
        ->inRandomOrder()
        ->take(4)
        ->get()
        ->map(function ($product) use ($user_id) {
            $product->sizeCodes = $product->variants->pluck('size.code')->unique()->toArray(); // Get unique size codes
            $product->originalPrices = $product->variants->pluck('original_price')->unique()->toArray(); // Get original prices from variants
            $product->isInWishlist = $user_id
                ? Wishlist::where('user_id', $user_id)
                    ->where('product_id', $product->id)
                    ->exists()
                : false;
            return $product;
        });

         //   dd($perfumeProducts);
        // Fetch trending products

        $data['trends'] = Product::where('trending', 1)
            ->where('status', 1)
            ->inRandomOrder()
            ->take(8)
            ->get();

        // Assign products to view
        $data['menProducts'] = $menProducts;
        $data['womenProducts'] = $womenProducts;
        $data['perfumeProducts'] = $perfumeProducts;

        // Return the view with data
        return view('web.home', $data);
    }



    public function show($slug)
    {
        // Find subcategory by slug
        $subcategory = Subcategory::where('slug', $slug)->firstOrFail();

        // Return a view for the subcategory page (e.g., products listing page)
        return view('subcategory.show', compact('subcategory'));
    }

    public function searchSuggestions(Request $request)
    {
        $keyword = $request->get('keyword');

        $products = Product::where('title', 'LIKE', "%$keyword%")
            ->take(15)
            ->get(['id', 'title', 'slug','image_1']);

        $products->transform(function ($product) {
            $product->url = route('product.details', $product->slug);
            $product->image=isImage('products', $product->image_1);
            return $product;
        });

        return response()->json($products);
    }

    public function search(Request $request)
    {
        $title = "Search Results";
        $keyword = $request->get('keyword');

        // Search in categories and subcategories by name to get their IDs
        $categoryIds = DB::table('categories')
                        ->where('name', 'LIKE', "%$keyword%")
                        ->pluck('id')
                        ->toArray();

        $subCategoryData = DB::table('sub_categories')
                            ->where('name', 'LIKE', "%$keyword%")
                            ->get(['id', 'category_id']);

        $subCategoryIds = $subCategoryData->pluck('id')->toArray();
        $subCategoryCategoryIds = $subCategoryData->pluck('category_id')->toArray();

        // Fetch products based on category, sub-category, or title
        $user_id = Auth::check() ? Auth::user()->id : null;

        $products = Product::with([
            'variants.color',
            'variants.size' => function ($query) {
                $query->orderBy('sort', 'asc'); // Ensure sizes are sorted by 'sort'
            }
        ])
        ->where('status', 1)
        ->where(function ($query) use ($categoryIds, $subCategoryIds, $subCategoryCategoryIds, $keyword) {
            $query->whereIn('category_id', array_merge($categoryIds, $subCategoryCategoryIds))
                  ->orWhereIn('subcategory_id', $subCategoryIds)
                  ->orWhere('title', 'LIKE', "%$keyword%");
        })
        ->paginate(8);

        // Prepare product data with additional details
        $products->getCollection()->transform(function ($product) use ($user_id) {
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

        // Fetch subcategories related to the search
        $subCategories = DB::table('sub_categories')
                          ->whereIn('id', $subCategoryIds)
                          ->get();

        // Fetch all active categories with their subcategories
        $categories = Category::with(['subcategories' => function ($query) {
                            $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories
                        }])
                        ->where('status', 1)->orderBy('order_by', 'asc') // Only active categories
                        ->get();

        // Pass data to the search blade
        return view('web.layout.search', compact('products', 'categories', 'subCategories', 'keyword', 'title'));
    }


    public function profile()
    {
        $data['title'] = 'Profile';
        $data['user'] = Auth::user();
        $customerId = Auth::id();
        $data['categories'] = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories

        }])
        ->where('status', 1)->orderBy('order_by', 'asc') // Only active categories
        ->get();

        // Fetch order and order items data using a join query

        return view('web.profile', $data);
    }
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $user->first_name = $request->firstname;
        $user->last_name = $request->lastname;
        $user->mobile = $request->phone;
        $user->email = $request->email;
        $user->sex = $request->gender;
        $user->dob = $request->dob;

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/profile_photos'), $filename); // Store file in storage/app/public/profile_photos
            $user->profile_photo = $filename;
        }

        if ($user->save()) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }


    public function changePassword(Request $request)
    {
        $user = Auth::user();

        // If password exists, validate current password
        if (!empty($user->password)) {
            if (empty($request->currentPassword)) {
                return response()->json(['success' => false, 'errors' => ['Current password is required']]);
            }

            if (!Hash::check($request->currentPassword, $user->password)) {
                return response()->json(['success' => false, 'errors' => ['Current password is incorrect']]);
            }
        }

        // Validate new password match
        if ($request->newPassword !== $request->confirmNewPassword) {
            return response()->json(['success' => false, 'errors' => ['New password and confirm password do not match']]);
        }

        // Update password
        $user->password = Hash::make($request->newPassword);
        $user->save();

        return response()->json(['success' => true]);
    }



    public function address()
    {
        $data['title'] = 'Address';
        $data['user'] = Auth::user();
        $data['categories'] = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories

        }])
        ->where('status', 1)->orderBy('order_by', 'asc') // Only active categories
        ->get();


        $data['addresses'] = CustomerAddress::where('user_id', Auth::id())->get();
        return view('web.address', $data);
    }

    public function setDefaultAddress($id)
    {
        // Set all addresses for the user as non-default
        CustomerAddress::where('user_id', Auth::id())->update(['status' => 0]);

        // Set the selected address as default
        CustomerAddress::where('id', $id)->where('user_id', Auth::id())->update(['status' => 1]);

        return redirect()->back()->with('success', 'Default address set successfully!');
    }

    public function addressshow($id)
    {
        $address = CustomerAddress::find($id);
        return response()->json($address);
    }

    public function addressupdate(Request $request, $id)
    {
        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',

            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
            'mobile' => 'required|string|max:15',

        ]);

        // Find the address and update the data
        $address = CustomerAddress::find($id);
        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Address not found'], 404);
        }

        $address->name = $request->input('name');
       // $address->lastname = $request->input('lastname');
        $address->email =Auth::user()->email;
        $address->state = $request->input('state');
        $address->city = $request->input('city');
        $address->zip_code = $request->input('pincode');
        $address->mobile = $request->input('mobile');
        $address->alertnate_mobile = $request->input('alternate_mobile');
        $address->address1 = $request->input('full_address');
       // $address->address2 = explode(' ', $request->input('landmark'))[1] ?? '';
        $address->address2 = $request->input('landmark');
        $address->save();

        return response()->json(['success' => true, 'message' => 'Address updated successfully']);
    }


    public function deleteAddress($id)
    {
        // Attempt to delete the address for the authenticated user
        $deleted = CustomerAddress::where('id', $id)->where('user_id', Auth::id())->delete();

        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Address deleted successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to delete address. Please try again.']);
        }
    }




    public function addAddress()
    {
        $data['title'] = 'Address';
        $data['user'] = Auth::user();
        $data['categories'] = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories

        }])
        ->where('status', 1)->orderBy('order_by', 'asc') // Only active categories
        ->get();


        return view('web.addressnew', $data);
    }

    public function storeAddress(Request $request)
    {
        // Validate the form input
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
            'mobile' => 'required|string|max:15',
        ], [
            'name.required' => 'First name is required',
            'lastname.required' => 'Last name is required',
            'state.required' => 'State is required',
            'city.required' => 'City is required',
            'pincode.required' => 'Pincode is required',
            'mobile.required' => 'Mobile number is required',
            'pincode.max' => 'Pincode must be 6 characters',
            'mobile.max' => 'Mobile number must be 15 characters',
        ]);

        // Get the logged-in user's ID
        $userId = Auth::id();

        // Update all existing addresses for the user to status 0
        CustomerAddress::where('user_id', $userId)->update(['status' => 0]);

        // Create the new address with status 1
        CustomerAddress::create([
            'user_id' => $userId,
            'name' => $request->input('name') . ' ' . $request->input('lastname'),
            'email' =>Auth::user()->email,
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'zip_code' => $request->input('pincode'),
            'mobile' => $request->input('mobile'),
            'alertnate_mobile' => $request->input('alternate_mobile'),
            'address1' => $request->input('full_address'),
            'address2' => $request->input('landmark'),
            'status' => 1, // Mark the newly added address as active
        ]);

        // Redirect to the previous URL or a fallback URL
        return redirect()->route('address')->with('success', 'Address saved successfully!');
    }
    public function checkoutaddAddress()
    {
        $data['title'] = 'Address';
        $data['user'] = Auth::user();
        $data['categories'] = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories

        }])
        ->where('status', 1)->orderBy('order_by', 'asc') // Only active categories
        ->get();


        return view('web.chekoutaddressnew', $data);
    }

    public function checkoutstoreAddress(Request $request)
    {
        // Validate the form input
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
            'mobile' => 'required|string|max:15',
        ], [
            'name.required' => 'First name is required',
            'lastname.required' => 'Last name is required',
            'state.required' => 'State is required',
            'city.required' => 'City is required',
            'pincode.required' => 'Pincode is required',
            'mobile.required' => 'Mobile number is required',
            'pincode.max' => 'Pincode must be 6 characters',
            'mobile.max' => 'Mobile number must be 15 characters',
        ]);

        // Get the logged-in user's ID and user record
        $userId = Auth::id();
        $user = Auth::user();

        // ✅ Update users table if fields are empty
        $updateData = [];
        if (empty($user->first_name)) {
            $updateData['first_name'] = $request->input('name');
        }
        if (empty($user->last_name)) {
            $updateData['last_name'] = $request->input('lastname');
        }
        if (empty($user->email) && $request->filled('email')) {
            $updateData['email'] = $request->input('email');
        }

        if (!empty($updateData)) {
            $user->update($updateData);
        }

        // Deactivate all existing addresses
        CustomerAddress::where('user_id', $userId)->update(['status' => 0]);

        // Create new address and mark as active
        CustomerAddress::create([
            'user_id' => $userId,
            'name' => $request->input('name') . ' ' . $request->input('lastname'),
            'email' => $user->email,
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'zip_code' => $request->input('pincode'),
            'mobile' => $request->input('mobile'),
            'alertnate_mobile' => $request->input('alternate_mobile'),
            'address1' => $request->input('full_address'),
            'address2' => $request->input('landmark'),
            'status' => 1,
        ]);

        return redirect()->route('cart.checkout')->with('success', 'Address saved successfully!');
    }


    public function getOrderDetails(Request $request)
    {
        // Get the authenticated user's ID
        $customerId = Auth::id();

        // Fetch order and order items data using a join query
        $orders = DB::table('orders')
            ->join('order_items', 'orders.order_id', '=', 'order_items.order_id')
            ->where('orders.customer_id', $customerId)
            ->select(
                'orders.order_id',
                'orders.order_status',
                'orders.payment_status',
                'orders.created_at',
                'order_items.SKU',
                'order_items.qty',
                'order_items.image',
                'order_items.total_price'
            )
            ->get();

        // Return the data as a JSON response
        return response()->json(['orders' => $orders]);
    }

    public function toggle(Request $request)
    {
        // Ensure the user is authenticated
        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['status' => 0, 'message' => 'User not authenticated'], 401);
        }

        // Validate the request
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_id' => 'nullable|exists:colors,id', // Ensure color_id exists
            'size_id' => 'nullable|exists:sizes,id',  // Ensure size_id exists
            'action' => 'required|in:add,remove',     // Ensure action is valid
        ]);

        $productId = $validated['product_id'];
        $colorId = $validated['color_id'];
        $sizeId = $validated['size_id'];
        $action = $validated['action'];

        // Find the existing wishlist item
        $wishlistItem = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('color_id', $colorId)
            ->where('size_id', $sizeId)
            ->first();

        if ($action === 'add') {
            if (!$wishlistItem) {
                // Add to wishlist
                Wishlist::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'color_id' => $colorId,
                    'size_id' => $sizeId,
                ]);

                return response()->json(['status' => 1, 'message' => 'Added to wishlist']);
            }
            return response()->json(['status' => 0, 'message' => 'Item already in wishlist']);
        } elseif ($action === 'remove') {
            if ($wishlistItem) {
                // Remove from wishlist
                $wishlistItem->delete();

                return response()->json(['status' => 1, 'message' => 'Removed from wishlist']);
            }
            return response()->json(['status' => 0, 'message' => 'Item not found in wishlist']);
        }

        // Return a default error response if action is invalid
        return response()->json(['status' => 0, 'message' => 'Invalid action']);
    }


    public function wishlist()
    {
        $data['title'] = 'Wishlist';
        $data['categories'] = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories
        }])
        ->where('status', 1)->orderBy('order_by', 'asc') // Only active categories
        ->get();

        // Fetch wishlist items for the logged-in user
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with(['product', 'size', 'color', 'product.variants' => function ($query) {
                $query->where('status', 1); // Fetch only active variants
            }])->get();

        // Attach the variant price based on the color and size
        foreach ($wishlists as $wishlist) {
            if ($wishlist->product) {
                $variant = $wishlist->product->variants
                    ->where('color_id', $wishlist->color_id)
                    ->where('size_id', $wishlist->size_id)
                    ->first();

                $wishlist->variant_price = $variant ? $variant->price : null;
                $wishlist->variant_original_price = $variant ? $variant->original_price : null;
                $wishlist->variant_quantity = $variant ? $variant->quantity : 0; // Include variant quantity

                // Determine stock status
                $wishlist->is_in_stock = $variant && $variant->quantity > 0;
            } else {
                $wishlist->variant_price = null;
                $wishlist->variant_original_price = null;
                $wishlist->variant_quantity = 0;
                $wishlist->is_in_stock = false;
            }
        }

        $data['wishlists'] = $wishlists;
        return view('web.wishlist', $data);
    }


    public function storeWishlist(Request $request)
    {
        if (Auth::check()) {
            $product_id = $request->product_id;
            $size = $request->size;
            $color = $request->color;
            $user_id = Auth::user()->id;

            // Check if the product is already in the wishlist
            $wishlist = Wishlist::where('user_id', $user_id)->where('product_id', $product_id)->first();

            if (empty($wishlist)) {
                $wishlist = new Wishlist;
                $wishlist->product_id = $product_id;
                $wishlist->user_id = $user_id;
                $wishlist->size_id = $size;
                $wishlist->color_id = $color;
                $wishlist->save();

                return response()->json(['status' => 1, 'message' => 'Success! Added to Wishlist Successfully']);
            } else {
                return response()->json(['status' => 0, 'message' => 'Product already in Wishlist']);
            }
        } else {
            return response()->json(['status' => 0, 'message' => 'Please login first']);
        }
    }
    public function remove($id)
    {
        $user_id = Auth::id();

        // Find the wishlist item by ID and ensure it belongs to the logged-in user
        $wishlist = Wishlist::where('id', $id)->where('user_id', $user_id)->first();

        if ($wishlist) {
            $wishlist->delete();

            return redirect()->back()->with('success', 'Item removed from wishlist successfully!');
        } else {
            return redirect()->back()->with('error', 'Item not found in your wishlist!');
        }
    }

    public function moveToCart($id)
    {
        $user_id = Auth::id();

        // Find the wishlist item by ID and ensure it belongs to the logged-in user
        $wishlist = Wishlist::where('id', $id)->where('user_id', $user_id)->first();

        if ($wishlist) {
            // Fetch product details from the products table
            $product = Product::find($wishlist->product_id);

            if (!$product) {
                return redirect()->back()->with('error', 'Product not found!');
            }

            // Check if the product is already in the cart
            $existingCartItem = Cart::where('user_id', $user_id)
                                    ->where('product_id', $wishlist->product_id)
                                    ->first();

            if ($existingCartItem) {
                return redirect()->back()->with('error', 'Product is already in your cart!');
            }

            // Add the product to the cart with details
            $cart = new Cart;
            $cart->user_id = $user_id;
            $cart->product_id = $wishlist->product_id;
            $cart->part_number = $product->part_number;
            $cart->sku = $product->sku;
            $cart->color_id = $product->color;
            $cart->price = $product->price;
            $cart->qty = 1;  // Default quantity, adjust as needed
            $cart->status = 'pending'; // Default status
            $cart->save();

            // Remove the item from the wishlist
            $wishlist->delete();

            return redirect()->back()->with('success', 'Item moved to cart successfully!');
        } else {
            return redirect()->back()->with('error', 'Item not found in your wishlist!');
        }
    }

    public function contactus(Request $request)
    {

        $data['title'] = 'Contact Us';
        $data['categories'] = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories

        }])
        ->where('status', 1)->orderBy('order_by', 'asc') // Only active categories
        ->get();

        return view('web.contactus', $data);
    }



    public function contact_us(Request $request)
    {
        // Validation
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
            'g-recaptcha-response' => 'required', // Ensure reCAPTCHA response is sent
        ]);

        // Verify reCAPTCHA
        $recaptchaSecret = env('RECAPTCHA_SECRET_KEY');
        $recaptchaToken = $request->input('g-recaptcha-response');

        $response = Http::post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaToken,
        ]);

        $responseData = $response->json();

        // Check reCAPTCHA success
        if (!$responseData['success'] || ($responseData['score'] ?? 0) < 0.5) {
            return back()->withErrors(['captcha' => 'reCAPTCHA verification failed. Please try again.']);
        }

        // Store the data in the contacts table
        $contact = Contact::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'order_no' => $request->input('order-number'),
            'message' => $validatedData['message'],
        ]);

        // Send a success message to the customer
        try {
            Mail::send([], [], function ($message) use ($contact) {
                $message->to($contact->email)
                    ->subject('Contact Form Submission')
                    ->text('Thank you for contacting us! We will get back to you shortly.');
            });
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'There was an issue sending the confirmation email.']);
        }

        return redirect()->back()->with('success', 'Your message has been successfully sent!');
    }

    public function about()
    {
        $data['title'] = 'About';
        return view('web.about',$data);
    }


}
