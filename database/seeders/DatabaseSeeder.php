<?php

namespace Database\Seeders;

use App\Models\User;
// ...existing code...
use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat admin user
        User::query()->updateOrCreate(
            ['email' => 'admin@anggrekfoods.local'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => 'Anggrek2729',
            ]
        );

        // Buat kategori produk
        $categories = [
            ['name' => 'Mie', 'slug' => 'mie', 'sort_order' => 1, 'description' => 'Berbagai pilihan mie berkualitas tinggi dengan cita rasa autentik. Dari mie ayam tradisional hingga mie goreng istimewa, semua dibuat dengan bahan pilihan terbaik.'],
            ['name' => 'Nasi Goreng', 'slug' => 'nasi-goreng', 'sort_order' => 2, 'description' => 'Nasi goreng lezat dengan cita rasa yang menggugah selera. Pilihan mulai dari original, spesial, hingga seafood dengan bumbu rahasia yang nikmat.'],
            ['name' => 'Ayam', 'slug' => 'ayam', 'sort_order' => 3, 'description' => 'Ayam berkualitas premium yang dimasak dengan berbagai teknik memasak. Dari ayam goreng crispy hingga ayam bakar bumbunya yang sempurna.'],
        ];

        foreach ($categories as $catData) {
            ProductCategory::query()->updateOrCreate(
                ['slug' => $catData['slug']],
                $catData
            );
        }

        // Buat produk untuk kategori Mie
        $mieCategory = ProductCategory::where('slug', 'mie')->first();
        $mieProducts = [
            ['name' => 'Mie Ayam', 'slug' => 'mie-ayam', 'description' => 'Mie lembut dengan kuah kaldu ayam yang gurih dan kaya rasa, disajikan dengan potongan ayam segar dan sayuran pilihan.'],
            ['name' => 'Mie Goreng', 'slug' => 'mie-goreng', 'description' => 'Mie yang digoreng dengan bumbu spesial yang nikmat, memberikan cita rasa yang autentik dan memuaskan.'],
            ['name' => 'Mie Kuah', 'slug' => 'mie-kuah', 'description' => 'Mie berkuah dengan broth premium yang kaya akan cita rasa umami, sempurna untuk dimakan kapan saja.'],
            ['name' => 'Mie Bakso', 'slug' => 'mie-bakso', 'description' => 'Mie halus dalam kuah kaldu ayam dengan bakso daging berkualitas, pelengkap sayuran segar dan telur puyuh.'],
        ];

        foreach ($mieProducts as $product) {
            Product::query()->updateOrCreate(
                ['slug' => $product['slug']],
                array_merge($product, ['product_category_id' => $mieCategory->id])
            );
        }

        // Buat produk untuk kategori Nasi Goreng
        $nasiCategory = ProductCategory::where('slug', 'nasi-goreng')->first();
        $nasiProducts = [
            ['name' => 'Nasi Goreng Original', 'slug' => 'nasi-goreng-original', 'description' => 'Nasi goreng tradisional dengan bumbu khas yang telah menjadi favorit pelanggan kami selama bertahun-tahun.'],
            ['name' => 'Nasi Goreng Spesial', 'slug' => 'nasi-goreng-spesial', 'description' => 'Nasi goreng istimewa dengan tambahan protein pilihan dan sayuran berkualitas untuk rasa yang lebih kaya.'],
            ['name' => 'Nasi Goreng Seafood', 'slug' => 'nasi-goreng-seafood', 'description' => 'Nasi goreng premium dengan kombinasi seafood segar seperti udang, cumi, dan ikan, memberikan cita rasa laut yang autentik.'],
        ];

        foreach ($nasiProducts as $product) {
            Product::query()->updateOrCreate(
                ['slug' => $product['slug']],
                array_merge($product, ['product_category_id' => $nasiCategory->id])
            );
        }

        // Buat produk untuk kategori Ayam
        $ayamCategory = ProductCategory::where('slug', 'ayam')->first();
        $ayamProducts = [
            ['name' => 'Ayam Goreng', 'slug' => 'ayam-goreng', 'description' => 'Ayam goreng dengan kulit yang renyah dan daging yang empuk, dimarinasi dengan bumbu rahasia kami.'],
            ['name' => 'Ayam Bakar', 'slug' => 'ayam-bakar', 'description' => 'Ayam bakar dengan kulit yang menggosong sempurna dan daging yang lembut, disajikan dengan sambal pedas.'],
            ['name' => 'Ayam Geprek', 'slug' => 'ayam-geprek', 'description' => 'Ayam geprek dengan tekstur unik yang dipadukan dengan sambal matah yang pedas dan segar.'],
        ];

        foreach ($ayamProducts as $product) {
            Product::query()->updateOrCreate(
                ['slug' => $product['slug']],
                array_merge($product, ['product_category_id' => $ayamCategory->id])
            );
        }
    }
}

