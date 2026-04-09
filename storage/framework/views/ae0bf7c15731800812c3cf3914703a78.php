
<div class="lg:col-span-9" id="shopResultsWrap">
    
    <div id="shopProducts"
        class="grid gap-5 transition-all duration-500"
        :class="viewMode === 'grid' ? 'grid-cols-2 sm:grid-cols-3 xl:grid-cols-4' : 'grid-cols-1'">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
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
        ?>

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
                <a href="<?php echo e(route('product.show', ['slug' => $p->slug])); ?>"
                    class="absolute inset-0 z-10"
                    aria-label="<?php echo e($p->name); ?>"></a>

                <?php if($badge): ?>
                <div class="absolute top-3 left-3 z-10">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                <?php echo e($badge === 'hot' ? 'bg-red-500/80 text-white' : ''); ?>

                                <?php echo e($badge === 'sale' ? 'bg-brand-accent text-black' : ''); ?>

                                <?php echo e($badge === 'bestseller' ? 'bg-purple-500/80 text-white' : ''); ?>

                                <?php echo e($badge === 'used' ? 'bg-gray-500/80 text-white' : ''); ?>

                                backdrop-blur-sm border border-white/10">
                        <?php echo e($badge); ?>

                    </span>
                </div>
                <?php endif; ?>

                
                <?php
                $inWish = auth()->check()
                ? auth()->user()->wishlistProducts()->where('products.id', $p->id)->exists()
                : in_array((int)$p->id, array_map('intval', (array) session('wishlist_ids', [])), true);
                ?>

                <button
                    type="button"
                    class="absolute top-3 right-3 z-30 w-10 h-10 flex items-center justify-center
                            transition-transform hover:scale-110
                            <?php echo e($inWish ? 'text-yellow-400' : 'text-gray-300 hover:text-yellow-400'); ?>

                            js-wish-toggle"
                    data-url="<?php echo e(route('wishlist.toggle', ['product' => $p->id])); ?>"
                    data-in="<?php echo e($inWish ? 1 : 0); ?>"
                    aria-label="Toggle wishlist">
                    <i class="bi <?php echo e($inWish ? 'bi-heart-fill' : 'bi-heart'); ?> text-lg"></i>
                </button>

                <img src="<?php echo e($img); ?>"
                    alt="<?php echo e($p->name); ?>"
                    class="h-full w-full transition-transform duration-700 group-hover:scale-110"
                    :class="viewMode === 'list' ? 'object-contain p-4' : 'object-cover'" />

                <div class="quick-add-wrap absolute inset-x-4 bottom-4 z-30 opacity-0 translate-y-8
                    group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out">
                    <button type="button"
                        class="relative z-30 w-full py-3 rounded-xl bg-brand-accent text-black font-bold text-sm js-cart-add"
                        data-url="<?php echo e(route('cart.add', ['product' => $p->id])); ?>"
                        data-qty="1">
                        <i class="bi bi-cart-plus"></i> Quick Add
                    </button>
                </div>
            </div>

            <div
                class="flex flex-col"
                :class="viewMode === 'list' ? 'flex-1 p-5 justify-between' : 'p-4'">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-brand-accent uppercase tracking-wider"><?php echo e($tag); ?></span>
                    <div class="flex items-center gap-1">
                        <i class="bi bi-star-fill text-yellow-400 text-xs"></i>
                        <span class="text-xs text-gray-400">
                            <?php echo e(number_format($p->rating ?? 4.8, 1)); ?>

                            <?php if($p->rating_count): ?> (<?php echo e($p->rating_count); ?>) <?php endif; ?>
                        </span>
                    </div>
                </div>

                <h3
                    class="font-bold text-white group-hover:text-brand-accent transition-colors line-clamp-2 mb-3"
                    :class="viewMode === 'list' ? 'text-lg' : 'text-sm'">
                    <a href="<?php echo e(route('product.show', ['slug' => $p->slug])); ?>" class="relative z-10">
                        <?php echo e($p->name); ?>

                    </a>
                </h3>

                <div class="flex items-center justify-between mt-auto">
                    <span class="text-lg font-bold text-white">AED <?php echo e(number_format($p->price, 0)); ?></span>

                    <?php if($p->compare_at_price && $p->compare_at_price > $p->price): ?>
                    <span class="text-xs text-gray-500 line-through ml-2">
                        AED <?php echo e(number_format($p->compare_at_price, 0)); ?>

                    </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="mt-12 text-center">
        <?php if($products->hasMorePages()): ?>
        <a href="<?php echo e($products->nextPageUrl()); ?>"
            class="group relative inline-flex items-center justify-center px-8 py-4 rounded-full border border-white/20 text-white font-medium hover:border-brand-accent transition-all duration-300 overflow-hidden">
            <span class="relative z-10 flex items-center gap-2 text-white transition-colors">
                Load More Products <i class="bi bi-arrow-down animate-bounce"></i>
            </span>
            <div class="absolute inset-0 bg-brand-accent translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
        </a>
        <?php else: ?>
        <!-- <div class="text-gray-500 text-sm">No more products</div> -->
        <?php endif; ?>
    </div>

    
    <div class="mt-10">
        <?php echo e($products->links()); ?>

    </div>
</div><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/pages/shop/_products.blade.php ENDPATH**/ ?>