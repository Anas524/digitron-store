

<?php $__env->startSection('title', 'Digitron Computers UAE - Build Your Master PC'); ?>

<?php $__env->startSection('page','home'); ?>
<?php $__env->startSection('noLenis','1'); ?>

<?php $__env->startSection('fullwidth'); ?>

<section id="vsHero" class="vs-hero" data-autoplay="1" data-interval="6500">
    
    <article class="vs-slide is-active vs-slide1">
        <video class="vs-bg-video" autoplay muted loop playsinline>
            <source src="<?php echo e(asset('videos/slide-hero.mp4')); ?>" type="video/mp4">
        </video>

        <div class="vs-overlay"></div>
        <div class="vs-content">
            <h1 class="vs-title">
                <span class="vs-white">Build Smarter.</span>
                <!-- <span class="vs-accent">Shop Faster.</span> -->
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-brand-secondary">Shop Faster.</span>
            </h1>
            <p class="vs-text">
                New, used, and custom PCs — curated parts, trusted picks, and a smooth shopping experience for serious builders.
            </p>
            <a href="<?php echo e(route('shop')); ?>" class="vs-btn">Shop Components</a>
        </div>
        <div class="vs-social">
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
        </div>
    </article>

    
    <article class="vs-slide vs-slide2">
        <video class="vs-bg-video" autoplay muted loop playsinline>
            <source src="<?php echo e(asset('videos/video-ref-3.mp4')); ?>" type="video/mp4">
        </video>

        <div class="vs-overlay"></div>
        <div class="vs-content">
            <h1 class="vs-title">
                <span class="vs-white">Next-Gen CPUs</span>
                <!-- <span class="vs-accent">for Every Build</span> -->
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-brand-secondary">for Every Build</span>
            </h1>
            <p class="vs-text">
                Shop Intel & AMD processors for gaming, streaming, and productivity — high clocks, more cores, and smooth performance.
            </p>
            <a href="<?php echo e(route('shop', ['category' => 'processors'])); ?>" class="vs-btn">Shop Processors</a>
        </div>
        <div class="vs-social">
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
        </div>
    </article>

    
    <article class="vs-slide vs-slide3">
        <video class="vs-bg-video" autoplay muted loop playsinline>
            <source src="<?php echo e(asset('videos/video-ref-4.mp4')); ?>" type="video/mp4">
        </video>

        <div class="vs-overlay"></div>
        <div class="vs-content">
            <h1 class="vs-title">
                <span class="vs-white">Power Your Build with the</span>
                <!-- <span class="vs-accent">Right GPU</span> -->
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-brand-secondary">Right GPU</span>
            </h1>
            <p class="vs-text">
                Shop NVIDIA RTX and AMD Radeon graphics cards — smooth gaming, faster rendering, and the performance your setup deserves.
            </p>
            <a href="<?php echo e(route('shop', ['category' => 'graphics-cards'])); ?>" class="vs-btn">Shop Graphics Cards</a>
        </div>
        <div class="vs-social">
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
        </div>
    </article>

    
    <div class="vs-dots" aria-label="Slider dots"></div>
</section>



<section class="dc-showcase-section relative py-16 overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand-accent/5 rounded-full blur-[150px]"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-brand-secondary/5 rounded-full blur-[120px]"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-[0.02]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start mb-16">

            
            <div class="lg:col-span-7 min-w-0 relative z-10">
                <?php
                $posters = $homePromoBanners ?? [];
                $posterCount = count($posters);
                ?>
                <div class="dc-poster-slider relative rounded-3xl overflow-hidden border border-white/10 shadow-2xl shadow-black/50" id="dcPosterSlider">
                    <div class="dc-poster-track relative w-full">
                        <?php $__empty_1 = true; $__currentLoopData = $posters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $poster): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="dc-poster-slide <?php echo e($index === 0 ? 'is-active' : ''); ?>" data-index="<?php echo e($index); ?>">
                            <img
                                src="<?php echo e($poster['image']); ?>"
                                alt="Showcase Poster"
                                class="dc-poster-image w-full h-auto block">
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="dc-poster-empty bg-[#0a0a0f]"></div>
                        <?php endif; ?>
                    </div>

                    <?php if($posterCount > 1): ?>
                    <button type="button" class="dc-poster-arrow prev absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-black/50 backdrop-blur-sm border border-white/20 flex items-center justify-center text-white hover:bg-brand-accent hover:text-black hover:border-brand-accent transition-all z-10">
                        <i class="bi bi-chevron-left text-xl"></i>
                    </button>

                    <button type="button" class="dc-poster-arrow next absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-black/50 backdrop-blur-sm border border-white/20 flex items-center justify-center text-white hover:bg-brand-accent hover:text-black hover:border-brand-accent transition-all z-10">
                        <i class="bi bi-chevron-right text-xl"></i>
                    </button>

                    <div class="dc-poster-dots absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                        <?php $__currentLoopData = $posters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $poster): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button class="w-2 h-2 rounded-full bg-white/30 transition-all <?php echo e($index === 0 ? 'w-8 bg-brand-accent' : ''); ?>" data-index="<?php echo e($index); ?>"></button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/10">
                        <div class="dc-poster-progress h-full bg-brand-accent transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <?php
            $toneMap = [
            'accent' => [
            'border' => 'hover:border-brand-accent/50',
            'overlay' => 'from-brand-accent/20 to-brand-secondary/10',
            'iconWrap' => 'bg-brand-accent/10 text-brand-accent group-hover:bg-brand-accent group-hover:text-black',
            'title' => 'group-hover:text-brand-accent',
            'arrow' => 'group-hover:text-brand-accent',
            ],
            'emerald' => [
            'border' => 'hover:border-emerald-500/50',
            'overlay' => 'from-emerald-500/15 to-transparent',
            'iconWrap' => 'bg-emerald-500/10 text-emerald-400 group-hover:bg-emerald-500 group-hover:text-black',
            'title' => 'group-hover:text-emerald-400',
            'arrow' => 'group-hover:text-emerald-400',
            ],
            'secondary' => [
            'border' => 'hover:border-brand-secondary/50',
            'overlay' => 'from-brand-secondary/20 to-transparent',
            'iconWrap' => 'bg-brand-secondary/10 text-brand-secondary group-hover:bg-brand-secondary group-hover:text-white',
            'title' => 'group-hover:text-brand-secondary',
            'arrow' => 'group-hover:text-brand-secondary',
            ],
            ];
            ?>

            <div class="lg:col-span-5 min-w-0 relative z-20">
                <div class="grid grid-cols-1 gap-4">
                    <?php $__empty_1 = true; $__currentLoopData = $showcasePromos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                    $tone = $toneMap[$promo['tone'] ?? 'accent'] ?? $toneMap['accent'];
                    ?>

                    <a href="<?php echo e($promo['href'] ?? '#'); ?>"
                        class="dc-promo-card group relative block overflow-hidden rounded-2xl border border-white/10 bg-white/[0.03] min-h-[154px] <?php echo e($tone['border']); ?> transition-all duration-500 hover:-translate-y-1">
                        <div class="absolute inset-0 bg-gradient-to-br <?php echo e($tone['overlay']); ?> opacity-100 transition-opacity duration-500"></div>

                        <div class="relative flex h-full items-center gap-4 p-6">
                            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl text-2xl transition-all duration-300 group-hover:scale-110 <?php echo e($tone['iconWrap']); ?>">
                                <i class="bi <?php echo e($promo['icon'] ?? 'bi-grid'); ?>"></i>
                            </div>

                            <div class="min-w-0 flex-1">
                                <h4 class="text-xl font-bold text-white transition-colors <?php echo e($tone['title']); ?>">
                                    <?php echo e($promo['title'] ?? 'Untitled'); ?>

                                </h4>
                                <p class="mt-2 text-sm leading-6 text-gray-300">
                                    <?php echo e($promo['text'] ?? 'No text available.'); ?>

                                </p>
                            </div>

                            <i class="bi bi-arrow-right shrink-0 text-gray-400 transition-all group-hover:translate-x-2 <?php echo e($tone['arrow']); ?>"></i>
                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="rounded-2xl border border-red-500/40 bg-red-500/10 p-6 text-red-300">
                        showcasePromos is empty
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="dc-categories-section relative mt-2">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <span class="text-brand-accent text-sm font-bold uppercase tracking-[0.3em] mb-2 block">Browse</span>
                    <h2 class="text-4xl md:text-5xl font-display font-bold">
                        SHOP BY
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-brand-secondary">CATEGORY</span>
                    </h2>
                </div>

                <a href="<?php echo e(route('shop')); ?>" class="hidden md:inline-flex items-center gap-2 text-gray-400 hover:text-brand-accent transition-colors group">
                    View All Categories
                    <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <div class="dc-categories-orbit relative">
                <div class="absolute inset-0 pointer-events-none overflow-hidden">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] border border-white/5 rounded-full"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] border border-white/5 rounded-full"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] border border-white/5 rounded-full"></div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 relative z-10" id="dcCategoriesGrid">
                    <?php
                    $categories = $homeShowcaseCategories ?? collect([
                    (object)['category' => (object)['name' => 'Processors', 'slug' => 'processors'], 'primaryImage' => null],
                    (object)['category' => (object)['name' => 'Graphics Cards', 'slug' => 'graphics-cards'], 'primaryImage' => null],
                    (object)['category' => (object)['name' => 'Memory', 'slug' => 'ram'], 'primaryImage' => null],
                    (object)['category' => (object)['name' => 'Storage', 'slug' => 'ssds'], 'primaryImage' => null],
                    (object)['category' => (object)['name' => 'Motherboards', 'slug' => 'motherboards'], 'primaryImage' => null],
                    (object)['category' => (object)['name' => 'Power Supplies', 'slug' => 'power-supply'], 'primaryImage' => null],
                    ]);

                    $accentColors = ['#00f0ff', '#7000ff', '#00ff88', '#ffaa00', '#ff2d55', '#38bdf8'];
                    $orbitSpeedClasses = ['orbit-speed-20','orbit-speed-25','orbit-speed-30','orbit-speed-35','orbit-speed-40','orbit-speed-45'];
                    ?>

                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    $category = $item->category ?? $item['category'] ?? null;
                    if (!$category) continue;

                    $slug = $category->slug ?? $category['slug'] ?? '';
                    $name = $category->name ?? $category['name'] ?? '';
                    $image = $item->primaryImage
                    ? asset('storage/' . $item->primaryImage->image_path)
                    : asset('images/placeholder-product.png');
                    $color = $accentColors[$index % count($accentColors)];
                    ?>

                    <a href="<?php echo e(route('shop', ['category' => $slug])); ?>"
                        class="dc-orbit-category group relative flex flex-col items-center"
                        data-color="<?php echo e($color); ?>"
                        style="--orbit-color: <?php echo e($color); ?>">
                        <div class="dc-orbit-ring absolute inset-0 rounded-full border-2 border-dashed border-white/10 group-hover:border-[var(--orbit-color)]/30 group-hover:animate-spin-slow transition-all duration-500 <?php echo e($orbitSpeedClasses[$index % count($orbitSpeedClasses)]); ?>"></div>

                        <div class="dc-orbit-glow absolute inset-4 rounded-full bg-[var(--orbit-color)]/0 group-hover:bg-[var(--orbit-color)]/20 blur-xl transition-all duration-500 group-hover:scale-150"></div>

                        <div class="dc-orbit-circle relative w-32 h-32 md:w-40 md:h-40 rounded-full border border-white/10 bg-gradient-to-b from-white/10 to-transparent p-1 group-hover:scale-110 group-hover:border-[var(--orbit-color)]/50 transition-all duration-500 overflow-hidden">
                            <div class="w-full h-full rounded-full bg-[#0a0a0f] flex items-center justify-center overflow-hidden relative">
                                <img src="<?php echo e($image); ?>" alt="<?php echo e($name); ?>"
                                    class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-125 transition-all duration-700 mix-blend-screen">
                                <div class="absolute inset-0 bg-gradient-to-t from-[var(--orbit-color)]/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                        </div>

                        <div class="mt-6 text-center relative">
                            <h3 class="text-lg font-bold text-white group-hover:text-[var(--orbit-color)] transition-colors"><?php echo e($name); ?></h3>
                            <span class="text-xs text-gray-500 group-hover:text-gray-300 transition-colors flex items-center justify-center gap-1 mt-1">
                                Explore <i class="bi bi-arrow-right opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all"></i>
                            </span>
                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mt-8 text-center md:hidden">
                    <a href="<?php echo e(route('shop')); ?>" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-white/20 text-white hover:border-brand-accent hover:text-brand-accent transition-colors">
                        View All Categories
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        
        <div class="mt-16 glass-panel rounded-2xl p-6 border border-white/10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <?php $__currentLoopData = $showcaseStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="relative">
                    <div class="text-3xl font-display font-bold mb-1 <?php echo e($stat['tone']); ?> counter" data-target="<?php echo e($stat['value']); ?>">
                        0
                    </div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider">
                        <?php echo e($stat['label']); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>



<?php
$categorySpotlights = ($categorySpotlights ?? collect())->count()
? $categorySpotlights
: collect([
[
'cat' => 'processors',
'kicker' => 'Boost performance',
'title' => 'Next-Gen Processors',
'name' => 'Intel / AMD CPUs',
'desc' => 'Upgrade your build with modern processors for gaming, editing, and multitasking workloads.',
'accent' => '#22c55e',
'img' => asset('images/placeholder-product.png'),
],
[
'cat' => 'graphics-cards',
'kicker' => 'Power your visuals',
'title' => 'Graphics Cards',
'name' => 'NVIDIA / AMD GPUs',
'desc' => 'High-performance graphics cards for gaming, rendering, and creator workflows.',
'accent' => '#fb7185',
'img' => asset('images/placeholder-product.png'),
],
]);
?>
<section class="catHeroWrap catHeroFull relative z-10 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div
            id="catHero"
            class="catHero catHeroFullInner"
            data-autoplay="1"
            data-interval="5200"
            data-shop-base="<?php echo e(route('shop')); ?>"
            data-slides='<?php echo json_encode($categorySpotlights ?? [], 15, 512) ?>'>

            <div class="catHeroHead catHeroHeadOverlay">
                <div>
                    <span class="text-brand-accent text-sm font-bold uppercase tracking-[0.28em] block mb-2">
                        Upgrade Paths
                    </span>
                    <h2>Explore Performance Zones</h2>
                </div>

                <a href="<?php echo e(route('shop')); ?>">View all categories</a>
            </div>

            <div class="catHeroStage"></div>

            <div class="catHeroNav">
                <button type="button" class="catPrev" aria-label="Previous">‹</button>
                <button type="button" class="catNext" aria-label="Next">›</button>
            </div>

            <div class="catHeroDots" aria-label="Dots"></div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<section id="builder" class="py-24 relative">
    <!-- Background Grid Effect -->
    <div class="absolute inset-0 bg-grid-pattern opacity-[0.03] pointer-events-none"></div>

    <div class="text-center mb-16 reveal-text">
        <!-- <h2 class="text-4xl md:text-5xl font-display font-bold mb-4">CUSTOM <span class="text-brand-accent">PC BUILDER</span></h2> -->
        <h2 class="text-4xl md:text-5xl font-display font-bold mb-4">
                            CUSTOM <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-brand-secondary">PC BUILDER</span>
                        </h2>
        <p class="text-gray-400 max-w-2xl mx-auto">Select your components and see your dream PC come to life. Real-time compatibility checking and performance estimation.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-8 space-y-6">
            <?php
            $cpuItems = $builder['cpu'] ?? collect();
            $gpuItems = $builder['gpu'] ?? collect();
            $ramItems = $builder['ram'] ?? collect();

            $getImg = function($p){
            $imgs = $p->images ?? collect();
            return $imgs->first()
            ? asset('storage/' . ltrim($imgs->first()->image_path, '/'))
            : asset('images/placeholder-product.png');
            };

            $fmt = fn($n) => number_format((int)$n);
            ?>

            
            <div class="glass-panel rounded-xl p-6 component-category" data-category="cpu">
                <div class="flex justify-between items-center mb-4 border-b border-white/5 pb-2">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <i class="bi bi-cpu text-brand-accent"></i> Processor
                    </h3>

                    <div class="flex items-center gap-2">
                        <button type="button" class="builder-nav builder-prev w-10 h-10 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10"
                            data-target="#cpuTrack">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="builder-nav builder-next w-10 h-10 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10"
                            data-target="#cpuTrack">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <?php if($cpuItems->count()): ?>
                <div id="cpuTrack" class="builder-track flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-2">
                    <?php $__currentLoopData = $cpuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    $img = $getImg($p);
                    $price = (int)($p->price ?? 0);
                    $watts = (int)($p->watts ?? 120);
                    $perf = (int)($p->perf_score ?? 70);

                    $payload = [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $price,
                    'watts' => $watts,
                    'perf' => $perf,
                    'cartUrl' => route('cart.add', $p->id),
                    ];
                    ?>

                    <div class="component-card min-w-[260px] md:min-w-[280px] snap-start bg-[#0f1115] p-4 rounded-lg border border-white/10 hover:border-white/20 transition relative cursor-pointer group"
                        data-payload='<?php echo json_encode($payload, 15, 512) ?>'
                        onclick="selectComponentFromCard('cpu', this)">
                        <div class="h-32 bg-white/[0.03] border border-white/5 rounded mb-3 overflow-hidden relative">
                            <img src="<?php echo e($img); ?>" class="w-full h-full object-contain p-3 opacity-80 group-hover:opacity-100 transition-opacity" alt="<?php echo e($p->name); ?>">
                        </div>

                        <h4 class="font-bold text-sm truncate"><?php echo e($p->name); ?></h4>
                        <p class="text-xs text-gray-400 mt-1 line-clamp-1"><?php echo e($p->short_specs ?? $p->specs ?? '—'); ?></p>

                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-brand-accent font-bold">AED <?php echo e($fmt($price)); ?></span>
                            <div class="w-2 h-2 rounded-full bg-gray-600 status-dot"></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php else: ?>
                <div class="text-gray-500 text-sm italic py-8 text-center">
                    No CPU products found yet. Add products in Admin → Products (Category: Processors).
                </div>
                <?php endif; ?>
            </div>

            
            <div class="glass-panel rounded-xl p-6 component-category" data-category="gpu">
                <div class="flex justify-between items-center mb-4 border-b border-white/5 pb-2">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <i class="bi bi-gpu-card text-brand-secondary"></i> Graphics Card
                    </h3>

                    <div class="flex items-center gap-2">
                        <button type="button" class="builder-nav builder-prev w-10 h-10 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10"
                            data-target="#gpuTrack">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="builder-nav builder-next w-10 h-10 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10"
                            data-target="#gpuTrack">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <?php if($gpuItems->count()): ?>
                <div id="gpuTrack"
                    class="builder-track flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-2">
                    <?php $__currentLoopData = $gpuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php
                    $img = $getImg($p);
                    $price = (int)($p->price ?? 0);
                    $watts = (int)($p->watts ?? 250);
                    $perf = (int)($p->perf_score ?? 80);

                    $payload = ['id'=>$p->id,'name'=>$p->name,'price'=>$price,'watts'=>$watts,'perf'=>$perf, 'cartUrl' => route('cart.add', $p->id),];
                    ?>

                    <div class="component-card min-w-[260px] md:min-w-[280px] snap-start bg-[#0f1115] p-4 rounded-lg border border-white/5 cursor-pointer group"
                        data-payload='<?php echo json_encode($payload, 15, 512) ?>'
                        onclick="selectComponentFromCard('gpu', this)">
                        <div class="h-32 bg-white/[0.03] border border-white/5 rounded mb-3 overflow-hidden relative">
                            <img src="<?php echo e($img); ?>"
                                class="w-full h-full object-contain p-3 opacity-80 group-hover:opacity-100 transition-opacity"
                                alt="<?php echo e($p->name); ?>">
                        </div>

                        <h4 class="font-bold text-sm truncate"><?php echo e($p->name); ?></h4>
                        <p class="text-xs text-gray-400 mt-1 line-clamp-1"><?php echo e($p->short_specs ?? $p->specs ?? '—'); ?></p>

                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-brand-accent font-bold">AED <?php echo e($fmt($price)); ?></span>
                            <div class="w-2 h-2 rounded-full bg-gray-600 status-dot"></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php else: ?>
                <div class="text-gray-500 text-sm italic py-8 text-center">
                    No GPU products found yet. Add products in Admin → Products (Category: Graphics Cards).
                </div>
                <?php endif; ?>
            </div>

            
            <div class="glass-panel rounded-xl p-6 component-category" data-category="ram">
                <div class="flex justify-between items-center mb-4 border-b border-white/5 pb-2">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <i class="bi bi-memory text-brand-danger"></i> Memory
                    </h3>

                    <div class="flex items-center gap-2">
                        <button type="button" class="builder-nav builder-prev w-10 h-10 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10"
                            data-target="#ramTrack">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="builder-nav builder-next w-10 h-10 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10"
                            data-target="#ramTrack">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <?php if($ramItems->count()): ?>
                <div id="ramTrack"
                    class="builder-track flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-2">
                    <?php $__currentLoopData = $ramItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php
                    $img = $getImg($p);
                    $price = (int)($p->price ?? 0);
                    $watts = (int)($p->watts ?? 250);
                    $perf = (int)($p->perf_score ?? 80);

                    $payload = ['id'=>$p->id,'name'=>$p->name,'price'=>$price,'watts'=>$watts,'perf'=>$perf, 'cartUrl' => route('cart.add', $p->id),];
                    ?>

                    <div class="component-card min-w-[260px] md:min-w-[280px] snap-start bg-[#0f1115] p-4 rounded-lg border border-white/5 cursor-pointer group"
                        data-payload='<?php echo json_encode($payload, 15, 512) ?>'
                        onclick="selectComponentFromCard('ram', this)">
                        <div class="h-32 bg-white/[0.03] border border-white/5 rounded mb-3 overflow-hidden relative">
                            <img src="<?php echo e($img); ?>"
                                class="w-full h-full object-contain p-3 opacity-80 group-hover:opacity-100 transition-opacity"
                                alt="<?php echo e($p->name); ?>">
                        </div>

                        <h4 class="font-bold text-sm truncate"><?php echo e($p->name); ?></h4>
                        <p class="text-xs text-gray-400 mt-1 line-clamp-1"><?php echo e($p->short_specs ?? $p->specs ?? '—'); ?></p>

                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-brand-accent font-bold">AED <?php echo e($fmt($price)); ?></span>
                            <div class="w-2 h-2 rounded-full bg-gray-600 status-dot"></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php else: ?>
                <div class="text-gray-500 text-sm italic py-8 text-center">
                    No RAM products found yet. Add products in Admin → Products (Category: Memory).
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="lg:col-span-4">
            <div
                class="self-start glass-panel rounded-xl p-6 border-t-4 border-t-brand-accent shadow-2xl shadow-brand-accent/10"
                style="position: sticky; top: 112px;">
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

                <button
                    id="builderAddToCartBtn"
                    type="button"
                    class="w-full bg-brand-accent hover:bg-white text-black font-bold py-4 rounded-lg transition-colors flex items-center justify-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="builderAddToCartText">Add to Cart</span>
                    <i class="bi bi-cart-check group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </div>
    </div>
</section>


<section class="py-20">
    <h2 class="text-3xl font-display font-bold mb-12 text-center">TRENDING <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-brand-secondary">NOW</span></h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 h-96 md:h-[500px]">
        <div class="lg:col-span-2 lg:row-span-2 relative group overflow-hidden rounded-2xl cursor-pointer border border-white/10">
            <img src="<?php echo e(asset('images/categories/gpu.png')); ?>" class="absolute inset-0 w-full h-full object-contain p-10 opacity-80 transition-transform duration-700 group-hover:scale-110" alt="GPUs">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-80"></div>
            <div class="absolute bottom-0 left-0 p-8">
                <h3 class="text-3xl font-bold mb-2">Graphics Cards</h3>
                <p class="text-gray-300 mb-4 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">RTX 40 Series & AMD RX 7000 Series available now.</p>
                <span class="text-brand-accent font-bold flex items-center gap-2">Shop Now <i class="bi bi-arrow-right"></i></span>
            </div>
        </div>

        <div class="relative group overflow-hidden rounded-2xl cursor-pointer border border-white/10">
            <img src="<?php echo e(asset('images/categories/processors.png')); ?>" class="absolute inset-0 w-full h-full object-contain p-10 opacity-80 transition-transform duration-700 group-hover:scale-110" alt="CPUs">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>
            <div class="absolute bottom-0 left-0 p-6">
                <h3 class="text-xl font-bold">Processors</h3>
            </div>
        </div>

        <div class="relative group overflow-hidden rounded-2xl cursor-pointer border border-white/10">
            <img src="<?php echo e(asset('images/categories/motherboards.png')); ?>" class="absolute inset-0 w-full h-full object-contain p-10 opacity-80 transition-transform duration-700 group-hover:scale-110" alt="Motherboards">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>
            <div class="absolute bottom-0 left-0 p-6">
                <h3 class="text-xl font-bold">Motherboards</h3>
            </div>
        </div>

        <div class="relative group overflow-hidden rounded-2xl cursor-pointer border border-white/10">
            <img src="<?php echo e(asset('images/categories/ssd.png')); ?>" class="absolute inset-0 w-full h-full object-contain p-10 opacity-80 transition-transform duration-700 group-hover:scale-110" alt="Peripherals">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>
            <div class="absolute bottom-0 left-0 p-6">
                <h3 class="text-xl font-bold">Peripherals</h3>
            </div>
        </div>

        <div class="relative group overflow-hidden rounded-2xl cursor-pointer border border-white/10">
            <img src="<?php echo e(asset('images/categories/psu.png')); ?>" class="absolute inset-0 w-full h-full object-contain p-10 opacity-80 transition-transform duration-700 group-hover:scale-110" alt="Cooling">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>
            <div class="absolute bottom-0 left-0 p-6">
                <h3 class="text-xl font-bold">Cooling</h3>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('cta'); ?>


<section class="py-20 relative overflow-hidden">
    
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-accent/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-brand-secondary/10 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass-panel relative rounded-3xl border border-white/10 overflow-hidden">
            
            <div class="absolute inset-0 bg-grid-pattern opacity-[0.04] pointer-events-none"></div>
            
            <div class="absolute inset-x-0 -top-20 h-40 bg-gradient-to-b from-white/10 to-transparent pointer-events-none"></div>

            <div class="relative p-8 md:p-12 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-10">
                
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-brand-accent/25 bg-brand-accent/10 mb-5">
                        <span class="w-2 h-2 rounded-full bg-brand-accent animate-pulse"></span>
                        <span class="text-brand-accent text-xs md:text-sm font-bold uppercase tracking-[0.25em]">
                            Ready to build?
                        </span>
                    </div>

                    <h3 class="text-3xl md:text-4xl font-display font-bold mb-2">
                        Get a custom quote <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-brand-secondary">fast</span>
                    </h3>

                    <p class="text-gray-400 mt-3 text-base md:text-lg leading-relaxed">
                        Share your budget + use case (gaming, editing, streaming, office). We’ll recommend the best parts and pricing—no guesswork.
                    </p>

                    
                    <div class="mt-6 flex flex-wrap gap-3 text-sm text-gray-300">
                        <span class="px-3 py-1.5 rounded-full border border-white/10 bg-white/5">
                            <i class="bi bi-clock-history text-brand-accent"></i> Fast response
                        </span>
                        <span class="px-3 py-1.5 rounded-full border border-white/10 bg-white/5">
                            <i class="bi bi-shield-check text-brand-accent"></i> Expert checked
                        </span>
                        <span class="px-3 py-1.5 rounded-full border border-white/10 bg-white/5">
                            <i class="bi bi-cpu text-brand-accent"></i> Compatibility ensured
                        </span>
                    </div>
                </div>

                
                <div class="w-full lg:w-auto flex flex-col sm:flex-row gap-3">
                    <a href="<?php echo e(route('quote')); ?>#quote-section"
                        class="group inline-flex items-center justify-center gap-2 px-7 py-4 rounded-xl
                    bg-gradient-to-r from-brand-accent to-brand-secondary text-black font-black
                    shadow-lg shadow-brand-accent/20 hover:shadow-xl hover:shadow-brand-accent/30 transition">
                        Contact Us
                        <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>

                    <a href="<?php echo e(route('shop')); ?>"
                        class="inline-flex items-center justify-center gap-2 px-7 py-4 rounded-xl
                    border border-white/20 bg-white/5 text-white font-bold
                    hover:bg-white/10 hover:border-brand-accent/50 hover:text-brand-accent transition">
                        Browse Store
                        <i class="bi bi-bag"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/pages/home.blade.php ENDPATH**/ ?>