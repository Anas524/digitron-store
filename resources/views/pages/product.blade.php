@extends('layouts.app')

@section('page','product')

@section('whatsapp_message', 'Hello Digitron Computers UAE, I am interested in ' . $product->name . ' (SKU: ' . ($product->sku ?? 'N/A') . '). Please share availability and final price.')

@section('fullwidth')

@php
// images() relation already ordered: primary first
$main = $product->images->first();

$mainImg = $main
? asset('storage/' . ltrim($main->image_path, '/'))
: asset('images/placeholder.png');

$thumbs = $product->images
->map(fn($img) => asset('storage/' . ltrim($img->image_path, '/')))
->values()
->all();
@endphp

{{-- Product Hero with Parallax Gallery --}}
<section class="relative min-h-screen pt-24 pb-12 overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-secondary/20 via-transparent to-brand-accent/10"></div>
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-brand-accent/5 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-brand-secondary/5 rounded-full blur-[100px] translate-y-1/2 -translate-x-1/2"></div>
    </div>

    <!-- Grid Pattern -->
    <div class="absolute inset-0 bg-grid-pattern opacity-[0.03] pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8 animate-fade-in">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <a href="{{ route('shop') }}" class="hover:text-white transition-colors">Shop</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <a href="#" class="hover:text-white transition-colors">
                {{ $product->category?->name ?? 'Uncategorized' }}
            </a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-white">{{ $product['name'] }}</span>
        </nav>

        <div class="grid gap-12 lg:grid-cols-2">
            {{-- Left: Interactive Gallery --}}
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="relative aspect-square rounded-3xl border border-white/10 bg-white/[0.03] overflow-hidden group" id="main-gallery">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <img src="{{ $mainImg }}" id="main-image"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-contain p-8 transition-transform duration-500 group-hover:scale-105">
                    </div>

                    <!-- Zoom Hint -->
                    <div class="absolute bottom-4 right-4 px-3 py-1.5 rounded-full bg-black/50 backdrop-blur-sm text-xs text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity">
                        <i class="bi bi-zoom-in"></i> Hover to zoom
                    </div>

                    <!-- Navigation Arrows -->
                    <button
                        type="button"
                        id="galleryPrev"
                        class="absolute left-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full bg-black/40 backdrop-blur-sm border border-white/10 flex items-center justify-center text-white hover:bg-brand-accent hover:text-black transition-all opacity-0 group-hover:opacity-100">
                        <i class="bi bi-chevron-left"></i>
                    </button>

                    <button
                        type="button"
                        id="galleryNext"
                        class="absolute right-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full bg-black/40 backdrop-blur-sm border border-white/10 flex items-center justify-center text-white hover:bg-brand-accent hover:text-black transition-all opacity-0 group-hover:opacity-100">
                        <i class="bi bi-chevron-right"></i>
                    </button>

                    <!-- 360 View Badge -->
                    <!-- <div class="absolute top-4 left-4 px-3 py-1.5 rounded-full bg-brand-accent text-black text-xs font-bold flex items-center gap-2">
                        <i class="bi bi-360"></i> 360° View
                    </div> -->
                </div>

                <!-- Thumbnails -->
                <div class="grid grid-cols-5 gap-3">
                    @forelse($thumbs as $index => $img)
                    <button type="button"
                        class="gallery-thumb aspect-square rounded-xl border-2 {{ $index === 0 ? 'border-brand-accent' : 'border-white/10' }}
              overflow-hidden hover:border-brand-accent transition-all group"
                        data-gallery-thumb
                        data-img="{{ $img }}">
                        <img src="{{ $img }}" class="w-full h-full object-cover opacity-70 group-hover:opacity-100 transition-opacity">
                    </button>
                    @empty
                    {{-- optional: show 1 placeholder thumb --}}
                    <div class="aspect-square rounded-xl border border-white/10 bg-white/5"></div>
                    @endforelse
                </div>

                {{-- Video Preview (under thumbs) --}}
                @if($product->video_url)
                @php $videoUrl = $product->video_url; @endphp

                <div class="mt-4">
                    <div class="relative rounded-2xl overflow-hidden border border-white/10 group cursor-pointer"
                        data-open-video
                        data-video-url="{{ $videoUrl }}">
                        <video muted playsinline preload="metadata"
                            class="w-full aspect-video object-contain bg-black opacity-70 group-hover:opacity-90 transition-opacity">
                            <source src="{{ $videoUrl }}">
                        </video>

                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-brand-accent/90 flex items-center justify-center text-black text-2xl
                    group-hover:scale-110 transition-transform">
                                <i class="bi bi-play-fill ml-1"></i>
                            </div>
                        </div>

                        <div class="absolute bottom-3 left-3 text-sm font-medium text-white/90">Product Video</div>
                    </div>
                </div>

                {{-- Video Modal --}}
                <div id="videoModal" class="hidden fixed inset-0 z-[9999] bg-black/90">
                    <div class="min-h-dvh w-full flex items-center justify-center p-2 sm:p-5">
                        <div
                            class="relative w-full max-w-[94vw] sm:max-w-4xl rounded-2xl bg-[#0b0b12] border border-white/10 shadow-2xl overflow-hidden"
                            data-video-backdrop>

                            {{-- header inside modal --}}
                            <div class="flex items-center justify-between px-4 py-3 border-b border-white/10">
                                <div class="text-white font-semibold">Product Video</div>

                                <button
                                    type="button"
                                    id="closeVideoModal"
                                    class="shrink-0 w-10 h-10 rounded-full bg-black/70 border border-white/15 text-white text-base flex items-center justify-center hover:bg-white hover:text-black transition"
                                    aria-label="Close video">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>

                            <div class="flex items-center justify-center p-2 sm:p-4 bg-black">
                                <video
                                    id="productVideoPlayer"
                                    controls
                                    playsinline
                                    preload="metadata"
                                    class="block w-full h-auto max-h-[70dvh] sm:max-h-[78vh] rounded-xl bg-black object-contain">
                                </video>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Right: Product Info --}}
            <div class="lg:sticky lg:top-24 lg:h-fit space-y-6">
                @php
                $inStock = (int)($product->stock_qty ?? 0) > 0;
                $rating = is_numeric($product->rating) ? (float)$product->rating : 0;
                $ratingCount = is_numeric($product->rating_count) ? (int)$product->rating_count : 0;

                // clamp rating 0..5
                $rating = max(0, min(5, $rating));

                // how many full stars to show (rounded)
                $stars = (int) round($rating);
                @endphp
                <!-- Header -->
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold border flex items-center gap-1.5
                            {{ $inStock ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $inStock ? 'bg-emerald-400 animate-pulse' : 'bg-red-400' }}"></span>
                            {{ $inStock ? 'In Stock' : 'Out of Stock' }}
                        </span>

                        @if(!empty($product->badge_text))
                        <span class="px-3 py-1 rounded-full bg-brand-accent/20 text-brand-accent text-xs font-bold border border-brand-accent/30">
                            {{ $product->badge_text }}
                        </span>
                        @endif
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-display font-bold leading-tight mb-2">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-brand-secondary">
                            {{ $product->name }}
                        </span>
                    </h1>

                    @if(!empty($product->short_description))
                    <p class="text-gray-300 mt-3 leading-relaxed">
                        {{ $product->short_description }}
                    </p>
                    @endif

                    <div class="flex items-center gap-4 text-sm">
                        <div class="flex items-center gap-1">
                            @for($i=1; $i<=5; $i++)
                                <i class="bi bi-star-fill {{ $i <= round($rating) ? 'text-yellow-400' : 'text-gray-600' }}"></i>
                                @endfor
                                <span class="text-gray-400 ml-2">
                                    {{ number_format($rating, 1) }} ({{ $ratingCount }} {{ Str::plural('review', $ratingCount) }})
                                </span>
                        </div>

                        <span class="text-gray-600">|</span>
                        <span class="text-gray-400">SKU: <span class="text-white">{{ $product->sku ?? '—' }}</span></span>
                    </div>
                </div>

                <!-- Price -->
                <div class="glass-panel rounded-2xl p-6 border border-white/10">
                    @php
                    $price = is_numeric($product->price) ? (float)$product->price : null;
                    $compare = is_numeric($product->compare_at_price) ? (float)$product->compare_at_price : null;

                    // prefer stored discount_percent if you have it, else compute from compare_at_price
                    $discount = is_numeric($product->discount_percent) ? (float)$product->discount_percent : null;
                    if (!$discount && $compare && $price && $compare > $price) {
                    $discount = round((($compare - $price) / $compare) * 100, 0);
                    }
                    @endphp
                    <div class="flex items-end gap-4 mb-4">
                        <span class="text-4xl sm:text-5xl font-display font-bold text-white">
                            AED {{ $price ? number_format($price, 2) : '—' }}
                        </span>

                        @if($compare && $compare > ($price ?? 0))
                        <span class="text-xl text-gray-500 line-through mb-2">
                            AED {{ number_format($compare, 2) }}
                        </span>
                        @endif

                        @if($discount)
                        <span class="px-3 py-1 rounded-lg bg-red-500/20 text-red-400 text-sm font-bold mb-2">
                            -{{ rtrim(rtrim(number_format($discount, 2), '0'), '.') }}%
                        </span>
                        @endif
                    </div>

                    @if(!empty($product->delivery_text))
                    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
                        <i class="bi bi-truck text-brand-accent"></i>
                        {{ $product->delivery_text }}
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="space-y-3">
                        <div class="flex flex-col gap-3 sm:gap-4">

                            <!-- Qty -->
                            <div class="flex items-center border border-white/10 rounded-2xl overflow-hidden w-full sm:w-fit">
                                <button type="button" class="flex-1 sm:flex-none px-4 py-3 hover:bg-white/5 transition-colors" data-qty-minus>
                                    <i class="bi bi-dash"></i>
                                </button>

                                <input
                                    type="number"
                                    value="1"
                                    id="qty"
                                    class="w-full sm:w-16 text-center bg-transparent border-x border-white/10 py-3 text-sm font-bold"
                                    min="1"
                                    max="10">

                                <button type="button" class="flex-1 sm:flex-none px-4 py-3 hover:bg-white/5 transition-colors" data-qty-plus>
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>

                            <!-- Cart + Wishlist -->
                            <div class="grid grid-cols-[1fr_56px] sm:flex sm:items-center gap-3 sm:gap-4 w-full">
                                <button
                                    type="button"
                                    class="w-full py-3.5 px-4 rounded-2xl bg-brand-accent text-black font-bold hover:bg-white transition-all flex items-center justify-center gap-2 group js-cart-add text-sm sm:text-base"
                                    data-url="{{ route('cart.add', ['product' => $product->id]) }}"
                                    data-qty="#qty">
                                    <i class="bi bi-cart-plus text-base sm:text-lg group-hover:scale-110 transition-transform"></i>
                                    <span>Add to Cart</span>
                                </button>

                                @php
                                $inWishlist = $inWishlist ?? false;
                                @endphp

                                <button
                                    type="button"
                                    class="h-14 w-14 rounded-2xl border flex items-center justify-center transition-all js-wish-toggle
                                            {{ $inWishlist ? 'border-brand-accent/60 bg-brand-accent/10 text-brand-accent' : 'border-white/10 text-gray-400 hover:text-brand-accent hover:border-brand-accent/50 hover:bg-brand-accent/10' }}"
                                    data-url="{{ route('wishlist.toggle', $product->id) }}"
                                    data-in="{{ $inWishlist ? 1 : 0 }}"
                                    aria-label="Add to wishlist">
                                    <i class="bi {{ $inWishlist ? 'bi-heart-fill' : 'bi-heart' }} text-xl"></i>
                                </button>
                            </div>
                        </div>

                        @php $isOutOfStock = (int) $product->stock_qty <= 0; @endphp

                            <button
                            type="button"
                            class="w-full py-3.5 rounded-2xl border border-brand-accent text-brand-accent font-bold transition-all flex items-center justify-center gap-2 js-buy-now text-sm sm:text-base {{ $isOutOfStock ? 'opacity-50 cursor-not-allowed' : 'hover:bg-brand-accent hover:text-black' }}"
                            data-url="{{ route('cart.add', ['product' => $product->id]) }}"
                            data-qty="#qty"
                            data-redirect="{{ route('cart') }}"
                            {{ $isOutOfStock ? 'disabled' : '' }}>
                            {{ $isOutOfStock ? 'Out of Stock' : 'Buy Now - AED ' . ($price ? number_format($price, 2) : '—') }}
                            </button>
                    </div>
                </div>

                <!-- Key Specs -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @php
                    $specs = [
                    ['icon' => 'tag-fill', 'label' => 'Brand', 'value' => $product->brand],
                    ['icon' => 'bookmark-fill', 'label' => 'Condition', 'value' => $product->condition ? ucfirst($product->condition) : null],
                    ['icon' => 'grid-fill', 'label' => 'Category', 'value' => $product->category?->name],
                    ['icon' => 'box-seam', 'label' => 'Stock', 'value' => $product->stock_qty ?? 0],
                    ['icon' => 'percent', 'label' => 'Discount', 'value' => isset($discount) && $discount ? $discount.'%' : null],
                    ];
                    @endphp

                    @foreach($specs as $s)
                    @if(!is_null($s['value']) && $s['value'] !== '')
                    <div class="rounded-xl border border-white/5 bg-white/[0.02] p-4 hover:border-white/20 transition-colors group">
                        <i class="bi bi-{{ $s['icon'] }} text-brand-accent text-xl mb-2 inline-block"></i>
                        <div class="text-xs text-gray-500 mb-1">{{ $s['label'] }}</div>
                        <div class="text-sm font-bold text-white">{{ $s['value'] }}</div>
                    </div>
                    @endif
                    @endforeach
                </div>

                <!-- Trust Badges -->
                <div class="flex items-center justify-center gap-6 py-4 border-t border-b border-white/10">
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <i class="bi bi-shield-check text-brand-accent text-lg"></i>
                        <span>2 Year Warranty</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <i class="bi bi-arrow-repeat text-brand-accent text-lg"></i>
                        <span>30 Day Returns</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <i class="bi bi-patch-check text-brand-accent text-lg"></i>
                        <span>Authentic Product</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
{{-- Detailed Tabs --}}
<section class="py-16 border-t border-white/10">
    <div x-data="{ activeTab: 'specs' }" class="max-w-5xl mx-auto">
        <!-- Tab Navigation -->
        <div class="flex items-center gap-8 border-b border-white/10 mb-8 overflow-x-auto">
            <button @click="activeTab = 'specs'" :class="activeTab === 'specs' ? 'text-white border-b-2 border-brand-accent' : 'text-gray-400 hover:text-white'" class="pb-4 text-sm font-bold uppercase tracking-wider transition-colors whitespace-nowrap">
                Specifications
            </button>
            <button @click="activeTab = 'description'" :class="activeTab === 'description' ? 'text-white border-b-2 border-brand-accent' : 'text-gray-400 hover:text-white'" class="pb-4 text-sm font-bold uppercase tracking-wider transition-colors whitespace-nowrap">
                Description
            </button>
            <button @click="activeTab = 'reviews'" :class="activeTab === 'reviews' ? 'text-white border-b-2 border-brand-accent' : 'text-gray-400 hover:text-white'" class="pb-4 text-sm font-bold uppercase tracking-wider transition-colors whitespace-nowrap">
                Reviews {{ $ratingCount ? "({$ratingCount})" : '' }}
            </button>
            <button @click="activeTab = 'compatibility'" :class="activeTab === 'compatibility' ? 'text-white border-b-2 border-brand-accent' : 'text-gray-400 hover:text-white'" class="pb-4 text-sm font-bold uppercase tracking-wider transition-colors whitespace-nowrap">
                Compatibility
            </button>
        </div>

        <!-- Tab Content -->
        <div class="min-h-[400px]">
            <!-- Specs Tab -->
            <div x-show="activeTab === 'specs'" x-transition class="space-y-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <i class="bi bi-cpu text-brand-accent"></i> GPU Specifications
                        </h3>
                        <table class="w-full text-sm">
                            @foreach([
                            'NVIDIA CUDA Cores' => '7168',
                            'Boost Clock' => '2.48 GHz',
                            'Base Clock' => '1.98 GHz',
                            'Memory Size' => '12 GB',
                            'Memory Type' => 'GDDR6X',
                            'Memory Interface' => '192-bit',
                            'Memory Bandwidth' => '504 GB/s',
                            ] as $key => $value)
                            <tr class="border-b border-white/5">
                                <td class="py-3 text-gray-400">{{ $key }}</td>
                                <td class="py-3 text-white font-medium text-right">{{ $value }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <i class="bi bi-display text-brand-accent"></i> Display Support
                        </h3>
                        <table class="w-full text-sm">
                            @foreach([
                            'Max Resolution' => '7680 x 4320',
                            'Standard Display Connectors' => 'HDMI(2.1), 3x DP(1.4a)',
                            'Multi Monitor' => 'Up to 4 displays',
                            'HDCP' => '2.3',
                            'VR Ready' => 'Yes',
                            ] as $key => $value)
                            <tr class="border-b border-white/5">
                                <td class="py-3 text-gray-400">{{ $key }}</td>
                                <td class="py-3 text-white font-medium text-right">{{ $value }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="glass-panel rounded-2xl p-6 border border-white/10">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <i class="bi bi-lightning-charge text-brand-accent"></i> Power Requirements
                    </h3>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-5 sm:gap-8">
                        <div class="text-center">
                            <div class="text-3xl font-display font-bold text-brand-accent">220W</div>
                            <div class="text-xs text-gray-500 mt-1">Graphics Card Power</div>
                        </div>

                        <div class="text-center">
                            <div class="text-3xl font-display font-bold text-white">700W</div>
                            <div class="text-xs text-gray-500 mt-1">Recommended PSU</div>
                        </div>

                        <div class="text-center col-span-2 sm:col-span-1">
                            <div class="text-2xl sm:text-3xl font-display font-bold text-white">1x 16-pin</div>
                            <div class="text-xs text-gray-500 mt-1">Power Connectors</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Tab -->
            <div x-show="activeTab === 'description'" x-transition class="prose prose-invert max-w-none">
                <div class="text-gray-300 leading-relaxed space-y-4">

                    @if(!empty($product->short_description))
                    <p class="text-lg">{{ $product->short_description }}</p>
                    @endif

                    @if(!empty($product->description))
                    <div class="whitespace-pre-line">
                        {{ $product->description }}
                    </div>
                    @else
                    <p class="text-gray-500">No description provided yet.</p>
                    @endif

                    <div class="my-8 grid md:grid-cols-3 gap-6">
                        @if(!empty($product->badge_text))
                        <div class="rounded-xl bg-white/5 p-6 text-center">
                            <i class="bi bi-patch-check text-4xl text-brand-accent mb-4"></i>
                            <h4 class="font-bold mb-2">Badge</h4>
                            <p class="text-sm text-gray-400">{{ $product->badge_text }}</p>
                        </div>
                        @endif

                        @if(!empty($product->delivery_text))
                        <div class="rounded-xl bg-white/5 p-6 text-center">
                            <i class="bi bi-truck text-4xl text-brand-accent mb-4"></i>
                            <h4 class="font-bold mb-2">Delivery</h4>
                            <p class="text-sm text-gray-400">{{ $product->delivery_text }}</p>
                        </div>
                        @endif

                        @if($discount)
                        <div class="rounded-xl bg-white/5 p-6 text-center">
                            <i class="bi bi-percent text-4xl text-brand-accent mb-4"></i>
                            <h4 class="font-bold mb-2">Discount</h4>
                            <p class="text-sm text-gray-400">{{ (int)$discount }}% off</p>
                        </div>
                        @endif
                    </div>

                </div>
            </div>

            <!-- Reviews Tab -->
            <div x-show="activeTab === 'reviews'" x-transition>
                <div class="flex items-center gap-8 mb-8">
                    <div class="text-center">
                        <div class="text-6xl font-display font-bold text-white">
                            {{ number_format($rating, 1) }}
                        </div>
                        <div class="flex items-center justify-center gap-1 my-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star-fill {{ $i <= $stars ? 'text-yellow-400' : 'text-gray-600' }}"></i>
                                @endfor
                        </div>
                        <div class="text-sm text-gray-400">Based on 128 reviews</div>
                    </div>
                    <div class="flex-1 space-y-2">
                        @foreach([5, 4, 3, 2, 1] as $stars)
                        @php
                        $widthMap = [1 => 65, 2 => 25, 3 => 7, 4 => 2, 5 => 1];
                        $percent = $widthMap[$stars] ?? 0;
                        @endphp
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-400 w-8">{{ $stars }} star</span>
                            <div class="flex-1 h-2 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-yellow-400 rounded-full"
                                    @style(["width: {$percent}%"])>
                                </div>
                            </div>
                            <span class="text-sm text-gray-400 w-10">{{ $percent }}%</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Review Cards -->
                <div class="space-y-4">
                    @foreach([
                    ['name' => 'Ahmed R.', 'rating' => 5, 'date' => '2 days ago', 'title' => 'Absolute beast of a card!', 'content' => 'Upgraded from a 3070 and the difference is night and day. Running Cyberpunk at 4K with ray tracing and DLSS 3 is buttery smooth.'],
                    ['name' => 'Sarah K.', 'rating' => 5, 'date' => '1 week ago', 'title' => 'Perfect for 1440p gaming', 'content' => 'Got this for my new build. Temps are great, barely hits 65°C under load. The ASUS cooling solution is top notch.'],
                    ['name' => 'Mohammed A.', 'rating' => 4, 'date' => '2 weeks ago', 'title' => 'Great performance, slightly pricey', 'content' => 'Performance is excellent but wish it was a bit cheaper. Still, for the UAE market this is a fair price.'],
                    ] as $review)
                    <div class="glass-panel rounded-2xl p-6 border border-white/10">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-accent to-brand-secondary flex items-center justify-center text-black font-bold">
                                    {{ substr($review['name'], 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-white">{{ $review['name'] }}</div>
                                    <div class="text-xs text-gray-500">Verified Purchase</div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500">{{ $review['date'] }}</div>
                        </div>
                        <div class="flex items-center gap-1 mb-2">
                            @for($i = 0; $i < 5; $i++)
                                <i class="bi bi-star-fill {{ $i < ($review['rating'] ?? 0) ? 'text-yellow-400' : 'text-gray-600' }} text-sm"></i>
                                @endfor
                        </div>
                        <h4 class="font-bold text-white mb-2">{{ $review['title'] }}</h4>
                        <p class="text-gray-400 text-sm">{{ $review['content'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Compatibility Tab -->
            <div x-show="activeTab === 'compatibility'" x-transition>
                <div class="glass-panel rounded-2xl p-8 border border-white/10 text-center">
                    <i class="bi bi-motherboard text-6xl text-brand-accent mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Compatibility Check</h3>
                    <p class="text-gray-400 mb-6">
                        We’ll automatically verify compatibility with your build (CPU/Motherboard/RAM/PSU).
                        <span class="text-gray-500">Coming soon.</span>
                    </p>

                    <div class="flex flex-wrap justify-center gap-3 mb-6">
                        <span class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-sm text-gray-300">
                            Category: {{ $product->category?->name ?? '—' }}
                        </span>
                        <span class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-sm text-gray-300">
                            Condition: {{ ucfirst($product->condition ?? '—') }}
                        </span>
                        <span class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-sm text-gray-300">
                            Stock: {{ (int)($product->stock_qty ?? 0) }}
                        </span>
                    </div>

                    <button disabled
                        class="px-6 py-3 rounded-xl bg-white/10 text-gray-400 font-bold cursor-not-allowed">
                        PC Builder Integration (Soon)
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Related Products --}}
<section class="py-16 border-t border-white/10">
    <h2 class="text-2xl font-display font-bold mb-8">Frequently Bought Together</h2>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach($related as $item)
        @php
        $imgPath = optional($item->images->firstWhere('is_primary', 1) ?? $item->images->first())->image_path;
        $src = $imgPath ? asset('storage/' . ltrim($imgPath, '/')) : asset('images/placeholder.png');
        @endphp
        <a href="{{ route('product.show', ['slug' => $item['slug']]) }}" class="group rounded-xl border border-white/5 bg-white/[0.02] p-4 hover:border-brand-accent/50 transition-all hover:-translate-y-1">
            <div class="aspect-square rounded-lg bg-white/5 mb-4 overflow-hidden">
                <img src="{{ $src }}"
                    data-fallback="{{ asset('images/placeholder.png') }}"
                    onerror="this.onerror=null; this.src=this.dataset.fallback;"
                    class="w-full h-full object-cover opacity-70 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500"
                    alt="{{ $item->name }}" />
            </div>
            <div class="text-sm font-medium text-white group-hover:text-brand-accent transition-colors mb-1">{{ $item->name }}</div>
            <div class="text-sm font-bold text-brand-accent">AED {{ number_format((float)$item->price, 2) }}</div>
        </a>
        @endforeach
    </div>
</section>
@endsection