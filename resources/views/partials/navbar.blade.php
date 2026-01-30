<header
    x-data="{
        open:false,
        hidden:false,
        lastY:0,
        init(){
            this.lastY = window.scrollY;

            window.addEventListener('scroll', () => {
                const y = window.scrollY;
                const goingDown = y > this.lastY;
                const goingUp   = y < this.lastY;

                // Always show when near top
                if (y <= 10) {
                    this.hidden = false;
                    this.lastY = y;
                    return;
                }

                // Hide only when scrolling down
                if (goingDown && y > 80) this.hidden = true;

                // Show when scrolling up (small threshold avoids flicker)
                if (goingUp && (this.lastY - y) > 5) this.hidden = false;

                this.lastY = y;
            }, { passive:true });
        }
    }"
    x-init="init()"
    :class="hidden ? '-translate-y-full' : 'translate-y-0'"
    class="fixed top-0 left-0 right-0 z-50 transition-transform duration-300">
    <div class="">
        <div class="mt-4">

            <div class="flex items-center justify-between gap-3 px-4 py-3 sm:px-6">

                <div class="header-row">
                    {{-- Logo + Brand Text --}}
                    <a href="/" class="brand">
                        <img src="images/logo-croped.png" alt="Digitron Computers UAE" class="brand-logo">

                        <span class="brand-text">
                            <span class="brand-title">
                                <span class="bt-main">Digitron</span>
                                <span class="bt-sub">Computers UAE</span>
                            </span>
                        </span>
                    </a>

                    {{-- Desktop Right Icons (icons only, hover label) --}}
                    <div class="top-actions actions-desktop">
                        <a href="/shop" class="icon-btn" aria-label="Shop">
                            <i class="bi bi-basket3"></i>
                            <span class="icon-label">Shop</span>
                        </a>

                        <a href="/cart" class="icon-btn has-badge" aria-label="Cart">
                            <i class="bi bi-cart3"></i>
                            <span class="cart-badge cart-count">2</span>
                            <span class="icon-label">Cart</span>
                        </a>

                        <a href="/account" class="icon-btn" aria-label="Account">
                            <i class="bi bi-person-circle"></i>
                            <span class="icon-label">Account</span>
                        </a>
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
            <div x-show="open" x-transition class="mobile-dropdown">
                <div class="mobile-menu">

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

                    <a href="/account" class="mobile-link"><i class="bi bi-person-circle"></i><span>Account</span></a>
                </div>
            </div>

        </div>
    </div>

</header>

{{-- Spacer so content doesn't hide behind fixed header --}}
<!-- <div class="h-24 sm:h-28"></div> -->
