<?php if(auth()->guard()->check()): ?>
<?php if(!auth()->user()->is_admin): ?>
<?php
$cartCount = (int)($cartCount ?? 0);
$wishlistCount = (int)($wishlistCount ?? 0);
?>

<div
    x-data
    x-cloak
    x-show="$store.account.open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9998]"
    @keydown.escape.window="$store.account.closePanel()">
    
    <div
        x-cloak
        class="fixed inset-0 bg-black/60 backdrop-blur-sm"
        x-show="$store.account.open"
        x-transition.opacity
        @click="$store.account.closePanel()"></div>

    
    <aside
        class="dc-account-panel transition-all duration-300 ease-out"
        :class="$store.account.open ? 'is-open' : ''"
        @click.stop>
        <div class="dc-account-shell">

            <div class="dc-account-head">
                <div>
                    <div class="dc-account-title">My Account</div>
                    <div class="dc-account-sub">Manage your profile & shopping</div>
                </div>

                <button
                    type="button"
                    class="dc-account-close"
                    @click="$store.account.closePanel()"
                    aria-label="Close">
                    ✕
                </button>
            </div>

            <div class="dc-account-body">
                <div class="dc-account-user">
                    <div class="dc-account-avatar"><i class="bi bi-person"></i></div>
                    <div>
                        <div class="dc-account-name"><?php echo e(auth()->user()->name); ?></div>
                        <div class="dc-account-email"><?php echo e(auth()->user()->email); ?></div>
                    </div>
                </div>

                <div class="dc-account-stats">
                    <a href="/wishlist" class="dc-stat">
                        <div class="dc-stat-label">Wishlist</div>
                        <div class="dc-stat-value"><?php echo e($wishlistCount); ?></div>
                    </a>
                    <a href="/cart" class="dc-stat">
                        <div class="dc-stat-label">Cart</div>
                        <div class="dc-stat-value"><?php echo e($cartCount); ?></div>
                    </a>
                </div>

                <div class="dc-account-links">
                    <a href="<?php echo e(route('account')); ?>" class="dc-linkrow">
                        <i class="bi bi-person"></i>
                        <span>Profile</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                    <a href="<?php echo e(route('my.orders')); ?>" class="dc-linkrow">
                        <i class="bi bi-receipt"></i>
                        <span>My Orders</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                    <a href="<?php echo e(route('addresses')); ?>" class="dc-linkrow">
                        <i class="bi bi-geo-alt"></i>
                        <span>Delivery Address</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>

                <form method="POST" action="<?php echo e(route('customer.logout')); ?>" class="mt-6">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="dc-logout">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>
</div>
<?php endif; ?>
<?php endif; ?><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/partials/account-sidebar.blade.php ENDPATH**/ ?>