@extends('layouts.admin')

@section('title','Products')

@section('content')
<div class="min-h-screen p-8">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-3xl font-display font-bold">Products</h1>
      <p class="text-gray-400">Create, edit, delete and manage images.</p>
    </div>

    <a href="{{ route('admin.products.create') }}"
       class="px-5 py-3 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition">
      + Add Product
    </a>
  </div>

  @if(session('success'))
    <div class="toast mb-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 text-emerald-200 px-4 py-3">
      {{ session('success') }}
    </div>
  @endif

  <div class="overflow-hidden rounded-2xl border border-white/10 bg-white/5">
    <table class="w-full text-sm">
      <thead class="bg-white/5 text-gray-300">
        <tr>
          <th class="text-left p-4">Product</th>
          <th class="text-left p-4">Price</th>
          <th class="text-left p-4">Stock</th>
          <th class="text-left p-4">Status</th>
          <th class="text-right p-4">Actions</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-white/10">
        @forelse($products as $p)
          <tr class="hover:bg-white/5">
            <td class="p-4">
              <div class="flex items-center gap-3">
                @php
                  $img = optional($p->primaryImage)->image_path;
                @endphp
                <div class="w-14 h-14 rounded-xl overflow-hidden bg-white/5 border border-white/10">
                  @if($img)
                    <img src="{{ asset('storage/'.$img) }}" class="w-full h-full object-cover" alt="">
                  @endif
                </div>
                <div>
                  <div class="font-semibold text-white">{{ $p->name }}</div>
                  <div class="text-gray-400 text-xs">{{ $p->brand ?? '—' }} • {{ $p->sku ?? '—' }}</div>
                  @if($p->badge_text)
                    <span class="inline-block mt-1 text-[11px] px-2 py-1 rounded-full bg-white/10 text-gray-200">
                      {{ $p->badge_text }}
                    </span>
                  @endif
                </div>
              </div>
            </td>

            <td class="p-4 text-white">
              AED {{ number_format($p->price, 2) }}
              @if($p->compare_at_price)
                <div class="text-xs text-gray-400 line-through">
                  AED {{ number_format($p->compare_at_price, 2) }}
                </div>
              @endif
            </td>

            <td class="p-4 text-white">{{ $p->stock_qty ?? 0 }}</td>

            <td class="p-4">
              @if($p->is_active)
                <span class="px-3 py-1 rounded-full text-xs bg-emerald-500/15 text-emerald-200 border border-emerald-500/30">Active</span>
              @else
                <span class="px-3 py-1 rounded-full text-xs bg-yellow-500/15 text-yellow-200 border border-yellow-500/30">Draft</span>
              @endif
            </td>

            <td class="p-4">
              <div class="flex justify-end gap-2">
                <a href="{{ route('admin.products.edit',$p) }}"
                   class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition">
                  Edit
                </a>

                <form method="POST" action="{{ route('admin.products.destroy',$p) }}"
                      onsubmit="return confirm('Delete this product?')">
                  @csrf
                  @method('DELETE')
                  <button class="px-4 py-2 rounded-lg bg-red-500/15 text-red-200 border border-red-500/30 hover:bg-red-500/25 transition">
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="p-6 text-center text-gray-400">No products found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ $products->links() }}
  </div>
</div>
@endsection
