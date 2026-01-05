@extends('layouts.app')

@section('content')
<div class="mb-8 rounded-2xl p-6 shadow-lg text-white" style="background: linear-gradient(90deg, #F59E0B 0%, #D97706 100%);">
    <p class="text-sm text-white/80">Dashboard</p>
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-white">Portfolio & Dashboard Penjualan</h1>
            <p class="text-white/80">Monitoring penjualan dan profit Anggrek Foods</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 rounded-md bg-brand-softer p-4 text-sm text-brand-dark">
        {{ session('success') }}
    </div>
@endif

<!-- Stats Cards -->
<div class="grid md:grid-cols-3 gap-6 mb-8">
    <!-- Today -->
    <div class="bg-gradient-to-br from-emerald-500 via-teal-500 to-sky-600 text-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-medium opacity-90">Hari Ini</h3>
            <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="text-3xl font-bold mb-1">Rp{{ number_format($todaySales->profit ?? 0, 0, ',', '.') }}</div>
        <div class="text-sm opacity-90">
            Profit • {{ $todaySales->total_qty ?? 0 }} terjual<br>
            Revenue: Rp{{ number_format($todaySales->revenue ?? 0, 0, ',', '.') }}
        </div>
    </div>

    <!-- This Month -->
    <div class="bg-gradient-to-br from-sky-500 via-indigo-500 to-purple-600 text-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-medium opacity-90">Bulan Ini</h3>
            <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
        </div>
        <div class="text-3xl font-bold mb-1">Rp{{ number_format($thisMonth->profit ?? 0, 0, ',', '.') }}</div>
        <div class="text-sm opacity-90">
            Profit • {{ $thisMonth->total_qty ?? 0 }} terjual<br>
            Revenue: Rp{{ number_format($thisMonth->revenue ?? 0, 0, ',', '.') }}
        </div>
    </div>

    <!-- All Time -->
    <div class="bg-gradient-to-br from-purple-600 via-fuchsia-600 to-pink-500 text-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-medium opacity-90">Total Keseluruhan</h3>
            <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
        </div>
        <div class="text-3xl font-bold mb-1">Rp{{ number_format($allTime->profit ?? 0, 0, ',', '.') }}</div>
        <div class="text-sm opacity-90">
            Profit • {{ $allTime->total_qty ?? 0 }} terjual<br>
            Revenue: Rp{{ number_format($allTime->revenue ?? 0, 0, ',', '.') }}
        </div>
    </div>
</div>

<!-- Chart & Top Products -->
<div class="grid md:grid-cols-3 gap-6 mb-8">
    <!-- Profit Chart (30 days) -->
    <div class="md:col-span-2 bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-brand-dark mb-4">Profit Harian (30 Hari Terakhir)</h3>
        <canvas id="profitChart" height="80"></canvas>
    </div>

    <!-- Top Products -->
    <div class="bg-white rounded-xl shadow p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-brand-dark">Top 5 Produk</h3>
            <span class="text-xs px-2 py-1 rounded-full bg-brand-softer text-brand-dark">Terlaris</span>
        </div>
        @if($topProducts->count() > 0)
            <div class="space-y-3">
                @foreach($topProducts as $index => $sale)
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-brand-dark text-white flex items-center justify-center font-bold text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium truncate">{{ $sale->hppCalculation->product->name }}</div>
                            <div class="text-xs text-gray-500">{{ $sale->total_sold }} terjual</div>
                        </div>
                        <div class="text-sm font-bold text-brand-dark">
                            Rp{{ number_format($sale->total_profit, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 text-center py-8">Belum ada data penjualan</p>
        @endif
    </div>
</div>

<!-- Recent Sales -->
<div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h3 class="text-lg font-semibold text-brand-dark">Penjualan Terbaru (7 Hari)</h3>
        <a href="{{ route('sales.index') }}" class="text-sm text-brand-dark hover:text-brand font-semibold">Lihat Semua →</a>
    </div>

    @if($recentSales->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-left">Produk</th>
                        <th class="px-6 py-3 text-right">Qty</th>
                        <th class="px-6 py-3 text-right">Revenue</th>
                        <th class="px-6 py-3 text-right">Profit</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-sm">
                    @foreach($recentSales as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $sale->sale_date->format('d M Y') }}</td>
                            <td class="px-6 py-3">
                                <div class="font-medium">{{ $sale->hppCalculation->product->name }}</div>
                                <div class="text-xs text-gray-500">{{ $sale->hppCalculation->product->category->name }}</div>
                            </td>
                            <td class="px-6 py-3 text-right">{{ $sale->quantity_sold }}</td>
                            <td class="px-6 py-3 text-right font-medium">Rp{{ number_format($sale->total_revenue, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-semibold text-brand-dark">Rp{{ number_format($sale->profit, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="px-6 py-12 text-center text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-sm">Belum ada data penjualan.</p>
            <p class="text-xs mt-2">Mulai dengan menghitung HPP produk, lalu tambahkan ke portfolio.</p>
        </div>
    @endif
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const chartData = @json($chartData);

// Prepare data for chart
const labels = chartData.map(item => {
    const date = new Date(item.sale_date);
    return date.toLocaleDateString('id-ID', { month: 'short', day: 'numeric' });
});
const profits = chartData.map(item => item.daily_profit);
const revenues = chartData.map(item => item.daily_revenue);

// Create chart
const ctx = document.getElementById('profitChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Profit',
            data: profits,
            borderColor: '#f59e0b',
            backgroundColor: 'rgba(245, 158, 11, 0.15)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Profit: Rp' + context.parsed.y.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp' + Math.round(value).toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
@endsection
