

<?php $__env->startSection('page','shop'); ?>

<?php $__env->startSection('fullwidth'); ?>

<section class="relative h-[60vh] min-h-[500px] overflow-hidden flex items-center justify-center">
    <!-- Video Background -->
    <div class="absolute inset-0 w-full h-full z-0">
        <video autoplay muted loop playsinline preload="auto" class="object-cover w-full h-full opacity-60 scale-110">
            <source src="<?php echo e(asset('videos/shop-hero.mp4')); ?>?v=<?php echo e(time()); ?>" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-gradient-to-b from-[#070A12]/15 via-[#070A12]/35 to-[#070A12]/55"></div>
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
                <div class="text-3xl font-display font-bold text-white"><?php echo e(number_format(\App\Models\Product::where('is_active',1)->count())); ?></div>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<section x-data="{ filtersOpen: false, viewMode: 'grid' }" class="py-12 -mt-20 relative z-20">
    
    <div class="sticky top-20 z-30 mb-8">
        <div class="glass-panel rounded-2xl border border-white/10 p-4 shadow-2xl shadow-black/50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                
                <div class="flex items-center gap-4">
                    <button
                        @click="filtersOpen = !filtersOpen"
                        class="inline-flex lg:hidden items-center gap-2 rounded-xl bg-white/5 px-4 py-2.5 text-sm font-medium hover:bg-white/10 transition-colors border border-white/10">
                        <i class="bi bi-funnel"></i>
                        <span x-text="filtersOpen ? 'Hide Filters' : 'Show Filters'"></span>
                    </button>

                    <div class="hidden sm:block text-sm text-gray-400">
                        Showing <span class="text-white font-semibold"><?php echo e($products->total()); ?></span> products
                    </div>
                </div>

                
                <div class="hidden md:flex items-center gap-3 min-w-0 flex-1">
                    <button
                        type="button"
                        id="catPillsPrev"
                        class="shrink-0 w-10 h-10 rounded-xl border border-white/10 bg-white/5 text-white hover:bg-white/10 transition-colors flex items-center justify-center">
                        <i class="bi bi-chevron-left"></i>
                    </button>

                    <div class="relative min-w-0 flex-1">
                        <div
                            id="categoryPillsScroller"
                            class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 no-scrollbar scroll-smooth">
                            <a href="<?php echo e(route('shop', array_merge(request()->except('page','category'), ['category' => 'all']))); ?>"
                                class="px-4 py-2 rounded-full text-sm font-bold whitespace-nowrap
                <?php echo e($activeCategory === 'all' ? 'bg-brand-accent text-black' : 'bg-white/5 text-white hover:bg-white/10 border border-white/10'); ?>">
                                All
                            </a>

                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('shop', array_merge(request()->except('page'), ['category' => $cat->slug]))); ?>"
                                class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap border border-white/10
                <?php echo e($activeCategory === $cat->slug ? 'bg-brand-accent text-black font-bold' : 'bg-white/5 text-white hover:bg-white/10'); ?>">
                                <?php echo e($cat->name); ?>

                            </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <button
                        type="button"
                        id="catPillsNext"
                        class="shrink-0 w-10 h-10 rounded-xl border border-white/10 bg-white/5 text-white hover:bg-white/10 transition-colors flex items-center justify-center">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                
                <div class="flex items-center gap-3">
                    
                    <div class="hidden sm:flex items-center bg-white/5 rounded-lg p-1 border border-white/10">
                        <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white/20 text-white' : 'text-gray-400'" class="p-2 rounded-md transition-colors">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </button>
                        <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-white/20 text-white' : 'text-gray-400'" class="p-2 rounded-md transition-colors">
                            <i class="bi bi-list-ul"></i>
                        </button>
                    </div>

                    <?php
                    $sortVal = request('sort','featured');
                    $sortLabelMap = [
                    'featured' => 'Sort: Featured',
                    'price_asc' => 'Price: Low to High',
                    'price_desc' => 'Price: High to Low',
                    'newest' => 'Newest First',
                    'rating' => 'Best Rated',
                    ];
                    $sortLabel = $sortLabelMap[$sortVal] ?? 'Sort: Featured';
                    ?>

                    <div class="relative" x-data="{
                            open:false,
                            label:<?php echo \Illuminate\Support\Js::from($sortLabel)->toHtml() ?>,
                            items:[
                                {label:'Sort: Featured', val:'featured'},
                                {label:'Price: Low to High', val:'price_asc'},
                                {label:'Price: High to Low', val:'price_desc'},
                                {label:'Newest First', val:'newest'},
                                {label:'Best Rated', val:'rating'},
                            ],
                            choose(it){
                                this.label = it.label;
                                this.open = false;

                                // update hidden input (inside sidebar form)
                                const sortInput = document.getElementById('sortInput');
                                if (sortInput) sortInput.value = it.val;

                                // if sidebar form exists, submit it
                                const form = document.getElementById('shopFiltersForm');
                                if (form) form.submit();
                                else {
                                    // fallback: just update URL (should rarely happen)
                                    const url = new URL(window.location.href);
                                    url.searchParams.set('sort', it.val);
                                    window.location.href = url.toString();
                                }
                            }
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

                            <template x-for="it in items" :key="it.val">
                                <button type="button" @click="choose(it)"
                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-200 hover:bg-white/10 hover:text-white transition-colors">
                                    <span x-text="it.label"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-12">

        
        <aside class="lg:col-span-3 transition-all duration-500"
            :class="filtersOpen ? 'block' : 'hidden lg:block'">
            <div id="shopFiltersWrap">
                <?php echo $__env->make('pages.shop._filters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </aside>

        
        <div class="lg:col-span-9">
            <div id="shopResultsWrap">
                <?php echo $__env->make('pages.shop._products', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

    </div>

</section>


<?php
    // ensure variable exists
    $recentProducts = $recentProducts ?? collect();

    // fallback if empty
    if ($recentProducts->isEmpty()) {
        $recentProducts = \App\Models\Product::with(['images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
            ->latest()
            ->take(4)
            ->get();
    }
?>

<section class="py-16 border-t border-white/10">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-display font-bold">
            <?php echo e(count(session('recently_viewed', [])) > 1 ? 'Recently Viewed' : 'You May Like'); ?>

        </h2>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <?php $__currentLoopData = $recentProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('product.show', $p->slug)); ?>"
               class="group rounded-xl border border-white/5 bg-white/[0.02] p-3 hover:border-white/20 transition-colors">

                <div class="aspect-square rounded-lg bg-white/5 mb-3 overflow-hidden">
                    <img src="<?php echo e($p->primary_image_url); ?>"
                         class="w-full h-full object-cover opacity-70 group-hover:opacity-100 transition-opacity">
                </div>

                <div class="text-xs text-gray-400 mb-1">
                    <?php echo e(ucfirst($p->condition ?? 'New')); ?>

                </div>

                <div class="text-sm font-medium text-white truncate">
                    <?php echo e($p->name); ?>

                </div>

                <div class="text-sm font-bold text-brand-accent mt-1">
                    AED <?php echo e(number_format($p->price, 0)); ?>

                </div>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/pages/shop.blade.php ENDPATH**/ ?>