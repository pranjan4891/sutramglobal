<?php
namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        // Fetch categories with their subcategories
        $categories = Category::with('subcategories')->get();

        // Pass data to the view
        return view('frontend.navbar', compact('categories'));
    }
}
