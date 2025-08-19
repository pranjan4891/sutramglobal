<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index()
    {
        $data['title'] = 'Companies';
        $data['action'] = 'List';
        return view('admin.corporate_buyer.company_list', $data);
    }
    public function add()
    {
        $data['title'] = 'Company';
        $data['action'] = 'Add';
        return view('admin.corporate_buyer.company_manage', $data);
    }
    public function edit($id)
    {
        $data['title'] = 'Company';
        $data['action'] = 'Edit';
        $data['edit_data'] = Company::where('id', $id)->first();
        return view('admin.corporate_buyer.company_manage', $data);
    }
    public function store(Request $request)
    {
        $edit_id = $request->edit_id;
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:companies,name,' . $edit_id . ',id',
                'typo' => 'required|unique:companies,typo,' . $edit_id . ',id',
                // 'typo' => 'required|regex:/^@[a-zA-Z0-9]+(?:\.[a-zA-Z0-9]+)*$/|unique:companies,typo,' . $edit_id . ',id',

                'image' => ($edit_id ? 'nullable|mimes:jpeg,jpg,png,webp' : 'required|mimes:jpeg,jpg,png,webp'),
            ],[
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'typo.required' => 'Typo is required',
                'typo.regex' => 'Typo should not contain special characters',
                'typo.unique' => 'Typo already exists',
                'image.required' => 'Logo is required',
            ]
        );
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        if (!empty($edit_id)) {
            $model = Company::where('id', $edit_id)->first();
            $message = 'Success! Company updated';
        } else {
            $model = new Company();
            $message = 'Success! Company added';
        }
        if ($request->hasFile('image')) {
            deleteImageIfExists('companies', $model->image);
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/companies'), $imageName);
            $model->image = $imageName;
        }
        $model->name = $request->name;
        $model->typo = $request->typo;
        $model->about = $request->about;
        $model->address = $request->address;
        $model->status = $request->status;
        $model->save();
        return redirect()->route('admin.companies')->with('success', $message);
    }
    public function getList()
    {
        $column = array('id', 'image', 'name','typo', 'created_at', 'status', 'id');

        $row = Company::selectRaw('id,image,name,typo,created_at,status,id')->where('id', '>', '0');
        $total_count = $row->count();
        if (!empty($_POST['search']['value'])) {
            $row->where('name', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $row->orWhere('created_at', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
            $action = '<a class="btn btn-primary btn-sm" href="' . route('admin.company.edit', $value->id) . '"><i class="fa fa-edit"></i></a>';
            $action .= '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-sm ml-1 delete"><i class="fa fa-trash"></i></a>';
            $image = '<img style="height:80px;width:150px;" src="' . isImage('companies' , $value->image) . '">';
            $status='<select style="width: 100%;" class="form-control" onchange="change_status(`'.$value->id.'`,this.value);">';
            $status .= '<option '.($value->status=='1'?'selected':'').' value="1">Active</option>';
            $status .= '<option '.($value->status=='0'?'selected':'').' value="0">Inactive</option>';
            $status .='</select>';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $image;
            $sub_array[] = $value->name;
            $sub_array[] = $value->typo;
            $sub_array[] = date('d-m-Y', strtotime($value->created_at));
            $sub_array[] = $status;
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
        $model = Company::find($id);
        $model->status = $status;
        $model->save();
        return response()->json('success');
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        $model = Company::find($id);
        if ($model->image) {
            deleteImageIfExists('companies', $model->image);
        }
        $model->delete();
        return response()->json('success');

    }
}
