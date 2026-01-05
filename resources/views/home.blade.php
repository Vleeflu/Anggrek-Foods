@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-amber-50">
    <!-- Hero Section -->
    <div class="mb-16 rounded-3xl p-8 md:p-16 text-white shadow-2xl" style="background: linear-gradient(90deg, #F59E0B 0%, #D97706 100%);">
        <h1 class="text-5xl md:text-6xl font-bold mb-4">HPP Kalkulator Anggrek Foods</h1>
        <p class="text-lg md:text-xl text-white/90 max-w-2xl leading-relaxed">
            Hitung harga pokok penjualan produk Anda dengan mudah, akurat, dan profesional. Kelola inventori, tracking biaya, dan maksimalkan profit Anda sekarang.
        </p>
    </div>

    <!-- Categories Grid -->
    <div>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-4xl font-bold text-gray-900">Kategori Menu</h2>
            @auth
            <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand text-white font-semibold shadow-sm transition hover:bg-brand-dark">
                + Tambah Kategori
            </a>
            @endauth
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($categories as $category)
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden hover:-translate-y-1 border border-amber-100">
                    <!-- Card Header with Icon -->
                    <div class="p-6 text-white" style="background: linear-gradient(90deg, #F59E0B 0%, #D97706 100%);">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-white mb-2">{{ $category->name }}</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        <p class="text-gray-600 text-sm mb-6">
                            {{ \Illuminate\Support\Str::limit($category->description ?? 'Pilihan menu berkualitas dengan cita rasa istimewa', 100) }}
                        </p>

                        <!-- Stats -->
                        <div class="grid grid-cols-1 gap-4 mb-6 pb-6 border-b">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-brand-dark">{{ $category->products_count ?? 0 }}</div>
                                <div class="text-xs text-gray-600">Produk</div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <a href="{{ route('menu.show', $category) }}" class="flex-1 py-3 px-4 bg-brand hover:bg-brand-dark text-white rounded-lg font-semibold transition text-center">
                                Lihat Menu →
                            </a>
                            @auth
                                <a href="{{ route('categories.edit', $category) }}" class="py-3 px-4 border-2 border-brand text-brand-dark hover:bg-brand-softer rounded-lg font-semibold transition">
                                    ✎
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="py-3 px-4 border-2 border-red-700 text-red-700 hover:bg-red-50 rounded-lg font-semibold transition flex items-center justify-center" aria-label="Hapus kategori">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 9l.01 6M15 9l-.01 6M4 7h16M10 4h4a1 1 0 011 1v2H9V5a1 1 0 011-1zM6 7l1 12a1 1 0 001 .9h8a1 1 0 001-.9l1-12" />
                                        </svg>
                                    </button>
                                </form>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-600 text-lg mb-4">Belum ada kategori.</p>
                    @auth
                    <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand hover:bg-brand-dark text-white font-semibold shadow-sm transition">
                        + Tambah Kategori
                    </a>
                    @endauth
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
