@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('menu.show', $currentCategory->slug) }}"
       class="text-sm text-gray-600 hover:text-gray-800">
        ← Kembali ke Menu {{ $currentCategory->name }}
    </a>
</div>

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-6 border border-amber-100">
    <h1 class="text-2xl font-bold text-brand-dark mb-6">
        Tambah Produk — {{ $currentCategory->name }}
    </h1>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('products.store') }}" class="space-y-4">
        @csrf

        {{-- KATEGORI --}}
        <div>
            <label class="block text-sm font-medium mb-1">
                Kategori <span class="text-red-500">*</span>
            </label>
            <select name="product_category_id"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:border-brand focus:ring focus:ring-brand-softer" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        @selected(
                            old('product_category_id', $currentCategory->id) == $category->id
                        )>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- NAMA --}}
        <div>
            <label class="block text-sm font-medium mb-1">
                Nama Produk <span class="text-red-500">*</span>
            </label>
                 <input type="text" name="name"
                     value="{{ old('name') }}"
                     class="w-full border border-gray-300 rounded px-3 py-2 focus:border-brand focus:ring focus:ring-brand-softer" required>
        </div>

        {{-- SLUG --}}
        <div>
            <label class="block text-sm font-medium mb-1">Slug</label>
                 <input type="text" name="slug"
                     value="{{ old('slug') }}"
                     class="w-full border border-gray-300 rounded px-3 py-2 focus:border-brand focus:ring focus:ring-brand-softer"
                   placeholder="Biarkan kosong untuk generate otomatis">
        </div>

        {{-- DESKRIPSI --}}
        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="description" rows="4"
                      class="w-full border border-gray-300 rounded px-3 py-2 focus:border-brand focus:ring focus:ring-brand-softer">{{ old('description') }}</textarea>
        </div>

        {{-- STATUS --}}
        <div>
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', true) ? 'checked' : '' }}
                       class="mr-2">
                <span class="text-sm font-medium">Aktif</span>
            </label>
        </div>

        {{-- ACTION --}}
        <div class="flex gap-4 pt-4">
            <button type="submit"
                    class="px-6 py-2 bg-brand text-white rounded-lg hover:bg-brand-dark">
                Simpan
            </button>

            <a href="{{ route('menu.show', $currentCategory->slug) }}"
               class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
