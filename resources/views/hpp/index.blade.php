@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div>
        <p class="text-sm text-gray-500">Kalkulasi Biaya</p>
        <h1 class="text-4xl font-bold text-gray-900">Riwayat Perhitungan HPP</h1>
        <p class="text-gray-600 mt-1">Kelola dan tinjau semua perhitungan HPP produk Anda</p>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 rounded-lg p-4 border text-sm shadow-sm" style="background: linear-gradient(90deg, #FEF3C7 0%, #FCD34D 100%); color: #78350F;">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

<!-- Filter Section -->
<div class="mb-6 bg-white rounded-xl shadow-sm p-5 border border-gray-100">
    <form method="GET" action="{{ route('hpp.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
            <input type="text" name="search" placeholder="Nama produk atau kategori..."
                   value="{{ request('search') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-brand focus:ring focus:ring-brand-softer text-sm">
        </div>

        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-brand focus:ring focus:ring-brand-softer text-sm">
        </div>

        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-brand focus:ring focus:ring-brand-softer text-sm">
        </div>

        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
            <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-brand focus:ring focus:ring-brand-softer text-sm">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                <option value="hpp_high" {{ request('sort') == 'hpp_high' ? 'selected' : '' }}>HPP Tertinggi</option>
                <option value="hpp_low" {{ request('sort') == 'hpp_low' ? 'selected' : '' }}>HPP Terendah</option>
            </select>
        </div>

        <div class="flex gap-2 items-end">
            <button type="submit" class="px-5 py-2 bg-brand text-white rounded-lg hover:bg-brand-dark font-medium text-sm transition">
                🔍 Filter
            </button>
            <a href="{{ route('hpp.index') }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium text-sm transition">
                Reset
            </a>
            <a href="{{ route('hpp.export', request()->all()) }}" class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </a>
        </div>
    </form>
</div>

<!-- Stats Summary -->
<div class="grid md:grid-cols-3 gap-4 mb-6">
    <!-- Total Perhitungan -->
    <div class="rounded-lg p-4 shadow-sm"
        style="background:#ffdd55; color:#111827;">
        <div class="text-sm font-medium opacity-80">
            Total Perhitungan
        </div>
        <div class="text-2xl font-bold mt-1">
            {{ $calculations->total() }}
        </div>
    </div>
    <!-- Rata-rata HPP -->
    <div class="rounded-lg p-4 shadow-sm"
        style="background:#C7D2FE; color:#111827;">
        <div class="text-sm font-medium opacity-80">
            Rata-rata HPP
        </div>
        <div class="text-2xl font-bold mt-1">
            Rp{{ number_format($calculations->avg('hpp_per_portion') ?? 0, 0, ',', '.') }}
        </div>
    </div>
    <!-- Harga Jual Tertinggi -->
    <div class="rounded-lg p-4 shadow-sm"
        style="background:#93C5FD; color:#111827;">
        <div class="text-sm font-medium opacity-80">
            Harga Jual Tertinggi
        </div>
        <div class="text-2xl font-bold mt-1">
            Rp{{ number_format($calculations->max('selling_price') ?? 0, 0, ',', '.') }}
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100 w-full">
    @if($calculations->count() > 0)
        <div class="w-full">
            <table class="w-full" style="table-layout: fixed;">
                <colgroup>
                    <col style="width: 110px;" />
                    <col style="width: 130px;" />
                    <col style="width: 110px;" />
                    <col style="width: 110px;" />
                    <col style="width: 70px;" />
                    <col style="width: 110px;" />
                    <col style="width: 80px;" />
                </colgroup>
                <thead class="bg-amber-100 border-b-2 border-amber-300 text-amber-900 text-xs uppercase tracking-wide font-semibold">
                    <tr>
                        <th class="px-3 py-3 text-left">Tanggal</th>
                        <th class="px-3 py-3 text-left">Produk</th>
                        <th class="px-3 py-3 text-right text-xs">HPP/Porsi</th>
                        <th class="px-3 py-3 text-right text-xs">Harga Jual</th>
                        <th class="px-3 py-3 text-center">Porsi</th>
                        <th class="px-3 py-3 text-center">User</th>
                        <th class="px-3 py-3 pr-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @foreach($calculations as $calculation)
                        <tr class="hover:bg-amber-50 transition">
                            <td class="px-3 py-3">
                                <div class="font-medium text-gray-900 text-sm">{{ $calculation->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $calculation->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="font-medium text-gray-900 text-sm truncate">{{ $calculation->product->name }}</div>
                                <div class="text-xs text-gray-500 truncate">{{ $calculation->product->category->name }}</div>
                            </td>
                            <td class="px-3 py-3 text-right">
                                <span class="inline-block px-2 py-1 rounded-lg bg-blue-50 text-blue-700 font-semibold text-xs">
                                    Rp{{ number_format($calculation->hpp_per_portion, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-right">
                                <span class="inline-block px-2 py-1 rounded-lg bg-brand-softer text-brand-dark font-semibold text-xs">
                                    Rp{{ number_format($calculation->selling_price, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-center font-medium text-sm">{{ $calculation->portions }}</td>
                            <td class="px-3 py-3 text-center text-sm text-gray-600">{{ $calculation->user->name }}</td>
                            <td class="px-3 py-3 pr-6">
                                <div class="flex gap-1 justify-center">
                                    <a href="{{ route('hpp.show', $calculation) }}"
                                       class="px-2 py-1 bg-brand text-white text-xs rounded-lg hover:bg-brand-dark transition font-medium">
                                        Lihat
                                    </a>
                                    <a href="{{ route('hpp.edit', $calculation) }}"
                                       class="px-2 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition font-medium">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('hpp.destroy', $calculation) }}"
                                          onsubmit="return confirm('Yakin hapus data ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 transition font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="px-6 py-16 text-center">
            <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-gray-600 font-medium text-lg">Belum ada data perhitungan HPP</p>
            <p class="text-gray-500 text-sm mt-1">Mulai dengan membuat perhitungan HPP baru</p>
        </div>
    @endif
</div>

<!-- Pagination -->
@if($calculations->hasPages())
    <div class="mt-6">
        {{ $calculations->links() }}
    </div>
@endif
@endsection
