@extends('layouts.admin')

@section('title', 'Admin Dashboard | Digitron Command Center')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Admin Custom Styles */
    :root {
        --admin-primary: #00f0ff;
        --admin-secondary: #7000ff;
        --admin-success: #00ff88;
        --admin-warning: #ffaa00;
        --admin-danger: #ff2d55;
        --admin-bg: #050508;
        --admin-panel: #0a0a0f;
    }

    .admin-gradient-text {
        background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .glass-panel {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .neon-glow {
        box-shadow: 0 0 20px rgba(0, 240, 255, 0.15),
            0 0 40px rgba(0, 240, 255, 0.1),
            0 0 60px rgba(0, 240, 255, 0.05);
    }

    .sidebar-link {
        position: relative;
        overflow: hidden;
    }

    .sidebar-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: var(--admin-primary);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .sidebar-link:hover::before,
    .sidebar-link.active::before {
        transform: scaleY(1);
    }

    .stat-card {
        position: relative;
        overflow: hidden;
    }

    .stat-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(0, 240, 255, 0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    .stat-card:hover::after {
        opacity: 1;
    }

    .chart-container {
        position: relative;
    }

    .chart-line {
        stroke-dasharray: 1000;
        stroke-dashoffset: 1000;
        animation: drawLine 2s ease forwards;
    }

    @keyframes drawLine {
        to {
            stroke-dashoffset: 0;
        }
    }

    .pulse-ring {
        position: absolute;
        border-radius: 50%;
        border: 2px solid var(--admin-success);
        animation: pulseRing 2s ease-out infinite;
    }

    @keyframes pulseRing {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        100% {
            transform: scale(1.5);
            opacity: 0;
        }
    }

    .floating-element {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    .grid-bg {
        background-image:
            linear-gradient(rgba(0, 240, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 240, 255, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
    }

    .activity-item {
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        transform: translateX(10px);
        background: rgba(255, 255, 255, 0.05);
    }

    .progress-ring {
        transform: rotate(-90deg);
    }

    .progress-ring-circle {
        transition: stroke-dashoffset 1s ease;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen flex bg-[#050508] text-white relative overflow-hidden">
    <!-- Animated Background -->
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full grid-bg opacity-30"></div>
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-admin-primary/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-admin-secondary/10 rounded-full blur-[100px] animate-pulse" style="animation-delay: 1s;"></div>

        <!-- Floating Particles -->
        <div class="absolute inset-0 overflow-hidden">
            @for ($i = 0; $i < 20; $i++)
                @php
                $left=rand(0, 100);
                $top=rand(0, 100);
                $delay=$i * 0.3;
                $dur=4 + ($i % 4);
                @endphp

                <div class="absolute w-1 h-1 bg-brand-accent/30 rounded-full floating-element"
                @style([ "left: {$left}%" , "top: {$top}%" , "animation-delay: {$delay}s" , "animation-duration: {$dur}s" ,
                ])>
        </div>
        @endfor
    </div>
</div>

{{-- Sidebar --}}
<aside class="w-[280px] shrink-0 glass-panel border-r border-white/10 relative z-20 flex flex-col">
    <!-- Logo Section -->
    <div class="p-6 border-b border-white/10 relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-r from-admin-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative flex items-center gap-3">
            <div class="w-16 h-16 rounded-xl overflow-hidden flex items-center justify-center">
                <img src="{{ asset('images/logo-croped.png') }}" alt="Digitron" class="w-full h-full object-contain p-1">
            </div>
            <div>
                <div class="text-lg font-bold tracking-wide">DIGITRON</div>
                <div class="text-[10px] text-admin-primary uppercase tracking-[0.2em]">Command Center</div>
            </div>
        </div>
    </div>

    <!-- User Profile Mini -->
    <div class="p-4 border-b border-white/10">
        <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5">
            <div class="relative">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-sm font-bold">
                    {{ substr(auth()->user()->email, 0, 1) }}
                </div>
                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-admin-success rounded-full border-2 border-[#0a0a0f]"></div>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium truncate">{{ auth()->user()->email }}</div>
                <div class="text-xs text-admin-success flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-admin-success rounded-full animate-pulse"></span>
                    Online
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto custom-scrollbar">
        <div class="text-[10px] uppercase tracking-wider text-gray-500 mb-3 px-2">Main</div>

        <a href="{{ route('admin.dashboard') }}" class="sidebar-link active flex items-center gap-3 px-4 py-3 rounded-xl bg-white/10 text-white transition-all group">
            <div class="w-8 h-8 rounded-lg bg-admin-primary/20 flex items-center justify-center text-admin-primary group-hover:scale-110 transition-transform">
                <i class="bi bi-grid-1x2-fill"></i>
            </div>
            <span class="font-medium">Dashboard</span>
            <div class="ml-auto w-2 h-2 rounded-full bg-admin-primary shadow-lg shadow-admin-primary/50"></div>
        </a>

        <div class="text-[10px] uppercase tracking-wider text-gray-500 mb-3 mt-6 px-2">Management</div>

        <a href="{{ route('admin.products.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all group">
            <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-admin-secondary/20 group-hover:text-admin-secondary transition-colors">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <span>Products</span>
            <span class="ml-auto text-xs bg-white/10 px-2 py-0.5 rounded-full">248</span>
        </a>

        <a href="{{ route('admin.newsletter.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all group">
            <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-admin-warning/20 group-hover:text-admin-warning transition-colors">
                <i class="bi bi-envelope-paper-fill"></i>
            </div>
            <span>Newsletter</span>
            <span class="ml-auto text-xs bg-admin-primary/20 text-admin-primary px-2 py-0.5 rounded-full">+12</span>
        </a>

        <a href="{{ route('admin.quotes.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all group">
            <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-admin-success/20 group-hover:text-admin-success transition-colors">
                <i class="bi bi-file-text-fill"></i>
            </div>
            <span>Quotes</span>
            <span class="ml-auto text-xs bg-admin-danger/20 text-admin-danger px-2 py-0.5 rounded-full">5 new</span>
        </a>

        <div class="text-[10px] uppercase tracking-wider text-gray-500 mb-3 mt-6 px-2">System</div>

        <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all group">
            <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:text-white transition-colors">
                <i class="bi bi-gear-fill"></i>
            </div>
            <span>Settings</span>
        </a>

        <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all group">
            <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:text-white transition-colors">
                <i class="bi bi-shield-check"></i>
            </div>
            <span>Security</span>
        </a>

        <div class="text-[10px] uppercase tracking-wider text-gray-500 mb-3 mt-6 px-2">Pages</div>

        <a href="{{ route('home') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all group">
            <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:text-white transition-colors">
                <i class="bi bi-house-door"></i>
            </div>
            <span>Home</span>
        </a>

        <a href="{{ route('shop') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all group">
            <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:text-white transition-colors">
                <i class="bi bi-basket3"></i>
            </div>
            <span>Shop</span>
        </a>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-white/10">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-admin-danger hover:bg-admin-danger/10 transition-all group">
                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-admin-danger/20 transition-colors">
                    <i class="bi bi-box-arrow-right"></i>
                </div>
                <span class="font-medium">Logout</span>
            </button>
        </form>
    </div>
</aside>

{{-- Main Content --}}
<main class="flex-1 relative z-10 overflow-y-auto">
    <!-- Top Header -->
    <header class="sticky top-0 z-30 glass-panel border-b border-white/10 px-8 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold admin-gradient-text">Dashboard Overview</h1>
                <p class="text-sm text-gray-400">Welcome back, here's what's happening today.</p>
            </div>

            <div class="flex items-center gap-4">
                <!-- Search -->
                <div class="relative hidden md:block">
                    <input type="text" placeholder="Search anything..."
                        class="w-64 pl-10 pr-4 py-2.5 rounded-xl bg-white/5 border border-white/10 focus:border-admin-primary focus:outline-none text-sm transition-all">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                </div>

                <!-- Notifications -->
                <button class="relative w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition-colors">
                    <i class="bi bi-bell-fill text-gray-400"></i>
                    <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-admin-danger text-white text-xs flex items-center justify-center font-bold">3</span>
                </button>

                <!-- Quick Actions -->
                <button class="px-4 py-2.5 rounded-xl bg-admin-primary text-black font-bold text-sm hover:bg-white transition-colors flex items-center gap-2">
                    <i class="bi bi-plus-lg"></i>
                    <span class="hidden sm:inline">Add Product</span>
                </button>
            </div>
        </div>
    </header>

    <div class="p-8 space-y-8">
        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
            $stats = [
            ['label' => 'Total Revenue', 'value' => 'AED 128,450', 'change' => '+12.5%', 'icon' => 'cash-stack', 'color' => 'admin-primary', 'chart' => [30, 45, 35, 50, 40, 60, 55]],
            ['label' => 'Orders', 'value' => '1,284', 'change' => '+8.2%', 'icon' => 'cart-check', 'color' => 'admin-success', 'chart' => [40, 30, 45, 35, 50, 40, 60]],
            ['label' => 'Products', 'value' => '248', 'change' => '+3', 'icon' => 'box-seam', 'color' => 'admin-secondary', 'chart' => [50, 40, 60, 45, 55, 50, 65]],
            ['label' => 'Customers', 'value' => '3,642', 'change' => '+18.3%', 'icon' => 'people-fill', 'color' => 'admin-warning', 'chart' => [35, 45, 40, 55, 50, 60, 70]],
            ];
            @endphp

            @foreach($stats as $stat)
            <div class="stat-card glass-panel rounded-2xl p-6 border border-white/10 hover:border-{{ $stat['color'] }}/30 transition-all group">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-sm text-gray-400 mb-1">{{ $stat['label'] }}</p>
                        <h3 class="text-2xl font-bold text-white group-hover:scale-105 transition-transform origin-left">{{ $stat['value'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-{{ $stat['color'] }}/10 flex items-center justify-center text-{{ $stat['color'] }}">
                        <i class="bi bi-{{ $stat['icon'] }} text-xl"></i>
                    </div>
                </div>

                <div class="flex items-center gap-2 mb-4">
                    <span class="text-{{ $stat['color'] }} text-sm font-bold flex items-center gap-1">
                        <i class="bi bi-arrow-up-short"></i>
                        {{ $stat['change'] }}
                    </span>
                    <span class="text-gray-500 text-xs">vs last month</span>
                </div>

                <!-- Mini Sparkline -->
                <div class="h-10 flex items-end gap-1">
                    @php
                    $barBgMap = [
                    'admin-primary' => 'bg-brand-accent/20 hover:bg-brand-accent/40',
                    'admin-success' => 'bg-emerald-400/20 hover:bg-emerald-400/40',
                    'admin-secondary' => 'bg-violet-400/20 hover:bg-violet-400/40',
                    'admin-warning' => 'bg-amber-400/20 hover:bg-amber-400/40',
                    'admin-danger' => 'bg-rose-400/20 hover:bg-rose-400/40',
                    ];

                    $barBg = $barBgMap[$stat['color']] ?? 'bg-white/10 hover:bg-white/20';
                    @endphp
                    @foreach($stat['chart'] as $height)
                    <div class="flex-1 rounded-t transition-all {{ $barBg }}"
                        @style(["height: {$height}%"])></div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <!-- Main Chart & Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Revenue Chart -->
            <div class="lg:col-span-2 glass-panel rounded-2xl p-6 border border-white/10">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold">Revenue Overview</h3>
                        <p class="text-sm text-gray-400">Monthly revenue performance</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <select class="px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 text-sm focus:outline-none focus:border-admin-primary">
                            <option>Last 6 Months</option>
                            <option>Last Year</option>
                            <option>All Time</option>
                        </select>
                    </div>
                </div>

                <!-- SVG Chart -->
                <div class="h-64 relative">
                    <svg class="w-full h-full" viewBox="0 0 800 200" preserveAspectRatio="none">
                        <!-- Grid Lines -->
                        @for($i = 0; $i
                        < 5; $i++)
                            <line x1="0" y1="{{ $i * 50 }}" x2="800" y2="{{ $i * 50 }}" stroke="rgba(255,255,255,0.05)" stroke-width="1" />
                        @endfor

                        <!-- Gradient Definition -->
                        <defs>
                            <linearGradient id="chartGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#00f0ff;stop-opacity:0.3" />
                                <stop offset="100%" style="stop-color:#00f0ff;stop-opacity:0" />
                            </linearGradient>
                        </defs>

                        <!-- Area -->
                        <path d="M0,150 Q100,120 200,100 T400,80 T600,60 T800,40 L800,200 L0,200 Z"
                            fill="url(#chartGradient)" class="opacity-50" />

                        <!-- Line -->
                        <path d="M0,150 Q100,120 200,100 T400,80 T600,60 T800,40"
                            fill="none" stroke="#00f0ff" stroke-width="3" class="chart-line" />

                        <!-- Data Points -->
                        @foreach([[0,150], [200,100], [400,80], [600,60], [800,40]] as $point)
                        <circle cx="{{ $point[0] }}" cy="{{ $point[1] }}" r="6" fill="#00f0ff" class="hover:r-8 transition-all" />
                        <circle cx="{{ $point[0] }}" cy="{{ $point[1] }}" r="10" fill="transparent" stroke="#00f0ff" stroke-width="2" opacity="0.3">
                            <animate attributeName="r" values="10;15;10" dur="2s" repeatCount="indefinite" />
                            <animate attributeName="opacity" values="0.3;0;0.3" dur="2s" repeatCount="indefinite" />
                        </circle>
                        @endforeach
                    </svg>

                    <!-- X Axis Labels -->
                    <div class="absolute bottom-0 left-0 right-0 flex justify-between text-xs text-gray-500 px-4">
                        <span>Jan</span>
                        <span>Feb</span>
                        <span>Mar</span>
                        <span>Apr</span>
                        <span>May</span>
                        <span>Jun</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="glass-panel rounded-2xl p-6 border border-white/10">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold">Live Activity</h3>
                    <div class="flex items-center gap-2 text-xs text-admin-success">
                        <span class="w-2 h-2 bg-admin-success rounded-full animate-pulse"></span>
                        Live
                    </div>
                </div>

                <div class="space-y-4 max-h-80 overflow-y-auto custom-scrollbar pr-2">
                    @php
                    $activities = [
                    ['type' => 'order', 'title' => 'New order #1284', 'desc' => 'RTX 4070 Super - AED 2,799', 'time' => '2 min ago', 'icon' => 'cart', 'color' => 'admin-primary'],
                    ['type' => 'user', 'title' => 'New registration', 'desc' => 'ahmed@example.com joined', 'time' => '5 min ago', 'icon' => 'person-plus', 'color' => 'admin-success'],
                    ['type' => 'quote', 'title' => 'Quote request', 'desc' => 'Custom PC build inquiry', 'time' => '12 min ago', 'icon' => 'file-text', 'color' => 'admin-warning'],
                    ['type' => 'review', 'title' => 'New 5-star review', 'desc' => 'Ryzen 7 7800X3D', 'time' => '18 min ago', 'icon' => 'star', 'color' => 'admin-secondary'],
                    ['type' => 'order', 'title' => 'Order shipped #1283', 'desc' => 'Delivered to Dubai', 'time' => '25 min ago', 'icon' => 'truck', 'color' => 'admin-primary'],
                    ['type' => 'system', 'title' => 'Low stock alert', 'desc' => 'DDR5 32GB only 5 left', 'time' => '32 min ago', 'icon' => 'exclamation-triangle', 'color' => 'admin-danger'],
                    ];
                    @endphp

                    @foreach($activities as $activity)
                    <div class="activity-item flex items-start gap-3 p-3 rounded-xl cursor-pointer">
                        <div class="w-10 h-10 rounded-lg bg-{{ $activity['color'] }}/10 flex items-center justify-center text-{{ $activity['color'] }} shrink-0">
                            <i class="bi bi-{{ $activity['icon'] }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-white truncate">{{ $activity['title'] }}</div>
                            <div class="text-xs text-gray-400 truncate">{{ $activity['desc'] }}</div>
                        </div>
                        <div class="text-xs text-gray-500 shrink-0">{{ $activity['time'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick Access Modules -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Products Module -->
            <a href="{{ route('admin.products.index') }}" class="group relative overflow-hidden rounded-2xl glass-panel border border-white/10 p-6 hover:border-admin-primary/50 transition-all">
                <div class="absolute inset-0 bg-gradient-to-br from-admin-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-admin-primary to-admin-secondary flex items-center justify-center text-black text-2xl shadow-lg shadow-admin-primary/30 group-hover:scale-110 transition-transform">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-admin-success/20 text-admin-success text-xs font-bold">+3 today</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Products</h3>
                    <p class="text-sm text-gray-400 mb-4">Manage inventory, add new items, update pricing and stock levels.</p>
                    <div class="flex items-center gap-2 text-admin-primary font-medium text-sm group-hover:gap-4 transition-all">
                        Manage Products <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </a>

            <!-- Newsletter Module -->
            <a href="{{ route('admin.newsletter.index') }}" class="group relative overflow-hidden rounded-2xl glass-panel border border-white/10 p-6 hover:border-admin-warning/50 transition-all">
                <div class="absolute inset-0 bg-gradient-to-br from-admin-warning/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-admin-warning to-orange-500 flex items-center justify-center text-black text-2xl shadow-lg shadow-admin-warning/30 group-hover:scale-110 transition-transform">
                            <i class="bi bi-envelope-paper-fill"></i>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-admin-primary/20 text-admin-primary text-xs font-bold">2,847 subs</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Newsletter</h3>
                    <p class="text-sm text-gray-400 mb-4">View subscribers, export lists, and manage email campaigns.</p>
                    <div class="flex items-center gap-2 text-admin-warning font-medium text-sm group-hover:gap-4 transition-all">
                        View Subscribers <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </a>

            <!-- Quotes Module -->
            <a href="{{ route('admin.quotes.index') }}" class="group relative overflow-hidden rounded-2xl glass-panel border border-white/10 p-6 hover:border-admin-success/50 transition-all">
                <div class="absolute inset-0 bg-gradient-to-br from-admin-success/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-admin-success to-emerald-600 flex items-center justify-center text-black text-2xl shadow-lg shadow-admin-success/30 group-hover:scale-110 transition-transform">
                            <i class="bi bi-file-text-fill"></i>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-admin-danger/20 text-admin-danger text-xs font-bold">5 pending</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Quotes</h3>
                    <p class="text-sm text-gray-400 mb-4">Review custom PC build requests and respond to customer inquiries.</p>
                    <div class="flex items-center gap-2 text-admin-success font-medium text-sm group-hover:gap-4 transition-all">
                        Review Quotes <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- System Status -->
        <!-- <div class="glass-panel rounded-2xl p-6 border border-white/10">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold">System Health</h3>
                <span class="px-3 py-1 rounded-full bg-admin-success/20 text-admin-success text-xs font-bold flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-admin-success rounded-full animate-pulse"></span>
                    All Systems Operational
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @php
                $systems = [
                ['name' => 'Database', 'status' => 98, 'color' => 'admin-success'],
                ['name' => 'Storage', 'status' => 72, 'color' => 'admin-warning'],
                ['name' => 'Memory', 'status' => 45, 'color' => 'admin-primary'],
                ['name' => 'CPU Load', 'status' => 23, 'color' => 'admin-secondary'],
                ];
                @endphp

                @foreach($systems as $system)
                <div class="text-center">
                    <div class="relative w-24 h-24 mx-auto mb-3">
                        <svg class="w-full h-full progress-ring" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="8" />
                            <circle cx="50" cy="50" r="45" fill="none" stroke="var(--admin-{{ str_replace('admin-', '', $system['color']) }})"
                                stroke-width="8" stroke-linecap="round"
                                stroke-dasharray="283"
                                stroke-dashoffset="{{ 283 - (283 * $system['status'] / 100) }}"
                                class="progress-ring-circle" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-xl font-bold">{{ $system['status'] }}%</span>
                        </div>
                    </div>
                    <div class="text-sm text-gray-400">{{ $system['name'] }}</div>
                </div>
                @endforeach
            </div>
        </div> -->
    </div>
</main>
</div>
@endsection

@push('scripts')
<script>
    // Real-time clock
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
        const dateString = now.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // You can add this to the header if desired
    }
    setInterval(updateTime, 1000);

    // Animate stats on load
    document.addEventListener('DOMContentLoaded', () => {
        const statValues = document.querySelectorAll('.stat-card h3');
        statValues.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            setTimeout(() => {
                el.style.transition = 'all 0.6s ease';
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });

    // Simulate real-time updates
    setInterval(() => {
        const pulseElements = document.querySelectorAll('.animate-pulse');
        pulseElements.forEach(el => {
            el.style.animation = 'none';
            el.offsetHeight; // Trigger reflow
            el.style.animation = null;
        });
    }, 5000);
</script>
@endpush