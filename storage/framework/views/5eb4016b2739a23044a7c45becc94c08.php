<div x-data="{ open: false }" class="relative">
    <header
        id="siteHeader"
        x-data="{
        scrolled:false,
        lastY:0,
        offset:0,
        h:0,

        init(){
            this.lastY = window.scrollY;
            this.h = this.$el.offsetHeight;

            const onScroll = () => {
                const y = window.scrollY;
                const delta = y - this.lastY;

                if (y <= 10) {
                    this.offset = 0;
                    this.scrolled = false;
                    this.lastY = y;
                    return;
                }

                if (delta > 0) {
                    this.offset = Math.min(this.h, this.offset + delta);
                } else if (delta < 0) {
                    this.offset = Math.max(0, this.offset + delta);
                }

                this.scrolled = (y > 10 && this.offset < this.h);
                this.lastY = y;
            };

            window.addEventListener('scroll', onScroll, { passive:true });

            window.addEventListener('resize', () => {
                this.h = this.$el.offsetHeight;
                this.offset = Math.min(this.offset, this.h);
                document.documentElement.style.setProperty('--headerH', this.h + 'px');
            }, { passive:true });
        }
    }"
        x-init="init()"
        :style="open ? '' : ('transform: translateY(-' + offset + 'px)')"
        class="fixed top-0 left-0 right-0 z-50 will-change-transform">
        <?php
        $menuParents = $menuParents ?? collect();
        $megaMenuByParent = $megaMenuByParent ?? collect();
        $conditionLabels = [
        'new' => 'New',
        'used' => 'Used',
        'refurbished' => 'Refurbished',
        ];
        ?>
        <div
            :class="(open || scrolled) ? 'header-glass' : 'header-clear'">
            <div class="pt-4">

                <div class="flex items-center justify-between gap-3 px-4 py-3 sm:px-6">

                    <div class="header-row">
                        
                        <a href="/" class="brand">
                            <img src="<?php echo e(asset('images/logo-cropped.png')); ?>" alt="Digitron Computers UAE" class="brand-logo">

                            <span class="brand-text">
                                <span class="brand-title">
                                    <!-- <span class="bt-main">DIGITRON</span>
                                <span class="bt-sub">COMPUTERS UAE</span> -->
                                    <span class="bt-main text-xl md:text-2xl font-display font-bold mb-2">
                                        DIGITRON <span class="bt-sub text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-brand-secondary">COMPUTERS UAE</span>
                                    </span>
                                </span>
                            </span>
                        </a>

                        
                        <div class="top-actions actions-desktop">
                            
                            <?php if(!request()->routeIs('home')): ?>
                            <a href="<?php echo e(route('home')); ?>" class="icon-btn" aria-label="Home">
                                <i class="bi bi-house-door"></i>
                                <span class="icon-label">Home</span>
                            </a>
                            <?php endif; ?>

                            <a href="<?php echo e(route('about')); ?>" class="icon-btn" aria-label="About">
                                <i class="bi bi-info-circle"></i>
                                <span class="icon-label">About</span>
                            </a>

                            <a href="/shop" class="icon-btn" aria-label="Shop">
                                <i class="bi bi-basket3"></i>
                                <span class="icon-label">Shop</span>
                            </a>

                            <a href="<?php echo e(route('quote')); ?>" class="icon-btn quote-btn" aria-label="Contact Us">
                                <i class="bi bi-headset"></i>
                                <span class="icon-label">Contact Us</span>
                            </a>

                            <a href="/wishlist" class="icon-btn has-badge" aria-label="Wishlist">
                                <i class="bi bi-heart"></i>
                                <!-- <span class="wish-badge wish-count">0</span> -->
                                <span class="icon-label">Wishlist</span>
                            </a>

                            <a href="/cart" class="icon-btn has-badge" aria-label="Cart">
                                <i class="bi bi-cart3"></i>
                                <?php $cartCount = (int)($cartCount ?? 0); ?>
                                <span id="headerCartCount"
                                    class="cart-badge <?php echo e($cartCount <= 0 ? 'hidden' : ''); ?>">
                                    <?php echo e($cartCount > 0 ? $cartCount : ''); ?>

                                </span>
                                <span class="icon-label">Cart</span>
                            </a>

                            <?php
                            $cartCount = (int)($cartCount ?? 0);
                            $wishlistCount = (int)($wishlistCount ?? 0);
                            ?>

                            
                            <?php if(auth()->guard()->check()): ?>
                            <?php if(auth()->user()->is_admin): ?>
                            
                            <div class="relative" x-data="{ accOpen: <?php echo e(session('openAccountDropdown') ? 'true' : 'false'); ?> }">
                                <button type="button" class="icon-btn" aria-label="Account"
                                    @click="accOpen = !accOpen" @keydown.escape.window="accOpen=false">
                                    <i class="bi bi-person-circle"></i>
                                    <span class="icon-label">Account</span>
                                </button>

                                <div x-cloak x-show="accOpen" x-transition.origin.top.right @click.outside="accOpen=false"
                                    class="absolute right-0 mt-3 w-[360px] z-50 rounded-2xl border border-white/10 bg-[#0b1220]/95 backdrop-blur-xl shadow-2xl overflow-hidden">
                                    <div class="p-5">
                                        <div class="text-white font-bold text-lg mb-1">Admin</div>
                                        <div class="text-gray-400 text-sm mb-4">
                                            Logged in as <span class="text-white"><?php echo e(auth()->user()->email); ?></span>
                                        </div>

                                        <a href="<?php echo e(route('admin.dashboard')); ?>"
                                            class="block w-full text-center py-3 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition">
                                            Open Dashboard
                                        </a>

                                        <form method="POST" action="<?php echo e(route('admin.logout')); ?>" class="mt-3">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit"
                                                class="w-full py-3 rounded-xl border border-white/10 text-white hover:bg-white/10 transition">
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            
                            <div class="relative">
                                <button
                                    type="button"
                                    class="icon-btn"
                                    aria-label="Account"
                                    @click.prevent="$store.account.openPanel()">
                                    <i class="bi bi-person-circle"></i>
                                    <span class="icon-label">Account</span>
                                </button>
                            </div>
                            <?php endif; ?>
                            <?php endif; ?>

                            <?php if(auth()->guard()->guest()): ?>
                            
                            <div class="relative"
                                x-data="{
                            accOpen: <?php echo e(session('openAccountPanel') ? 'true' : 'false'); ?>,
                            tab: '<?php echo e(session('accountTab','login')); ?>'
                            }">

                                <button type="button" class="icon-btn" aria-label="Account"
                                    @click="accOpen = !accOpen" @keydown.escape.window="accOpen=false">
                                    <i class="bi bi-person-circle"></i>
                                    <span class="icon-label">Account</span>
                                </button>

                                <div x-cloak x-show="accOpen" x-transition.origin.top.right @click.outside="accOpen=false"
                                    class="absolute right-0 mt-3 w-[360px] z-50 rounded-2xl border border-white/10 bg-[#0b1220]/95 backdrop-blur-xl shadow-2xl overflow-hidden">

                                    <div class="p-5">
                                        <div class="text-white font-bold text-lg mb-1">Account</div>
                                        <div class="text-gray-400 text-sm mb-4">Login or create your account.</div>

                                        <div class="flex gap-2 mb-4">
                                            <button type="button"
                                                class="flex-1 py-2 rounded-xl border border-white/10"
                                                :class="tab==='login' ? 'bg-white/10 text-white' : 'bg-white/5 text-gray-300'"
                                                @click="tab='login'">Login</button>

                                            <button type="button"
                                                class="flex-1 py-2 rounded-xl border border-white/10"
                                                :class="tab==='register' ? 'bg-white/10 text-white' : 'bg-white/5 text-gray-300'"
                                                @click="tab='register'">Register</button>
                                        </div>

                                        
                                        <div x-cloak x-show="tab==='login'">
                                            <form method="POST" action="<?php echo e(route('customer.login')); ?>" class="space-y-3">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="redirect_to" id="desktopLoginRedirectTo">

                                                <?php $__errorArgs = ['login'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="mb-3 rounded-xl border border-red-500/30 bg-red-500/10 text-red-200 text-sm px-4 py-3">
                                                    <?php echo e($message); ?>

                                                </div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                                <div>
                                                    <label class="text-xs text-gray-400">Email</label>
                                                    <input name="email" type="email" required value="<?php echo e(old('email')); ?>"
                                                        class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">
                                                </div>

                                                <div>
                                                    <label class="text-xs text-gray-400">Password</label>
                                                    <input name="password" type="password" required
                                                        class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">
                                                </div>

                                                <label class="inline-flex items-center gap-2 text-sm text-gray-400">
                                                    <input type="checkbox" name="remember" class="rounded border-white/20 bg-white/5">
                                                    Remember me
                                                </label>

                                                <button type="submit"
                                                    class="w-full py-3 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition">
                                                    Login
                                                </button>

                                                <button type="button" class="w-full text-sm text-gray-400 hover:text-white" @click="tab='register'">
                                                    New here? Create an account
                                                </button>
                                            </form>
                                        </div>

                                        
                                        <div x-cloak x-show="tab==='register'">
                                            <form method="POST" action="<?php echo e(route('customer.register')); ?>" class="space-y-3">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="redirect_to" id="desktopRegisterRedirectTo">

                                                <?php if($errors->has('name') || $errors->has('email') || $errors->has('password') || $errors->has('password_confirmation')): ?>
                                                <div class="mb-3 rounded-xl border border-red-500/30 bg-red-500/10 text-red-200 text-sm px-4 py-3">
                                                    <ul class="list-disc ps-4 space-y-1">
                                                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <li><?php echo e($message); ?></li> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <li><?php echo e($message); ?></li> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <li><?php echo e($message); ?></li> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <li><?php echo e($message); ?></li> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </ul>
                                                </div>
                                                <?php endif; ?>

                                                <div>
                                                    <label class="text-xs text-gray-400">Name</label>
                                                    <input name="name" type="text" required value="<?php echo e(old('name')); ?>"
                                                        class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">
                                                </div>

                                                <div>
                                                    <label class="text-xs text-gray-400">Email</label>
                                                    <input name="email" type="email" required value="<?php echo e(old('email')); ?>"
                                                        class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">
                                                </div>

                                                <div>
                                                    <label class="text-xs text-gray-400">Password</label>
                                                    <input name="password" type="password" required
                                                        class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">
                                                </div>

                                                <div>
                                                    <label class="text-xs text-gray-400">Confirm Password</label>
                                                    <input name="password_confirmation" type="password" required
                                                        class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">
                                                </div>

                                                <button type="submit"
                                                    class="w-full py-3 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition">
                                                    Create Account
                                                </button>

                                                <button type="button" class="w-full text-sm text-gray-400 hover:text-white" @click="tab='login'">
                                                    Already have an account? Login
                                                </button>
                                            </form>
                                        </div>

                                        
                                        <!-- <div class="mt-5 pt-4 border-t border-white/10">
                                            <a href="<?php echo e(route('admin.login')); ?>" class="text-xs text-gray-500 hover:text-gray-300">
                                                Admin login →
                                            </a>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- DESKTOP MENU ICON ONLY -->
                        <div class="dc-menu-wrap actions-desktop" id="dcMenuRoot">
                            <button class="dc-menu-btn" type="button" aria-expanded="false" aria-controls="dcMenuPanel">
                                <i class="bi bi-list dc-menu-ico" aria-hidden="true"></i>
                            </button>

                            <div class="dc-menu-bridge" aria-hidden="true"></div>

                            <!-- MAIN MENU PANEL -->
                            <div class="dc-menu-panel" id="dcMenuPanel" data-state="compact">
                                <div class="dc-menu-inner">

                                    <!-- LEFT: MAIN MENU ITEMS -->
                                    <aside class="dc-menu-left">
                                        <?php $__currentLoopData = $menuParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $hasChildren = $parent->children && $parent->children->count();
                                        $paneKey = 'cat-' . $parent->id;
                                        ?>

                                        <?php if($hasChildren): ?>
                                        <button type="button" class="dc-main-item <?php echo e($loop->first ? 'is-active' : ''); ?>" data-mega="<?php echo e($paneKey); ?>">
                                            <span><?php echo e($parent->name); ?></span>
                                            <span class="dc-chevron">›</span>
                                        </button>
                                        <?php else: ?>
                                        <a class="dc-main-link" href="<?php echo e(route('shop', ['category' => $parent->slug])); ?>">
                                            <span><?php echo e($parent->name); ?></span>
                                        </a>
                                        <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </aside>

                                    <!-- RIGHT: MEGA CONTENT (only for PC Components) -->
                                    <section class="dc-menu-right">
                                        <?php $__currentLoopData = $menuParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $paneKey = 'cat-' . $parent->id;
                                        $pane = $megaMenuByParent->get($parent->id);
                                        $groups = $pane['groups'] ?? [];
                                        $hasChildren = $parent->children && $parent->children->count();
                                        $hasAnyProducts =
                                        !empty($groups['new']) && collect($groups['new'])->flatten(1)->count()
                                        || !empty($groups['used']) && collect($groups['used'])->flatten(1)->count()
                                        || !empty($groups['refurbished']) && collect($groups['refurbished'])->flatten(1)->count();
                                        ?>

                                        <?php if($hasChildren): ?>
                                        <div class="dc-mega-pane <?php echo e($loop->first ? 'is-active' : ''); ?>" data-pane="<?php echo e($paneKey); ?>">
                                            <div class="dc-mega-tabs">
                                                <?php $__currentLoopData = $conditionLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conditionKey => $conditionLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button
                                                    type="button"
                                                    class="dc-tab <?php echo e($loop->first ? 'is-active' : ''); ?>"
                                                    data-tab="<?php echo e($paneKey); ?>-<?php echo e($conditionKey); ?>">
                                                    <?php echo e($conditionLabel); ?>

                                                </button>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>

                                            <?php $__currentLoopData = $conditionLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conditionKey => $conditionLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                            $groupedProducts = collect($groups[$conditionKey] ?? []);
                                            ?>

                                            <div
                                                class="dc-tabpanel <?php echo e($loop->first ? 'is-active' : ''); ?>"
                                                data-tabpanel="<?php echo e($paneKey); ?>-<?php echo e($conditionKey); ?>">
                                                <div class="dc-cols">
                                                    <?php $__empty_1 = true; $__currentLoopData = $groupedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryName => $products): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <div class="dc-col">
                                                        <div class="dc-col-title"><?php echo e($categoryName); ?></div>

                                                        <?php $__currentLoopData = $products->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <a class="dc-link dc-product-link" href="<?php echo e(route('product.show', $product->slug)); ?>">
                                                            <span class="dc-link-line" aria-hidden="true"></span>
                                                            <span class="dc-link-text"><?php echo e($product->name); ?></span>
                                                        </a>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <div class="dc-col">
                                                        <div class="dc-col-title">No <?php echo e($conditionLabel); ?> Products</div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </section>
                                </div>
                            </div>
                        </div>

                        
                        <button
                            type="button"
                            @click="open = !open"
                            :aria-expanded="open.toString()"
                            class="menu-btn md:hidden"
                            aria-label="Menu">
                            <svg xmlns="http://www.w3.org/2000/svg" class="menu-ico" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                </div>

            </div>
        </div>

    </header>

    
    <div
        x-cloak
        x-show="open"
        x-transition.opacity
        class="mobile-menu-overlay md:hidden"
        @click="open = false">
    </div>

    
    <div
        x-cloak
        x-show="open"
        x-transition.opacity.duration.200ms
        class="mobile-dropdown md:hidden"
        @click.self="open = false">
        <div class="mobile-menu-shell">

            
            <div class="mobile-quick-links">
                <?php if(!request()->routeIs('home')): ?>
                <a href="<?php echo e(route('home')); ?>" class="mobile-quick-link">
                    <i class="bi bi-house-door"></i>
                    <span>Home</span>
                </a>
                <?php endif; ?>

                <?php if(!request()->routeIs('shop')): ?>
                <a href="<?php echo e(route('shop')); ?>" class="mobile-quick-link">
                    <i class="bi bi-basket3"></i>
                    <span>Shop</span>
                </a>
                <?php endif; ?>

                <?php if(!request()->routeIs('cart')): ?>
                <a href="<?php echo e(route('cart')); ?>" class="mobile-quick-link">
                    <i class="bi bi-cart3"></i>
                    <span>Cart</span>
                </a>
                <?php endif; ?>

                <?php if(!request()->routeIs('about')): ?>
                <a href="<?php echo e(route('about')); ?>" class="mobile-quick-link">
                    <i class="bi bi-info-circle"></i>
                    <span>About</span>
                </a>
                <?php endif; ?>

                <?php if(!request()->routeIs('wishlist')): ?>
                <a href="<?php echo e(url('/wishlist')); ?>" class="mobile-quick-link">
                    <i class="bi bi-heart"></i>
                    <span>Wishlist</span>
                </a>
                <?php endif; ?>

                <?php if(!request()->routeIs('quote')): ?>
                <a href="<?php echo e(route('quote')); ?>" class="mobile-quick-link">
                    <i class="bi bi-headset"></i>
                    <span>Quote</span>
                </a>
                <?php endif; ?>

                <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->is_admin): ?>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="mobile-quick-link">
                    <i class="bi bi-person-circle"></i>
                    <span>Admin</span>
                </a>
                <?php else: ?>
                <button type="button" class="mobile-quick-link"
                    @click.prevent="open = false; $store.account.openPanel()">
                    <i class="bi bi-person-circle"></i>
                    <span>Account</span>
                </button>
                <?php endif; ?>
                <?php endif; ?>

                <?php if(auth()->guard()->guest()): ?>
                <button type="button" class="mobile-quick-link"
                    @click.prevent="open = false; $store.mobileAccount.openLogin()">
                    <i class="bi bi-person-circle"></i>
                    <span>Account</span>
                </button>
                <?php endif; ?>
            </div>

            
            <div class="mobile-menu-body" @click.capture="if($event.target.closest('a')) open=false">
                <?php $__currentLoopData = $menuParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $hasChildren = $parent->children && $parent->children->count();
                $pane = $megaMenuByParent->get($parent->id);
                $groups = $pane['groups'] ?? [];
                ?>

                <?php if($hasChildren): ?>
                <details class="m-acc">
                    <summary class="mobile-link">
                        <span><?php echo e($parent->name); ?></span>
                        <i class="bi bi-chevron-right ms-auto"></i>
                    </summary>

                    <div class="m-sub">
                        <?php $__currentLoopData = $conditionLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conditionKey => $conditionLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $groupedProducts = collect($groups[$conditionKey] ?? []);
                        ?>

                        <details class="m-acc">
                            <summary class="m-subhead"><?php echo e($conditionLabel); ?></summary>

                            <?php $__empty_1 = true; $__currentLoopData = $groupedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryName => $products): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php $first = $products->first(); ?>
                            <?php if($first?->category): ?>
                            <a
                                class="m-sublink dc-product-link"
                                href="<?php echo e(route('shop', ['category' => $first->category->slug, 'condition' => $conditionKey])); ?>">
                                <span class="dc-link-line"></span>
                                <span class="dc-link-text"><?php echo e($categoryName); ?></span>
                            </a>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <span class="m-sublink text-gray-500">No <?php echo e($conditionLabel); ?> Products</span>
                            <?php endif; ?>
                        </details>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </details>
                <?php else: ?>
                <a href="<?php echo e(route('shop', ['category' => $parent->slug])); ?>" class="mobile-link">
                    <span><?php echo e($parent->name); ?></span>
                </a>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

</div>


<!-- <div class="h-24 sm:h-28"></div> --><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/partials/navbar.blade.php ENDPATH**/ ?>