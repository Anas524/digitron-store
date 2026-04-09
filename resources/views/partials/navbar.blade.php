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
        @php
        $menuParents = $menuParents ?? collect();
        $megaMenuByParent = $megaMenuByParent ?? collect();
        $conditionLabels = [
        'new' => 'New',
        'used' => 'Used',
        'refurbished' => 'Refurbished',
        ];
        @endphp
        <div
            :class="(open || scrolled) ? 'header-glass' : 'header-clear'">
            <div class="pt-4">

                <div class="flex items-center justify-between gap-3 px-4 py-3 sm:px-6">

                    <div class="header-row">
                        {{-- Logo + Brand Text --}}
                        <a href="/" class="brand">
                            <img src="{{ asset('images/logo-cropped.png') }}" alt="Digitron Computers UAE" class="brand-logo">

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

                        {{-- Desktop Right Icons (icons only, hover label) --}}
                        <div class="top-actions actions-desktop">
                            {{-- HOME ICON (hide on home page) --}}
                            @if(!request()->routeIs('home'))
                            <a href="{{ route('home') }}" class="icon-btn" aria-label="Home">
                                <i class="bi bi-house-door"></i>
                                <span class="icon-label">Home</span>
                            </a>
                            @endif

                            <a href="{{ route('about') }}" class="icon-btn" aria-label="About">
                                <i class="bi bi-info-circle"></i>
                                <span class="icon-label">About</span>
                            </a>

                            <a href="/shop" class="icon-btn" aria-label="Shop">
                                <i class="bi bi-basket3"></i>
                                <span class="icon-label">Shop</span>
                            </a>

                            <a href="{{ route('quote') }}" class="icon-btn quote-btn" aria-label="Contact Us">
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
                                @php $cartCount = (int)($cartCount ?? 0); @endphp
                                <span id="headerCartCount"
                                    class="cart-badge {{ $cartCount <= 0 ? 'hidden' : '' }}">
                                    {{ $cartCount > 0 ? $cartCount : '' }}
                                </span>
                                <span class="icon-label">Cart</span>
                            </a>

                            @php
                            $cartCount = (int)($cartCount ?? 0);
                            $wishlistCount = (int)($wishlistCount ?? 0);
                            @endphp

                            {{-- ACCOUNT --}}
                            @auth
                            @if(auth()->user()->is_admin)
                            {{-- ADMIN: keep dropdown --}}
                            <div class="relative" x-data="{ accOpen: {{ session('openAccountDropdown') ? 'true' : 'false' }} }">
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
                                            Logged in as <span class="text-white">{{ auth()->user()->email }}</span>
                                        </div>

                                        <a href="{{ route('admin.dashboard') }}"
                                            class="block w-full text-center py-3 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition">
                                            Open Dashboard
                                        </a>

                                        <form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
                                            @csrf
                                            <button type="submit"
                                                class="w-full py-3 rounded-xl border border-white/10 text-white hover:bg-white/10 transition">
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @else
                            {{-- CUSTOMER: trigger only --}}
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
                            @endif
                            @endauth

                            @guest
                            {{-- GUEST: KEEP DROPDOWN but make it CUSTOMER login/register --}}
                            <div class="relative"
                                x-data="{
                            accOpen: {{ session('openAccountPanel') ? 'true' : 'false' }},
                            tab: '{{ session('accountTab','login') }}'
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

                                        {{-- LOGIN --}}
                                        <div x-cloak x-show="tab==='login'">
                                            <form method="POST" action="{{ route('customer.login') }}" class="space-y-3">
                                                @csrf
                                                <input type="hidden" name="redirect_to" id="desktopLoginRedirectTo">

                                                @error('login')
                                                <div class="mb-3 rounded-xl border border-red-500/30 bg-red-500/10 text-red-200 text-sm px-4 py-3">
                                                    {{ $message }}
                                                </div>
                                                @enderror

                                                <div>
                                                    <label class="text-xs text-gray-400">Email</label>
                                                    <input name="email" type="email" required value="{{ old('email') }}"
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

                                        {{-- REGISTER --}}
                                        <div x-cloak x-show="tab==='register'">
                                            <form method="POST" action="{{ route('customer.register') }}" class="space-y-3">
                                                @csrf
                                                <input type="hidden" name="redirect_to" id="desktopRegisterRedirectTo">

                                                @if ($errors->has('name') || $errors->has('email') || $errors->has('password') || $errors->has('password_confirmation'))
                                                <div class="mb-3 rounded-xl border border-red-500/30 bg-red-500/10 text-red-200 text-sm px-4 py-3">
                                                    <ul class="list-disc ps-4 space-y-1">
                                                        @error('name') <li>{{ $message }}</li> @enderror
                                                        @error('email') <li>{{ $message }}</li> @enderror
                                                        @error('password') <li>{{ $message }}</li> @enderror
                                                        @error('password_confirmation') <li>{{ $message }}</li> @enderror
                                                    </ul>
                                                </div>
                                                @endif

                                                <div>
                                                    <label class="text-xs text-gray-400">Name</label>
                                                    <input name="name" type="text" required value="{{ old('name') }}"
                                                        class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">
                                                </div>

                                                <div>
                                                    <label class="text-xs text-gray-400">Email</label>
                                                    <input name="email" type="email" required value="{{ old('email') }}"
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

                                        {{-- Admin login small link (opens admin flow) --}}
                                        <!-- <div class="mt-5 pt-4 border-t border-white/10">
                                            <a href="{{ route('admin.login') }}" class="text-xs text-gray-500 hover:text-gray-300">
                                                Admin login →
                                            </a>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            @endguest
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
                                        @foreach($menuParents as $parent)
                                        @php
                                        $hasChildren = $parent->children && $parent->children->count();
                                        $paneKey = 'cat-' . $parent->id;
                                        @endphp

                                        @if($hasChildren)
                                        <button type="button" class="dc-main-item {{ $loop->first ? 'is-active' : '' }}" data-mega="{{ $paneKey }}">
                                            <span>{{ $parent->name }}</span>
                                            <span class="dc-chevron">›</span>
                                        </button>
                                        @else
                                        <a class="dc-main-link" href="{{ route('shop', ['category' => $parent->slug]) }}">
                                            <span>{{ $parent->name }}</span>
                                        </a>
                                        @endif
                                        @endforeach
                                    </aside>

                                    <!-- RIGHT: MEGA CONTENT (only for PC Components) -->
                                    <section class="dc-menu-right">
                                        @foreach($menuParents as $parent)
                                        @php
                                        $paneKey = 'cat-' . $parent->id;
                                        $pane = $megaMenuByParent->get($parent->id);
                                        $groups = $pane['groups'] ?? [];
                                        $hasChildren = $parent->children && $parent->children->count();
                                        $hasAnyProducts =
                                        !empty($groups['new']) && collect($groups['new'])->flatten(1)->count()
                                        || !empty($groups['used']) && collect($groups['used'])->flatten(1)->count()
                                        || !empty($groups['refurbished']) && collect($groups['refurbished'])->flatten(1)->count();
                                        @endphp

                                        @if($hasChildren)
                                        <div class="dc-mega-pane {{ $loop->first ? 'is-active' : '' }}" data-pane="{{ $paneKey }}">
                                            <div class="dc-mega-tabs">
                                                @foreach($conditionLabels as $conditionKey => $conditionLabel)
                                                <button
                                                    type="button"
                                                    class="dc-tab {{ $loop->first ? 'is-active' : '' }}"
                                                    data-tab="{{ $paneKey }}-{{ $conditionKey }}">
                                                    {{ $conditionLabel }}
                                                </button>
                                                @endforeach
                                            </div>

                                            @foreach($conditionLabels as $conditionKey => $conditionLabel)
                                            @php
                                            $groupedProducts = collect($groups[$conditionKey] ?? []);
                                            @endphp

                                            <div
                                                class="dc-tabpanel {{ $loop->first ? 'is-active' : '' }}"
                                                data-tabpanel="{{ $paneKey }}-{{ $conditionKey }}">
                                                <div class="dc-cols">
                                                    @forelse($groupedProducts as $categoryName => $products)
                                                    <div class="dc-col">
                                                        <div class="dc-col-title">{{ $categoryName }}</div>

                                                        @foreach($products->take(6) as $product)
                                                        <a class="dc-link dc-product-link" href="{{ route('product.show', $product->slug) }}">
                                                            <span class="dc-link-line" aria-hidden="true"></span>
                                                            <span class="dc-link-text">{{ $product->name }}</span>
                                                        </a>
                                                        @endforeach
                                                    </div>
                                                    @empty
                                                    <div class="dc-col">
                                                        <div class="dc-col-title">No {{ $conditionLabel }} Products</div>
                                                    </div>
                                                    @endforelse
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                        @endforeach
                                    </section>
                                </div>
                            </div>
                        </div>

                        {{-- Mobile Menu Button ONLY --}}
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

    {{-- Mobile overlay --}}
    <div
        x-cloak
        x-show="open"
        x-transition.opacity
        class="mobile-menu-overlay md:hidden"
        @click="open = false">
    </div>

    {{-- Mobile Dropdown --}}
    <div
        x-cloak
        x-show="open"
        x-transition.opacity.duration.200ms
        class="mobile-dropdown md:hidden"
        @click.self="open = false">
        <div class="mobile-menu-shell">

            {{-- Top quick nav row --}}
            <div class="mobile-quick-links">
                @if(!request()->routeIs('home'))
                <a href="{{ route('home') }}" class="mobile-quick-link">
                    <i class="bi bi-house-door"></i>
                    <span>Home</span>
                </a>
                @endif

                @if(!request()->routeIs('shop'))
                <a href="{{ route('shop') }}" class="mobile-quick-link">
                    <i class="bi bi-basket3"></i>
                    <span>Shop</span>
                </a>
                @endif

                @if(!request()->routeIs('cart'))
                <a href="{{ route('cart') }}" class="mobile-quick-link">
                    <i class="bi bi-cart3"></i>
                    <span>Cart</span>
                </a>
                @endif

                @if(!request()->routeIs('about'))
                <a href="{{ route('about') }}" class="mobile-quick-link">
                    <i class="bi bi-info-circle"></i>
                    <span>About</span>
                </a>
                @endif

                @if(!request()->routeIs('wishlist'))
                <a href="{{ url('/wishlist') }}" class="mobile-quick-link">
                    <i class="bi bi-heart"></i>
                    <span>Wishlist</span>
                </a>
                @endif

                @if(!request()->routeIs('quote'))
                <a href="{{ route('quote') }}" class="mobile-quick-link">
                    <i class="bi bi-headset"></i>
                    <span>Quote</span>
                </a>
                @endif

                @auth
                @if(auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="mobile-quick-link">
                    <i class="bi bi-person-circle"></i>
                    <span>Admin</span>
                </a>
                @else
                <button type="button" class="mobile-quick-link"
                    @click.prevent="open = false; $store.account.openPanel()">
                    <i class="bi bi-person-circle"></i>
                    <span>Account</span>
                </button>
                @endif
                @endauth

                @guest
                <button type="button" class="mobile-quick-link"
                    @click.prevent="open = false; $store.mobileAccount.openLogin()">
                    <i class="bi bi-person-circle"></i>
                    <span>Account</span>
                </button>
                @endguest
            </div>

            {{-- Scrollable menu body --}}
            <div class="mobile-menu-body" @click.capture="if($event.target.closest('a')) open=false">
                @foreach($menuParents as $parent)
                @php
                $hasChildren = $parent->children && $parent->children->count();
                $pane = $megaMenuByParent->get($parent->id);
                $groups = $pane['groups'] ?? [];
                @endphp

                @if($hasChildren)
                <details class="m-acc">
                    <summary class="mobile-link">
                        <span>{{ $parent->name }}</span>
                        <i class="bi bi-chevron-right ms-auto"></i>
                    </summary>

                    <div class="m-sub">
                        @foreach($conditionLabels as $conditionKey => $conditionLabel)
                        @php
                        $groupedProducts = collect($groups[$conditionKey] ?? []);
                        @endphp

                        <details class="m-acc">
                            <summary class="m-subhead">{{ $conditionLabel }}</summary>

                            @forelse($groupedProducts as $categoryName => $products)
                            @php $first = $products->first(); @endphp
                            @if($first?->category)
                            <a
                                class="m-sublink dc-product-link"
                                href="{{ route('shop', ['category' => $first->category->slug, 'condition' => $conditionKey]) }}">
                                <span class="dc-link-line"></span>
                                <span class="dc-link-text">{{ $categoryName }}</span>
                            </a>
                            @endif
                            @empty
                            <span class="m-sublink text-gray-500">No {{ $conditionLabel }} Products</span>
                            @endforelse
                        </details>
                        @endforeach
                    </div>
                </details>
                @else
                <a href="{{ route('shop', ['category' => $parent->slug]) }}" class="mobile-link">
                    <span>{{ $parent->name }}</span>
                </a>
                @endif
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- Spacer so content doesn't hide behind fixed header --}}
<!-- <div class="h-24 sm:h-28"></div> -->