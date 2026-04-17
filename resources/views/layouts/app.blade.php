<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', config('app.name'))</title>
  <link rel="icon" href="{{ asset('images/icon.png') }}">
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

  @vite(['resources/css/app.css','resources/js/app.js'])

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

<body class="text-white" data-bg-parallax="1" data-page="@yield('page','')" data-no-lenis="@yield('noLenis','0')" data-auth="{{ auth()->check() ? 1 : 0 }}" data-login-url="{{ route('login') }}">

  @include('partials.loader')

  @include('partials.navbar')

  {{-- Full-width area (for hero slider, banners, etc.) --}}
  @yield('fullwidth')

  {{-- Normal page container --}}
  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    @yield('content')
  </main>

  @yield('cta')

  @php
  $whatsAppNumber = '971501234567'; // replace with real number

  $defaultWaMessage = 'Hello Digitron Computers UAE, I need support.';
  $waChips = [
  ['label' => 'Delivery', 'question' => 'Do you offer delivery in UAE?'],
  ['label' => 'Warranty', 'question' => 'What warranty do you provide?'],
  ['label' => 'Custom PC', 'question' => 'Can you build a custom gaming PC?'],
  ['label' => 'Payment', 'question' => 'What payment methods do you accept?'],
  ['label' => 'Location', 'question' => 'Where is your store located?'],
  ['label' => 'Stock', 'question' => 'Do you have stock available?'],
  ];

  if (View::hasSection('whatsapp_message')) {
  $defaultWaMessage = trim($__env->yieldContent('whatsapp_message'));
  }

  $isProductPage = View::hasSection('page') && trim($__env->yieldContent('page')) === 'product';

  if ($isProductPage) {
  $waChips = [
  ['label' => 'Price', 'question' => 'Please share the final price for this product.'],
  ['label' => 'Stock', 'question' => 'Is this product currently in stock?'],
  ['label' => 'Delivery', 'question' => 'How fast can you deliver this product in UAE?'],
  ['label' => 'Warranty', 'question' => 'What warranty is available for this product?'],
  ['label' => 'Availability', 'question' => 'Please confirm availability for this product.'],
  ['label' => 'WhatsApp', 'question' => '__OPEN_WHATSAPP__'],
  ];
  }

  $productName = $product->name ?? '';
  $productSku = $product->sku ?? '';
  $productPrice = isset($product->price) ? number_format((float) $product->price, 2) : '';
  $productWarranty = $product->warranty ?? '';
  $productDelivery = $product->delivery_text ?? '';
  $productStockText = isset($product->stock_qty)
  ? ($product->stock_qty > 0 ? 'In stock' : 'Out of stock')
  : '';
  @endphp

  @include('partials.footer')

  @include('partials.account-sidebar')
  @include('partials.mobile-account-modal')

  <div
    class="dc-chatbot"
    data-wa-number="{{ $whatsAppNumber }}"
    data-wa-default="{{ $defaultWaMessage }}"
    data-page-type="{{ $isProductPage ? 'product' : 'general' }}"
    data-product-id="{{ $product->id ?? '' }}"
    data-product-name="{{ $productName }}"
    data-product-sku="{{ $productSku }}"
    data-product-price="{{ $productPrice }}"
    data-product-stock="{{ $productStockText }}"
    data-product-warranty="{{ $productWarranty }}"
    data-product-delivery="{{ $productDelivery }}"
    data-lead-url="{{ route('chatbot-leads.store') }}"
    data-csrf="{{ csrf_token() }}">
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

        @if($isProductPage)
        <div class="dc-chat-msg dc-chat-msg-bot">
          <div class="dc-chat-bubble">
            I can help with quick answers for this product like price, stock, delivery, warranty, and availability.
          </div>
        </div>
        @endif

        <div class="dc-chat-quick">
          @foreach($waChips as $chip)
          <button
            type="button"
            class="dc-chat-chip"
            data-question="{{ $chip['question'] }}">
            {{ $chip['label'] }}
          </button>
          @endforeach
        </div>
      </div>

      <form class="dc-chatbot-foot" id="dcChatbotForm">
        <textarea
          id="dcChatbotInput"
          class="dc-chatbot-input"
          placeholder="Type your question..."
          rows="1"></textarea>

        <button type="submit" class="dc-chatbot-send" aria-label="Send">
          <i class="bi bi-send-fill"></i>
        </button>
      </form>
    </div>
  </div>

  <div x-data class="hidden" x-text="$store.account.open ? 'open' : 'closed'"></div>

  @if(session('openAccountPanel'))
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
  @endif
</body>

</html>