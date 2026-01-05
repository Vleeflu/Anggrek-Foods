@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('hpp.index') }}" class="text-sm text-gray-600 hover:text-gray-800">← Kembali ke Riwayat</a>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <div class="px-4 py-3 -mx-6 -mt-6 mb-6 rounded-t-xl text-white" style="background: linear-gradient(90deg, #F59E0B 0%, #D97706 100%);">
        <h2 class="text-2xl font-semibold">DETAIL PERHITUNGAN HPP</h2>
        <p class="text-sm opacity-90">{{ $calculation->created_at->format('d F Y, H:i') }} WIB</p>
    </div>

    <!-- Product Info -->
    <div class="mb-6 grid md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
        <div>
            <div class="text-sm text-gray-600">Produk</div>
            <div class="font-semibold">{{ $calculation->product->name }}</div>
            <div class="text-xs text-gray-500">{{ $calculation->product->category->name }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-600">Dibuat oleh</div>
            <div class="font-semibold">{{ $calculation->user->name }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-600">Jumlah Porsi</div>
            <div class="font-semibold">{{ $calculation->portions }} porsi</div>
        </div>
    </div>

    <!-- Ingredients Table -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-brand-dark mb-3">Bahan-Bahan Produk</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left">No</th>
                        <th class="px-3 py-2 text-left">Nama Bahan</th>
                        <th class="px-3 py-2 text-right">Jumlah</th>
                        <th class="px-3 py-2 text-left">Satuan</th>
                        <th class="px-3 py-2 text-right">Harga/Satuan</th>
                        <th class="px-3 py-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($calculation->ingredients as $index => $ingredient)
                        <tr>
                            <td class="px-3 py-2">{{ $index + 1 }}</td>
                            <td class="px-3 py-2">{{ $ingredient->name }}</td>
                            <td class="px-3 py-2 text-right">{{ $ingredient->quantity }}</td>
                            <td class="px-3 py-2">{{ $ingredient->unit }}</td>
                            <td class="px-3 py-2 text-right">Rp{{ number_format($ingredient->price_per_unit, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right font-semibold">Rp{{ number_format($ingredient->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100 font-semibold">
                    <tr>
                        <td colspan="5" class="px-3 py-2 text-right">Total Bahan Produk:</td>
                        <td class="px-3 py-2 text-right text-brand-dark">Rp{{ number_format($calculation->total_ingredients_cost, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Packaging Costs -->
    @if($calculation->packagingCosts->count() > 0)
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-brand-dark mb-3">Biaya Utilitas dan Kemasan</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left">No</th>
                        <th class="px-3 py-2 text-left">Keterangan</th>
                        <th class="px-3 py-2 text-right">Harga</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($calculation->packagingCosts as $index => $packaging)
                        <tr>
                            <td class="px-3 py-2">{{ $index + 1 }}</td>
                            <td class="px-3 py-2">{{ $packaging->description }}</td>
                            <td class="px-3 py-2 text-right font-semibold">Rp{{ number_format($packaging->price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100 font-semibold">
                    <tr>
                        <td colspan="2" class="px-3 py-2 text-right">Total Biaya Kemasan:</td>
                        <td class="px-3 py-2 text-right text-brand-dark">Rp{{ number_format($calculation->total_packaging_cost, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    <!-- Results -->
    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-gray-50 rounded-lg p-6">
            <h4 class="font-semibold mb-4 text-brand-dark">Rincian Biaya</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span>Total Bahan Produk:</span><span class="font-semibold">Rp{{ number_format($calculation->total_ingredients_cost, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span>Total Biaya Kemasan:</span><span class="font-semibold">Rp{{ number_format($calculation->total_packaging_cost, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span>Biaya Tenaga Kerja:</span><span class="font-semibold">Rp{{ number_format($calculation->labor_cost, 0, ',', '.') }}</span></div>
                <div class="flex justify-between border-t pt-2"><span class="font-bold">Total Semua Biaya:</span><span class="font-bold text-brand-dark">Rp{{ number_format($calculation->total_cost, 0, ',', '.') }}</span></div>
            </div>
        </div>

        <div class="rounded-lg p-6 text-white" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);">
            <h4 class="font-semibold mb-4">Hasil Akhir</h4>
            <div class="space-y-3">
                <div>
                    <div class="text-sm opacity-90">HPP per Porsi</div>
                    <div class="text-3xl font-bold">Rp{{ number_format($calculation->hpp_per_portion, 0, ',', '.') }}</div>
                </div>
                <div class="border-t border-white/20 pt-3">
                    <div class="text-sm opacity-90">Margin Keuntungan: {{ $calculation->profit_margin_percent }}%</div>
                    <div class="text-sm">Profit: Rp{{ number_format($calculation->profit_amount, 0, ',', '.') }}</div>
                </div>
                <div class="border-t border-white/20 pt-3">
                    <div class="text-sm opacity-90">Harga Jual yang Disarankan</div>
                    <div class="text-3xl font-bold">Rp{{ number_format($calculation->selling_price, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    @if($calculation->notes)
    <div class="mt-6 bg-blue-50 p-4 rounded-lg">
        <h4 class="font-semibold text-sm text-blue-900 mb-2">Catatan:</h4>
        <p class="text-sm text-blue-800">{{ $calculation->notes }}</p>
    </div>
    @endif

    <div class="mt-6 flex gap-4">
        <a href="{{ route('sales.create', $calculation) }}" class="px-6 py-2 bg-brand text-white rounded-lg hover:bg-brand-dark font-semibold">+ Tambah ke Portfolio (Catat Penjualan)</a>
        <a href="{{ route('hpp.edit', $calculation) }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Edit Perhitungan</a>
        <a href="{{ route('hpp.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Kembali</a>
    </div>
</div>
@endsection
