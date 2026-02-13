@extends('layouts.admin')

@section('title','Add Product')

@section('content')
<div class="min-h-screen p-8 max-w-4xl">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-display font-bold">Add Product</h1>
    <a href="{{ route('admin.products.index') }}" class="text-gray-300 hover:text-white">← Back</a>
  </div>

  @if($errors->any())
    <div class="mb-4 rounded-xl border border-red-500/30 bg-red-500/10 text-red-200 px-4 py-3">
      {{ $errors->first() }}
    </div>
  @endif

  <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data"
        class="rounded-2xl border border-white/10 bg-white/5 p-6 space-y-4">
    @csrf

    <div>
      <label class="text-sm text-gray-300">Name</label>
      <input name="name" value="{{ old('name') }}" required
             class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 outline-none focus:border-brand-accent">
    </div>

    <div class="grid md:grid-cols-3 gap-4">
      <div>
        <label class="text-sm text-gray-300">Brand</label>
        <input name="brand" value="{{ old('brand') }}"
               class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 outline-none focus:border-brand-accent">
      </div>
      <div>
        <label class="text-sm text-gray-300">SKU</label>
        <input name="sku" value="{{ old('sku') }}"
               class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 outline-none focus:border-brand-accent">
      </div>
      <div>
        <label class="text-sm text-gray-300">Condition</label>
        <select name="condition"
                class="mt-1 w-full rounded-xl bg-[#0b1220] border border-white/10 px-4 py-3 outline-none focus:border-brand-accent">
          <option value="new">New</option>
          <option value="used">Used</option>
          <option value="refurbished">Refurbished</option>
        </select>
      </div>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
      <div>
        <label class="text-sm text-gray-300">Price (AED)</label>
        <input type="number" step="0.01" name="price" value="{{ old('price') }}" required
               class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 outline-none focus:border-brand-accent">
      </div>
      <div>
        <label class="text-sm text-gray-300">Compare At (optional)</label>
        <input type="number" step="0.01" name="compare_at_price" value="{{ old('compare_at_price') }}"
               class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 outline-none focus:border-brand-accent">
      </div>
      <div>
        <label class="text-sm text-gray-300">Stock Qty</label>
        <input type="number" name="stock_qty" value="{{ old('stock_qty',0) }}"
               class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 outline-none focus:border-brand-accent">
      </div>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
      <div>
        <label class="text-sm text-gray-300">Badge Text</label>
        <input name="badge_text" value="{{ old('badge_text') }}"
               class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 outline-none focus:border-brand-accent">
      </div>
      <div>
        <label class="text-sm text-gray-300">Rating (0-5)</label>
        <input type="number" step="0.1" name="rating" value="{{ old('rating') }}"
               class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 outline-none focus:border-brand-accent">
      </div>
      <div>
        <label class="text-sm text-gray-300">Rating Count</label>
        <input type="number" name="rating_count" value="{{ old('rating_count',0) }}"
               class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 outline-none focus:border-brand-accent">
      </div>
    </div>

    <div>
      <label class="text-sm text-gray-300">Short Description</label>
      <textarea name="short_description" rows="2"
                class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 outline-none focus:border-brand-accent">{{ old('short_description') }}</textarea>
    </div>

    <div>
      <label class="text-sm text-gray-300">Description</label>
      <textarea name="description" rows="5"
                class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 outline-none focus:border-brand-accent">{{ old('description') }}</textarea>
    </div>

    <div class="flex items-center gap-3">
      <input id="is_active" type="checkbox" name="is_active" value="1" checked class="w-4 h-4">
      <label for="is_active" class="text-gray-300">Active</label>
    </div>

    <div>
      <label class="text-sm text-gray-300">Images (first image becomes primary)</label>
      <input type="file" name="images[]" multiple accept="image/*"
             class="mt-2 block w-full text-sm text-gray-300">
    </div>

    <div class="flex gap-3 pt-2">
      <button class="px-6 py-3 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition">
        Save Product
      </button>
      <a href="{{ route('admin.products.index') }}"
         class="px-6 py-3 rounded-xl border border-white/10 hover:bg-white/10 transition">
        Cancel
      </a>
    </div>
  </form>
</div>
@endsection
