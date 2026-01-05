# Database Schema - Anggrek Foods HPP Calculator

## Overview
Database yang terstruktur dengan relasi antar tabel menggunakan foreign keys untuk mengelola perhitungan HPP (Harga Pokok Penjualan) untuk UMKM Anggrek Foods.

## Database Tables

### 1. **users**
Tabel pengguna yang dapat mengakses sistem.
- `id` - Primary Key
- `name` - Nama pengguna
- `username` - Username untuk login (unique)
- `email` - Email pengguna (unique)
- `password` - Password (hashed)
- `remember_token`
- `timestamps`

**Relationships:**
- Has many `hpp_calculations`

---

### 2. **branches**
Tabel cabang/lokasi usaha Anggrek Foods.
- `id` - Primary Key
- `name` - Nama cabang
- `code` - Kode cabang (unique)
- `address` - Alamat cabang
- `phone` - Nomor telepon
- `is_active` - Status aktif (boolean)
- `timestamps`

**Relationships:**
- Has many `hpp_calculations`

---

### 3. **product_categories**
Kategori produk utama (Mie, Nasi Goreng, Ayam).
- `id` - Primary Key
- `name` - Nama kategori
- `slug` - Slug URL-friendly (unique)
- `description` - Deskripsi kategori
- `sort_order` - Urutan tampilan
- `timestamps`

**Relationships:**
- Has many `products`

---

### 4. **products**
Produk detail di setiap kategori (Mie Ayam, Mie Goreng, dll).
- `id` - Primary Key
- `product_category_id` - Foreign Key → `product_categories.id`
- `name` - Nama produk
- `slug` - Slug URL-friendly (unique)
- `description` - Deskripsi produk
- `is_active` - Status aktif (boolean)
- `timestamps`

**Relationships:**
- Belongs to `product_category`
- Has many `hpp_calculations`

---

### 5. **hpp_calculations**
Perhitungan HPP untuk produk tertentu.
- `id` - Primary Key
- `user_id` - Foreign Key → `users.id`
- `branch_id` - Foreign Key → `branches.id` (nullable)
- `product_id` - Foreign Key → `products.id`
- `total_ingredients_cost` - Total biaya bahan (decimal 15,2)
- `total_packaging_cost` - Total biaya kemasan (decimal 15,2)
- `labor_cost` - Biaya tenaga kerja (decimal 15,2)
- `portions` - Jumlah porsi yang dihasilkan (integer)
- `total_cost` - Total semua biaya (decimal 15,2)
- `hpp_per_portion` - HPP per porsi (decimal 15,2)
- `profit_margin_percent` - Persentase keuntungan (decimal 5,2)
- `profit_amount` - Nominal keuntungan (decimal 15,2)
- `selling_price` - Harga jual (decimal 15,2)
- `notes` - Catatan tambahan (text)
- `timestamps`

**Relationships:**
- Belongs to `user`
- Belongs to `branch`
- Belongs to `product`
- Has many `hpp_ingredients`
- Has many `hpp_packaging_costs`

---

### 6. **hpp_ingredients**
Detail bahan-bahan yang digunakan dalam perhitungan HPP.
- `id` - Primary Key
- `hpp_calculation_id` - Foreign Key → `hpp_calculations.id`
- `name` - Nama bahan
- `quantity` - Jumlah (decimal 10,2)
- `unit` - Satuan (Kilogram, Liter, Gram, Pcs)
- `price_per_unit` - Harga per satuan (decimal 15,2)
- `total_price` - Total harga (quantity × price_per_unit)
- `timestamps`

**Relationships:**
- Belongs to `hpp_calculation`

---

### 7. **hpp_packaging_costs**
Detail biaya kemasan dan utilitas.
- `id` - Primary Key
- `hpp_calculation_id` - Foreign Key → `hpp_calculations.id`
- `description` - Keterangan (Cup Gelas, Plastik, dll)
- `price` - Harga (decimal 15,2)
- `timestamps`

**Relationships:**
- Belongs to `hpp_calculation`

---

### 8. **sessions**
Tabel sesi untuk session driver database.
- `id` - Primary Key (string)
- `user_id` - Foreign Key → `users.id` (nullable)
- `ip_address` - IP address
- `user_agent` - Browser user agent
- `payload` - Session data
- `last_activity` - Timestamp terakhir aktif

---

## Entity Relationship Diagram (ERD)

```
users
  └─── hpp_calculations (one-to-many)

branches
  └─── hpp_calculations (one-to-many)

product_categories
  └─── products (one-to-many)
         └─── hpp_calculations (one-to-many)
                ├─── hpp_ingredients (one-to-many)
                └─── hpp_packaging_costs (one-to-many)
```

## Foreign Key Constraints

1. `products.product_category_id` → `product_categories.id` (CASCADE on delete)
2. `hpp_calculations.user_id` → `users.id` (CASCADE on delete)
3. `hpp_calculations.branch_id` → `branches.id` (SET NULL on delete)
4. `hpp_calculations.product_id` → `products.id` (CASCADE on delete)
5. `hpp_ingredients.hpp_calculation_id` → `hpp_calculations.id` (CASCADE on delete)
6. `hpp_packaging_costs.hpp_calculation_id` → `hpp_calculations.id` (CASCADE on delete)

## Migration Order

1. `0001_01_01_000000_create_users_table.php`
2. `0001_01_01_000003_create_sessions_table.php`
3. `0001_01_01_000004_add_username_to_users_table.php`
4. `2024_01_02_000001_create_branches_table.php`
5. `2024_01_02_000002_create_product_categories_table.php`
6. `2024_01_02_000003_create_products_table.php`
7. `2024_01_02_000004_create_hpp_calculations_table.php`
8. `2024_01_02_000005_create_hpp_ingredients_table.php`
9. `2024_01_02_000006_create_hpp_packaging_costs_table.php`

## Sample Data (Seeded)

### User
- Username: `admin`
- Password: `Anggrek2729`
- Email: `admin@anggrekfoods.local`

### Branch
- Anggrek Foods - Pusat (MAIN)

### Product Categories
1. Mie
2. Nasi Goreng
3. Ayam

### Products
**Mie:**
- Mie Ayam, Mie Goreng, Mie Kuah, Mie Bakso

**Nasi Goreng:**
- Nasi Goreng Original, Nasi Goreng Spesial, Nasi Goreng Seafood

**Ayam:**
- Ayam Goreng, Ayam Bakar, Ayam Geprek

## Running Migrations

```bash
# Start MySQL in XAMPP first
php artisan config:clear
php artisan migrate:fresh --seed
```

This will:
1. Drop all existing tables
2. Run all migrations in order
3. Seed sample data (user, branches, categories, products)
