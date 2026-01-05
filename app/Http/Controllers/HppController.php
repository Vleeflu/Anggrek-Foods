<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\HppCalculation;
use App\Models\HppIngredient;
use App\Models\HppPackagingCost;
use App\Exports\HppExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HppController extends Controller
{
    public function index(Request $request)
    {
        $query = HppCalculation::with(['product.category', 'user']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('product.category', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        // Sort filter
        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'hpp_high') {
            $query->orderBy('hpp_per_portion', 'desc');
        } elseif ($sort === 'hpp_low') {
            $query->orderBy('hpp_per_portion', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $calculations = $query->paginate(20);

        return view('hpp.index', compact('calculations'));
    }

    public function export(Request $request)
    {
        $query = HppCalculation::with(['product.category', 'user']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('product.category', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'hpp_high') {
            $query->orderBy('hpp_per_portion', 'desc');
        } elseif ($sort === 'hpp_low') {
            $query->orderBy('hpp_per_portion', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $export = new HppExport($query);
        return $export->download('riwayat-hpp-' . date('Y-m-d') . '.xlsx');
    }

    public function form(string $categorySlug, string $productSlug)
    {
        $category = ProductCategory::where('slug', $categorySlug)->firstOrFail();
        $product = Product::where('slug', $productSlug)
            ->where('product_category_id', $category->id)
            ->firstOrFail();

        return view('hpp.form', [
            'category' => $category,
            'product' => $product,
        ]);
    }

    public function store(Request $request, string $categorySlug, string $productSlug)
    {
        $category = ProductCategory::where('slug', $categorySlug)->firstOrFail();
        $product = Product::where('slug', $productSlug)
            ->where('product_category_id', $category->id)
            ->firstOrFail();

        $validated = $request->validate([
            // ...existing code...
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => 'required|string',
            'ingredients.*.quantity' => 'required|numeric|min:0',
            'ingredients.*.unit' => 'required|string',
            'ingredients.*.price_per_unit' => 'required|numeric|min:0',
            'packaging' => 'nullable|array',
            'packaging.*.description' => 'required|string',
            'packaging.*.price' => 'required|numeric|min:0',
            'labor_cost' => 'required|numeric|min:0',
            'portions' => 'required|integer|min:1',
            'profit_margin_percent' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Calculate totals
            $totalIngredientsCost = 0;
            foreach ($validated['ingredients'] as $ingredient) {
                $totalIngredientsCost += $ingredient['quantity'] * $ingredient['price_per_unit'];
            }

            $totalPackagingCost = 0;
            if (!empty($validated['packaging'])) {
                foreach ($validated['packaging'] as $packaging) {
                    // Packaging price is per porsi; multiply by jumlah porsi untuk total batch
                    $totalPackagingCost += $packaging['price'] * $validated['portions'];
                }
            }

            $totalCost = $totalIngredientsCost + $totalPackagingCost + $validated['labor_cost'];
            $hppPerPortion = $totalCost / $validated['portions'];
            $profitAmount = $hppPerPortion * ($validated['profit_margin_percent'] / 100);
            $sellingPrice = $hppPerPortion + $profitAmount;

            // Create calculation
            $calculation = HppCalculation::create([
                'user_id' => Auth::id(),
                // ...existing code...
                'product_id' => $product->id,
                'total_ingredients_cost' => $totalIngredientsCost,
                'total_packaging_cost' => $totalPackagingCost,
                'labor_cost' => $validated['labor_cost'],
                'portions' => $validated['portions'],
                'total_cost' => $totalCost,
                'hpp_per_portion' => $hppPerPortion,
                'profit_margin_percent' => $validated['profit_margin_percent'],
                'profit_amount' => $profitAmount,
                'selling_price' => $sellingPrice,
                'notes' => $validated['notes'],
            ]);

            // Save ingredients
            foreach ($validated['ingredients'] as $ingredient) {
                HppIngredient::create([
                    'hpp_calculation_id' => $calculation->id,
                    'name' => $ingredient['name'],
                    'quantity' => $ingredient['quantity'],
                    'unit' => $ingredient['unit'],
                    'price_per_unit' => $ingredient['price_per_unit'],
                    'total_price' => $ingredient['quantity'] * $ingredient['price_per_unit'],
                ]);
            }

            // Save packaging costs
            if (!empty($validated['packaging'])) {
                foreach ($validated['packaging'] as $packaging) {
                    HppPackagingCost::create([
                        'hpp_calculation_id' => $calculation->id,
                        'description' => $packaging['description'],
                        'price' => $packaging['price'],
                    ]);
                }
            }

            DB::commit();

            // Cek aksi penyimpanan
            $saveAction = $request->input('save_action', 'hpp');

            if ($saveAction === 'sales') {
                // Redirect ke form penjualan dengan calculation ID
                return redirect()->route('sales.create', $calculation->id)->with('success', 'Perhitungan HPP berhasil disimpan. Silakan lengkapi data penjualan.');
            }

            // Default: redirect ke halaman index HPP
            return redirect()->route('hpp.index')->with('success', 'Perhitungan HPP berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan perhitungan: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $calculation = HppCalculation::with(['product.category', 'user', 'ingredients', 'packagingCosts'])->find($id);
        if (!$calculation) {
            return redirect()->route('hpp.index')->with('error', 'Data HPP tidak ditemukan.');
        }
        return view('hpp.show', compact('calculation'));
    }

    public function edit($id)
    {
        $calculation = HppCalculation::with(['product.category', 'ingredients', 'packagingCosts'])->find($id);
        if (!$calculation) {
            return redirect()->route('hpp.index')->with('error', 'Data HPP tidak ditemukan.');
        }
        return view('hpp.edit', compact('calculation'));
    }

    public function update(Request $request, HppCalculation $calculation)
    {
        $validated = $request->validate([
            // ...existing code...
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => 'required|string',
            'ingredients.*.quantity' => 'required|numeric|min:0',
            'ingredients.*.unit' => 'required|string',
            'ingredients.*.price_per_unit' => 'required|numeric|min:0',
            'packaging' => 'nullable|array',
            'packaging.*.description' => 'required|string',
            'packaging.*.price' => 'required|numeric|min:0',
            'labor_cost' => 'required|numeric|min:0',
            'portions' => 'required|integer|min:1',
            'profit_margin_percent' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Calculate totals
            $totalIngredientsCost = 0;
            foreach ($validated['ingredients'] as $ingredient) {
                $totalIngredientsCost += $ingredient['quantity'] * $ingredient['price_per_unit'];
            }

            $totalPackagingCost = 0;
            if (!empty($validated['packaging'])) {
                foreach ($validated['packaging'] as $packaging) {
                    // Packaging price per porsi; kalikan dengan jumlah porsi
                    $totalPackagingCost += $packaging['price'] * $validated['portions'];
                }
            }

            $totalCost = $totalIngredientsCost + $totalPackagingCost + $validated['labor_cost'];
            $hppPerPortion = $totalCost / $validated['portions'];
            $profitAmount = $hppPerPortion * ($validated['profit_margin_percent'] / 100);
            $sellingPrice = $hppPerPortion + $profitAmount;

            // Update calculation
            $calculation->update([
                // ...existing code...
                'total_ingredients_cost' => $totalIngredientsCost,
                'total_packaging_cost' => $totalPackagingCost,
                'labor_cost' => $validated['labor_cost'],
                'portions' => $validated['portions'],
                'total_cost' => $totalCost,
                'hpp_per_portion' => $hppPerPortion,
                'profit_margin_percent' => $validated['profit_margin_percent'],
                'profit_amount' => $profitAmount,
                'selling_price' => $sellingPrice,
                'notes' => $validated['notes'],
            ]);

            // Delete old ingredients and packaging
            $calculation->ingredients()->delete();
            $calculation->packagingCosts()->delete();

            // Save new ingredients
            foreach ($validated['ingredients'] as $ingredient) {
                HppIngredient::create([
                    'hpp_calculation_id' => $calculation->id,
                    'name' => $ingredient['name'],
                    'quantity' => $ingredient['quantity'],
                    'unit' => $ingredient['unit'],
                    'price_per_unit' => $ingredient['price_per_unit'],
                    'total_price' => $ingredient['quantity'] * $ingredient['price_per_unit'],
                ]);
            }

            // Save new packaging costs
            if (!empty($validated['packaging'])) {
                foreach ($validated['packaging'] as $packaging) {
                    HppPackagingCost::create([
                        'hpp_calculation_id' => $calculation->id,
                        'description' => $packaging['description'],
                        'price' => $packaging['price'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('hpp.show', $calculation->id)->with('success', 'Perhitungan HPP berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal update perhitungan: ' . $e->getMessage()]);
        }
    }

    public function destroy(HppCalculation $calculation)
    {
        $calculation->delete();
        return redirect()->route('hpp.index')->with('success', 'Perhitungan HPP berhasil dihapus.');
    }
}
