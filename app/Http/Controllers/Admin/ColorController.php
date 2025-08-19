<?php

namespace App\Http\Controllers\Admin;

use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ColorController extends Controller
{
    public function index()
    {
        $data['title'] = 'Colors';
        $data['action'] = 'List';
        return view('admin.colors.color_list', $data);
    }
    public function add()
    {
        $data['title'] = 'Colors';
        $data['action'] = 'Add';
        return view('admin.colors.color_manage', $data);
    }
    public function edit($id)
    {
        $data['title'] = 'Colors';
        $data['action'] = 'Edit';
        $data['edit_data'] = Color::where('id', $id)->first();
        return view('admin.colors.color_manage', $data);
    }
    public function store(Request $request)
    {
        $edit_id = $request->edit_id;
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:colors,name,' . $edit_id . ',id',
                'code' => 'required|unique:colors,code,' . $edit_id . ',id',
            ],[
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'code.required' => 'code is required',
                'code.regex' => 'code should not contain special characters',
                'code.unique' => 'code already exists',
            ]
        );
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        if (!empty($edit_id)) {
            $model = Color::where('id', $edit_id)->first();
            $message = 'Success! colors updated';
        } else {
            $model = new Color();
            $message = 'Success! colors added';
        }

        $model->name = $request->name;
        $model->code = $request->code;
        $model->save();
        return redirect()->route('admin.colors')->with('success', $message);
    }
    public function getList()
    {
        $column = array('id', 'name', 'code', 'created_at', 'id');

        $row = Color::selectRaw('id,name,code,created_at,id')->where('id', '>', '0');
        $total_count = $row->count();
        if (!empty($_POST['search']['value'])) {
            $row->where('name', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $row->orWhere('created_at', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }

        $number_filter_row = $row->count();
        if (!empty($_POST["length"]) && $_POST["length"] != -1) {
            $row->limit($_POST['length'])->offset($_POST['start']);
        }

        $result = $row->get();
        // dd($result);
        $data = array();
        foreach ($result as $key => $value) {
            $action = '<a class="btn btn-primary btn-sm" href="' . route('admin.colors.edit', $value->id) . '"><i class="fa fa-edit"></i></a>';
            $action .= '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-sm ml-1 delete"><i class="fa fa-trash"></i></a>';

            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->name;
            $sub_array[] = $value->code;
            $sub_array[] = date('d-m-Y', strtotime($value->created_at));
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        $output = array(
            "draw"       =>  intval($_POST["draw"]),
            "recordsTotal"   =>  $total_count,
            "recordsFiltered"  =>  $number_filter_row,
            "data"       =>  $data,
        );
        echo json_encode($output);
    }

    public function status(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $model = Color::find($id);
        $model->status = $status;
        $model->save();
        return response()->json('success');
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        $model = Color::find($id);
        $model->delete();
        return response()->json('success');

    }
}
