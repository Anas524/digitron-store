

<?php $__env->startSection('title', 'Admin Dashboard | Digitron Command Center'); ?>
<?php $__env->startSection('adminContentClass', 'p-0 h-full overflow-y-auto'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-[#050508] text-white relative min-h-full">
    <!-- Animated Background -->
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute inset-0 grid-bg opacity-30"></div>
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-admin-primary/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-admin-secondary/10 rounded-full blur-[100px] animate-pulse" style="animation-delay: 1s;"></div>

        <!-- Floating Particles -->
        <div class="absolute inset-0 overflow-hidden">
            <?php for($i = 0; $i < 20; $i++): ?>
                <?php
                $left=rand(0, 100);
                $top=rand(0, 100);
                $delay=$i * 0.3;
                $dur=4 + ($i % 4);
                ?>

                <div
                class="absolute w-1 h-1 bg-brand-accent/30 rounded-full floating-element"
                style="<?php echo \Illuminate\Support\Arr::toCssStyles([ "left: {$left}%" , "top: {$top}%" , "animation-delay: {$delay}s" , "animation-duration: {$dur}s" ,
                ]) ?>">
        </div>
        <?php endfor; ?>
    </div>
</div>

<div class="relative z-10">
    <!-- Top Header -->
    <header class="sticky top-0 z-30 glass-panel border-b border-white/10 px-4 py-4 lg:px-8">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <!-- Mobile Sidebar Toggle -->
                <button
                    type="button"
                    class="lg:hidden w-11 h-11 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 transition shrink-0"
                    @click.stop="sidebarOpen = true"
                    aria-label="Open sidebar">
                    <i class="bi bi-list text-xl"></i>
                </button>

                <div class="min-w-0">
                    <h1 class="text-xl lg:text-2xl font-bold admin-gradient-text leading-tight">Dashboard Overview</h1>
                    <p class="text-xs lg:text-sm text-gray-400">Welcome back, here's what's happening today.</p>
                </div>
            </div>

            <div class="flex items-center gap-2 lg:gap-4 shrink-0">
                <form action="<?php echo e(route('admin.search')); ?>" method="GET" class="relative hidden md:block">
                    <input
                        type="text"
                        name="search"
                        value="<?php echo e(request('q')); ?>"
                        placeholder="Search products, quotes, newsletter..."
                        class="w-80 pl-10 pr-4 py-2.5 rounded-xl bg-white/5 border border-white/10 focus:border-admin-primary focus:outline-none text-sm text-white transition-all">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                </form>

                <div x-data="{ open: false }" class="relative shrink-0">
                    <button
                        type="button"
                        @click.stop="open = !open"
                        class="relative w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition-colors shrink-0">
                        <i class="bi bi-bell-fill text-gray-400"></i>

                        <?php if(($notificationCount ?? 0) > 0): ?>
                        <span class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1 rounded-full bg-admin-danger text-white text-xs flex items-center justify-center font-bold">
                            <?php echo e($notificationCount > 99 ? '99+' : $notificationCount); ?>

                        </span>
                        <?php endif; ?>
                    </button>

                    <div
                        x-show="open"
                        x-transition.opacity.scale.origin.top.right
                        x-cloak
                        @click.outside="open = false"
                        class="absolute right-0 top-full mt-3 w-[300px] sm:w-[360px] max-w-[calc(100vw-2rem)] sm:max-w-[90vw] rounded-2xl border border-white/10 bg-[#0b1220]/95 backdrop-blur-xl shadow-2xl overflow-hidden z-50">
                        <div class="px-4 py-3 border-b border-white/10 flex items-start justify-between gap-3">
                            <div>
                                <div class="text-white font-bold text-sm">Notifications</div>
                                <div class="text-xs text-gray-400">
                                    <?php echo e($notificationCount ?? 0); ?> new item<?php echo e(($notificationCount ?? 0) == 1 ? '' : 's'); ?>

                                </div>
                            </div>

                            <a href="<?php echo e(route('admin.chatbot-leads.index')); ?>" class="text-xs text-admin-primary hover:underline whitespace-nowrap shrink-0">
                                View All
                            </a>
                        </div>

                        <div class="max-h-96 overflow-y-auto custom-scrollbar">
                            <?php $__empty_1 = true; $__currentLoopData = ($notifications ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                            $noteUrl = match($note['type']) {
                            'quote' => route('admin.quotes.index'),
                            'newsletter' => route('admin.newsletter.index'),
                            'chatbot_lead' => route('admin.chatbot-leads.index'),
                            default => route('admin.dashboard'),
                            };
                            ?>

                            <a href="<?php echo e($noteUrl); ?>"
                                class="block px-4 py-3 border-b border-white/5 hover:bg-white/5 transition">
                                <div class="flex items-start gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-xl bg-<?php echo e($note['color']); ?>/10 text-<?php echo e($note['color']); ?> flex items-center justify-center shrink-0">
                                        <i class="bi bi-<?php echo e($note['icon']); ?>"></i>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-semibold text-white truncate">
                                            <?php echo e($note['title']); ?>

                                        </div>
                                        <div class="text-xs text-gray-400 truncate">
                                            <?php echo e($note['subtitle']); ?>

                                        </div>
                                    </div>

                                    <div class="text-[11px] text-gray-500 shrink-0">
                                        <?php echo e($note['time']); ?>

                                    </div>
                                </div>
                            </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="px-4 py-8 text-center text-sm text-gray-500">
                                No new notifications
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <a href="<?php echo e(route('admin.products.index')); ?>"
                    class="px-3 py-2.5 lg:px-4 rounded-xl bg-admin-primary text-white font-semibold text-sm hover:bg-admin-primary/80 transition-colors flex items-center gap-2 shadow-lg shadow-admin-primary/20">
                    <i class="bi bi-plus-lg"></i>
                    <span class="hidden sm:inline">Add Product</span>
                </a>
            </div>
        </div>
    </header>

    <div class="p-8 space-y-8">
        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="stat-card glass-panel rounded-2xl p-6 border border-white/10 hover:border-<?php echo e($stat['color']); ?>/30 transition-all group">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-sm text-gray-400 mb-1"><?php echo e($stat['label']); ?></p>
                        <h3 class="text-2xl font-bold text-white group-hover:scale-105 transition-transform origin-left"><?php echo e($stat['value']); ?></h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-<?php echo e($stat['color']); ?>/10 flex items-center justify-center text-<?php echo e($stat['color']); ?>">
                        <i class="bi bi-<?php echo e($stat['icon']); ?> text-xl"></i>
                    </div>
                </div>

                <div class="flex items-center gap-2 mb-4">
                    <span class="text-<?php echo e($stat['color']); ?> text-sm font-bold flex items-center gap-1">
                        <i class="bi bi-arrow-up-short"></i>
                        <?php echo e($stat['change']); ?>

                    </span>
                    <span class="text-gray-500 text-xs">vs last month</span>
                </div>

                <div class="h-10 flex items-end gap-1">
                    <?php
                    $barBgMap = [
                    'admin-primary' => 'bg-brand-accent/20 hover:bg-brand-accent/40',
                    'admin-success' => 'bg-emerald-400/20 hover:bg-emerald-400/40',
                    'admin-secondary' => 'bg-violet-400/20 hover:bg-violet-400/40',
                    'admin-warning' => 'bg-amber-400/20 hover:bg-amber-400/40',
                    'admin-danger' => 'bg-rose-400/20 hover:bg-rose-400/40',
                    ];
                    $barBg = $barBgMap[$stat['color']] ?? 'bg-white/10 hover:bg-white/20';
                    ?>

                    <?php $__currentLoopData = $stat['chart']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $height): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex-1 rounded-t transition-all <?php echo e($barBg); ?>"
                        style="<?php echo \Illuminate\Support\Arr::toCssStyles(["height: {$height}%"]) ?>"></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Main Chart & Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 glass-panel rounded-2xl p-4 sm:p-6 border border-white/10 overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5 sm:mb-6">
                    <div class="min-w-0">
                        <h3 class="text-xl sm:text-lg font-bold leading-tight">Revenue Overview</h3>
                        <p class="text-sm text-gray-400 leading-snug">Monthly revenue performance</p>
                    </div>

                    <div class="w-full sm:w-auto">
                        <select class="admin-select w-full sm:w-auto px-3 py-2.5 rounded-xl bg-[#12141b] border border-white/10 text-sm text-white focus:outline-none focus:border-cyan-400">
                            <option value="6m">Last 6 Months</option>
                            <option value="1y">Last Year</option>
                            <option value="all">All Time</option>
                        </select>
                    </div>
                </div>

                <div class="relative h-52 sm:h-64">
                    <svg class="w-full h-full" viewBox="0 0 800 200" preserveAspectRatio="xMidYMid meet">
                        <?php for($i = 0; $i
                        < 5; $i++): ?>
                            <line x1="0" y1="<?php echo e($i * 50); ?>" x2="800" y2="<?php echo e($i * 50); ?>" stroke="rgba(255,255,255,0.05)" stroke-width="1" />
                        <?php endfor; ?>

                        <defs>
                            <linearGradient id="chartGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#00f0ff;stop-opacity:0.3" />
                                <stop offset="100%" style="stop-color:#00f0ff;stop-opacity:0" />
                            </linearGradient>
                        </defs>

                        <?php
                        $linePath = '';
                        $areaPath = '';

                        if (!empty($monthlyRevenuePoints)) {
                        $first = $monthlyRevenuePoints[0];
                        $linePath = 'M' . $first['x'] . ',' . $first['y'];

                        foreach (array_slice($monthlyRevenuePoints, 1) as $point) {
                        $linePath .= ' L' . $point['x'] . ',' . $point['y'];
                        }

                        $last = end($monthlyRevenuePoints);
                        $areaPath = $linePath . ' L' . $last['x'] . ',200 L' . $first['x'] . ',200 Z';
                        }
                        ?>

                        <?php if($areaPath): ?>
                        <path d="<?php echo e($areaPath); ?>" fill="url(#chartGradient)" class="opacity-50" />
                        <?php endif; ?>

                        <?php if($linePath): ?>
                        <path d="<?php echo e($linePath); ?>" fill="none" stroke="#00f0ff" stroke-width="3" class="chart-line" />
                        <?php endif; ?>

                        <?php $__currentLoopData = $monthlyRevenuePoints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <circle cx="<?php echo e($point['x']); ?>" cy="<?php echo e($point['y']); ?>" r="4" fill="#00f0ff" class="sm:r-[6] transition-all" />
                        <circle cx="<?php echo e($point['x']); ?>" cy="<?php echo e($point['y']); ?>" r="7" fill="transparent" stroke="#00f0ff" stroke-width="2" opacity="0.3">
                            <animate attributeName="r" values="10;15;10" dur="2s" repeatCount="indefinite" />
                            <animate attributeName="opacity" values="0.3;0;0.3" dur="2s" repeatCount="indefinite" />
                        </circle>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </svg>

                    <div class="absolute bottom-0 left-0 right-0 flex justify-between text-[11px] sm:text-xs text-gray-500 px-1 sm:px-4">
                        <?php $__currentLoopData = $monthlyRevenueLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="flex-1 text-center truncate"><?php echo e($label); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <div class="glass-panel rounded-2xl p-6 border border-white/10">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold">Live Activity</h3>
                    <div class="flex items-center gap-2 text-xs text-admin-success">
                        <span class="w-2 h-2 bg-admin-success rounded-full animate-pulse"></span>
                        Live
                    </div>
                </div>

                <div class="space-y-4 max-h-80 overflow-y-auto overflow-x-hidden custom-scrollbar pr-2">
                    <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="activity-item flex items-start gap-3 p-3 rounded-xl cursor-pointer">
                        <div class="w-10 h-10 rounded-lg bg-<?php echo e($activity['color']); ?>/10 flex items-center justify-center text-<?php echo e($activity['color']); ?> shrink-0">
                            <i class="bi bi-<?php echo e($activity['icon']); ?>"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-white truncate"><?php echo e($activity['title']); ?></div>
                            <div class="text-xs text-gray-400 truncate"><?php echo e($activity['desc']); ?></div>
                        </div>
                        <div class="text-xs text-gray-500 shrink-0"><?php echo e($activity['time']); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Quick Access Modules -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="<?php echo e(route('admin.products.index')); ?>" class="group relative overflow-hidden rounded-2xl glass-panel border border-white/10 p-6 hover:border-admin-primary/50 transition-all">
                <div class="absolute inset-0 bg-gradient-to-br from-admin-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-700 flex items-center justify-center text-white text-[1.65rem] shadow-lg shadow-cyan-900/30 group-hover:scale-110 transition-transform">
                            <i class="bi bi-box-seam-fill leading-none"></i>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-admin-success/20 text-admin-success text-xs font-bold">
                            <?php echo e($newProductsToday ?? 0); ?> today
                        </span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Products</h3>
                    <p class="text-sm text-gray-400 mb-4">Manage inventory, add new items, update pricing and stock levels.</p>
                    <div class="flex items-center gap-2 text-admin-primary font-medium text-sm group-hover:gap-4 transition-all">
                        Manage Products <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </a>

            <a href="<?php echo e(route('admin.newsletter.index')); ?>" class="group relative overflow-hidden rounded-2xl glass-panel border border-white/10 p-6 hover:border-admin-warning/50 transition-all">
                <div class="absolute inset-0 bg-gradient-to-br from-admin-warning/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-700 flex items-center justify-center text-white text-[1.65rem] shadow-lg shadow-orange-950/30 group-hover:scale-110 transition-transform">
                            <i class="bi bi-envelope-paper-fill leading-none"></i>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-admin-primary/20 text-admin-primary text-xs font-bold">
                            <?php echo e(number_format($newsletterCount)); ?> subs
                        </span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Newsletter</h3>
                    <p class="text-sm text-gray-400 mb-4">View subscribers, export lists, and manage email campaigns.</p>
                    <div class="flex items-center gap-2 text-admin-warning font-medium text-sm group-hover:gap-4 transition-all">
                        View Subscribers <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </a>

            <a href="<?php echo e(route('admin.quotes.index')); ?>" class="group relative overflow-hidden rounded-2xl glass-panel border border-white/10 p-6 hover:border-admin-success/50 transition-all">
                <div class="absolute inset-0 bg-gradient-to-br from-admin-success/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white text-[1.65rem] shadow-lg shadow-emerald-950/30 group-hover:scale-110 transition-transform">
                            <i class="bi bi-file-text-fill leading-none"></i>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-admin-danger/20 text-admin-danger text-xs font-bold">
                            <?php echo e(number_format($pendingQuotesCount)); ?> pending
                        </span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Quotes</h3>
                    <p class="text-sm text-gray-400 mb-4">Review custom PC build requests and respond to customer inquiries.</p>
                    <div class="flex items-center gap-2 text-admin-success font-medium text-sm group-hover:gap-4 transition-all">
                        Review Quotes <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>