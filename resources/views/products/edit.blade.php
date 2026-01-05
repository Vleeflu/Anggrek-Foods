@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('menu.show', $product->category->slug) }}" class="text-sm text-gray-600 hover:text-gray-800">← Kembali ke Menu</a>
</div>

<div class="bg-white rounded-xl shadow p-6 max-w-2xl mx-auto border border-amber-100">
    <h1 class="text-2xl font-bold text-brand-dark mb-6">Edit Produk: {{ $product->name }}</h1>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Kategori <span class="text-red-500">*</span></label>
            <select name="product_category_id" class="w-full border border-gray-300 rounded px-3 py-2 focus:border-brand focus:ring focus:ring-brand-softer" required>
                <option value="">- Pilih Kategori -</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('product_category_id', $product->product_category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Nama Produk <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:border-brand focus:ring focus:ring-brand-softer" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:border-brand focus:ring focus:ring-brand-softer">
            <p class="text-xs text-gray-500 mt-1">URL-friendly identifier.</p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full border border-gray-300 rounded px-3 py-2 focus:border-brand focus:ring focus:ring-brand-softer">{{ old('description', $product->description) }}</textarea>
        </div>

        <div>
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="mr-2">
                <span class="text-sm font-medium">Aktif</span>
            </label>
        </div>

        <div class="flex gap-4 pt-4">
            <button type="submit" class="px-6 py-2 bg-brand text-white rounded-lg hover:bg-brand-dark">Update</button>
            <a href="{{ route('menu.show', $product->category->slug) }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Batal</a>
        </div>
    </form>
</div>
@endsection
