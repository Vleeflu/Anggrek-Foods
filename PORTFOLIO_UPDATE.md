# Update: Portfolio & Sales Tracking System

## ✅ Yang Sudah Diimplemen tasikan

### 1. **Database Sales Tracking**
- Migration: `2025_12_21_151936_create_sales_table.php`
- Table `sales` dengan fields:
  - hpp_calculation_id (FK)
  - branch_id (FK, nullable)
  - sale_date
  - quantity_sold
  - selling_price_used
  - cost_per_unit (HPP per portion)
  - total_revenue
  - total_cost
  - profit
  - notes

### 2. **Models**
- `Sale` model dengan relationships:
  - `belongsTo(HppCalculation)`
  - `belongsTo(Branch)`
- `HppCalculation` model updated:
  - `hasMany(Sale)`

### 3. **Controllers**
- **SaleController**: CRUD untuk sales
  - `create()` - Form input penjualan
  - `store()` - Simpan penjualan (auto-calculate profit)
  - `index()` - List semua penjualan
  - `destroy()` - Hapus penjualan

- **PortfolioController** (Updated): Dashboard dengan statistics
  - Today's sales/profit
  - This month's sales/profit
  - All-time statistics
  - Recent sales (7 hari)
  - 30-day profit chart
  - Top 5 produk

### 4. **Views Created**
- `sales/create.blade.php` - Form tambah penjualan
  - Input: tanggal, cabang, qty, harga jual
  - Real-time profit preview
  - Auto-calculate dari HPP calculation

- `sales/index.blade.php` - List semua penjualan dengan filter
  - Tabel lengkap dengan semua details
  - Pagination
  - Delete functionality

- `portfolio/index.blade.php` - Dashboard modern
  - 3 card stats (Today, This Month, All-Time)
  - 30-day profit line chart (menggunakan Chart.js)
  - Top 5 produk terbaik
  - Recent sales table
  - Visual dengan gradients dan icons

### 5. **Routes**
```
GET    /sales                    - List semua penjualan
GET    /sales/create/{calc}      - Form input penjualan
POST   /sales/{calc}             - Save penjualan
DELETE /sales/{sale}             - Hapus penjualan
```

### 6. **Navigation Update**
- Header sekarang punya link "Penjualan"
- Urutan: Home → Portfolio → [Auth] Riwayat HPP → Penjualan → Cabang → Produk → Logout

### 7. **HPP Show Page**
- Added button "+ Tambah ke Portfolio (Catat Penjualan)"
- Direct link ke form sales create
- Prominent button dengan warna hijau

## 🎯 Workflow Penggunaan

### Step 1: Hitung HPP
1. Home → Pilih kategori → Pilih produk
2. Isi form calculator (bahan, kemasan, tenaga kerja, porsi, margin)
3. Klik "Simpan Perhitungan"

### Step 2: Catat Penjualan
1. Di halaman detail HPP, klik "+ Tambah ke Portfolio (Catat Penjualan)"
2. Isi form:
   - Tanggal penjualan
   - Cabang (optional)
   - Jumlah terjual
   - Harga jual yang digunakan (sudah default ke harga dari HPP)
   - Catatan (optional)
3. Lihat preview profit otomatis
4. Klik "Simpan ke Portfolio"

### Step 3: Monitor Portfolio
1. Klik "Portfolio" di header
2. Lihat statistik:
   - Profit hari ini
   - Profit bulan ini
   - Profit total
   - Grafik 30 hari
   - Top 5 produk
3. Klik "Lihat Semua" untuk riwayat penjualan lengkap

### Step 4: Lihat Riwayat Penjualan
1. Klik "Penjualan" di header
2. Lihat tabel lengkap semua penjualan
3. Bisa delete data penjualan

## 📊 Dashboard Features

### Statistics Cards
- **Today (Biru)**: Profit hari ini + qty + revenue
- **This Month (Hijau)**: Profit bulan ini + qty + revenue  
- **All-Time (Ungu)**: Profit total + qty + revenue

### Charts
- **30-Day Profit Chart**: Line chart profit harian (Chart.js)
  - X-axis: Tanggal (format: Jan 1, Jan 2, dst)
  - Y-axis: Profit (Rp format)
  - Interactive tooltip

### Top 5 Products
- List produk dengan profit tertinggi
- Ranked 1-5
- Menampilkan qty dan total profit

### Recent Sales Table
- 7 hari terakhir
- Info: Tanggal, Produk, Kategori, Cabang, Qty, Revenue, Profit
- Link ke halaman lengkap semua penjualan

## 🔧 Technical Details

### Automatic Calculations
```php
// Saat save penjualan:
$costPerUnit = $calculation->hpp_per_portion;
$totalRevenue = $quantity * $sellingPrice;
$totalCost = $quantity * $costPerUnit;
$profit = $totalRevenue - $totalCost;
```

### Chart.js Integration
- CDN: `https://cdn.jsdelivr.net/npm/chart.js@4.4.0`
- Format: Line chart dengan fill di bawah
- Color: Brand color (#2E7D32)

### Relationships
```
Sales ← HPP Calculation ← Product ← Category
  ↓
Branch (optional)
```

## 🎨 UI/UX Features

1. **Real-time Profit Preview**
   - Qty input → auto update preview
   - Harga jual input → auto update preview
   - Live calculation di form

2. **Visual Hierarchy**
   - Large profit numbers
   - Color-coded stats (Biru/Hijau/Ungu)
   - Icons untuk visual clarity

3. **Responsive Design**
   - Cards: grid md:grid-cols-3
   - Charts: md:col-span-2
   - Mobile-friendly

4. **Data Visualization**
   - Gradient backgrounds
   - Icons (SVG)
   - Styled tables dengan hover effects
   - Progress indicators untuk ranking

## ⚙️ Validasi & Error Handling

### Form Validation (sales/create)
- sale_date: required, date format
- quantity_sold: required, integer, min 1
- selling_price_used: required, numeric, min 0
- branch_id: nullable, exists in branches
- notes: nullable, string

### Error Display
- Red banner dengan list errors
- Form repopulates dengan old values
- Clear error messages

## 📝 Notes

1. **Portions di HPP Form**: Masih di bagian bawah, tapi sekarang jelas karena ada preview calculations
2. **Portfolio Adalah Public**: Bisa dilihat tanpa login (di-design untuk showcase)
3. **Sales adalah Auth**: Hanya bisa diakses setelah login
4. **Soft Delete**: Tidak diimplementasikan (hard delete)
5. **Chart Data**: Query otomatis ambil 30 hari terakhir

## 🚀 Bonus Features

- **Flash Messages**: Success notification setelah save
- **Delete Confirmation**: Confirm dialog sebelum delete
- **Pagination**: Sales list dengan 20 per page
- **Date Format**: Localized ke Indonesian (id-ID)
- **Currency Format**: Rupiah format dengan thousand separator
- **Responsive Images**: SVG icons di semua tempat

## 📱 Testing Checklist

- [ ] Hitung HPP untuk satu produk
- [ ] Save HPP calculation
- [ ] Klik "Tambah ke Portfolio"
- [ ] Isi form penjualan
- [ ] Verifikasi profit calculation
- [ ] Save ke portfolio
- [ ] Check Portfolio dashboard (stats, chart, top products)
- [ ] Check Recent sales di portfolio
- [ ] Click "Lihat Semua" → Check sales history table
- [ ] Delete satu penjualan dan verify
- [ ] Check multiple penjualan untuk lihat chart update
