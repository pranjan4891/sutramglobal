<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;


class CouponController extends Controller
{
    public function index()
    {
        $data['title'] = 'Coupons';
        $data['action'] = 'List';
        return view('admin.coupon.list', $data);
    }

    public function add()
    {
        $data['title'] = 'Coupons';
        $data['action'] = 'Add';
        return view('admin.coupon.manage', $data);
    }

    public function edit($id)
    {
        $data['title'] = 'Coupons';
        $data['action'] = 'Edit';
        $data['coupon'] = Coupon::find($id);
        return view('admin.coupon.manage', $data);
    }

    public function store(Request $request)
    {
        $edit_id = $request->edit_id;

        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:coupons,code,' . $edit_id . ',id',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_per_customer' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ], [
            'code.required' => 'Coupon code is required',
            'code.unique' => 'Coupon code already exists',
            'discount_type.required' => 'Discount type is required',
            'discount_value.required' => 'Discount value is required',
            'min_order_value.numeric' => 'Minimum order value must be a number',
            'start_date.required' => 'Start date is required',
            'end_date.required' => 'End date is required',
            'end_date.after_or_equal' => 'End date must be after or equal to the start date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!empty($edit_id)) {
            $model = Coupon::find($edit_id);
            $message = 'Success! Coupon updated';
        } else {
            $model = new Coupon();
            $message = 'Success! Coupon added';
        }

        $model->code = $request->code;
        $model->discount_type = $request->discount_type;
        $model->discount_value = $request->discount_value;
        $model->min_order_value = $request->min_order_value ?? 0;
        $model->start_date = $request->start_date;
        $model->end_date = $request->end_date;
        $model->usage_limit = $request->usage_limit ?? null;
        $model->usage_per_customer = $request->usage_per_customer ?? null;
        $model->description=$request->description;
        $model->status = $request->status;

        $model->save();

        return redirect()->route('admin.coupons')->with('success', $message);
    }

    public function getList()
    {
        $column = ['id', 'code', 'discount_type', 'discount_value', 'start_date', 'end_date', 'status', 'id'];

        $row = Coupon::selectRaw('id, code, discount_type, discount_value, start_date, end_date, status, id')->where('id', '>', 0);
        $total_count = $row->count();

        if (!empty($_POST['search']['value'])) {
            $row->where('code', 'LIKE', '%' . $_POST['search']['value'] . '%')
                ->orWhere('start_date', 'LIKE', '%' . $_POST['search']['value'] . '%')
                ->orWhere('end_date', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }

        $number_filter_row = $row->count();

        if (!empty($_POST["length"]) && $_POST["length"] != -1) {
            $row->limit($_POST["length"])->offset($_POST["start"]);
        }

        $result = $row->get();

        // Status mapping array
        $status_labels = [
            1 => 'Active',
            0 => 'Inactive',
            2 => 'Expired',
        ];

        $data = [];
        foreach ($result as $key => $value) {
            $action = '<a class="btn btn-primary btn-sm" href="' . route('admin.coupons.edit', $value->id) . '"><i class="fa fa-edit"></i></a>';
            $action .= '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-sm ml-1 delete"><i class="fa fa-trash"></i></a>';

            // Status button with data attributes for the modal
            $status = '<button type="button" class="btn btn-sm ' . $this->getStatusClass($value->status) . ' status-btn"
                            data-id="' . $value->id . '"
                            data-status="' . $value->status . '">
                            ' . ($status_labels[$value->status] ?? 'Unknown') . '
                       </button>';

            $sub_array = [];
            $sub_array[] = ++$key; // Index
            $sub_array[] = $value->code; // Coupon code
            $sub_array[] = ucfirst($value->discount_type); // Discount type
            $sub_array[] = $value->discount_value; // Discount value
            $sub_array[] = date('d-m-Y', strtotime($value->start_date)) . '<br>' . date('d-m-Y', strtotime($value->end_date)); // Validity
            $sub_array[] = $status; // Status button
            $sub_array[] = $action; // Actions

            $data[] = $sub_array;
        }

        $output = [
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $total_count,
            "recordsFiltered" => $number_filter_row,
            "data" => $data,
        ];

        echo json_encode($output);
    }

    // Helper method to get button class based on status
    private function getStatusClass($status)
    {
        switch ($status) {
            case 1:
                return 'btn-success';
            case 0:
                return 'btn-warning';
            case 2:
                return 'btn-danger';
            default:
                return 'btn-secondary';
        }
    }


    public function status(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $model = Coupon::find($id);
        $model->status = $status;
        $model->save();
        return response()->json('success');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $model = Coupon::find($id);
        $model->delete();
        return response()->json('success');
    }
}
