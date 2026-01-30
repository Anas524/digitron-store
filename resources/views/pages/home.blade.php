@extends('layouts.app')

@section('fullwidth')
{{-- FULLSCREEN Hero Slider --}}
<section id="vsHero" class="vs-hero" data-autoplay="1" data-interval="6500">
  {{-- Slide 1 --}}
  <article class="vs-slide is-active vs-slide1">
    <div class="vs-overlay"></div>

    <div class="vs-content">
      <h1 class="vs-title">Build Smarter. Shop Faster.</h1>
      <p class="vs-text">
        New, used, and custom PCs — curated parts, trusted picks, and a smooth shopping experience for serious builders.
      </p>

      <a href="{{ route('shop') }}" class="vs-btn">Shop Components</a>
    </div>

    <div class="vs-social">
      <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
      <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
      <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
    </div>
  </article>

  {{-- Slide 2 --}}
  <article class="vs-slide is-active vs-slide2">
    <div class="vs-overlay"></div>

    <div class="vs-content">
      <h1 class="vs-title">Next-Gen CPUs for Every Build</h1>
      <p class="vs-text">
        Shop Intel & AMD processors for gaming, streaming, and productivity — high clocks, more cores, and smooth performance.
      </p>

      <a href="{{ route('shop', ['category' => 'processors']) }}" class="vs-btn">Shop Processors</a>
    </div>

    <div class="vs-social">
      <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
      <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
      <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
    </div>
  </article>

  <!-- {{-- Slide 2 example (video) --}}
  <article class="vs-slide">
    <video class="vs-video" autoplay muted playsinline loop preload="metadata">
      <source src="{{ asset('videos/hero/slide2.mp4') }}" type="video/mp4">
    </video>
    <div class="vs-overlay"></div>

    <div class="vs-content">
      <h2 class="vs-title">Where Gaming Begins</h2>
      <p class="vs-text">Pre-built gaming PCs and RTX-ready parts — performance you can feel.</p>
      <a href="/gaming-pcs" class="vs-btn">Explore</a>
    </div>

    <div class="vs-social">
      <a href="#"><i class="bi bi-facebook"></i></a>
      <a href="#"><i class="bi bi-instagram"></i></a>
      <a href="#"><i class="bi bi-youtube"></i></a>
    </div>
  </article> -->

  {{-- Dots --}}
  <div class="vs-dots" aria-label="Slider dots"></div>
</section>
@endsection

@section('content')
{{-- Category Tiles --}}
<section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-14">
    <div class="flex items-end justify-between gap-4">
        <h2 class="text-lg font-bold">Shop by Category</h2>
        <a href="{{ route('shop') }}" class="text-sm text-slate-300 hover:text-slate-100">View all</a>
    </div>

    <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
        @php
        $cats = ['Processors','Motherboards','RAM','Graphics Cards','SSDs','Power Supply'];
        @endphp
        @foreach($cats as $c)
        <a href="{{ route('shop', ['category' => strtolower(str_replace(' ','-',$c))]) }}"
            class="group rounded-2xl border border-white/10 bg-white/5 p-4 hover:bg-white/10 transition">
            <div class="h-10 w-10 rounded-xl bg-white/10 grid place-items-center text-sm font-black">⚡</div>
            <div class="mt-3 text-sm font-semibold group-hover:text-white">{{ $c }}</div>
            <div class="mt-1 text-[11px] text-slate-400">Explore</div>
        </a>
        @endforeach
    </div>
</section>
@endsection