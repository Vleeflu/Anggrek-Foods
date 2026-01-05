@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('menu.show', $category->slug) }}" class="text-sm text-gray-600 hover:text-gray-800">← Kembali ke menu rinci</a>
</div>

<div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
    <div class="px-5 py-4 -mx-6 -mt-6 mb-6 rounded-t-2xl text-white" style="background: linear-gradient(90deg, #F59E0B 0%, #D97706 100%);">
        <h2 class="text-2xl font-bold text-white">HPP Kalkulator — {{ strtoupper($product->name) }}</h2>
        <p class="text-sm text-white/90">Kategori: {{ $category->name }}</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 p-3 text-sm text-red-700">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form id="hppForm" method="POST" action="{{ route('hpp.store', [$category->slug, $product->slug]) }}" class="space-y-6">
        @csrf

        <!-- Bahan-Bahan Produk -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-brand-dark">Bahan-Bahan Produk</h3>
                <button type="button" onclick="addIngredientRow()" class="px-3 py-1 bg-brand text-white text-sm rounded hover:bg-brand-light">+ Tambah Bahan</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-amber-100 text-amber-900">
                        <tr class="font-semibold">
                            <th class="px-3 py-2 text-left">No</th>
                            <th class="px-3 py-2 text-left">Nama Bahan</th>
                            <th class="px-3 py-2 text-left">Jumlah</th>
                            <th class="px-3 py-2 text-left">Satuan</th>
                            <th class="px-3 py-2 text-left">Harga Bahan per Satuan</th>
                            <th class="px-3 py-2 text-left">Harga</th>
                            <th class="px-3 py-2"></th>
                        </tr>
                    </thead>
                    <tbody id="ingredientsTable">
                        <tr class="ingredient-row border-b bg-white">
                            <td class="px-3 py-2 row-number">1</td>
                            <td class="px-3 py-2"><input type="text" name="ingredients[0][name]" class="w-full border border-gray-300 rounded px-2 py-1 bg-white" placeholder="Nama bahan" required></td>
                            <td class="px-3 py-2"><input type="number" step="0.01" name="ingredients[0][quantity]" class="ingredient-qty w-full border border-gray-300 rounded px-2 py-1 bg-white" placeholder="0" value="0" required></td>
                            <td class="px-3 py-2">
                                <select name="ingredients[0][unit]" class="w-full border rounded px-2 py-1" required>
                                    <option>Kilogram</option>
                                    <option>Liter</option>
                                    <option>Gram</option>
                                    <option>Pcs</option>
                                </select>
                            </td>
                            <td class="px-3 py-2"><input type="number" step="0.01" name="ingredients[0][price_per_unit]" class="ingredient-price border border-gray-300 rounded px-2 py-1 w-28 text-left bg-white" placeholder="0" value="0" required></td>
                            <td class="px-3 py-2 ingredient-total font-semibold w-28 text-right">Rp0</td>
                            <td class="px-3 py-2"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-800">×</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-3 flex justify-end text-sm">
                <span class="font-medium">Total Bahan Produk: <span id="totalIngredients" class="text-brand-dark font-bold">Rp0</span></span>
            </div>
        </div>

        <!-- Biaya Utilitas dan Kemasan -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-brand-dark">Biaya Utilitas dan Kemasan</h3>
                <button type="button" onclick="addPackagingRow()" class="px-3 py-1 bg-brand text-white text-sm rounded hover:bg-brand-light">+ Tambah Item</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-amber-100 text-amber-900">
                        <tr class="font-semibold">
                            <th class="px-3 py-2 text-left">No</th>
                            <th class="px-3 py-2 text-left">Keterangan</th>
                            <th class="px-3 py-2 text-left">Harga</th>
                            <th class="px-3 py-2"></th>
                        </tr>
                    </thead>
                    <tbody id="packagingTable">
                        <tr class="packaging-row border-b bg-white">
                            <td class="px-3 py-2 row-number">1</td>
                            <td class="px-3 py-2"><input type="text" name="packaging[0][description]" class="w-full border border-gray-300 rounded px-2 py-1 bg-white" placeholder="Cup Gelas, Plastik, dll"></td>
                            <td class="px-3 py-2"><input type="number" step="0.01" name="packaging[0][price]" class="packaging-price w-full border border-gray-300 rounded px-2 py-1 bg-white" placeholder="0" value="0"></td>
                            <td class="px-3 py-2"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-800">×</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-3 flex justify-end text-sm">
                <span class="font-medium">Total Biaya Kemasan: <span id="totalPackaging" class="text-brand-dark font-bold">Rp0</span></span>
            </div>
        </div>

        <!-- Biaya Tenaga Kerja -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <h3 class="text-lg font-semibold text-brand-dark mb-3">Biaya Tenaga Kerja</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Keterangan</label>
                    <input type="text" id="laborDesc" class="w-full border border-gray-300 rounded px-3 py-2 bg-white" placeholder="Tenaga Kerja (per hari)" value="Tenaga Kerja (per hari)" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Harga (Rp)</label>
                    <input type="number" step="0.01" name="labor_cost" id="laborCost" class="w-full border border-gray-300 rounded px-3 py-2 bg-white" placeholder="0" value="0" required>
                </div>
            </div>
        </div>

        <!-- Porsi Produksi -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <h3 class="text-lg font-semibold text-brand-dark mb-3">Jumlah Produksi</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Jumlah Porsi yang Dihasilkan</label>
                    <input type="number" name="portions" id="portions" class="w-full border border-gray-300 rounded px-3 py-2 bg-white" placeholder="1" value="1" min="1" required>
                </div>
            </div>
        </div>

        <!-- Margin Keuntungan -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <h3 class="text-lg font-semibold text-brand-dark mb-3">Margin Keuntungan</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Persentase Profit (%)</label>
                    <input type="range" name="profit_margin_percent" id="profitSlider" min="0" max="100" value="30" class="w-full" oninput="updateMargin(this.value)">
                    <div class="text-center mt-2 text-2xl font-bold text-brand-dark"><span id="profitValue">30</span>%</div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <h3 class="text-lg font-semibold text-brand-dark mb-3">Catatan (Opsional)</h3>
            <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 bg-white" placeholder="Catatan tambahan..."></textarea>
        </div>

        <!-- Hidden input untuk menentukan aksi -->
        <input type="hidden" name="save_action" id="saveAction" value="hpp">

        <!-- Tombol Hitung -->
        <div class="flex items-center gap-4 flex-wrap">
            <button type="button" onclick="calculateHPP()" class="px-6 py-3 bg-brand text-white rounded-lg hover:bg-brand-dark font-semibold">Preview HPP</button>
            <button type="submit" onclick="document.getElementById('saveAction').value='hpp'" class="px-6 py-3 bg-brand-dark text-white rounded-lg hover:bg-brand font-semibold">Simpan Perhitungan</button>
            <button type="submit" onclick="document.getElementById('saveAction').value='sales'" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">Simpan ke Penjualan</button>
            <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-gray-800">Kembali ke Home</a>
        </div>
    </form>

    <!-- Hasil Perhitungan -->
    <div id="resultSection" class="mt-8 hidden">
        <div class="px-4 py-3 rounded-t-lg text-gray-900" style="background: linear-gradient(90deg, #FF9500 0%, #FFEB00 100%);">
            <h3 class="text-xl font-semibold">HASIL PERHITUNGAN (PREVIEW)</h3>
        </div>
        <div class="border-2 border-brand/30 rounded-b-lg p-6 space-y-4">
            <!-- Summary Biaya -->
            <div class="grid md:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-4 border border-amber-100">
                    <h4 class="font-semibold mb-3 text-brand-dark">Rincian Biaya</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span>Total Bahan Produk:</span><span id="resultIngredients" class="font-semibold">Rp0</span></div>
                        <div class="flex justify-between"><span>Total Biaya Kemasan:</span><span id="resultPackaging" class="font-semibold">Rp0</span></div>
                        <div class="flex justify-between"><span>Biaya Tenaga Kerja:</span><span id="resultLabor" class="font-semibold">Rp0</span></div>
                        <div class="flex justify-between border-t pt-2"><span class="font-bold">Total Semua Biaya:</span><span id="resultTotalCost" class="font-bold text-brand-dark">Rp0</span></div>
                        <div class="flex justify-between border-t pt-2"><span class="font-bold">Produksi/Total Biaya:</span><span id="resultProduction" class="font-bold text-brand-dark">Rp0</span></div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 border border-amber-100">
                    <h4 class="font-semibold mb-3 text-brand-dark">Harga Pokok Penjualan (HPP)</h4>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-brand-dark" id="resultHPP">Rp0</div>
                        <div class="text-sm text-gray-600 mt-1">Per Produk</div>
                    </div>

                    <div class="mt-4">
                        <div class="flex justify-between text-sm mb-1"><span>Margin Keuntungan:</span><span id="resultMarginPercent" class="font-semibold">0%</span></div>
                        <div class="flex justify-between text-sm mb-1"><span>Profit:</span><span id="resultProfit" class="font-semibold">Rp0</span></div>
                        <div class="flex justify-between text-lg font-bold mt-3 pt-3 border-t"><span>Harga Jual:</span><span id="resultSellingPrice" class="text-brand-dark">Rp0</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg text-sm text-blue-700">
                <strong>Info:</strong> Ini adalah preview perhitungan. Klik tombol "Simpan Perhitungan" di atas untuk menyimpan hasil ke database.
            </div>
        </div>
    </div>
</div>

<script>
let ingredientIndex = 1;
let packagingIndex = 1;

function addIngredientRow() {
    const table = document.getElementById('ingredientsTable');
    const row = document.createElement('tr');
    row.className = 'ingredient-row border-b';
    row.innerHTML = `
        <td class="px-3 py-2 row-number">${ingredientIndex + 1}</td>
        <td class="px-3 py-2"><input type="text" name="ingredients[${ingredientIndex}][name]" class="w-full border rounded px-2 py-1" placeholder="Nama bahan" required></td>
        <td class="px-3 py-2"><input type="number" step="0.01" name="ingredients[${ingredientIndex}][quantity]" class="ingredient-qty w-full border rounded px-2 py-1" placeholder="0" value="0" required></td>
        <td class="px-3 py-2">
            <select name="ingredients[${ingredientIndex}][unit]" class="w-full border rounded px-2 py-1" required>
                <option>Kilogram</option>
                <option>Liter</option>
                <option>Gram</option>
                <option>Pcs</option>
            </select>
        </td>
        <td class="px-3 py-2"><input type="number" step="0.01" name="ingredients[${ingredientIndex}][price_per_unit]" class="ingredient-price border rounded px-2 py-1 w-28 text-left" placeholder="0" value="0" required></td>
        <td class="px-3 py-2 ingredient-total font-semibold w-28 text-right">Rp0</td>
        <td class="px-3 py-2"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-800">×</button></td>
    `;
    table.appendChild(row);
    ingredientIndex++;
    updateRowNumbers('ingredientsTable');
    attachIngredientListeners(row);
}

function addPackagingRow() {
    const table = document.getElementById('packagingTable');
    const row = document.createElement('tr');
    row.className = 'packaging-row border-b';
    row.innerHTML = `
        <td class="px-3 py-2 row-number">${packagingIndex + 1}</td>
        <td class="px-3 py-2"><input type="text" name="packaging[${packagingIndex}][description]" class="w-full border rounded px-2 py-1" placeholder="Cup Gelas, Plastik, dll"></td>
        <td class="px-3 py-2"><input type="number" step="0.01" name="packaging[${packagingIndex}][price]" class="packaging-price w-full border rounded px-2 py-1" placeholder="0" value="0"></td>
        <td class="px-3 py-2"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-800">×</button></td>
    `;
    table.appendChild(row);
    packagingIndex++;
    updateRowNumbers('packagingTable');
    attachPackagingListeners(row);
}

function removeRow(button) {
    const row = button.closest('tr');
    const table = row.closest('tbody');
    row.remove();
    updateRowNumbers(table.id);
    calculateTotals();
}

function updateRowNumbers(tableId) {
    const table = document.getElementById(tableId);
    const rows = table.querySelectorAll('tr');
    rows.forEach((row, index) => {
        const numberCell = row.querySelector('.row-number');
        if (numberCell) numberCell.textContent = index + 1;
    });
}

function formatRupiah(number) {
    return 'Rp' + Math.round(number).toLocaleString('id-ID');
}

function calculateTotals() {
    // Calculate ingredients total
    let totalIngredients = 0;
    document.querySelectorAll('.ingredient-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.ingredient-qty').value) || 0;
        const price = parseFloat(row.querySelector('.ingredient-price').value) || 0;
        const total = qty * price;
        row.querySelector('.ingredient-total').textContent = formatRupiah(total);
        totalIngredients += total;
    });
    document.getElementById('totalIngredients').textContent = formatRupiah(totalIngredients);

    // Calculate packaging total (per unit * portions)
    const portions = parseInt(document.getElementById('portions').value) || 1;
    let totalPackaging = 0;
    document.querySelectorAll('.packaging-price').forEach(input => {
        totalPackaging += (parseFloat(input.value) || 0) * portions;
    });
    document.getElementById('totalPackaging').textContent = formatRupiah(totalPackaging);
}

function attachIngredientListeners(row) {
    const qtyInput = row.querySelector('.ingredient-qty');
    const priceInput = row.querySelector('.ingredient-price');
    qtyInput.addEventListener('input', calculateTotals);
    priceInput.addEventListener('input', calculateTotals);
}

function attachPackagingListeners(row) {
    const priceInput = row.querySelector('.packaging-price');
    priceInput.addEventListener('input', calculateTotals);
}

function updateMargin(value) {
    document.getElementById('profitValue').textContent = value;
}

function calculateHPP() {
    const laborCost = parseFloat(document.getElementById('laborCost').value) || 0;
    const portions = parseInt(document.getElementById('portions').value) || 1;
    const profitMargin = parseFloat(document.getElementById('profitSlider').value) || 0;

    let totalIngredients = 0;
    document.querySelectorAll('.ingredient-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.ingredient-qty').value) || 0;
        const price = parseFloat(row.querySelector('.ingredient-price').value) || 0;
        totalIngredients += qty * price;
    });

    let totalPackaging = 0;
    document.querySelectorAll('.packaging-price').forEach(input => {
        totalPackaging += (parseFloat(input.value) || 0) * portions;
    });

    const totalCost = totalIngredients + totalPackaging + laborCost;
    const hppPerPortion = totalCost / portions;
    const profitAmount = hppPerPortion * (profitMargin / 100);
    const sellingPrice = hppPerPortion + profitAmount;

    // Display results
    document.getElementById('resultIngredients').textContent = formatRupiah(totalIngredients);
    document.getElementById('resultPackaging').textContent = formatRupiah(totalPackaging);
    document.getElementById('resultLabor').textContent = formatRupiah(laborCost);
    document.getElementById('resultTotalCost').textContent = formatRupiah(totalCost);
    document.getElementById('resultProduction').textContent = formatRupiah(hppPerPortion);
    document.getElementById('resultHPP').textContent = formatRupiah(hppPerPortion);
    document.getElementById('resultMarginPercent').textContent = profitMargin + '%';
    document.getElementById('resultProfit').textContent = formatRupiah(profitAmount);
    document.getElementById('resultSellingPrice').textContent = formatRupiah(sellingPrice);

    document.getElementById('resultSection').classList.remove('hidden');
}

// Initialize listeners on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.ingredient-row').forEach(attachIngredientListeners);
    document.querySelectorAll('.packaging-row').forEach(attachPackagingListeners);

    document.getElementById('laborCost').addEventListener('input', calculateTotals);
});
</script>
@endsection
