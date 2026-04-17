@extends('layouts.app')

@section('title', 'Contact Us | Get a Quote | Digitron Computers UAE')
@section('page', 'contact')

@section('fullwidth')
{{-- Hero Section with Video Background --}}
<section class="relative h-[70vh] min-h-[600px] overflow-hidden flex items-center justify-center pt-28 pb-28">
  <!-- Video Background -->
  <div class="absolute inset-0 w-full h-full z-0">
    <video autoplay muted loop playsinline class="object-cover w-full h-full opacity-60 scale-110" id="hero-video">
      <source src="{{ asset('videos/video-ref-2.mp4') }}" type="video/mp4">
    </video>
    <div class="absolute inset-0 bg-gradient-to-b from-[#070A12]/15 via-[#070A12]/35 to-[#070A12]/55"></div>
  </div>

  <!-- Animated Grid -->
  <div class="absolute inset-0 bg-grid-pattern opacity-[0.05] pointer-events-none"></div>

  <!-- Floating Elements -->
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
    @for ($i = 0; $i < 25; $i++)
      <div
      class="absolute w-2 h-2 bg-brand-accent/20 rounded-full floating-particle"
      @style([ 'left'=> rand(0,100).'%',
      'top' => rand(0,100).'%',
      'animation-delay' => ($i*0.2).'s',
      'animation-duration' => (4 + ($i % 4)).'s',
      ])
      ></div>
  @endfor
  </div>

  <!-- Glowing Orbs -->
  <div class="absolute top-1/3 left-1/4 w-96 h-96 bg-brand-accent/10 rounded-full blur-[150px] animate-pulse"></div>
  <div class="absolute bottom-1/3 right-1/4 w-80 h-80 bg-brand-secondary/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 1.5s;"></div>

  <!-- Content -->
  <div class="relative z-10 text-center px-4 max-w-5xl mx-auto parallax-hero">
    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-brand-accent/30 bg-brand-accent/10 mb-6 animate-fade-in">
      <span class="w-2 h-2 bg-brand-accent rounded-full animate-pulse"></span>
      <span class="text-brand-accent text-sm font-bold uppercase tracking-[0.2em]">24/7 Support Available</span>
    </div>

    <h1 class="text-5xl md:text-7xl font-display font-black mb-6 tracking-tight leading-none">
      LET'S <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent via-brand-secondary to-brand-accent animate-gradient">CONNECT</span>
    </h1>

    <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto font-light leading-relaxed mb-8">
      Whether you need a custom quote, technical support, or just want to talk shop —
      we're here for the UAE's PC enthusiasts.
    </p>

    <!-- Quick Contact Options -->
    <div class="flex flex-wrap items-center justify-center gap-4 mb-12">
      <a href="https://wa.me/971501240180" target="_blank" class="group px-6 py-3 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 font-medium hover:bg-emerald-500 hover:text-white transition-all flex items-center gap-2">
        <i class="bi bi-whatsapp text-xl group-hover:scale-110 transition-transform"></i>
        WhatsApp Now
      </a>
      <a href="tel:+971501240180" class="group px-6 py-3 rounded-full bg-white/5 border border-white/20 text-white font-medium hover:bg-brand-accent hover:text-black hover:border-brand-accent transition-all flex items-center gap-2">
        <i class="bi bi-telephone-fill group-hover:rotate-12 transition-transform"></i>
        Call Us
      </a>
      <button onclick="scrollToQuote()" class="group px-6 py-3 rounded-full bg-brand-accent/20 border border-brand-accent/30 text-brand-accent font-medium hover:bg-brand-accent hover:text-black transition-all flex items-center gap-2">
        <i class="bi bi-chat-square-text-fill group-hover:scale-110 transition-transform"></i>
        Get Quote
      </button>
    </div>
  </div>

  <!-- Scroll Indicator -->
  <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-gray-400 hover:text-brand-accent transition-colors cursor-pointer" onclick="scrollToContact()">
    <span class="text-xs uppercase tracking-widest">Explore Options</span>
    <div class="w-6 h-10 rounded-full border-2 border-current flex items-start justify-center p-2">
      <div class="w-1 h-2 bg-current rounded-full animate-bounce"></div>
    </div>
  </div>

  <!-- Corner Accents -->
  <div class="absolute top-5 left-5 w-32 h-32 border-l-2 border-t-2 border-brand-accent/20"></div>
  <div class="absolute bottom-5 right-5 w-32 h-32 border-r-2 border-b-2 border-brand-accent/20"></div>
</section>
@endsection

@section('content')
{{-- Contact Methods Grid --}}
<section id="contact-methods" class="relative z-20 -mt-10 md:-mt-14 pt-12 pb-20 scroll-mt-28">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      @php
      $contactMethods = [
      [
      'icon' => 'whatsapp',
      'title' => 'WhatsApp',
      'value' => '+971 50 124 0180',
      'desc' => 'Fastest response time',
      'action' => 'Chat Now',
      'link' => 'https://wa.me/971501240180',

      // static Tailwind classes
      'hoverBorder' => 'hover:border-emerald-400/50',
      'shadow' => 'hover:shadow-emerald-500/20',
      'glowBg' => 'from-emerald-500/10',
      'iconBg' => 'bg-emerald-500/10',
      'iconText' => 'text-emerald-400',
      'valueText' => 'text-emerald-400',
      'hoverText' => 'group-hover:text-emerald-400',
      ],
      [
      'icon' => 'telephone-fill',
      'title' => 'Phone',
      'value' => '+971 50 124 0180',
      'desc' => 'Talk to our experts',
      'action' => 'Call Us',
      'link' => 'tel:+971501240180',

      'hoverBorder' => 'hover:border-brand-accent/50',
      'shadow' => 'hover:shadow-brand-accent/20',
      'glowBg' => 'from-brand-accent/10',
      'iconBg' => 'bg-brand-accent/10',
      'iconText' => 'text-brand-accent',
      'valueText' => 'text-brand-accent',
      'hoverText' => 'group-hover:text-brand-accent',
      ],
      [
      'icon' => 'envelope-fill',
      'title' => 'Email',
      'value' => 'shuhabrahim@hotmail.com',
      'desc' => 'Detailed inquiries',
      'action' => 'Send Email',
      'link' => 'mailto:shuhabrahim@hotmail.com',

      'hoverBorder' => 'hover:border-brand-secondary/50',
      'shadow' => 'hover:shadow-brand-secondary/20',
      'glowBg' => 'from-brand-secondary/10',
      'iconBg' => 'bg-brand-secondary/10',
      'iconText' => 'text-brand-secondary',
      'valueText' => 'text-brand-secondary',
      'hoverText' => 'group-hover:text-brand-secondary',
      ],
      [
      'icon' => 'geo-alt-fill',
      'title' => 'Visit Us',
      'value' => 'Dubai',
      'desc' => 'See builds in person',
      'action' => 'Get Directions',
      'link' => '#showroom',

      'hoverBorder' => 'hover:border-orange-400/50',
      'shadow' => 'hover:shadow-orange-500/20',
      'glowBg' => 'from-orange-500/10',
      'iconBg' => 'bg-orange-500/10',
      'iconText' => 'text-orange-400',
      'valueText' => 'text-orange-400',
      'hoverText' => 'group-hover:text-orange-400',
      ],
      ];
      @endphp

      @foreach($contactMethods as $method)
      <a href="{{ $method['link'] }}"
        class="group glass-panel rounded-2xl p-6 border border-white/10 transition-all duration-500 hover:-translate-y-3 hover:shadow-xl {{ $method['hoverBorder'] }} {{ $method['shadow'] }} relative overflow-hidden">
        <!-- Hover Glow -->
        <div class="absolute inset-0 bg-gradient-to-br {{ $method['glowBg'] }} to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

        <div class="relative">
          <div class="w-14 h-14 rounded-2xl {{ $method['iconBg'] }} flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
            <i class="bi bi-{{ $method['icon'] }} text-2xl {{ $method['iconText'] }}"></i>
          </div>

          <h3 class="text-lg font-bold text-white mb-1">{{ $method['title'] }}</h3>
          <div class="{{ $method['valueText'] }} font-semibold mb-2">{{ $method['value'] }}</div>
          <p class="text-sm text-gray-400 mb-4">{{ $method['desc'] }}</p>

          <div class="flex items-center gap-2 text-sm font-medium text-white {{ $method['hoverText'] }} transition-colors">
            {{ $method['action'] }}
            <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</section>

{{-- Quote Request Section --}}
<section id="quote-section" class="py-24 relative overflow-hidden scroll-mt-28">
  <!-- Background Elements -->
  <div class="absolute inset-0 z-0">
    <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-brand-accent/5 to-transparent pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-1/3 h-1/2 bg-gradient-to-tr from-brand-secondary/5 to-transparent pointer-events-none"></div>
  </div>

  <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-2 gap-12 items-start">
      {{-- Left: Info & FAQ --}}
      <div class="lg:sticky lg:top-28 space-y-8">
        <div>
          <span class="text-brand-accent text-sm font-bold uppercase tracking-[0.3em] mb-4 block">Custom Builds</span>
          <h2 class="text-4xl md:text-5xl font-display font-bold mb-4">GET YOUR <span class="text-brand-secondary">QUOTE</span></h2>
          <p class="text-gray-400 text-lg leading-relaxed">
            Tell us your dream PC specs, budget, and timeline. Our build experts will craft
            the perfect configuration and send you a detailed quote within 2 hours.
          </p>
        </div>

        {{-- Why Quote With Us --}}
        <div class="glass-panel rounded-2xl p-6 border border-white/10">
          <h3 class="font-bold text-white mb-4 flex items-center gap-2">
            <i class="bi bi-check-circle-fill text-brand-accent"></i>
            Why Request a Quote?
          </h3>
          <ul class="space-y-3">
            @foreach([
            'Personalized build recommendations',
            'Best price guarantee on components',
            'Compatibility checking by experts',
            'Flexible payment plans (0% installments)',
            'Priority support and faster delivery'
            ] as $benefit)
            <li class="flex items-start gap-3 text-sm text-gray-400">
              <i class="bi bi-check-lg text-brand-accent mt-0.5"></i>
              <span>{{ $benefit }}</span>
            </li>
            @endforeach
          </ul>
        </div>

        {{-- Quick FAQ --}}
        <div class="space-y-3" x-data="{ open: null }">
          <h3 class="font-bold text-white mb-4">Common Questions</h3>

          @php
          $faqs = [
          ['q' => 'How fast will I get my quote?', 'a' => 'Most quotes are delivered within 2 hours during business hours. Complex custom water-cooling builds may take up to 24 hours.'],
          ['q' => 'Is there any obligation?', 'a' => 'Absolutely not. Our quotes are completely free with no pressure to buy. We\'re here to help you plan your perfect build.'],
          ['q' => 'Can I modify the quote later?', 'a' => 'Yes! Quotes are flexible. Want to swap that RTX 4070 for a 4080? Just reply to your quote email or WhatsApp us.'],
          ['q' => 'Do you offer bulk discounts?', 'a' => 'Definitely. For orders over AED 10,000 or 5+ units, we provide custom business pricing. Select "Bulk / Business" in the form.']
          ];
          @endphp

          @foreach($faqs as $index => $faq)
          <div class="glass-panel rounded-xl border border-white/10 overflow-hidden">
            <button @click="open === {{ $index }} ? open = null : open = {{ $index }}"
              class="w-full px-5 py-4 flex items-center justify-between text-left hover:bg-white/5 transition-colors">
              <span class="font-medium text-white text-sm">{{ $faq['q'] }}</span>
              <i class="bi bi-chevron-down transition-transform" :class="open === {{ $index }} ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open === {{ $index }}"
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0 -translate-y-2"
              x-transition:enter-end="opacity-100 translate-y-0"
              x-transition:leave="transition ease-in duration-150"
              x-transition:leave-start="opacity-100 translate-y-0"
              x-transition:leave-end="opacity-0 -translate-y-2"
              class="px-5 pb-4">
              <p class="text-sm text-gray-400 leading-relaxed">{{ $faq['a'] }}</p>
            </div>
          </div>
          @endforeach
        </div>

        {{-- Trust Badges --}}
        <div class="flex flex-wrap items-center gap-6 pt-4">
          <div class="flex items-center gap-2 text-sm text-gray-400">
            <i class="bi bi-shield-check text-brand-accent text-lg"></i>
            <span>Secure Form</span>
          </div>
          <div class="flex items-center gap-2 text-sm text-gray-400">
            <i class="bi bi-clock-history text-brand-accent text-lg"></i>
            <span>2hr Response</span>
          </div>
          <div class="flex items-center gap-2 text-sm text-gray-400">
            <i class="bi bi-lock-fill text-brand-accent text-lg"></i>
            <span>Data Protected</span>
          </div>
        </div>
      </div>

      {{-- Right: Quote Form --}}
      <div class="glass-panel rounded-3xl border border-white/10 p-6 md:p-10 relative overflow-hidden">
        <!-- Form Header -->
        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-white/10">
          <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-accent to-brand-secondary flex items-center justify-center text-black text-2xl shadow-lg shadow-brand-accent/30">
            <i class="bi bi-calculator-fill"></i>
          </div>
          <div>
            <h3 class="text-xl font-bold text-white">Build Your Quote</h3>
            <p class="text-sm text-gray-400">Step 1 of 1 — Tell us what you need</p>
          </div>
        </div>

        <form id="quoteForm" action="{{ route('quote.store') }}" method="POST" class="space-y-6">
          @csrf

          {{-- Personal Info --}}
          <div class="space-y-4">
            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-brand-accent/20 text-brand-accent text-xs flex items-center justify-center">1</span>
              Your Details
            </h4>

            <div class="grid md:grid-cols-2 gap-4">
              <div class="relative group">
                <input type="text" name="full_name" required
                  class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3.5 text-white outline-none focus:border-brand-accent transition-all peer placeholder-transparent"
                  placeholder="Full Name" id="full_name">
                <label for="full_name" class="absolute left-4 top-3.5 text-gray-500 text-sm transition-all peer-focus:-top-2.5 peer-focus:left-3 peer-focus:text-xs peer-focus:text-brand-accent peer-focus:bg-[#0a0a0f] peer-focus:px-2 peer-[:not(:placeholder-shown)]:-top-2.5 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:bg-[#0a0a0f] peer-[:not(:placeholder-shown)]:px-2 pointer-events-none">
                  Full Name *
                </label>
                <i class="bi bi-person absolute right-4 top-3.5 text-gray-500 peer-focus:text-brand-accent transition-colors"></i>
              </div>

              <div class="relative group">
                <input type="email" name="email" required
                  class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3.5 text-white outline-none focus:border-brand-accent transition-all peer placeholder-transparent"
                  placeholder="Email" id="email">
                <label for="email" class="absolute left-4 top-3.5 text-gray-500 text-sm transition-all peer-focus:-top-2.5 peer-focus:left-3 peer-focus:text-xs peer-focus:text-brand-accent peer-focus:bg-[#0a0a0f] peer-focus:px-2 peer-[:not(:placeholder-shown)]:-top-2.5 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:bg-[#0a0a0f] peer-[:not(:placeholder-shown)]:px-2 pointer-events-none">
                  Email Address *
                </label>
                <i class="bi bi-envelope absolute right-4 top-3.5 text-gray-500 peer-focus:text-brand-accent transition-colors"></i>
              </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
              <div class="relative group">
                <input type="tel" name="phone"
                  class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3.5 text-white outline-none focus:border-brand-accent transition-all peer placeholder-transparent"
                  placeholder="Phone" id="phone">
                <label for="phone" class="absolute left-4 top-3.5 text-gray-500 text-sm transition-all peer-focus:-top-2.5 peer-focus:left-3 peer-focus:text-xs peer-focus:text-brand-accent peer-focus:bg-[#0a0a0f] peer-focus:px-2 peer-[:not(:placeholder-shown)]:-top-2.5 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:bg-[#0a0a0f] peer-[:not(:placeholder-shown)]:px-2 pointer-events-none">
                  Phone Number
                </label>
                <i class="bi bi-telephone absolute right-4 top-3.5 text-gray-500 peer-focus:text-brand-accent transition-colors"></i>
              </div>

              <div class="relative">
                <select name="area"
                  class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3.5 text-white outline-none focus:border-brand-accent transition-all appearance-none cursor-pointer">
                  <option value="" class="bg-[#0a0a0f]">Select Your Area</option>
                  <option value="dubai" class="bg-[#0a0a0f]">Dubai</option>
                  <option value="abu-dhabi" class="bg-[#0a0a0f]">Abu Dhabi</option>
                  <option value="sharjah" class="bg-[#0a0a0f]">Sharjah</option>
                  <option value="ajman" class="bg-[#0a0a0f]">Ajman</option>
                  <option value="rak" class="bg-[#0a0a0f]">Ras Al Khaimah</option>
                  <option value="fujairah" class="bg-[#0a0a0f]">Fujairah</option>
                  <option value="uaq" class="bg-[#0a0a0f]">Umm Al Quwain</option>
                  <option value="other" class="bg-[#0a0a0f]">Other UAE</option>
                </select>
                <i class="bi bi-geo-alt absolute right-4 top-3.5 text-gray-500 pointer-events-none"></i>
              </div>
            </div>
          </div>

          {{-- Build Details --}}
          <div class="space-y-4">
            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-brand-secondary/20 text-brand-secondary text-xs flex items-center justify-center">2</span>
              Build Requirements
            </h4>

            <div class="grid md:grid-cols-2 gap-4">
              {{-- Quote Type --}}
              <div class="relative">
                {{-- Left icon --}}
                <i id="quoteTypeIcon"
                  class="bi bi-controller absolute left-4 top-3.5 text-gray-500 pointer-events-none"></i>

                <select id="quote_type" name="quote_type" required
                  class="pl-12 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3.5 text-white outline-none focus:border-brand-accent transition-all appearance-none cursor-pointer">
                  <option value="" class="bg-[#0a0a0f]">Quote Type *</option>
                  <option value="gaming-pc" class="bg-[#0a0a0f]">Gaming PC Build</option>
                  <option value="workstation" class="bg-[#0a0a0f]">Workstation / Creative</option>
                  <option value="streaming" class="bg-[#0a0a0f]">Streaming Setup</option>
                  <option value="part-request" class="bg-[#0a0a0f]">Specific Part Request</option>
                  <option value="pre-built" class="bg-[#0a0a0f]">Pre-Built PC</option>
                  <option value="upgrade" class="bg-[#0a0a0f]">PC Upgrade Service</option>
                  <option value="repair" class="bg-[#0a0a0f]">Repair / Troubleshooting</option>
                  <option value="bulk" class="bg-[#0a0a0f]">Bulk / Business Order</option>
                </select>

                {{-- Right chevron --}}
                <i class="bi bi-chevron-down absolute right-4 top-3.5 text-gray-500 pointer-events-none"></i>
              </div>

              {{-- Budget --}}
              <div class="relative group">
                <input type="number" name="budget" min="1000" step="500"
                  class="no-spin w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3.5 pr-14 text-white outline-none focus:border-brand-accent transition-all peer placeholder-transparent"
                  placeholder="Budget" id="budget">
                <label for="budget"
                  class="absolute left-4 top-3.5 text-gray-500 text-sm transition-all
                          peer-focus:-top-2.5 peer-focus:left-3 peer-focus:text-xs peer-focus:text-brand-accent
                          peer-focus:bg-[#0a0a0f] peer-focus:px-2
                          peer-[:not(:placeholder-shown)]:-top-2.5 peer-[:not(:placeholder-shown)]:left-3
                          peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:bg-[#0a0a0f]
                          peer-[:not(:placeholder-shown)]:px-2 pointer-events-none">
                  Budget (AED)
                </label>
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm pointer-events-none">AED</span>
              </div>
            </div>

            {{-- Use Case Tags --}}
            <div class="space-y-2">
              <label class="text-sm text-gray-400">Primary Use Case (select all that apply)</label>
              <div class="flex flex-wrap gap-3">
                @foreach(['4K Gaming', 'Esports', 'Video Editing', '3D Rendering', 'Streaming', 'Programming', 'Office Work', 'VR Ready'] as $tag)
                <label class="cursor-pointer inline-flex">
                  <input type="checkbox" name="use_case[]" value="{{ $tag }}" class="sr-only peer">
                  <span class="px-4 py-2 rounded-full border border-white/10 bg-white/5 text-sm text-gray-400 whitespace-nowrap
                                peer-checked:bg-brand-accent/20 peer-checked:text-brand-accent peer-checked:border-brand-accent/50
                                transition-all hover:bg-white/10">
                    {{ $tag }}
                  </span>
                </label>
                @endforeach
              </div>
            </div>
          </div>

          {{-- Detailed Message --}}
          <div class="space-y-4">
            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 text-xs flex items-center justify-center">3</span>
              Tell Us More
            </h4>

            <div class="relative">
              <textarea name="message" rows="5"
                class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3.5 text-white outline-none focus:border-brand-accent transition-all resize-none"
                placeholder="Describe your dream PC, specific components you want, or any questions..."></textarea>
              <div class="absolute bottom-3 right-3 text-xs text-gray-500">
                <span id="char-count">0</span>/500
              </div>
            </div>

            {{-- File Upload --}}
            <div class="relative">
              <input type="file" name="attachments[]" multiple id="file-upload" class="sr-only" accept=".jpg,.jpeg,.png,.pdf,.txt">
              <label for="file-upload" class="flex items-center gap-3 p-4 rounded-xl border border-dashed border-white/20 bg-white/5 cursor-pointer hover:border-brand-accent/50 hover:bg-white/10 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-brand-accent/20 transition-colors">
                  <i class="bi bi-cloud-arrow-up text-xl text-gray-400 group-hover:text-brand-accent"></i>
                </div>
                <div class="flex-1">
                  <div class="text-sm font-medium text-white">Attach files (optional)</div>
                  <div class="text-xs text-gray-500">Reference images, part lists, or inspiration — max 10MB</div>
                </div>
                <i class="bi bi-plus-lg text-gray-400"></i>
              </label>
              <div id="file-list" class="mt-2 space-y-1 hidden"></div>
            </div>
          </div>

          {{-- Submit --}}
          <div class="pt-4">
            <button type="submit" id="submit-btn"
              class="w-full group relative rounded-xl bg-gradient-to-r from-brand-accent to-brand-secondary text-black font-bold py-4 text-lg overflow-hidden shadow-lg shadow-brand-accent/30 hover:shadow-xl hover:shadow-brand-accent/40 transition-all">
              <span class="relative z-10 flex items-center justify-center gap-2">
                <span id="btn-text">Get My Quote</span>
                <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform" id="btn-icon"></i>
              </span>
              <div class="absolute inset-0 bg-white translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
            </button>

            <p id="quoteMsg" class="mt-4 text-sm text-center hidden"></p>
          </div>

          {{-- Privacy Note --}}
          <p class="text-xs text-gray-500 text-center">
            By submitting, you agree to our <a href="#" class="text-brand-accent hover:underline">Privacy Policy</a>.
            We'll only use your info to respond to your quote request.
          </p>

          <input type="hidden" name="details[primary_use_case]" id="primary_use_case_hidden">
        </form>
      </div>
    </div>
  </div>
</section>

{{-- Showroom Section --}}
<section id="showroom" class="py-24 relative overflow-hidden scroll-mt-28">
  <!-- Parallax Background -->
  <div class="absolute inset-0 z-0">
    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1920"
      alt="Showroom"
      class="w-full h-full object-cover opacity-20 parallax-bg-slow">
    <div class="absolute inset-0 bg-gradient-to-r from-[#070A12] via-[#070A12]/95 to-[#070A12]/80"></div>
  </div>

  <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-2 gap-12 items-center">
      <div>
        <span class="text-brand-secondary text-sm font-bold uppercase tracking-[0.3em] mb-4 block">Visit Us</span>
        <h2 class="text-4xl md:text-5xl font-display font-bold mb-6">EXPERIENCE THE <span class="text-brand-accent">POWER</span></h2>
        <p class="text-gray-400 text-lg mb-8 leading-relaxed">
          Step into our flagship showroom at Dubai. Test drive the latest GPUs,
          feel the mechanical keyboards, and consult with our build experts in person.
        </p>

        <div class="space-y-6 mb-8">
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-brand-accent/10 flex items-center justify-center text-brand-accent shrink-0">
              <i class="bi bi-geo-alt-fill text-xl"></i>
            </div>
            <div>
              <h4 class="font-bold text-white mb-1">Flagship Showroom</h4>
              <p class="text-gray-400">Victoria building, international city 2<br>Dubai, UAE</p>
            </div>
          </div>

          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-brand-secondary/10 flex items-center justify-center text-brand-secondary shrink-0">
              <i class="bi bi-clock-fill text-xl"></i>
            </div>
            <div>
              <h4 class="font-bold text-white mb-1">Opening Hours</h4>
              <p class="text-gray-400">Sat-Thu: 10AM - 10PM | Fri: 2PM - 10PM</p>
            </div>
          </div>
        </div>

        <div class="flex flex-wrap gap-4">
          <a href="https://goo.gl/maps/xyz" target="_blank" class="px-6 py-3 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition-colors flex items-center gap-2">
            <i class="bi bi-map-fill"></i> Get Directions
          </a>
          <!-- <button onclick="bookVisit()" class="px-6 py-3 rounded-xl border border-white/20 text-white font-bold hover:border-brand-accent hover:text-brand-accent transition-colors flex items-center gap-2">
            <i class="bi bi-calendar-event"></i> Book Visit
          </button> -->
        </div>
      </div>

      <div class="relative">
        <div class="aspect-[4/3] rounded-2xl overflow-hidden border border-white/10 glass-panel relative group">
          <img src="https://images.unsplash.com/photo-1591488320449-011701bb6704?auto=format&fit=crop&q=80&w=800"
            alt="Digitron Showroom"
            class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-500 group-hover:scale-105">

          <!-- Map Pin -->
          <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            <div class="relative">
              <div class="w-6 h-6 bg-brand-accent rounded-full animate-ping absolute"></div>
              <div class="w-6 h-6 bg-brand-accent rounded-full relative shadow-lg shadow-brand-accent/50 flex items-center justify-center">
                <i class="bi bi-geo-alt-fill text-black text-sm"></i>
              </div>
            </div>
          </div>

          <!-- Live Badge -->
          <div class="absolute top-4 left-4 px-3 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-sm font-bold flex items-center gap-2">
            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
            Open Now
          </div>
        </div>

        <!-- Floating Stats -->
        <div class="absolute -bottom-6 -left-6 glass-panel rounded-xl p-4 border border-brand-accent/30 shadow-xl">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-brand-accent/20 flex items-center justify-center">
              <i class="bi bi-people-fill text-brand-accent text-xl"></i>
            </div>
            <div>
              <div class="text-lg font-bold text-white">50+</div>
              <div class="text-xs text-gray-400">Builds on Display</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection