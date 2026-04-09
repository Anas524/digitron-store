<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title><?php echo $__env->yieldContent('title', config('app.name')); ?></title>
  <link rel="icon" href="<?php echo e(asset('images/icon.png')); ?>">
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <script>
    (function() {
      const nav = performance.getEntriesByType('navigation')[0];
      const isReload = nav && nav.type === 'reload';

      if (isReload) {
        document.documentElement.classList.add('is-reload');
      } else {
        document.documentElement.classList.add('not-reload');
      }
    })();
  </script>

  <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css','resources/js/app.js']); ?>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Rajdhani:wght@300;500;600;700&display=swap" rel="stylesheet">
  <link rel="preload" href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&display=swap" as="style">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- GSAP -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

  <!-- Lenis Smooth Scroll -->
  <script src="https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.29/bundled/lenis.min.js"></script>

  <style>
    [x-cloak] {
      display: none !important;
    }

    body.loader-active {
      overflow: hidden;
    }

    #site-loader {
      display: none;
    }

    html.is-reload #site-loader {
      display: flex;
    }

    html.is-reload body:not(.loaded)>*:not(#site-loader):not(script) {
      opacity: 0;
      visibility: hidden;
    }

    html.is-reload body.loaded>*:not(#site-loader):not(script) {
      opacity: 1;
      visibility: visible;
      transition: opacity 0.3s ease;
    }
  </style>
</head>

<body class="text-white" data-bg-parallax="1" data-page="<?php echo $__env->yieldContent('page',''); ?>" data-no-lenis="<?php echo $__env->yieldContent('noLenis','0'); ?>" data-auth="<?php echo e(auth()->check() ? 1 : 0); ?>" data-login-url="<?php echo e(route('login')); ?>">

  <?php echo $__env->make('partials.loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  
  <?php echo $__env->yieldContent('fullwidth'); ?>

  
  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <?php echo $__env->yieldContent('content'); ?>
  </main>

  <?php echo $__env->yieldContent('cta'); ?>

  <?php
  $whatsAppNumber = '971501234567'; // replace with real number
  ?>

  <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php echo $__env->make('partials.account-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php echo $__env->make('partials.mobile-account-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div
    class="dc-chatbot"
    data-wa-number="<?php echo e($whatsAppNumber); ?>"
    data-wa-default="Hello Digitron Computers UAE, I need support.">
    
    <button
      type="button"
      class="dc-whatsapp-float dc-chatbot-toggle"
      aria-label="Open support chat">
      <i class="bi bi-whatsapp"></i>
    </button>

    
    <div class="dc-chatbot-panel hidden" id="dcChatbotPanel">
      <div class="dc-chatbot-head">
        <div class="dc-chatbot-head-left">
          <div class="dc-chatbot-avatar">
            <i class="bi bi-whatsapp"></i>
          </div>
          <div>
            <div class="dc-chatbot-title">Digitron Support</div>
            <div class="dc-chatbot-sub">Quick answers here</div>
          </div>
        </div>

        <button type="button" class="dc-chatbot-close" aria-label="Close chat">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <div class="dc-chatbot-body custom-scrollbar" id="dcChatbotBody">
        <div class="dc-chat-msg dc-chat-msg-bot">
          <div class="dc-chat-bubble">
            Hi 👋 Welcome to Digitron Computers UAE.<br>
            Ask me about delivery, warranty, custom PC builds, payment, store location, or stock.
          </div>
        </div>

        <div class="dc-chat-quick">
          <button type="button" class="dc-chat-chip" data-question="Do you offer delivery in UAE?">Delivery</button>
          <button type="button" class="dc-chat-chip" data-question="What warranty do you provide?">Warranty</button>
          <button type="button" class="dc-chat-chip" data-question="Can you build a custom gaming PC?">Custom PC</button>
          <button type="button" class="dc-chat-chip" data-question="What payment methods do you accept?">Payment</button>
          <button type="button" class="dc-chat-chip" data-question="Where is your store located?">Location</button>
          <button type="button" class="dc-chat-chip" data-question="Do you have stock available?">Stock</button>
        </div>
      </div>

      <form class="dc-chatbot-foot" id="dcChatbotForm">
        <input
          type="text"
          id="dcChatbotInput"
          class="dc-chatbot-input"
          placeholder="Type your question..."
          autocomplete="off">
        <button type="submit" class="dc-chatbot-send" aria-label="Send">
          <i class="bi bi-send-fill"></i>
        </button>
      </form>
    </div>
  </div>

  <div x-data class="hidden" x-text="$store.account.open ? 'open' : 'closed'"></div>

  <?php if(session('openAccountPanel')): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      requestAnimationFrame(() => {
        requestAnimationFrame(() => {
          if (window.Alpine && Alpine.store('account')) {
            Alpine.store('account').openPanel();
          }
        });
      });
    });
  </script>
  <?php endif; ?>
</body>

</html><?php /**PATH C:\DigitronComputers\digitron-store\resources\views/layouts/app.blade.php ENDPATH**/ ?>