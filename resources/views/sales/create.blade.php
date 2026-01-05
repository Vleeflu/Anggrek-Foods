@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('hpp.show', $calculation) }}" class="text-sm text-gray-600 hover:text-gray-800">← Kembali ke Detail HPP</a>
</div>

<div class="bg-white rounded-xl shadow p-6 max-w-3xl mx-auto">
    <div class="px-4 py-3 -mx-6 -mt-6 mb-6 rounded-t-xl text-white" style="background: linear-gradient(90deg, #F59E0B 0%, #D97706 100%);">
        <h2 class="text-2xl font-semibold">Tambah Data Penjualan</h2>
        <p class="text-sm opacity-90">{{ $calculation->product->name }} - {{ $calculation->product->category->name }}</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- HPP Info -->
    <div class="mb-6 rounded-lg p-4 border-l-4 border-amber-400" style="background: linear-gradient(90deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.1) 100%);">
        <div class="grid md:grid-cols-3 gap-4 text-sm">
            <div>
                <div class="text-gray-600">HPP per Porsi</div>
                <div class="text-xl font-bold text-brand">Rp{{ number_format($calculation->hpp_per_portion, 0, ',', '.') }}</div>
            </div>
            <div>
                <div class="text-gray-600">Harga Jual Disarankan</div>
                <div class="text-xl font-bold text-brand-dark">Rp{{ number_format($calculation->selling_price, 0, ',', '.') }}</div>
            </div>
            <div>
                <div class="text-gray-600">Margin</div>
                <div class="text-xl font-bold text-purple-600">{{ $calculation->profit_margin_percent }}%</div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('sales.store', $calculation) }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Tanggal Penjualan <span class="text-red-500">*</span></label>
            <input type="date" name="sale_date" value="{{ old('sale_date', now()->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2" required>
        </div>



        <div>
            <label class="block text-sm font-medium mb-1">Jumlah Terjual <span class="text-red-500">*</span></label>
            <input type="number" name="quantity_sold" id="quantitySold" value="{{ old('quantity_sold', 1) }}" min="1" class="w-full border rounded px-3 py-2" required onchange="calculateProfit()">
            <p class="text-xs text-gray-500 mt-1">Berapa banyak produk yang terjual</p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Harga Jual yang Digunakan <span class="text-red-500">*</span></label>
            <div class="relative">
                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                <input type="number" step="0.01" name="selling_price_used" id="sellingPrice" value="{{ old('selling_price_used', $calculation->selling_price) }}" min="0" class="w-full border rounded px-10 py-2" required onchange="calculateProfit()">
            </div>
            <p class="text-xs text-gray-500 mt-1">Harga jual per porsi (default: harga yang disarankan dari perhitungan HPP)</p>
        </div>

        <!-- Profit Preview -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="font-semibold mb-3">Preview Perhitungan</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span>Jumlah Terjual:</span><span id="previewQty" class="font-semibold">1 porsi</span></div>
                <div class="flex justify-between"><span>Harga Jual per Porsi:</span><span id="previewPrice" class="font-semibold">Rp{{ number_format($calculation->selling_price, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span>HPP per Porsi:</span><span class="font-semibold">Rp{{ number_format($calculation->hpp_per_portion, 0, ',', '.') }}</span></div>
                <div class="flex justify-between border-t pt-2"><span class="font-bold">Total Revenue:</span><span id="previewRevenue" class="font-bold text-brand-dark">Rp{{ number_format($calculation->selling_price, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span class="font-bold">Total Cost:</span><span id="previewCost" class="font-bold text-red-600">Rp{{ number_format($calculation->hpp_per_portion, 0, ',', '.') }}</span></div>
                <div class="flex justify-between border-t pt-2"><span class="font-bold text-lg">Profit:</span><span id="previewProfit" class="font-bold text-lg text-brand">Rp{{ number_format($calculation->selling_price - $calculation->hpp_per_portion, 0, ',', '.') }}</span></div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Catatan</label>
            <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2" placeholder="Catatan tambahan tentang penjualan ini...">{{ old('notes') }}</textarea>
        </div>

        <div class="flex gap-4 pt-4">
            <button type="submit" class="px-6 py-3 bg-brand text-white rounded-lg hover:bg-brand-dark font-semibold">Simpan Daftar Penjualan</button>
            <a href="{{ route('hpp.show', $calculation) }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center">Batal</a>
        </div>
    </form>
</div>

<script>
const hppPerPortion = {{ $calculation->hpp_per_portion }};

function calculateProfit() {
    const qty = parseInt(document.getElementById('quantitySold').value) || 1;
    const price = parseFloat(document.getElementById('sellingPrice').value) || 0;

    const revenue = qty * price;
    const cost = qty * hppPerPortion;
    const profit = revenue - cost;

    document.getElementById('previewQty').textContent = qty + ' porsi';
    document.getElementById('previewPrice').textContent = formatRupiah(price);
    document.getElementById('previewRevenue').textContent = formatRupiah(revenue);
    document.getElementById('previewCost').textContent = formatRupiah(cost);
    document.getElementById('previewProfit').textContent = formatRupiah(profit);
}

function formatRupiah(number) {
    return 'Rp' + Math.round(number).toLocaleString('id-ID');
}

// Calculate on page load
calculateProfit();
</script>
@endsection
