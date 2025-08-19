<?php

namespace App\Http\Controllers\Admin;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
class PageController extends Controller
{
    public function index()
    {
        $data['title'] = 'Page';
        $data['action'] = 'List';
        return view('admin.pages.index',$data);
    }
    public function getPageList ()
    {
        $column = array('id','title','updated_at','status','id');

        $row = Page::selectRaw('id,title,updated_at,status,id')->where('id','>','0');
        $total_row = $row->count();
        if(!empty($_POST['search']['value']))
        {
            $row->where('title','LIKE','%'.$_POST['search']['value'].'%');
        }
        if(!empty($_POST['order']))
        {
            $row->orderBy($column[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
        }
        else
        {
            $row->orderBy('id','desc');
        }

        $number_filter_row=$row->count();
        if(!empty($_POST["length"])&&$_POST["length"] != -1)
        {
            $row->limit($_POST['length'])->offset($_POST['start']);
        }

        $result =$row->get();
        $data = array();
        foreach($result as $key=> $value)
        {
            $action = '<a class="btn btn-primary btn-sm" href="'.route('admin.page.manage',$value->id).'"><i class="fa fa-edit"></i> Edit</a>';
            // $action .= '<a href="javascript:void(0)" data-id="' . $value->id . '" class="btn btn-danger btn-sm ml-1 btn_delete"><i class="fa fa-trash"></i> Delete</a>';
            $status='<select style="width: 100%;" class="form-control" onchange="updateStatus(`'.$value->id.'`,this.value);">';
            $status .= '<option '.($value->status == '1' ? 'selected':'').' value="1">Active</option>';
            $status .= '<option '.($value->status == '2' ? 'selected':'').' value="2">Inactive</option>';
            $status .='</select>';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->title;
            $sub_array[] = date('d-m-Y',strtotime($value->updated_at));
            $sub_array[] = $status;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        $output = array(
           "draw"       =>  intval($_POST["draw"]),
           "recordsTotal"   =>  $total_row,
           "recordsFiltered"  =>  $number_filter_row,
           "data"       =>  $data,
        );
        echo json_encode($output);
    }

    public function manage($id = '')
    {
        $data['title'] = 'Page';
        $data['action'] = !empty($id) ? 'Edit' : 'Add';
        $data['edit_data'] = !empty($id) ? Page::findOrFail($id) : '';
        return view('admin.pages.manage_page',$data);
    }
    public function update(Request $request)
    {
        $edit_id = $request->edit_id;
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:pages,title,'.$request->edit_id,
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        if (!empty($edit_id)) {
            $page = Page::findOrFail($edit_id);
        } else {
            $page = new Page();
        }
        $page->title = $request->title;
        $page->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', str_replace('&', 'and', $request->title))));
        $page->description = $request->description;
        $page->status = $request->status;
        $page->save();
        $message = (!empty($edit_id)) ? 'Page updated successfully' : 'Page added successfully';
        return redirect()->route('admin.pages')->with('success', $message);
    }

    public function status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $page = Page::find($id);
        $page->status = $status;
        $page->save();
        return response()->json('success');
    }
    // public function delete(Request $request)
    // {
    //     $id = $request->id;
    //     $page = Page::find($id);
    //     $page->delete();
    //     return response()->json('success');
    // }
}
