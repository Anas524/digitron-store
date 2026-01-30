@extends('layouts.app')

@section('content')
@php
  // Temporary dummy products (until DB wiring)
  $products = [
    ['name'=>'RTX 4070 Super', 'price'=>'2,799', 'tag'=>'New'],
    ['name'=>'Ryzen 7 7800X3D', 'price'=>'1,699', 'tag'=>'Hot'],
    ['name'=>'DDR5 32GB 6000MHz', 'price'=>'489', 'tag'=>'New'],
    ['name'=>'NVMe SSD 1TB Gen4', 'price'=>'299', 'tag'=>'Fast'],
    ['name'=>'Used GTX 1660 Super', 'price'=>'499', 'tag'=>'Used'],
    ['name'=>'750W Gold PSU', 'price'=>'349', 'tag'=>'Stable'],
  ];
@endphp

<section x-data="{ filtersOpen: false }" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold">Shop</h1>
            <p class="text-sm text-slate-400">Browse components with clean filters and premium UI.</p>
        </div>

        <div class="flex items-center gap-2">
            <button
                @click="filtersOpen = !filtersOpen"
                class="inline-flex lg:hidden items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm hover:bg-white/10"
            >
                <span x-text="filtersOpen ? 'Hide Filters' : 'Show Filters'"></span>
            </button>

            <select class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm outline-none">
                <option>Sort: Featured</option>
                <option>Price: Low to High</option>
                <option>Price: High to Low</option>
                <option>Newest</option>
            </select>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-12">
        {{-- Filters --}}
        <aside
            class="lg:col-span-3"
            :class="filtersOpen ? 'block' : 'hidden lg:block'"
        >
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm font-semibold">Filters</div>
                    <a href="{{ route('shop') }}" class="text-xs text-slate-400 hover:text-slate-200">Reset</a>
                </div>

                <div class="mt-4 space-y-4">
                    {{-- Condition --}}
                    <div>
                        <div class="text-xs font-semibold text-slate-300">Condition</div>
                        <div class="mt-2 space-y-2 text-sm">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="h-4 w-4 rounded border-white/20 bg-white/5">
                                <span>New</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="h-4 w-4 rounded border-white/20 bg-white/5">
                                <span>Used</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="h-4 w-4 rounded border-white/20 bg-white/5">
                                <span>Refurbished</span>
                            </label>
                        </div>
                    </div>

                    {{-- Brand (dummy) --}}
                    <div>
                        <div class="text-xs font-semibold text-slate-300">Brand</div>
                        <div class="mt-2 space-y-2 text-sm">
                            @foreach(['Intel','AMD','NVIDIA','ASUS','MSI','Gigabyte'] as $b)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" class="h-4 w-4 rounded border-white/20 bg-white/5">
                                    <span>{{ $b }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price range (dummy) --}}
                    <div>
                        <div class="text-xs font-semibold text-slate-300">Price Range (AED)</div>
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <input placeholder="Min" class="w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm outline-none placeholder:text-slate-500">
                            <input placeholder="Max" class="w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm outline-none placeholder:text-slate-500">
                        </div>
                    </div>

                    <button class="w-full rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 hover:bg-slate-200">
                        Apply Filters
                    </button>
                </div>
            </div>
        </aside>

        {{-- Product Grid --}}
        <div class="lg:col-span-9">
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-4">
                @foreach($products as $p)
                    <a href="{{ route('product.show', ['slug' => \Illuminate\Support\Str::slug($p['name'])]) }}"
                       class="group rounded-2xl border border-white/10 bg-white/5 p-3 hover:bg-white/10 transition">
                        <div class="aspect-square rounded-xl bg-slate-950/40 border border-white/10 grid place-items-center text-slate-500">
                            IMG
                        </div>

                        <div class="mt-3 flex items-center justify-between">
                            <div class="text-xs font-semibold text-slate-300">{{ $p['tag'] }}</div>
                            <div class="text-xs text-slate-400">AED {{ $p['price'] }}</div>
                        </div>

                        <div class="mt-1 text-sm font-semibold group-hover:text-white line-clamp-2">
                            {{ $p['name'] }}
                        </div>

                        <div class="mt-3 rounded-xl bg-white/10 px-3 py-2 text-center text-xs hover:bg-white/15">
                            View Product
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection

<div class="h-24 sm:h-28"></div>