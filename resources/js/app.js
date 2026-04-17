import './bootstrap';
import '../css/app.css';

import $ from 'jquery';
window.$ = window.jQuery = $;

import Alpine from 'alpinejs';
window.Alpine = Alpine;

import './admin';
import './admin-products';

document.addEventListener('alpine:init', () => {
  Alpine.store('account', {
    open: false,

    openPanel() {
      this.open = true;

      const panelExists = document.querySelector('.dc-account-panel');
      if (panelExists) {
        document.documentElement.classList.add('overflow-hidden');
        document.body.classList.add('overflow-hidden');
      }
    },

    closePanel() {
      console.log('account closePanel called');
      this.open = false;
      document.documentElement.classList.remove('overflow-hidden');
      document.body.classList.remove('overflow-hidden');
    },

    togglePanel() {
      this.open ? this.closePanel() : this.openPanel();
    }
  });

  Alpine.store('mobileAccount', {
    open: false,
    tab: 'login',

    openLogin() {
      this.tab = 'login';
      this.open = true;
      document.documentElement.classList.add('overflow-hidden');
      document.body.classList.add('overflow-hidden');
    },

    openRegister() {
      this.tab = 'register';
      this.open = true;
      document.documentElement.classList.add('overflow-hidden');
      document.body.classList.add('overflow-hidden');
    },

    close() {
      this.open = false;
      document.documentElement.classList.remove('overflow-hidden');
      document.body.classList.remove('overflow-hidden');
    }
  });
});

Alpine.start();

import Lenis from 'lenis';

console.log('noLenis:', document.body?.dataset?.noLenis);

// Disable Lenis on admin pages (custom scroll containers)
if (String(document.body?.dataset?.noLenis || '0') !== '1') {
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
}

$(function () {

  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  window.APP_AUTH = {
    loggedIn: String(document.body?.dataset?.auth || '0') === '1',
    loginUrl: document.body?.dataset?.loginUrl || '/login',
  };

  function toast(msg) {
    let el = document.getElementById('dcToast');

    if (!el) {
      el = document.createElement('div');
      el.id = 'dcToast';
      el.className = [
        'fixed',
        'top-24',
        'left-1/2',
        '-translate-x-1/2',
        'z-[99999]',
        'max-w-[92vw]',
        'w-max',
        'px-4',
        'py-3',
        'rounded-xl',
        'bg-[#0b1220]/95',
        'text-white',
        'border',
        'border-white/10',
        'backdrop-blur-xl',
        'shadow-2xl',
        'text-sm',
        'font-medium',
        'opacity-0',
        'pointer-events-none',
        'transition-all',
        'duration-200'
      ].join(' ');

      document.body.appendChild(el);
    }

    el.textContent = msg;
    el.style.opacity = '1';
    el.style.transform = 'translateX(-50%) translateY(0)';

    clearTimeout(el._t);
    el._t = setTimeout(() => {
      el.style.opacity = '0';
      el.style.transform = 'translateX(-50%) translateY(-8px)';
    }, 2200);
  }

  function promptLogin(message = 'Please login to continue') {
    toast(message);

    setTimeout(() => {
      // Mobile guest/login modal
      if (window.innerWidth < 768) {
        if (window.Alpine && Alpine.store('mobileAccount')) {
          Alpine.store('mobileAccount').openLogin();
        }
        return;
      }

      // Desktop guest dropdown trigger in navbar
      const guestAccountBtn = document.querySelector(
        '.top-actions .relative[x-data] > button.icon-btn[aria-label="Account"]'
      );

      if (!window.APP_AUTH.loggedIn && guestAccountBtn) {
        guestAccountBtn.click();
        return;
      }

      // Desktop logged-in account sidebar
      if (window.APP_AUTH.loggedIn && window.Alpine && Alpine.store('account')) {
        Alpine.store('account').openPanel();
      }
    }, 250);
  }

  /* =========================
     * Initial Loader
     * ========================= */
  (function initLoader() {
    const loader = document.getElementById('site-loader');
    if (!loader) {
      document.body.classList.add('loaded');
      return;
    }

    const isReload = document.documentElement.classList.contains('is-reload');

    if (!isReload) {
      loader.style.display = 'none';
      document.body.classList.remove('loader-active');
      document.body.classList.add('loaded');
      return;
    }

    document.body.classList.remove('loaded');
    document.body.classList.add('loader-active');
    document.body.style.overflow = 'hidden';

    const loadingTexts = [
      'Initializing systems...',
      'Loading modules...',
      'Preparing interface...',
      'Optimizing performance...',
      'Welcome to Digitron...'
    ];

    const loaderText = document.getElementById('loader-text');
    const loaderPercentage = document.getElementById('loader-percentage');
    const loaderProgress = document.querySelector('.loader-progress');

    if (!loaderText || !loaderPercentage || !loaderProgress) {
      loader.style.display = 'none';
      document.body.style.overflow = '';
      document.body.classList.remove('loader-active');
      document.body.classList.add('loaded');
      return;
    }

    let progress = 0;
    let textIndex = 0;
    let finished = false;
    const startTime = Date.now();
    const minLoadTime = 1800;

    const progressInterval = setInterval(() => {
      if (finished) return;

      progress += Math.random() * 12 + 6;
      if (progress > 100) progress = 100;

      loaderProgress.style.width = progress + '%';
      loaderPercentage.textContent = Math.floor(progress) + '%';

      const nextMilestone = (textIndex + 1) * 20;
      if (progress >= nextMilestone && textIndex < loadingTexts.length - 1) {
        textIndex++;
        loaderText.textContent = loadingTexts[textIndex];
        loaderText.classList.add('glitch-complete');
        setTimeout(() => loaderText.classList.remove('glitch-complete'), 250);
      }

      if (progress >= 100) {
        clearInterval(progressInterval);
        completeLoader();
      }
    }, 180);

    function completeLoader() {
      if (finished) return;
      finished = true;

      const elapsed = Date.now() - startTime;
      const remaining = Math.max(0, minLoadTime - elapsed);

      setTimeout(() => {
        loaderText.textContent = 'Systems ready.';
        loaderPercentage.textContent = '100%';
        loaderProgress.style.width = '100%';
        loaderText.classList.add('text-emerald-400');

        setTimeout(() => {
          loader.classList.add('loader-exit');

          setTimeout(() => {
            loader.style.display = 'none';
            document.body.style.overflow = '';
            document.body.classList.remove('loader-active');
            document.body.classList.add('loaded');
            document.documentElement.classList.remove('is-reload');
          }, 800);
        }, 350);
      }, remaining);
    }

    window.skipLoader = function () {
      clearInterval(progressInterval);
      progress = 100;
      completeLoader();
    };

    document.addEventListener('keydown', function skipOnKey() {
      if (loader.style.display !== 'none') {
        window.skipLoader();
      }
    }, { once: true });
  })();

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
    const $btn = $root.find('.dc-menu-btn');
    const $mainExpandable = $root.find('.dc-main-item[data-mega]');
    const $mainLinks = $root.find('.dc-main-link');

    if (!$root.length || !$panel.length) return;

    const OPEN_DELAY = 50;
    const CLOSE_DELAY = 900;

    let openTimer = null;
    let closeTimer = null;
    let activePaneKey = null;

    function clearTimers() {
      clearTimeout(openTimer);
      clearTimeout(closeTimer);
    }

    function setExpanded(expanded) {
      $panel.attr('data-state', expanded ? 'expanded' : 'compact');
    }

    function setActivePane(key) {
      activePaneKey = key || null;

      $mainExpandable.removeClass('is-active');
      $root.find('.dc-mega-pane').removeClass('is-active');

      if (!key) {
        setExpanded(false);
        return;
      }

      $mainExpandable.filter(`[data-mega="${key}"]`).addClass('is-active');
      $root.find(`.dc-mega-pane[data-pane="${key}"]`).addClass('is-active');
      setExpanded(true);
    }

    function setDefaultState() {
      const $defaultItem = $mainExpandable.first();
      const defaultKey = $defaultItem.data('mega') || null;

      if (defaultKey) {
        setActivePane(defaultKey);
      } else {
        setExpanded(false);
      }

      const $firstTab = $root.find('.dc-tab[data-tab]').first();
      if ($firstTab.length) {
        const tabKey = $firstTab.data('tab');
        $root.find('.dc-tab[data-tab]').removeClass('is-active');
        $root.find('.dc-tabpanel[data-tabpanel]').removeClass('is-active');
        $firstTab.addClass('is-active');
        $root.find(`.dc-tabpanel[data-tabpanel="${tabKey}"]`).addClass('is-active');
      }
    }

    function openMenu() {
      clearTimeout(closeTimer);

      if ($root.hasClass('is-open')) return;

      openTimer = setTimeout(() => {
        $root.addClass('is-open');
        $btn.attr('aria-expanded', 'true');
        if (!activePaneKey) setDefaultState();
      }, OPEN_DELAY);
    }

    function closeMenuNow() {
      clearTimers();
      $root.removeClass('is-open');
      $btn.attr('aria-expanded', 'false');
      activePaneKey = null;
      setDefaultState();
    }

    function isPointerInsideMenu() {
      const rootEl = $root[0];
      const panelEl = $panel[0];
      if (!rootEl || !panelEl) return false;

      return rootEl.matches(':hover') || panelEl.matches(':hover');
    }

    function scheduleClose() {
      clearTimeout(closeTimer);

      closeTimer = setTimeout(() => {
        if (isPointerInsideMenu()) return;
        closeMenuNow();
      }, CLOSE_DELAY);
    }

    function cancelClose() {
      clearTimeout(closeTimer);
    }

    $root.on('mouseenter', function () {
      cancelClose();
      openMenu();
    });

    $root.on('mouseleave', function () {
      scheduleClose();
    });

    $panel.on('mouseenter', function () {
      cancelClose();
    });

    $panel.on('mouseleave', function () {
      scheduleClose();
    });

    $mainExpandable.on('mouseenter focus', function () {
      cancelClose();
      openMenu();
      setActivePane($(this).data('mega'));
    });

    $mainLinks.on('mouseenter focus', function () {
      cancelClose();
      openMenu();

      if (!activePaneKey) {
        const $defaultItem = $mainExpandable.first();
        const defaultKey = $defaultItem.data('mega') || null;

        if (defaultKey) {
          setActivePane(defaultKey);
        }
        return;
      }

      setExpanded(true);
    });

    $root.on('mouseenter', '.dc-tab[data-tab]', function () {
      const key = $(this).data('tab');
      const $pane = $(this).closest('.dc-mega-pane');

      $pane.find('.dc-tab[data-tab]').removeClass('is-active');
      $pane.find('.dc-tabpanel[data-tabpanel]').removeClass('is-active');

      $(this).addClass('is-active');
      $pane.find(`.dc-tabpanel[data-tabpanel="${key}"]`).addClass('is-active');
    });

    $btn.on('click', function (e) {
      e.preventDefault();
      clearTimers();

      if ($root.hasClass('is-open')) {
        closeMenuNow();
      } else {
        openMenu();
      }
    });

    $(document).on('click', function (e) {
      if (!$(e.target).closest('#dcMenuRoot').length) {
        closeMenuNow();
      }
    });

    $(window).on('scroll', function () {
      closeMenuNow();
    });

    $(document).on('keydown', function (e) {
      if (e.key === 'Escape') {
        closeMenuNow();
      }
    });

    setDefaultState();
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

  // PC Builder State
  const buildState = {
    cpu: null,
    gpu: null,
    ram: null
  };

  // Component Selection Logic
  window.selectComponent = function (category, element, data) {

    // Active highlight (only for that category)
    $(`[data-category="${category}"] .component-card`).removeClass('is-active');
    $(element).addClass('is-active');

    // save data
    buildState[category] = data;

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

    const addBtn = document.getElementById('builderAddToCartBtn');
    const addBtnText = document.getElementById('builderAddToCartText');

    const selectedItems = Object.values(buildState).filter(Boolean);

    if (addBtn) {
      addBtn.disabled = selectedItems.length === 0;
    }

    if (addBtnText) {
      addBtnText.textContent = selectedItems.length ? `Add ${selectedItems.length} Item${selectedItems.length > 1 ? 's' : ''} to Cart` : 'Add to Cart';
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

    // Category pills horizontal arrows
    (function initCategoryPillsScroll() {
      const scroller = document.getElementById('categoryPillsScroller');
      const prevBtn = document.getElementById('catPillsPrev');
      const nextBtn = document.getElementById('catPillsNext');

      if (!scroller || !prevBtn || !nextBtn) return;

      const scrollAmount = 240;

      function updateButtons() {
        const maxScrollLeft = scroller.scrollWidth - scroller.clientWidth;

        prevBtn.style.display = scroller.scrollLeft > 5 ? 'flex' : 'none';
        nextBtn.style.display = scroller.scrollLeft < maxScrollLeft - 5 ? 'flex' : 'none';
      }

      prevBtn.addEventListener('click', () => {
        scroller.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
      });

      nextBtn.addEventListener('click', () => {
        scroller.scrollBy({ left: scrollAmount, behavior: 'smooth' });
      });

      scroller.addEventListener('scroll', updateButtons);
      window.addEventListener('resize', updateButtons);

      updateButtons();
    })();
  })();

  const v = document.querySelector('video');
  if (v) {
    v.muted = true;
    v.play().catch(() => { });
  }

  // Product page scripts
  (function initProductPage() {
    if (document.body?.dataset?.page !== 'product') return;

    const mainImage = document.getElementById('main-image');
    const mainGallery = document.getElementById('main-gallery');
    if (!mainImage || !mainGallery) return;

    // Thumb click -> change image
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-gallery-thumb]');
      if (!btn) return;

      const src = btn.getAttribute('data-img');
      if (!src) return;

      mainImage.src = src;

      document.querySelectorAll('.gallery-thumb').forEach(t => {
        t.classList.remove('border-brand-accent');
        t.classList.add('border-white/10');
      });

      btn.classList.remove('border-white/10');
      btn.classList.add('border-brand-accent');
    });

    // Qty buttons
    const qtyInput = document.getElementById('qty');
    const clampQty = () => {
      let val = parseInt(qtyInput.value || '1', 10);
      if (!Number.isFinite(val)) val = 1;
      val = Math.max(1, Math.min(10, val));
      qtyInput.value = val;
    };

    document.addEventListener('click', (e) => {
      if (!qtyInput) return;

      if (e.target.closest('[data-qty-minus]')) {
        qtyInput.value = (parseInt(qtyInput.value || '1', 10) || 1) - 1;
        clampQty();
      }

      if (e.target.closest('[data-qty-plus]')) {
        qtyInput.value = (parseInt(qtyInput.value || '1', 10) || 1) + 1;
        clampQty();
      }
    });

    qtyInput?.addEventListener('change', clampQty);

    // Zoom effect
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
  })();

  // Product page gallery + video (jQuery)
  (function initProductGalleryAndVideo() {
    const $main = $('#main-image');
    if (!$main.length) return; // not on product page

    const $thumbs = $('[data-gallery-thumb]');
    let index = 0;

    function setActive(i) {
      const $t = $thumbs.eq(i);
      if (!$t.length) return;

      index = i;

      // swap image
      const img = $t.data('img');
      if (img) $main.attr('src', img);

      // active border styles
      $thumbs.removeClass('border-brand-accent').addClass('border-white/10');
      $t.removeClass('border-white/10').addClass('border-brand-accent');
    }

    // init active thumb
    if ($thumbs.length) setActive(0);

    // click thumb
    $(document).on('click', '[data-gallery-thumb]', function () {
      const i = $thumbs.index(this);
      setActive(i);
    });

    // arrows (add ids to your buttons for easy targeting)
    // left button: id="galleryPrev"
    // right button: id="galleryNext"
    $(document).on('click', '#galleryPrev', function () {
      if (!$thumbs.length) return;
      const next = (index - 1 + $thumbs.length) % $thumbs.length;
      setActive(next);
    });

    $(document).on('click', '#galleryNext', function () {
      if (!$thumbs.length) return;
      const next = (index + 1) % $thumbs.length;
      setActive(next);
    });

    // Video modal open/close (only if exists)
    $(document).on('click', '#openVideoModal', function () {
      const $m = $('#videoModal');
      if (!$m.length) return;
      $m.removeClass('hidden').addClass('flex');

      const v = document.getElementById('productVideoPlayer');
      if (v) v.play().catch(() => { });
    });

    $(document).on('click', '#closeVideoModal', function () {
      const $m = $('#videoModal');
      if (!$m.length) return;

      const v = document.getElementById('productVideoPlayer');
      if (v) {
        v.pause();
        v.currentTime = 0;
      }

      $m.addClass('hidden').removeClass('flex');
    });

    // click outside modal to close (optional)
    $(document).on('click', '#videoModal', function (e) {
      if (e.target !== this) return;
      $('#closeVideoModal').trigger('click');
    });
  })();

  // Product video modal (frontend)
  (function initProductVideoModal() {
    const modal = document.getElementById('videoModal');
    const player = document.getElementById('productVideoPlayer');

    if (!modal || !player) return;

    function openVideo(url) {
      if (!url) return;

      player.innerHTML = `<source src="${url}">`;
      player.load();

      modal.classList.remove('hidden');
      modal.classList.add('flex');

      document.documentElement.classList.add('overflow-hidden');
      document.body.classList.add('overflow-hidden');

      player.play().catch(() => { });
    }

    function closeVideo() {
      modal.classList.add('hidden');
      modal.classList.remove('flex');

      player.pause();
      player.currentTime = 0;
      player.innerHTML = '';

      document.documentElement.classList.remove('overflow-hidden');
      document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('click', function (e) {
      const openTrigger = e.target.closest('[data-open-video]');
      if (openTrigger) {
        e.preventDefault();
        openVideo(openTrigger.getAttribute('data-video-url'));
        return;
      }

      if (e.target.closest('#closeVideoModal')) {
        closeVideo();
        return;
      }

      // close when clicking outside modal content
      if (!modal.classList.contains('hidden')) {
        const modalBox = e.target.closest('#videoModal > div > div');
        const clickedInsideModal = !!modalBox;
        const clickedInsideOverlay = !!e.target.closest('#videoModal');

        if (clickedInsideOverlay && !clickedInsideModal) {
          closeVideo();
        }
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
        closeVideo();
      }
    });
  })();

  (function initShopAjaxFilters() {
    const $form = $('#shopFiltersForm');
    if (!$form.length) return;

    let xhr = null;
    let t = null;

    function setLoading(on) {
      $('#shopResultsWrap').toggleClass('opacity-50 pointer-events-none', !!on);
    }

    function fetchResults(pushUrl = true) {
      // cancel previous request
      if (xhr) xhr.abort();

      const url = $form.attr('action') + '?' + $form.serialize();

      setLoading(true);

      xhr = $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
        .done(function (res) {
          if (res.filters) $('#shopFiltersWrap').html(res.filters);
          if (res.products) $('#shopResultsWrap').html(res.products);

          // update browser URL (no reload)
          if (pushUrl) history.pushState({ shop: true }, '', url);

          // re-init handlers that depend on DOM (if any)
          // (your product reveal uses IntersectionObserver, it will still work)
        })
        .always(function () {
          setLoading(false);
          xhr = null;
        });
    }

    // 1) Change on inputs (checkbox/radio/number/range/select)
    $(document).on('change', '#shopFiltersForm input, #shopFiltersForm select', function () {
      clearTimeout(t);
      t = setTimeout(() => fetchResults(true), 120); // small debounce
    });

    // 2) Prevent normal submit (Enter key etc.)
    $(document).on('submit', '#shopFiltersForm', function (e) {
      e.preventDefault();
      fetchResults(true);
    });

    // 3) Pagination & Load More -> AJAX
    $(document).on('click', '#shopResultsWrap a[href]', function (e) {
      const href = $(this).attr('href');
      if (!href) return;

      // only intercept pagination links (same page)
      if (href.includes('/shop')) {
        e.preventDefault();

        // load page URL via AJAX but keep current filters
        // easiest: just request that href and replace blocks
        if (xhr) xhr.abort();
        setLoading(true);

        xhr = $.ajax({
          url: href,
          method: 'GET',
          dataType: 'json',
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
          .done(function (res) {
            if (res.filters) $('#shopFiltersWrap').html(res.filters);
            if (res.products) $('#shopResultsWrap').html(res.products);
            history.pushState({ shop: true }, '', href);
          })
          .always(function () {
            setLoading(false);
            xhr = null;
          });
      }
    });

    // 4) Back/forward button support
    window.addEventListener('popstate', function () {
      // when user clicks back, load current URL state
      // rebuild form from URL? simplest: just request location.href and replace blocks
      if (xhr) xhr.abort();
      setLoading(true);

      xhr = $.ajax({
        url: window.location.href,
        method: 'GET',
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
        .done(function (res) {
          if (res.filters) $('#shopFiltersWrap').html(res.filters);
          if (res.products) $('#shopResultsWrap').html(res.products);
        })
        .always(function () {
          setLoading(false);
          xhr = null;
        });
    });

    // Category pills (and any filter links) -> AJAX
    $(document).on('click', '[data-shop-link]', function (e) {
      e.preventDefault();
      const href = $(this).attr('href');
      if (!href) return;

      // load this URL via AJAX and update blocks
      if (xhr) xhr.abort();
      setLoading(true);

      xhr = $.ajax({
        url: href,
        method: 'GET',
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
        .done(function (res) {
          if (res.filters) $('#shopFiltersWrap').html(res.filters);
          if (res.products) $('#shopResultsWrap').html(res.products);
          history.pushState({ shop: true }, '', href);
        })
        .always(function () {
          setLoading(false);
          xhr = null;
        });
    });

  })();

  // Newsletter AJAX
  $(document).on('submit', '#newsletterForm', function (e) {
    e.preventDefault();

    const $form = $(this);
    const $msg = $('#newsletterMsg');
    const $btn = $form.find('button[type="submit"]');

    if (!$form.length || !$btn.length) return;

    const originalContent = $btn.html();

    $msg.addClass('hidden').removeClass('newsletter-success newsletter-error').text('');
    $btn.prop('disabled', true).html('<i class="bi bi-arrow-repeat animate-spin"></i>');

    $.ajax({
      url: $form.attr('action'),
      method: 'POST',
      data: $form.serialize(),
      dataType: 'json',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .done(function (res) {
        $msg
          .removeClass('hidden newsletter-error')
          .addClass('newsletter-success')
          .text(res.message || '✓ Thanks for subscribing! Check your inbox.');

        $form[0].reset();
      })
      .fail(function (xhr) {
        $msg
          .removeClass('hidden newsletter-success')
          .addClass('newsletter-error')
          .text(xhr.responseJSON?.message || '✗ Subscription failed. Please try again.');
      })
      .always(function () {
        $btn.prop('disabled', false).html(originalContent);

        setTimeout(() => {
          $msg.addClass('hidden');
        }, 5000);
      });
  });

  // ===============================
  // QUOTE PAGE JS (move to app.js)
  // ===============================
  (function () {
    // run only if quote form exists on this page
    const hasQuoteForm = $('#quoteForm').length > 0;
    const isAdminQuotes = $('#page-admin-quotes').length > 0; // we will add this id

    // -----------------------------
    // Parallax Effects
    // -----------------------------
    window.addEventListener('scroll', () => {
      const scrolled = window.pageYOffset;

      const hero = document.querySelector('.parallax-hero');
      if (hero) hero.style.transform = `translateY(${scrolled * 0.3}px)`;

      const slowBg = document.querySelector('.parallax-bg-slow');
      if (slowBg) slowBg.style.transform = `translateY(${scrolled * 0.1}px) scale(1.1)`;
    });

    // -----------------------------
    // Scroll helpers (needed for onclick in blade)
    // -----------------------------
    window.scrollToQuote = function () {
      const el = document.getElementById('quote-section');
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    window.scrollToContact = function () {
      const el = document.getElementById('contact-methods');
      if (el) el.scrollIntoView({ behavior: 'smooth' });
    };

    // -----------------------------
    // Character Counter
    // -----------------------------
    (function () {
      const messageField = document.querySelector('textarea[name="message"]');
      const charCount = document.getElementById('char-count');
      if (!messageField || !charCount) return;

      messageField.addEventListener('input', () => {
        let count = messageField.value.length;

        if (count > 500) {
          messageField.value = messageField.value.substring(0, 500);
          count = 500;
          charCount.classList.add('text-red-400');
        } else {
          charCount.classList.remove('text-red-400');
        }

        charCount.textContent = count;
      });
    })();

    // -----------------------------
    // Use Case -> hidden input sync
    // -----------------------------
    function syncUseCaseHidden() {
      const selected = $('input[name="use_case[]"]:checked')
        .map(function () { return String($(this).val() || '').trim(); })
        .get()
        .filter(Boolean);

      $('#primary_use_case_hidden').val(selected.join(', '));
    }

    // update on change
    $(document).on('change', 'input[name="use_case[]"]', syncUseCaseHidden);

    // ensure it's set right before submit (important)
    $(document).on('submit', '#quoteForm', function () {
      syncUseCaseHidden();
    });

    document.querySelectorAll('footer .current-year').forEach(el => {
      el.textContent = new Date().getFullYear();
    });

    // call on load + when user comes back via browser back (bfcache)
    $(function () {
      syncUseCaseHidden();
    });
    window.addEventListener('pageshow', syncUseCaseHidden);

    // -----------------------------
    // File Upload list UI (simple)
    // -----------------------------
    $(document).on('change', '#file-upload', function () {
      const files = this.files || [];
      const $list = $('#file-list').empty();

      if (!files.length) {
        $list.addClass('hidden');
        return;
      }

      for (const f of files) {
        $list.append(
          `<div class="text-xs text-gray-400 flex items-center gap-2">
          <i class="bi bi-paperclip"></i>
          <span class="truncate">${f.name}</span>
          <span class="text-gray-600">(${Math.ceil(f.size / 1024)} KB)</span>
        </div>`
        );
      }

      $list.removeClass('hidden');
    });

    // -----------------------------
    // Quote AJAX submit (REAL)
    // -----------------------------
    $(document).on('submit', '#quoteForm', function (e) {
      e.preventDefault();

      // VERY IMPORTANT: make sure hidden is updated before FormData
      syncUseCaseHidden();

      const $form = $(this);
      const $msg = $('#quoteMsg').addClass('hidden').text('');

      const $btn = $('#submit-btn');
      if ($btn.prop('disabled')) return;

      const $btnText = $('#btn-text');
      const $btnIcon = $('#btn-icon');

      $btn.prop('disabled', true).addClass('opacity-70 cursor-not-allowed');
      $btnText.text('Submitting...');
      $btnIcon.removeClass('bi-arrow-right').addClass('bi-arrow-repeat animate-spin');

      const fd = new FormData($form[0]);

      $.ajax({
        url: $form.attr('action'),
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        dataType: 'json',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
      })
        .done(function (res) {
          $msg.removeClass('hidden').text(res.message || 'Submitted');

          $form[0].reset();
          $('#file-list').addClass('hidden').empty();
          $('#primary_use_case_hidden').val('');
        })
        .fail(function (xhr) {
          $msg.removeClass('hidden').text(xhr.responseJSON?.message || 'Please check your inputs.');
        })
        .always(function () {
          $btn.prop('disabled', false).removeClass('opacity-70 cursor-not-allowed');
          $btnText.text('Get My Quote');
          $btnIcon.removeClass('bi-arrow-repeat animate-spin').addClass('bi-arrow-right');
        });
    });

    // -----------------------------
    // Video Speed
    // -----------------------------
    (function () {
      const heroVideo = document.getElementById('hero-video');
      if (heroVideo) heroVideo.playbackRate = 0.6;
    })();

    // -----------------------------
    // GSAP (optional)
    // -----------------------------
    // if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    //   gsap.registerPlugin(ScrollTrigger);

    //   gsap.from('#contact-methods .reveal-text', {
    //     scrollTrigger: {
    //       trigger: '#contact-methods',
    //       start: 'top 80%',
    //     },
    //     y: 60,
    //     opacity: 0,
    //     duration: 0.8,
    //     stagger: 0.15,
    //     ease: 'power3.out'
    //   });
    // }
  })();

  // ===============================
  // ADMIN QUOTES (View/Delete modals)
  // ===============================
  (function () {
    // run only on admin quotes page
    if (!document.getElementById('page-admin-quotes')) return;

    function escapeText(v) {
      if (v === null || v === undefined || v === '') return '—';
      return String(v);
    }

    function openQuoteModal(q) {
      $('#qmSub').text(escapeText(q.date));
      $('#qmName').text(escapeText(q.name));
      $('#qmEmail').text(escapeText(q.email));
      $('#qmPhone').text(escapeText(q.phone));
      $('#qmArea').text(escapeText(q.area));
      $('#qmType').text(escapeText(q.type));
      $('#qmBudget').text(q.budget !== null && q.budget !== undefined && q.budget !== '' ? q.budget : '—');
      $('#qmMessage').text(q.message ? String(q.message) : '—');

      // details pretty JSON
      if (q.details && typeof q.details === 'object') {
        try { $('#qmDetails').text(JSON.stringify(q.details, null, 2)); }
        catch { $('#qmDetails').text('—'); }
      } else {
        $('#qmDetails').text('—');
      }

      $('#quoteModal').removeClass('hidden');
    }

    function closeQuoteModal() {
      $('#quoteModal').addClass('hidden');
    }

    function renderUseCaseTags(details) {
      const wrap = document.getElementById('qmUseCaseWrap');
      if (!wrap) return;

      // reset
      wrap.innerHTML = '';

      const raw = details?.primary_use_case ?? details?.use_case ?? '';

      let list = [];
      if (Array.isArray(raw)) {
        list = raw.map(x => String(x).trim()).filter(Boolean);
      } else {
        list = String(raw)
          .split(',')
          .map(x => x.trim())
          .filter(Boolean);
      }

      if (!list.length) {
        wrap.innerHTML = `<span class="text-gray-500 text-sm">—</span>`;
        return;
      }

      list.forEach(tag => {
        const span = document.createElement('span');
        span.className = 'px-2 py-1 rounded-full text-xs font-semibold border border-brand-accent/30 bg-brand-accent/10 text-brand-accent';
        span.textContent = tag;
        wrap.appendChild(span);
      });
    }

    // OPEN: View
    $(document).off('click.quoteView').on('click.quoteView', '.quote-view-btn', function () {
      const raw = $(this).attr('data-quote');
      if (!raw) return;

      let q;
      try { q = JSON.parse(raw); }
      catch (e) { console.error('Invalid quote JSON payload', e); return; }

      openQuoteModal(q);
      renderUseCaseTags(q.details);

      // attachments
      $('#qmFiles').html('<div class="text-gray-500 text-sm">Loading...</div>');

      $.get('/admin/quotes/' + q.id + '/attachments')
        .done(function (res) {
          if (!res || !res.ok) {
            $('#qmFiles').html('<div class="text-gray-500 text-sm">—</div>');
            return;
          }

          const items = res.items || [];
          if (!items.length) {
            $('#qmFiles').html('<div class="text-gray-500 text-sm">—</div>');
            return;
          }

          const html = items.map(function (f) {
            const sizeKB = f.size ? Math.ceil(f.size / 1024) + ' KB' : '';
            return `
            <a href="${f.url}" target="_blank"
              class="flex items-center justify-between gap-3 rounded-lg border border-white/10 bg-white/5 px-3 py-2 hover:bg-white/10">
              <span class="truncate text-cyan-200 underline">${f.name}</span>
              <span class="text-xs text-gray-500">${sizeKB}</span>
            </a>`;
          }).join('');

          $('#qmFiles').html(html);
        })
        .fail(function () {
          $('#qmFiles').html('<div class="text-gray-500 text-sm">—</div>');
        });
    });

    // CLOSE quote modal
    $(document).off('click.quoteClose').on('click.quoteClose', '.quote-modal-close', closeQuoteModal);
    $(document).off('click.quoteBackdrop').on('click.quoteBackdrop', '#quoteModalBackdrop', closeQuoteModal);
    $(document).off('keydown.quoteEsc').on('keydown.quoteEsc', function (e) {
      if (e.key === 'Escape') closeQuoteModal();
    });

    // Delete modal
    let quoteDelId = null;

    function openDelModal(id) {
      quoteDelId = id;
      $('#quoteDelModal').removeClass('hidden');
    }
    function closeDelModal() {
      quoteDelId = null;
      $('#quoteDelModal').addClass('hidden');
    }

    // OPEN delete
    $(document).off('click.quoteDel').on('click.quoteDel', '.quote-del-btn', function () {
      const id = $(this).data('id');
      if (!id) return;
      openDelModal(id);
    });

    // CLOSE delete modal
    $(document).off('click.quoteDelClose').on('click.quoteDelClose', '.quote-del-close, #quoteDelBackdrop', closeDelModal);

    // CONFIRM delete
    $(document).off('click.quoteDelConfirm').on('click.quoteDelConfirm', '#quoteDelConfirm', function () {
      if (!quoteDelId) return;

      $.ajax({
        url: '/admin/quotes/' + quoteDelId,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
      })
        .done(function (res) {
          if (res && res.ok) {
            $('.quote-del-btn[data-id="' + quoteDelId + '"]').closest('tr').remove();
            closeDelModal();
          } else {
            alert(res.message || 'Delete failed');
          }
        })
        .fail(function (xhr) {
          alert(xhr.responseJSON?.message || 'Delete failed');
        });
    });
  })();

  $(document).off('click.builderAddCart').on('click.builderAddCart', '#builderAddToCartBtn', async function (e) {
    e.preventDefault();

    const selectedItems = Object.values(buildState).filter(Boolean);

    if (!selectedItems.length) {
      toast('Please select at least one component');
      return;
    }

    if (!window.APP_AUTH.loggedIn) {
      promptLogin('Please login to add your build to cart');
      return;
    }

    const $btn = $(this);
    const $text = $('#builderAddToCartText');

    if ($btn.data('busy')) return;
    $btn.data('busy', true).prop('disabled', true);

    const oldText = $text.text();
    $text.text('Adding...');

    let lastCount = parseInt($('#headerCartCount').text() || '0', 10) || 0;

    try {
      for (const item of selectedItems) {
        if (!item.cartUrl) continue;

        const res = await $.ajax({
          url: item.cartUrl,
          type: 'POST',
          data: { qty: 1 },
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        if (res && typeof res.count !== 'undefined') {
          lastCount = res.count;
        }
      }

      setHeaderCartCount(lastCount);
      $text.text('Added to Cart');

      setTimeout(() => {
        $text.text(oldText);
        $btn.data('busy', false).prop('disabled', false);
      }, 1200);

    } catch (err) {
      $text.text(oldText);
      $btn.data('busy', false).prop('disabled', false);
      toast('Failed to add selected components');
    }
  });

  // Buy Now on product page
  $(document).on('click', '.js-buy-now', function (e) {
    e.preventDefault();

    const $btn = $(this);
    const url = $btn.data('url');
    const qtySelector = $btn.data('qty');
    const redirectUrl = $btn.data('redirect') || '/cart';

    let qty = 1;
    if (qtySelector) {
      const val = $(qtySelector).val();
      qty = parseInt(val, 10);
      if (isNaN(qty) || qty < 1) qty = 1;
    }

    const originalHtml = $btn.html();
    $btn.prop('disabled', true).html('<i class="bi bi-arrow-repeat animate-spin"></i> Processing...');

    $.ajax({
      url,
      method: 'POST',
      data: {
        _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
        quantity: qty
      },
      dataType: 'json',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .done(function (res) {
        window.location.href = redirectUrl;
      })
      .fail(function (xhr) {
        alert(xhr.responseJSON?.message || 'Unable to process Buy Now.');
      })
      .always(function () {
        $btn.prop('disabled', false).html(originalHtml);
      });
  });

  function initShopPriceRange() {
    const $form = $('#shopFiltersForm');
    if (!$form.length) return;

    const $minR = $('#priceMinRange');
    const $maxR = $('#priceMaxRange');
    const $minI = $('#minPriceInput');
    const $maxI = $('#maxPriceInput');

    const $fill = $('#priceFill');
    const $dotMin = $('#priceDotMin');
    const $dotMax = $('#priceDotMax');

    if (!$minR.length || !$maxR.length || !$minI.length || !$maxI.length || !$fill.length || !$dotMin.length || !$dotMax.length) return;

    $minR.on('mousedown touchstart', function () {
      $minR.css('z-index', 60);
      $maxR.css('z-index', 59);
    });

    $maxR.on('mousedown touchstart', function () {
      $maxR.css('z-index', 60);
      $minR.css('z-index', 59);
    });

    const minBound = parseInt($minR.attr('min') || '0', 10);
    const maxBound = parseInt($minR.attr('max') || '0', 10);

    const clamp = (n, a, b) => Math.max(a, Math.min(parseInt(n || 0, 10), b));
    const pct = (val) => (maxBound === minBound ? 0 : ((val - minBound) / (maxBound - minBound)) * 100);

    function render() {
      let minV = clamp($minR.val(), minBound, maxBound);
      let maxV = clamp($maxR.val(), minBound, maxBound);

      if (minV > maxV) minV = maxV;

      $minR.val(minV);
      $maxR.val(maxV);
      $minI.val(minV);
      $maxI.val(maxV);

      const left = pct(minV);
      const right = pct(maxV);
      const width = Math.max(0, right - left);

      $fill.css({ left: left + '%', width: width + '%' });
      $dotMin.css({ left: `calc(${left}% - 8px)`, top: '-4px' });
      $dotMax.css({ left: `calc(${right}% - 8px)`, top: '-4px' });
    }

    let t = null;
    function submitSoon() {
      clearTimeout(t);
      t = setTimeout(() => $form.trigger('submit'), 250);
    }

    $minR.on('input', function () {
      if (parseInt($minR.val(), 10) > parseInt($maxR.val(), 10)) $maxR.val($minR.val());
      render();
    });

    $maxR.on('input', function () {
      if (parseInt($maxR.val(), 10) < parseInt($minR.val(), 10)) $minR.val($maxR.val());
      render();
    });

    $minR.on('change mouseup touchend', submitSoon);
    $maxR.on('change mouseup touchend', submitSoon);

    $minI.on('change', function () {
      let v = clamp($minI.val(), minBound, maxBound);
      if (v > parseInt($maxI.val(), 10)) v = parseInt($maxI.val(), 10);
      $minR.val(v);
      render();
      submitSoon();
    });

    $maxI.on('change', function () {
      let v = clamp($maxI.val(), minBound, maxBound);
      if (v < parseInt($minI.val(), 10)) v = parseInt($minI.val(), 10);
      $maxR.val(v);
      render();
      submitSoon();
    });

    render();
  }


  // Wishlist page init (jQuery)
  function initWishlistPage() {
    const $bg = $('.parallax-bg');
    const $grid = $('#wishlist-grid');
    const $empty = $('#empty-wishlist');
    const removeUrl = (id) => `/wishlist/${id}`;
    const clearUrl = () => `/wishlist`;

    // 1) Parallax hero bg
    $(window).off('scroll.wishPar').on('scroll.wishPar', function () {
      const scrolled = window.pageYOffset || 0;
      if ($bg.length) $bg.css('transform', `translateY(${scrolled * 0.5}px) scale(1.1)`);
    }).trigger('scroll.wishPar');

    // 2) Particles generate (no Blade rand needed)
    const $p = $('#wishlistParticles');
    if ($p.length && !$p.data('inited')) {
      $p.data('inited', true);
      for (let i = 0; i < 20; i++) {
        const left = Math.random() * 100;
        const top = Math.random() * 100;
        const delay = i * 0.15;
        const duration = 4 + (i % 4);
        $('<div class="floating-element bg-brand-secondary/30"></div>')
          .css({
            left: left + '%',
            top: top + '%',
            animationDelay: delay + 's',
            animationDuration: duration + 's'
          })
          .appendTo($p);
      }
    }

    // helpers
    function animateValue($el, start, end, duration, prefix = 'AED ') {
      const startTime = performance.now();
      function tick(now) {
        const p = Math.min((now - startTime) / duration, 1);
        const eased = 1 - Math.pow(1 - p, 3);
        const val = Math.round(start + (end - start) * eased);
        $el.text(prefix ? (prefix + val.toLocaleString()) : val.toLocaleString());
        if (p < 1) requestAnimationFrame(tick);
      }
      requestAnimationFrame(tick);
    }

    function updateWishlistStats() {
      const $items = $('.wishlist-item');
      let count = $items.length;
      let total = 0;

      $items.each(function () {
        total += parseInt($(this).data('price') || 0, 10);
      });

      const $count = $('#wishlist-count');
      const $value = $('#wishlist-value');

      const curCount = parseInt(($count.text() || '0').replace(/[^0-9]/g, ''), 10) || 0;
      const curVal = parseInt(($value.text() || '0').replace(/[^0-9]/g, ''), 10) || 0;

      animateValue($count, curCount, count, 500, '');
      animateValue($value, curVal, total, 500, 'AED ');
    }

    function checkEmpty() {
      const remaining = $('.wishlist-item').length;
      if (remaining === 0) {
        $grid.addClass('hidden');
        $empty.removeClass('hidden');
      }
    }

    // 3) Remove item
    $(document).off('click.wishRemove').on('click.wishRemove', '.js-remove-wish', function () {
      const id = $(this).data('id');
      const $card = $(`.wishlist-item[data-id="${id}"]`);
      if (!$card.length) return;

      $.ajax({
        url: removeUrl(id),
        type: 'DELETE',
      }).done(function (res) {
        if (!res || !res.ok) return;

        $card.css({ transform: 'scale(0.92)', opacity: '0' });
        setTimeout(() => {
          $card.remove();
          updateWishlistStats();
          checkEmpty();
        }, 300);
      });
    });

    // 4) Clear all
    $(document).off('click.wishClear').on('click.wishClear', '#btnClearWishlist', function () {
      if (!confirm('Are you sure you want to clear your entire wishlist?')) return;

      $.ajax({
        url: clearUrl(),
        type: 'DELETE',
      }).done(function (res) {
        if (!res || !res.ok) return;

        $('.wishlist-item').remove();
        $grid.addClass('hidden');
        $empty.removeClass('hidden');
        updateWishlistStats();
      });
    });

    // 6) Add all to cart
    $(document).off('click.wishAddAll').on('click.wishAddAll', '#btnAddAllToCart', function () {
      const $inStock = $('.wishlist-item').filter(function () {
        const s = String($(this).data('stock') || '');
        return s === 'In Stock' || s === 'Low Stock';
      });

      $inStock.each(function (index) {
        const $btn = $(this).find('.js-add-to-cart').first();
        setTimeout(() => $btn.trigger('click'), index * 250);
      });
    });

    // store original order for "recent"
    $grid.find('.wishlist-item').each(function (i) {
      $(this).attr('data-added-index', i);
    });

    function applyFilter(key) {
      const $items = $grid.find('.wishlist-item');

      $items.each(function () {
        const $it = $(this);
        const stock = String($it.data('stock') || '');
        const sale = String($it.data('sale')) === '1';

        let show = true;
        if (key === 'stock') show = stock !== 'Out of Stock';
        if (key === 'sale') show = sale;

        $it.toggleClass('hidden', !show);
      });

      updateWishlistStats(); // update based on visible
    }

    // update stats based on visible items
    function updateWishlistStats() {
      const $items = $grid.find('.wishlist-item').not('.hidden');
      let count = $items.length;
      let total = 0;

      $items.each(function () {
        total += parseInt($(this).data('price') || 0, 10);
      });

      const $count = $('#wishlist-count');
      const $value = $('#wishlist-value');

      const curCount = parseInt(($count.text() || '0').replace(/[^0-9]/g, ''), 10) || 0;
      const curVal = parseInt(($value.text() || '0').replace(/[^0-9]/g, ''), 10) || 0;

      animateValue($count, curCount, count, 500, '');
      animateValue($value, curVal, total, 500, 'AED ');
    }

    function applySort(key) {
      const nodes = $grid.children('.wishlist-item').get();

      nodes.sort((a, b) => {
        const $a = $(a), $b = $(b);

        if (key === 'plh') return Number($a.data('price')) - Number($b.data('price'));
        if (key === 'phl') return Number($b.data('price')) - Number($a.data('price'));
        if (key === 'az') return String($a.data('name')).localeCompare(String($b.data('name')));

        // recent
        return Number($a.attr('data-added-index')) - Number($b.attr('data-added-index'));
      });

      $grid.append(nodes);
    }

    $(document).off('click.wishFilter').on('click.wishFilter', '.wishFilter', function () {
      $('.wishFilter').removeClass('bg-brand-secondary/20 text-brand-secondary font-medium')
        .addClass('text-gray-300');
      $(this).addClass('bg-brand-secondary/20 text-brand-secondary font-medium')
        .removeClass('text-gray-300');

      applyFilter($(this).data('wish-filter'));
    });

    $('#wishSort').off('change.wishSort').on('change.wishSort', function () {
      applySort($(this).val());
    });

    // init defaults
    applyFilter('all');
    applySort('recent');
    updateWishlistStats();
  }

  // Product page: heart button -> toggle wishlist (NO redirect)
  $(document).off('click.wishToggle').on('click.wishToggle', '.js-wish-toggle', function (e) {
    e.preventDefault();
    e.stopPropagation();

    const $btn = $(this);
    const url = $btn.data('url');
    if (!url) return;

    if ($btn.data('busy')) return;
    $btn.data('busy', true);

    if (!window.APP_AUTH.loggedIn) {
      promptLogin('Please login to use wishlist');
      $btn.data('busy', false);
      return;
    }

    $.ajax({
      url,
      type: 'POST',
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    })
      .done(function (res) {
        let added;

        if (res && typeof res.in_wishlist !== 'undefined') added = !!res.in_wishlist;
        else if (res && typeof res.added !== 'undefined') added = !!res.added;
        else added = String($btn.data('in') || '0') !== '1';

        $btn.data('in', added ? 1 : 0);

        // IMPORTANT: your button uses yellow classes, so update these:
        if (added) {
          $btn.removeClass('text-gray-300 hover:text-yellow-400').addClass('text-yellow-400');
          $btn.find('i').removeClass('bi-heart').addClass('bi-heart-fill');
        } else {
          $btn.removeClass('text-yellow-400').addClass('text-gray-300 hover:text-yellow-400');
          $btn.find('i').removeClass('bi-heart-fill').addClass('bi-heart');
        }
      })
      .always(function () {
        $btn.data('busy', false);
      });
  });

  // =========================
  // Cart page init (jQuery)
  // =========================
  function initCartPage() {
    const $par = $('.parallax-content');
    const $itemsWrap = $('#cart-items');

    const CSRF = $('meta[name="csrf-token"]').attr('content');

    function ajaxCartUpdate(id, qty) {
      return $.ajax({
        url: `/cart/update/${id}`,
        type: 'POST',
        data: { qty },
        headers: { 'X-CSRF-TOKEN': CSRF }
      });
    }

    function ajaxCartRemove(id) {
      return $.ajax({
        url: `/cart/remove/${id}`,
        type: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF }
      });
    }

    function ajaxCartClear() {
      return $.ajax({
        url: `/cart/clear`,
        type: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF }
      });
    }



    function syncHeaderCartCount(count) {
      setHeaderCartCount(count);
    }

    // Parallax hero content
    $(window).off('scroll.cartPar').on('scroll.cartPar', function () {
      const scrolled = window.pageYOffset || 0;
      if ($par.length) $par.css('transform', `translateY(${scrolled * 0.3}px)`);
    }).trigger('scroll.cartPar');

    // Particles
    const $p = $('#cartParticles');
    if ($p.length && !$p.data('inited')) {
      $p.data('inited', true);
      for (let i = 0; i < 15; i++) {
        const left = Math.random() * 100;
        const top = Math.random() * 100;
        const delay = i * 0.2;
        const duration = 3 + (i % 3);

        $('<div class="absolute w-2 h-2 rounded-full floating-element bg-brand-accent/20"></div>')
          .css({
            left: left + '%',
            top: top + '%',
            animationDelay: delay + 's',
            animationDuration: duration + 's'
          })
          .appendTo($p);
      }
    }

    function animateValue($el, start, end, duration, prefix = '') {
      const startTime = performance.now();
      function tick(now) {
        const p = Math.min((now - startTime) / duration, 1);
        const eased = 1 - Math.pow(1 - p, 3);
        const val = Math.round(start + (end - start) * eased);
        $el.text(prefix + val.toLocaleString());
        if (p < 1) requestAnimationFrame(tick);
      }
      requestAnimationFrame(tick);
    }

    function getCartTotals() {
      let subtotal = 0;
      let count = 0;

      $('.cart-item').each(function () {
        const price = parseInt($(this).data('price') || 0, 10);
        const qty = parseInt($(this).data('qty') || 1, 10);
        subtotal += price * qty;
        count += qty;
      });

      const tax = Math.round(subtotal * 0.05);
      const total = subtotal + tax;

      return { subtotal, tax, total, count };
    }

    function renderTotals() {
      const { subtotal, tax, total, count } = getCartTotals();

      const $sub = $('#subtotal');
      const $tax = $('#tax');
      const $final = $('#final-total');

      const curSub = parseInt(($sub.text() || '0').replace(/[^0-9]/g, ''), 10) || 0;
      const curTax = parseInt(($tax.text() || '0').replace(/[^0-9]/g, ''), 10) || 0;
      const curFinal = parseInt(($final.text() || '0').replace(/[^0-9]/g, ''), 10) || 0;

      animateValue($sub, curSub, subtotal, 400, 'AED ');
      animateValue($tax, curTax, tax, 400, 'AED ');
      animateValue($final, curFinal, total, 500, '');

      $('#cart-count').text(count);
      $('#cart-total-hero').text('AED ' + total.toLocaleString());

      syncHeaderCartCount(count);
    }

    function updateRowTotal($row) {
      const price = parseInt($row.data('price') || 0, 10);
      const qty = parseInt($row.data('qty') || 1, 10);
      const $total = $row.find('.item-total');

      const cur = parseInt(($total.text() || '0').replace(/[^0-9]/g, ''), 10) || 0;
      const next = price * qty;

      animateValue($total, cur, next, 250, 'AED ');
    }

    function ensureEmptyState() {
      if ($('.cart-item').length > 0) return;

      $itemsWrap.html(`
      <div class="glass-panel rounded-2xl p-12 text-center border border-white/10">
        <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-4">
          <i class="bi bi-cart-x text-4xl text-gray-500"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Your cart is empty</h3>
        <p class="text-gray-400 mb-6">Looks like you haven't added anything yet.</p>
        <a href="/shop" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition-colors">
          Start Shopping <i class="bi bi-arrow-right"></i>
        </a>
      </div>
    `);

      // reset totals
      $('#cart-count').text('0');
      $('#cart-total-hero').text('AED 0');
      $('#subtotal').text('AED 0');
      $('#tax').text('AED 0');
      $('#final-total').text('0');
    }

    // Qty minus
    $(document).off('click.cartMinus').on('click.cartMinus', '.js-qty-minus', function () {
      const id = $(this).data('id');
      const $row = $(`.cart-item[data-id="${id}"]`);
      const $input = $row.find('.js-qty-input');
      if (!$row.length) return;

      let oldQty = parseInt($row.data('qty') || $input.val() || 1, 10);
      let qty = Math.max(1, oldQty - 1);

      // UI first (smooth)
      $row.data('qty', qty);
      $input.val(qty);
      updateRowTotal($row);
      renderTotals();

      ajaxCartUpdate(id, qty).fail(function () {
        // revert if backend failed
        $row.data('qty', oldQty);
        $input.val(oldQty);
        updateRowTotal($row);
        renderTotals();
      });
    });

    // Qty plus
    $(document).off('click.cartPlus').on('click.cartPlus', '.js-qty-plus', function () {
      const id = $(this).data('id');
      const $row = $(`.cart-item[data-id="${id}"]`);
      const $input = $row.find('.js-qty-input');
      if (!$row.length) return;

      let oldQty = parseInt($row.data('qty') || $input.val() || 1, 10);
      let qty = Math.min(10, oldQty + 1);

      $row.data('qty', qty);
      $input.val(qty);
      updateRowTotal($row);
      renderTotals();

      ajaxCartUpdate(id, qty).fail(function () {
        $row.data('qty', oldQty);
        $input.val(oldQty);
        updateRowTotal($row);
        renderTotals();
      });
    });

    // Qty input change
    $(document).off('change.cartQty').on('change.cartQty', '.js-qty-input', function () {
      const id = $(this).data('id');
      const $row = $(`.cart-item[data-id="${id}"]`);
      if (!$row.length) return;

      const oldQty = parseInt($row.data('qty') || 1, 10);

      let qty = parseInt($(this).val() || '1', 10);
      if (!Number.isFinite(qty)) qty = 1;
      qty = Math.max(1, Math.min(10, qty));
      $(this).val(qty);

      $row.data('qty', qty);
      updateRowTotal($row);
      renderTotals();

      ajaxCartUpdate(id, qty).fail(() => {
        $row.data('qty', oldQty);
        $(this).val(oldQty);
        updateRowTotal($row);
        renderTotals();
      });
    });

    // Remove row
    $(document).off('click.cartRemove').on('click.cartRemove', '.js-cart-remove', function () {
      const id = $(this).data('id');
      const $row = $(`.cart-item[data-id="${id}"]`);
      if (!$row.length) return;

      ajaxCartRemove(id).done(function () {
        $row.css({ transform: 'translateX(100px)', opacity: 0 });
        setTimeout(() => {
          $row.remove();
          renderTotals();
          ensureEmptyState();
        }, 280);
      });
    });

    // Clear cart
    $(document).off('click.cartClear').on('click.cartClear', '#btnClearCart', function () {
      if (!confirm('Clear all items from cart?')) return;

      ajaxCartClear().done(function () {
        const $rows = $('.cart-item');
        $rows.each(function (i) {
          const $r = $(this);
          setTimeout(() => {
            $r.css({ transform: 'translateX(100px)', opacity: 0 });
            setTimeout(() => $r.remove(), 250);
          }, i * 60);
        });

        setTimeout(() => {
          renderTotals();
          ensureEmptyState();
          syncHeaderCartCount(0);
        }, $rows.length * 60 + 400);
      });
    });

    // Checkout
    $(document).off('click.checkoutStart').on('click.checkoutStart', '#btnCheckout', function () {
      const isAuth = String($(this).data('auth')) === '1';
      const checkoutUrl = $(this).data('checkout-url');

      if (!checkoutUrl) return;

      if (!isAuth) {
        sessionStorage.setItem('digitron_after_login_redirect', checkoutUrl);

        if (typeof promptLogin === 'function') {
          promptLogin('Please login to continue checkout');
        }
        return;
      }

      window.location.href = checkoutUrl;
    });

    // initial totals
    renderTotals();
  }

  initCategoryHero();
  initShopPriceRange();

  // Set CSS var for fixed header height (used by fullwidth heroes)
  (function setHeaderHeightVar() {
    const header = document.getElementById('siteHeader');
    if (!header) return;

    const set = () => {
      document.documentElement.style.setProperty('--headerH', header.offsetHeight + 'px');
    };

    set();
    window.addEventListener('resize', set, { passive: true });
  })();

  function setMobileCartCount(count) {
    const $b = $('.mobile-badge.cart-count');
    if (!$b.length) return;

    const c = parseInt(count || 0, 10) || 0;
    if (c > 0) $b.text(c).removeClass('hidden');
    else $b.text('').addClass('hidden');
  }

  function setHeaderCartCount(count) {
    const $b = $('#headerCartCount');
    if (!$b.length) return;

    const c = parseInt(count || 0, 10) || 0;

    if (c > 0) {
      $b.text(c).removeClass('hidden');
    } else {
      $b.text('').addClass('hidden');
    }
    setMobileCartCount(c)
  }

  $(document).off('click.cartAdd').on('click.cartAdd', '.js-cart-add', function (e) {
    e.preventDefault();
    e.stopPropagation();

    const $btn = $(this);
    const url = $btn.data('url');
    if (!url) return;

    if (!window.APP_AUTH.loggedIn) {
      if ($btn.data('busy')) return;

      $btn.data('busy', true);

      const oldHtml = $btn.html();
      const isQuickAdd = $btn.text().toLowerCase().includes('quick');
      const loginText = isQuickAdd ? 'Login to Quick Add' : 'Login to Add to Cart';
      const $card = $btn.closest('.product-card');

      if (isQuickAdd) {
        $card.addClass('force-quick-msg');
      }

      $btn.prop('disabled', true)
        .addClass('opacity-80 cursor-not-allowed')
        .html(`<i class="bi bi-lock-fill"></i> ${loginText}`);

      setTimeout(() => {
        $btn.html(oldHtml)
          .prop('disabled', false)
          .removeClass('opacity-80 cursor-not-allowed')
          .data('busy', false);

        if (isQuickAdd) {
          $card.removeClass('force-quick-msg');
        }
      }, 1800);

      setTimeout(() => {
        if (window.innerWidth < 768) {
          if (window.Alpine && Alpine.store('mobileAccount')) {
            Alpine.store('mobileAccount').openLogin();
          }
        } else {
          if (window.Alpine && Alpine.store('account')) {
            Alpine.store('account').openPanel();
          }
        }
      }, 400);

      return;
    }

    // qty can be selector (#qty) or number
    let qty = 1;
    const q = $btn.data('qty');

    if (typeof q === 'string' && q.startsWith('#')) {
      qty = parseInt($(q).val() || '1', 10) || 1;
    } else if (q) {
      qty = parseInt(q, 10) || 1;
    }
    qty = Math.max(1, Math.min(10, qty));

    if ($btn.data('busy')) return;
    $btn.data('busy', true);

    const oldHtml = $btn.html();
    $btn.prop('disabled', true).addClass('opacity-80 cursor-not-allowed');
    $btn.html('<i class="bi bi-arrow-repeat animate-spin"></i> Adding...');

    const $badge = $('#headerCartCount');
    const oldCount = parseInt(($badge.text() || '0'), 10) || 0;
    setHeaderCartCount(oldCount + qty);

    $.ajax({
      url,
      type: 'POST',
      data: { qty },
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    })
      .done(function (res) {
        if (res && (res.ok === true || typeof res.count !== 'undefined')) {
          if (typeof res.count !== 'undefined') setHeaderCartCount(res.count);

          $btn.html('<i class="bi bi-check2"></i> Added');

          if (String(document.body?.dataset?.page || '') === 'cart') {
            window.location.reload();
            return;
          }

          setTimeout(() => $btn.html(oldHtml), 800);
        } else {
          setHeaderCartCount(oldCount);
          $btn.html(oldHtml);
        }
      })
      .fail(function () {
        setHeaderCartCount(oldCount);
        $btn.html(oldHtml);
      })
      .always(function () {
        $btn.data('busy', false);
        $btn.prop('disabled', false).removeClass('opacity-80 cursor-not-allowed');
      });
  });

  // =========================
  // Wishlist -> Add to Cart
  // =========================
  function initWishlistCartActions() {
    const CSRF = $('meta[name="csrf-token"]').attr('content');

    function syncHeaderCartCount(count) {
      const $b = $('#headerCartCount');
      if ($b.length) {
        const c = parseInt(count || 0, 10) || 0;
        setHeaderCartCount(count);
      }
    }

    // Single item add-to-cart
    $(document).off('click.wishAddCart').on('click.wishAddCart', '.js-add-to-cart', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const $btn = $(this);
      const url = $btn.data('url');
      const stock = String($btn.data('stock') || '').toLowerCase();

      if (!url) return;
      if (stock.includes('out')) return;

      if ($btn.data('busy')) return;
      $btn.data('busy', true);

      const oldHtml = $btn.html();
      $btn.html('<i class="bi bi-arrow-repeat animate-spin"></i> Adding...');

      $.ajax({
        url,
        type: 'POST',
        data: { qty: 1 },
        headers: { 'X-CSRF-TOKEN': CSRF }
      })
        .done(function (res) {
          // your CartController returns {ok:true, count:...}
          if (res && res.ok) {
            syncHeaderCartCount(res.count);

            // small success UI
            $btn.html('<i class="bi bi-check2"></i> Added');
            setTimeout(() => $btn.html(oldHtml), 900);
          } else {
            $btn.html(oldHtml);
          }
        })
        .fail(function () {
          $btn.html(oldHtml);
        })
        .always(function () {
          $btn.data('busy', false);
        });
    });

    // Add all to cart
    $(document).off('click.wishAddAll').on('click.wishAddAll', '#btnAddAllToCart', function (e) {
      e.preventDefault();

      const $btn = $(this);
      const tpl = $btn.data('url-template');
      if (!tpl) return;

      // collect ids from wishlist cards
      const ids = $('.js-add-to-cart').map(function () {
        const stock = String($(this).data('stock') || '').toLowerCase();
        if (stock.includes('out')) return null;
        return $(this).data('id');
      }).get().filter(Boolean);

      if (!ids.length) return;

      if ($btn.data('busy')) return;
      $btn.data('busy', true);

      const oldHtml = $btn.html();
      $btn.html('<i class="bi bi-arrow-repeat animate-spin"></i> Adding...');

      // sequential requests to avoid server spam
      let lastCount;
      const run = ids.reduce((p, id) => {
        return p.then(() => {
          const url = String(tpl).replace('__ID__', id);
          return $.ajax({
            url,
            type: 'POST',
            data: { qty: 1 },
            headers: { 'X-CSRF-TOKEN': CSRF }
          }).done((res) => {
            if (res && res.ok) lastCount = res.count;
          });
        });
      }, Promise.resolve());

      run
        .then(() => {
          syncHeaderCartCount(lastCount);

          $btn.html('<i class="bi bi-check2"></i> Added All');
          setTimeout(() => $btn.html(oldHtml), 1000);
        })
        .catch(() => {
          $btn.html(oldHtml);
        })
        .finally(() => {
          $btn.data('busy', false);
        });
    });
  }

  (function initBuilderSliders() {
    $(document).off('click.builderNav').on('click.builderNav', '.builder-nav', function () {
      const target = $(this).data('target');
      const $track = $(target);
      if (!$track.length) return;

      const dir = $(this).hasClass('builder-prev') ? -1 : 1;
      const amount = Math.round($track[0].clientWidth * 0.8); // scroll by ~80% view

      $track[0].scrollBy({ left: dir * amount, behavior: 'smooth' });
    });
  })();

  window.selectComponentFromCard = function (category, el) {
    const payload = el?.dataset?.payload ? JSON.parse(el.dataset.payload) : null;
    if (!payload) return;

    // keep same parameter order
    if (typeof window.selectComponent === 'function') {
      window.selectComponent(category, el, payload);
    }
  };

  // store selected parts
  window.builderSelected = window.builderSelected || {
    cpu: null,
    gpu: null,
    ram: null
  };

  window.selectComponent = function (category, el, data) {
    buildState[category] = data;

    // use el (NOT element)
    const categoryContainer = el.closest('.component-category');
    if (!categoryContainer) return;

    const cards = categoryContainer.querySelectorAll('.component-card');

    cards.forEach(card => card.classList.remove('selected'));
    el.classList.add('selected');

    updateSummary();
  };

  function initAboutPage() {
    // Run only on About page
    if (String(document.body?.dataset?.page || '') !== 'about') return;

    // Parallax Effects
    const onScroll = () => {
      const scrolled = window.pageYOffset || 0;

      const hero = document.querySelector('.parallax-hero');
      if (hero) hero.style.transform = `translateY(${scrolled * 0.3}px)`;

      const slowBg = document.querySelector('.parallax-bg-slow');
      if (slowBg) slowBg.style.transform = `translateY(${scrolled * 0.1}px) scale(1.1)`;
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll(); // run once on load

    // Scroll to Story (if your button uses onclick="scrollToStory()")
    window.scrollToStory = function () {
      const story = document.getElementById('story');
      if (story) story.scrollIntoView({ behavior: 'smooth' });
    };

    // Counter Animation
    document.querySelectorAll('.counter-up').forEach((counter) => {
      const target = counter.dataset.target || '';
      const numValue = parseInt(String(target).replace(/[^0-9]/g, ''), 10) || 0;

      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;

          let current = 0;
          const increment = numValue / 50;

          const timer = setInterval(() => {
            current += increment;

            if (current >= numValue) {
              counter.textContent = target;
              clearInterval(timer);
            } else {
              const suffix =
                target.includes('+') ? '+' :
                  target.includes('h') ? 'h' : '';

              counter.textContent = Math.floor(current).toLocaleString() + suffix;
            }
          }, 30);

          observer.unobserve(entry.target);
        });
      });

      observer.observe(counter);
    });

    // Feature Card Mouse Tracking
    document.querySelectorAll('.feature-card').forEach((card) => {
      card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        card.style.setProperty('--mouse-x', `${x}%`);
        card.style.setProperty('--mouse-y', `${y}%`);
      });
    });

    // Reveal on Scroll
    const revealElements = document.querySelectorAll('.reveal-text');
    const revealObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) entry.target.classList.add('visible');
        });
      },
      { threshold: 0.1 }
    );
    revealElements.forEach((el) => revealObserver.observe(el));

    // GSAP Animations (only if gsap loaded globally)
    if (typeof window.gsap !== 'undefined' && typeof window.ScrollTrigger !== 'undefined') {
      window.gsap.registerPlugin(window.ScrollTrigger);

      window.gsap.from('.timeline-item', {
        scrollTrigger: { trigger: '#story', start: 'top 60%' },
        y: 60,
        opacity: 0,
        duration: 0.8,
        stagger: 0.2,
        ease: 'power3.out'
      });

      window.gsap.from('.feature-card', {
        scrollTrigger: { trigger: '.feature-card', start: 'top 85%' },
        y: 80,
        opacity: 0,
        duration: 0.6,
        stagger: 0.1,
        ease: 'back.out(1.7)'
      });

      window.gsap.from('.team-card', {
        scrollTrigger: { trigger: '.team-card', start: 'top 85%' },
        y: 60,
        opacity: 0,
        duration: 0.8,
        stagger: 0.15,
        ease: 'power3.out'
      });
    }

    // Video Speed Control
    const heroVideo = document.getElementById('hero-video');
    if (heroVideo) heroVideo.playbackRate = 0.7;
  }

  const qt = document.getElementById('quote_type');
  const qi = document.getElementById('quoteTypeIcon');

  function setQuoteIcon(val) {
    if (!qi) return;

    const map = {
      'gaming-pc': 'bi-controller',
      'workstation': 'bi-pc-display',
      'streaming': 'bi-broadcast',
      'part-request': 'bi-tools',
      'pre-built': 'bi-lightning-charge',
      'upgrade': 'bi-arrow-up-circle',
      'repair': 'bi-wrench-adjustable',
      'bulk': 'bi-building'
    };

    qi.className =
      'bi ' + (map[val] || 'bi-controller') +
      ' absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none';
  }

  if (qt) {
    setQuoteIcon(qt.value);
    qt.addEventListener('change', () => setQuoteIcon(qt.value));
  }

  function initRevealText() {
    const els = document.querySelectorAll('.reveal-text');
    if (!els.length) return;

    const io = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });

    els.forEach(el => io.observe(el));
  }

  initRevealText();

  (function initDigitronChatbot() {
    const $root = $('.dc-chatbot');
    if (!$root.length) return;

    const $toggle = $root.find('.dc-chatbot-toggle');
    const $panel = $root.find('.dc-chatbot-panel');
    const $close = $root.find('.dc-chatbot-close');
    const $body = $('#dcChatbotBody');
    const $form = $('#dcChatbotForm');
    const $input = $('#dcChatbotInput');

    const waNumber = String($root.data('wa-number') || '').trim();
    const defaultMessage = String($root.data('wa-default') || 'Hello, I need support.').trim();
    const pageType = String($root.data('page-type') || 'general').trim();

    const leadUrl = String($root.data('lead-url') || '').trim();
    const csrfToken = String($root.data('csrf') || '').trim();

    function saveLead(payload = {}) {
      if (!leadUrl) return Promise.resolve();

      return fetch(leadUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          product_id: product.id || null,
          product_name: product.name || '',
          product_sku: product.sku || '',
          message: payload.message || '',
          source_type: payload.source_type || '',
          button_label: payload.button_label || '',
          page_type: pageType || ''
        })
      }).catch(() => null);
    }

    const product = {
      id: String($root.data('product-id') || '').trim(),
      name: String($root.data('product-name') || '').trim(),
      sku: String($root.data('product-sku') || '').trim(),
      price: String($root.data('product-price') || '').trim(),
      stock: String($root.data('product-stock') || '').trim(),
      warranty: String($root.data('product-warranty') || '').trim(),
      delivery: String($root.data('product-delivery') || '').trim()
    };

    const faq = [
      {
        keywords: ['delivery', 'shipping', 'dispatch'],
        answer: function () {
          if (pageType === 'product' && product.delivery) {
            return `Delivery for ${escapeHtml(product.name || 'this product')}: ${escapeHtml(product.delivery)}`;
          }
          return 'We deliver across the UAE. Delivery timing depends on product availability and your location.';
        }
      },
      {
        keywords: ['warranty', 'guarantee'],
        answer: function () {
          if (pageType === 'product' && product.warranty) {
            return `Warranty for ${escapeHtml(product.name || 'this product')}: ${escapeHtml(product.warranty)}`;
          }
          return 'Warranty depends on the product. Most items include warranty support. You can also ask us on WhatsApp for exact product warranty details.';
        }
      },
      {
        keywords: ['payment', 'cash', 'card', 'tabby', 'tamara'],
        answer: function () {
          return 'We support standard payment options. For installment services like Tabby or Tamara, please confirm availability with our support team.';
        }
      },
      {
        keywords: ['location', 'store', 'shop', 'address'],
        answer: function () {
          return 'You can contact our support team for exact store location and directions. We will gladly guide you.';
        }
      },
      {
        keywords: ['stock', 'available', 'availability', 'in stock'],
        answer: function () {
          if (pageType === 'product' && product.stock) {
            return `${escapeHtml(product.name || 'This product')} is currently marked as: <strong>${escapeHtml(product.stock)}</strong>.`;
          }
          return 'Stock changes frequently. Please share the product name and we will confirm the latest availability for you.';
        }
      },
      {
        keywords: ['price', 'final price', 'cost'],
        answer: function () {
          if (pageType === 'product' && product.price) {
            return `The listed price for ${escapeHtml(product.name || 'this product')} is <strong>AED ${escapeHtml(product.price)}</strong>. Final confirmation can continue on WhatsApp if needed.`;
          }
          return 'Please share the product name so we can confirm the latest price for you.';
        }
      },
      {
        keywords: ['custom pc', 'gaming pc', 'workstation', 'build pc', 'custom build'],
        answer: function () {
          return 'Yes, we can help with custom gaming PCs, workstation builds, upgrades, and performance recommendations.';
        }
      },
      {
        keywords: ['repair', 'fix', 'service'],
        answer: function () {
          return 'Yes, please tell us what issue you are facing, and our team will guide you on the next step.';
        }
      },
      {
        keywords: ['return', 'refund'],
        answer: function () {
          return 'Return and refund eligibility depends on the product and its condition. Please contact support with your order details.';
        }
      }
    ];

    function scrollToBottom() {
      setTimeout(() => {
        if ($body.length) {
          $body.scrollTop($body[0].scrollHeight);
        }
      }, 50);
    }

    function escapeHtml(text) {
      return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    function addUserMessage(text) {
      $body.append(`
      <div class="dc-chat-msg dc-chat-msg-user">
        <div class="dc-chat-bubble">${escapeHtml(text)}</div>
      </div>
    `);
      scrollToBottom();
    }

    function addBotMessage(text) {
      $body.append(`
      <div class="dc-chat-msg dc-chat-msg-bot">
        <div class="dc-chat-bubble">${text}</div>
      </div>
    `);
      scrollToBottom();
    }

    function openWhatsApp(text) {
      const msg = encodeURIComponent(text || defaultMessage);
      const url = `https://wa.me/${waNumber}?text=${msg}`;
      window.open(url, '_blank');
    }

    function prefillInput(force = false) {
      if (!$input.length) return;

      const current = String($input.val() || '').trim();
      if (force || !current) {
        $input.val(defaultMessage);
      }
    }

    function normalizeText(text) {
      return String(text || '')
        .toLowerCase()
        .replace(/\s+/g, ' ')
        .trim();
    }

    function isExactDefaultMessage(message) {
      return normalizeText(message) === normalizeText(defaultMessage);
    }

    function looksEditedFromDefault(message) {
      const clean = normalizeText(message);
      const base = normalizeText(defaultMessage);

      if (!clean || !base) return false;
      if (clean === base) return false;

      return clean.includes(base) || base.includes(clean);
    }

    function getProductIntroReply() {
      const parts = [];

      if (product.name) {
        parts.push(`<strong>${escapeHtml(product.name)}</strong>`);
      }

      if (product.sku) {
        parts.push(`SKU: ${escapeHtml(product.sku)}`);
      }

      if (product.price) {
        parts.push(`Price: AED ${escapeHtml(product.price)}`);
      }

      if (product.stock) {
        parts.push(`Availability: ${escapeHtml(product.stock)}`);
      }

      if (parts.length) {
        return `Here are the quick details for this product:<br>${parts.join('<br>')}<br><br>You can also ask me about warranty, delivery, or click WhatsApp for a custom request.`;
      }

      return 'I can help with quick answers for this product like price, stock, delivery, warranty, and availability. For custom requests, continue on WhatsApp.';
    }

    function findFaqAnswer(message) {
      const q = normalizeText(message);
      if (!q) return null;

      for (const item of faq) {
        if (item.keywords.some(k => q.includes(k))) {
          return typeof item.answer === 'function' ? item.answer() : item.answer;
        }
      }

      return null;
    }

    function handleQuestion(message, options = {}) {
      const clean = String(message || '').trim();
      if (!clean) return;

      const fromChip = options.fromChip === true;

      if (!fromChip) {
        addUserMessage(clean);
      }

      if (clean === '__OPEN_WHATSAPP__') {
        setTimeout(() => {
          addBotMessage('Opening WhatsApp for direct support.');
          openWhatsApp(defaultMessage);
        }, 250);
        return;
      }

      if (pageType === 'product' && isExactDefaultMessage(clean)) {
        saveLead({
          message: clean,
          source_type: 'default_message'
        });

        setTimeout(() => {
          addBotMessage(getProductIntroReply());
        }, 300);
        return;
      }

      const answer = findFaqAnswer(clean);
      if (answer) {
        setTimeout(() => {
          addBotMessage(answer);
        }, 300);
        return;
      }

      if (pageType === 'product' && looksEditedFromDefault(clean)) {
        saveLead({
          message: clean,
          source_type: 'custom_message'
        });

        setTimeout(() => {
          addBotMessage('This looks like a custom or specific request. Please continue on WhatsApp for direct support.');
          setTimeout(() => {
            openWhatsApp(clean);
          }, 5000);
        }, 250);
        return;
      }

      saveLead({
        message: clean,
        source_type: 'custom_message'
      });

      setTimeout(() => {
        addBotMessage('This looks like a custom or specific request. Please continue on WhatsApp for direct support.');
        setTimeout(() => {
          openWhatsApp(clean);
        }, 5000);
      }, 250);
    }

    $toggle.on('click', function () {
      $panel.toggleClass('hidden');

      if (!$panel.hasClass('hidden')) {
        prefillInput(false);
        $input.trigger('focus');

        const val = String($input.val() || '');
        if ($input.length && $input[0].setSelectionRange) {
          $input[0].setSelectionRange(val.length, val.length);
        }

        scrollToBottom();
      }
    });

    $close.on('click', function () {
      $panel.addClass('hidden');
    });

    $(document).on('click', function (e) {
      if (!$(e.target).closest('.dc-chatbot').length) {
        $panel.addClass('hidden');
      }
    });

    $(document).on('click', '.dc-chat-chip', function () {
      const q = String($(this).data('question') || '').trim();
      const label = String($(this).text() || '').trim();

      if (!q) return;

      if (q === '__OPEN_WHATSAPP__') {
        addBotMessage('Opening WhatsApp for direct support.');
        setTimeout(() => {
          openWhatsApp(defaultMessage);
        }, 300);
        return;
      }

      saveLead({
        message: q,
        source_type: 'quick_button',
        button_label: label
      });

      handleQuestion(q, { fromChip: true });
    });

    $form.on('submit', function (e) {
      e.preventDefault();

      const message = String($input.val() || '').trim();
      if (!message) return;

      handleQuestion(message);
      $input.val('');
    });
  })();

  document.querySelectorAll('.loader-particle').forEach(el => {
    el.style.left = el.dataset.left + '%';
    el.style.top = el.dataset.top + '%';
    el.style.animationDelay = el.dataset.delay + 's';
  });

  (function syncPostLoginRedirect() {
    const redirect = sessionStorage.getItem('digitron_after_login_redirect') || '';

    [
      '#desktopLoginRedirectTo',
      '#desktopRegisterRedirectTo',
      '#mobileLoginRedirectTo',
      '#mobileRegisterRedirectTo'
    ].forEach(sel => {
      const el = document.querySelector(sel);
      if (el) el.value = redirect;
    });
  })();

  $(document).off('submit.checkoutForm').on('submit.checkoutForm', '#checkoutForm', function () {
    const $btn = $('#placeOrderBtn');
    if (!$btn.length) return;

    $btn.prop('disabled', true)
      .addClass('opacity-80 cursor-not-allowed')
      .html('<i class="bi bi-arrow-repeat animate-spin"></i><span>Placing Order...</span>');
  });


  // Poster Slider
  (function initPosterSlider() {
    const slider = document.getElementById('dcPosterSlider');
    if (!slider) return;

    const slides = slider.querySelectorAll('.dc-poster-slide');
    const dots = slider.querySelectorAll('.dc-poster-dots button');
    const prevBtn = slider.querySelector('.dc-poster-arrow.prev');
    const nextBtn = slider.querySelector('.dc-poster-arrow.next');
    const progressBar = slider.querySelector('.dc-poster-progress');

    let current = 0;
    const total = slides.length;
    const interval = 6000;
    let timer = null;

    if (!total) return;

    if (total <= 1) {
      slides.forEach((slide, i) => {
        slide.classList.toggle('is-active', i === 0);
      });
      return;
    }

    function updateDots(index) {
      dots.forEach((dot, i) => {
        dot.classList.toggle('w-8', i === index);
        dot.classList.toggle('bg-brand-accent', i === index);
        dot.classList.toggle('bg-white/30', i !== index);
      });
    }

    function restartProgress() {
      if (!progressBar) return;

      progressBar.style.transition = 'none';
      progressBar.style.width = '0%';

      setTimeout(() => {
        progressBar.style.transition = `width ${interval}ms linear`;
        progressBar.style.width = '100%';
      }, 60);
    }

    function goTo(index) {
      slides.forEach((slide, i) => {
        slide.classList.remove('is-active', 'is-exiting');

        if (i === current && current !== index) {
          slide.classList.add('is-exiting');
        }
      });

      current = (index + total) % total;
      slides[current].classList.add('is-active');

      updateDots(current);
      resetTimer();
    }

    function next() {
      goTo(current + 1);
    }

    function prev() {
      goTo(current - 1);
    }

    function stopTimer() {
      if (timer) {
        clearInterval(timer);
        timer = null;
      }

      if (progressBar) {
        const computedWidth = window.getComputedStyle(progressBar).width;
        progressBar.style.transition = 'none';
        progressBar.style.width = computedWidth;
      }
    }

    function resetTimer() {
      stopTimer();
      restartProgress();
      timer = setInterval(next, interval);
    }

    nextBtn?.addEventListener('click', next);
    prevBtn?.addEventListener('click', prev);

    dots.forEach((dot, index) => {
      dot.addEventListener('click', function () {
        goTo(index);
      });
    });

    slider.addEventListener('mouseenter', stopTimer);
    slider.addEventListener('mouseleave', resetTimer);

    slides.forEach((slide, i) => {
      slide.classList.toggle('is-active', i === 0);
    });

    updateDots(0);
    resetTimer();
  })();

  // Counter Animation
  (function initCounters() {
    const counters = document.querySelectorAll('.counter');
    if (!counters.length) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;

        const target = entry.target;
        const finalValue = target.dataset.target || '0';

        if (/[a-zA-Z]/.test(finalValue) && !/^\d+$/.test(finalValue)) {
          target.textContent = finalValue;
          observer.unobserve(target);
          return;
        }

        const numericValue = parseInt(String(finalValue).replace(/,/g, ''), 10) || 0;
        let current = 0;
        const step = Math.max(1, Math.ceil(numericValue / 40));

        const timer = setInterval(() => {
          current += step;

          if (current >= numericValue) {
            target.textContent = numericValue.toLocaleString();
            clearInterval(timer);
          } else {
            target.textContent = current.toLocaleString();
          }
        }, 35);

        observer.unobserve(target);
      });
    }, { threshold: 0.45 });

    counters.forEach(counter => observer.observe(counter));
  })();

  // Orbit Hover Effects
  (function initOrbitEffects() {
    const categories = document.querySelectorAll('.dc-orbit-category');
    if (!categories.length) return;

    categories.forEach((cat, index) => {
      cat.style.opacity = '0';
      cat.style.transform = 'translateY(30px)';

      setTimeout(() => {
        cat.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
        cat.style.opacity = '1';
        cat.style.transform = 'translateY(0)';
      }, index * 100);

      cat.addEventListener('mousemove', (e) => {
        const circle = cat.querySelector('.dc-orbit-circle');
        if (!circle) return;

        const rect = cat.getBoundingClientRect();
        const x = e.clientX - rect.left - rect.width / 2;
        const y = e.clientY - rect.top - rect.height / 2;

        circle.style.transform = `translate(${x * 0.05}px, ${y * 0.05}px) scale(1.06)`;
      });

      cat.addEventListener('mouseleave', () => {
        const circle = cat.querySelector('.dc-orbit-circle');
        if (!circle) return;

        circle.style.transform = '';
      });
    });
  })();

  // GSAP
  if (typeof window.gsap !== 'undefined' && typeof window.ScrollTrigger !== 'undefined') {
    window.gsap.registerPlugin(window.ScrollTrigger);

    // Safety reset for promo cards
    window.gsap.set('.dc-promo-card', {
      opacity: 1,
      x: 0,
      clearProps: 'transform'
    });

    // Only animate if showcase exists and cards are present
    const promoCards = document.querySelectorAll('.dc-promo-card');
    const showcase = document.querySelector('.dc-showcase-section');

    if (showcase && promoCards.length) {
      window.gsap.from('.dc-promo-card', {
        scrollTrigger: {
          trigger: showcase,
          start: 'top 85%',
          toggleActions: 'play none none none',
          once: true,
        },
        x: 24,
        opacity: 0,
        duration: 0.65,
        stagger: 0.12,
        ease: 'power3.out',
        onComplete: function () {
          // Force-clear ALL inline styles GSAP set so CSS hover takes full control
          promoCards.forEach(function (card) {
            card.style.removeProperty('transform');
            card.style.removeProperty('opacity');
            card.style.removeProperty('x');
            card.style.removeProperty('will-change');
          });
        }
      });
    }
  }

  (function initDesktopMegaMenu() {
    const root = document.getElementById('dcMenuRoot');
    if (!root) return;

    const btn = root.querySelector('.dc-menu-btn');
    const panel = root.querySelector('#dcMenuPanel');
    if (!btn || !panel) return;

    const mainItems = root.querySelectorAll('.dc-main-item[data-mega]');
    const megaPanes = root.querySelectorAll('.dc-mega-pane');
    const tabs = root.querySelectorAll('.dc-tab');
    const tabPanels = root.querySelectorAll('.dc-tabpanel');

    let closeMenuTimer = null;
    let closePaneTimer = null;

    const MENU_CLOSE_DELAY = 350;   // full menu close
    const PANE_CLOSE_DELAY = 300;   // right section close

    function openMenu() {
      clearTimeout(closeMenuTimer);
      root.classList.add('is-open');
      btn.setAttribute('aria-expanded', 'true');
    }

    function scheduleCloseMenu() {
      clearTimeout(closeMenuTimer);
      closeMenuTimer = setTimeout(() => {
        root.classList.remove('is-open');
        btn.setAttribute('aria-expanded', 'false');

        // restore default active state
        const defaultItem = root.querySelector('.dc-main-item[data-mega="pc"]');
        const defaultPane = root.querySelector('.dc-mega-pane[data-pane="pc"]');

        mainItems.forEach(i => i.classList.remove('is-active'));
        megaPanes.forEach(p => p.classList.remove('is-active'));

        if (defaultItem) defaultItem.classList.add('is-active');
        if (defaultPane) defaultPane.classList.add('is-active');
      }, MENU_CLOSE_DELAY);
    }

    function cancelCloseMenu() {
      clearTimeout(closeMenuTimer);
    }

    function scheduleClosePane() {
      clearTimeout(closePaneTimer);
      closePaneTimer = setTimeout(() => {
        const defaultItem = root.querySelector('.dc-main-item[data-mega="pc"]');
        const defaultPane = root.querySelector('.dc-mega-pane[data-pane="pc"]');

        mainItems.forEach(i => i.classList.remove('is-active'));
        megaPanes.forEach(p => p.classList.remove('is-active'));

        if (defaultItem) defaultItem.classList.add('is-active');
        if (defaultPane) defaultPane.classList.add('is-active');
      }, PANE_CLOSE_DELAY);
    }

    function cancelClosePane() {
      clearTimeout(closePaneTimer);
    }

    // Open/close whole menu
    root.addEventListener('mouseenter', () => {
      openMenu();
      cancelClosePane();
    });

    root.addEventListener('mouseleave', () => {
      scheduleCloseMenu();
      scheduleClosePane();
    });

    panel.addEventListener('mouseenter', () => {
      cancelCloseMenu();
      cancelClosePane();
    });

    panel.addEventListener('mouseleave', () => {
      scheduleCloseMenu();
      scheduleClosePane();
    });

    btn.addEventListener('click', (e) => {
      e.preventDefault();
      clearTimeout(closeMenuTimer);
      clearTimeout(closePaneTimer);

      const isOpen = root.classList.contains('is-open');
      root.classList.toggle('is-open', !isOpen);
      btn.setAttribute('aria-expanded', String(!isOpen));
    });

    // LEFT SIDE main items
    mainItems.forEach(item => {
      item.addEventListener('mouseenter', () => {
        cancelCloseMenu();
        cancelClosePane();

        const key = item.dataset.mega;

        mainItems.forEach(i => i.classList.remove('is-active'));
        megaPanes.forEach(p => p.classList.remove('is-active'));

        item.classList.add('is-active');
        root.querySelector(`.dc-mega-pane[data-pane="${key}"]`)?.classList.add('is-active');
      });
    });

    // If cursor leaves the left area briefly, don't instantly collapse the pane
    const leftArea = root.querySelector('.dc-menu-left');
    if (leftArea) {
      leftArea.addEventListener('mouseleave', () => {
        scheduleClosePane();
      });

      leftArea.addEventListener('mouseenter', () => {
        cancelClosePane();
        cancelCloseMenu();
      });
    }

    // RIGHT SIDE pane
    megaPanes.forEach(pane => {
      pane.addEventListener('mouseenter', () => {
        cancelClosePane();
        cancelCloseMenu();
      });

      pane.addEventListener('mouseleave', () => {
        scheduleClosePane();
      });
    });

    // TOP tabs
    tabs.forEach(tab => {
      tab.addEventListener('mouseenter', () => {
        cancelClosePane();
        cancelCloseMenu();

        const key = tab.dataset.tab;

        tabs.forEach(t => t.classList.remove('is-active'));
        tabPanels.forEach(p => p.classList.remove('is-active'));

        tab.classList.add('is-active');
        root.querySelector(`.dc-tabpanel[data-tabpanel="${key}"]`)?.classList.add('is-active');
      });
    });

    root.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        clearTimeout(closeMenuTimer);
        clearTimeout(closePaneTimer);
        root.classList.remove('is-open');
        btn.setAttribute('aria-expanded', 'false');
      }
    });
  })();

  // run after everything loads
  initAboutPage();

  const page = $('body').data('page') || '';
  if (page === 'wishlist') initWishlistPage();
  if (page === 'wishlist') initWishlistCartActions();
  if (page === 'cart') initCartPage();

});
