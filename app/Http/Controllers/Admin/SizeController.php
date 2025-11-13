<?php

namespace App\Http\Controllers\Admin;

use App\Models\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    public function index()
    {
        $data['title'] = 'Sizes';
        $data['action'] = 'List';
        return view('admin.sizes.size_list', $data);
    }
    public function add()
    {
        $data['title'] = 'Sizes';
        $data['action'] = 'Add';
        return view('admin.sizes.size_manage', $data);
    }
    public function edit($id)
    {
        $data['title'] = 'Sizes';
        $data['action'] = 'Edit';
        $data['edit_data'] = Size::where('id', $id)->first();
       // dd($data['edit_data']);
        return view('admin.sizes.size_manage', $data);
    }
    public function store(Request $request)
    {
        $edit_id = $request->edit_id;
        $validator = Validator::make($request->all(),
            [
                'sort' => 'required|integer',
                'name' => 'required',
                'code' => 'required',
                'status' => 'required|in:0,1',
            ],[
                'sort.required' => 'Sort is required',
                'sort.integer' => 'Sort must be a number',
                'name.required' => 'Name is required',
                'code.required' => 'Code is required',
                'status.required' => 'Status is required',
                'status.in' => 'Invalid status value',
            ]
        );
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        if (!empty($edit_id)) {
            $model = Size::where('id', $edit_id)->first();
            $message = 'Success! Size updated';
        } else {
            $model = new Size();
            $message = 'Success! Size added';
        }

        $model->sort = $request->sort;
        $model->name = $request->name;
        $model->code = $request->code;
        $model->status = $request->status;
        $model->save();
        return redirect()->route('admin.sizes')->with('success', $message);
    }
    // public function getList()
    // {
    //     $column = array('id', 'code', 'chest', 'waist', 'length', 'type', 'category',  'id');

    //     $row = Size::selectRaw('id', 'code', 'chest', 'waist', 'length', 'type', 'category',  'id')->where('id', '>', '0');
    //     $total_count = $row->count();
    //     if (!empty($_POST['search']['value'])) {
    //         $row->where('code', 'LIKE', '%' . $_POST['search']['value'] . '%');
    //         $row->orWhere('created_at', 'LIKE', '%' . $_POST['search']['value'] . '%');
    //     }


    //     $number_filter_row = $row->count();
    //     if (!empty($_POST["length"]) && $_POST["length"] != -1) {
    //         $row->limit($_POST['length'])->offset($_POST['start']);
    //     }

    //     $result = $row->get();
    //     // dd($result);
    //     $data = array();
    //     foreach ($result as $key => $value) {
    //         $action = '<a class="btn btn-primary btn-sm" href="' . route('admin.sizes.edit', $value->id) . '"><i class="fa fa-edit"></i></a>';
    //         $action .= '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-sm ml-1 delete"><i class="fa fa-trash"></i></a>';

    //         $sub_array = array();
    //         $sub_array[] = ++$key;
    //         $sub_array[] = $value->code;
    //         $sub_array[] = $value->category;
    //         $sub_array[] = $value->type;
    //         $sub_array[] = $value->chest;
    //         $sub_array[] = $value->waist;
    //         $sub_array[] = $value->length;
    //         $sub_array[] = $action;
    //         $data[] = $sub_array;
    //     }
    //     $output = array(
    //         "draw"       =>  intval($_POST["draw"]),
    //         "recordsTotal"   =>  $total_count,
    //         "recordsFiltered"  =>  $number_filter_row,
    //         "data"       =>  $data,
    //     );
    //     echo json_encode($output);
    // }
    public function getList()
    {
        $columns = array('id', 'sort', 'name', 'code', 'status');

        // Select columns from Size model
        $row = Size::select($columns)->where('id', '>', 0);

        // Get total count
        $total_count = $row->count();

        // Search functionality
        if (!empty($_POST['search']['value'])) {
            $search = $_POST['search']['value'];
            $row->where(function ($query) use ($search) {
                $query->where('sort', 'LIKE', '%' . $search . '%')
                    ->orWhere('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('code', 'LIKE', '%' . $search . '%');
            });
        }

        // Get the filtered count after search
        $number_filter_row = $row->count();

        // Handle pagination (length and start)
        if (!empty($_POST["length"]) && $_POST["length"] != -1) {
            $row->limit($_POST['length'])->offset($_POST['start']);
        }

        // Get the results
        $result = $row->get();

        // Prepare the data to send
        $data = array();
        foreach ($result as $key => $value) {
            $action = '<a class="btn btn-primary btn-sm" href="' . route('admin.sizes.edit', $value->id) . '"><i class="fa fa-edit"></i></a>';
            $action .= '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-sm ml-1 delete"><i class="fa fa-trash"></i></a>';

            $status = $value->status == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';

            $sub_array = array();
            $sub_array[] = ++$key; // Increment the key to represent the index
            $sub_array[] = $value->sort;
            $sub_array[] = $value->name;
            $sub_array[] = $value->code;
            $sub_array[] = $status;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }

        // Prepare the response in datatables format
        $output = array(
            "draw"            => intval($_POST["draw"]),
            "recordsTotal"    => $total_count,
            "recordsFiltered" => $number_filter_row,
            "data"            => $data,
        );

        // Return the output as JSON response
        return response()->json($output);
    }


    public function status(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $model = Size::find($id);
        $model->status = $status;
        $model->save();
        return response()->json('success');
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        $model = Size::find($id);
        $model->delete();
        return response()->json('success');
    }
}
