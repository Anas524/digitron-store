<div x-show="$store.shop.wishOpen" x-transition.opacity class="fixed inset-0 z-[80]">
  <div @click="$store.shop.closeAll()" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

  <div x-transition
       class="absolute right-0 top-0 h-full w-full max-w-md bg-slate-950 border-l border-white/10 shadow-2xl overflow-hidden">
    <div class="relative p-6 border-b border-white/10 overflow-hidden">
      <div class="absolute inset-0 opacity-25 pointer-events-none" data-parallax-layer style="transform: translateY(var(--parY,0px));">
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-pink-500/25 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-cyan-500/20 blur-3xl"></div>
      </div>

      <div class="relative flex items-center justify-between">
        <div>
          <div class="text-white font-bold text-xl tracking-tight">Wishlist</div>
          <div class="text-white/60 text-sm" x-text="`Saved: ${$store.shop.wishlist.count}`"></div>
        </div>
        <button @click="$store.shop.closeAll()" class="p-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10">
          <i class="bi bi-x-lg text-white"></i>
        </button>
      </div>
    </div>

    <div class="p-6 space-y-4 overflow-y-auto h-[calc(100%-110px)]">
      <template x-if="!$store.shop.isAuth">
        <div class="text-white/70 text-center py-16">
          <div class="text-3xl mb-2">💙</div>
          <div class="font-semibold">Login required</div>
          <div class="text-sm text-white/50">Wishlist is saved to your account.</div>
        </div>
      </template>

      <template x-if="$store.shop.isAuth && $store.shop.wishlist.items.length===0">
        <div class="text-white/70 text-center py-16">
          <div class="text-3xl mb-2">✨</div>
          <div class="font-semibold">No wishlist items</div>
          <div class="text-sm text-white/50">Tap the heart on any product.</div>
        </div>
      </template>

      <template x-for="p in $store.shop.wishlist.items" :key="p.id">
        <div class="flex gap-4 p-4 rounded-2xl bg-white/5 border border-white/10">
          <img :src="p.image" class="w-16 h-16 rounded-xl object-cover border border-white/10" />
          <div class="flex-1">
            <div class="text-white font-semibold leading-tight" x-text="p.name"></div>
            <div class="text-white/60 text-sm" x-text="`AED ${p.price.toFixed(2)}`"></div>

            <div class="mt-3 flex items-center justify-between">
              <button class="px-3 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-white text-sm font-semibold"
                      @click="$store.shop.addToCart(p.id, 1)">
                Move to Cart
              </button>

              <button class="text-white/70 hover:text-white"
                      @click="$store.shop.toggleWish(p.id)">
                <i class="bi bi-heart-fill"></i>
              </button>
            </div>
          </div>
        </div>
      </template>
    </div>
  </div>
</div>