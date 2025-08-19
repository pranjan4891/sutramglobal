<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Newslatter;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Mail\WishlistNotification;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['action'] = '';
        $data['paintings'] = Product::where('status', 1)->count();
        $data['contacts'] = Contact::count();
        $data['total_users'] = User::count();
        $data['total_order'] = Order::count();
        return view('admin.dashboard', $data);
    }
    public function cartProducts()
    {
        $data['title'] = 'Cart Products';
        $data['action'] = '';
        return view('admin.cart_products', $data);
    }
    public function getCartProducts()
    {
        $column = array('id', 'user_id', 'product_id', 'price', 'qty', 'created_at', 'id');

        $row = Cart::with(['user', 'product'])
            ->select('id', 'user_id', 'product_id', 'price', 'qty', 'created_at')
            ->where('id', '>', 0);

        $total_row = $row->count();

        if (!empty($_POST['search']['value'])) {
            $searchValue = $_POST['search']['value'];

            $row->where(function ($query) use ($searchValue) {
                $query->where('price', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('qty', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $searchValue . '%')
                    ->orWhereHas('user', function ($query) use ($searchValue) {
                        $query->where('first_name', 'LIKE', '%' . $searchValue . '%')
                       ->Orwhere('last_name', 'LIKE', '%' . $searchValue . '%')
                           ;
                    })
                    ->orWhereHas('product', function ($query) use ($searchValue) {
                        $query->where('title', 'LIKE', '%' . $searchValue . '%');
                    });
            });
        }

        if (!empty($_POST['order'])) {
            $orderColumn = $column[$_POST['order'][0]['column']];
            $orderDirection = $_POST['order'][0]['dir'];
            $row->orderBy($orderColumn, $orderDirection);
        } else {
            $row->orderBy('id', 'desc');
        }

        $number_filter_row = $row->count();

        if (!empty($_POST["length"]) && $_POST["length"] != -1) {
            $row->limit($_POST['length'])->offset($_POST['start']);
        }

        $result = $row->get();

        $data = array();
        foreach ($result as $key => $value) {
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->user->first_name.' '.$value->user->last_name;
            $sub_array[] = $value->product->title;
            $sub_array[] = number_format($value->price, 2, '.', '');
            $sub_array[] = $value->qty;
            $totalPrice = $value->price*$value->qty;
            $sub_array[] = number_format($totalPrice, 2, '.', '');
            $sub_array[] = date('d-m-Y', strtotime($value->created_at));
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $total_row,
            "recordsFiltered" => $number_filter_row,
            "data" => $data,
        );

        echo json_encode($output);

    }
    public function contactUs()
    {
        $data['title'] = 'Contact Us';
        $data['action'] = 'List';
        return view('admin.contact-us', $data);
    }
    public function getcontactUsList()
    {
        $column = array('id', 'name', 'email','order_no', 'message', 'created_at','id');

        $row = Contact::selectRaw('id,name,email,order_no,message,created_at')->where('id', '>', '0');
        $total_row = $row->count();
        if (!empty($_POST['search']['value'])) {
            $row->orWhere('name', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $row->orWhere('email', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $row->orWhere('order_no', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $row->orWhere('message', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
            $action = '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-xs btn_delete"><i class="fa fa-trash-o"></i> Delete</a>';

            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = '<b> Name: </b>' . $value->name . '<br><b>Email: </b>' . $value->email;
            $sub_array[] = $value->order_no;
            $sub_array[] = $value->message;
            $sub_array[] = date('d-m-Y', strtotime($value->created_at)) . '<br>' . $value->created_by;
            $sub_array[] =  $action;
            $data[] = $sub_array;
        }
        $output = array(
            "draw"       =>  intval($_POST["draw"]),
            "recordsTotal"   =>    $total_row,
            "recordsFiltered"  =>  $number_filter_row,
            "data"       =>  $data,
        );
        echo json_encode($output);
    }

    public function deleteContactUs(Request $request)
    {
        $id = $request->id;
        $model = Contact::find($id);
        $model->delete();
        return response()->json('success');
    }


    public function newsLatter()
    {
        $data['title'] = 'News Latter';
        $data['action'] = 'List';
        return view('admin.news-latter', $data);
    }
    public function getNewsLatterList()
    {
        $column = array('id', 'name', 'email','phone', 'message', 'created_at','id');

        $row = Newslatter::selectRaw('id,name,email,phone,message,created_at')->where('id', '>', '0');
        $total_row = $row->count();
        if (!empty($_POST['search']['value'])) {
            $row->orWhere('name', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $row->orWhere('email', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $row->orWhere('phone', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $row->orWhere('message', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
            $action = '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-xs btn_delete"><i class="fa fa-trash-o"></i> Delete</a>';

            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->name;
            $sub_array[] = $value->phone;
            $sub_array[] = $value->email;
            $sub_array[] = $value->message;
            $sub_array[] = date('d-m-Y', strtotime($value->created_at)) . '<br>' . $value->created_by;
            $sub_array[] =  $action;
            $data[] = $sub_array;
        }
        $output = array(
            "draw"       =>  intval($_POST["draw"]),
            "recordsTotal"   =>    $total_row,
            "recordsFiltered"  =>  $number_filter_row,
            "data"       =>  $data,
        );
        echo json_encode($output);
    }

    public function deleteNewsLatter(Request $request)
    {
        $id = $request->id;
        $model = Newslatter::find($id);
        $model->delete();
        return response()->json('success');
    }

    public function wishlist()
    {
        $data['title'] = 'Wishlists';
        $data['action'] = 'List';
        return view('admin.wishlist', $data);
    }

    public function getWishlist()
    {
        $column = array('id', 'user_id', 'product_id', 'size_id', 'color_id', 'status', 'created_at', 'id');

        $row = Wishlist::with(['user', 'product', 'size', 'color'])
            ->select('id', 'user_id', 'product_id', 'size_id', 'color_id', 'status', 'created_at')
            ->where('id', '>', 0);

        $total_row = $row->count();

        if (!empty($_POST['search']['value'])) {
            $searchValue = $_POST['search']['value'];

            $row->where(function ($query) use ($searchValue) {
                $query->where('status', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $searchValue . '%')
                    ->orWhereHas('user', function ($query) use ($searchValue) {
                        $query->where('first_name', 'LIKE', '%' . $searchValue . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $searchValue . '%');
                    })
                    ->orWhereHas('product', function ($query) use ($searchValue) {
                        $query->where('title', 'LIKE', '%' . $searchValue . '%');
                    })
                    ->orWhereHas('size', function ($query) use ($searchValue) {
                        $query->where('name', 'LIKE', '%' . $searchValue . '%');
                    })
                    ->orWhereHas('color', function ($query) use ($searchValue) {
                        $query->where('name', 'LIKE', '%' . $searchValue . '%');
                    });
            });
        }

        if (!empty($_POST['order'])) {
            $orderColumn = $column[$_POST['order'][0]['column']];
            $orderDirection = $_POST['order'][0]['dir'];
            $row->orderBy($orderColumn, $orderDirection);
        } else {
            $row->orderBy('id', 'desc');
        }

        $number_filter_row = $row->count();

        if (!empty($_POST["length"]) && $_POST["length"] != -1) {
            $row->limit($_POST['length'])->offset($_POST['start']);
        }

        $result = $row->get();

        $data = array();
        foreach ($result as $key => $value) {
            $action = '<a class="btn btn-primary btn-sm ml-1" href="' . route('admin.wishlists.sendMail', ['id' => $value->id]) . '"><i class="fa fa-paper-plane"></i> Notify</a>';

            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = '<div class="text-center"><img style="height:100px;width:100px;" src="' . isImage('products', $value->product->image_1) . '"></div>';
            $sub_array[] = $value->user->first_name . ' ' . $value->user->last_name . '<br><b>Email: </b>' . $value->user->email;
            $sub_array[] = '<b>Title: </b>' . $value->product->title . '<br><b>Color: </b>' . ($value->color ? $value->color->name : 'N/A') . '<br><b>Size: </b>' . ($value->size ? $value->size->code : 'N/A');
            $sub_array[] = date('d-m-Y', strtotime($value->created_at));
            $sub_array[] = $action;
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $total_row,
            "recordsFiltered" => $number_filter_row,
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function sendMail($id)
    {
        $wishlist = Wishlist::with(['user', 'product', 'size', 'color'])->find($id);

        if (!$wishlist) {
            return redirect()->route('admin.wishlist')->with('error', 'Wishlist item not found.');
        }

        $emailData = [
            'userName' => $wishlist->user->first_name . ' ' . $wishlist->user->last_name,
            'userEmail' => $wishlist->user->email,
            'productTitle' => $wishlist->product->title,
            'productSlug' => $wishlist->product->slug,
            'productSize' => $wishlist->size ? $wishlist->size->code : 'N/A',
            'productColor' => $wishlist->color ? $wishlist->color->name : 'N/A',
            'createdAt' => $wishlist->created_at->format('d-m-Y'),
        ];

        try {
            Mail::to($wishlist->user->email)->send(new WishlistNotification($emailData));

            // Add success message
            return redirect()->route('admin.wishlist')->with('success', 'Email sent successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.wishlist')->with('error', 'Failed to send email. ' . $e->getMessage());
        }
    }


}
