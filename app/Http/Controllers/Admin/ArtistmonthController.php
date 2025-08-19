<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;

use App\Models\ExploreArtist;
use App\Http\Controllers\Controller;

class ArtistController extends Controller
{

    public function exploreArtistList(Request $request)
    {
        $data['title'] = 'Explore Artist';
        $data['action'] = 'List';
        $data['data'] = ExploreArtist::get();

        return view('admin.artist.explore-list', $data);
    }
    public function getexploreList()
    {
        $result = ExploreArtist::selectRaw('id, artist_name, product_id, product_name')->get();

        $data = array();
        $artists = array(); // Track artists already added to the data array
        $sr = 1;
        foreach ($result as $key => $value) {
            if (!in_array($value->artist_name, $artists)) {
                $artists[] = $value->artist_name; // Add artist to the list
                $sub_array = array();
                $sub_array[] = $sr++;
                $sub_array[] = $value->artist_name; // Artist name
                $products = array();
                $action_array = array(); // Initialize action array for each artist
                foreach ($result as $inner_value) {
                    if ($inner_value->artist_name === $value->artist_name) {
                        $productImage = getProductImage($inner_value->product_id);
                        $image = '<img style="height:100px;width:200px;" src="' . isImage('Main' , $productImage) . '" class="img-fluid rounded-start" alt="' . $inner_value->product_name . '">';
                        $productName = '<h5 class="card-title">' . $inner_value->product_name . '</h5>';
                        $action1 = '<a class="btn btn-primary btn-xs" href="#"><i class="fa fa-pencil"></i> Edit</a>';
                        $action2 = '<a href="javascript:void(0)" data-id="' . $inner_value->product_id . '" class="btn btn-danger btn-xs explore_delete"><i class="fa fa-trash-o"></i> Remove</a>';

                        $product_card = '<div class="card card-primary card-outline">
                                    <div class="row g-0">
                                        <div class="col-md-6">' . $image . '</div>
                                        <div class="col-md-6">
                                            <div class="card-body">' . $productName . ' <br>' . $action2 . '</div>
                                        </div>
                                    </div>
                                </div>';

                        $products[] = $product_card;
                    }
                }

                // Limit the products array to 3 elements
                $products = array_slice($products, 0, 3);

                // Add product columns to the sub_array
                foreach ($products as $product) {
                    $sub_array[] = $product;
                }

                // If fewer than 3 products, add a button to add more
                // if (count($products) < 3) {
                //     $addButton = '<a href="'. route('admin.artist.exploreArtistManage') .'" class="btn btn-primary"><i class="fa fa-plus"></i></a>';
                //     $hdjsdh = '<div class="card text-center">
                //     <div class="row g-0">
                //         <div class="col-md-12">
                //             <div class="card-body" style="height:100px;">' . $addButton . '</div>
                //         </div>
                //     </div>
                // </div>';
                //     $sub_array[] = $hdjsdh;
                // }

                // Fill in any missing product slots
                $missing_products_count = 3 - count($products);
                for ($i = 0; $i < $missing_products_count; $i++) {
                    $sub_array[] = ''; // Add empty string to represent missing products
                }

                $data[] = $sub_array;
            }
        }

        $output = array(
            "draw"       =>  intval($_POST["draw"]),
            "recordsTotal"   =>  $this->count_all_list(),
            "recordsFiltered"  =>  count($data),
            "data"       =>  $data,
        );
        echo json_encode($output);
    }

    public function count_all_list()
    {
        // return 100;
        $row = ExploreArtist::selectRaw('id')->where('id', '>', '0')->count();
        return $row;
    }
    public function isActiveStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $model = ExploreArtist::find($id);
        $model->is_active = $status;
        $model->save();
        return response()->json('success');
    }
}
