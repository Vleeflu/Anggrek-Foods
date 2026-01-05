<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::with('products')
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return view('home', [
            'categories' => $categories,
        ]);
    }

    public function show(ProductCategory $category)
    {
        $products = Product::where('product_category_id', $category->id)
            ->where('is_active', true)
            ->get();

        return view('menu.submenu', [
            'category' => $category,
            'products' => $products,
        ]);
    }
}
