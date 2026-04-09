
<?php $__env->startSection('title','Admin | Newsletter'); ?>
<?php $__env->startSection('page','admin-newsletter'); ?>

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
          <h1 class="text-2xl font-display font-black leading-tight">Newsletter</h1>
          <p class="text-sm text-gray-400 truncate">All subscribed emails</p>
        </div>
      </div>

      <div class="text-xs text-gray-400 shrink-0">
        Total: <span class="text-white font-semibold"><?php echo e($subs->total()); ?></span>
      </div>
    </div>
  </div>

  <section class="py-0 lg:py-10">
    <div class="hidden lg:flex items-end justify-between gap-4 mb-6">
      <div>
        <h1 class="text-3xl font-display font-black">Newsletter</h1>
        <p class="text-gray-400 text-sm">All subscribed emails</p>
      </div>
      <div class="text-sm text-gray-400">
        Total: <span class="text-white font-semibold"><?php echo e($subs->total()); ?></span>
      </div>
    </div>

    <div class="glass-panel rounded-2xl border border-white/10 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-white/5 text-gray-300">
            <tr>
              <th class="text-left px-4 py-3">Email</th>
              <th class="text-left px-4 py-3">Subscribed</th>
              <th class="text-left px-4 py-3">IP</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/10">
            <?php $__empty_1 = true; $__currentLoopData = $subs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr class="hover:bg-white/[0.03]">
                <td class="px-4 py-3 text-white"><?php echo e($s->email); ?></td>
                <td class="px-4 py-3 text-gray-400 whitespace-nowrap"><?php echo e(\Carbon\Carbon::parse($s->created_at)->format('d M Y, h:i A')); ?></td>
                <td class="px-4 py-3 text-gray-500 whitespace-nowrap"><?php echo e($s->ip_address); ?></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                <td colspan="3" class="px-4 py-8 text-center text-gray-400">No subscriptions yet.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-6">
      <?php echo e($subs->links()); ?>

    </div>
  </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/admin/newsletter/index.blade.php ENDPATH**/ ?>