@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="mb-8 rounded-2xl shadow-lg p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-white" style="background: linear-gradient(90deg, #F59E0B 0%, #D97706 100%);">
        <div>
            <p class="text-sm text-white/80">HPP Kalkulator UMKM</p>
            <h2 class="text-3xl md:text-4xl font-bold text-white">Anggrek Foods — Menu & Kategori</h2>
            <p class="mt-2 text-white/85 max-w-2xl">
                Pilih kategori untuk melihat produk dan mulai hitung HPP dengan cepat.
            </p>
        </div>

        @auth
        <a href="{{ route('categories.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-white text-amber-900 font-semibold px-4 py-2 shadow-sm hover:bg-amber-50 transition border border-amber-200">
            + Tambah Kategori
        </a>
        @endauth
    </div>

    <!-- CARD KATEGORI -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $category)
            <div class="bg-white rounded-2xl shadow hover:shadow-xl transition p-6 flex flex-col border border-amber-100">

                <!-- TITLE -->
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-11 h-11 rounded-full bg-brand-softer text-brand-dark flex items-center justify-center font-bold">
                        {{ strtoupper(substr($category->name,0,1)) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ $category->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $category->products_count ?? 0 }} produk</p>
                    </div>
                </div>

                <p class="text-sm text-gray-600 mb-5">
                    {{ $category->description ?? 'Klik untuk melihat menu rinci dan menghitung HPP.' }}
                </p>

                <div class="mt-auto flex flex-col gap-3">
                    <a href="{{ route('menu.show', $category->slug) }}"
                       class="inline-flex items-center justify-center rounded-lg bg-brand px-4 py-2 text-white font-semibold hover:bg-brand-dark transition">
                        Lihat Menu {{ $category->name }}
                    </a>

                    @auth
                    <div class="flex justify-end gap-3 text-sm">
                        <a href="{{ route('categories.edit', $category) }}" class="px-3 py-2 rounded-lg border border-brand text-brand-dark hover:bg-brand-softer font-semibold transition">Edit</a>
                        <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
@endsection
