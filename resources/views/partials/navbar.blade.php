<header
    x-data="{
        open:false,
        scrolled:false,
        lastY:0,
        offset:0,
        h:0,

        init(){
            this.open = false;
            this.lastY = window.scrollY;

            // header height
            this.h = this.$el.offsetHeight;

            const onScroll = () => {
            const y = window.scrollY;
            const delta = y - this.lastY;

            // when near top: fully show + no bg
            if (y <= 10) {
                this.offset = 0;
                this.scrolled = false;
                this.lastY = y;
                return;
            }

            // progressive hide/show
            if (delta > 0) {
                // scrolling down → hide gradually
                this.offset = Math.min(this.h, this.offset + delta);
            } else if (delta < 0) {
                // scrolling up → show gradually
                this.offset = Math.max(0, this.offset + delta);
            }

            // background only when header is visible and not at top
            this.scrolled = (y > 10 && this.offset < this.h);

            this.lastY = y;
            };

            window.addEventListener('scroll', onScroll, { passive:true });

            // if resized, recalc header height
            window.addEventListener('resize', () => {
            this.h = this.$el.offsetHeight;
            this.offset = Math.min(this.offset, this.h);
            }, { passive:true });
        }
    }"
    x-init="init()"
    :style="`transform: translateY(-${offset}px)`"
    class="fixed top-0 left-0 right-0 z-50 will-change-transform">
    <div
        :class="scrolled ? 'header-glass' : 'header-clear'">
        <div class="pt-4">

            <div class="flex items-center justify-between gap-3 px-4 py-3 sm:px-6">

                <div class="header-row">
                    {{-- Logo + Brand Text --}}
                    <a href="/" class="brand">
                        <img src="{{ asset('images/logo-croped.png') }}" alt="Digitron Computers UAE" class="brand-logo">

                        <span class="brand-text">
                            <span class="brand-title">
                                <span class="bt-main">DIGITRON</span>
                                <span class="bt-sub">COMPUTERS UAE</span>
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

                        <a href="/shop" class="icon-btn" aria-label="Shop">
                            <i class="bi bi-basket3"></i>
                            <span class="icon-label">Shop</span>
                        </a>

                        <a href="/cart" class="icon-btn has-badge" aria-label="Cart">
                            <i class="bi bi-cart3"></i>
                            <span class="cart-badge cart-count">2</span>
                            <span class="icon-label">Cart</span>
                        </a>

                        {{-- DESKTOP: ACCOUNT / ADMIN LOGIN DROPDOWN --}}
                        <div class="relative" x-data="{ accOpen:false }">

                            {{-- Button (icon like before) --}}
                            <button
                                type="button"
                                class="icon-btn"
                                aria-label="Account"
                                @click="accOpen = !accOpen"
                                @keydown.escape.window="accOpen=false">
                                <i class="bi bi-person-circle"></i>
                                <span class="icon-label">Account</span>
                            </button>

                            {{-- Dropdown Panel --}}
                            <div
                                x-show="accOpen"
                                x-transition.origin.top.right
                                @click.outside="accOpen=false"
                                class="absolute right-0 mt-3 w-[360px] z-50 rounded-2xl border border-white/10 bg-[#0b1220]/95 backdrop-blur-xl shadow-2xl overflow-hidden">

                                {{-- If Admin already logged in --}}
                                @auth
                                @if(auth()->user()->is_admin)
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
                                @else
                                <div class="p-5 text-gray-300">
                                    <div class="font-bold text-white mb-1">Account</div>
                                    <div class="text-sm text-gray-400">You are logged in (not admin).</div>
                                </div>
                                @endif
                                @endauth

                                {{-- Not logged in: show login form --}}
                                @guest
                                <div class="p-5">
                                    <div class="text-white font-bold text-lg mb-1">Admin Login</div>
                                    <div class="text-gray-400 text-sm mb-4">Enter credentials to open dashboard.</div>

                                    {{-- Error message (from your middleware redirect with "error") --}}
                                    @if(session('error'))
                                    <div class="mb-3 rounded-xl border border-red-500/30 bg-red-500/10 text-red-200 text-sm px-4 py-3">
                                        {{ session('error') }}
                                    </div>
                                    @endif

                                    {{-- Validation errors --}}
                                    @if($errors->any())
                                    <div class="mb-3 rounded-xl border border-red-500/30 bg-red-500/10 text-red-200 text-sm px-4 py-3">
                                        {{ $errors->first() }}
                                    </div>
                                    @endif

                                    <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-3">
                                        @csrf

                                        <div>
                                            <label class="text-xs text-gray-400">Email</label>
                                            <input name="email" type="email" required
                                                class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent"
                                                placeholder="admin@digitron.ae">
                                        </div>

                                        <div>
                                            <label class="text-xs text-gray-400">Password</label>
                                            <input name="password" type="password" required
                                                class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent"
                                                placeholder="••••••••">
                                        </div>

                                        <button type="submit"
                                            class="w-full py-3 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition">
                                            Login
                                        </button>
                                    </form>
                                </div>
                                @endguest

                            </div>
                        </div>
                    </div>

                    <!-- DESKTOP MENU ICON ONLY -->
                    <div class="dc-menu-wrap actions-desktop" id="dcMenuRoot">
                        <button class="dc-menu-btn" type="button" aria-expanded="false" aria-controls="dcMenuPanel">
                            <i class="bi bi-list dc-menu-ico" aria-hidden="true"></i>
                        </button>

                        <!-- MAIN MENU PANEL -->
                        <div class="dc-menu-panel" id="dcMenuPanel" data-state="compact">
                            <div class="dc-menu-inner">

                                <!-- LEFT: MAIN MENU ITEMS -->
                                <aside class="dc-menu-left">
                                    <button type="button" class="dc-main-item is-active" data-mega="pc">
                                        PC Components <span class="dc-chevron">›</span>
                                    </button>

                                    <a class="dc-main-link" href="/gaming">Gaming</a>
                                    <a class="dc-main-link" href="/gaming-pcs">Gaming PCs</a>
                                    <a class="dc-main-link" href="/computer-systems">Computer Systems</a>
                                    <a class="dc-main-link" href="/peripherals">Computer Peripherals</a>
                                    <a class="dc-main-link" href="/networking">Networking</a>
                                    <a class="dc-main-link" href="/security">Security & Surveillance</a>
                                    <a class="dc-main-link" href="/office">Office Solutions</a>
                                </aside>

                                <!-- RIGHT: MEGA CONTENT (only for PC Components) -->
                                <section class="dc-menu-right">

                                    <!-- PC COMPONENTS MEGA -->
                                    <div class="dc-mega-pane is-active" data-pane="pc">
                                        <div class="dc-mega-tabs">
                                            <button type="button" class="dc-tab is-active" data-tab="new">New Components</button>
                                            <button type="button" class="dc-tab" data-tab="used">Used Components</button>
                                            <button type="button" class="dc-tab" data-tab="custom">Custom PCs</button>
                                        </div>

                                        <!-- NEW -->
                                        <div class="dc-tabpanel is-active" data-tabpanel="new">
                                            <div class="dc-cols">
                                                <div class="dc-col">
                                                    <div class="dc-col-title">CPUs / Processors</div>
                                                    <a class="dc-link" href="/shop/processors?brand=amd">AMD Processors</a>
                                                    <a class="dc-link" href="/shop/processors?brand=intel">Intel Processors</a>
                                                </div>

                                                <div class="dc-col">
                                                    <div class="dc-col-title">Graphic Cards</div>
                                                    <a class="dc-link" href="/shop/gpu?series=rtx">NVIDIA RTX Series</a>
                                                    <a class="dc-link" href="/shop/gpu?series=rx">AMD RX Series</a>
                                                </div>

                                                <div class="dc-col">
                                                    <div class="dc-col-title">Motherboards</div>
                                                    <a class="dc-link" href="/shop/motherboards?brand=asus">ASUS</a>
                                                    <a class="dc-link" href="/shop/motherboards?brand=msi">MSI</a>
                                                </div>

                                                <div class="dc-col">
                                                    <div class="dc-col-title">Memory</div>
                                                    <a class="dc-link" href="/shop/ram?type=ddr4">DDR4</a>
                                                    <a class="dc-link" href="/shop/ram?type=ddr5">DDR5</a>
                                                </div>

                                                <div class="dc-col">
                                                    <div class="dc-col-title">Storage</div>
                                                    <a class="dc-link" href="/shop/ssds">SSDs</a>
                                                    <a class="dc-link" href="/shop/nvme">NVMe</a>
                                                    <a class="dc-link" href="/shop/hdd">Hard Drives</a>
                                                </div>

                                                <div class="dc-col">
                                                    <div class="dc-col-title">Power</div>
                                                    <a class="dc-link" href="/shop/psu">PSUs</a>
                                                    <a class="dc-link" href="/shop/cooling">Cooling</a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- USED -->
                                        <div class="dc-tabpanel" data-tabpanel="used">
                                            <div class="dc-cols">
                                                <div class="dc-col">
                                                    <div class="dc-col-title">Pre-Owned Hardware</div>
                                                    <a class="dc-link" href="/used/gpu">Used GPUs</a>
                                                    <a class="dc-link" href="/used/cpu">Used CPUs</a>
                                                    <a class="dc-link" href="/used/ram">Used RAM</a>
                                                    <a class="dc-link" href="/used/ssd">Used SSDs</a>
                                                </div>
                                                <div class="dc-col">
                                                    <div class="dc-col-title">Bundles</div>
                                                    <a class="dc-link" href="/used/bundles">CPU + Motherboard</a>
                                                    <a class="dc-link" href="/used/full-builds">Used Full Builds</a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- CUSTOM -->
                                        <div class="dc-tabpanel" data-tabpanel="custom">
                                            <div class="dc-cols">
                                                <div class="dc-col">
                                                    <div class="dc-col-title">New Customizable PCs</div>
                                                    <a class="dc-link" href="/custom/build">Build Your PC</a>
                                                    <a class="dc-link" href="/custom/workstation">Workstations</a>
                                                </div>
                                                <div class="dc-col">
                                                    <div class="dc-col-title">Pre-built Gaming PCs</div>
                                                    <a class="dc-link" href="/gaming-pcs/entry">Entry Level</a>
                                                    <a class="dc-link" href="/gaming-pcs/mid">Mid Range</a>
                                                    <a class="dc-link" href="/gaming-pcs/high">High End</a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </section>
                            </div>
                        </div>
                    </div>



                    {{-- Mobile Menu Button ONLY --}}
                    <button
                        @click="open = !open"
                        class="menu-btn"
                        aria-label="Menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="menu-ico" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

            </div>

            {{-- Mobile Dropdown --}}
            <div x-show="open" x-transition class="mobile-dropdown" @click.self="open=false">
                <div class="mobile-menu" @click.capture="if($event.target.closest('a')) open=false">

                    <!-- PC Components (accordion) -->
                    <details class="m-acc">
                        <summary class="mobile-link">
                            <i class="bi bi-pc-display"></i>
                            <span>PC Components</span>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </summary>

                        <div class="m-sub">
                            <details class="m-acc">
                                <summary class="m-subhead">New Components</summary>
                                <a class="m-sublink" href="/shop/processors">Processors</a>
                                <a class="m-sublink" href="/shop/motherboards">Motherboards</a>
                                <a class="m-sublink" href="/shop/ram">RAM</a>
                                <a class="m-sublink" href="/shop/ssds">SSDs / NVMe</a>
                                <a class="m-sublink" href="/shop/psu">PSUs</a>
                                <a class="m-sublink" href="/shop/gpu">Graphics Cards</a>
                            </details>

                            <details class="m-acc">
                                <summary class="m-subhead">Used Components</summary>
                                <a class="m-sublink" href="/used/gpu">Used GPUs</a>
                                <a class="m-sublink" href="/used/cpu">Used CPUs</a>
                                <a class="m-sublink" href="/used/ram">Used RAM</a>
                                <a class="m-sublink" href="/used/ssd">Used SSDs</a>
                            </details>

                            <details class="m-acc">
                                <summary class="m-subhead">Custom PCs</summary>
                                <a class="m-sublink" href="/custom/build">New Customizable PCs</a>
                                <a class="m-sublink" href="/gaming-pcs">Pre-built Gaming PCs</a>
                            </details>
                        </div>
                    </details>

                    <!-- Other main menu items -->
                    <a href="/gaming" class="mobile-link"><i class="bi bi-controller"></i><span>Gaming</span></a>
                    <a href="/gaming-pcs" class="mobile-link"><i class="bi bi-cpu"></i><span>Gaming PCs</span></a>
                    <a href="/computer-systems" class="mobile-link"><i class="bi bi-pc"></i><span>Computer Systems</span></a>
                    <a href="/peripherals" class="mobile-link"><i class="bi bi-mouse"></i><span>Computer Peripherals</span></a>
                    <a href="/networking" class="mobile-link"><i class="bi bi-wifi"></i><span>Networking</span></a>

                    <!-- Shop / Cart / Account -->
                    <a href="/shop" class="mobile-link"><i class="bi bi-bag"></i><span>Shop</span></a>

                    <a href="/cart" class="mobile-link">
                        <i class="bi bi-cart3"></i><span>Cart</span>
                        <span class="mobile-badge cart-count">2</span>
                    </a>

                    <a href="{{ route('admin.login') }}" class="mobile-link">
                        <i class="bi bi-person-circle"></i><span>Account</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

</header>

{{-- Spacer so content doesn't hide behind fixed header --}}
<!-- <div class="h-24 sm:h-28"></div> -->