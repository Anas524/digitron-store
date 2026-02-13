@extends('layouts.app')

@section('title', 'Digitron Computers UAE - Build Your Master PC')

@section('fullwidth')
{{-- FULLSCREEN Hero Slider (PRESERVED) --}}
<section id="vsHero" class="vs-hero" data-autoplay="1" data-interval="6500">
    {{-- Slide 1 --}}
    <article class="vs-slide is-active vs-slide1">
        <div class="vs-overlay"></div>
        <div class="vs-content">
            <h1 class="vs-title">Build Smarter. Shop Faster.</h1>
            <p class="vs-text">
                New, used, and custom PCs — curated parts, trusted picks, and a smooth shopping experience for serious builders.
            </p>
            <a href="{{ route('shop') }}" class="vs-btn">Shop Components</a>
        </div>
        <div class="vs-social">
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
        </div>
    </article>

    {{-- Slide 2 --}}
    <article class="vs-slide vs-slide2">
        <div class="vs-overlay"></div>
        <div class="vs-content">
            <h1 class="vs-title">Next-Gen CPUs for Every Build</h1>
            <p class="vs-text">
                Shop Intel & AMD processors for gaming, streaming, and productivity — high clocks, more cores, and smooth performance.
            </p>
            <a href="{{ route('shop', ['category' => 'processors']) }}" class="vs-btn">Shop Processors</a>
        </div>
        <div class="vs-social">
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
        </div>
    </article>

    {{-- Slide 3 --}}
    <article class="vs-slide vs-slide3">
        <div class="vs-overlay"></div>
        <div class="vs-content">
            <h1 class="vs-title">Power Your Build with the Right GPU</h1>
            <p class="vs-text">
                Shop NVIDIA RTX and AMD Radeon graphics cards — smooth gaming, faster rendering, and the performance your setup deserves.
            </p>
            <a href="{{ route('shop', ['category' => 'graphics-cards']) }}" class="vs-btn">Shop Graphics Cards</a>
        </div>
        <div class="vs-social">
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
        </div>
    </article>

    {{-- Dots --}}
    <div class="vs-dots" aria-label="Slider dots"></div>
</section>

{{-- Shop by Category (PRESERVED STRUCTURE, ENHANCED STYLE) --}}
@php
$slides = [
[
'cat' => 'processors',
'kicker' => 'Boost performance',
'title' => 'Next-Gen Processors',
'name' => 'Intel / AMD CPUs',
'desc' => 'Upgrade your build with the latest processors for gaming, editing, and heavy workloads. Fast clocks, more cores, better efficiency.',
'accent' => '#22c55e',
'img' => asset('images/categories/processors.png'),
],
[
'cat' => 'motherboards',
'kicker' => 'Build stability',
'title' => 'Feature-Rich Motherboards',
'name' => 'ATX / mATX / ITX',
'desc' => 'Strong VRMs, modern I/O, Wi-Fi, and premium chipsets — built for smooth upgrades and future compatibility.',
'accent' => '#38bdf8',
'img' => asset('images/categories/motherboards.png'),
],
[
'cat' => 'ram',
'kicker' => 'Speed matters',
'title' => 'High-Performance RAM',
'name' => 'DDR4 / DDR5 Kits',
'desc' => 'More FPS, faster renders, and smoother multitasking with optimized memory frequencies and low latency.',
'accent' => '#a78bfa',
'img' => asset('images/categories/ram.png'),
],
[
'cat' => 'graphics-cards',
'kicker' => 'Power your visuals',
'title' => 'Graphics Cards',
'name' => 'NVIDIA / AMD GPUs',
'desc' => 'Crush gaming and creative workloads with modern GPUs — ray tracing, AI features, and high VRAM options.',
'accent' => '#fb7185',
'img' => asset('images/categories/gpu.png'),
],
[
'cat' => 'ssds',
'kicker' => 'Instant loading',
'title' => 'Ultra-Fast SSDs',
'name' => 'NVMe / SATA Storage',
'desc' => 'Boot faster, load games instantly, and move files at high speed with reliable SSD performance.',
'accent' => '#f59e0b',
'img' => asset('images/categories/ssd.png'),
],
[
'cat' => 'power-supply',
'kicker' => 'Safe & efficient',
'title' => 'Power Supplies',
'name' => '80+ Rated PSUs',
'desc' => 'Protect your components with stable power delivery, modular cables, and efficient certifications.',
'accent' => '#60a5fa',
'img' => asset('images/categories/tx-1600psu.png'),
],
];
@endphp

<section class="catHeroWrap catHeroFull relative z-10">
    <div id="catHero"
        class="catHero catHeroFullInner"
        data-autoplay="1"
        data-interval="5000"
        data-shop-base="{{ route('shop') }}"
        data-slides='@json($slides)'>

        <div class="catHeroHead catHeroHeadOverlay">
            <h2>Shop by Category</h2>
            <a href="{{ route('shop') }}">View all</a>
        </div>
        <div class="catHeroStage"></div>

        <div class="catHeroNav">
            <button type="button" class="catPrev" aria-label="Previous">‹</button>
            <button type="button" class="catNext" aria-label="Next">›</button>
        </div>

        <div class="catHeroDots" aria-label="Dots"></div>
    </div>
</section>
@endsection

@section('content')

{{-- Interactive PC Builder Section --}}
<section id="builder" class="py-24 relative overflow-hidden">
    <!-- Background Grid Effect -->
    <div class="absolute inset-0 bg-grid-pattern opacity-[0.03] pointer-events-none"></div>

    <div class="text-center mb-16 reveal-text">
        <h2 class="text-4xl md:text-5xl font-display font-bold mb-4">CUSTOM <span class="text-brand-accent">PC BUILDER</span></h2>
        <p class="text-gray-400 max-w-2xl mx-auto">Select your components and see your dream PC come to life. Real-time compatibility checking and performance estimation.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Component Selection Area --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- CPU Category --}}
            <div class="glass-panel rounded-xl p-6 component-category" data-category="cpu">
                <div class="flex justify-between items-center mb-4 border-b border-white/5 pb-2">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <i class="bi bi-cpu text-brand-accent"></i> Processor
                    </h3>
                    <span class="text-xs text-gray-500 uppercase tracking-wider">Step 1</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="component-card bg-[#0f1115] p-4 rounded-lg border border-white/5 cursor-pointer group" onclick="selectComponent('cpu', this, {name: 'Intel Core i9-14900K', price: 1800, watts: 253, perf: 100})">
                        <div class="h-32 bg-white/[0.03] border border-white/5 rounded mb-3 overflow-hidden relative">
                            <img src="{{ asset('images/products/i9-14900k.png') }}"
                                class="w-full h-full object-contain p-3 opacity-80 group-hover:opacity-100 transition-opacity"
                                alt="Intel i9">
                        </div>
                        <h4 class="font-bold text-sm">Intel Core i9-14900K</h4>
                        <p class="text-xs text-gray-400 mt-1">24 Cores | 6.0 GHz</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-brand-accent font-bold">AED 1,800</span>
                            <div class="w-2 h-2 rounded-full bg-gray-600 status-dot"></div>
                        </div>
                    </div>
                    <div class="component-card bg-[#0f1115] p-4 rounded-lg border border-white/5 cursor-pointer group" onclick="selectComponent('cpu', this, {name: 'AMD Ryzen 9 7950X', price: 1600, watts: 170, perf: 95})">
                        <div class="h-32 bg-white/[0.03] border border-white/5 rounded mb-3 overflow-hidden relative">
                            <img src="{{ asset('images/products/ryzen-7-7800x3d.png') }}"
                                class="w-full h-full object-contain p-3 opacity-80 group-hover:opacity-100 transition-opacity"
                                alt="Ryzen CPU">
                        </div>
                        <h4 class="font-bold text-sm">AMD Ryzen 9 7950X</h4>
                        <p class="text-xs text-gray-400 mt-1">16 Cores | 5.7 GHz</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-brand-accent font-bold">AED 1,600</span>
                            <div class="w-2 h-2 rounded-full bg-gray-600 status-dot"></div>
                        </div>
                    </div>
                    <div class="component-card bg-[#0f1115] p-4 rounded-lg border border-white/5 cursor-pointer group" onclick="selectComponent('cpu', this, {name: 'Intel Core i5-13600K', price: 900, watts: 125, perf: 70})">
                        <div class="h-32 bg-white/[0.03] border border-white/5 rounded mb-3 overflow-hidden relative">
                            <img src="{{ asset('images/products/i9-14900k.png') }}"
                                class="w-full h-full object-contain p-3 opacity-80 group-hover:opacity-100 transition-opacity"
                                alt="Intel CPU">
                        </div>
                        <h4 class="font-bold text-sm">Intel Core i5-13600K</h4>
                        <p class="text-xs text-gray-400 mt-1">14 Cores | 5.1 GHz</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-brand-accent font-bold">AED 900</span>
                            <div class="w-2 h-2 rounded-full bg-gray-600 status-dot"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- GPU Category --}}
            <div class="glass-panel rounded-xl p-6 component-category" data-category="gpu">
                <div class="flex justify-between items-center mb-4 border-b border-white/5 pb-2">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <i class="bi bi-gpu-card text-brand-secondary"></i> Graphics Card
                    </h3>
                    <span class="text-xs text-gray-500 uppercase tracking-wider">Step 2</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="component-card bg-[#0f1115] p-4 rounded-lg border border-white/5 cursor-pointer group" onclick="selectComponent('gpu', this, {name: 'RTX 4090 24GB', price: 5500, watts: 450, perf: 100})">
                        <div class="h-32 bg-white/[0.03] border border-white/5 rounded mb-3 overflow-hidden relative">
                            <img src="{{ asset('images/products/rtx-4090-super.png') }}" class="w-full h-full object-cover opacity-70 group-hover:opacity-100 transition-opacity mix-blend-screen" alt="RTX 4090">
                        </div>
                        <h4 class="font-bold text-sm">NVIDIA RTX 4090</h4>
                        <p class="text-xs text-gray-400 mt-1">24GB GDDR6X | 4K Gaming</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-brand-accent font-bold">AED 5,500</span>
                            <div class="w-2 h-2 rounded-full bg-gray-600 status-dot"></div>
                        </div>
                    </div>
                    <div class="component-card bg-[#0f1115] p-4 rounded-lg border border-white/5 cursor-pointer group" onclick="selectComponent('gpu', this, {name: 'RX 7900 XTX', price: 3800, watts: 355, perf: 85})">
                        <div class="h-32 bg-white/[0.03] border border-white/5 rounded mb-3 overflow-hidden relative">
                            <img src="{{ asset('images/products/rx-7900-xtx.png') }}" class="w-full h-full object-cover opacity-70 group-hover:opacity-100 transition-opacity mix-blend-screen" alt="RX 7900 XTX">
                        </div>
                        <h4 class="font-bold text-sm">AMD RX 7900 XTX</h4>
                        <p class="text-xs text-gray-400 mt-1">24GB GDDR6 | Ray Tracing</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-brand-accent font-bold">AED 3,800</span>
                            <div class="w-2 h-2 rounded-full bg-gray-600 status-dot"></div>
                        </div>
                    </div>
                    <div class="component-card bg-[#0f1115] p-4 rounded-lg border border-white/5 cursor-pointer group" onclick="selectComponent('gpu', this, {name: 'RTX 4070 Ti', price: 2800, watts: 285, perf: 75})">
                        <div class="h-32 bg-white/[0.03] border border-white/5 rounded mb-3 overflow-hidden relative">
                            <img src="{{ asset('images/products/rtx-4070-ti.png') }}" class="w-full h-full object-cover opacity-70 group-hover:opacity-100 transition-opacity mix-blend-screen" alt="rtx-4070-ti">
                        </div>
                        <h4 class="font-bold text-sm">NVIDIA RTX 4070 Ti</h4>
                        <p class="text-xs text-gray-400 mt-1">12GB GDDR6X | 1440p</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-brand-accent font-bold">AED 2,800</span>
                            <div class="w-2 h-2 rounded-full bg-gray-600 status-dot"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RAM Category --}}
            <div class="glass-panel rounded-xl p-6 component-category" data-category="ram">
                <div class="flex justify-between items-center mb-4 border-b border-white/5 pb-2">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <i class="bi bi-memory text-brand-danger"></i> Memory
                    </h3>
                    <span class="text-xs text-gray-500 uppercase tracking-wider">Step 3</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="component-card bg-[#0f1115] p-4 rounded-lg border border-white/5 cursor-pointer group" onclick="selectComponent('ram', this, {name: '32GB DDR5 6000MHz', price: 600, watts: 10, perf: 10})">
                        <div class="h-32 rounded mb-3 overflow-hidden relative bg-white/[0.03] border border-white/5">
                            <img src="{{ asset('images/products/ddr5-32gb.png') }}"
                                class="w-full h-full object-contain p-3 opacity-80 group-hover:opacity-100 transition-opacity"
                                alt="RAM">
                        </div>
                        <h4 class="font-bold text-sm">32GB DDR5 6000MHz</h4>
                        <p class="text-xs text-gray-400 mt-1">Corsair Dominator RGB</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-brand-accent font-bold">AED 600</span>
                            <div class="w-2 h-2 rounded-full bg-gray-600 status-dot"></div>
                        </div>
                    </div>
                    <div class="component-card bg-[#0f1115] p-4 rounded-lg border border-white/5 cursor-pointer group" onclick="selectComponent('ram', this, {name: '64GB DDR5 5600MHz', price: 1200, watts: 15, perf: 15})">
                        <div class="h-32 rounded mb-3 overflow-hidden relative bg-white/[0.03] border border-white/5">
                            <img src="{{ asset('images/products/ddr5-64gb.png') }}"
                                class="w-full h-full object-contain p-3 opacity-80 group-hover:opacity-100 transition-opacity"
                                alt="RAM">
                        </div>
                        <h4 class="font-bold text-sm">64GB DDR5 5600MHz</h4>
                        <p class="text-xs text-gray-400 mt-1">G.Skill Trident Z5</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-brand-accent font-bold">AED 1,200</span>
                            <div class="w-2 h-2 rounded-full bg-gray-600 status-dot"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sticky Summary Sidebar --}}
        <div class="lg:col-span-4">
            <div class="sticky top-24 glass-panel rounded-xl p-6 border-t-4 border-t-brand-accent shadow-2xl shadow-brand-accent/10">
                <h3 class="text-2xl font-display font-bold mb-6 border-b border-white/10 pb-4">Build Summary</h3>

                <div id="build-list" class="space-y-3 mb-6 min-h-[150px]">
                    <div class="text-gray-500 text-sm italic text-center py-10">Select components to start building...</div>
                </div>

                <div class="mb-6">
                    <div class="flex justify-between text-xs uppercase tracking-widest mb-2">
                        <span class="text-gray-400">Gaming Performance</span>
                        <span class="text-brand-accent font-bold" id="fps-score">0 FPS</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-2 overflow-hidden">
                        <div id="perf-bar" class="bg-gradient-to-r from-brand-secondary to-brand-accent h-2 rounded-full w-0 transition-all duration-500"></div>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-6 p-3 bg-white/5 rounded-lg">
                    <div class="flex items-center gap-2 text-sm text-gray-300">
                        <i class="bi bi-lightning-charge-fill text-yellow-400"></i> Est. Wattage
                    </div>
                    <div class="font-bold text-white"><span id="wattage">0</span>W</div>
                </div>

                <div class="flex justify-between items-end mb-6">
                    <span class="text-gray-400">Total Price</span>
                    <span class="text-3xl font-display font-bold text-white">AED <span id="total-price">0</span></span>
                </div>

                <button class="w-full bg-brand-accent hover:bg-white text-black font-bold py-4 rounded-lg transition-colors flex items-center justify-center gap-2 group">
                    Add to Cart <i class="bi bi-cart-check group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </div>
    </div>
</section>

{{-- Featured Grid Section --}}
<section class="py-20">
    <h2 class="text-3xl font-display font-bold mb-12 text-center">TRENDING <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-brand-secondary">NOW</span></h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 h-96 md:h-[500px]">
        <div class="lg:col-span-2 lg:row-span-2 relative group overflow-hidden rounded-2xl cursor-pointer border border-white/10">
            <img src="{{ asset('images/categories/gpu.png') }}" class="absolute inset-0 w-full h-full object-contain p-10 opacity-80 transition-transform duration-700 group-hover:scale-110" alt="GPUs">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-80"></div>
            <div class="absolute bottom-0 left-0 p-8">
                <h3 class="text-3xl font-bold mb-2">Graphics Cards</h3>
                <p class="text-gray-300 mb-4 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">RTX 40 Series & AMD RX 7000 Series available now.</p>
                <span class="text-brand-accent font-bold flex items-center gap-2">Shop Now <i class="bi bi-arrow-right"></i></span>
            </div>
        </div>

        <div class="relative group overflow-hidden rounded-2xl cursor-pointer border border-white/10">
            <img src="{{ asset('images/categories/processors.png') }}" class="absolute inset-0 w-full h-full object-contain p-10 opacity-80 transition-transform duration-700 group-hover:scale-110" alt="CPUs">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>
            <div class="absolute bottom-0 left-0 p-6">
                <h3 class="text-xl font-bold">Processors</h3>
            </div>
        </div>

        <div class="relative group overflow-hidden rounded-2xl cursor-pointer border border-white/10">
            <img src="{{ asset('images/categories/motherboards.png') }}" class="absolute inset-0 w-full h-full object-contain p-10 opacity-80 transition-transform duration-700 group-hover:scale-110" alt="Motherboards">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>
            <div class="absolute bottom-0 left-0 p-6">
                <h3 class="text-xl font-bold">Motherboards</h3>
            </div>
        </div>

        <div class="relative group overflow-hidden rounded-2xl cursor-pointer border border-white/10">
            <img src="{{ asset('images/categories/ssd.png') }}" class="absolute inset-0 w-full h-full object-contain p-10 opacity-80 transition-transform duration-700 group-hover:scale-110" alt="Peripherals">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>
            <div class="absolute bottom-0 left-0 p-6">
                <h3 class="text-xl font-bold">Peripherals</h3>
            </div>
        </div>

        <div class="relative group overflow-hidden rounded-2xl cursor-pointer border border-white/10">
            <img src="{{ asset('images/categories/psu.png') }}" class="absolute inset-0 w-full h-full object-contain p-10 opacity-80 transition-transform duration-700 group-hover:scale-110" alt="Cooling">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>
            <div class="absolute bottom-0 left-0 p-6">
                <h3 class="text-xl font-bold">Cooling</h3>
            </div>
        </div>
    </div>
</section>

@endsection