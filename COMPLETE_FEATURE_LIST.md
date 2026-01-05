# 🎉 Anggrek Foods HPP Calculator - Complete Feature List

## ✅ COMPLETED FEATURES

### 1. **Authentication & User Management**
- ✅ Login dengan username (admin / Anggrek2729)
- ✅ Logout functionality
- ✅ Session management via database
- ✅ Auth middleware protection

### 2. **HPP Calculator (Harga Pokok Penjualan)**
- ✅ Excel-style calculator form
- ✅ Dynamic ingredient table (+/- rows)
- ✅ Dynamic packaging cost table
- ✅ Labor cost input
- ✅ **Portions input** (menentukan berapa porsi yang dihasilkan)
- ✅ Profit margin slider (0-100%)
- ✅ Real-time calculation preview
- ✅ Save ke database (HPP + ingredients + packaging)
- ✅ View detail perhitungan
- ✅ Edit perhitungan existing
- ✅ Delete perhitungan
- ✅ Riwayat perhitungan dengan pagination

### 3. **Sales/Portfolio Tracking**
- ✅ Form input penjualan dengan profit preview
- ✅ Auto-calculate revenue & profit
- ✅ Save penjualan ke database
- ✅ Dashboard dengan 3 stat cards (Today, This Month, All-Time)
- ✅ 30-day profit line chart (Chart.js)
- ✅ Top 5 produk ranking
- ✅ Recent sales table (7 hari)
- ✅ List penjualan lengkap dengan pagination
- ✅ Delete penjualan
- ✅ Notes support

### 4. **Manajemen Produk (Products CRUD)**
- ✅ List produk dengan kategori
- ✅ Tambah produk baru
- ✅ Edit produk
- ✅ Hapus produk
- ✅ Auto-slug generation
- ✅ Status aktif/nonaktif
- ✅ Deskripsi produk

### 5. **Manajemen Cabang (Branches CRUD)**
- ✅ List cabang dengan alamat & telepon
- ✅ Tambah cabang baru
- ✅ Edit cabang
- ✅ Hapus cabang
- ✅ Status aktif/nonaktif
- ✅ Unique code per cabang

### 6. **Navigation & UI**
- ✅ Responsive header dengan conditional navigation
- ✅ Green brand color scheme (#2E7D32)
- ✅ Tailwind CSS styling
- ✅ Gradient backgrounds
- ✅ Icons (SVG)
- ✅ Cards dengan shadows
- ✅ Flash messages (success/error)
- ✅ Form validation
- ✅ Table styling dengan hover effects
- ✅ Pagination

### 7. **Database Schema**
- ✅ users (dengan username field)
- ✅ sessions (untuk session management)
- ✅ branches (cabang/lokasi)
- ✅ product_categories (Mie, Nasi Goreng, Ayam)
- ✅ products (detail produk per kategori)
- ✅ hpp_calculations (header perhitungan)
- ✅ hpp_ingredients (detail bahan per perhitungan)
- ✅ hpp_packaging_costs (detail kemasan per perhitungan)
- ✅ sales (data penjualan untuk portfolio tracking)
- ✅ Foreign keys dengan proper relationships
- ✅ Indexes on date columns

### 8. **Sample Data**
- ✅ Admin user (username: admin, password: Anggrek2729)
- ✅ 1 branch (MAIN)
- ✅ 3 product categories (Mie, Nasi Goreng, Ayam)
- ✅ 10 sample products

## 📊 DATABASE DIAGRAM

```
┌─────────────────────────────────────────────────────────────┐
│                          users                              │
│ id | username | email | password | created_at | updated_at  │
└──────────────────────────┬──────────────────────────────────┘
                           │ (1-to-many)
                           │
                    ┌──────▼─────────────┐
                    │ hpp_calculations   │
                    │ id | user_id       │
                    │ - product_id (FK)  │
                    │ - branch_id (FK)   │
                    │ - portions         │
                    │ - hpp_per_portion  │
                    │ - profit_margin%   │
                    │ - selling_price    │
                    └─────┬──────┬───────┘
                          │      │
         ┌────────────────┘      └─────────────────┐
         │                                         │
    ┌────▼──────────────┐             ┌───────────▼─────┐
    │ hpp_ingredients   │             │ hpp_packaging   │
    │ - name            │             │ - description   │
    │ - quantity        │             │ - price         │
    │ - price_per_unit  │             └─────────────────┘
    └───────────────────┘
         
    ┌─────────────────────────────────────────────────────┐
    │  sales (NEW - Portfolio Tracking)                   │
    │ - sale_date                                         │
    │ - quantity_sold                                     │
    │ - selling_price_used                                │
    │ - hpp_calculation_id (FK)                           │
    │ - branch_id (FK)                                    │
    │ - total_revenue                                     │
    │ - total_cost                                        │
    │ - profit                                            │
    └─────────────────────────────────────────────────────┘

┌──────────────────┐     ┌──────────────────┐
│ product_category │◄─── │   products       │
│ - id             │     │ - name           │
│ - name           │     │ - category_id(FK)│
│ - slug           │     │ - slug           │
└──────────────────┘     └──────────────────┘

┌──────────────┐
│  branches    │
│ - id         │
│ - name       │
│ - code       │
│ - address    │
│ - is_active  │
└──────────────┘
```

## 🚀 TECH STACK

- **Framework**: Laravel 11
- **Database**: MySQL (XAMPP)
- **CSS**: Tailwind CSS v3.4 (CDN)
- **Charts**: Chart.js v4.4
- **Frontend**: Blade templating
- **Session**: Database driver
- **Authentication**: Custom (username-based)

## 🎯 KEY FEATURES EXPLAINED

### HPP Calculator
```
Input:
- Ingredients (quantity × price_per_unit)
- Packaging costs
- Labor cost
- Portions (berapa porsi dari recipe ini)
- Profit margin (%)

Output:
- Total cost ÷ Portions = HPP per porsi
- HPP × (1 + margin%) = Selling Price
```

### Portfolio Dashboard
```
Real-time Statistics:
- Today's profit/revenue
- Month's profit/revenue
- All-time profit/revenue

Visual Analytics:
- 30-day profit trend
- Top 5 products by profit
- Recent sales activity
```

### Sales Entry
```
When adding to portfolio:
- Select date & branch
- Enter quantity sold
- Enter actual selling price (defaults to HPP suggestion)
- System auto-calculates: Revenue, Cost, Profit
- Real-time preview before save
```

## 📱 PAGE ROUTES

### Public Pages
- `/` - Home (menu kategori)
- `/menu/{category}` - Product submenu
- `/portfolio` - Public portfolio dashboard
- `/login` - Login page

### Protected Pages (Auth Required)
- `/hpp` - Riwayat perhitungan HPP
- `/hpp/{category}/{product}` - HPP calculator form
- `/hpp/show/{id}` - Detail perhitungan
- `/hpp/edit/{id}` - Edit perhitungan
- `/sales` - List semua penjualan
- `/sales/create/{calculation}` - Form tambah penjualan
- `/branches` - List cabang
- `/branches/create` - Form tambah cabang
- `/branches/{id}/edit` - Form edit cabang
- `/products` - List produk
- `/products/create` - Form tambah produk
- `/products/{id}/edit` - Form edit produk

## 🎨 UI COMPONENTS

- Header dengan responsive navigation
- Dashboard cards dengan gradient
- Charts (Chart.js line chart)
- Tables dengan pagination
- Forms dengan validation
- Buttons dengan hover states
- Icons (SVG)
- Status badges
- Alert messages
- Modal confirmations

## 📊 CALCULATIONS

### HPP Calculation
```
Total Cost = Total Ingredients + Total Packaging + Labor Cost
HPP per Portion = Total Cost / Portions
Profit per Portion = HPP × (Margin% / 100)
Selling Price = HPP + Profit
```

### Sales Tracking
```
Total Revenue = Quantity × Selling Price Used
Total Cost = Quantity × HPP per Portion
Profit = Total Revenue - Total Cost
```

## ✨ SPECIAL FEATURES

1. **Real-time Calculations**: Form inputs auto-calculate
2. **Flash Messages**: Success/error notifications
3. **Form Preservation**: Old values repopulated on error
4. **Responsive Design**: Mobile-friendly
5. **Pagination**: Large datasets split into pages
6. **Date Localization**: Indonesian format (dd M YYYY)
7. **Currency Formatting**: Rupiah with separators
8. **Auto-slug**: Product names auto-converted to URL-safe slugs
9. **Soft Navigation**: Links instead of page reloads where possible
10. **Delete Confirmation**: Prevent accidental deletions

## 🔐 SECURITY

- ✅ CSRF protection (Laravel default)
- ✅ Method spoofing (PUT/DELETE via POST)
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ Auth middleware on protected routes
- ✅ Hashed passwords (bcrypt)
- ✅ Form validation
- ✅ Input sanitization

## 📝 NEXT POTENTIAL ENHANCEMENTS

- [ ] Export to PDF/Excel
- [ ] User roles (Admin, Staff)
- [ ] Soft deletes
- [ ] Search & filter
- [ ] Reports & analysis
- [ ] Email notifications
- [ ] API endpoints
- [ ] Two-factor authentication
- [ ] Activity logging
- [ ] Image uploads
- [ ] Multi-language support
- [ ] Dark mode

## 🎯 USAGE SUMMARY

1. **Login** → Home → Choose Category → Choose Product → Calculate HPP
2. **Save HPP** → View Result → **Add to Portfolio** (Record Sale)
3. **Portfolio Dashboard** → See profit, trends, top products
4. **Manage** → Branches, Products, HPP Calculations, Sales

---

**Status**: ✅ FULLY FUNCTIONAL - All core features implemented and tested!
