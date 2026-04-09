<!-- Marquee Section -->
<div class="py-10 bg-brand-accent text-black overflow-hidden relative -rotate-1 scale-105 border-y-4 border-black z-10">
    <div class="whitespace-nowrap animate-marquee flex gap-10 font-display font-bold text-2xl">
        <span>FREE DELIVERY IN DUBAI</span> <span>•</span>
        <span>24/7 TECH SUPPORT</span> <span>•</span>
        <span>0% INSTALLMENTS</span> <span>•</span>
        <span>AUTHORIZED DEALER</span> <span>•</span>
        <span>FREE DELIVERY IN DUBAI</span> <span>•</span>
        <span>24/7 TECH SUPPORT</span> <span>•</span>
        <span>0% INSTALLMENTS</span> <span>•</span>
        <span>AUTHORIZED DEALER</span> <span>•</span>
    </div>
</div>

<footer class="relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 bg-[#030305]">
        <!-- Grid Pattern -->
        <div class="absolute inset-0 bg-grid-pattern opacity-[0.03]"></div>

        <!-- Glowing Orbs -->
        <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-brand-accent/5 rounded-full blur-[150px] animate-pulse"></div>
        <div class="absolute top-0 right-1/4 w-80 h-80 bg-brand-secondary/5 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>

        <!-- Animated Line -->
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-accent/50 to-transparent animate-shimmer"></div>
    </div>

    <!-- Main Footer Content -->
    <div class="relative z-10">
        <!-- Newsletter Bar -->
        <div class="border-b border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                    <div class="text-center lg:text-left">
                        <h3 class="text-2xl md:text-3xl font-display font-bold mb-2">
                            JOIN THE <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-brand-secondary">MASTER RACE</span>
                        </h3>
                        <p class="text-gray-400">Get exclusive deals, new arrival alerts, and PC building tips.</p>
                    </div>

                    <form id="newsletterForm" action="{{ route('newsletter.subscribe') }}" method="POST" class="w-full max-w-md">
                        @csrf
                        <div class="flex gap-3">
                            <div class="relative flex-1">
                                <input name="email" type="email" required
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3.5 pl-12 text-white placeholder-gray-500 focus:outline-none focus:border-brand-accent focus:ring-1 focus:ring-brand-accent/50 transition-all"
                                    placeholder="Enter your email">
                                <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                            </div>
                            <button type="submit"
                                class="group px-6 py-3.5 rounded-xl bg-gradient-to-r from-brand-accent to-brand-secondary text-black font-bold hover:shadow-lg hover:shadow-brand-accent/30 transition-all flex items-center gap-2">
                                <span>Subscribe</span>
                                <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                        <p id="newsletterMsg" class="mt-3 text-sm hidden"></p>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Footer Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8">
                <!-- Brand Column -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="flex items-center gap-3 group">
                        <div class="relative">
                            <img src="{{ asset('images/logo-cropped.png') }}" alt="Digitron Computers UAE" class="h-14 w-auto object-contain relative z-10">
                            <div class="absolute inset-0 bg-brand-accent/20 blur-xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>
                        <div class="leading-tight">
                            <div class="font-display font-bold text-xl tracking-wider text-white group-hover:text-brand-accent transition-colors">DIGITRON</div>
                            <div class="text-[10px] text-white/50 font-bold tracking-[0.3em] uppercase">Computers UAE</div>
                        </div>
                    </div>

                    <p class="text-gray-400 text-sm leading-relaxed max-w-sm">
                        The UAE's premier destination for high-performance computing hardware.
                        From custom builds to enterprise solutions, we fuel the PC Master Race.
                    </p>

                    <!-- Contact Info -->
                    <div class="space-y-3">
                        <a href="https://wa.me/971501234567" class="flex items-center gap-3 text-gray-400 hover:text-emerald-400 transition-colors group">
                            <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-emerald-500/10 transition-colors">
                                <i class="bi bi-whatsapp text-lg"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">WhatsApp</div>
                                <div class="text-sm font-medium">+971 50 123 4567</div>
                            </div>
                        </a>

                        <a href="tel:+97141234567" class="flex items-center gap-3 text-gray-400 hover:text-brand-accent transition-colors group">
                            <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-brand-accent/10 transition-colors">
                                <i class="bi bi-telephone text-lg"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Phone</div>
                                <div class="text-sm font-medium">+971 4 123 4567</div>
                            </div>
                        </a>

                        <div class="flex items-center gap-3 text-gray-400">
                            <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center">
                                <i class="bi bi-geo-alt text-lg"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Showroom</div>
                                <div class="text-sm font-medium">Al Ain Centre, Dubai</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shop Links -->
                <div class="lg:col-span-2">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-accent"></span>
                        Shop
                    </h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('shop') }}" class="group flex items-center gap-2 text-gray-400 hover:text-white transition-colors text-sm">
                                <span class="w-0 group-hover:w-2 h-px bg-brand-accent transition-all"></span>
                                <span class="group-hover:translate-x-1 transition-transform">All Products</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('shop', ['category' => 'graphics-cards']) }}" class="group flex items-center gap-2 text-gray-400 hover:text-white transition-colors text-sm">
                                <span class="w-0 group-hover:w-2 h-px bg-brand-accent transition-all"></span>
                                <span class="group-hover:translate-x-1 transition-transform">Graphics Cards</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('shop', ['category' => 'processors']) }}" class="group flex items-center gap-2 text-gray-400 hover:text-white transition-colors text-sm">
                                <span class="w-0 group-hover:w-2 h-px bg-brand-accent transition-all"></span>
                                <span class="group-hover:translate-x-1 transition-transform">Processors</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('shop', ['category' => 'memory']) }}" class="group flex items-center gap-2 text-gray-400 hover:text-white transition-colors text-sm">
                                <span class="w-0 group-hover:w-2 h-px bg-brand-accent transition-all"></span>
                                <span class="group-hover:translate-x-1 transition-transform">Memory</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('shop', ['category' => 'storage']) }}" class="group flex items-center gap-2 text-gray-400 hover:text-white transition-colors text-sm">
                                <span class="w-0 group-hover:w-2 h-px bg-brand-accent transition-all"></span>
                                <span class="group-hover:translate-x-1 transition-transform">Storage</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('shop', ['category' => 'motherboards']) }}" class="group flex items-center gap-2 text-gray-400 hover:text-white transition-colors text-sm">
                                <span class="w-0 group-hover:w-2 h-px bg-brand-accent transition-all"></span>
                                <span class="group-hover:translate-x-1 transition-transform">Motherboards</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('shop', ['category' => 'power-supply']) }}" class="group flex items-center gap-2 text-gray-400 hover:text-white transition-colors text-sm">
                                <span class="w-0 group-hover:w-2 h-px bg-brand-accent transition-all"></span>
                                <span class="group-hover:translate-x-1 transition-transform">Power Supply</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Support Links -->
                <div class="lg:col-span-2">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-secondary"></span>
                        Support
                    </h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('quote') }}" class="group flex items-center gap-2 text-gray-400 hover:text-white transition-colors text-sm">
                                <span class="w-0 group-hover:w-2 h-px bg-brand-secondary transition-all"></span>
                                <span class="group-hover:translate-x-1 transition-transform">Get a Quote</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('quote') }}#quote-section" class="group flex items-center gap-2 text-gray-400 hover:text-white transition-colors text-sm">
                                <span class="w-0 group-hover:w-2 h-px bg-brand-secondary transition-all"></span>
                                <span class="group-hover:translate-x-1 transition-transform">PC Builder</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('quote') }}" class="group flex items-center gap-2 text-gray-400 hover:text-white transition-colors text-sm">
                                <span class="w-0 group-hover:w-2 h-px bg-brand-secondary transition-all"></span>
                                <span class="group-hover:translate-x-1 transition-transform">Contact Us</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Company Links -->
                <div class="lg:col-span-2">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        Company
                    </h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('about') }}" class="group flex items-center gap-2 text-gray-400 hover:text-white transition-colors text-sm">
                                <span class="w-0 group-hover:w-2 h-px bg-emerald-400 transition-all"></span>
                                <span class="group-hover:translate-x-1 transition-transform">About Us</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('home') }}" class="group flex items-center gap-2 text-gray-400 hover:text-white transition-colors text-sm">
                                <span class="w-0 group-hover:w-2 h-px bg-emerald-400 transition-all"></span>
                                <span class="group-hover:translate-x-1 transition-transform">Home</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('shop') }}" class="group flex items-center gap-2 text-gray-400 hover:text-white transition-colors text-sm">
                                <span class="w-0 group-hover:w-2 h-px bg-emerald-400 transition-all"></span>
                                <span class="group-hover:translate-x-1 transition-transform">Shop</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Social & Payment -->
                <div class="lg:col-span-2">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-400"></span>
                        Connect
                    </h4>

                    <!-- Social Icons -->
                    <div class="flex flex-wrap gap-3 mb-8">
                        @php
                        $socials = [
                        ['icon' => 'instagram', 'color' => 'hover:text-pink-500', 'bg' => 'hover:bg-pink-500/10'],
                        ['icon' => 'facebook', 'color' => 'hover:text-blue-500', 'bg' => 'hover:bg-blue-500/10'],
                        ['icon' => 'twitter-x', 'color' => 'hover:text-white', 'bg' => 'hover:bg-white/10'],
                        ['icon' => 'youtube', 'color' => 'hover:text-red-500', 'bg' => 'hover:bg-red-500/10'],
                        ['icon' => 'tiktok', 'color' => 'hover:text-cyan-400', 'bg' => 'hover:bg-cyan-400/10'],
                        ['icon' => 'discord', 'color' => 'hover:text-indigo-400', 'bg' => 'hover:bg-indigo-400/10'],
                        ];
                        @endphp

                        @foreach($socials as $social)
                        <a href="#" class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 {{ $social['color'] }} {{ $social['bg'] }} hover:border-current transition-all group">
                            <i class="bi bi-{{ $social['icon'] }} text-lg group-hover:scale-110 transition-transform"></i>
                        </a>
                        @endforeach
                    </div>

                    <!-- Payment Methods -->
                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">We Accept</h5>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['credit-card', 'paypal', 'apple', 'google'] as $payment)
                        <div class="px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 text-gray-400 text-xs flex items-center gap-1.5">
                            <i class="bi bi-{{ $payment }}"></i>
                            <span class="capitalize">{{ $payment === 'credit-card' ? 'Card' : $payment }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center justify-center gap-6 text-xs text-gray-500">
                        <span>&copy; {{ date('Y') }} Digitron Computers UAE. All rights reserved.</span>
                        <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                        <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                        <a href="#" class="hover:text-white transition-colors">Cookie Settings</a>
                    </div>

                    <!-- Live Status -->
                    <div class="flex items-center gap-3 px-4 py-2 rounded-full bg-white/5 border border-white/10">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs text-gray-400">Store Open • <span class="text-emerald-400">Online</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>