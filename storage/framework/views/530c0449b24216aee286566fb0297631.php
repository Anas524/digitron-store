
<?php $__env->startSection('title','Admin | Quotes'); ?>
<?php $__env->startSection('page','admin-quotes'); ?>

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
          <h1 class="text-2xl font-display font-black leading-tight">Quotes</h1>
          <p class="text-sm text-gray-400 truncate">Customer quote requests</p>
        </div>
      </div>

      <div class="text-xs text-gray-400 shrink-0">
        Total: <span class="text-white font-semibold"><?php echo e($quotes->total()); ?></span>
      </div>
    </div>
  </div>

  <section id="page-admin-quotes" class="py-0 lg:py-10">
    <div class="hidden lg:flex items-end justify-between gap-4 mb-6">
      <div>
        <h1 class="text-3xl font-display font-black">Quotes</h1>
        <p class="text-gray-400 text-sm">Customer quote requests</p>
      </div>
      <div class="text-sm text-gray-400">
        Total: <span class="text-white font-semibold"><?php echo e($quotes->total()); ?></span>
      </div>
    </div>

    <div class="glass-panel rounded-2xl border border-white/10 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-white/5 text-gray-300">
            <tr>
              <th class="text-left px-4 py-3">Name</th>
              <th class="text-left px-4 py-3">Email</th>
              <th class="text-left px-4 py-3">Phone</th>
              <th class="text-left px-4 py-3">Area</th>
              <th class="text-left px-4 py-3">Type</th>
              <th class="text-left px-4 py-3">Budget</th>
              <th class="text-left px-4 py-3">Files</th>
              <th class="text-left px-4 py-3">Status</th>
              <th class="text-left px-4 py-3">Date</th>
              <th class="text-left px-4 py-3">Action</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-white/10">
            <?php $__empty_1 = true; $__currentLoopData = $quotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
              $detailsArr = $q->details ? json_decode($q->details, true) : null;
            ?>

            <tr class="hover:bg-white/[0.03]">
              <td class="px-4 py-3 text-white whitespace-nowrap"><?php echo e($q->full_name); ?></td>
              <td class="px-4 py-3 text-gray-300 whitespace-nowrap"><?php echo e($q->email); ?></td>
              <td class="px-4 py-3 text-gray-400 whitespace-nowrap"><?php echo e($q->phone); ?></td>
              <td class="px-4 py-3 text-gray-400 whitespace-nowrap"><?php echo e($q->area); ?></td>
              <td class="px-4 py-3 text-gray-400 whitespace-nowrap"><?php echo e($q->quote_type); ?></td>
              <td class="px-4 py-3 text-gray-400 whitespace-nowrap">
                <?php if(!is_null($q->budget)): ?>
                <?php echo e(number_format((float)$q->budget, 2)); ?>

                <?php else: ?>
                —
                <?php endif; ?>
              </td>

              <td class="px-4 py-3 text-gray-400 whitespace-nowrap">
                <?php echo e($q->att_count ? $q->att_count.' file(s)' : '—'); ?>

              </td>

              <td class="px-4 py-3 whitespace-nowrap">
                <span class="px-2 py-1 rounded-full text-xs font-bold
                    <?php echo e($q->status === 'new' ? 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30' : 'bg-green-500/20 text-green-300 border border-green-500/30'); ?>">
                  <?php echo e(strtoupper($q->status)); ?>

                </span>
              </td>

              <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                <?php echo e(\Carbon\Carbon::parse($q->created_at)->format('d M Y, h:i A')); ?>

              </td>

              <td class="px-4 py-3">
                <?php
                  $payload = [
                    "id" => $q->id,
                    "name" => $q->full_name,
                    "email" => $q->email,
                    "phone" => $q->phone,
                    "area" => $q->area,
                    "type" => $q->quote_type,
                    "budget" => $q->budget,
                    "message" => $q->message,
                    "details" => $detailsArr,
                    "date" => \Carbon\Carbon::parse($q->created_at)->format("d M Y, h:i A"),
                  ];
                ?>

                <div class="flex items-center gap-2 whitespace-nowrap">
                  <button
                    type="button"
                    class="quote-view-btn px-3 py-1.5 rounded-lg border border-white/10 bg-white/5 hover:bg-white/10 text-white text-xs"
                    data-quote='<?php echo json_encode($payload, 15, 512) ?>'>
                    View
                  </button>

                  <button
                    type="button"
                    class="quote-del-btn px-3 py-1.5 rounded-lg border border-red-500/30 bg-red-500/10 hover:bg-red-500/20 text-red-200 text-xs"
                    data-id="<?php echo e($q->id); ?>">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="11" class="px-4 py-8 text-center text-gray-400">No quotes yet.</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-6">
      <?php echo e($quotes->links()); ?>

    </div>
  </section>

  
  <div id="quoteModal" class="fixed inset-0 z-50 hidden">
    <div id="quoteModalBackdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    <div class="relative max-w-3xl mx-auto mt-6 sm:mt-16 p-3 sm:p-0">
      <div class="glass-panel rounded-2xl border border-white/10 overflow-hidden">
        <div class="flex items-center justify-between px-4 sm:px-6 py-4 bg-white/5 border-b border-white/10">
          <div>
            <div class="text-white font-bold text-lg" id="qmTitle">Quote</div>
            <div class="text-gray-400 text-xs" id="qmSub">—</div>
          </div>
          <button type="button" class="quote-modal-close text-gray-300 hover:text-white">✕</button>
        </div>

        <div class="p-4 sm:p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
          <div class="grid md:grid-cols-2 gap-4 text-sm">
            <div class="text-gray-300"><span class="text-gray-500">Name:</span> <span id="qmName"></span></div>
            <div class="text-gray-300 break-all"><span class="text-gray-500">Email:</span> <span id="qmEmail"></span></div>
            <div class="text-gray-300"><span class="text-gray-500">Phone:</span> <span id="qmPhone"></span></div>
            <div class="text-gray-300"><span class="text-gray-500">Area:</span> <span id="qmArea"></span></div>
            <div class="text-gray-300"><span class="text-gray-500">Type:</span> <span id="qmType"></span></div>
            <div class="text-gray-300"><span class="text-gray-500">Budget:</span> <span id="qmBudget"></span></div>
          </div>

          <div>
            <div class="text-gray-400 text-xs mb-1">Message</div>
            <div class="rounded-xl border border-white/10 bg-white/5 p-4 text-gray-200 text-sm whitespace-pre-wrap" id="qmMessage">—</div>
          </div>

          <div>
            <div class="text-gray-400 text-xs mb-2">Primary Use Case</div>

            <div id="qmUseCaseWrap" class="flex flex-wrap gap-2">
              <span class="text-gray-500 text-sm">—</span>
            </div>
          </div>

          <div class="mt-4">
            <div class="text-gray-400 text-xs mb-1">Attachments</div>
            <div id="qmFiles" class="space-y-2 text-sm text-gray-200">
              <div class="text-gray-500 text-sm">—</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="quoteDelModal" class="fixed inset-0 z-50 hidden">
    <div id="quoteDelBackdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    <div class="relative max-w-md mx-auto mt-24 sm:mt-40 p-3 sm:p-0">
      <div class="glass-panel rounded-2xl border border-white/10 overflow-hidden">
        <div class="px-6 py-4 bg-white/5 border-b border-white/10 flex items-center justify-between">
          <div class="text-white font-bold">Delete Quote</div>
          <button type="button" class="quote-del-close text-gray-300 hover:text-white">✕</button>
        </div>

        <div class="p-6">
          <p class="text-gray-300 text-sm">
            Are you sure you want to delete this quote? This will also remove all attached files.
          </p>

          <div class="mt-6 flex flex-col sm:flex-row justify-end gap-2">
            <button type="button"
              class="quote-del-close px-4 py-2 rounded-lg border border-white/10 bg-white/5 hover:bg-white/10 text-white text-sm">
              Cancel
            </button>

            <button type="button" id="quoteDelConfirm"
              class="px-4 py-2 rounded-lg border border-red-500/30 bg-red-500/15 hover:bg-red-500/25 text-red-100 text-sm font-semibold">
              Yes, Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/admin/quotes/index.blade.php ENDPATH**/ ?>