<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    public function index()
    {
        // Fetch all categories from the database
        $categories = Category::where('status', 1)->orderBy('name', 'ASC')->get();

        // Pass the categories to the view
        $data = [
            'title' => 'Products',
            'action' => 'List',
            'categories' => $categories, // Pass categories to the view
        ];

        return view('admin.product.list', $data);
    }
    public function manage($id = '')
    {
        $data['title'] = !empty($id) ? 'Edit Product' : 'Add Product';
        $data['action'] = 'Add';
        $data['product'] = Product::with('variants')->find($id);
        $data['categories'] = Category::where('status', 1)->orderBy('name', 'ASC')->get();
        $data['companies'] = Company::where('status', 1)->get();
        $data['colors'] = Color::get();
        $data['sizes'] = Size::orderBy('sort', 'ASC')->get();
        $data['size_guider']= DB::table('size_catergory')->where('status', '0')->get();
        $data['subcategories'] = !empty($id) ? SubCategory::where('status', 1)->where('category_id', $data['product']->category_id)->orderBy('name', 'ASC')->get() : '';

        return view('admin.product.manage', $data);
    }

    public function store(Request $request)
    {
        $edit_id = $request->edit_id;

        // Validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'sku' => 'required|unique:products,sku,' . $edit_id,
            'variants' => 'required|array|min:1',
            'variants.*.size' => 'required|exists:sizes,id',
            'variants.*.quantity' => 'required|integer|min:1',
            'variants.*.original_price' => 'required|numeric|min:0',
            'variants.*.price' => 'required|numeric|min:0',
            'image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Conditional validation for color
        $validator->sometimes('variants.*.color', 'required|exists:colors,id', function ($input) {
            return $input->category_id != 3; // Apply the rule if category_id is not 3
        });

        // Custom error messages
        $validator->setCustomMessages([
            'title.required' => 'Title field is required',
            'category_id.required' => 'Category field is required',
            'subcategory_id.required' => 'Subcategory field is required',
            'sku.required' => 'The SKU field is required.',
            'sku.unique' => 'The SKU has already been taken.',
            'variants.required' => 'At least one variant is required.',
            'variants.*.size.required' => 'Each variant must have a valid size.',
            'variants.*.color.required' => 'Each variant must have a valid color.',
            'variants.*.quantity.required' => 'Each variant must have a valid quantity.',
            'variants.*.original_price.required' => 'Each variant must have an original price.',
            'variants.*.price.required' => 'Each variant must have a price.',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        try {
            // The rest of your product handling logic
            $product = $edit_id ? Product::findOrFail($edit_id) : new Product();
            $product->title = $request->title;
            $product->sub_title = $request->sub_title;
            $product->category_id = $request->category_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->size_guider_id = $request->size_guider_id;
            $product->sku = $request->sku;
            $product->short_desc = $request->s_description;
            $product->description = $request->description;
            $product->status = $request->status ?? 0;
           // $product->trending = $request->trending ?? 0;
            $product->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->title)));

            // Handle product images
            $imageFields = ['image_1', 'image_2', 'image_3', 'image_4','image_5','image_6','image_7','image_8'];
            foreach ($imageFields as $imageField) {
                if ($request->hasFile($imageField)) {
                    deleteImageIfExists('products', $product->$imageField);
                    $image = $request->file($imageField);
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('uploads/products'), $imageName);
                    $product->$imageField = $imageName;
                }
            }

            // Save the product details
            $product->save();

            // Handle product variants
            if ($edit_id) {
                ProductVariant::where('product_id', $product->id)->delete(); // Delete existing variants if updating
            }

            foreach ($request->variants as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size_id' => $variant['size'],
                    'color_id' => $variant['color'] ?? null, // Allow null if not required
                    'quantity' => $variant['quantity'],
                    'original_price' => str_replace(',', '', $variant['original_price']),
                    'price' => str_replace(',', '', $variant['price']),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => $edit_id ? 'Product updated successfully.' : 'Product added successfully.',
                'url' => route('admin.products') // Redirect URL after success
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    // public function getProductList()
    // {
    //     $column = array('id', 'image_1', 'title', 'category_id', 'subcategory_id', 'trending', 'status', 'id');

    //     $row = Product::selectRaw('id, image_1, title, category_id, subcategory_id, trending, status, id');
    //     $total_records = $row->count();

    //     if (!empty($_POST['search']['value'])) {
    //         $row->where('title', 'LIKE', '%' . $_POST['search']['value'] . '%');
    //         $row->orWhere('category_id', 'LIKE', '%' . $_POST['search']['value'] . '%');
    //         $row->orWhere('subcategory_id', 'LIKE', '%' . $_POST['search']['value'] . '%');
    //     }

    //     if (!empty($_POST['order'])) {
    //         $row->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    //     } else {
    //         $row->orderBy('id', 'desc');
    //     }

    //     $number_filter_row = $row->count();

    //     if (!empty($_POST["length"]) && $_POST["length"] != -1) {
    //         $row->limit($_POST['length'])->offset($_POST['start']);
    //     }

    //     $result = $row->get();
    //     $data = array();

    //     foreach ($result as $key => $value) {
    //         $action = '<a class="btn btn-primary btn-sm ml-1" href="' . route('admin.product.manage', $value->id) . '"><i class="fa fa-edit"></i></a>';
    //         $action .= '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-sm ml-1 btn_delete"><i class="fa fa-trash"></i></a>';

    //         $image = '<div class="text-center"><img style="height:100px;width:100px;" src="' . isImage('products', $value->image_1) . '"></div>';

    //         $status = '<select style="width: 100%;" class="form-control" onchange="changeStatus(`' . $value->id . '`,this.value);">';
    //         $status .= '<option ' . ($value->status == '1' ? 'selected' : '') . ' value="1">Active</option>';
    //         $status .= '<option ' . ($value->status == '0' ? 'selected' : '') . ' value="0">Inactive</option>';
    //         $status .= '</select>';

    //         $trending = '<select style="width: 100%;" class="form-control" onchange="changeTrends(`' . $value->id . '`,this.value);">';
    //         $trending .= '<option ' . ($value->trending == '1' ? 'selected' : '') . ' value="1">Active</option>';
    //         $trending .= '<option ' . ($value->trending == '0' ? 'selected' : '') . ' value="0">Inactive</option>';
    //         $trending .= '</select>';

    //         $sub_array = array();
    //         $sub_array[] = ++$key;
    //         $sub_array[] = $image;
    //         $sub_array[] = '<b>Title: </b>' . $value->title . '<br><b>Category: </b>' . $value->category->name . '<br><b>Subcategory: </b>' . $value->subcategory->name;
    //         $sub_array[] = $trending;
    //         $sub_array[] = $status;
    //         $sub_array[] = $action;

    //         $data[] = $sub_array;
    //     }

    //     $output = array(
    //         "draw" => intval($_POST["draw"]),
    //         "recordsTotal" => $total_records,
    //         "recordsFiltered" => $number_filter_row,
    //         "data" => $data,
    //     );

    //     echo json_encode($output);
    // }
    public function getProductList()
{
    $column = array('id', 'image_1', 'title', 'category_id', 'subcategory_id', 'trending', 'status', 'id');

    $row = Product::query()->select('id', 'image_1', 'title', 'category_id', 'subcategory_id', 'trending', 'status');

    $total_records = Product::count();

    // Apply search filter
    if (!empty(request('search')['value'])) {
        $searchValue = request('search')['value'];
        $row->where(function ($query) use ($searchValue) {
            $query->where('title', 'LIKE', '%' . $searchValue . '%')
                  ->orWhere('category_id', 'LIKE', '%' . $searchValue . '%')
                  ->orWhere('subcategory_id', 'LIKE', '%' . $searchValue . '%');
        });
    }

    // Filter by category
    if (request()->has('category') && !empty(request('category'))) {
        $row->where('category_id', request('category'));
    }

    // Filter by subcategory
    if (request()->has('subcategory') && !empty(request('subcategory'))) {
        $row->where('subcategory_id', request('subcategory'));
    }

    $number_filter_row = $row->count();

    // Apply ordering
    if (!empty(request('order'))) {
        $orderColumnIndex = request('order')[0]['column'];
        $orderDirection = request('order')[0]['dir'];
        $row->orderBy($column[$orderColumnIndex], $orderDirection);
    } else {
        $row->orderBy('id', 'desc');
    }

    // Apply pagination
    if (!empty(request('length')) && request('length') != -1) {
        $row->limit(request('length'))->offset(request('start'));
    }

    $result = $row->get();

    $data = [];
    foreach ($result as $key => $value) {
        $action = '<a class="btn btn-primary btn-sm ml-1" href="' . route('admin.product.manage', $value->id) . '"><i class="fa fa-edit"></i></a>';
        $action .= '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-sm ml-1 btn_delete"><i class="fa fa-trash"></i></a>';

        $image = '<div class="text-center"><img style="height:100px;width:100px;" src="' . isImage('products', $value->image_1) . '"></div>';

        $status = '<select style="width: 100%;" class="form-control" onchange="changeStatus(`' . $value->id . '`,this.value);">';
        $status .= '<option ' . ($value->status == '1' ? 'selected' : '') . ' value="1">Active</option>';
        $status .= '<option ' . ($value->status == '0' ? 'selected' : '') . ' value="0">Inactive</option>';
        $status .= '</select>';

        $trending = '<select style="width: 100%;" class="form-control" onchange="changeTrends(`' . $value->id . '`,this.value);">';
        $trending .= '<option ' . ($value->trending == '1' ? 'selected' : '') . ' value="1">Active</option>';
        $trending .= '<option ' . ($value->trending == '0' ? 'selected' : '') . ' value="0">Inactive</option>';
        $trending .= '</select>';

        $sub_array = [];
        $sub_array[] = ++$key;
        $sub_array[] = $image;
        $sub_array[] = '<b>Title: </b>' . $value->title . '<br><b>Category: </b>' . ($value->category->name ?? '-') . '<br><b>Subcategory: </b>' . ($value->subcategory->name ?? '-');
        $sub_array[] = $trending;
        $sub_array[] = $status;
        $sub_array[] = $action;

        $data[] = $sub_array;
    }

    $output = [
        "draw" => intval(request("draw")),
        "recordsTotal" => $total_records,
        "recordsFiltered" => $number_filter_row,
        "data" => $data,
    ];

    return response()->json($output);
}


    public function getSubcategories(Request $request)
    {
        $categoryId = $request->input('category_id');
        $subcategories = Subcategory::where('category_id', $categoryId)->get();
        return response()->json($subcategories);
    }

    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $model = Product::find($id);
        $model->status = $status;
        $model->save();
        return response()->json('success');
    }

    public function changeTrends(Request $request)
    {
        $id = $request->id;
        $trending = $request->trending;
        $model = Product::find($id);
        $model->trending = $trending;
        $model->save();
        return response()->json('success');
    }


    public function delete(Request $request)
    {
        $id = $request->id;
        $product = Product::findOrFail($id);
        $imageFields = ['image_1', 'image_2', 'image_3', 'image_4'];
        foreach ($imageFields as $imageField) {
            deleteImageIfExists('products', $product->$imageField);
        }
        $product->delete();
        $variants = ProductVariant::where('product_id', $id)->get();
        foreach ($variants as $variant) {
            $imageFields = ['image_1', 'image_2', 'image_3', 'image_4'];
            foreach ($imageFields as $imageField) {
                deleteImageIfExists('products', $variant->$imageField);
            }
            $variant->delete();
        }
        return response()->json('success');
    }
    public function variants($product_id = '')
    {
        $data['title'] = 'Product Variants';
        $data['product_id'] = $product_id;
        return view('admin.product.variants', $data);
    }
    public function manageVariants($product_id, $id = '')
    {
        $data['product_id'] = $product_id;
        $data['title'] = 'Product Variants';
        // return $data['title'];
        $data['variant'] = ProductVariant::find($id);
        return view('admin.product.manage_variant', $data);
    }
    public function variantStore(Request $request)
    {

        $edit_id = $request->edit_id;
        $product_id = $request->product_id;
        $validator = Validator::make($request->all(), [
            'color' => 'required|unique:product_variants,color,' . $edit_id . ',id',
            'part_number' => 'required|unique:product_variants,part_number,' . $edit_id . ',id',
            'sku' => 'required|unique:product_variants,sku,' . $edit_id . ',id',
            'quantity' => 'required|min:1',
        ], [
            'color.required' => 'The color field is required.',
            'color.unique' => 'The color has already been taken.',
            'part_number.required' => 'The part number field is required.',
            'part_number.unique' => 'The part number has already been taken.',
            'sku.required' => 'The SKU field is required.',
            'sku.unique' => 'The SKU has already been taken.',
            'quantity.required' => 'The quantity field is required.',
            'quantity.min' => 'The quantity must be at least 1.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }

        if (!empty($edit_id)) {
            $variants = ProductVariant::where('id', $edit_id)->where('product_id', $product_id)->first();
            $message = 'Product Variant updated successfully';
        } else {
            $variants = new ProductVariant();
            $variants->product_id = $product_id;
            $message = 'Product Variant added successfully';
        }
        $imageFields = ['image_1', 'image_2', 'image_3', 'image_4'];
        foreach ($imageFields as $imageField) {
            if ($request->hasFile($imageField)) {
                deleteImageIfExists('products', $variants->$imageField);
                $image = $request->file($imageField);
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/products'), $imageName);
                $variants->$imageField = $imageName;
            }
        }

        $variants->color = $request->color;
        $variants->part_number = $request->part_number;
        $variants->sku = $request->sku;
        $variants->quantity = $request->quantity;
        $variants->save();

        return response()->json([
            'success' => true,
            'message' => $message,
            'url' => route('admin.product.variants', ['product_id' => $variants->product_id])
        ]);

    }
    public function getProductVariants()
    {
        $column = array('id','product_id', 'image_1', 'part_number', 'sku', 'color', 'quantity','status', 'id');

        $row = ProductVariant::selectRaw('id, product_id, image_1, part_number, sku, color, quantity,status, id')->where('product_id','=',$_POST['product_id']);

        $total_records = $row->count();

        if (!empty($_POST['search']['value'])) {
            $row->where('part_number', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $row->orWhere('sku', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $row->orWhere('color', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $row->orWhere('quantity', 'LIKE', '%' . $_POST['search']['value'] . '%');

        }
        if (!empty($_POST['order'])) {
            $row->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $row->orderBy('id', 'desc');
        }

        $number_filter_row = $row->count();
        if (!empty($_POST["length"]) && $_POST["length"] != -1) {
            $row->limit($_POST['length'])->offset($_POST['start']);
        }

        $result = $row->get();
        // dd($result);
        $data = array();
        foreach ($result as $key => $value) {
            $action = '<a class="btn btn-primary btn-sm ml-1" href="' . route('admin.product.manageVariants', ['product_id' => $value->product_id, 'id' => $value->id]) . '"><i class="fa fa-edit"></i></a>';

            $action .= '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-sm ml-1 btn_delete"><i class="fa fa-trash"></i></a>';
            $image = '<div class="text-center"><img style="height:100px;width:100px;" src="' . isImage('products', $value->image_1) . '"></div>';
            $status='<select style="width: 100%;" class="form-control" onchange="variantStatus(`'.$value->id.'`,this.value);">';
            $status .= '<option '.($value->status=='1'?'selected':'').' value="1">In Stock</option>';
            $status .= '<option '.($value->status=='0'?'selected':'').' value="0">Out of Stock</option>';
            $status .='</select>';

            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $image;
            $sub_array[] = $value->part_number;
            $sub_array[] = $value->sku;
            $sub_array[] = $value->color;
            $sub_array[] = $value->quantity;
            $sub_array[] = $status;
            $sub_array[] =  $action;
            $data[] = $sub_array;
        }
        $output = array(
            "draw"       =>  intval($_POST["draw"]),
            "recordsTotal"   =>  $total_records,
            "recordsFiltered"  =>  $number_filter_row,
            "data"       =>  $data,
        );
        echo json_encode($output);
    }
    public function variantStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $model = ProductVariant::find($id);
        $model->status = $status;
        $model->save();
        return response()->json('success');
    }
    public function variantDelete(Request $request)
    {
        $id = $request->id;
        $model = ProductVariant::findOrFail($id);
        $imageFields = ['image_1', 'image_2', 'image_3', 'image_4'];
        foreach ($imageFields as $imageField) {
            deleteImageIfExists('products', $model->$imageField);
        }
        $model->delete();
        return response()->json('success');
    }

}
