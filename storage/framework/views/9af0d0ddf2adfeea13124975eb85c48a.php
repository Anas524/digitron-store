

<?php $__env->startSection('content'); ?>
<div class="relative min-h-full">
    <!-- Mobile top bar -->
    <div class="lg:hidden sticky top-0 z-20 glass-panel border-b border-white/10 px-4 py-4 mb-4">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <button
                    type="button"
                    class="w-11 h-11 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 transition shrink-0"
                    @click.stop="sidebarOpen = true"
                    aria-label="Open admin sidebar">
                    <i class="bi bi-list text-xl"></i>
                </button>

                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-white leading-tight">Home Showcase</h1>
                    <p class="text-sm text-gray-400 truncate">
                        Manage homepage showcase posters.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-8">
        <div class="hidden lg:flex items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white">Home Showcase</h1>
                <p class="mt-2 text-gray-400">
                    Upload homepage showcase posters, enable or disable them, and remove old ones.
                </p>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-emerald-300">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-red-300">
                <ul class="space-y-1 text-sm">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>• <?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 md:p-7">
            <div class="mb-5">
                <h2 class="text-xl font-bold text-white">Upload New Poster</h2>
                <p class="mt-1 text-sm text-gray-400">
                    Recommended: wide banner image for the homepage slider.
                </p>
            </div>

            <form action="<?php echo e(route('admin.home-showcase.store')); ?>"
                  method="POST"
                  enctype="multipart/form-data"
                  class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <?php echo csrf_field(); ?>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-300">Poster Image</label>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="input-style" required>
                </div>

                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-3 text-white">
                        <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 rounded border-white/20 bg-transparent text-brand-accent focus:ring-brand-accent">
                        <span>Active</span>
                    </label>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-brand-accent px-6 py-3 font-bold text-black transition hover:opacity-90">
                        <i class="bi bi-upload"></i>
                        Upload Poster
                    </button>
                </div>
            </form>
        </div>

        
        <div>
            <div class="mb-5 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white">Existing Posters</h2>
                    <p class="mt-1 text-sm text-gray-400">
                        Update image, change active status, or delete a poster.
                    </p>
                </div>
            </div>

            <?php if($slides->isEmpty()): ?>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-8 text-center text-gray-400">
                    No showcase posters uploaded yet.
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                    <?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5">
                            <div class="relative">
                                <img src="<?php echo e(asset('storage/' . $slide->image_path)); ?>"
                                     alt="Showcase Poster"
                                     class="h-56 sm:h-64 w-full object-cover object-center">

                                <div class="absolute left-4 top-4">
                                    <?php if($slide->is_active): ?>
                                        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-500/30 bg-emerald-500/15 px-3 py-1 text-xs font-semibold text-emerald-300">
                                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                            Active
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-2 rounded-full border border-gray-500/30 bg-gray-500/15 px-3 py-1 text-xs font-semibold text-gray-300">
                                            <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                                            Inactive
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="p-6">
                                <form action="<?php echo e(route('admin.home-showcase.update', $slide)); ?>"
                                      method="POST"
                                      enctype="multipart/form-data"
                                      class="space-y-5">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-300">Replace Image</label>
                                        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="input-style">
                                    </div>

                                    <div>
                                        <label class="inline-flex items-center gap-3 text-white">
                                            <input type="checkbox"
                                                   name="is_active"
                                                   value="1"
                                                   <?php echo e($slide->is_active ? 'checked' : ''); ?>

                                                   class="h-4 w-4 rounded border-white/20 bg-transparent text-brand-accent focus:ring-brand-accent">
                                            <span>Active</span>
                                        </label>
                                    </div>

                                    <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-accent px-5 py-2.5 font-bold text-black transition hover:opacity-90">
                                            <i class="bi bi-save"></i>
                                            Save Changes
                                        </button>
                                </form>

                                        <form action="<?php echo e(route('admin.home-showcase.destroy', $slide)); ?>"
                                              method="POST"
                                              onsubmit="return confirm('Delete this poster?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-500/30 bg-red-500/15 px-5 py-2.5 font-bold text-red-300 transition hover:bg-red-500/20">
                                                <i class="bi bi-trash3"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/admin/home_showcase/index.blade.php ENDPATH**/ ?>