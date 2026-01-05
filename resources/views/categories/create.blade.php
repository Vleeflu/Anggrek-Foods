@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg border border-gray-100">

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-gray-500">Kategori Produk</p>
            <h1 class="text-3xl font-bold text-gray-900">Tambah Kategori Baru</h1>
        </div>
        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-brand-softer text-brand-dark">Form</span>
    </div>

    <form method="POST"
          action="{{ route('categories.store') }}"
          class="space-y-5">
        @csrf

        <div>
            <label class="block mb-2 font-semibold text-gray-800">Nama Kategori</label>
                 <input type="text"
                     name="name"
                     class="w-full border border-gray-300 focus:border-brand focus:ring focus:ring-brand-softer rounded-lg px-3 py-2 text-gray-900"
                   required
                   value="{{ old('name') }}">
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block mb-2 font-semibold text-gray-800">Deskripsi</label>
            <textarea name="description"
                      class="w-full border border-gray-300 focus:border-brand focus:ring focus:ring-brand-softer rounded-lg px-3 py-2 text-gray-900"
                      rows="3">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('home') }}"
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                Batal
            </a>

                <button type="submit"
                    class="px-4 py-2 bg-brand text-white rounded-lg hover:bg-brand-dark shadow-sm transition font-semibold">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
