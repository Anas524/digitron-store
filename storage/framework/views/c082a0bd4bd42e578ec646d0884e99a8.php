

<?php $__env->startSection('title', 'My Wishlist | Digitron Computers UAE'); ?>

<?php $__env->startSection('page','wishlist'); ?>

<?php $__env->startSection('fullwidth'); ?>

<section class="relative h-[45vh] min-h-[460px] overflow-hidden flex items-center justify-center"
    style="padding-top: calc(var(--headerH, 90px) + 60px); padding-bottom: 190px;">
    <!-- Background Image with Parallax -->
    <div class="absolute inset-0 w-full h-full z-0">
        <img src="<?php echo e(asset('images/pexels-ron-lach-7858767.jpg')); ?>"
            alt="Tech Background"
            class="w-full h-full object-cover opacity-60 scale-110 parallax-bg">
        <div class="absolute inset-0 bg-gradient-to-b from-[#070A12]/15 via-[#070A12]/35 to-[#070A12]/55"></div>
    </div>

    <!-- Animated Particles (JS generated) -->
    <div id="wishlistParticles" class="absolute inset-0 overflow-hidden pointer-events-none"></div>

    <!-- Content -->
    <div class="relative z-40 text-center px-4 pt-24 sm:pt-28 md:pt-32">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-brand-secondary/30 bg-brand-secondary/10 mb-6 animate-pulse">
            <i class="bi bi-heart-fill text-brand-secondary"></i>
            <span class="text-brand-secondary text-sm font-bold uppercase tracking-wider">Saved Items</span>
        </div>

        <h1 class="text-5xl md:text-7xl font-display font-black mb-4 tracking-tight">
            MY <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-secondary to-brand-accent">WISHLIST</span>
        </h1>

        <p class="text-lg text-gray-400 max-w-xl mx-auto">
            Your curated collection of premium hardware. Don't wait too long, prices change and stock moves fast.
        </p>

        <!-- Wishlist Stats -->
        <div class="flex items-center justify-center gap-10 mt-12 mb-12">
            <div class="text-center">
                <div class="text-2xl font-bold text-white" id="wishlist-count"><?php echo e($wishlistCount); ?></div>
                <div class="text-xs text-gray-500 uppercase tracking-wider">Items</div>
            </div>
            <div class="w-px h-10 bg-white/20"></div>
            <div class="text-center">
                <div class="text-2xl font-bold text-brand-secondary" id="wishlist-value">AED <?php echo e(number_format($wishlistTotal)); ?></div>
                <div class="text-xs text-gray-500 uppercase tracking-wider">Total Value</div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
$wishlistCount = $items->count();
$wishlistTotal = $items->sum(fn($p) => (int)($p->price ?? 0));
?>

<section class="py-12 -mt-6 md:-mt-8 relative z-30">
    <!-- Toolbar -->
    <div class="glass-panel rounded-2xl p-4 border border-white/10 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="relative">
                <select id="wishSort"
                    class="wish-select appearance-none px-4 py-2 pr-10 rounded-xl bg-white/5 border border-white/10 text-sm text-white
           focus:outline-none focus:border-brand-secondary transition-colors">
                    <option value="recent">Sort by: Recently Added</option>
                    <option value="plh">Price: Low to High</option>
                    <option value="phl">Price: High to Low</option>
                    <option value="az">Name: A-Z</option>
                </select>

                <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
            </div>

            <div class="hidden md:flex items-center gap-2 text-sm text-gray-400">
                <span>Show:</span>
                <button type="button" data-wish-filter="all"
                    class="wishFilter px-3 py-1 rounded-lg bg-brand-secondary/20 text-brand-secondary font-medium">All</button>

                <button type="button" data-wish-filter="stock"
                    class="wishFilter px-3 py-1 rounded-lg hover:bg-white/5 transition-colors text-gray-300">In Stock</button>

                <button type="button" data-wish-filter="sale"
                    class="wishFilter px-3 py-1 rounded-lg hover:bg-white/5 transition-colors text-gray-300">On Sale</button>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button id="btnClearWishlist" class="flex items-center gap-2 px-4 py-2 rounded-xl border border-white/10 hover:border-brand-danger/50 hover:text-brand-danger transition-colors text-sm">
                <i class="bi bi-trash3"></i> Clear All
            </button>
            <button id="btnAddAllToCart"
                class="flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-secondary text-white hover:bg-brand-accent hover:text-black transition-colors font-medium text-sm"
                data-url-template="<?php echo e(route('cart.add', ['product' => '__ID__'])); ?>">
                <i class="bi bi-cart-plus"></i> Add All to Cart
            </button>
        </div>
    </div>

    <!-- Wishlist Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="wishlist-grid">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $imgUrl = $product->primary_image_url ?? null;

            // fallback if empty
            if (!$imgUrl) {
                $imgUrl = asset('images/placeholder-product.png');
            }
    
            $price = (int) ($product->price ?? 0);
            $original = (int) ($product->compare_price ?? $product->original_price ?? 0);
            $sale = $original > $price;

            $qty = (int) ($product->stock_qty ?? 0);
            $stockLabel = $qty <= 0 ? 'Out of Stock' : ($qty <=5 ? 'Low Stock' : 'In Stock' );

            $added = optional($product->pivot?->created_at)->diffForHumans() ?? 'recently';

            $badge = $sale ? 'Sale' : null; // or set null to disable badge
            $rating = (float) ($product->rating ?? 4.8); // if you don't have rating column
            $reviews = (int) ($product->reviews_count ?? 0); // if you don't have, keep 0
        ?>

            <div class="wishlist-item group relative glass-panel rounded-2xl border border-white/10 overflow-hidden hover:border-brand-secondary/50 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-brand-secondary/10"
                data-id="<?php echo e($product->id); ?>"
                data-price="<?php echo e($price); ?>"
                data-stock="<?php echo e($stockLabel); ?>"
                data-sale="<?php echo e($sale ? 1 : 0); ?>"
                data-name="<?php echo e(strtolower($product->name)); ?>">

                <!-- Image Section -->
                <div class="relative aspect-[4/3] overflow-hidden">
                    <img src="<?php echo e($imgUrl); ?>" alt="<?php echo e($product->name); ?>"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                    <small class="text-xs text-red-400 break-all">
                        : <?php echo e($product->image); ?>

                        | URL: <?php echo e($imgUrl); ?>

                    </small>
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0f] via-transparent to-transparent opacity-60"></div>

                    <!-- Badge -->
                    <?php if($badge): ?>
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                        <?php echo e($badge === 'Price Drop' ? 'bg-emerald-500/80 text-white' : ''); ?>

                        <?php echo e($badge === 'Hot' ? 'bg-orange-500/80 text-white' : ''); ?>

                        <?php echo e($badge === 'Sale' ? 'bg-brand-accent text-black' : ''); ?>

                        <?php echo e($badge === 'Restocking Soon' ? 'bg-gray-500/80 text-white' : ''); ?>

                        backdrop-blur-sm border border-white/10 animate-pulse">
                            <?php echo e($badge); ?>

                        </span>
                    </div>
                    <?php endif; ?>

                    <!-- Stock Status -->
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1.5 rounded-full text-xs font-medium backdrop-blur-sm border border-white/10
                        <?php echo e($stockLabel === 'In Stock' ? 'bg-emerald-500/20 text-emerald-400' : ''); ?>

                        <?php echo e($stockLabel === 'Low Stock' ? 'bg-orange-500/20 text-orange-400' : ''); ?>

                        <?php echo e($stockLabel === 'Out of Stock' ? 'bg-red-500/20 text-red-400' : ''); ?>">
                            <span class="w-1.5 h-1.5 rounded-full inline-block mr-1
                            <?php echo e($stockLabel === 'In Stock' ? 'bg-emerald-400 animate-pulse' : ($stockLabel === 'Low Stock' ? 'bg-orange-400' : 'bg-red-400')); ?>"></span>
                            <?php echo e($stockLabel); ?>

                        </span>
                    </div>

                    <!-- Quick Actions -->
                    <div class="absolute bottom-4 left-4 right-4 flex gap-2
                    translate-y-[calc(100%+1rem)] opacity-0 pointer-events-none
                    group-hover:translate-y-0 group-hover:opacity-100 group-hover:pointer-events-auto
                    transition-all duration-500">
                        <button type="button"
                            class="js-add-to-cart flex-1 py-3 rounded-xl bg-brand-secondary text-white font-bold text-sm hover:bg-brand-accent hover:text-black transition-colors flex items-center justify-center gap-2
                                    <?php echo e($stockLabel === 'Out of Stock' ? 'opacity-50 cursor-not-allowed pointer-events-none' : ''); ?>"
                            data-id="<?php echo e($product->id); ?>"
                            data-url="<?php echo e(route('cart.add', ['product' => $product->id])); ?>"
                            data-stock="<?php echo e($stockLabel); ?>">
                            <i class="bi bi-cart-plus"></i> Add to Cart
                        </button>
                        <button type="button"
                            class="js-remove-wish w-12 h-12 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-white hover:bg-brand-danger hover:text-white transition-colors"
                            data-id="<?php echo e($product->id); ?>">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>

                    <!-- Added Date -->
                    <div class="absolute right-4 top-14 z-40
                                opacity-0 -translate-y-1 group-hover:opacity-100 group-hover:translate-y-0
                                transition-all duration-300 pointer-events-none">
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium
                                bg-black/50 text-white backdrop-blur-md border border-white/10 shadow-lg">
                        <i class="bi bi-clock text-xs text-brand-accent"></i>
                        Added <?php echo e($added); ?>

                    </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-5">
                    <!-- Rating -->
                    <div class="flex items-center gap-2 mb-2">
                        <div class="flex items-center gap-0.5">
                            <?php for($i = 0; $i < 5; $i++): ?>
                                <i class="bi bi-star-fill text-xs <?php echo e($i < floor($rating) ? 'text-yellow-400' : 'text-gray-600'); ?>"></i>
                                <?php endfor; ?>
                        </div>
                        <span class="text-xs text-gray-400">(<?php echo e($reviews); ?>)</span>
                    </div>

                    <!-- Title -->
                    <h3 class="font-bold text-lg text-white group-hover:text-brand-secondary transition-colors mb-1 line-clamp-2">
                        <?php echo e($product->name); ?>

                    </h3>
                    <p class="text-sm text-gray-400 mb-4"><?php echo e($product->short_specs ?? $product->specs ?? ''); ?></p>

                    <!-- Price -->
                    <div class="flex items-end justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-2xl font-bold text-white">AED <?php echo e(number_format($price)); ?></span>
                                <?php if($sale): ?>
                                <span class="text-sm text-gray-500 line-through">AED <?php echo e(number_format($original)); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if($sale): ?>
                            <span class="text-xs text-emerald-400 font-medium">
                                Save AED <?php echo e(number_format($original - $price)); ?>

                            </span>
                            <?php endif; ?>
                        </div>

                        <!-- Added Date (visible on mobile) -->
                        <div class="text-xs text-gray-500 md:hidden">
                            Added <?php echo e($added); ?>

                        </div>
                    </div>
                </div>

                <!-- Hover Border Effect -->
                <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-brand-secondary/30 pointer-events-none transition-colors"></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Empty State (Hidden by default) -->
    <div id="empty-wishlist" class="hidden glass-panel rounded-2xl p-16 text-center border border-white/10">
        <div class="w-24 h-24 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-6 relative">
            <i class="bi bi-heart text-4xl text-gray-500"></i>
            <div class="absolute inset-0 rounded-full border-2 border-brand-secondary/30 animate-ping"></div>
        </div>
        <h3 class="text-2xl font-bold mb-2">Your wishlist is empty</h3>
        <p class="text-gray-400 mb-6 max-w-md mx-auto">Discover premium hardware and save your favorites for later. Great builds start with great planning.</p>
        <a href="<?php echo e(route('shop')); ?>" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-gradient-to-r from-brand-secondary to-brand-accent text-white font-bold hover:shadow-lg hover:shadow-brand-secondary/30 transition-all">
            Explore Products <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <!-- Recommendations -->
    <div class="mt-16 pt-16 border-t border-white/10">
        <h2 class="text-2xl font-bold mb-2">Recommended For You</h2>
        <p class="text-gray-400 mb-8">Based on your wishlist interests</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php $__currentLoopData = $recommended; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $rImg = $rec->primary_image_url ?? asset('images/placeholder-product.png');
            ?>

            <a href="#" class="group rounded-xl border border-white/5 bg-white/[0.02] p-4 hover:border-brand-accent/30 transition-all hover:-translate-y-1">
                <div class="aspect-square rounded-lg bg-white/5 mb-4 overflow-hidden">
                    <img src="<?php echo e($rImg); ?>" class="w-full h-full object-cover opacity-70 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
                </div>
                <h4 class="text-sm font-medium text-white group-hover:text-brand-accent transition-colors truncate"><?php echo e($rec->name); ?></h4>
                <div class="text-sm font-bold text-brand-accent mt-1">AED <?php echo e(number_format((int)$rec->price)); ?></div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/pages/shop/wishlist.blade.php ENDPATH**/ ?>