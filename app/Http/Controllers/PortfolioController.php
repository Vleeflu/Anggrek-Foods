<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\HppCalculation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortfolioController extends Controller
{
    public function index()
    {
        // Today's stats
        $today = now()->format('Y-m-d');
        $todaySales = Sale::whereDate('sale_date', $today)
            ->selectRaw('SUM(quantity_sold) as total_qty, SUM(total_revenue) as revenue, SUM(profit) as profit')
            ->first();

        // This month's stats
        $thisMonth = Sale::whereYear('sale_date', now()->year)
            ->whereMonth('sale_date', now()->month)
            ->selectRaw('SUM(quantity_sold) as total_qty, SUM(total_revenue) as revenue, SUM(profit) as profit')
            ->first();

        // Total all time
        $allTime = Sale::selectRaw('SUM(quantity_sold) as total_qty, SUM(total_revenue) as revenue, SUM(profit) as profit')
            ->first();

        // Recent sales (last 7 days)
        $recentSales = Sale::with(['hppCalculation.product.category'])
            ->whereBetween('sale_date', [now()->subDays(6), now()])
            ->orderBy('sale_date', 'desc')
            ->get();

        // Sales by date for chart (last 30 days)
        $chartData = Sale::whereBetween('sale_date', [now()->subDays(29), now()])
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->selectRaw('sale_date, SUM(profit) as daily_profit, SUM(total_revenue) as daily_revenue')
            ->get();

        // Top products
        $topProducts = Sale::with('hppCalculation.product')
            ->select('hpp_calculation_id',
                DB::raw('SUM(quantity_sold) as total_sold'),
                DB::raw('SUM(profit) as total_profit'))
            ->groupBy('hpp_calculation_id')
            ->orderByDesc('total_profit')
            ->limit(5)
            ->get();

        return view('portfolio.index', compact(
            'todaySales',
            'thisMonth',
            'allTime',
            'recentSales',
            'chartData',
            'topProducts'
        ));
    }
}
