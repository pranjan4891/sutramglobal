<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function get_state(Request $request)
    {
        $country_id = $request->country_id;
        $row = State::where('country_id',$country_id)->get();
        $html= '';
        if(!empty($row))
        {
            foreach ($row as $value) {
                $html.= '<option value="'.$value->id.'">'.$value->name.'</option>';
            }
        }
        echo json_encode($html);
    }

    public function get_city(Request $request)
    {
        $state_id = $request->state_id;
        $row = City::where('state_id',$state_id)->get();
        $html= '';
        if(!empty($row))
        {
            foreach ($row as $value) {
                $html.= '<option value="'.$value->id.'">'.$value->name.'</option>';
            }
        }
        echo json_encode($html);
    }
    public function get_subcategory(Request $request)
    {
		$category_id = $request->category_id;
		$row = SubCategory::where('status', 1)->where('category_id', $category_id)->get();
        $html= '';
        if(!empty($row))
        {
            foreach ($row as $value) {
                $html.= '<option value="'.$value->id.'">'.$value->name.'</option>';
            }
        }
        echo json_encode($html);
    }
}
