

<?php $__env->startSection('title', 'Order Complete | Digitron Computers UAE'); ?>
<?php $__env->startSection('page', 'order-complete'); ?>

<?php $__env->startSection('fullwidth'); ?>
<section class="relative h-[32vh] min-h-[240px] overflow-hidden flex items-center justify-center pt-28 sm:pt-32 md:pt-36 pb-16">
    <div class="absolute inset-0 w-full h-full z-0">
        <img src="<?php echo e(asset('images/slide1.jpg')); ?>" alt="Order Success" class="w-full h-full object-cover opacity-30 scale-110">
        <div class="absolute inset-0 bg-gradient-to-b from-[#070A12]/20 via-[#070A12]/45 to-[#070A12]/70"></div>
    </div>

    <div class="relative z-10 text-center px-4">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-emerald-400/30 bg-emerald-400/10 mb-5">
            <i class="bi bi-check-circle text-emerald-400"></i>
            <span class="text-emerald-400 text-sm font-bold uppercase tracking-wider">Order Placed</span>
        </div>

        <h1 class="text-4xl md:text-6xl font-display font-black mb-3 tracking-tight">
            ORDER <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-brand-accent">COMPLETE</span>
        </h1>

        <p class="text-base md:text-lg text-gray-400 max-w-2xl mx-auto">
            Thank you. Your Cash on Delivery order has been placed successfully.
        </p>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="py-12 -mt-8 relative z-20">
    <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 glass-panel rounded-2xl p-6 border border-white/10">
            <h2 class="text-2xl font-bold mb-4 text-white">Order Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                <div>
                    <div class="text-gray-500">Order Number</div>
                    <div class="text-white font-semibold"><?php echo e($order->order_number); ?></div>
                </div>
                <div>
                    <div class="text-gray-500">Payment Method</div>
                    <div class="text-white font-semibold"><?php echo e(ucwords(str_replace('_', ' ', $order->payment_method))); ?></div>
                </div>
                <div>
                    <div class="text-gray-500">Payment Status</div>
                    <div class="text-white font-semibold"><?php echo e(ucfirst($order->payment_status)); ?></div>
                </div>
                <div>
                    <div class="text-gray-500">Order Status</div>
                    <div class="text-white font-semibold"><?php echo e(ucfirst($order->order_status)); ?></div>
                </div>
            </div>

            <div class="border-t border-white/10 pt-4">
                <h3 class="text-lg font-bold mb-4">Shipping Information</h3>
                <div class="space-y-2 text-sm text-gray-300">
                    <div><span class="text-gray-500">Name:</span> <?php echo e($order->full_name); ?></div>
                    <div><span class="text-gray-500">Email:</span> <?php echo e($order->email); ?></div>
                    <div><span class="text-gray-500">Phone:</span> <?php echo e($order->phone); ?></div>
                    <div><span class="text-gray-500">City:</span> <?php echo e($order->city); ?></div>
                    <div><span class="text-gray-500">Address:</span> <?php echo e($order->address); ?></div>
                </div>
            </div>
        </div>

        <div class="glass-panel rounded-2xl p-6 border border-white/10">
            <h3 class="text-xl font-bold mb-4">Summary</h3>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between text-gray-400">
                    <span>Subtotal</span>
                    <span>AED <?php echo e(number_format($order->subtotal, 2)); ?></span>
                </div>
                <div class="flex justify-between text-gray-400">
                    <span>Tax</span>
                    <span>AED <?php echo e(number_format($order->tax, 2)); ?></span>
                </div>
                <div class="flex justify-between text-white text-lg font-bold border-t border-white/10 pt-3">
                    <span>Total</span>
                    <span>AED <?php echo e(number_format($order->total_amount, 2)); ?></span>
                </div>
            </div>

            <div class="mt-6 space-y-3">
                <a href="<?php echo e(route('my.orders')); ?>" class="w-full inline-flex items-center justify-center py-3 rounded-xl bg-brand-accent text-black font-bold">
                    View My Orders
                </a>
                <a href="<?php echo e(route('shop')); ?>" class="w-full inline-flex items-center justify-center py-3 rounded-xl border border-white/10 text-white hover:bg-white/5">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/pages/shop/complete.blade.php ENDPATH**/ ?>