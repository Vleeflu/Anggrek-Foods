<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\HppCalculation;
use App\Exports\SaleExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function create(HppCalculation $calculation)
    {
        return view('sales.create', compact('calculation'));
    }

    public function store(Request $request, HppCalculation $calculation)
    {
        $validated = $request->validate([
            'sale_date' => 'required|date',
            'quantity_sold' => 'required|integer|min:1',
            'selling_price_used' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $costPerUnit = $calculation->hpp_per_portion;
        $totalRevenue = $validated['quantity_sold'] * $validated['selling_price_used'];
        $totalCost = $validated['quantity_sold'] * $costPerUnit;
        $profit = $totalRevenue - $totalCost;

        Sale::create([
            'hpp_calculation_id' => $calculation->id,
            'sale_date' => $validated['sale_date'],
            'quantity_sold' => $validated['quantity_sold'],
            'selling_price_used' => $validated['selling_price_used'],
            'cost_per_unit' => $costPerUnit,
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'profit' => $profit,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('portfolio')->with('success', 'Data penjualan berhasil ditambahkan ke portfolio.');
    }

    public function index(Request $request)
    {
        $query = Sale::with(['hppCalculation.product.category']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('hppCalculation.product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('hppCalculation.product.category', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('sale_date', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('sale_date', '<=', $request->input('to_date'));
        }

        // Sort filter
        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('sale_date', 'asc');
        } elseif ($sort === 'profit_high') {
            $query->orderBy('profit', 'desc');
        } elseif ($sort === 'profit_low') {
            $query->orderBy('profit', 'asc');
        } elseif ($sort === 'revenue_high') {
            $query->orderBy('total_revenue', 'desc');
        } else {
            $query->orderBy('sale_date', 'desc');
        }

        // Get stats before pagination
        $statsQuery = Sale::with(['hppCalculation.product.category']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $statsQuery->whereHas('hppCalculation.product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('hppCalculation.product.category', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        if ($request->filled('from_date')) {
            $statsQuery->whereDate('sale_date', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $statsQuery->whereDate('sale_date', '<=', $request->input('to_date'));
        }

        $totalProfit = $statsQuery->sum('profit');
        $totalRevenue = $statsQuery->sum('total_revenue');
        $avgProfit = $statsQuery->count() > 0 ? $statsQuery->avg('profit') : 0;

        $sales = $query->paginate(20);

        return view('sales.index', compact('sales', 'totalProfit', 'totalRevenue', 'avgProfit'));
    }

    public function export(Request $request)
    {
        $query = Sale::with(['hppCalculation.product.category']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('hppCalculation.product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('hppCalculation.product.category', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('sale_date', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('sale_date', '<=', $request->input('to_date'));
        }

        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('sale_date', 'asc');
        } elseif ($sort === 'profit_high') {
            $query->orderBy('profit', 'desc');
        } elseif ($sort === 'profit_low') {
            $query->orderBy('profit', 'asc');
        } elseif ($sort === 'revenue_high') {
            $query->orderBy('total_revenue', 'desc');
        } else {
            $query->orderBy('sale_date', 'desc');
        }

        $export = new SaleExport($query);
        return $export->download('riwayat-penjualan-' . date('Y-m-d') . '.xlsx');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return back()->with('success', 'Data penjualan berhasil dihapus.');
    }
}
