@extends('layouts.app')

@section('page','shop')

@section('fullwidth')
{{-- Premium Hero with Video Background --}}
<section class="relative h-[60vh] min-h-[500px] overflow-hidden flex items-center justify-center">
    <!-- Video Background -->
    <div class="absolute inset-0 w-full h-full z-0">
        <video autoplay muted loop playsinline preload="auto" class="object-cover w-full h-full opacity-40">
            <source src="{{ asset('videos/shop-hero.mp4') }}?v={{ time() }}" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#070A12]/60 to-[#070A12]"></div>
    </div>

    <!-- Animated Grid Overlay -->
    <div class="absolute inset-0 bg-grid-pattern opacity-[0.05] pointer-events-none"></div>

    <!-- Content -->
    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto parallax-hero">
        <div class="inline-block mb-4 px-4 py-1.5 rounded-full border border-brand-accent/30 bg-brand-accent/10 text-brand-accent text-xs font-bold tracking-[0.2em] uppercase animate-pulse">
            Premium Components
        </div>
        <h1 class="text-5xl md:text-7xl font-display font-black mb-6 tracking-tight">
            SHOP
            <span class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-500">
                HARDWARE
            </span>
        </h1>

        <p class="text-lg md:text-xl text-gray-400 max-w-2xl mx-auto font-light">
            Curated selection of new, used, and refurbished components for serious builders in UAE.
        </p>

        <!-- Quick Stats -->
        <div class="mt-10 flex justify-center gap-8 md:gap-16">
            <div class="text-center">
                <div class="text-3xl font-display font-bold text-white counter" data-target="5000">0</div>
                <div class="text-xs text-gray-500 uppercase tracking-widest mt-1">Products</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-display font-bold text-white counter" data-target="24">0</div>
                <div class="text-xs text-gray-500 uppercase tracking-widest mt-1">Hour Delivery</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-display font-bold text-white counter" data-target="2">0</div>
                <div class="text-xs text-gray-500 uppercase tracking-widest mt-1">Year Warranty</div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <i class="bi bi-chevron-down text-brand-accent text-2xl"></i>
    </div>
</section>
@endsection

@section('content')

<section x-data="{ filtersOpen: false, viewMode: 'grid' }" class="py-12 -mt-20 relative z-20">
    {{-- Sticky Filter Bar --}}
    <div class="sticky top-20 z-30 mb-8">
        <div class="glass-panel rounded-2xl border border-white/10 p-4 shadow-2xl shadow-black/50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                {{-- Left: Results & Filter Toggle --}}
                <div class="flex items-center gap-4">
                    <button
                        @click="filtersOpen = !filtersOpen"
                        class="inline-flex lg:hidden items-center gap-2 rounded-xl bg-white/5 px-4 py-2.5 text-sm font-medium hover:bg-white/10 transition-colors border border-white/10">
                        <i class="bi bi-funnel"></i>
                        <span x-text="filtersOpen ? 'Hide Filters' : 'Show Filters'"></span>
                    </button>

                    <div class="hidden sm:block text-sm text-gray-400">
                        Showing <span class="text-white font-semibold">{{ $products->total() }}</span> products
                    </div>
                </div>

                {{-- Center: Category Pills --}}
                <div class="hidden md:flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 no-scrollbar">
                    <a href="{{ route('shop') }}"
                        class="px-4 py-2 rounded-full text-sm font-bold whitespace-nowrap
                                {{ $activeCategory === 'all' ? 'bg-brand-accent text-black' : 'bg-white/5 text-white hover:bg-white/10 border border-white/10' }}">
                        All
                    </a>

                    @foreach($categories as $cat)
                    <a href="{{ route('shop', ['category' => $cat->slug]) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap border border-white/10
                                    {{ $activeCategory === $cat->slug ? 'bg-brand-accent text-black font-bold' : 'bg-white/5 text-white hover:bg-white/10' }}">
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </div>

                {{-- Right: View & Sort --}}
                <div class="flex items-center gap-3">
                    {{-- View Toggle --}}
                    <div class="hidden sm:flex items-center bg-white/5 rounded-lg p-1 border border-white/10">
                        <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white/20 text-white' : 'text-gray-400'" class="p-2 rounded-md transition-colors">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </button>
                        <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-white/20 text-white' : 'text-gray-400'" class="p-2 rounded-md transition-colors">
                            <i class="bi bi-list-ul"></i>
                        </button>
                    </div>

                    <div class="relative" x-data="{
                        open:false,
                        label:'Sort: Featured',
                        items:['Sort: Featured','Price: Low to High','Price: High to Low','Newest First','Best Rated'],
                        choose(v){ this.label=v; this.open=false; }
                        }">
                        <button type="button"
                            @click="open=!open"
                            @keydown.escape.window="open=false"
                            class="min-w-[190px] rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white
                                    outline-none hover:bg-white/10 focus:border-brand-accent transition-colors flex items-center justify-between gap-3">
                            <span x-text="label"></span>
                            <i class="bi bi-chevron-down text-gray-300"></i>
                        </button>

                        <div x-show="open" x-transition.origin.top.right @click.outside="open=false"
                            class="absolute right-0 mt-2 w-full rounded-xl border border-white/10 bg-[#0b1220]/95 backdrop-blur-xl shadow-2xl z-50 overflow-hidden">
                            <template x-for="it in items" :key="it">
                                <button type="button" @click="choose(it)"
                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-200 hover:bg-white/10 hover:text-white transition-colors">
                                    <span x-text="it"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-12">
        {{-- Sidebar Filters --}}
        <aside
            class="lg:col-span-3 transition-all duration-500"
            :class="filtersOpen ? 'block' : 'hidden lg:block'">
            <div class="space-y-4">
                {{-- Active Filters --}}
                <div class="glass-panel rounded-2xl border border-white/10 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-sm font-bold text-white flex items-center gap-2">
                            <i class="bi bi-sliders"></i> Filters
                        </div>
                        <a href="{{ route('shop') }}" class="text-xs text-brand-accent hover:text-white transition-colors">Clear All</a>
                    </div>

                    {{-- Active Tags --}}
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-brand-accent/20 text-brand-accent text-xs border border-brand-accent/30">
                            New <i class="bi bi-x-circle-fill cursor-pointer hover:text-white"></i>
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-brand-accent/20 text-brand-accent text-xs border border-brand-accent/30">
                            NVIDIA <i class="bi bi-x-circle-fill cursor-pointer hover:text-white"></i>
                        </span>
                    </div>
                </div>

                {{-- Condition Filter --}}
                <div class="glass-panel rounded-2xl border border-white/10 p-5 filter-section">
                    <button class="flex items-center justify-between w-full text-sm font-bold text-white mb-4 group">
                        <span>Condition</span>
                        <i class="bi bi-chevron-down transition-transform group-hover:text-brand-accent"></i>
                    </button>
                    <div class="space-y-3">
                        @foreach(['New' => '128', 'Used' => '45', 'Refurbished' => '23'] as $condition => $count)
                        <label class="flex items-center justify-between group cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <input type="checkbox" class="peer sr-only" {{ $condition === 'New' ? 'checked' : '' }}>
                                    <div class="w-5 h-5 rounded border-2 border-white/20 peer-checked:bg-brand-accent peer-checked:border-brand-accent transition-all"></div>
                                    <i class="bi bi-check-lg absolute top-0.5 left-0.5 text-black text-xs opacity-0 peer-checked:opacity-100"></i>
                                </div>
                                <span class="text-sm text-gray-300 group-hover:text-white transition-colors">{{ $condition }}</span>
                            </div>
                            <span class="text-xs text-gray-500">{{ $count }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Brand Filter --}}
                <div class="glass-panel rounded-2xl border border-white/10 p-5 filter-section">
                    <button class="flex items-center justify-between w-full text-sm font-bold text-white mb-4 group">
                        <span>Brand</span>
                        <i class="bi bi-chevron-down transition-transform group-hover:text-brand-accent"></i>
                    </button>
                    <div class="space-y-3 max-h-48 overflow-y-auto custom-scrollbar">
                        @foreach(['NVIDIA' => '45', 'AMD' => '38', 'Intel' => '32', 'ASUS' => '28', 'MSI' => '24', 'Gigabyte' => '20', 'Corsair' => '18', 'Samsung' => '15'] as $brand => $count)
                        <label class="flex items-center justify-between group cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <input type="checkbox" class="peer sr-only">
                                    <div class="w-5 h-5 rounded border-2 border-white/20 peer-checked:bg-brand-accent peer-checked:border-brand-accent transition-all"></div>
                                    <i class="bi bi-check-lg absolute top-0.5 left-0.5 text-black text-xs opacity-0 peer-checked:opacity-100"></i>
                                </div>
                                <span class="text-sm text-gray-300 group-hover:text-white transition-colors">{{ $brand }}</span>
                            </div>
                            <span class="text-xs text-gray-500">{{ $count }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Price Range --}}
                <div class="glass-panel rounded-2xl border border-white/10 p-5 filter-section">
                    <button class="flex items-center justify-between w-full text-sm font-bold text-white mb-4 group">
                        <span>Price Range (AED)</span>
                        <i class="bi bi-chevron-down transition-transform group-hover:text-brand-accent"></i>
                    </button>
                    <div class="space-y-4">
                        <div class="relative h-2 bg-white/10 rounded-full">
                            <div class="absolute h-full bg-gradient-to-r from-brand-accent to-brand-secondary rounded-full" style="left: 20%; right: 30%;"></div>
                            <div class="absolute w-4 h-4 bg-white rounded-full shadow-lg cursor-pointer hover:scale-125 transition-transform" style="left: 20%; top: -4px;"></div>
                            <div class="absolute w-4 h-4 bg-white rounded-full shadow-lg cursor-pointer hover:scale-125 transition-transform" style="right: 30%; top: -4px;"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="relative flex-1">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">AED</span>
                                <input type="number" value="200" class="w-full rounded-xl border border-white/10 bg-white/5 pl-10 pr-3 py-2.5 text-sm outline-none focus:border-brand-accent transition-colors">
                            </div>
                            <span class="text-gray-500">-</span>
                            <div class="relative flex-1">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">AED</span>
                                <input type="number" value="5000" class="w-full rounded-xl border border-white/10 bg-white/5 pl-10 pr-3 py-2.5 text-sm outline-none focus:border-brand-accent transition-colors">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Rating Filter --}}
                <div class="glass-panel rounded-2xl border border-white/10 p-5 filter-section">
                    <button class="flex items-center justify-between w-full text-sm font-bold text-white mb-4 group">
                        <span>Rating</span>
                        <i class="bi bi-chevron-down transition-transform group-hover:text-brand-accent"></i>
                    </button>
                    <div class="space-y-2">
                        @foreach([5, 4, 3] as $stars)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="rating" class="sr-only peer" {{ $stars === 4 ? 'checked' : '' }}>
                            <div class="flex-1 flex items-center gap-1">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="bi bi-star-fill text-xs {{ $i < $stars ? 'text-yellow-400' : 'text-gray-600' }}"></i>
                                    @endfor
                                    <span class="text-xs text-gray-400 ml-2">& Up</span>
                            </div>
                            <div class="w-4 h-4 rounded-full border-2 border-white/20 peer-checked:border-brand-accent peer-checked:bg-brand-accent transition-all relative">
                                <div class="absolute inset-1 rounded-full bg-black opacity-0 peer-checked:opacity-100"></div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>

        {{-- Product Grid --}}
        <div class="lg:col-span-9">
            {{-- Grid View --}}
            <div
                class="grid gap-5 transition-all duration-500"
                :class="viewMode === 'grid' ? 'grid-cols-2 sm:grid-cols-3 xl:grid-cols-4' : 'grid-cols-1'">
                @foreach($products as $p)
                @php
                // image (from accessor)
                $img = $p->primary_image_url;

                // badge (from DB: badge_text)
                $badge = strtolower(str_replace(' ', '', trim($p->badge_text ?? ''))); // hot|sale|bestseller|used

                // tag (from DB: condition)
                $cond = strtolower(trim($p->condition ?? 'new')); // new|used|refurbished
                $tagMap = [
                'new' => 'New',
                'used' => 'Used',
                'refurbished' => 'Refurbished',
                ];
                $tag = $tagMap[$cond] ?? ucfirst($cond);
                @endphp

                <a href="{{ route('product.show', ['slug' => $p->slug]) }}"
                    class="product-card group relative rounded-2xl border border-white/10 bg-white/[0.03] overflow-hidden
                            hover:border-brand-accent/50 transition-all duration-500 hover:shadow-2xl hover:shadow-brand-accent/10
                            hover:-translate-y-2">

                    <div class="relative aspect-square min-h-[220px] overflow-hidden rounded-t-2xl bg-gradient-to-br from-white/5 to-transparent flex items-center justify-center">

                        @if($badge)
                        <div class="absolute top-3 left-3 z-10">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                {{ $badge === 'hot' ? 'bg-red-500/80 text-white' : '' }}
                                {{ $badge === 'sale' ? 'bg-brand-accent text-black' : '' }}
                                {{ $badge === 'bestseller' ? 'bg-purple-500/80 text-white' : '' }}
                                {{ $badge === 'used' ? 'bg-gray-500/80 text-white' : '' }}
                                backdrop-blur-sm border border-white/10">
                                {{ $badge }}
                            </span>
                        </div>
                        @endif

                        <img src="{{ $img }}"
                            alt="{{ $p->name }}"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" />

                        <div class="absolute inset-x-4 bottom-4 z-30 opacity-0 translate-y-8
                    group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out">
                            <button type="button" class="w-full py-3 rounded-xl bg-brand-accent text-black font-bold text-sm">
                                <i class="bi bi-cart-plus"></i> Quick Add
                            </button>
                        </div>
                    </div>

                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-brand-accent uppercase tracking-wider">{{ $tag }}</span>
                            <div class="flex items-center gap-1">
                                <i class="bi bi-star-fill text-yellow-400 text-xs"></i>
                                <span class="text-xs text-gray-400">
                                    {{ number_format($p->rating ?? 4.8, 1) }}
                                    @if($p->rating_count) ({{ $p->rating_count }}) @endif
                                </span>
                            </div>
                        </div>

                        <h3 class="text-sm font-bold text-white group-hover:text-brand-accent transition-colors line-clamp-2 mb-3">
                            {{ $p->name }}
                        </h3>

                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-white">AED {{ number_format($p->price, 0) }}</span>

                            @if($p->compare_at_price && $p->compare_at_price > $p->price)
                            <span class="text-xs text-gray-500 line-through ml-2">
                                AED {{ number_format($p->compare_at_price, 0) }}
                            </span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Load More --}}
            <div class="mt-12 text-center">
                @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}"
                    class="group relative inline-flex items-center justify-center px-8 py-4 rounded-full border border-white/20 text-white font-medium hover:border-brand-accent transition-all duration-300 overflow-hidden">
                    <span class="relative z-10 flex items-center gap-2 text-white transition-colors">
                        Load More Products <i class="bi bi-arrow-down animate-bounce"></i>
                    </span>
                    <div class="absolute inset-0 bg-brand-accent translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                </a>
                @else
                <div class="text-gray-500 text-sm">No more products</div>
                @endif
            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</section>

{{-- Recently Viewed Section --}}
<section class="py-16 border-t border-white/10">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-display font-bold">Recently Viewed</h2>
        <a href="#" class="text-sm text-brand-accent hover:text-white transition-colors">View All</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @php
        $recent = $products->take(4); // takes first 4 from current page
        @endphp

        @foreach($recent as $p)
        <a href="{{ route('product.show', ['slug' => $p->slug]) }}"
            class="group rounded-xl border border-white/5 bg-white/[0.02] p-3 hover:border-white/20 transition-colors">
            <div class="aspect-square rounded-lg bg-white/5 mb-3 overflow-hidden">
                <img src="{{ $p->primary_image_url }}"
                    class="w-full h-full object-cover opacity-70 group-hover:opacity-100 transition-opacity">
            </div>
            <div class="text-xs text-gray-400 mb-1">{{ ucfirst($p->condition ?? 'New') }}</div>
            <div class="text-sm font-medium text-white truncate">{{ $p->name }}</div>
            <div class="text-sm font-bold text-brand-accent mt-1">AED {{ number_format($p->price, 0) }}</div>
        </a>
        @endforeach
    </div>
</section>
@endsection