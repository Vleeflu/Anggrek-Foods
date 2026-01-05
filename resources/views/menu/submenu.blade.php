@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-gray-800">← Kembali ke Home</a>
        @auth
        <a href="{{ route('products.create.byCategory', ['category' => $category->slug]) }}" class="px-4 py-2 rounded-lg bg-brand text-white font-semibold hover:bg-brand-dark shadow-sm transition">+ Tambah Produk</a>
        @endauth
    </div>

    <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-sm text-gray-500">Kategori</p>
                <h2 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h2>
                <p class="text-sm text-gray-600">{{ $category->description ?? 'Daftar produk dalam kategori ini.' }}</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-brand-softer text-brand-dark text-xs font-semibold">{{ $products->count() }} produk</span>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($products as $product)
                <div class="rounded-xl border border-amber-100 p-4 flex flex-col gap-3 hover:shadow-md transition h-full bg-white">
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 text-lg">{{ $product->name }}</div>
                        <div class="text-sm text-gray-600">{{ $product->description ?? 'Klik untuk kalkulator HPP' }}</div>
                    </div>
                    <div class="flex flex-col gap-2 mt-2">
                        <a href="{{ route('hpp.form', [$category->slug, $product->slug]) }}" class="inline-flex items-center justify-center rounded-lg bg-brand px-4 py-2 text-white font-semibold hover:bg-brand-dark transition">Buka HPP</a>
                        @auth
                        <div class="flex items-center gap-3 mt-2 text-sm">
                            <a href="{{ route('products.edit', $product) }}" class="px-3 py-2 rounded-lg border border-brand text-brand-dark hover:bg-brand-softer font-semibold transition">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-2 rounded-lg border border-red-600 text-red-700 hover:bg-red-50 font-semibold transition">Hapus</button>
                            </form>
                        </div>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
