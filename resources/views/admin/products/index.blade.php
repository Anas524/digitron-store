@extends('layouts.admin')

@section('title','Products')

@section('content')
@php
$csrf = csrf_token();
$categories = $categories ?? collect();
@endphp

<div
  id="adminProductsCfg"
  class="hidden"
  data-csrf="{{ csrf_token() }}"
  data-store-url="{{ route('admin.products.store') }}">
</div>

<div x-data="{ mobileRailOpen: false }" class="relative min-h-full">
  <!-- Mobile overlay for products rail -->
  <div
    x-cloak
    x-show="mobileRailOpen"
    x-transition.opacity
    class="fixed inset-0 z-30 bg-black/60 backdrop-blur-sm lg:hidden"
    @click="mobileRailOpen = false">
  </div>

  <!-- Mobile top bar -->
  <div class="lg:hidden sticky top-0 z-20 glass-panel border-b border-white/10 px-4 py-4 mb-4">
    <div class="flex items-center justify-between gap-3">
      <div class="flex items-center gap-3 min-w-0">
        <!-- Admin sidebar toggle -->
        <button
          type="button"
          class="w-11 h-11 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 transition shrink-0"
          @click.stop="mobileRailOpen = false; sidebarOpen = true"
          aria-label="Open admin sidebar">
          <i class="bi bi-list text-xl"></i>
        </button>

        <div class="min-w-0">
          <h1 class="text-2xl font-display font-bold leading-tight">Products</h1>
          <p class="text-sm text-gray-400 truncate">Inline create & edit.</p>
        </div>
      </div>

      <!-- Products rail toggle -->
      <button
        type="button"
        class="w-11 h-11 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 transition shrink-0"
        @click.stop="mobileRailOpen = true"
        aria-label="Open products list">
        <i class="bi bi-grid"></i>
      </button>
    </div>
  </div>

  <!-- Mobile products rail drawer -->
  <aside
    class="fixed inset-y-0 left-0 z-40 w-[300px] max-w-[88vw] glass-panel border-r border-white/10 h-screen flex flex-col overflow-hidden transform transition-transform duration-300 ease-in-out lg:hidden"
    :class="mobileRailOpen ? 'translate-x-0' : '-translate-x-full'">

    <div class="p-5 border-b border-white/10 relative overflow-hidden shrink-0 flex items-center justify-between gap-3 bg-white/[0.02]">
      <div class="flex items-center gap-2 min-w-0">
        <div class="text-lg font-display font-bold">Products</div>
        <span class="text-xs px-2 py-1 rounded-full bg-white/10 border border-white/10 text-gray-300">
          {{ $products->total() }}
        </span>
      </div>

      <button
        type="button"
        class="w-10 h-10 rounded-xl border border-white/10 bg-white/5 flex items-center justify-center text-gray-300 hover:text-white hover:bg-white/10 transition"
        @click.stop="mobileRailOpen = false"
        aria-label="Close products list">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>

    <div class="p-4 border-b border-white/10">
      <div class="relative">
        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
        <input
          id="prodSearchMobile"
          type="text"
          placeholder="Search product..."
          class="w-full pl-9 pr-3 py-2.5 rounded-xl bg-white/5 border border-white/10 outline-none focus:border-brand-accent text-sm">
      </div>
    </div>

    <div
      id="railListMobile"
      class="flex-1 overflow-y-auto custom-scrollbar px-4 py-4 space-y-2">
      @foreach($products as $i => $p)
      <button
        type="button"
        class="rail-item-mobile w-full text-left px-3 py-2 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10"
        data-id="{{ $p->id }}"
        data-jump="prod-{{ $p->id }}"
        data-name="{{ strtolower($p->name) }}"
        @click="mobileRailOpen = false">
        <div class="flex items-center justify-between">
          <div class="text-xs text-gray-400">
            S.NO {{ ($products->firstItem() ?? 1) + $i }}
          </div>
          <i class="bi bi-chevron-right text-gray-500"></i>
        </div>
        <div class="text-sm font-semibold text-white truncate">{{ $p->name }}</div>
        <div class="text-xs text-gray-400 truncate">{{ $p->brand ?? '—' }} • {{ $p->sku ?? '—' }}</div>
      </button>
      @endforeach
    </div>

    <div class="p-4 border-t border-white/10">
      <button
        id="addProductBtnMobile"
        type="button"
        class="w-full px-4 py-3 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition flex items-center justify-center gap-2"
        @click="mobileRailOpen = false">
        <i class="bi bi-plus-circle"></i> Add Product
      </button>
    </div>
  </aside>

  <div class="relative">
    <div class="min-h-full">
      <div class="grid grid-cols-12 gap-4 lg:gap-6 p-0 lg:p-6 min-h-0">
        {{-- LEFT RAIL DESKTOP --}}
        <aside class="col-span-12 lg:col-span-3 hidden lg:block">
          <div class="sticky top-6 h-[calc(100vh-3rem)] flex flex-col overflow-hidden">
            <div class="flex items-center gap-2 mb-3">
              <div class="text-xl font-display font-bold">Products</div>
              <span class="text-xs px-2 py-1 rounded-full bg-white/10 border border-white/10 text-gray-300">
                {{ $products->total() }}
              </span>
            </div>

            <div class="relative">
              <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
              <input id="prodSearch" type="text" placeholder="Search product..."
                class="w-full pl-9 pr-3 py-2.5 rounded-xl bg-white/5 border border-white/10 outline-none focus:border-brand-accent text-sm">
            </div>

            <div id="railList" data-left-rail data-scroll-lock class="mt-3 flex-1 overflow-y-auto custom-scrollbar pr-2 pl-2 py-2 space-y-2">
              @foreach($products as $i => $p)
              <button type="button"
                class="rail-item w-full text-left px-3 py-2 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10"
                data-id="{{ $p->id }}"
                data-jump="prod-{{ $p->id }}"
                data-name="{{ strtolower($p->name) }}">
                <div class="flex items-center justify-between">
                  <div class="text-xs text-gray-400">
                    <span data-rail-sno>S.NO {{ ($products->firstItem() ?? 1) + $i }}</span>
                  </div>
                  <i class="bi bi-chevron-right text-gray-500"></i>
                </div>
                <div class="text-sm font-semibold text-white truncate">{{ $p->name }}</div>
                <div class="text-xs text-gray-400 truncate">{{ $p->brand ?? '—' }} • {{ $p->sku ?? '—' }}</div>
              </button>
              @endforeach
            </div>

            <div class="pt-3 pb-2 lg:pb-4">
              <button id="addProductBtn"
                class="w-full px-4 py-3 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition flex items-center justify-center gap-2">
                <i class="bi bi-plus-circle"></i> Add Product
              </button>
            </div>
          </div>
        </aside>

        {{-- RIGHT CONTENT --}}
        <section class="col-span-12 lg:col-span-9 min-w-0 min-h-0">
          <div
            id="adminRightScroll"
            class="min-h-0 lg:h-full overflow-visible lg:overflow-y-auto custom-scrollbar px-0 lg:px-3 py-3 overscroll-contain"
            data-right-scroll>

            @if(session('success'))
            <div class="toast mb-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 text-emerald-200 px-4 py-3">
              {{ session('success') }}
            </div>
            @endif

            <div id="cardsWrap" class="space-y-4">
              @foreach($products as $i => $p)
              @include('admin.partials.product_card', [
              'p' => $p,
              'sno' => ($products->firstItem() ?? 1) + $i,
              'csrf' => $csrf,
              'categories' => $categories,
              ])
              @endforeach
            </div>

            <div class="mt-6">
              {{ $products->links() }}
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</div>

<template id="draftCardTpl">
  @include('admin.partials.product_card', [
  'p' => null,
  'sno' => 'NEW',
  'csrf' => $csrf,
  'categories' => $categories,
  ])
</template>
@endsection