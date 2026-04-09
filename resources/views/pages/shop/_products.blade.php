{{-- Product Grid --}}
<div class="lg:col-span-9" id="shopResultsWrap">
    {{-- Grid View --}}
    <div id="shopProducts"
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

        <div
            class="product-card group relative rounded-2xl border border-white/10 bg-white/[0.03] overflow-hidden
                    hover:border-brand-accent/50 transition-all duration-500 hover:shadow-2xl hover:shadow-brand-accent/10"
            :class="viewMode === 'list'
                ? 'flex flex-row items-stretch min-h-[220px]'
                : 'hover:-translate-y-2 block'">

            <div
                class="relative overflow-hidden bg-gradient-to-br from-white/5 to-transparent flex items-center justify-center"
                :class="viewMode === 'list'
                    ? 'w-[220px] md:w-[260px] shrink-0 min-h-[220px] rounded-l-2xl'
                    : 'aspect-square min-h-[220px] rounded-t-2xl'">
                <a href="{{ route('product.show', ['slug' => $p->slug]) }}"
                    class="absolute inset-0 z-10"
                    aria-label="{{ $p->name }}"></a>

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

                {{-- Wishlist --}}
                @php
                $inWish = auth()->check()
                ? auth()->user()->wishlistProducts()->where('products.id', $p->id)->exists()
                : in_array((int)$p->id, array_map('intval', (array) session('wishlist_ids', [])), true);
                @endphp

                <button
                    type="button"
                    class="absolute top-3 right-3 z-30 w-10 h-10 flex items-center justify-center
                            transition-transform hover:scale-110
                            {{ $inWish ? 'text-yellow-400' : 'text-gray-300 hover:text-yellow-400' }}
                            js-wish-toggle"
                    data-url="{{ route('wishlist.toggle', ['product' => $p->id]) }}"
                    data-in="{{ $inWish ? 1 : 0 }}"
                    aria-label="Toggle wishlist">
                    <i class="bi {{ $inWish ? 'bi-heart-fill' : 'bi-heart' }} text-lg"></i>
                </button>

                <img src="{{ $img }}"
                    alt="{{ $p->name }}"
                    class="h-full w-full transition-transform duration-700 group-hover:scale-110"
                    :class="viewMode === 'list' ? 'object-contain p-4' : 'object-cover'" />

                <div class="quick-add-wrap absolute inset-x-4 bottom-4 z-30 opacity-0 translate-y-8
                    group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out">
                    <button type="button"
                        class="relative z-30 w-full py-3 rounded-xl bg-brand-accent text-black font-bold text-sm js-cart-add"
                        data-url="{{ route('cart.add', ['product' => $p->id]) }}"
                        data-qty="1">
                        <i class="bi bi-cart-plus"></i> Quick Add
                    </button>
                </div>
            </div>

            <div
                class="flex flex-col"
                :class="viewMode === 'list' ? 'flex-1 p-5 justify-between' : 'p-4'">
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

                <h3
                    class="font-bold text-white group-hover:text-brand-accent transition-colors line-clamp-2 mb-3"
                    :class="viewMode === 'list' ? 'text-lg' : 'text-sm'">
                    <a href="{{ route('product.show', ['slug' => $p->slug]) }}" class="relative z-10">
                        {{ $p->name }}
                    </a>
                </h3>

                <div class="flex items-center justify-between mt-auto">
                    <span class="text-lg font-bold text-white">AED {{ number_format($p->price, 0) }}</span>

                    @if($p->compare_at_price && $p->compare_at_price > $p->price)
                    <span class="text-xs text-gray-500 line-through ml-2">
                        AED {{ number_format($p->compare_at_price, 0) }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
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
        <!-- <div class="text-gray-500 text-sm">No more products</div> -->
        @endif
    </div>

    {{-- Pagination --}}
    <div class="mt-10">
        {{ $products->links() }}
    </div>
</div>