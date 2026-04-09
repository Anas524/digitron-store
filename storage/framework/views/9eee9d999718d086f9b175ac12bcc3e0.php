<?php if(auth()->guard()->guest()): ?>
<div
    x-data
    x-cloak
    x-show="$store.mobileAccount.open"
    class="fixed inset-0 z-[9999] md:hidden"
    @keydown.escape.window="$store.mobileAccount.close()"
>
    
    <div
        x-cloak
        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
        x-show="$store.mobileAccount.open"
        x-transition.opacity
        @click="$store.mobileAccount.close()"
    ></div>

    
    <div class="absolute inset-x-4 top-1/2 -translate-y-1/2 rounded-2xl border border-white/10 bg-[#0b1220]/95 backdrop-blur-xl shadow-2xl overflow-hidden">
        <div class="p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="text-white font-bold text-lg">Account</div>
                    <div class="text-gray-400 text-sm">Login or create your account.</div>
                </div>

                <button
                    type="button"
                    class="w-9 h-9 rounded-xl border border-white/10 bg-white/5 text-white"
                    @click="$store.mobileAccount.close()">
                    ✕
                </button>
            </div>

            <div class="flex gap-2 mb-4">
                <button
                    type="button"
                    class="flex-1 py-2 rounded-xl border border-white/10"
                    :class="$store.mobileAccount.tab==='login' ? 'bg-white/10 text-white' : 'bg-white/5 text-gray-300'"
                    @click="$store.mobileAccount.tab='login'">
                    Login
                </button>

                <button
                    type="button"
                    class="flex-1 py-2 rounded-xl border border-white/10"
                    :class="$store.mobileAccount.tab==='register' ? 'bg-white/10 text-white' : 'bg-white/5 text-gray-300'"
                    @click="$store.mobileAccount.tab='register'">
                    Register
                </button>
            </div>

            
            <div x-cloak x-show="$store.mobileAccount.tab==='login'">
                <form method="POST" action="<?php echo e(route('customer.login')); ?>" class="space-y-3">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="redirect_to" id="mobileLoginRedirectTo">

                    <div>
                        <label class="text-xs text-gray-400">Email</label>
                        <input name="email" type="email" required
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

                    <button type="button" class="w-full text-sm text-gray-400 hover:text-white"
                        @click="$store.mobileAccount.tab='register'">
                        New here? Create an account
                    </button>
                </form>
            </div>

            
            <div x-cloak x-show="$store.mobileAccount.tab==='register'">
                <form method="POST" action="<?php echo e(route('customer.register')); ?>" class="space-y-3">
                    <?php echo csrf_field(); ?>
                    <form method="POST" action="<?php echo e(route('customer.register')); ?>" class="space-y-3">

                    <div>
                        <label class="text-xs text-gray-400">Name</label>
                        <input name="name" type="text" required
                            class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">
                    </div>

                    <div>
                        <label class="text-xs text-gray-400">Email</label>
                        <input name="email" type="email" required
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

                    <button type="button" class="w-full text-sm text-gray-400 hover:text-white"
                        @click="$store.mobileAccount.tab='login'">
                        Already have an account? Login
                    </button>
                </form>
            </div>

            <div class="mt-5 pt-4 border-t border-white/10">
                <a href="<?php echo e(route('admin.login')); ?>" class="text-xs text-gray-500 hover:text-gray-300">
                    Admin login →
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/partials/mobile-account-modal.blade.php ENDPATH**/ ?>