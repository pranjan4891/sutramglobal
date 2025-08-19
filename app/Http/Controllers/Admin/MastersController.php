<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MastersController extends Controller
{
    //  Category Part
    public function category()
    {
        $data['title'] = 'Categories';
        $data['action'] = 'List';
        return view('admin.masters.category', $data);
    }
    public function categoryStore(Request $request)
    {
        $edit_id = $request->edit_id;
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
           // 'image' => ($edit_id ? 'nullable|mimes:jpeg,jpg,png,webp,svg' : 'required|mimes:jpeg,jpg,png,webp,svg'),
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ]);
        }
        try {
            if (!empty($edit_id)) {
                $master = Category::find($edit_id);
                $message = 'Category Updated Successfully';
            } else {
                $master = new Category();
                $message = 'Category Added Successfully';
            }
            if ($request->hasFile('image')) {
                deleteImageIfExists('categories', $master->image);
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/categories'), $imageName);
                $master->image = $imageName;
            }
            $master->name = $request->name;
            $master->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));
            $master->image = !empty($fileName) ? $fileName : $master->image;
            $master->status = $request->status;
            $master->order_by = $request->order_by;
            $master->save();
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getCategoryList(Request $request)
    {
        $column = array('id', 'image', 'name', 'status', 'order_by', 'created_at', 'id');
        $query = Category::where('id', '>', '0');

        // Count total rows before filtering
        $total_row = $query->count();

        // Search filtering
        if (isset($_POST['search']) && $_POST['search']['value'] != '') {
            $query->where('name', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }

        // Order by
        if (isset($_POST['order'])) {
            $query->orderBy($column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Count after filtering
        $filter_row = $query->count();

        // Pagination
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $query->skip($_POST["start"])->take($_POST["length"]);
        }

        // Get the results
        $result = $query->get();

        // Prepare data for DataTable
        $data = array();
        foreach ($result as $key => $value) {
            $action = '<a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick="edit(`' . route('admin.masters.categoryEdit', $value->id) . '`);"><i class="fa fa-edit"></i></a>';
            $action .= '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-sm ml-1 btn_delete"><i class="fa fa-trash"></i></a>';
            $image = '<div class="text-center"><img style="height: 80px;width: 80px;" src="' . isImage('categories', $value->image) . '"></div>';

            // Status dropdown
            $status = '<select style="width: 100%;" class="form-control" onchange="categoryStatus(`' . $value->id . '`, this.value);">';
            $status .= '<option ' . ($value->status == '1' ? 'selected' : '') . ' value="1">Active</option>';
            $status .= '<option ' . ($value->status == '0' ? 'selected' : '') . ' value="0">Inactive</option>';
            $status .= '</select>';

            // Construct each row's data
            $sub_array = array();
            $sub_array[] = ++$key;  // Row number
            $sub_array[] = $image;  // Image
            $sub_array[] = $value->name;  // Name
            $sub_array[] = date('d-m-Y', strtotime($value->created_at));  // Created at
            $sub_array[] = $status;  // Status dropdown
            $sub_array[] = $value->order_by;  // Order By
            $sub_array[] = $action;  // Action buttons

            $data[] = $sub_array;
        }

        // Prepare the output for DataTable
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $total_row,
            "recordsFiltered" => $filter_row,
            "data" => $data
        );

        echo json_encode($output);
    }

    public function categoryEdit($id)
    {
        $role = Category::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    public function categoryStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $model = Category::find($id);
        $model->Statstatusus = $status;
        $model->save();
        return response()->json('success');
    }

    public function categoryDelete(Request $request)
    {
        $id = $request->id;
        $model = Category::find($id);
        deleteImageIfExists('categories', $model->image);
        $model->delete();
        return response()->json('success');
    }
    //  Sub Category Part
    public function subCategory()
    {
        $data['title'] = 'Sub Categories';
        $data['action'] = 'List';
        $data['categories'] = Category::where('status', 1)->get();
        return view('admin.masters.subcategories', $data);
    }
    public function subCategoryStore(Request $request)
    {
        $edit_id = $request->edit_id;
        $validation = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            //'image' => ($edit_id ? 'nullable|mimes:jpeg,jpg,png,webp,svg' : 'required|mimes:jpeg,jpg,png,webp,svg'),
        ],[
            'category_id.required' => 'Please Select Category',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ]);
        }
        try {
            if (!empty($edit_id)) {
                $master = SubCategory::find($edit_id);
                $message = 'Sub Category Updated Successfully';
            } else {
                $master = new SubCategory();
                $message = 'Sub Category Added Successfully';
            }
            if ($request->hasFile('image')) {
                deleteImageIfExists('subcategories', $master->image);
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/subcategories'), $imageName);
                $master->image = $imageName;
            }
            $master->name = $request->name;
            $master->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));
            $master->category_id = $request->category_id;
            $master->image = !empty($fileName) ? $fileName : $master->image;
            $master->status = $request->status;
            $master->position = $request->order_by;
            $master->save();
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getSubCategoryList(Request $request)
    {
        $column = ['id', 'image', 'name', 'category_id', 'status','position', 'created_at', 'id'];
        $query = SubCategory::with('category')->where('id', '>', '0');

        $total_row = $query->count();
        if (isset($_POST['search'])) {
            $query->where('name', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $query->orWhere('category_id', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }
        if (isset($_POST['order'])) {
            $query->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $query->orderBy('id', 'desc');
        }
        $filter_row = $query->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $query->skip($_POST["start"])->take($_POST["length"]);
        }
        $result = $query->get();
        $data = array();
        foreach ($result as $key => $value) {
            $action = '<a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick="edit(`' . route('admin.masters.subCategoryEdit', $value->id) . '`);"><i class="fa fa-edit"></i></a>';
            $action .= '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-sm ml-1 btn_delete"><i class="fa fa-trash"></i></a>';
            $image = '<div class="text-center"><img style="height: 80px;width: 80px;" src="' . isImage('subcategories', $value->image) . '"></div>';
            $status='<select style="width: 100%;" class="form-control" onchange="subCategoryStatus(`'.$value->id.'`,this.value);">';
            $status .= '<option '.($value->status=='1'?'selected':'').' value="1">Active</option>';
            $status .= '<option '.($value->status=='0'?'selected':'').' value="0">Inactive</option>';
            $status .='</select>';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $image;
            $sub_array[] = $value->name;
            $sub_array[] = $value->category->name;
            $sub_array[] = date('d-m-Y', strtotime($value->created_at));
            $sub_array[] = $status;
            $sub_array[] = $value->position;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        $output = array(
            "draw"   =>  intval($_POST["draw"]),
            "recordsTotal" =>  $total_row,
            "recordsFiltered" =>  $filter_row,
            "data"  =>  $data
        );

        echo json_encode($output);
    }

    public function subCategoryEdit($id)
    {
        $role = SubCategory::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    public function subCategoryStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $model = SubCategory::find($id);
        $model->status = $status;
        $model->save();
        return response()->json('success');
    }

    public function subCategoryDelete(Request $request)
    {
        $id = $request->id;
        $model = SubCategory::find($id);
        deleteImageIfExists('subcategories', $model->image);
        $model->delete();
        return response()->json('success');
    }


//  Slider Part
    public function slider()
    {
        $data['title'] = 'Slider';
        $data['action'] = 'List';
        return view('admin.masters.sliders', $data);
    }
    public function sliderStore(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => ($request->edit_id ? 'nullable|mimes:jpeg,jpg,png' : 'required|mimes:jpeg,jpg,png'),
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ]);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = date('dmy') . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path("coded-slider"), $fileName);
        }
        $id = $request->edit_id;
        try {
            if (!empty($id)) {
                $master = Slider::find($id);
                if ($request->hasFile('image')) {
                    if ($master->image) {
                        $oldImagePath = public_path("/coded-slider/{$master->image}");
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                }
                $master->title = $request->title;
                $master->image = !empty($fileName) ? $fileName : $master->image;
                $master->is_active = $request->is_active;
                $master->order_by = $request->order_by;
                $master->save();
                $message = 'Slider Updated Successfully';
            } else {
                $master = new Slider();
                $master->title = $request->title;
                $master->image = $fileName;
                $master->is_active = $request->is_active;
                $master->order_by = $request->order_by;
                $master->save();
                $message = 'Slider Added Successfully';
            }
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getSliderList(Request $request)
    {
        $column = array('id', 'image', 'title', 'is_active', 'created_at', 'id');
        $query = Slider::where('id', '>', '0');

        $total_row = $query->count();
        if (isset($_POST['search'])) {
            $query->where('title', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }
        if (isset($_POST['order'])) {
            $query->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $query->orderBy('id', 'desc');
        }
        $filter_row = $query->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $query->skip($_POST["start"])->take($_POST["length"]);
        }
        $result = $query->get();
        $data = array();
        foreach ($result as $key => $value) {
            $action = '<a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick="edit(`' . route('admin.masters.sliderEdit', $value->id) . '`);"><i class="fa fa-edit"></i></a>';
            $action .= '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-sm ml-1 btn_delete"><i class="fa fa-trash"></i></a>';
            $image = '<img style="height:80px;width:100%;" src="' . asset('public/coded-slider/' .$value->image) . '">';
            $status='<select style="width: 100%;" class="form-control" onchange="sliderStatus(`'.$value->id.'`,this.value);">';
            $status .= '<option '.($value->is_active=='1'?'selected':'').' value="1">Active</option>';
            $status .= '<option '.($value->is_active=='2'?'selected':'').' value="2">Inactive</option>';
            $status .='</select>';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $image;
            $sub_array[] = $value->title;
            $sub_array[] = date('d-m-Y', strtotime($value->created_at));
            $sub_array[] = $status;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        $output = array(
            "draw"   =>  intval($_POST["draw"]),
            "recordsTotal" =>  $total_row,
            "recordsFiltered" =>  $filter_row,
            "data"  =>  $data
        );

        echo json_encode($output);
    }

    public function sliderEdit($id)
    {
        $role = Slider::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    public function sliderStatus(Request $request)
    {
        $id = $request->id;
        $is_active = $request->is_active;
        $model = Slider::find($id);
        $model->is_active = $is_active;
        $model->save();
        return response()->json('success');
    }

    public function sliderDelete(Request $request)
    {
        $id = $request->id;
        $model = Slider::find($id);
        deleteImageIfExists('coded-slider', $model->image);
        $model->delete();
        return response()->json('success');
    }


}
