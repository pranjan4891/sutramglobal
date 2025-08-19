<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index()
    {
        $data['title'] = 'Blog';
        $data['action'] = 'List';
        return view('admin.blog.list', $data);
    }
    public function add()
    {
        $data['title'] = 'Blog';
        $data['action'] = 'Add';
        $data['blogCategory'] = BlogCategory::where('is_active',1)->get();
        return view('admin.blog.manage', $data);
    }
    public function edit($slug)
    {
        $data['title'] = 'Blog';
        $data['action'] = 'Edit';
        $data['edit_data'] = Blog::where('slug', $slug)->first();
        $data['blogCategory'] = BlogCategory::where('is_active',1)->get();
        return view('admin.blog.manage', $data);
    }
    public function Store(Request $request)
    {
        $slug = $request->slug;
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|unique:blogs,title,' . $slug . ',slug',
                'category_id' => 'required',
                'short_description' => 'required',
                'description' => 'required',
                'banner_image' => ($slug ? 'nullable|mimes:jpeg,jpg,png' : 'required|mimes:jpeg,jpg,png'),
            ],
            [
                'category_id.required' => 'Category is required',
                'banner_image.required' => 'Banner image is required',
            ]
        );
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        if (!empty($slug)) {
            $blog = Blog::where('slug', $slug)->first();
            $blog->title = $request->title;
            $blog->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->title)));
            $blog->short_description = $request->short_description;
            $blog->description = $request->description;
            $blog->mete_title = $request->mete_title;
            $blog->mete_keyword = $request->mete_keyword;
            $blog->mete_description = $request->mete_description;
            $blog->created_by = Auth::user()->name;
            $blog->is_active = !empty($request->is_active) ? $request->is_active : 0;
            $blog->is_home = !empty($request->is_home) ? $request->is_home : 0;
            if ($request->hasFile('banner_image')) {
                if ($blog->banner_image) {
                    $oldImagePath = public_path("/coded-blog/{$blog->banner_image}");
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $image = $request->file('banner_image');
                $fileName = date('dmy') . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path("/coded-blog"), $fileName);
                $blog->banner_image = $fileName;
            }
            $blog->save();
            return redirect()->route('admin.blogs')->with('success', 'Success! Blog updated');
        } else {
            $blog = new Blog();
            $blog->title = $request->title;
            $blog->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->title)));
            $blog->short_description = $request->short_description;
            $blog->description = $request->description;
            $blog->mete_title = $request->mete_title;
            $blog->mete_keyword = $request->mete_keyword;
            $blog->mete_description = $request->mete_description;
            $blog->created_by = Auth::user()->name;
            $blog->is_active = !empty($request->is_active) ? $request->is_active : 0;
            $blog->is_home = !empty($request->is_home) ? $request->is_home : 0;
            if ($request->hasFile('banner_image')) {
                $image = $request->file('banner_image');
                $fileName = date('dmy') . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path("/coded-blog"), $fileName);
                $blog->banner_image = $fileName;
            }
            $blog->save();
            return redirect()->route('admin.blogs')->with('success', 'Success! Blog saved');
        }
    }
    public function getBlogList()
    {
        $column = array('id', 'banner_image', 'title', 'slug', 'short_description', 'created_at', 'created_by', 'is_active', 'is_home', 'id');

        $row = Blog::selectRaw('id,banner_image,title,slug,short_description,created_at,created_by,is_active,is_home,id')->where('id', '>', '0');
        if (!empty($_POST['search']['value'])) {
            $row->where('title', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $row->orWhere('created_by', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
            $action1 = '<a class="btn btn-primary btn-xs" href="' . route('admin.blog.edit', $value->slug) . '"><i class="fa fa-pencil"></i> Edit</a>';
            $action2 = '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-xs blog_delete"><i class="fa fa-trash-o"></i> Delete</a>';
            $image = '<img style="height:80px;width:150px;" src="' . isImage('coded-blog' , $value->banner_image) . '">';
            $check = $value->is_active == '1' ? 'checked' : '';
            $listing = '<div class="form-group mt-2 mb-1">
                        <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" onclick="isActive(' . $value->id . ',this)" value="1" id="is_active_' . $value->id . '" name="is_active" ' . $check . '>
                        <label for="is_active_' . $value->id . '" class="custom-control-label">Is Active</label>
                        </div>
                        </div>';
            $check2 = $value->is_home == '1' ? 'checked' : '';
            $listing .= '<div class="form-group mb-1">
                        <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" onclick="isHome(' . $value->id . ',this)" value="1" id="is_home_' . $value->id . '" name="is_home" ' . $check2 . '>
                        <label for="is_home_' . $value->id . '" class="custom-control-label">Is Home</label>
                        </div>
                        </div>';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $image;
            $sub_array[] = $value->title;
            // $sub_array[] = $value->short_description;
            $sub_array[] = date('d-m-Y', strtotime($value->created_at)) . '<br>' . $value->created_by;
            $sub_array[] =  $action1 . ' ' . $action2 . ' ' . $listing;
            $data[] = $sub_array;
        }
        $output = array(
            "draw"       =>  intval($_POST["draw"]),
            "recordsTotal"   =>  $this->count_all_list(),
            "recordsFiltered"  =>  $number_filter_row,
            "data"       =>  $data,
        );
        echo json_encode($output);
    }
    public function count_all_list()
    {
        // return 100;
        $row = Blog::selectRaw('id')->where('id', '>', '0')->count();
        return $row;
    }
    public function isActive(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $model = Blog::find($id);
        $model->is_active = $status;
        if ($status == 0) {
            $model->is_home = 0;
        }
        $model->save();
        return response()->json('success');
    }

    public function isHome(Request $request)
    {
        $id = $request->id;
        $is_home = $request->is_home;

        $blog = Blog::findOrFail($id);

        if ($is_home == 1) {
            if ($blog->is_active == 1) {
                $blogsCount = Blog::where('is_home', 1)->count();

                if ($blogsCount < 3) {
                    $blog->is_home = $is_home;
                    $blog->save();
                    return response()->json(['status' => 'success', 'message' => 'Added to Home Page.']);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Already added 3 blogs to Home Page.']);
                }
            } else {
                return response()->json(['status' => 'error', 'message' => 'Blog post is not active.']);
            }
        } elseif ($is_home == 0) {
            $blog->is_home = $is_home;
            $blog->save();
            return response()->json(['status' => 'success', 'message' => 'Removed from Home Page.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid request.']);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $blog = Blog::find($id);
        if ($blog->banner_image) {
            $oldImagePath = public_path("/coded-blog/{$blog->banner_image}");
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        $blog->delete();
        return response()->json('success');

    }

}
