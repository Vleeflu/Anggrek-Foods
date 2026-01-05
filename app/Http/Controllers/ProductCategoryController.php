<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::orderBy('name')->paginate(15);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        ProductCategory::create($validated);
        return redirect()->route('home')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(ProductCategory $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, ProductCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $category->update($validated);
        return redirect()->route('home')->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy(ProductCategory $category)
    {
        $category->delete();
        return redirect()->route('home')->with('success', 'Kategori berhasil dihapus.');
    }
}
