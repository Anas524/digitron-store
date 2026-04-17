

<?php $__env->startSection('title', 'About Us | Digitron Computers UAE'); ?>

<?php $__env->startSection('page','about'); ?>

<?php $__env->startSection('fullwidth'); ?>

<section class="relative h-screen min-h-[700px] overflow-hidden flex items-center justify-center">
    <!-- Video Background -->
    <div class="absolute inset-0 w-full h-full z-0">
        <video autoplay muted loop playsinline class="object-cover w-full h-full opacity-40 scale-110" id="hero-video">
            <source src="<?php echo e(asset('videos/about-hero.mp4')); ?>" type="video/mp4">
        <div class="absolute inset-0 bg-gradient-to-b from-[#070A12]/30 via-[#070A12]/60 to-[#070A12]"></div>
    </div>

    <!-- Animated Grid Overlay -->
    <div class="absolute inset-0 bg-grid-pattern opacity-[0.03] pointer-events-none"></div>

    <?php
        $floats = [];
        for ($i = 0; $i < 30; $i++) {
            $floats[] = [
            'x' => rand(0, 100),
            'y' => rand(0, 100),
            'delay' => $i * 0.5,
            'text' => ['01001000','11001101','00110110','10101010','BYTE','CORE','GPU','CPU'][rand(0,7)],
            ];
        }
    ?>
    
    <!-- Floating Tech Elements -->
    <?php $__currentLoopData = $floats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="absolute text-brand-accent/10 text-xs font-mono floating-code"
        style="<?php echo \Illuminate\Support\Arr::toCssStyles([
        'left' => $f['x'].'%',
        'top' => $f['y'].'%',
        'animation-delay' => $f['delay'].'s',
        ]) ?>">
        <?php echo e($f['text']); ?>

    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <!-- Glowing Orbs -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-brand-accent/10 rounded-full blur-[150px] animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-brand-secondary/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>

    <!-- Content -->
    <div class="relative z-10 text-center px-4 max-w-5xl mx-auto parallax-hero">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-brand-accent/30 bg-brand-accent/10 mb-8 animate-fade-in">
            <span class="w-2 h-2 bg-brand-accent rounded-full animate-pulse"></span>
            <span class="text-brand-accent text-sm font-bold uppercase tracking-[0.2em]">Est. 2018 • Dubai, UAE</span>
        </div>

        <h1 class="text-5xl md:text-7xl lg:text-8xl font-display font-black mb-6 tracking-tight leading-none">
            WE ARE <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent via-brand-secondary to-brand-accent animate-gradient">DIGITRON</span>
        </h1>

        <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto font-light leading-relaxed mb-8">
            Fueling the PC Master Race in the UAE with premium hardware,
            expert builds, and uncompromising performance since 2018.
        </p>
    </div>

    <!-- Scroll Indicator -->
    <button type="button"
        class="absolute bottom-28 left-1/2 -translate-x-1/2 z-50 flex flex-col items-center gap-2 text-gray-300 hover:text-brand-accent transition-colors"
        onclick="scrollToStory()">
        <span class="text-xs uppercase tracking-widest">Discover Our Story</span>
        <span class="w-6 h-10 rounded-full border-2 border-current flex items-start justify-center p-2">
            <span class="w-1 h-2 bg-current rounded-full animate-bounce"></span>
        </span>
    </button>

    <!-- Corner Accents -->
    <div class="absolute top-5 left-5 w-24 h-24 border-l-2 border-t-2 border-brand-accent/30"></div>
    <div class="absolute bottom-5 right-5 w-24 h-24 border-r-2 border-b-2 border-brand-accent/30"></div>
</section>


<section class="relative -mt-20 z-20 mb-16">
    <div class="glass-panel rounded-2xl mx-4 lg:mx-auto max-w-6xl border border-white/10 p-8 shadow-2xl shadow-brand-accent/5">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <?php
            $stats = [
            ['number' => '50,000+', 'label' => 'PCs Built', 'icon' => 'pc-display'],
            ['number' => '25,000+', 'label' => 'Happy Customers', 'icon' => 'people'],
            ['number' => '6', 'label' => 'Years Strong', 'icon' => 'calendar-check'],
            ['number' => '24h', 'label' => 'Delivery Dubai', 'icon' => 'lightning-charge'],
            ];
            ?>

            <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="text-center group">
                <div class="w-16 h-16 rounded-2xl bg-brand-accent/10 flex items-center justify-center mx-auto mb-4 group-hover:bg-brand-accent/20 transition-colors group-hover:scale-110 transform duration-300">
                    <i class="bi bi-<?php echo e($stat['icon']); ?> text-3xl text-brand-accent"></i>
                </div>
                <div class="text-3xl md:text-4xl font-display font-bold text-white mb-1 counter-up" data-target="<?php echo e($stat['number']); ?>"><?php echo e($stat['number']); ?></div>
                <div class="text-sm text-gray-400 uppercase tracking-wider"><?php echo e($stat['label']); ?></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section id="story" class="py-24 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute left-0 top-0 w-1/3 h-full bg-gradient-to-r from-brand-accent/5 to-transparent pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal-text">
            <span class="text-brand-accent text-sm font-bold uppercase tracking-[0.3em] mb-4 block">Our Journey</span>
            <h2 class="text-4xl md:text-6xl font-display font-bold mb-6">BUILT FOR <span class="text-brand-secondary">GAMERS</span></h2>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg">From a small garage startup to the UAE's premier PC hardware destination.</p>
        </div>

        <!-- Timeline -->
        <div class="relative">
            <!-- Center Line -->
            <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-px bg-gradient-to-b from-brand-accent via-brand-secondary to-transparent hidden md:block"></div>

            <?php
            $milestones = [
            [
            'year' => '2018',
            'title' => 'The Beginning',
            'desc' => 'Digitron started in a small Dubai garage with a simple mission: make high-performance PCs accessible to every gamer in the UAE.',
            'image' => asset('images/timeline-img5.jpg'),
            'side' => 'left'
            ],
            [
            'year' => '2020',
            'title' => 'First Milestone',
            'desc' => 'Reached 5,000 custom builds. Expanded team to 15 enthusiasts. Launched same-day delivery in Dubai.',
            'image' => asset('images/timeline-img1.jpg'),
            'side' => 'right'
            ],
            [
            'year' => '2022',
            'title' => 'Going Premium',
            'desc' => 'Became authorized reseller for NVIDIA, ASUS, MSI, and Corsair. Opened flagship showroom in Al Ain Centre.',
            'image' => asset('images/timeline-img3.jpg'),
            'side' => 'left'
            ],
            [
            'year' => '2024',
            'title' => 'The Future',
            'desc' => '50,000+ builds completed. 24-hour delivery across UAE. AI-powered PC builder launched. Still obsessed with performance.',
            'image' => asset('images/timeline-img4.jpg'),
            'side' => 'right'
            ]
            ];
            ?>

            <div class="space-y-24">
                <?php $__currentLoopData = $milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $milestone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="relative flex items-center <?php echo e($milestone['side'] === 'right' ? 'md:flex-row-reverse' : ''); ?> group">
                    <!-- Content -->
                    <div class="w-full md:w-1/2 <?php echo e($milestone['side'] === 'right' ? 'md:pl-16' : 'md:pr-16'); ?>">
                        <div class="glass-panel rounded-2xl p-6 border border-white/10 hover:border-brand-accent/30 transition-all duration-500 hover:-translate-y-2 <?php echo e($milestone['side'] === 'right' ? 'md:text-left' : 'md:text-right'); ?>">
                            <div class="aspect-video rounded-xl overflow-hidden mb-6 relative">
                                <img src="<?php echo e($milestone['image']); ?>" alt="<?php echo e($milestone['title']); ?>"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#070A12]/80 to-transparent"></div>
                                <div class="absolute bottom-4 <?php echo e($milestone['side'] === 'right' ? 'left-4' : 'right-4'); ?>">
                                    <span class="text-5xl font-display font-bold text-white/20"><?php echo e($milestone['year']); ?></span>
                                </div>
                            </div>
                            <div class="inline-block px-3 py-1 rounded-full bg-brand-accent/10 text-brand-accent text-sm font-bold mb-3">
                                <?php echo e($milestone['year']); ?>

                            </div>
                            <h3 class="text-2xl font-bold text-white mb-3"><?php echo e($milestone['title']); ?></h3>
                            <p class="text-gray-400 leading-relaxed"><?php echo e($milestone['desc']); ?></p>
                        </div>
                    </div>

                    <!-- Center Dot -->
                    <div class="absolute left-1/2 transform -translate-x-1/2 w-6 h-6 rounded-full bg-brand-accent border-4 border-[#070A12] hidden md:block z-10 group-hover:scale-150 transition-transform shadow-lg shadow-brand-accent/50">
                        <div class="absolute inset-0 rounded-full bg-brand-accent animate-ping opacity-50"></div>
                    </div>

                    <!-- Empty Space -->
                    <div class="hidden md:block w-1/2"></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>


<section class="py-24 relative overflow-hidden isolate">
    
    <div class="absolute inset-0 z-0 pointer-events-none">
        <video autoplay muted loop playsinline preload="auto"
               class="w-full h-full object-cover opacity-40 scale-110"
               id="diff-video">
            <source src="<?php echo e(asset('videos/about-hero.mp4')); ?>" type="video/mp4">
        </video>

        
        <div class="absolute inset-0 bg-gradient-to-b from-[#070A12]/80 via-[#070A12]/70 to-[#070A12]/85"></div>
    </div>

    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-brand-secondary text-sm font-bold uppercase tracking-[0.3em] mb-4 block">Why Choose Us</span>
            <h2 class="text-4xl md:text-6xl font-display font-bold mb-6">
                THE <span class="text-brand-accent">DIGITRON</span> DIFFERENCE
            </h2>
        </div>

        <?php
            $features = [
                [
                    'icon' => 'tools',
                    'title' => 'Expert Builds',
                    'desc' => 'Every PC is hand-assembled by certified technicians with 10+ years experience. Cable management perfection guaranteed.',
                    'ring' => 'hover:border-brand-accent/50',
                    'glow' => 'from-brand-accent/10',
                    'iconBg' => 'bg-brand-accent/10',
                    'iconText' => 'text-brand-accent',
                    'titleHover' => 'group-hover:text-brand-accent',
                    'numHover' => 'group-hover:text-brand-accent/10',
                ],
                [
                    'icon' => 'lightning-charge-fill',
                    'title' => 'Same-Day Delivery',
                    'desc' => 'In-stock items delivered within 24 hours across Dubai. Real-time tracking from our warehouse to your door.',
                    'ring' => 'hover:border-brand-secondary/50',
                    'glow' => 'from-brand-secondary/10',
                    'iconBg' => 'bg-brand-secondary/10',
                    'iconText' => 'text-brand-secondary',
                    'titleHover' => 'group-hover:text-brand-secondary',
                    'numHover' => 'group-hover:text-brand-secondary/10',
                ],
                [
                    'icon' => 'shield-check',
                    'title' => '2-Year Warranty',
                    'desc' => 'Comprehensive coverage on all builds. Free troubleshooting, component replacement, and lifetime support.',
                    'ring' => 'hover:border-brand-accent/50',
                    'glow' => 'from-brand-accent/10',
                    'iconBg' => 'bg-brand-accent/10',
                    'iconText' => 'text-brand-accent',
                    'titleHover' => 'group-hover:text-brand-accent',
                    'numHover' => 'group-hover:text-brand-accent/10',
                ],
                [
                    'icon' => 'cash-coin',
                    'title' => '0% Installments',
                    'desc' => 'Spread your payments with Tabby or Tamara. No hidden fees, no interest, instant approval.',
                    'ring' => 'hover:border-brand-accent/50',
                    'glow' => 'from-brand-accent/10',
                    'iconBg' => 'bg-brand-accent/10',
                    'iconText' => 'text-brand-accent',
                    'titleHover' => 'group-hover:text-brand-accent',
                    'numHover' => 'group-hover:text-brand-accent/10',
                ],
                [
                    'icon' => 'arrow-repeat',
                    'title' => 'Easy Returns',
                    'desc' => 'Changed your mind? 30-day hassle-free returns on unopened items. No questions asked.',
                    'ring' => 'hover:border-brand-accent/50',
                    'glow' => 'from-brand-accent/10',
                    'iconBg' => 'bg-brand-accent/10',
                    'iconText' => 'text-brand-accent',
                    'titleHover' => 'group-hover:text-brand-accent',
                    'numHover' => 'group-hover:text-brand-accent/10',
                ],
                [
                    'icon' => 'headset',
                    'title' => '24/7 Support',
                    'desc' => 'Real humans, not bots. WhatsApp, call, or visit our showroom. We speak English, Arabic, and Hindi.',
                    'ring' => 'hover:border-brand-accent/50',
                    'glow' => 'from-brand-accent/10',
                    'iconBg' => 'bg-brand-accent/10',
                    'iconText' => 'text-brand-accent',
                    'titleHover' => 'group-hover:text-brand-accent',
                    'numHover' => 'group-hover:text-brand-accent/10',
                ],
            ];
        ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="group relative rounded-2xl p-8 border border-white/12 bg-white/[0.06] backdrop-blur-xl
                            <?php echo e($feature['ring']); ?> transition-all duration-500 hover:-translate-y-3 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br <?php echo e($feature['glow']); ?> to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                    <div class="relative w-16 h-16 rounded-2xl <?php echo e($feature['iconBg']); ?> flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="bi bi-<?php echo e($feature['icon']); ?> text-3xl <?php echo e($feature['iconText']); ?>"></i>
                    </div>

                    <h3 class="relative text-xl font-bold text-white mb-3 <?php echo e($feature['titleHover']); ?> transition-colors">
                        <?php echo e($feature['title']); ?>

                    </h3>
                    <p class="relative text-gray-300/80 leading-relaxed"><?php echo e($feature['desc']); ?></p>

                    <div class="absolute top-4 right-4 text-6xl font-display font-bold text-white/5 <?php echo e($feature['numHover']); ?> transition-colors">
                        0<?php echo e($index + 1); ?>

                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass-panel rounded-2xl border border-white/10 p-10 flex flex-col lg:flex-row items-center justify-between gap-8">
            <div>
                <span class="text-brand-accent text-sm font-bold uppercase tracking-[0.3em] block mb-2">Ready to build?</span>
                <h3 class="text-3xl md:text-4xl font-display font-bold text-white mb-2">
                    Get a custom quote in minutes
                </h3>
                <p class="text-gray-400 max-w-2xl">
                    Tell us your budget + usage (gaming, editing, AI) and we’ll recommend the perfect parts and price.
                </p>
            </div>

            <div class="flex gap-3">
                <a href="<?php echo e(route('quote')); ?>#quote-section"
                    class="px-7 py-4 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition flex items-center gap-2">
                    Get a Quote <i class="bi bi-arrow-right"></i>
                </a>
                <a href="<?php echo e(route('shop')); ?>"
                    class="px-7 py-4 rounded-xl border border-white/20 text-white font-bold hover:border-brand-accent hover:text-brand-accent transition">
                    Browse Store
                </a>
            </div>
        </div>
    </div>
</section>


<!-- <section class="py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal-text">
            <span class="text-brand-accent text-sm font-bold uppercase tracking-[0.3em] mb-4 block">The Crew</span>
            <h2 class="text-4xl md:text-6xl font-display font-bold mb-6">MEET THE <span class="text-brand-secondary">BUILDERS</span></h2>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg">The enthusiasts behind every masterpiece. Gamers, creators, and tech obsessives.</p>
        </div>

        <?php
        $team = [
        [
        'name' => 'Ahmed Al-Rashid',
        'role' => 'Founder & CEO',
        'bio' => 'Former esports pro turned entrepreneur. Built his first PC at 12, never stopped.',
        'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=400',
        'social' => ['twitter', 'linkedin', 'instagram']
        ],
        [
        'name' => 'Sarah Chen',
        'role' => 'Head of Engineering',
        'bio' => 'MIT grad, overclocking champion. Makes silicon sing at 6GHz+.',
        'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=400',
        'social' => ['twitter', 'linkedin', 'github']
        ],
        [
        'name' => 'Omar Hassan',
        'role' => 'Lead Technician',
        'bio' => 'Cable management wizard. 15,000+ builds, zero DOAs.',
        'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&q=80&w=400',
        'social' => ['twitter', 'instagram', 'youtube']
        ],
        [
        'name' => 'Fatima Al-Zahra',
        'role' => 'Customer Success',
        'bio' => 'Your WhatsApp bestie. Solves problems before you know you have them.',
        'image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&q=80&w=400',
        'social' => ['linkedin', 'instagram']
        ]
        ];
        ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php $__currentLoopData = $team; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="team-card group relative">
                <div class="glass-panel rounded-2xl overflow-hidden border border-white/10 hover:border-brand-accent/50 transition-all duration-500">
                    <div class="aspect-[3/4] overflow-hidden relative">
                        <img src="<?php echo e($member['image']); ?>" alt="<?php echo e($member['name']); ?>"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 grayscale group-hover:grayscale-0">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#070A12] via-transparent to-transparent opacity-80"></div>

                        <div class="absolute bottom-4 left-4 right-4 flex justify-center gap-3
                            opacity-0 translate-y-6 pointer-events-none
                            group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto
                            transition-all duration-500">
                            <?php $__currentLoopData = $member['social']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="#" class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center text-white hover:bg-brand-accent hover:text-black transition-colors">
                                <i class="bi bi-<?php echo e($social); ?>"></i>
                            </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="p-6 text-center">
                        <h3 class="text-lg font-bold text-white mb-1"><?php echo e($member['name']); ?></h3>
                        <div class="text-brand-accent text-sm font-medium mb-3"><?php echo e($member['role']); ?></div>
                        <p class="text-sm text-gray-400 leading-relaxed"><?php echo e($member['bio']); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section> -->


<section class="py-24 relative overflow-hidden">
    <!-- Parallax Background -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1920"
            alt="Showroom"
            class="w-full h-full object-cover opacity-20 parallax-bg-slow">
        <div class="absolute inset-0 bg-gradient-to-r from-[#070A12] via-[#070A12]/90 to-[#070A12]/70"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Content -->
            <div>
                <span class="text-brand-accent text-sm font-bold uppercase tracking-[0.3em] mb-4 block">Visit Us</span>
                <h2 class="text-4xl md:text-5xl font-display font-bold mb-6">EXPERIENCE THE <span class="text-brand-secondary">POWER</span></h2>
                <p class="text-gray-400 text-lg mb-8 leading-relaxed">
                    Step into our flagship showroom at Al Ain Centre. Test drive the latest GPUs,
                    feel the mechanical keyboards, and consult with our build experts in person.
                </p>

                <div class="space-y-6 mb-8">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-brand-accent/10 flex items-center justify-center text-brand-accent shrink-0">
                            <i class="bi bi-geo-alt-fill text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white mb-1">Flagship Showroom</h4>
                            <p class="text-gray-400">Victoria building<br>international city 2<br>Dubai, UAE</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-brand-secondary/10 flex items-center justify-center text-brand-secondary shrink-0">
                            <i class="bi bi-clock-fill text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white mb-1">Opening Hours</h4>
                            <p class="text-gray-400">Saturday - Thursday: 10:00 AM - 10:00 PM<br>Friday: 2:00 PM - 10:00 PM</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-400/10 flex items-center justify-center text-emerald-400 shrink-0">
                            <i class="bi bi-whatsapp text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white mb-1">Instant Support</h4>
                            <p class="text-gray-400">+971 50 124 0180<br>WhatsApp available 24/7</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4">
                    <a href="#" class="px-6 py-3 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition-colors flex items-center gap-2">
                        <i class="bi bi-map-fill"></i> Get Directions
                    </a>
                    <a href="<?php echo e(route('quote')); ?>" class="px-6 py-3 rounded-xl border border-white/20 text-white font-bold hover:border-brand-accent hover:text-brand-accent transition-colors flex items-center gap-2">
                        <i class="bi bi-headset"></i> Contact Us
                    </a>
                </div>
            </div>

            <!-- Interactive Map Placeholder -->
            <div class="relative">
                <div class="aspect-[4/3] rounded-2xl overflow-hidden border border-white/10 glass-panel relative group">
                    <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&q=80&w=800"
                        alt="Map"
                        class="w-full h-full object-cover opacity-60 group-hover:opacity-80 transition-opacity">

                    <!-- Map Pin -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                        <div class="relative">
                            <div class="w-4 h-4 bg-brand-accent rounded-full animate-ping absolute"></div>
                            <div class="w-4 h-4 bg-brand-accent rounded-full relative shadow-lg shadow-brand-accent/50"></div>
                            <div class="absolute top-6 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                <div class="glass-panel px-4 py-2 rounded-lg border border-brand-accent/30 text-sm font-bold text-white">
                                    Digitron HQ
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Overlay Controls -->
                    <div class="absolute bottom-4 right-4 flex gap-2">
                        <button class="w-10 h-10 rounded-lg bg-black/50 backdrop-blur-sm border border-white/10 flex items-center justify-center text-white hover:bg-brand-accent hover:text-black transition-colors">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                        <button class="w-10 h-10 rounded-lg bg-black/50 backdrop-blur-sm border border-white/10 flex items-center justify-center text-white hover:bg-brand-accent hover:text-black transition-colors">
                            <i class="bi bi-dash-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Floating Badge -->
                <div class="absolute -bottom-6 -left-6 glass-panel rounded-xl p-4 border border-brand-accent/30 shadow-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-emerald-500/20 flex items-center justify-center">
                            <i class="bi bi-check-circle-fill text-emerald-400 text-xl"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">Open Now</div>
                            <div class="text-xs text-gray-400">Closes 10:00 PM</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="py-24 relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-brand-accent/10 via-brand-secondary/10 to-transparent"></div>

    <!-- Floating Shapes -->
    <div class="absolute top-0 left-0 w-64 h-64 bg-brand-accent/20 rounded-full blur-[100px] animate-float"></div>
    <div class="absolute bottom-0 right-0 w-80 h-80 bg-brand-secondary/20 rounded-full blur-[120px] animate-float" style="animation-delay: 2s;"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-4xl md:text-6xl font-display font-bold mb-6">READY TO <span class="text-brand-accent">BUILD</span>?</h2>
        <p class="text-xl text-gray-400 mb-8 max-w-2xl mx-auto">
            Whether you're chasing 240Hz in competitive FPS or rendering 8K video,
            we'll build the perfect machine for your mission.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?php echo e(route('quote')); ?>" class="group px-8 py-4 rounded-xl bg-brand-accent text-black font-bold text-lg hover:bg-white transition-all flex items-center gap-2 shadow-lg shadow-brand-accent/30">
                Start Building <i class="bi bi-cpu group-hover:rotate-12 transition-transform"></i>
            </a>
            <a href="<?php echo e(route('shop')); ?>" class="px-8 py-4 rounded-xl border border-white/20 text-white font-bold text-lg hover:border-brand-accent hover:text-brand-accent transition-all flex items-center gap-2">
                Browse Store <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <!-- Trust Indicators -->
        <div class="mt-12 flex flex-wrap items-center justify-center gap-8 opacity-60">
            <div class="flex items-center gap-2 text-sm">
                <i class="bi bi-shield-check text-brand-accent"></i>
                <span>2-Year Warranty</span>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <i class="bi bi-truck text-brand-accent"></i>
                <span>Free Delivery</span>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <i class="bi bi-arrow-repeat text-brand-accent"></i>
                <span>30-Day Returns</span>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <i class="bi bi-headset text-brand-accent"></i>
                <span>24/7 Support</span>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/pages/about.blade.php ENDPATH**/ ?>