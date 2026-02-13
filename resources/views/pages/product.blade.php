@extends('layouts.app')

@section('page','product')

@php
$product = collect($products)->firstWhere('slug', $slug) ?? $products[0];
$thumbs = array_values(array_filter($product['thumbs'] ?? [$product['image']]));
@endphp

@section('fullwidth')

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
            <a href="#" class="hover:text-white transition-colors">{{ $product['category'] }}</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-white">{{ $product['name'] }}</span>
        </nav>

        <div class="grid gap-12 lg:grid-cols-2">
            {{-- Left: Interactive Gallery --}}
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="relative aspect-square rounded-3xl border border-white/10 bg-white/[0.03] overflow-hidden group" id="main-gallery">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <img src="{{ $product['image'] }}"
                            alt="{{ $product['name'] }}"
                            class="w-full h-full object-contain p-8 transition-transform duration-500 group-hover:scale-105"
                            id="main-image">
                    </div>

                    <!-- Zoom Hint -->
                    <div class="absolute bottom-4 right-4 px-3 py-1.5 rounded-full bg-black/50 backdrop-blur-sm text-xs text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity">
                        <i class="bi bi-zoom-in"></i> Hover to zoom
                    </div>

                    <!-- Navigation Arrows -->
                    <button class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-black/40 backdrop-blur-sm border border-white/10 flex items-center justify-center text-white hover:bg-brand-accent hover:text-black transition-all opacity-0 group-hover:opacity-100">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-black/40 backdrop-blur-sm border border-white/10 flex items-center justify-center text-white hover:bg-brand-accent hover:text-black transition-all opacity-0 group-hover:opacity-100">
                        <i class="bi bi-chevron-right"></i>
                    </button>

                    <!-- 360 View Badge -->
                    <div class="absolute top-4 left-4 px-3 py-1.5 rounded-full bg-brand-accent text-black text-xs font-bold flex items-center gap-2">
                        <i class="bi bi-360"></i> 360° View
                    </div>
                </div>

                <!-- Thumbnails -->
                <div class="grid grid-cols-5 gap-3">
                    @foreach($thumbs as $index => $img)
                    <button
                        type="button"
                        class="gallery-thumb aspect-square rounded-xl border-2 {{ $index === 0 ? 'border-brand-accent' : 'border-white/10' }}
                                overflow-hidden hover:border-brand-accent transition-all group"
                        data-gallery-thumb
                        data-img="{{ $img }}">
                        <img src="{{ $img }}"
                            class="w-full h-full object-cover opacity-70 group-hover:opacity-100 transition-opacity"
                            onerror="this.closest('button').style.display='none';">
                    </button>
                    @endforeach
                </div>

                <!-- Video Preview -->
                <div class="relative rounded-2xl overflow-hidden border border-white/10 group cursor-pointer">
                    <video autoplay muted loop playsinline class="w-full h-48 object-cover opacity-60 group-hover:opacity-80 transition-opacity">
                        <source src="https://assets.mixkit.co/videos/preview/mixkit-abstract-technology-network-connections-27612-large.mp4" type="video/mp4">
                    </video>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-16 h-16 rounded-full bg-brand-accent/90 flex items-center justify-center text-black text-2xl group-hover:scale-110 transition-transform">
                            <i class="bi bi-play-fill ml-1"></i>
                        </div>
                    </div>
                    <div class="absolute bottom-3 left-3 text-sm font-medium">Product Video</div>
                </div>
            </div>

            {{-- Right: Product Info --}}
            <div class="lg:sticky lg:top-24 lg:h-fit space-y-6">
                <!-- Header -->
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold border border-emerald-500/30 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            In Stock
                        </span>
                        <span class="px-3 py-1 rounded-full bg-brand-accent/20 text-brand-accent text-xs font-bold border border-brand-accent/30">New Arrival</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-display font-bold leading-tight mb-2">
                        NVIDIA GeForce<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-brand-secondary">
                            {{ $product['name'] }}
                        </span>
                    </h1>

                    <div class="flex items-center gap-4 text-sm">
                        <div class="flex items-center gap-1">
                            @for($i = 0; $i < 5; $i++)
                                <i class="bi bi-star-fill {{ $i < 4 ? 'text-yellow-400' : 'text-gray-600' }}"></i>
                                @endfor
                                <span class="text-gray-400 ml-2">4.8 (128 reviews)</span>
                        </div>
                        <span class="text-gray-600">|</span>
                        <span class="text-gray-400">SKU: <span class="text-white">RTX4070S-ASUS</span></span>
                    </div>
                </div>

                <!-- Price -->
                <div class="glass-panel rounded-2xl p-6 border border-white/10">
                    <div class="flex items-end gap-4 mb-4">
                        <span class="text-4xl sm:text-5xl font-display font-bold text-white">AED {{ $product['price'] }}</span>
                        <span class="text-xl text-gray-500 line-through mb-2">AED 3,199</span>
                        <span class="px-3 py-1 rounded-lg bg-red-500/20 text-red-400 text-sm font-bold mb-2">-12%</span>
                    </div>

                    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
                        <i class="bi bi-truck text-brand-accent"></i>
                        Free delivery to Dubai within 24 hours
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center border border-white/10 rounded-xl overflow-hidden">
                                <button type="button" class="px-4 py-3 hover:bg-white/5 transition-colors" data-qty-minus>
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" value="1" id="qty" class="w-16 text-center bg-transparent border-x border-white/10 py-3 text-sm font-bold" min="1" max="10">
                                <button type="button" class="px-4 py-3 hover:bg-white/5 transition-colors" data-qty-minus>
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>

                            <button class="flex-1 py-3.5 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition-all flex items-center justify-center gap-2 group">
                                <i class="bi bi-cart-plus text-lg group-hover:scale-110 transition-transform"></i>
                                Add to Cart
                            </button>

                            <button class="w-12 h-12 rounded-xl border border-white/10 flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-500/50 hover:bg-red-500/10 transition-all">
                                <i class="bi bi-heart text-xl"></i>
                            </button>
                        </div>

                        <button class="w-full py-3.5 rounded-xl border border-brand-accent text-brand-accent font-bold hover:bg-brand-accent hover:text-black transition-all flex items-center justify-center gap-2">
                            Buy Now - AED 2,799
                        </button>
                    </div>
                </div>

                <!-- Key Specs -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach([
                    ['icon' => 'gpu-card', 'label' => 'Memory', 'value' => '12GB GDDR6X'],
                    ['icon' => 'lightning-charge', 'label' => 'Boost Clock', 'value' => '2.48 GHz'],
                    ['icon' => 'display', 'label' => 'Resolution', 'value' => '4K @ 240Hz'],
                    ['icon' => 'fan', 'label' => 'Cooling', 'value' => 'Triple Fan'],
                    ['icon' => 'pci-e', 'label' => 'Interface', 'value' => 'PCIe 4.0'],
                    ['icon' => 'power', 'label' => 'Power', 'value' => '220W TDP'],
                    ] as $spec)
                    <div class="rounded-xl border border-white/5 bg-white/[0.02] p-4 hover:border-white/20 transition-colors group">
                        <i class="bi bi-{{ $spec['icon'] }} text-brand-accent text-xl mb-2 group-hover:scale-110 transition-transform inline-block"></i>
                        <div class="text-xs text-gray-500 mb-1">{{ $spec['label'] }}</div>
                        <div class="text-sm font-bold text-white">{{ $spec['value'] }}</div>
                    </div>
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
                Reviews (128)
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
                    <div class="flex items-center gap-8">
                        <div class="text-center">
                            <div class="text-3xl font-display font-bold text-brand-accent">220W</div>
                            <div class="text-xs text-gray-500 mt-1">Graphics Card Power</div>
                        </div>
                        <div class="h-12 w-px bg-white/10"></div>
                        <div class="text-center">
                            <div class="text-3xl font-display font-bold text-white">700W</div>
                            <div class="text-xs text-gray-500 mt-1">Recommended PSU</div>
                        </div>
                        <div class="h-12 w-px bg-white/10"></div>
                        <div class="text-center">
                            <div class="text-3xl font-display font-bold text-white">1x 16-pin</div>
                            <div class="text-xs text-gray-500 mt-1">Power Connectors</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Tab -->
            <div x-show="activeTab === 'description'" x-transition class="prose prose-invert max-w-none">
                <div class="text-gray-300 leading-relaxed space-y-4">
                    <p class="text-lg">Experience ultra-high performance gaming and content creation with the NVIDIA GeForce RTX 4070 Super. Powered by the ultra-efficient NVIDIA Ada Lovelace architecture, this GPU brings a quantum leap in performance and AI-powered graphics.</p>

                    <div class="my-8 grid md:grid-cols-3 gap-6">
                        <div class="rounded-xl bg-white/5 p-6 text-center">
                            <i class="bi bi-robot text-4xl text-brand-accent mb-4"></i>
                            <h4 class="font-bold mb-2">DLSS 3</h4>
                            <p class="text-sm text-gray-400">AI-powered performance multiplier for breakthrough FPS</p>
                        </div>
                        <div class="rounded-xl bg-white/5 p-6 text-center">
                            <i class="bi bi-brightness-high text-4xl text-brand-accent mb-4"></i>
                            <h4 class="font-bold mb-2">Ray Tracing</h4>
                            <p class="text-sm text-gray-400">Hyper-realistic lighting and reflections in real-time</p>
                        </div>
                        <div class="rounded-xl bg-white/5 p-6 text-center">
                            <i class="bi bi-cpu text-4xl text-brand-accent mb-4"></i>
                            <h4 class="font-bold mb-2">Reflex</h4>
                            <p class="text-sm text-gray-400">Lowest latency for competitive gaming advantage</p>
                        </div>
                    </div>

                    <p>Built with 3rd generation RT cores and 4th generation Tensor cores, the RTX 4070 Super delivers 2x the performance and power efficiency of the previous generation. The advanced cooling solution ensures whisper-quiet operation even under heavy loads.</p>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div x-show="activeTab === 'reviews'" x-transition>
                <div class="flex items-center gap-8 mb-8">
                    <div class="text-center">
                        <div class="text-6xl font-display font-bold text-white">4.8</div>
                        <div class="flex items-center justify-center gap-1 my-2">
                            @for($i = 0; $i < 5; $i++)
                                <i class="bi bi-star-fill text-yellow-400"></i>
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
                                <i class="bi bi-star-fill {{ $i < $review['rating'] ? 'text-yellow-400' : 'text-gray-600' }} text-sm"></i>
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
                    <h3 class="text-xl font-bold mb-4">PC Builder Integration</h3>
                    <p class="text-gray-400 mb-6">Check if this component is compatible with your current build</p>
                    <button class="px-6 py-3 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition-colors">
                        Open in PC Builder
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
        @foreach(collect($products)->where('slug','!=',$product['slug'])->take(4) as $item)
        <a href="{{ route('product.show', ['slug' => $item['slug']]) }}" class="group rounded-xl border border-white/5 bg-white/[0.02] p-4 hover:border-brand-accent/50 transition-all hover:-translate-y-1">
            <div class="aspect-square rounded-lg bg-white/5 mb-4 overflow-hidden">
                <img src="{{ $item['image'] }}" class="w-full h-full object-cover opacity-70 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
            </div>
            <div class="text-sm font-medium text-white group-hover:text-brand-accent transition-colors mb-1">{{ $item['name'] }}</div>
            <div class="text-sm font-bold text-brand-accent">AED {{ $item['price'] }}</div>
        </a>
        @endforeach
    </div>
</section>
@endsection