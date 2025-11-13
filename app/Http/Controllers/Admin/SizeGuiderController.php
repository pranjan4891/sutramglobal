<?php

namespace App\Http\Controllers\Admin;

use App\Models\SizeGuider;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SizeGuiderController extends Controller
{
    public function index()
    {
        $data['title'] = 'Size Guiders';
        $data['action'] = 'List';
        return view('admin.size_guiders.size_guider_list', $data);
    }
    public function add()
    {
        $data['title'] = 'Size Guiders';
        $data['action'] = 'Add';
        $data['categories'] = Category::where('status', 1)->get();
        $data['sizes'] = Size::where('status', 1)->get();
        return view('admin.size_guiders.size_guider_manage', $data);
    }
    public function edit($id)
    {
        $data['title'] = 'Size Guiders';
        $data['action'] = 'Edit';
        $data['edit_data'] = SizeGuider::with(['category', 'subCategory', 'size'])->where('id', $id)->first();
        $data['categories'] = Category::where('status', 1)->orWhere('id', $data['edit_data']->cat_id)->get();
        $data['sizes'] = Size::where('status', 1)->orWhere('id', $data['edit_data']->size_id)->get();
        if ($data['edit_data']->cat_id) {
            $data['subcategories'] = SubCategory::where('category_id', $data['edit_data']->cat_id)->where('status', 1)->orWhere('id', $data['edit_data']->sub_cat_id)->get();
        }
        return view('admin.size_guiders.size_guider_manage', $data);
    }
    public function store(Request $request)
    {
        $edit_id = $request->edit_id;
        $validator = Validator::make($request->all(),
            [
                'cat_id' => 'required|exists:categories,id',
                'sub_cat_id' => 'nullable|exists:sub_categories,id',
                'size_id' => 'required|exists:sizes,id',
                'chest' => 'nullable|numeric',
                'length' => 'nullable|numeric',
                'shoulder' => 'nullable|numeric',
                'sleeve' => 'nullable|numeric',
                'waist' => 'nullable|numeric',
            ],[
                'cat_id.required' => 'Category is required',
                'cat_id.exists' => 'Invalid category',
                'sub_cat_id.exists' => 'Invalid sub category',
                'size_id.required' => 'Size is required',
                'size_id.exists' => 'Invalid size',
                'chest.numeric' => 'Chest must be a number',
                'length.numeric' => 'Length must be a number',
                'shoulder.numeric' => 'Shoulder must be a number',
                'sleeve.numeric' => 'Sleeve must be a number',
                'waist.numeric' => 'Waist must be a number',
            ]
        );
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        if (!empty($edit_id)) {
            $model = SizeGuider::where('id', $edit_id)->first();
            $message = 'Success! Size Guider updated';
        } else {
            $model = new SizeGuider();
            $message = 'Success! Size Guider added';
        }

        $model->cat_id = $request->cat_id;
        $model->sub_cat_id = $request->sub_cat_id ?: null;
        $model->size_id = $request->size_id;
        $model->chest = $request->chest;
        $model->length = $request->length;
        $model->shoulder = $request->shoulder;
        $model->sleeve = $request->sleeve;
        $model->waist = $request->waist;
        $model->save();
        return redirect()->route('admin.size_guiders')->with('success', $message);
    }

    public function getList()
    {
        $columns = array('id', 'cat_id', 'sub_cat_id', 'size_id', 'chest', 'length', 'shoulder', 'sleeve', 'waist');

        $row = SizeGuider::with(['category', 'subCategory', 'size'])->select($columns)->where('id', '>', 0);

        $total_count = $row->count();

        if (!empty($_POST['search']['value'])) {
            $search = $_POST['search']['value'];
            $row->where(function ($query) use ($search) {
                $query->whereHas('category', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('subCategory', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('size', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%')
                      ->orWhere('code', 'LIKE', '%' . $search . '%');
                });
            });
        }

        $number_filter_row = $row->count();

        if (!empty($_POST["length"]) && $_POST["length"] != -1) {
            $row->limit($_POST['length'])->offset($_POST['start']);
        }

        $result = $row->get();

        $data = array();
        foreach ($result as $key => $value) {
            $action = '<a class="btn btn-primary btn-sm" href="' . route('admin.size_guiders.edit', $value->id) . '"><i class="fa fa-edit"></i></a>';
            $action .= '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-sm ml-1 delete"><i class="fa fa-trash"></i></a>';

            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->category ? $value->category->name : '-';
            $sub_array[] = $value->subCategory ? $value->subCategory->name : '-';
            $sub_array[] = $value->size ? $value->size->name . ' (' . $value->size->code . ')' : '-';
            $sub_array[] = $value->chest ?: '-';
            $sub_array[] = $value->length ?: '-';
            $sub_array[] = $value->shoulder ?: '-';
            $sub_array[] = $value->sleeve ?: '-';
            $sub_array[] = $value->waist ?: '-';
            $sub_array[] = $action;
            $data[] = $sub_array;
        }

        $output = array(
            "draw"            => intval($_POST["draw"]),
            "recordsTotal"    => $total_count,
            "recordsFiltered" => $number_filter_row,
            "data"            => $data,
        );

        return response()->json($output);
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $model = SizeGuider::find($id);
        $model->delete();
        return response()->json('success');
    }

    public function getSubCategories(Request $request)
    {
        $cat_id = $request->cat_id;
        $subcategories = SubCategory::where('category_id', $cat_id)->where('status', 1)->get();
        return response()->json($subcategories);
    }
}
