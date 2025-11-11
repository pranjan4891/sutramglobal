<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrintDesign;
use App\Models\Category;
use App\Models\SubCategory;
use Validator;
use Illuminate\Support\Str;

class PrintDesignController extends Controller
{
    public function index()
    {
        $data['title'] = 'Print Designs';
        $data['action'] = 'List';
        $data['categories'] = Category::where('status',1)->where('slug','print-on-demand')->get();
        return view('admin.masters.printdesigns', $data);
    }

    public function store(Request $request)
    {
        $edit_id = $request->edit_id;
        $rules = [
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'name' => 'required|string|max:255',
            'image' => ($edit_id ? 'nullable|mimes:jpeg,jpg,png,webp,svg' : 'required|mimes:jpeg,jpg,png,webp,svg'),
        ];
        $validator = Validator::make($request->all(), $rules, [
            'category_id.required' => 'Please select category',
            'subcategory_id.required' => 'Please select subcategory',
        ]);
        if ($validator->fails()) {
            return response()->json(['success'=>false,'message'=>$validator->errors()]);
        }

        try {
            if ($edit_id) {
                $model = PrintDesign::find($edit_id);
                $message = 'Print Design updated successfully';
            } else {
                $model = new PrintDesign();
                $message = 'Print Design added successfully';
            }

            if ($request->hasFile('image')) {
                // delete old
                deleteImageIfExists('pod_designs', $model->image);
                $image = $request->file('image');
                $imageName = time().'_'.$image->getClientOriginalName();
                $image->move(public_path('uploads/pod_designs'), $imageName);
                $model->image = $imageName;
            }

            $model->name = $request->name;
            $model->slug = Str::slug($request->name);
            $model->category_id = $request->category_id;
            $model->subcategory_id = $request->subcategory_id;

            // store the subcategory slug as requested
            $sub = SubCategory::find($request->subcategory_id);
            $model->subcategory_slug = $sub ? $sub->slug : null;

            $model->status = $request->status ?? 1;
            $model->position = $request->order_by;
            $model->save();

            return response()->json(['success'=>true,'message'=>$message]);
        } catch (\Exception $e) {
            return response()->json(['success'=>false,'message'=>$e->getMessage()]);
        }
    }

    public function getList(Request $request)
    {
        $column = ['id', 'image', 'name', 'category_id', 'status', 'position', 'created_at', 'id'];
        $query = PrintDesign::with('category','subcategory')->where('id', '>', 0);

        $total_row = $query->count();
        if (isset($_POST['search'])) {
            $q = $_POST['search']['value'];
            $query->where(function($subq) use ($q) {
                $subq->where('name', 'LIKE', "%$q%")
                     ->orWhere('subcategory_slug', 'LIKE', "%$q%");
            });
        }
        if (isset($_POST['order'])) {
            $query->orderBy($column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        } else {
            $query->orderBy('id','desc');
        }
        $filter_row = $query->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $query->skip($_POST["start"])->take($_POST["length"]);
        }
        $result = $query->get();
        $data = [];
        foreach ($result as $key => $value) {
            $action = '<a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick="edit(`'.route('admin.masters.printDesignEdit', $value->id).'`);"><i class="fa fa-edit"></i></a>';
            $action .= '<a href="javascript:void(0)" data-id="'.$value->id.'" class="btn btn-danger btn-sm ml-1 btn_delete"><i class="fa fa-trash"></i></a>';
            $image = '<div class="text-center"><img style="height:80px;width:80px;" src="'.isImage('pod_designs', $value->image).'"></div>';
            $status = '<select style="width:100%;" class="form-control" onchange="printDesignStatus(`'.$value->id.'`,this.value);">';
            $status .= '<option '.($value->status == 1 ? 'selected' : '').' value="1">Active</option>';
            $status .= '<option '.($value->status == 0 ? 'selected' : '').' value="0">Inactive</option>';
            $status .= '</select>';
            $sub_array = [];
            $sub_array[] = ++$key;
            $sub_array[] = $image;
            $sub_array[] = $value->name;
            $sub_array[] = $value->category ? $value->category->name : '-';
            $sub_array[] = $value->subcategory ? $value->subcategory->name : '-';
            $sub_array[] = date('d-m-Y', strtotime($value->created_at));
            $sub_array[] = $status;
            $sub_array[] = $value->position;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        $output = [
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $total_row,
            "recordsFiltered" => $filter_row,
            "data" => $data,
        ];
        echo json_encode($output);
    }

    public function edit($id)
    {
        $model = PrintDesign::find($id);
        return response()->json(['success'=>true,'data'=>$model]);
    }

    public function status(Request $request)
    {
        $model = PrintDesign::find($request->id);
        $model->status = $request->status;
        $model->save();
        return response()->json('success');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $model = PrintDesign::find($id);
        deleteImageIfExists('pod_designs', $model->image);
        $model->delete();
        return response()->json('success');
    }

    // AJAX: return subcategories for a category
    public function getSubcategories(Request $request)
    {
        $catId = $request->category_id;
        $subs = SubCategory::where('category_id', $catId)->where('status',1)->get();
        return response()->json(['success'=>true,'data'=>$subs]);
    }
}
