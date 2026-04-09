<div x-show="$store.shop.cartOpen" x-transition.opacity class="fixed inset-0 z-[80]">
  <div @click="$store.shop.closeAll()" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

  <div x-transition
       class="absolute right-0 top-0 h-full w-full max-w-md bg-slate-950 border-l border-white/10 shadow-2xl overflow-hidden">
    <!-- Parallax Header -->
    <div class="relative p-6 border-b border-white/10 overflow-hidden">
      <div class="absolute inset-0 opacity-25 pointer-events-none" data-parallax-layer style="transform: translateY(var(--parY,0px));">
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-cyan-500/30 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-amber-400/20 blur-3xl"></div>
      </div>

      <div class="relative flex items-center justify-between">
        <div>
          <div class="text-white font-bold text-xl tracking-tight">Your Cart</div>
          <div class="text-white/60 text-sm" x-text="`Items: ${$store.shop.cart.count}`"></div>
        </div>
        <button @click="$store.shop.closeAll()" class="p-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10">
          <i class="bi bi-x-lg text-white"></i>
        </button>
      </div>
    </div>

    <!-- Items -->
    <div class="p-6 space-y-4 overflow-y-auto h-[calc(100%-210px)]">
      <template x-if="$store.shop.cart.items.length===0">
        <div class="text-white/70 text-center py-16">
          <div class="text-3xl mb-2">🛒</div>
          <div class="font-semibold">Your cart is empty</div>
          <div class="text-sm text-white/50">Hover a product and hit Quick Add.</div>
        </div>
      </template>

      <template x-for="it in $store.shop.cart.items" :key="it.key">
        <div class="flex gap-4 p-4 rounded-2xl bg-white/5 border border-white/10">
          <img :src="it.product.image" class="w-16 h-16 rounded-xl object-cover border border-white/10" />
          <div class="flex-1">
            <div class="text-white font-semibold leading-tight" x-text="it.product.name"></div>
            <div class="text-white/60 text-sm" x-text="`AED ${it.product.price.toFixed(2)}`"></div>

            <div class="mt-3 flex items-center justify-between">
              <div class="inline-flex items-center gap-2">
                <button class="w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10"
                        @click="$store.shop.setQty(it.product.id, it.qty-1)">
                  <i class="bi bi-dash text-white"></i>
                </button>
                <div class="min-w-8 text-center text-white font-semibold" x-text="it.qty"></div>
                <button class="w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10"
                        @click="$store.shop.setQty(it.product.id, it.qty+1)">
                  <i class="bi bi-plus text-white"></i>
                </button>
              </div>

              <button class="text-white/70 hover:text-white"
                      @click="$store.shop.removeFromCart(it.product.id)">
                <i class="bi bi-trash3"></i>
              </button>
            </div>
          </div>
        </div>
      </template>
    </div>

    <!-- Footer -->
    <div class="p-6 border-t border-white/10">
      <div class="flex items-center justify-between mb-4">
        <div class="text-white/60 text-sm">Subtotal</div>
        <div class="text-white font-bold" x-text="`AED ${$store.shop.cart.subtotal.toFixed(2)}`"></div>
      </div>

      <button class="w-full py-3 rounded-2xl bg-gradient-to-r from-cyan-500 to-amber-400 text-black font-bold hover:opacity-95">
        Checkout
      </button>
    </div>
  </div>
</div>