# Anggrek Foods - HPP Calculator CRUD Features

## Fitur yang Telah Ditambahkan

### 1. Manajemen Cabang (Branches)
Lokasi: `/branches`

**Fitur:**
- List semua cabang dengan informasi: Nama, Kode, Alamat, Telepon, Status
- Tambah cabang baru
- Edit cabang existing
- Hapus cabang
- Status aktif/nonaktif

**Akses:**
- Menu: Klik "Cabang" di header (hanya untuk user yang login)
- URL: http://localhost:8000/branches

### 2. Manajemen Produk (Products)
Lokasi: `/products`

**Fitur:**
- List semua produk dengan kategori
- Tambah produk baru (dengan pilihan kategori)
- Edit produk existing
- Hapus produk
- Auto-generate slug dari nama produk
- Status aktif/nonaktif

**Akses:**
- Menu: Klik "Produk" di header (hanya untuk user yang login)
- URL: http://localhost:8000/products

### 3. Riwayat Perhitungan HPP
Lokasi: `/hpp`

**Fitur:**
- List semua perhitungan HPP yang pernah disimpan
- Detail lengkap perhitungan (bahan-bahan, kemasan, biaya, hasil)
- Edit perhitungan existing
- Hapus perhitungan
- Filter by tanggal, produk, cabang, user

**Akses:**
- Menu: Klik "Riwayat HPP" di header (hanya untuk user yang login)
- URL: http://localhost:8000/hpp

### 4. HPP Calculator - Simpan ke Database
Lokasi: `/hpp/{category}/{item}`

**Perubahan:**
- Form calculator sekarang bisa menyimpan ke database
- Menambahkan pilihan cabang
- Menambahkan margin keuntungan (0-100%)
- Menambahkan catatan
- Preview hasil sebelum disimpan
- Semua bahan dan kemasan disimpan dengan relasi ke perhitungan

**Tombol:**
- "Preview HPP" - Lihat hasil tanpa save
- "Simpan Perhitungan" - Save ke database

## Struktur Database

### Tables:
1. **branches** - Data cabang
2. **product_categories** - Kategori produk (Mie, Nasi Goreng, Ayam)
3. **products** - Produk per kategori
4. **hpp_calculations** - Header perhitungan HPP
5. **hpp_ingredients** - Detail bahan per perhitungan
6. **hpp_packaging_costs** - Detail biaya kemasan per perhitungan

### Relasi:
- `hpp_calculations` → `products` (belongsTo)
- `hpp_calculations` → `branches` (belongsTo, nullable)
- `hpp_calculations` → `users` (belongsTo)
- `hpp_ingredients` → `hpp_calculations` (belongsTo)
- `hpp_packaging_costs` → `hpp_calculations` (belongsTo)

## Routes Baru

### Branches
```
GET    /branches           - List cabang
GET    /branches/create    - Form tambah cabang
POST   /branches           - Save cabang baru
GET    /branches/{id}/edit - Form edit cabang
PUT    /branches/{id}      - Update cabang
DELETE /branches/{id}      - Hapus cabang
```

### Products
```
GET    /products           - List produk
GET    /products/create    - Form tambah produk
POST   /products           - Save produk baru
GET    /products/{id}/edit - Form edit produk
PUT    /products/{id}      - Update produk
DELETE /products/{id}      - Hapus produk
```

### HPP Calculations
```
GET    /hpp                    - List riwayat perhitungan
GET    /hpp/{cat}/{prod}       - Form calculator
POST   /hpp/{cat}/{prod}       - Simpan perhitungan
GET    /hpp/show/{id}          - Detail perhitungan
GET    /hpp/edit/{id}          - Edit perhitungan
PUT    /hpp/{id}               - Update perhitungan
DELETE /hpp/{id}               - Hapus perhitungan
```

## Cara Menggunakan

### 1. Login
```
URL: http://localhost:8000/login
Username: admin
Password: Anggrek2729
```

### 2. Tambah Cabang
1. Klik "Cabang" di header
2. Klik "+ Tambah Cabang"
3. Isi form:
   - Nama Cabang (required)
   - Kode Cabang (required, unique)
   - Alamat (optional)
   - Telepon (optional)
   - Status Aktif (checkbox)
4. Klik "Simpan"

### 3. Tambah Produk
1. Klik "Produk" di header
2. Klik "+ Tambah Produk"
3. Isi form:
   - Kategori (required, dropdown)
   - Nama Produk (required)
   - Slug (optional, auto-generate dari nama)
   - Deskripsi (optional)
   - Status Aktif (checkbox)
4. Klik "Simpan"

### 4. Hitung dan Simpan HPP
1. Dari home, pilih kategori (Mie/Nasi Goreng/Ayam)
2. Klik produk yang ingin dihitung HPP-nya
3. Isi form calculator:
   - Pilih cabang (optional)
   - Tambah bahan-bahan (klik + Tambah Bahan)
   - Tambah biaya kemasan (klik + Tambah Item)
   - Isi biaya tenaga kerja
   - Isi jumlah porsi
   - Atur margin keuntungan (slider 0-100%)
   - Tambah catatan (optional)
4. Klik "Preview HPP" untuk lihat hasil
5. Klik "Simpan Perhitungan" untuk save ke database
6. Setelah disimpan, akan redirect ke halaman detail

### 5. Lihat Riwayat HPP
1. Klik "Riwayat HPP" di header
2. Lihat list semua perhitungan
3. Klik "Lihat" untuk detail
4. Klik "Edit" untuk mengubah
5. Klik "Hapus" untuk menghapus

## Controllers

### BranchController
File: `app/Http/Controllers/BranchController.php`
- Full CRUD operations
- Validation rules
- Flash messages

### ProductController
File: `app/Http/Controllers/ProductController.php`
- Full CRUD operations
- Auto-slug generation with Str::slug()
- Category relationships
- Validation rules

### HppController (Updated)
File: `app/Http/Controllers/HppController.php`
- index() - List perhitungan
- form() - Show calculator form
- store() - Save new calculation
- show() - Detail calculation
- edit() - Edit form
- update() - Update calculation
- destroy() - Delete calculation

## Views Created

### Branches
- `resources/views/branches/index.blade.php` - List cabang
- `resources/views/branches/create.blade.php` - Form tambah
- `resources/views/branches/edit.blade.php` - Form edit

### Products
- `resources/views/products/index.blade.php` - List produk
- `resources/views/products/create.blade.php` - Form tambah
- `resources/views/products/edit.blade.php` - Form edit

### HPP
- `resources/views/hpp/index.blade.php` - Riwayat perhitungan
- `resources/views/hpp/form.blade.php` - Calculator form (updated)
- `resources/views/hpp/show.blade.php` - Detail perhitungan
- `resources/views/hpp/edit.blade.php` - Edit perhitungan

### Layout
- `resources/views/layouts/app.blade.php` - Updated navigation dengan link Riwayat HPP, Cabang, Produk

## Testing

### 1. Test Branches CRUD
```bash
# Login dulu
http://localhost:8000/login

# Akses branches
http://localhost:8000/branches

# Test create, edit, delete
```

### 2. Test Products CRUD
```bash
http://localhost:8000/products
```

### 3. Test HPP Calculator
```bash
# Pilih produk dari home
http://localhost:8000/

# Isi form dan save
# Check di riwayat HPP
http://localhost:8000/hpp
```

## Validasi

### Branch
- name: required, string, max 255
- code: required, string, max 50, unique
- address: nullable, string
- phone: nullable, string, max 50
- is_active: boolean

### Product
- product_category_id: required, exists in product_categories
- name: required, string, max 255
- slug: nullable, string, max 255, unique (auto-generate if empty)
- description: nullable, string
- is_active: boolean

### HPP Calculation
- branch_id: nullable, exists in branches
- ingredients: required, array, min 1
  - name: required, string
  - quantity: required, numeric, min 0
  - unit: required, string
  - price_per_unit: required, numeric, min 0
- packaging: nullable, array
  - description: required, string
  - price: required, numeric, min 0
- labor_cost: required, numeric, min 0
- portions: required, integer, min 1
- profit_margin_percent: required, numeric, min 0
- notes: nullable, string

## Flash Messages

Semua operations (create, update, delete) akan menampilkan flash message:
- Success: Green banner di atas
- Error: Red banner dengan list errors

## Notes

1. Semua fitur CRUD hanya bisa diakses setelah login
2. Soft delete tidak diimplementasikan (hard delete)
3. Pagination diterapkan pada:
   - HPP calculations (20 per page)
   - Products (default Laravel pagination)
4. Navigation di header otomatis update based on auth status
5. Form validation diterapkan di semua input
