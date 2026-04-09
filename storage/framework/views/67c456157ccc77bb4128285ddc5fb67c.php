<form id="shopFiltersForm" method="GET" action="<?php echo e(route('shop')); ?>" class="space-y-4">
    
    <input type="hidden" name="category" value="<?php echo e($activeCategory ?? 'all'); ?>">

    
    <input type="hidden" name="sort" id="sortInput" value="<?php echo e(request('sort','featured')); ?>">

    
    <?php
    $activeConds = array_values(array_filter((array) request('condition', [])));
    $activeBrands = array_values(array_filter((array) request('brand', [])));
    $activeRating = request('rating');
    $minP = request('min_price');
    $maxP = request('max_price');

    $hasActiveFilters =
    count($activeConds) ||
    count($activeBrands) ||
    ($activeRating !== null && $activeRating !== '') ||
    (($minP !== null && $minP !== '') || ($maxP !== null && $maxP !== ''));
    ?>

    <div class="glass-panel rounded-2xl border border-white/10 <?php echo e($hasActiveFilters ? 'p-5' : 'p-4'); ?>">
        <div class="flex items-center justify-between <?php echo e($hasActiveFilters ? 'mb-4' : ''); ?>">
            <div class="text-sm font-bold text-white flex items-center gap-2">
                <i class="bi bi-sliders"></i> Filters
            </div>

            <?php if($hasActiveFilters): ?>
            <a href="<?php echo e(route('shop')); ?>"
                class="text-xs text-brand-accent hover:text-white transition-colors">Clear All</a>
            <?php endif; ?>
        </div>

        <?php if($hasActiveFilters): ?>
        <div class="flex flex-wrap gap-2">
            
            <?php $__currentLoopData = $activeConds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('shop', array_merge(request()->except('page'), [
                                        'condition' => array_values(array_diff($activeConds, [$c]))
                                    ]))); ?>"
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-brand-accent/20 text-brand-accent text-xs border border-brand-accent/30">
                <?php echo e(ucfirst($c)); ?> <i class="bi bi-x-circle-fill"></i>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php $__currentLoopData = $activeBrands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('shop', array_merge(request()->except('page'), [
                                        'brand' => array_values(array_diff($activeBrands, [$b]))
                                    ]))); ?>"
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-brand-accent/20 text-brand-accent text-xs border border-brand-accent/30">
                <?php echo e($b); ?> <i class="bi bi-x-circle-fill"></i>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($activeRating !== null && $activeRating !== ''): ?>
            <a href="<?php echo e(route('shop', request()->except('page','rating'))); ?>"
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-brand-accent/20 text-brand-accent text-xs border border-brand-accent/30">
                <?php echo e($activeRating); ?>★ & Up <i class="bi bi-x-circle-fill"></i>
            </a>
            <?php endif; ?>

            
            <?php if(($minP !== null && $minP !== '') || ($maxP !== null && $maxP !== '')): ?>
            <a href="<?php echo e(route('shop', request()->except('page','min_price','max_price'))); ?>"
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-brand-accent/20 text-brand-accent text-xs border border-brand-accent/30">
                Price <i class="bi bi-x-circle-fill"></i>
            </a>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="text-xs text-gray-500">No filters applied</div>
        <?php endif; ?>
    </div>

    
    <div class="glass-panel rounded-2xl border border-white/10 p-5 filter-section">
        <button type="button" class="flex items-center justify-between w-full text-sm font-bold text-white mb-4 group">
            <span>Condition</span>
            <i class="bi bi-chevron-down transition-transform group-hover:text-brand-accent"></i>
        </button>
        <div class="space-y-3">
            <?php $selectedConditions = (array) request('condition', []); ?>

            <?php $__currentLoopData = $condFacet; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $condition => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $val = strtolower($condition); ?>
            <label class="flex items-center justify-between group cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <input name="condition[]" value="<?php echo e($val); ?>" type="checkbox"
                            class="peer sr-only"
                            <?php echo e(in_array($val, $selectedConditions) ? 'checked' : ''); ?>

                            onchange="document.getElementById('shopFiltersForm').submit()">
                        <div class="w-5 h-5 rounded border-2 border-white/20 peer-checked:bg-brand-accent peer-checked:border-brand-accent transition-all"></div>
                        <i class="bi bi-check-lg absolute top-0.5 left-0.5 text-black text-xs opacity-0 peer-checked:opacity-100"></i>
                    </div>
                    <span class="text-sm text-gray-300 group-hover:text-white transition-colors"><?php echo e(ucfirst($val)); ?></span>
                </div>
                <span class="text-xs text-gray-500"><?php echo e($count); ?></span>
            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="glass-panel rounded-2xl border border-white/10 p-5 filter-section">
        <button type="button" class="flex items-center justify-between w-full text-sm font-bold text-white mb-4 group">
            <span>Brand</span>
            <i class="bi bi-chevron-down transition-transform group-hover:text-brand-accent"></i>
        </button>
        <div class="space-y-3 max-h-48 overflow-y-auto custom-scrollbar">
            <?php $selectedBrands = (array) request('brand', []); ?>

            <?php $__currentLoopData = $brandFacet; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="flex items-center justify-between group cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <input name="brand[]" value="<?php echo e($brand); ?>" type="checkbox"
                            class="peer sr-only"
                            <?php echo e(in_array($brand, $selectedBrands) ? 'checked' : ''); ?>

                            onchange="document.getElementById('shopFiltersForm').submit()">
                        <div class="w-5 h-5 rounded border-2 border-white/20 peer-checked:bg-brand-accent peer-checked:border-brand-accent transition-all"></div>
                        <i class="bi bi-check-lg absolute top-0.5 left-0.5 text-black text-xs opacity-0 peer-checked:opacity-100"></i>
                    </div>
                    <span class="text-sm text-gray-300 group-hover:text-white transition-colors"><?php echo e($brand); ?></span>
                </div>
                <span class="text-xs text-gray-500"><?php echo e($count); ?></span>
            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <?php
    $minBound = (int)floor($priceMinMax->minp ?? 0);
    $maxBound = (int)ceil($priceMinMax->maxp ?? 0);

    // start from 0 (as you requested)
    $minBound = 0;

    $minVal = (int) request('min_price', $minBound);
    $maxVal = (int) request('max_price', $maxBound);

    // safety clamp
    $minVal = max($minBound, min($minVal, $maxBound));
    $maxVal = max($minBound, min($maxVal, $maxBound));
    if($minVal > $maxVal){ [$minVal, $maxVal] = [$maxVal, $minVal]; }
    ?>

    <div class="glass-panel rounded-2xl border border-white/10 p-5 filter-section">

        <button type="button" class="flex items-center justify-between w-full text-sm font-bold text-white mb-4 group">
            <span>Price Range (AED)</span>
            <i class="bi bi-chevron-down transition-transform group-hover:text-brand-accent"></i>
        </button>

        <div class="space-y-4">

            
            <div class="relative h-2 bg-white/10 rounded-full overflow-visible">

                
                <div id="priceFill"
                    class="absolute h-full rounded-full bg-gradient-to-r from-brand-accent to-brand-secondary"
                    style="left:0%; width:100%;"></div>

                
                <input id="priceMinRange"
                    type="range"
                    min="<?php echo e($minBound); ?>"
                    max="<?php echo e($maxBound); ?>"
                    step="1"
                    value="<?php echo e($minVal); ?>"
                    class="price-range price-range--min" />

                <input id="priceMaxRange"
                    type="range"
                    min="<?php echo e($minBound); ?>"
                    max="<?php echo e($maxBound); ?>"
                    step="1"
                    value="<?php echo e($maxVal); ?>"
                    class="price-range price-range--max" />

                
                <div id="priceDotMin"
                    class="absolute w-4 h-4 bg-white rounded-full shadow-lg"
                    style="top:-4px; left:0;"></div>

                <div id="priceDotMax"
                    class="absolute w-4 h-4 bg-white rounded-full shadow-lg"
                    style="top:-4px; left:calc(100% - 16px);"></div>
            </div>

            
            <div class="flex items-center gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">AED</span>
                    <input id="minPriceInput"
                        name="min_price"
                        type="number"
                        min="<?php echo e($minBound); ?>"
                        max="<?php echo e($maxBound); ?>"
                        value="<?php echo e($minVal); ?>"
                        class="w-full rounded-xl border border-white/10 bg-white/5 pl-10 pr-3 py-2.5 text-sm text-white outline-none focus:border-brand-accent transition-colors price-number" />
                </div>

                <span class="text-gray-500">-</span>

                <div class="relative flex-1">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">AED</span>
                    <input id="maxPriceInput"
                        name="max_price"
                        type="number"
                        min="<?php echo e($minBound); ?>"
                        max="<?php echo e($maxBound); ?>"
                        value="<?php echo e($maxVal); ?>"
                        class="w-full rounded-xl border border-white/10 bg-white/5 pl-10 pr-3 py-2.5 text-sm text-white outline-none focus:border-brand-accent transition-colors price-number" />
                </div>
            </div>

        </div>
    </div>

    
    <div class="glass-panel rounded-2xl border border-white/10 p-5 filter-section">
        <button type="button" class="flex items-center justify-between w-full text-sm font-bold text-white mb-4 group">
            <span>Rating</span>
            <i class="bi bi-chevron-down transition-transform group-hover:text-brand-accent"></i>
        </button>
        <div class="space-y-2">
            <?php $selectedRating = request('rating'); ?>

            <?php $__currentLoopData = [5,4,3,2,1]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stars): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="flex items-center gap-3 cursor-pointer group">
                <input type="radio" name="rating" value="<?php echo e($stars); ?>"
                    class="sr-only peer"
                    <?php echo e((string)$selectedRating === (string)$stars ? 'checked' : ''); ?>

                    onchange="document.getElementById('shopFiltersForm').submit()">

                <div class="flex-1 flex items-center gap-1">
                    <?php for($i=0;$i<5;$i++): ?>
                        <i class="bi bi-star-fill text-xs <?php echo e($i < $stars ? 'text-yellow-400' : 'text-gray-600'); ?>"></i>
                        <?php endfor; ?>
                        <span class="text-xs text-gray-400 ml-2">& Up</span>
                </div>

                <div class="w-4 h-4 rounded-full border-2 border-white/20 peer-checked:border-brand-accent peer-checked:bg-brand-accent transition-all relative">
                    <div class="absolute inset-1 rounded-full bg-black opacity-0 peer-checked:opacity-100"></div>
                </div>
            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($selectedRating !== null && $selectedRating !== ''): ?>
            <button type="button"
                onclick="(function(){
                                    const f=document.getElementById('shopFiltersForm');
                                    const r=f.querySelectorAll('input[name=rating]');
                                    r.forEach(x=>x.checked=false);
                                    // remove rating param by submitting to URL without it:
                                    const url=new URL(window.location.href); url.searchParams.delete('rating'); window.location.href=url.toString();
                                })()"
                class="mt-3 text-xs text-brand-accent hover:text-white transition-colors">
                Clear rating
            </button>
            <?php endif; ?>
        </div>
    </div>
</form><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/pages/shop/_filters.blade.php ENDPATH**/ ?>