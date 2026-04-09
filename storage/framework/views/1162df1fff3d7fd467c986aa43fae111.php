

<?php $__env->startSection('title', 'Orders | Digitron Command Center'); ?>

<?php $__env->startSection('content'); ?>
<div class="relative min-h-full text-white">
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
                    <h1 class="text-2xl font-bold leading-tight">Orders</h1>
                    <p class="text-sm text-gray-400 truncate">Manage customer orders and COD requests.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-0 lg:p-8">
        <div class="hidden lg:block mb-6">
            <h1 class="text-2xl font-bold">Orders</h1>
            <p class="text-sm text-gray-400">Manage customer orders and Cash on Delivery requests.</p>
        </div>

        <div class="glass-panel rounded-2xl border border-white/10 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px]">
                    <thead>
                        <tr class="text-slate-400 text-sm border-b border-white/10">
                            <th class="px-5 py-4 text-left">Order No</th>
                            <th class="px-5 py-4 text-left">Customer</th>
                            <th class="px-5 py-4 text-left">Phone</th>
                            <th class="px-5 py-4 text-left">City</th>
                            <th class="px-5 py-4 text-left">Total</th>
                            <th class="px-5 py-4 text-left">Payment</th>
                            <th class="px-5 py-4 text-left">Status</th>
                            <th class="px-5 py-4 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b border-white/5 cursor-pointer order-row hover:bg-white/[0.03]" data-order-id="<?php echo e($order->id); ?>">
                            <td class="px-5 py-4 font-semibold text-white whitespace-nowrap"><?php echo e($order->order_number); ?></td>

                            <td class="px-5 py-4">
                                <div class="font-medium text-white whitespace-nowrap"><?php echo e($order->full_name); ?></div>
                                <div class="text-sm text-slate-400 whitespace-nowrap"><?php echo e($order->email); ?></div>
                            </td>

                            <td class="px-5 py-4 text-white whitespace-nowrap"><?php echo e($order->phone); ?></td>
                            <td class="px-5 py-4 text-white whitespace-nowrap"><?php echo e($order->city); ?></td>
                            <td class="px-5 py-4 font-semibold text-white whitespace-nowrap">AED <?php echo e(number_format($order->total_amount, 2)); ?></td>
                            <td class="px-5 py-4 text-white whitespace-nowrap"><?php echo e(ucwords(str_replace('_', ' ', $order->payment_method))); ?></td>

                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                    <?php if($order->order_status === 'pending'): ?> bg-yellow-500/20 text-yellow-400
                                    <?php elseif($order->order_status === 'processing'): ?> bg-blue-500/20 text-blue-400
                                    <?php elseif($order->order_status === 'shipped'): ?> bg-indigo-500/20 text-indigo-400
                                    <?php elseif($order->order_status === 'delivered'): ?> bg-green-500/20 text-green-400
                                    <?php elseif($order->order_status === 'cancelled'): ?> bg-red-500/20 text-red-400
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($order->order_status)); ?>

                                </span>
                            </td>

                            <td class="px-5 py-4 text-slate-400 whitespace-nowrap"><?php echo e($order->created_at->format('d M Y')); ?></td>
                        </tr>

                        <tr id="order-details-<?php echo e($order->id); ?>" class="hidden bg-white/5">
                            <td colspan="8" class="px-4 sm:px-5 py-5">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <h4 class="text-white font-semibold mb-3">Customer Details</h4>
                                        <div class="space-y-2 text-sm text-slate-300">
                                            <p><span class="text-white">Full Name:</span> <?php echo e($order->full_name); ?></p>
                                            <p class="break-all"><span class="text-white">Email:</span> <?php echo e($order->email); ?></p>
                                            <p><span class="text-white">Phone:</span> <?php echo e($order->phone); ?></p>
                                            <p><span class="text-white">City:</span> <?php echo e($order->city); ?></p>
                                            <p><span class="text-white">Address:</span> <?php echo e($order->address); ?></p>
                                            <p><span class="text-white">Payment Method:</span> <?php echo e(ucwords(str_replace('_', ' ', $order->payment_method))); ?></p>
                                            <p>
                                                <span class="text-white">Payment Status:</span>
                                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                                    <?php echo e($order->payment_status === 'paid' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400'); ?>">
                                                    <?php echo e(ucfirst($order->payment_status)); ?>

                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="text-white font-semibold mb-3">Update Order</h4>

                                        <form method="POST" action="<?php echo e(route('admin.orders.updateStatus', ['order' => $order->id])); ?>" class="space-y-4">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>

                                            <div>
                                                <label class="block text-sm text-slate-300 mb-2">Order Status</label>
                                                <select name="order_status" class="w-full rounded-xl border border-white/10 bg-slate-900 text-white px-4 py-3">
                                                    <option value="pending" <?php echo e($order->order_status === 'pending' ? 'selected' : ''); ?>>Pending</option>
                                                    <option value="processing" <?php echo e($order->order_status === 'processing' ? 'selected' : ''); ?>>Processing</option>
                                                    <option value="shipped" <?php echo e($order->order_status === 'shipped' ? 'selected' : ''); ?>>Shipped</option>
                                                    <option value="delivered" <?php echo e($order->order_status === 'delivered' ? 'selected' : ''); ?>>Delivered</option>
                                                    <option value="cancelled" <?php echo e($order->order_status === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm text-slate-300 mb-2">Payment Status</label>
                                                <select name="payment_status" class="w-full rounded-xl border border-white/10 bg-slate-900 text-white px-4 py-3">
                                                    <option value="unpaid" <?php echo e($order->payment_status === 'unpaid' ? 'selected' : ''); ?>>Unpaid</option>
                                                    <option value="paid" <?php echo e($order->payment_status === 'paid' ? 'selected' : ''); ?>>Paid</option>
                                                </select>
                                            </div>

                                            <button type="submit" class="rounded-xl bg-[#00E5FF] px-5 py-3 font-semibold text-black hover:opacity-90 transition">
                                                Save Update
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <?php if($order->items && $order->items->count()): ?>
                                <div class="mt-6">
                                    <h4 class="text-white font-semibold mb-3">Order Items</h4>
                                    <div class="overflow-x-auto">
                                        <table class="w-full min-w-[650px] text-sm">
                                            <thead>
                                                <tr class="border-b border-white/10 text-slate-400">
                                                    <th class="py-2 text-left">Product</th>
                                                    <th class="py-2 text-left">SKU</th>
                                                    <th class="py-2 text-left">Price</th>
                                                    <th class="py-2 text-left">Qty</th>
                                                    <th class="py-2 text-left">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="border-b border-white/5">
                                                    <td class="py-2 text-white"><?php echo e($item->product_name); ?></td>
                                                    <td class="py-2 text-slate-300"><?php echo e($item->product_sku); ?></td>
                                                    <td class="py-2 text-white whitespace-nowrap">AED <?php echo e(number_format($item->product_price, 2)); ?></td>
                                                    <td class="py-2 text-white"><?php echo e($item->quantity); ?></td>
                                                    <td class="py-2 text-white whitespace-nowrap">AED <?php echo e(number_format($item->subtotal, 2)); ?></td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="px-5 py-8 text-center text-slate-400">No orders found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                <?php echo e($orders->links()); ?>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.order-row').forEach(row => {
            row.addEventListener('click', function() {
                const orderId = this.dataset.orderId;
                const detailRow = document.getElementById('order-details-' + orderId);

                document.querySelectorAll('[id^="order-details-"]').forEach(r => {
                    if (r !== detailRow) r.classList.add('hidden');
                });

                if (detailRow) {
                    detailRow.classList.toggle('hidden');
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>