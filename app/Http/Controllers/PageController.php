<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Http\Requests;


class PageController extends Controller
{
    public function show($slug)
    {
        // Find the page by its slug
        $page = Page::where('slug', $slug)->where('status', 1)->first();
        $title = $page->title;
        $categories = Category::with(['subcategories' => function ($query) {
            $query->where('status', 1)->orderBy('position', 'asc'); // Only active subcategories
        }])
        ->where('status', 1)->orderBy('order_by', 'asc') // Only active categories
        ->get();

        // Pass the page data to the view
        return view('web.pages.page', compact('page', 'categories', 'title'));
    }
}

