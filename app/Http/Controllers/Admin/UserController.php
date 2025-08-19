<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Display the User list view
    public function index()
    {
        $data['title'] = 'Users';
        $data['action'] = 'List';
        return view('admin.users', $data); // Assuming your view file is admin/users.blade.php
    }

    // Fetch user data for the datatable
    public function getUsersLists()
    {
        $column = array('id', 'first_name', 'last_name', 'email', 'mobile', 'status', 'profile_photo', 'created_at', 'id');

        $row = User::select('id', 'first_name', 'last_name', 'email', 'mobile', 'status', 'profile_photo', 'created_at');
        $total_count = $row->count();

        // Search filter
        if (!empty($_POST['search']['value'])) {
            $search = $_POST['search']['value'];
            $row->where('first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                ->orWhere('email', 'LIKE', '%' . $search . '%')
                ->orWhere('mobile', 'LIKE', '%' . $search . '%');
        }

        $number_filter_row = $row->count();

        // Pagination
        if (!empty($_POST["length"]) && $_POST["length"] != -1) {
            $row->limit($_POST['length'])->offset($_POST['start']);
        }

        $result = $row->where('is_deleted','0')->get();

        $data = array();
        foreach ($result as $key => $value) {
            // Determine the appropriate status icon and styling
            $statusIcon = $value->status == 1
                ? '<i class="fa fa-toggle-on text-success"></i>'  // Active status icon
                : '<i class="fa fa-toggle-off text-danger"></i>'; // Inactive status icon

            // Define the action buttons
            $action = '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-sm btn-outline-secondary change-status">'
                . $statusIcon . '</a>'; // Toggle button for status change
         //   $action .= ' <a class="btn btn-primary btn-sm" href="' . route('admin.users.edit', $value->id) . '"><i class="fa fa-edit"></i></a>'; // Edit button
            $action .= ' <a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-sm ml-1 btn_delete"><i class="fa fa-trash"></i></a>'; // Delete button

            // Prepare the row data
            $sub_array = [];
            $sub_array[] = ++$key;
            $sub_array[] = '<img src="' . isImage('profile_photos', $value->profile_photo) . '" alt="Profile Photo" width="50" height="50">'; // Profile photo
            $sub_array[] = $value->first_name . ' ' . $value->last_name; // Full name
            $sub_array[] = $value->mobile; // Mobile number
            $sub_array[] = $value->email; // Email
            $sub_array[] = date('d-m-Y', strtotime($value->created_at)); // Created date
            $sub_array[] = $value->status == 1
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Inactive</span>'; // Status badge
            $sub_array[] = $action; // Action buttons

            // Add the row data to the data array
            $data[] = $sub_array;
        }

        // Response for datatable
        $output = array(
            "draw"            => intval($_POST["draw"]),
            "recordsTotal"    => $total_count,
            "recordsFiltered" => $number_filter_row,
            "data"            => $data,
        );
        echo json_encode($output);
    }

    // Update the user status (Active/Inactive)
    public function changeStatus(Request $request)
    {
        $id = $request->id;

        $user = User::find($id);
        if ($user) {
            $user->status = $user->status == 1 ? 0 : 1; // Toggle status
            $user->save();
            return response()->json(['message' => 'Status updated successfully']);
        }

        return response()->json(['error' => 'User not found'], 404);
    }

    // Delete the user
    public function delete(Request $request)
    {
        $id = $request->id;

        $user = User::find($id);
        if ($user) {
            // Update the user to mark as "soft deleted"
            $user->update([
                'status' => 0,       // Inactive
                'is_deleted' => 1    // Mark as deleted
            ]);

            return response()->json(['message' => 'User soft deleted successfully']);
        }

        return response()->json(['error' => 'User not found'], 404);
    }


}
