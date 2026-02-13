import './bootstrap';
import '../css/app.css';

import $ from 'jquery';
window.$ = window.jQuery = $;

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import Lenis from 'lenis';

const lenis = new Lenis({
  duration: 1.6,
  smoothWheel: true,
  smoothTouch: false
});

function raf(time) {
  lenis.raf(time);
  requestAnimationFrame(raf);
}
requestAnimationFrame(raf);

$(function () {

  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  /* =========================
   * Background parallax
   * ========================= */
  (function initBgParallax() {
    if (!document.body || document.body.dataset.bgParallax !== "1") return;

    let ticking = false;

    function update() {
      const y = window.scrollY || 0;
      document.body.style.setProperty('--bgY', (y * 0.15) + 'px');
      ticking = false;
    }

    $(window).on('scroll', function () {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(update);
    });

    update();
  })();


  /* =========================
   * Mega menu (open/close + expand + tabs)
   * ========================= */
  (function initMegaMenu() {
    const $root = $('#dcMenuRoot');
    const $panel = $('#dcMenuPanel');
    if (!$root.length || !$panel.length) return;

    const OPEN_DELAY = 200;
    const CLOSE_DELAY = 1000;

    let tOpen = null, tClose = null;

    function hardClose() {
      clearTimeout(tOpen);
      clearTimeout(tClose);
      $root.removeClass('is-open');
      $panel.attr('data-state', 'compact');
    }

    $(document).on('click', function (e) {
      if (!$(e.target).closest('#dcMenuRoot').length) hardClose();
    });

    $(window).on('scroll', function () {
      hardClose();
    });

    function setTab(key) {
      $root.find('.dc-tab[data-tab]').removeClass('is-active')
        .filter(`[data-tab="${key}"]`).addClass('is-active');

      $root.find('.dc-tabpanel[data-tabpanel]').removeClass('is-active')
        .filter(`[data-tabpanel="${key}"]`).addClass('is-active');
    }

    function openMenu() {
      clearTimeout(tClose);
      clearTimeout(tOpen);
      tOpen = setTimeout(() => {
        $root.addClass('is-open');
        if (!$panel.attr('data-state')) $panel.attr('data-state', 'compact');
      }, OPEN_DELAY);
    }

    function closeMenu() {
      clearTimeout(tOpen);
      clearTimeout(tClose);
      tClose = setTimeout(() => {
        $root.removeClass('is-open');
        $panel.attr('data-state', 'compact');
      }, CLOSE_DELAY);
    }

    // hover open/close
    $root.on('mouseenter', openMenu);
    $root.on('mouseleave', closeMenu);

    // expand on PC Components hover
    $root.on('mouseenter focus', '.dc-main-item[data-mega="pc"]', function () {
      $panel.attr('data-state', 'expanded');
    });

    // compact on hover other left items
    $root.on('mouseover', '.dc-menu-left', function (e) {
      const overPc = $(e.target).closest('.dc-main-item[data-mega="pc"]').length;
      if (!overPc) $panel.attr('data-state', 'compact');
    });

    // tab switching
    $root.on('mouseenter focus click', '.dc-tab[data-tab]', function (e) {
      e.preventDefault();
      setTab($(this).data('tab'));
    });

    // default
    if (!$panel.attr('data-state')) $panel.attr('data-state', 'compact');
    setTab('new');
  })();


  /* =========================
   * Hero slider (dots + autoplay + drag + keyboard)
   * ========================= */
  (function initHeroSlider() {
    const $hero = $('#vsHero');
    if (!$hero.length) return;

    const $slides = $hero.find('.vs-slide');
    const $dotsWrap = $hero.find('.vs-dots');

    const autoplay = $hero.data('autoplay') == 1;
    const interval = Number($hero.data('interval') || 6500);

    let idx = 0;
    let timer = null;

    function setActive(i) {
      idx = (i + $slides.length) % $slides.length;

      $slides.removeClass('is-active').eq(idx).addClass('is-active');
      $dotsWrap.find('.vs-dot').removeClass('is-active').eq(idx).addClass('is-active');
    }

    function next() { setActive(idx + 1); }
    function prev() { setActive(idx - 1); }

    function start() {
      if (!autoplay) return;
      stop();
      timer = setInterval(next, interval);
    }

    function stop() {
      if (timer) clearInterval(timer);
      timer = null;
    }

    // dots UI
    const dotsHtml = Array.from({ length: $slides.length }).map((_, i) =>
      `<button class="vs-dot ${i === 0 ? 'is-active' : ''}" type="button" aria-label="Go to slide ${i + 1}" data-i="${i}"></button>`
    ).join('');
    $dotsWrap.html(dotsHtml);

    // dot click
    $dotsWrap.on('click', '.vs-dot', function () {
      setActive(Number($(this).data('i')));
      start();
    });

    // hover pause
    $hero.on('mouseenter', stop);
    $hero.on('mouseleave', start);

    // keyboard nav
    $(window).on('keydown', function (e) {
      const t = e.target;
      if (t && (t.tagName === 'INPUT' || t.tagName === 'TEXTAREA' || t.isContentEditable)) return;

      if (e.key === 'ArrowRight') { next(); start(); }
      if (e.key === 'ArrowLeft') { prev(); start(); }
    });

    // drag/swipe
    let dragging = false;
    let startX = 0;
    let lastX = 0;
    const THRESH = 50;

    function getX(ev) {
      if (ev.originalEvent && ev.originalEvent.touches && ev.originalEvent.touches[0]) {
        return ev.originalEvent.touches[0].clientX;
      }
      return ev.clientX;
    }

    $hero.on('mousedown touchstart', function (ev) {
      dragging = true;
      startX = getX(ev);
      lastX = startX;
      stop();
      $hero.addClass('is-dragging');
    });

    $(window).on('mousemove touchmove', function (ev) {
      if (!dragging) return;
      lastX = getX(ev);
    });

    $(window).on('mouseup touchend', function () {
      if (!dragging) return;
      dragging = false;
      $hero.removeClass('is-dragging');

      const dx = lastX - startX;
      if (Math.abs(dx) > THRESH) {
        if (dx < 0) next();
        else prev();
      }
      start();
    });

    // init
    setActive(0);
    start();
  })();

  function buildBg(accent) {
    // video-like background
    return `
      background:
        radial-gradient(900px 420px at 85% 55%, ${accent}26, transparent 60%),
        radial-gradient(700px 360px at 15% 25%, ${accent}1f, transparent 55%),
        linear-gradient(110deg, rgba(2,6,23,0.85), rgba(15,23,42,0.65), rgba(2,6,23,0.85));
    `;
  }

  function shopUrl(base, cat) {
    // if you use query string
    return base + '?category=' + encodeURIComponent(cat);
    // if you use /shop/{category}, change to:
    // return base.replace(/\/$/, '') + '/' + encodeURIComponent(cat);
  }

  function initCategoryHero() {
    const $root = $('#catHero');
    if (!$root.length) return;

    if ($root.data('inited')) return;
    $root.data('inited', true);

    const slides = JSON.parse($root.attr('data-slides') || '[]');
    if (!slides.length) return;

    const base = $root.attr('data-shop-base') || '/shop';
    const autoplay = String($root.data('autoplay')) === '1';
    const interval = Number($root.data('interval')) || 5000;

    const $stage = $root.find('.catHeroStage');
    const $dots = $root.find('.catHeroDots');

    // Render slides
    slides.forEach((s, i) => {
      const $slide = $(`
        <article class="catSlide ${i === 0 ? 'is-active' : ''}" data-i="${i}">
          <div class="catSlideBg"></div>

          <div class="catSlideInner">
            <div>
              <div class="catKicker"></div>
              <div class="catTitle"></div>
              <div class="catName"></div>

              <div class="catBtns">
                <a class="catBuy" href="#">
                  Buy Now
                  <span class="catArrow">→</span>
                </a>
                <a class="catExplore" href="#">Explore →</a>
              </div>

              <p class="catDesc"></p>
            </div>

            <div class="catArt">
              <div class="catGlow"></div>

              <div class="catFloatWrap">
                <img class="catImg" alt="" loading="lazy"/>
              </div>
            </div>
          </div>
        </article>
      `);

      // Fill content
      $slide.find('.catKicker').text(s.kicker || '');
      $slide.find('.catTitle').text(s.title || '');
      $slide.find('.catName').text(s.name || '');
      $slide.find('.catDesc').text(s.desc || '');
      $slide.find('.catArrow').css('background', s.accent || '#38bdf8');
      $slide.find('.catBuy, .catExplore').attr('href', shopUrl(base, s.cat));

      $slide.find('.catImg').attr('src', s.img).attr('alt', s.title || 'Category');
      $slide.find('.catGlow').css('background', s.accent || '#38bdf8');

      // Background style
      $slide.find('.catSlideBg').attr('style', buildBg(s.accent || '#38bdf8'));

      $stage.append($slide);

      // Dot
      const $dot = $(`<button class="catDot ${i === 0 ? 'is-active' : ''}" aria-label="Go to slide"></button>`);
      $dot.on('click', () => go(i));
      $dots.append($dot);
    });

    let active = 0;
    let timer = null;
    let locked = false;

    function setActive(i) {
      if (locked) return;
      locked = true;

      const $slides = $stage.find('.catSlide');
      const $allDots = $dots.find('.catDot');

      $slides.removeClass('is-active');
      $slides.filter(`[data-i="${i}"]`).addClass('is-active');

      $allDots.removeClass('is-active');
      $allDots.eq(i).addClass('is-active');

      active = i;

      // small lock to avoid double-click
      setTimeout(() => (locked = false), 260);
    }

    function next() {
      const i = (active + 1) % slides.length;
      setActive(i);
    }

    function prev() {
      const i = (active - 1 + slides.length) % slides.length;
      setActive(i);
    }

    function go(i) {
      setActive(i);
      restart();
    }

    function pause() {
      if (timer) clearInterval(timer);
      timer = null;
    }

    function resume() {
      if (!autoplay) return;
      if (timer) return;
      timer = setInterval(next, interval);
    }

    function restart() {
      pause();
      resume();
    }

    $root.find('.catNext').on('click', () => { next(); restart(); });
    $root.find('.catPrev').on('click', () => { prev(); restart(); });

    // Pause on hover
    // $stage.on('mouseenter', pause).on('mouseleave', resume);

    // Keyboard
    $(window).on('keydown.catHero', function (e) {
      if (e.key === 'ArrowLeft') { prev(); restart(); }
      if (e.key === 'ArrowRight') { next(); restart(); }
    });

    // Start
    resume();
  }

  initCategoryHero();

  // PC Builder State
  const buildState = {
    cpu: null,
    gpu: null,
    ram: null
  };

  // Component Selection Logic
  window.selectComponent = function (category, element, data) {
    buildState[category] = data;

    // Update UI Selection
    const categoryContainer = element.closest('.component-category');
    const cards = categoryContainer.querySelectorAll('.component-card');

    cards.forEach(card => {
      card.classList.remove('selected');
    });
    element.classList.add('selected');

    updateSummary();
  };

  function updateSummary() {
    const list = document.getElementById('build-list');
    const priceEl = document.getElementById('total-price');
    const wattageEl = document.getElementById('wattage');
    const perfBar = document.getElementById('perf-bar');
    const fpsScore = document.getElementById('fps-score');

    list.innerHTML = '';
    let totalPrice = 0;
    let totalWatts = 100; // Base system
    let totalPerf = 0;

    Object.keys(buildState).forEach(key => {
      const item = buildState[key];
      if (item) {
        const div = document.createElement('div');
        div.className = 'flex justify-between items-center p-3 bg-white/5 rounded border-l-2 border-brand-accent animate-[fadeIn_0.3s_ease-out]';
        div.innerHTML = `
                <div>
                    <div class="text-xs text-gray-400 uppercase">${key}</div>
                    <div class="font-bold text-sm">${item.name}</div>
                </div>
                <div class="font-mono text-brand-accent">AED ${item.price}</div>
            `;
        list.appendChild(div);

        totalPrice += item.price;
        totalWatts += item.watts;
        totalPerf += item.perf;
      }
    });

    if (totalPrice === 0) {
      list.innerHTML = '<div class="text-gray-500 text-sm italic text-center py-10">Select components to start building...</div>';
    }

    animateValue(priceEl, parseInt(priceEl.innerText.replace(/,/g, '')) || 0, totalPrice, 500);
    animateValue(wattageEl, parseInt(wattageEl.innerText) || 0, totalWatts, 500);

    const perfPercent = Math.min((totalPerf / 200) * 100, 100);
    perfBar.style.width = `${perfPercent}%`;

    if (perfPercent < 40) {
      perfBar.className = "h-2 rounded-full transition-all duration-500 bg-yellow-500";
      fpsScore.innerText = "60+ FPS (Medium)";
      fpsScore.className = "text-yellow-500 font-bold";
    } else if (perfPercent < 80) {
      perfBar.className = "h-2 rounded-full transition-all duration-500 bg-purple-500";
      fpsScore.innerText = "144+ FPS (High)";
      fpsScore.className = "text-purple-500 font-bold";
    } else {
      perfBar.className = "h-2 rounded-full transition-all duration-500 bg-cyan-400 shadow-[0_0_10px_#00f0ff]";
      fpsScore.innerText = "240+ FPS (Ultra)";
      fpsScore.className = "text-cyan-400 font-bold";
    }
  }

  function animateValue(obj, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
      if (!startTimestamp) startTimestamp = timestamp;
      const progress = Math.min((timestamp - startTimestamp) / duration, 1);
      obj.innerHTML = Math.floor(progress * (end - start) + start).toLocaleString();
      if (progress < 1) window.requestAnimationFrame(step);
    };
    window.requestAnimationFrame(step);
  }

  // GSAP Animations for Builder Section (safe)
  if (typeof window.gsap !== 'undefined' && typeof window.ScrollTrigger !== 'undefined') {
    window.gsap.registerPlugin(window.ScrollTrigger);

    window.gsap.from(".component-category", {
      scrollTrigger: {
        trigger: "#builder",
        start: "top 70%",
      },
      y: 100,
      opacity: 0,
      duration: 0.8,
      stagger: 0.2,
      ease: "back.out(1.7)"
    });

    // 3D Tilt Effect (kept)
    document.querySelectorAll('.component-card').forEach(card => {
      card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        const rotateX = ((y - centerY) / centerY) * -10;
        const rotateY = ((x - centerX) / centerX) * 10;

        window.gsap.to(card, {
          rotationX: rotateX,
          rotationY: rotateY,
          transformPerspective: 1000,
          ease: "power1.out",
          duration: 0.5
        });
      });

      card.addEventListener('mouseleave', () => {
        window.gsap.to(card, {
          rotationX: 0,
          rotationY: 0,
          duration: 0.5,
          ease: "power1.out"
        });
      });
    });
  }

  /* =========================
 * Shop page scripts (hero counters + product reveal + parallax)
 * Put this inside: $(function(){ ... })
 * ========================= */
  (function initShopPageScripts() {
    const isShop =
      document.body?.dataset?.page === 'shop' ||
      window.location.pathname.includes('/shop');

    if (!isShop) return;

    /* =========================
     * Counter Animation (runs when in view)
     * ========================= */
    const counters = document.querySelectorAll('.counter[data-target]');
    counters.forEach((counter) => {
      const target = parseInt(counter.getAttribute('data-target') || '0', 10);
      if (!Number.isFinite(target) || target <= 0) return;

      let started = false;

      const run = () => {
        if (started) return;
        started = true;

        const duration = 1800;
        const startTime = performance.now();

        const tick = (now) => {
          const p = Math.min((now - startTime) / duration, 1);
          const val = Math.floor(target * p);
          counter.textContent = val.toLocaleString();

          if (p < 1) requestAnimationFrame(tick);
          else counter.textContent = target.toLocaleString() + (target > 100 ? '+' : '');
        };

        requestAnimationFrame(tick);
      };

      if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver((entries) => {
          entries.forEach((e) => {
            if (e.isIntersecting) {
              run();
              obs.disconnect();
            }
          });
        }, { threshold: 0.35 });

        obs.observe(counter);
      } else {
        run(); // fallback
      }
    });

    /* =========================
    * Product Card Reveal (NO GSAP = no stuck hidden cards)
    * ========================= */
    (function revealProductCards() {
      const cards = document.querySelectorAll('.product-card');
      if (!cards.length) return;

      cards.forEach(c => c.classList.add('reveal-init'));

      const io = new IntersectionObserver((entries) => {
        entries.forEach(e => {
          if (e.isIntersecting) {
            e.target.classList.add('reveal-in');
            io.unobserve(e.target);
          }
        });
      }, { threshold: 0.12 });

      cards.forEach(c => io.observe(c));
    })();

    /* =========================
     * Parallax Hero (SAFE + subtle)
     * ========================= */
    const parallax = document.querySelector('.parallax-hero');
    if (parallax) {
      let rafId = null;

      const onScroll = () => {
        if (rafId) return;
        rafId = requestAnimationFrame(() => {
          rafId = null;
          const scrolled = window.pageYOffset || 0;
          const y = Math.min(scrolled * 0.12, 60);
          parallax.style.transform = `translateY(${y}px)`;
        });
      };

      window.addEventListener('scroll', onScroll, { passive: true });
      onScroll(); // initial
    }
  })();

  document.addEventListener('DOMContentLoaded', () => {
    const v = document.querySelector('video');
    if (!v) return;
    v.muted = true;
    v.play().catch(() => { });
  });

  (function initProductPage() {
    const isProduct =
      document.body?.dataset?.page === 'product' ||
      window.location.pathname.startsWith('/product/');

    if (!isProduct) return;

    // Thumbs -> main image
    const mainImage = document.getElementById('main-image');
    if (mainImage) {
      $(document).on('click', '[data-gallery-thumb]', function () {
        const src = this.getAttribute('data-img');
        if (!src) return;

        mainImage.src = src;

        $('.gallery-thumb')
          .removeClass('border-brand-accent')
          .addClass('border-white/10');

        $(this)
          .removeClass('border-white/10')
          .addClass('border-brand-accent');
      });
    }

    // Qty
    const $qty = $('#qty');
    $(document).on('click', '[data-qty-minus]', function () {
      if (!$qty.length) return;
      let v = parseInt($qty.val() || '1', 10);
      v = Math.max(1, v - 1);
      $qty.val(v);
    });
    $(document).on('click', '[data-qty-plus]', function () {
      if (!$qty.length) return;
      let v = parseInt($qty.val() || '1', 10);
      v = Math.min(10, v + 1);
      $qty.val(v);
    });

    // Image hover zoom
    const mainGallery = document.getElementById('main-gallery');
    if (mainGallery && mainImage) {
      mainGallery.addEventListener('mousemove', (e) => {
        const rect = mainGallery.getBoundingClientRect();
        const x = (e.clientX - rect.left) / rect.width;
        const y = (e.clientY - rect.top) / rect.height;

        mainImage.style.transformOrigin = `${x * 100}% ${y * 100}%`;
        mainImage.style.transform = 'scale(1.5)';
      });

      mainGallery.addEventListener('mouseleave', () => {
        mainImage.style.transformOrigin = 'center';
        mainImage.style.transform = 'scale(1)';
      });
    }

    // GSAP fade (safe)
    if (typeof window.gsap !== 'undefined') {
      window.gsap.from('.animate-fade-in', {
        y: 30,
        opacity: 0,
        duration: 0.8,
        ease: 'power3.out'
      });
    }
  })();

});

