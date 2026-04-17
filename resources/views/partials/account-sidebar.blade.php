@auth
@if(!auth()->user()->is_admin)
@php
$cartCount = (int)($cartCount ?? 0);
$wishlistCount = (int)($wishlistCount ?? 0);
@endphp

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
    {{-- Overlay --}}
    <div
        x-cloak
        class="fixed inset-0 bg-black/60 backdrop-blur-sm"
        x-show="$store.account.open"
        x-transition.opacity
        @click="$store.account.closePanel()"></div>

    {{-- Panel --}}
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
                        <div class="dc-account-name">{{ auth()->user()->name }}</div>
                        <div class="dc-account-email">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <div class="dc-account-stats">
                    <a href="/wishlist" class="dc-stat">
                        <div class="dc-stat-label">Wishlist</div>
                        <div class="dc-stat-value">{{ $wishlistCount }}</div>
                    </a>
                    <a href="/cart" class="dc-stat">
                        <div class="dc-stat-label">Cart</div>
                        <div class="dc-stat-value">{{ $cartCount }}</div>
                    </a>
                </div>

                <div class="dc-account-links">
                    <a href="{{ route('account') }}" class="dc-linkrow">
                        <i class="bi bi-person"></i>
                        <span>Profile</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                    <a href="{{ route('my.orders') }}" class="dc-linkrow">
                        <i class="bi bi-receipt"></i>
                        <span>My Orders</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                    <a href="{{ route('addresses') }}" class="dc-linkrow">
                        <i class="bi bi-geo-alt"></i>
                        <span>Delivery Address</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>

                <form method="POST" action="{{ route('customer.logout') }}" class="mt-6">
                    @csrf
                    <button type="submit" class="dc-logout">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>
</div>
@endif
@endauth