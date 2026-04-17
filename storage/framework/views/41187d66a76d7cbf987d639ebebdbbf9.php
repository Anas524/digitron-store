

<?php $__env->startSection('title', 'Settings | Digitron Admin'); ?>

<?php $__env->startSection('content'); ?>

<div class="max-w-4xl mx-auto">

    <h1 class="text-2xl font-bold mb-6 text-white">Store Settings</h1>

    <?php if(session('success')): ?>
    <div class="mb-4 p-4 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="space-y-6">
        <?php echo csrf_field(); ?>

        
        <div class="glass-panel p-6 rounded-2xl border border-white/10">
            <h2 class="font-bold text-white mb-4">Tax Settings</h2>

            <label class="block text-sm text-gray-400 mb-2">Tax Percentage (%)</label>
            <input type="number" step="0.01"
                name="tax_percent"
                value="<?php echo e($tax); ?>"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-brand-accent focus:outline-none">
        </div>

        
        <div class="glass-panel p-6 rounded-2xl border border-white/10">
            <h2 class="font-bold text-white mb-4">Shipping Settings</h2>

            <label class="block text-sm text-gray-400 mb-2">Shipping Type</label>
            <select name="shipping_type"
                class="w-full px-4 py-3 rounded-xl bg-[#111827] text-white border border-white/10 focus:border-brand-accent focus:outline-none appearance-none"
                style="color-scheme: dark;">
                <option value="free" <?php echo e($shipping_type=='free'?'selected':''); ?>>Free Shipping</option>
                <option value="flat" <?php echo e($shipping_type=='flat'?'selected':''); ?>>Flat Rate</option>
                <option value="conditional" <?php echo e($shipping_type=='conditional'?'selected':''); ?>>Free Above Amount</option>
            </select>

            <div class="mt-4">
                <label class="block text-sm text-gray-400 mb-2">Flat Shipping Rate (AED)</label>
                <input type="number" step="0.01"
                    name="shipping_flat_rate"
                    value="<?php echo e($flat_rate); ?>"
                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-brand-accent focus:outline-none">
            </div>

            <div class="mt-4">
                <label class="block text-sm text-gray-400 mb-2">Free Shipping Minimum (AED)</label>
                <input type="number" step="0.01"
                    name="free_shipping_min"
                    value="<?php echo e($free_min); ?>"
                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-brand-accent focus:outline-none">
            </div>
        </div>

        
        <div class="text-right">
            <button type="submit"
                class="px-6 py-3 rounded-xl bg-gradient-to-r from-brand-accent to-brand-secondary text-black font-bold hover:shadow-lg transition-all">
                Save Settings
            </button>
        </div>

    </form>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>