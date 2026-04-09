
<div id="site-loader"
    style="position: fixed; inset: 0; width: 100vw; height: 100vh; z-index: 99999; background: #050508;"
    class="flex items-center justify-center overflow-hidden">

    <!-- Animated Background Grid -->
    <div class="absolute inset-0 bg-grid-pattern opacity-[0.05]"></div>

    <!-- Floating Tech Elements -->
    <div class="absolute inset-0 pointer-events-none">
        <?php for($i = 0; $i < 30; $i++): ?>
            <?php
            $left=rand(0, 100);
            $top=rand(0, 100);
            $delay=$i * 0.1;
            $labels=['0', '1' , 'BYTE' , 'CORE' , 'GPU' , 'CPU' , 'RAM' , 'SSD' ];
            $label=$labels[array_rand($labels)];
            ?>

            <div
            class="loader-particle absolute text-brand-accent/20 text-xs font-mono"
            data-left="<?php echo e($left); ?>"
            data-top="<?php echo e($top); ?>"
            data-delay="<?php echo e($delay); ?>">
            <?php echo e($label); ?>

    </div>
    <?php endfor; ?>
</div>

<!-- Glowing Orbs -->
<div class="absolute top-1/4 left-1/4 w-96 h-96 bg-brand-accent/10 rounded-full blur-[150px] animate-pulse"></div>
<div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-brand-secondary/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 1s;"></div>

<!-- Main Loader Content -->
<div class="relative z-10 flex flex-col items-center">
    <!-- Logo Animation -->
    <div class="loader-logo mb-5 relative flex items-center justify-center">
        <div class="relative flex items-center justify-center">
            <div class="absolute w-36 h-36 rounded-full bg-brand-accent/10 blur-3xl"></div>

            <img
                src="<?php echo e(asset('images/logo-cropped.png')); ?>"
                alt="Digitron Computers UAE"
                class="relative w-28 md:w-36 lg:w-40 object-contain animate-logo-pulse">
        </div>

        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <svg class="w-36 h-36 animate-orbit" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="45" fill="none" stroke="rgba(0, 240, 255, 0.16)" stroke-width="1" stroke-dasharray="10 5" />
                <circle cx="50" cy="5" r="3.5" fill="#00f0ff" class="animate-orbit-dot" />
            </svg>
        </div>
    </div>

    <!-- Brand Name -->
    <div class="loader-text text-center mb-8">
        <!-- <h1 class="text-2xl sm:text-4xl md:text-6xl font-display font-black tracking-[0.08em] uppercase leading-none">
            <span class="block text-white">Digitron</span>
            <span class="block text-brand-accent mt-2">Computers UAE</span>
        </h1> -->
        <h1 class="text-2xl sm:text-4xl md:text-5xl font-display font-bold mb-2">
            <span class="block text-white mb-2 mt-2">Digitron</span>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-brand-secondary">Computers UAE</span>
        </h1>
        <p class="text-gray-400 text-xs sm:text-sm tracking-[0.35em] uppercase mt-4">Loading Experience</p>
    </div>

    <!-- Progress Bar -->
    <div class="w-64 md:w-80 h-1 bg-white/10 rounded-full overflow-hidden mb-4 relative">
        <div class="loader-progress h-full bg-gradient-to-r from-brand-accent to-brand-secondary rounded-full" style="width: 0%"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full animate-shimmer"></div>
    </div>

    <!-- Loading Status -->
    <div class="loader-status text-center mt-3">
        <p class="text-brand-accent text-sm font-mono animate-pulse" id="loader-text">Initializing systems...</p>
        <p class="text-gray-500 text-xs mt-2" id="loader-percentage">0%</p>
    </div>

    <!-- Skip Option -->
    <button
        type="button"
        onclick="skipLoader()"
        class="mt-6 text-gray-500 hover:text-white text-xs uppercase tracking-wider transition-colors flex items-center gap-2 group">
        <span>Press any key to skip</span>
        <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
    </button>
</div>

<!-- Corner Accents -->
<div class="absolute top-8 left-8 w-16 h-16 border-l-2 border-t-2 border-brand-accent/30 loader-corner"></div>
<div class="absolute top-8 right-8 w-16 h-16 border-r-2 border-t-2 border-brand-accent/30 loader-corner"></div>
<div class="absolute bottom-8 left-8 w-16 h-16 border-l-2 border-b-2 border-brand-accent/30 loader-corner"></div>
<div class="absolute bottom-8 right-8 w-16 h-16 border-r-2 border-b-2 border-brand-accent/30 loader-corner"></div>
</div><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/partials/loader.blade.php ENDPATH**/ ?>