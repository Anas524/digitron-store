<?php
$id = $p?->id;
$isDraft = empty($id);

$anchor = $isDraft ? ('prod-draft-' . uniqid()) : ('prod-' . $id);

// unique key for DOM ids (checkbox/labels) so drafts don’t clash
$domKey = $isDraft ? ('draft-' . uniqid()) : $id;

$placeholder = asset('images/placeholder-product.png');

$data = $isDraft ? [
'id' => null,
'name' => '',
'brand' => '',
'sku' => '',
'condition' => 'new',
'product_type' => 'component',
'category_id' => '',
'price' => '',
'compare_at_price' => '',
'stock_qty' => 0,
'is_active' => 1,
'badge_text' => '',
'rating' => '',
'rating_count' => '',
'delivery_text' => '',
'discount_percent' => '',
'short_description' => '',
'description' => '',
'images_count' => 0,
'primary_image_url' => $placeholder,
] : [
'id' => $p->id,
'name' => $p->name,
'brand' => $p->brand,
'sku' => $p->sku,
'condition' => $p->condition ?? 'new',
'product_type' => $p->product_type ?? 'component',
'category_id' => $p->category_id,
'price' => $p->price,
'compare_at_price' => $p->compare_at_price,
'stock_qty' => $p->stock_qty ?? 0,
'is_active' => $p->is_active ? 1 : 0,
'badge_text' => $p->badge_text,
'rating' => $p->rating,
'rating_count' => $p->rating_count,
'delivery_text' => $p->delivery_text,
'discount_percent' => $p->discount_percent,
'short_description' => $p->short_description,
'description' => $p->description,
'images_count' => $p->images_count ?? 0,
'primary_image_url' => $p->primary_image_url,
];

$updateUrl = $isDraft ? '' : route('admin.products.update', $p);
$deleteUrl = $isDraft ? '' : route('admin.products.destroy', $p);
?>

<article id="<?php echo e($anchor); ?>"
    class="product-card relative rounded-2xl border border-white/10 bg-white/5 overflow-hidden"
    data-product-id="<?php echo e($data['id'] ?? ''); ?>"
    data-update-url="<?php echo e($updateUrl); ?>"
    data-delete-url="<?php echo e($deleteUrl); ?>"
    data-is-draft="<?php echo e($isDraft ? 1 : 0); ?>">
    <div class="absolute inset-0 pointer-events-none opacity-[0.06] bg-grid-pattern"></div>

    <div class="p-5">
        <div class="hidden mb-3 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200"
            data-error-box>
        </div>
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
                <div class="text-xs px-2 py-1 rounded-full bg-white/10 border border-white/10 text-gray-300">
                    S.NO <span data-sno><?php echo e($sno); ?></span>
                </div>

                <div class="text-sm text-gray-400">
                    <span class="mr-2">Images: <span class="text-gray-200 font-semibold" data-images-count><?php echo e($data['images_count']); ?></span></span>
                    <span>•</span>
                    <span class="ml-2">Status:
                        <span class="font-semibold <?php echo e($data['is_active'] ? 'text-emerald-300' : 'text-yellow-300'); ?>" data-status-pill>
                            <?php echo e($data['is_active'] ? 'Active' : 'Draft'); ?>

                        </span>
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <?php
                $showEditDelete = !$isDraft;
                $showSaveCancel = $isDraft;
                ?>

                
                <button type="button"
                    class="relative group icon-btn inline-flex items-center justify-center px-3 py-2 rounded-xl bg-white/10 hover:bg-white/20 <?php echo e($showEditDelete ? '' : 'hidden'); ?>"
                    data-edit>
                    <i class="bi bi-pencil-square"></i>
                    <span class="pointer-events-none absolute left-1/2 -translate-x-1/2 top-full mt-2
      whitespace-nowrap rounded-lg bg-black/70 backdrop-blur px-2 py-1 text-xs text-white
      opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 transition">
                        Edit
                    </span>
                </button>

                
                <button type="button"
                    class="relative group icon-btn inline-flex items-center justify-center px-3 py-2 rounded-xl bg-emerald-500/15 border border-emerald-500/30
           text-emerald-200 hover:bg-emerald-500/25 <?php echo e($showSaveCancel ? '' : 'hidden'); ?>"
                    data-save>
                    <i class="bi bi-check2-circle"></i>
                    <span class="pointer-events-none absolute left-1/2 -translate-x-1/2 top-full mt-2
      whitespace-nowrap rounded-lg bg-black/70 backdrop-blur px-2 py-1 text-xs text-white
      opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 transition">
                        Save
                    </span>
                </button>

                
                <button type="button"
                    class="relative group icon-btn inline-flex items-center justify-center px-3 py-2 rounded-xl bg-white/10 hover:bg-white/20 <?php echo e($showSaveCancel ? '' : 'hidden'); ?>"
                    data-cancel>
                    <i class="bi bi-x-circle"></i>
                    <span class="pointer-events-none absolute left-1/2 -translate-x-1/2 top-full mt-2
      whitespace-nowrap rounded-lg bg-black/70 backdrop-blur px-2 py-1 text-xs text-white
      opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 transition">
                        Cancel
                    </span>
                </button>

                
                <button type="button"
                    class="relative group icon-btn inline-flex items-center justify-center px-3 py-2 rounded-xl bg-red-500/15 border border-red-500/30
           text-red-200 hover:bg-red-500/25 <?php echo e($showEditDelete ? '' : 'hidden'); ?>"
                    data-delete>
                    <i class="bi bi-trash3"></i>
                    <span class="pointer-events-none absolute left-1/2 -translate-x-1/2 top-full mt-2
      whitespace-nowrap rounded-lg bg-black/70 backdrop-blur px-2 py-1 text-xs text-white
      opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 transition">
                        Delete
                    </span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            
            <div class="lg:col-span-3">
                <div class="relative rounded-2xl border border-white/10 bg-black/20 overflow-hidden aspect-square group" data-gallery>
                    <img data-img
                        src="<?php echo e($data['primary_image_url'] ?? $placeholder); ?>"
                        class="w-full h-full object-cover"
                        onerror="this.onerror=null;this.src='<?php echo e($placeholder); ?>';"
                        alt="">

                    
                    <button type="button"
                        class="absolute left-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 backdrop-blur-sm
               border border-white/10 flex items-center justify-center text-white hover:bg-brand-accent hover:text-black
               transition-all opacity-0 group-hover:opacity-100"
                        data-gallery-prev>
                        <i class="bi bi-chevron-left"></i>
                    </button>

                    
                    <button type="button"
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 backdrop-blur-sm
               border border-white/10 flex items-center justify-center text-white hover:bg-brand-accent hover:text-black
               transition-all opacity-0 group-hover:opacity-100"
                        data-gallery-next>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                <div class="mt-3 hidden" data-edit-area>
                    <label class="text-xs text-gray-400">Add images</label>
                    <input type="file" multiple accept="image/*" data-images class="hidden">

                    <button type="button" data-upload-btn
                        class="mt-2 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl
         bg-white/10 border border-white/10 hover:bg-white/15 text-gray-200">
                        <i class="bi bi-cloud-arrow-up"></i>
                        Upload Images
                    </button>

                    <div class="text-[11px] text-gray-500 mt-1" data-upload-hint>
                        No files selected
                    </div>
                    <div class="text-[11px] text-gray-500 mt-1">First new image becomes primary only if none exists.</div>
                </div>

                <div class="flex gap-2 mt-4 overflow-x-auto overflow-y-visible pr-2 pb-2 pt-3 slim-scroll thumb-strip" data-gallery-thumbs>
                    <?php if(!$isDraft): ?>
                    <?php $__currentLoopData = $p->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $im): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $url = asset('storage/' . ltrim($im->image_path, '/')); ?>

                    <div class="relative w-14 h-14 shrink-0 overflow-visible pr-16" data-thumb data-image-id="<?php echo e($im->id); ?>">
                        <button type="button"
                            class="w-14 h-14 rounded-xl overflow-hidden border-2 <?php echo e($im->is_primary ? 'border-brand-accent' : 'border-white/10'); ?> hover:border-brand-accent transition"
                            data-gallery-thumb
                            data-img="<?php echo e($url); ?>"
                            data-image-id="<?php echo e($im->id); ?>">
                            <img src="<?php echo e($url); ?>" class="w-full h-full object-cover">
                        </button>

                        <div class="absolute -top-2 -right-2 hidden gap-1" data-thumb-actions>
                            <?php if($im->is_primary): ?>
                            <span class="w-6 h-6 rounded-full bg-black/70 border border-white/10 flex items-center justify-center" title="Main image">
                                <i class="bi bi-star-fill text-yellow-400 text-[12px]"></i>
                            </span>
                            <?php else: ?>
                            <button type="button"
                                class="w-6 h-6 rounded-full bg-black/70 border border-white/10 flex items-center justify-center"
                                title="Set Main"
                                data-set-primary
                                data-image-id="<?php echo e($im->id); ?>">
                                <i class="bi bi-star text-white text-[12px]"></i>
                            </button>
                            <?php endif; ?>

                            <button type="button"
                                class="w-6 h-6 rounded-full bg-red-500/70 border border-white/10 text-white text-[10px] flex items-center justify-center"
                                title="Delete"
                                data-del-image
                                data-image-id="<?php echo e($im->id); ?>">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>

            </div>

            
            <div class="lg:col-span-9">
                
                <div data-view-area>
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-2xl font-display font-bold leading-tight" data-view-name><?php echo e($data['name'] ?: 'New Product'); ?></div>
                            <div class="text-sm text-gray-400">
                                <span data-view-brand><?php echo e($data['brand'] ?: '—'); ?></span> •
                                <span data-view-sku><?php echo e($data['sku'] ?: '—'); ?></span> •
                                <span class="text-gray-300 font-semibold" data-view-condition><?php echo e(ucfirst($data['condition'] ?? '—')); ?></span> •
                                <span class="text-gray-300" data-view-product_type><?php echo e(($data['product_type'] ?? 'component') === 'custom_pc' ? 'Custom PC' : 'Component'); ?></span>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-xl font-bold text-brand-accent" data-view-price>
                                AED <?php echo e(is_numeric($data['price']) ? number_format($data['price'],2) : ($data['price'] ?: '—')); ?>

                            </div>
                            <?php if($data['compare_at_price']): ?>
                            <div class="text-sm text-gray-500 line-through" data-view-compare>
                                AED <?php echo e(is_numeric($data['compare_at_price']) ? number_format($data['compare_at_price'],2) : $data['compare_at_price']); ?>

                            </div>
                            <?php endif; ?>
                            <div class="text-xs text-gray-400 mt-1">Stock: <span class="text-gray-200 font-semibold" data-view-stock><?php echo e($data['stock_qty']); ?></span></div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-3">
                        <?php $__currentLoopData = [
                        ['Badge', 'badge_text'],
                        ['Rating', 'rating'],
                        ['Rating Count', 'rating_count'],
                        ['Delivery', 'delivery_text'],
                        ['Discount %', 'discount_percent'],
                        ['Category ID', 'category_id'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-xl border border-white/10 bg-white/[0.03] p-3">
                            <div class="text-xs text-gray-500"><?php echo e($field[0]); ?></div>
                            <div class="text-sm font-semibold text-gray-200" data-view="<?php echo e($field[1]); ?>"><?php echo e($data[$field[1]] ?: '—'); ?></div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="mt-4 rounded-xl border border-white/10 bg-white/[0.03] p-4">
                        <div class="text-xs text-gray-500 mb-1">Short Description</div>
                        <div class="text-sm text-gray-200 whitespace-pre-line" data-view-short_description><?php echo e($data['short_description'] ?: '—'); ?></div>
                    </div>

                    <div class="mt-3 rounded-xl border border-white/10 bg-white/[0.03] p-4">
                        <div class="text-xs text-gray-500 mb-1">Description</div>
                        <div class="text-sm text-gray-200 whitespace-pre-line" data-view-description><?php echo e($data['description'] ?: '—'); ?></div>
                    </div>
                </div>

                
                <div class="hidden" data-edit-area>
                    <form data-form>
                        <?php echo csrf_field(); ?>
                        <?php if(!$isDraft): ?>
                        <?php echo method_field('PUT'); ?>
                        <?php endif; ?>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="text-xs text-gray-400">Name *</label>
                                <input name="name" value="<?php echo e($data['name']); ?>"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Brand</label>
                                <input name="brand" value="<?php echo e($data['brand']); ?>"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">SKU</label>
                                <input name="sku" value="<?php echo e($data['sku']); ?>"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Condition</label>
                                <select name="condition"
                                    class="mt-1 w-full rounded-xl bg-[#0b1220] border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                                    <?php $__currentLoopData = ['new'=>'New','used'=>'Used','refurbished'=>'Refurbished']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($k); ?>" <?php if(($data['condition'] ?? 'new' )===$k): echo 'selected'; endif; ?>><?php echo e($v); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Category</label>
                                <select name="category_id"
                                    class="mt-1 w-full rounded-xl bg-[#0b1220] border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                                    <option value="">— None —</option>

                                    <?php $__currentLoopData = ($categories ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c->id); ?>" <?php if((string)($data['category_id'] ?? '' )===(string)$c->id): echo 'selected'; endif; ?>>
                                        <?php echo e($c->name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Badge</label>
                                <input name="badge_text" value="<?php echo e($data['badge_text']); ?>"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>
                        </div>

                        <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="text-xs text-gray-400">Price (AED) *</label>
                                <input type="number" step="0.01" name="price" value="<?php echo e($data['price']); ?>"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Compare at</label>
                                <input type="number" step="0.01" name="compare_at_price" value="<?php echo e($data['compare_at_price']); ?>"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Stock</label>
                                <input type="number" name="stock_qty" value="<?php echo e($data['stock_qty']); ?>"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Rating *</label>
                                <input type="number" step="0.1" name="rating" value="<?php echo e($data['rating']); ?>"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Rating count</label>
                                <input type="number" name="rating_count" value="<?php echo e($data['rating_count']); ?>"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Discount %</label>
                                <input type="number" step="0.01" name="discount_percent" value="<?php echo e($data['discount_percent']); ?>"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs text-gray-400">Delivery text</label>
                                <input name="delivery_text" value="<?php echo e($data['delivery_text']); ?>"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div class="flex items-center gap-2 mt-6 md:mt-0">
                                <input id="active-<?php echo e($domKey); ?>" type="checkbox" name="is_active" value="1" <?php if($data['is_active']): echo 'checked'; endif; ?>
                                    class="w-4 h-4">

                                <label for="active-<?php echo e($domKey); ?>" class="text-sm text-gray-300">Active</label>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="text-xs text-gray-400">Short Description</label>
                            <textarea name="short_description" rows="2"
                                class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent"><?php echo e($data['short_description']); ?></textarea>
                        </div>

                        <div class="mt-3">
                            <label class="text-xs text-gray-400">Description</label>
                            <textarea name="description" rows="4"
                                class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent"><?php echo e($data['description']); ?></textarea>
                        </div>

                        
                        <div class="mt-4">
                            <div class="text-sm text-white/70 mb-2">Product Video (optional)</div>

                            <?php if(!$isDraft && $p->video_path): ?>
                            <div class="text-xs text-emerald-300 mb-2">
                                Video uploaded
                            </div>
                            <?php endif; ?>

                            <input type="file"
                                name="video"
                                accept="video/mp4,video/webm,video/quicktime"
                                data-video
                                data-has-video="<?php echo e((!$isDraft && $p->video_path) ? 1 : 0); ?>"
                                class="block w-full text-sm text-white/80 file:mr-3 file:px-4 file:py-2 file:rounded-xl file:border-0 file:bg-white/10 file:text-white hover:file:bg-white/15">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</article><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/admin/partials/product_card.blade.php ENDPATH**/ ?>