<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;

class SubcategoryController extends Controller
{
    public function show($slug)
    {
        // Find subcategory by slug
        $subcategory = SubCategory::where('slug', $slug)->firstOrFail();

        // Return a view for the subcategory page (e.g., products listing page)
        return view('subcategory.show', compact('subcategory'));
    }
}

