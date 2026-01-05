<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products'));
    }

    // ✅ CREATE WITH CATEGORY CONTEXT
    public function create(ProductCategory $category)
    {
        $categories = ProductCategory::orderBy('name')->get();

        return view('products.create', [
            'categories' => $categories,
            'currentCategory' => $category, // 👈 context
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');

        $product = Product::create($validated);

        // 🔁 BALIK KE MENU CATEGORY
        return redirect()
            ->route('menu.show', $product->category->slug)
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()
            ->route('menu.show', $product->category->slug)
            ->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy(Product $product)
    {
        $categorySlug = $product->category->slug;

        $product->delete();

        return redirect()
            ->route('menu.show', $categorySlug)
            ->with('success', 'Produk berhasil dihapus.');
    }
}
