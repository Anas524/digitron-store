<div class="contents">
    <!-- Mobile Overlay -->
    <div
        x-cloak
        x-show="sidebarOpen"
        x-transition.opacity.duration.250ms
        class="fixed inset-0 z-40 bg-black/55 lg:hidden"
        @click="sidebarOpen = false">
    </div>

    <aside
        class="fixed inset-y-0 left-0 z-50 w-[285px] max-w-[86vw] bg-[#0a0f18]/96 backdrop-blur-2xl border-r border-white/10 shadow-[0_20px_60px_rgba(0,0,0,0.45)] h-screen flex flex-col overflow-hidden transform transition-transform duration-300 ease-out lg:relative lg:inset-auto lg:z-20 lg:h-full lg:w-[280px] lg:max-w-none lg:translate-x-0 lg:shrink-0 lg:bg-white/3 lg:backdrop-blur-xl lg:shadow-none"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        <!-- Logo Section (sticky top inside sidebar) -->
        <div class="p-5 border-b border-white/10 relative overflow-hidden group shrink-0">
            <div class="absolute inset-0 bg-gradient-to-r from-admin-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative flex items-center gap-3">
                <div class="w-16 h-16 rounded-xl overflow-hidden flex items-center justify-center">
                    <img src="{{ asset('images/logo-cropped.png') }}" alt="Digitron" class="w-full h-full object-contain p-1">
                </div>
                <div>
                    <div class="text-lg font-bold tracking-wide">DIGITRON</div>
                    <div class="text-[10px] text-admin-primary uppercase tracking-[0.2em]">Command Center</div>
                </div>

                <!-- Mobile Close -->
                <button
                    type="button"
                    class="lg:hidden ml-auto w-10 h-10 rounded-xl border border-white/10 bg-white/5 flex items-center justify-center text-gray-300 hover:text-white hover:bg-white/10 transition shrink-0"
                    @click.stop="sidebarOpen = false"
                    aria-label="Close sidebar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>

        <!-- User Profile Mini (sticky) -->
        <div class="p-4 border-b border-white/10 shrink-0 bg-white/[0.02]">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5">
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->email, 0, 1)) }}
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium truncate">{{ auth()->user()->email }}</div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto overflow-x-hidden custom-scrollbar" data-scroll-lock>
            <div class="text-[10px] uppercase tracking-wider text-gray-500 mb-3 px-2">Main</div>

            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active text-white bg-white/10' : 'text-gray-400 hover:text-white hover:bg-white/5' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all group"
                @click="if (window.innerWidth < 1024) sidebarOpen = false">
                <div class="w-8 h-8 rounded-lg bg-admin-primary/20 flex items-center justify-center text-admin-primary group-hover:scale-110 transition-transform">
                    <i class="bi bi-grid-1x2-fill"></i>
                </div>
                <span class="font-medium">Dashboard</span>
                @if(request()->routeIs('admin.dashboard'))
                <div class="ml-auto w-2 h-2 rounded-full bg-admin-primary shadow-lg shadow-admin-primary/50"></div>
                @endif
            </a>

            <div class="text-[10px] uppercase tracking-wider text-gray-500 mb-3 mt-6 px-2">Management</div>

            <a href="{{ route('admin.categories.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all group"
                @click="if (window.innerWidth < 1024) sidebarOpen = false">
                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-admin-primary/20 group-hover:text-admin-primary transition-colors">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>
                <span>Categories</span>
                <span class="ml-auto text-xs bg-white/10 px-2 py-0.5 rounded-full">{{ $sidebarCounts['categories'] ?? 0 }}</span>
            </a>
            
            <a href="{{ route('admin.products.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active text-white bg-white/10' : 'text-gray-400 hover:text-white hover:bg-white/5' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all group"
                @click="if (window.innerWidth < 1024) sidebarOpen = false">
                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-admin-secondary/20 group-hover:text-admin-secondary transition-colors">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <span>Products</span>
                <span class="ml-auto text-xs bg-white/10 px-2 py-0.5 rounded-full">{{ $sidebarCounts['products'] ?? 0 }}</span>
            </a>

            <a href="{{ route('admin.newsletter.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all group"
                @click="if (window.innerWidth < 1024) sidebarOpen = false">
                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-admin-warning/20 group-hover:text-admin-warning transition-colors">
                    <i class="bi bi-envelope-paper-fill"></i>
                </div>
                <span>Newsletter</span>
                <span class="ml-auto text-xs bg-admin-primary/20 text-admin-primary px-2 py-0.5 rounded-full">{{ $sidebarCounts['newsletter'] ?? 0 }}</span>
            </a>

            <a href="{{ route('admin.quotes.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all group"
                @click="if (window.innerWidth < 1024) sidebarOpen = false">
                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-admin-success/20 group-hover:text-admin-success transition-colors">
                    <i class="bi bi-file-text-fill"></i>
                </div>
                <span>Quotes</span>
                <span class="ml-auto text-xs bg-admin-danger/20 text-admin-danger px-2 py-0.5 rounded-full">{{ ($sidebarCounts['quotes'] ?? 0) }}</span>
            </a>

            <a href="{{ route('admin.orders.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all group"
                @click="if (window.innerWidth < 1024) sidebarOpen = false">
                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-admin-primary/20 group-hover:text-admin-primary transition-colors">
                    <i class="bi bi-bag-check-fill"></i>
                </div>
                <span>Orders</span>
                <span class="ml-auto text-xs bg-admin-primary/20 text-admin-primary px-2 py-0.5 rounded-full">{{ $sidebarCounts['orders'] ?? 0 }}</span>
            </a>

            <a href="{{ route('admin.home-showcase.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.home-showcase.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all group"
                @click="if (window.innerWidth < 1024) sidebarOpen = false">
                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-admin-primary/20 group-hover:text-admin-primary transition-colors">
                    <i class="bi bi-images"></i>
                </div>
                <span>Home Showcase</span>
                <span class="ml-auto text-xs bg-admin-primary/20 text-admin-primary px-2 py-0.5 rounded-full">{{ $sidebarCounts['home_showcase'] ?? 0 }}</span>
            </a>

            <div class="text-[10px] uppercase tracking-wider text-gray-500 mb-3 mt-6 px-2">Pages</div>

            <a href="{{ route('home') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all group"
                @click="if (window.innerWidth < 1024) sidebarOpen = false">
                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:text-white transition-colors">
                    <i class="bi bi-house-door"></i>
                </div>
                <span>Home</span>
            </a>

            <a href="{{ route('shop') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all group"
                @click="if (window.innerWidth < 1024) sidebarOpen = false">
                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:text-white transition-colors">
                    <i class="bi bi-basket3"></i>
                </div>
                <span>Shop</span>
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-white/10 shrink-0">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-admin-danger hover:bg-admin-danger/10 transition-all group cursor-pointer"
                    @click="if (window.innerWidth < 1024) sidebarOpen = false">
                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-admin-danger/20 transition-colors">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        </div>
    </aside>
</div>