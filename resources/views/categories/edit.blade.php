@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-gray-500">Kategori Produk</p>
            <h1 class="text-3xl font-bold text-gray-900">Edit Kategori</h1>
        </div>
        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-brand-softer text-brand-dark">Edit</span>
    </div>

    <form method="POST"
          action="{{ route('categories.update', $category) }}"
          class="space-y-5">
        @csrf
        @method('PUT')

        <div>
                                 <label class="block mb-2 font-semibold text-gray-800">Nama Kategori</label>
            <input type="text"
                   name="name"
                                         class="w-full border border-gray-300 focus:border-brand focus:ring focus:ring-brand-softer rounded-lg px-3 py-2 text-gray-900"
                   required
                   value="{{ old('name', $category->name) }}">
        </div>

        <div>
            <label class="block mb-2 font-semibold text-gray-800">Deskripsi</label>
            <textarea name="description"
                      class="w-full border border-gray-300 focus:border-brand focus:ring focus:ring-brand-softer rounded-lg px-3 py-2 text-gray-900"
                      rows="3">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('categories.index') }}"
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                Batal
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-brand text-white rounded-lg hover:bg-brand-dark shadow-sm transition font-semibold">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
