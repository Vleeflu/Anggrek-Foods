@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('hpp.show', $calculation) }}" class="text-sm text-gray-600 hover:text-gray-800">← Kembali ke Detail</a>
</div>

<div class="bg-white rounded-xl shadow p-6 border border-amber-100">
    <div class="px-4 py-3 -mx-6 -mt-6 mb-6 rounded-t-xl text-white" style="background: linear-gradient(90deg, #F59E0B 0%, #D97706 100%);">

        <h2 class="text-2xl font-semibold">EDIT PERHITUNGAN HPP</h2>
        <p class="text-sm opacity-90">{{ $calculation->product->name }} - {{ $calculation->product->category->name }}</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 p-3 text-sm text-red-700">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form id="hppForm" method="POST" action="{{ route('hpp.update', $calculation) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Bahan-Bahan Produk -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-brand-dark">Bahan-Bahan Produk</h3>
                <button type="button" onclick="addIngredientRow()" class="px-3 py-1 bg-brand text-white text-sm rounded hover:bg-brand-dark">+ Tambah Bahan</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-brand-dark text-white">
                        <tr>
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
                        @foreach($calculation->ingredients as $index => $ingredient)
                        <tr class="ingredient-row border-b">
                            <td class="px-3 py-2 row-number">{{ $index + 1 }}</td>
                            <td class="px-3 py-2"><input type="text" name="ingredients[{{ $index }}][name]" value="{{ old('ingredients.'.$index.'.name', $ingredient->name) }}" class="w-full border rounded px-2 py-1" required></td>
                            <td class="px-3 py-2"><input type="number" step="0.01" name="ingredients[{{ $index }}][quantity]" value="{{ old('ingredients.'.$index.'.quantity', $ingredient->quantity) }}" class="ingredient-qty w-full border rounded px-2 py-1" required></td>
                            <td class="px-3 py-2">
                                <select name="ingredients[{{ $index }}][unit]" class="w-full border rounded px-2 py-1" required>
                                    <option {{ $ingredient->unit == 'Kilogram' ? 'selected' : '' }}>Kilogram</option>
                                    <option {{ $ingredient->unit == 'Liter' ? 'selected' : '' }}>Liter</option>
                                    <option {{ $ingredient->unit == 'Gram' ? 'selected' : '' }}>Gram</option>
                                    <option {{ $ingredient->unit == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                                </select>
                            </td>
                            <td class="px-3 py-2"><input type="number" step="0.01" name="ingredients[{{ $index }}][price_per_unit]" value="{{ old('ingredients.'.$index.'.price_per_unit', $ingredient->price_per_unit) }}" class="ingredient-price w-full border rounded px-2 py-1" required></td>
                            <td class="px-3 py-2 ingredient-total font-semibold">Rp{{ number_format($ingredient->total_price, 0, ',', '.') }}</td>
                            <td class="px-3 py-2"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-800">×</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3 flex justify-end text-sm">
                <span class="font-medium">Total Bahan Produk: <span id="totalIngredients" class="text-brand-dark font-bold">Rp{{ number_format($calculation->total_ingredients_cost, 0, ',', '.') }}</span></span>
            </div>
        </div>

        <!-- Biaya Utilitas dan Kemasan -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-brand-dark">Biaya Utilitas dan Kemasan</h3>
                <button type="button" onclick="addPackagingRow()" class="px-3 py-1 bg-brand text-white text-sm rounded hover:bg-brand-dark">+ Tambah Item</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-brand-dark text-white">
                        <tr>
                            <th class="px-3 py-2 text-left">No</th>
                            <th class="px-3 py-2 text-left">Keterangan</th>
                            <th class="px-3 py-2 text-left">Harga</th>
                            <th class="px-3 py-2"></th>
                        </tr>
                    </thead>
                    <tbody id="packagingTable">
                        @foreach($calculation->packagingCosts as $index => $packaging)
                        <tr class="packaging-row border-b">
                            <td class="px-3 py-2 row-number">{{ $index + 1 }}</td>
                            <td class="px-3 py-2"><input type="text" name="packaging[{{ $index }}][description]" value="{{ old('packaging.'.$index.'.description', $packaging->description) }}" class="w-full border rounded px-2 py-1"></td>
                            <td class="px-3 py-2"><input type="number" step="0.01" name="packaging[{{ $index }}][price]" value="{{ old('packaging.'.$index.'.price', $packaging->price) }}" class="packaging-price w-full border rounded px-2 py-1"></td>
                            <td class="px-3 py-2"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-800">×</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3 flex justify-end text-sm">
                <span class="font-medium">Total Biaya Kemasan: <span id="totalPackaging" class="text-brand-dark font-bold">Rp{{ number_format($calculation->total_packaging_cost, 0, ',', '.') }}</span></span>
            </div>
        </div>

        <!-- Biaya Tenaga Kerja -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <h3 class="text-lg font-semibold text-brand-dark mb-3">Biaya Tenaga Kerja</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Keterangan</label>
                    <input type="text" id="laborDesc" class="w-full border rounded px-3 py-2" value="Tenaga Kerja" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Harga (Rp)</label>
                    <input type="number" step="0.01" name="labor_cost" id="laborCost" value="{{ old('labor_cost', $calculation->labor_cost) }}" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>
        </div>

        <!-- Porsi Produksi -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <h3 class="text-lg font-semibold text-brand-dark mb-3">Jumlah Produksi</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Jumlah Porsi yang Dihasilkan</label>
                    <input type="number" name="portions" id="portions" value="{{ old('portions', $calculation->portions) }}" class="w-full border rounded px-3 py-2" min="1" required>
                </div>
            </div>
        </div>

        <!-- Margin Keuntungan -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <h3 class="text-lg font-semibold text-brand-dark mb-3">Margin Keuntungan</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Persentase Profit (%)</label>
                    <input type="range" name="profit_margin_percent" id="profitSlider" min="0" max="100" value="{{ old('profit_margin_percent', $calculation->profit_margin_percent) }}" class="w-full" oninput="updateMargin(this.value)">
                    <div class="text-center mt-2 text-2xl font-bold text-brand-dark"><span id="profitValue">{{ old('profit_margin_percent', $calculation->profit_margin_percent) }}</span>%</div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <h3 class="text-lg font-semibold text-brand-dark mb-3">Catatan (Opsional)</h3>
            <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 bg-white">{{ old('notes', $calculation->notes) }}</textarea>
        </div>

        <!-- Tombol Submit -->
        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-3 bg-brand text-white rounded-lg hover:bg-brand-dark font-semibold">Simpan Perubahan</button>
            <a href="{{ route('hpp.show', $calculation) }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Batal</a>
        </div>
    </form>
</div>

<script>
let ingredientIndex = {{ count($calculation->ingredients) }};
let packagingIndex = {{ count($calculation->packagingCosts) }};

function addIngredientRow() {
    const table = document.getElementById('ingredientsTable');
    const row = document.createElement('tr');
    row.className = 'ingredient-row border-b';
    row.innerHTML = `
        <td class="px-3 py-2 row-number">${ingredientIndex + 1}</td>
        <td class="px-3 py-2"><input type="text" name="ingredients[${ingredientIndex}][name]" class="w-full border rounded px-2 py-1" required></td>
        <td class="px-3 py-2"><input type="number" step="0.01" name="ingredients[${ingredientIndex}][quantity]" class="ingredient-qty w-full border rounded px-2 py-1" value="0" required></td>
        <td class="px-3 py-2">
            <select name="ingredients[${ingredientIndex}][unit]" class="w-full border rounded px-2 py-1" required>
                <option>Kilogram</option>
                <option>Liter</option>
                <option>Gram</option>
                <option>Pcs</option>
            </select>
        </td>
        <td class="px-3 py-2"><input type="number" step="0.01" name="ingredients[${ingredientIndex}][price_per_unit]" class="ingredient-price w-full border rounded px-2 py-1" value="0" required></td>
        <td class="px-3 py-2 ingredient-total font-semibold">Rp0</td>
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
        <td class="px-3 py-2"><input type="text" name="packaging[${packagingIndex}][description]" class="w-full border rounded px-2 py-1"></td>
        <td class="px-3 py-2"><input type="number" step="0.01" name="packaging[${packagingIndex}][price]" class="packaging-price w-full border rounded px-2 py-1" value="0"></td>
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
    let totalIngredients = 0;
    document.querySelectorAll('.ingredient-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.ingredient-qty').value) || 0;
        const price = parseFloat(row.querySelector('.ingredient-price').value) || 0;
        const total = qty * price;
        row.querySelector('.ingredient-total').textContent = formatRupiah(total);
        totalIngredients += total;
    });
    document.getElementById('totalIngredients').textContent = formatRupiah(totalIngredients);

    let totalPackaging = 0;
    document.querySelectorAll('.packaging-price').forEach(input => {
        totalPackaging += parseFloat(input.value) || 0;
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

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.ingredient-row').forEach(attachIngredientListeners);
    document.querySelectorAll('.packaging-row').forEach(attachPackagingListeners);
    document.getElementById('laborCost').addEventListener('input', calculateTotals);
});
</script>
@endsection
