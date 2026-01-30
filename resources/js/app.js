import './bootstrap';
import '../css/app.css';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import Lenis from 'lenis';

const lenis = new Lenis({
  duration: 1.6,     // increase for more “slow”
  smoothWheel: true,
  smoothTouch: false
});

function raf(time) {
  lenis.raf(time);
  requestAnimationFrame(raf);
}
requestAnimationFrame(raf);

// Background parallax (only when enabled)
(function initBgParallax(){
  if (!document.body || document.body.dataset.bgParallax !== "1") return;

  let ticking = false;

  function update(){
    const y = window.scrollY || 0;
    document.body.style.setProperty('--bgY', (y * 0.15) + 'px');
    ticking = false;
  }

  function onScroll(){
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(update);
  }

  window.addEventListener('scroll', onScroll, { passive: true });
  update();
})();


(function initIconMegaMenu(){
  const root = document.getElementById('dcMenuRoot');
  if (!root) return;

  const mainPcBtn = root.querySelector('.dc-main-item[data-mega="pc"]');
  const panes = root.querySelectorAll('.dc-mega-pane[data-pane]');
  const tabs = root.querySelectorAll('.dc-tab[data-tab]');
  const tabpanels = root.querySelectorAll('.dc-tabpanel[data-tabpanel]');

  function setPane(key){
    panes.forEach(p => p.classList.toggle('is-active', p.dataset.pane === key));
    // default tab when pane changes
    setTab('new');
  }

  function setTab(key){
    tabs.forEach(t => t.classList.toggle('is-active', t.dataset.tab === key));
    tabpanels.forEach(p => p.classList.toggle('is-active', p.dataset.tabpanel === key));
  }

  // Hover PC Components opens its mega
  mainPcBtn?.addEventListener('mouseenter', () => setPane('pc'));
  mainPcBtn?.addEventListener('focus', () => setPane('pc'));
  mainPcBtn?.addEventListener('click', () => setPane('pc'));

  // Tabs switching
  tabs.forEach(t => {
    t.addEventListener('mouseenter', () => setTab(t.dataset.tab));
    t.addEventListener('focus', () => setTab(t.dataset.tab));
    t.addEventListener('click', () => setTab(t.dataset.tab));
  });

  // defaults
  setPane('pc');
  setTab('new');
})();

(function(){
  const wrap = document.getElementById('dcMenuRoot');
  if(!wrap) return;

  function updateSide(){
    const r = wrap.getBoundingClientRect();
    const nearRight = r.left > (window.innerWidth * 0.45);
    wrap.classList.toggle('is-right', nearRight);
  }

  window.addEventListener('resize', updateSide);
  updateSide();
})();

document.addEventListener('DOMContentLoaded', () => {
  const root  = document.getElementById('dcMenuRoot');
  const panel = document.getElementById('dcMenuPanel');
  if (!root || !panel) return;

  const pcBtn     = root.querySelector('.dc-main-item[data-mega="pc"]');
  const leftArea  = root.querySelector('.dc-menu-left');

  const tabs      = root.querySelectorAll('.dc-tab[data-tab]');
  const tabpanels = root.querySelectorAll('.dc-tabpanel[data-tabpanel]');

  // timings
  const OPEN_DELAY  = 200;   // open slower
  const CLOSE_DELAY = 1000;  // close after 3s (change if you want)

  let tOpen = null;
  let tClose = null;

  function openMenu(){
    clearTimeout(tClose);
    clearTimeout(tOpen);
    tOpen = setTimeout(() => {
      root.classList.add('is-open');
      if (!panel.dataset.state) panel.dataset.state = 'compact';
    }, OPEN_DELAY);
  }

  function closeMenu(){
    clearTimeout(tOpen);
    clearTimeout(tClose);
    tClose = setTimeout(() => {
      root.classList.remove('is-open');
      panel.dataset.state = 'compact';
    }, CLOSE_DELAY);
  }

  // open/close on hover
  root.addEventListener('mouseenter', openMenu);
  root.addEventListener('mouseleave', closeMenu);

  // keyboard support
  root.addEventListener('focusin', openMenu);
  root.addEventListener('focusout', closeMenu);

  // expand on PC Components hover
  pcBtn?.addEventListener('mouseenter', () => panel.dataset.state = 'expanded');
  pcBtn?.addEventListener('focus',      () => panel.dataset.state = 'expanded');

  // if hovering other left items -> compact
  leftArea?.addEventListener('mouseover', (e) => {
    const overPc = e.target.closest('.dc-main-item[data-mega="pc"]');
    if (!overPc) panel.dataset.state = 'compact';
  });

  // tabs switching
  function setTab(key){
    tabs.forEach(t => t.classList.toggle('is-active', t.dataset.tab === key));
    tabpanels.forEach(p => p.classList.toggle('is-active', p.dataset.tabpanel === key));
  }
  tabs.forEach(t => {
    t.addEventListener('mouseenter', () => setTab(t.dataset.tab));
    t.addEventListener('focus',      () => setTab(t.dataset.tab));
    t.addEventListener('click',      () => setTab(t.dataset.tab));
  });

  // defaults
  panel.dataset.state = panel.dataset.state || 'compact';
  setTab('new');
});

document.addEventListener('DOMContentLoaded', () => {
  const hero = document.getElementById('vsHero');
  if (!hero) return;

  const slides = Array.from(hero.querySelectorAll('.vs-slide'));
  const dotsWrap = hero.querySelector('.vs-dots');

  const autoplay = hero.dataset.autoplay === "1";
  const interval = Number(hero.dataset.interval || 6500);

  let idx = 0;
  let timer = null;

  // build dots
  dotsWrap.innerHTML = slides.map((_, i) =>
    `<button class="vs-dot ${i===0?'is-active':''}" type="button" aria-label="Go to slide ${i+1}" data-i="${i}"></button>`
  ).join('');
  const dots = Array.from(dotsWrap.querySelectorAll('.vs-dot'));

  function pauseAll(){
    slides.forEach(s => {
      const v = s.querySelector('video');
      if (v) v.pause();
    });
  }

  function playActive(){
    const v = slides[idx]?.querySelector('video');
    if (v) {
      // safe play (some browsers block autoplay if not muted)
      v.play().catch(()=>{});
    }
  }

  function setActive(i){
    idx = (i + slides.length) % slides.length;
    slides.forEach((s, k) => s.classList.toggle('is-active', k === idx));
    dots.forEach((d, k) => d.classList.toggle('is-active', k === idx));

    pauseAll();
    playActive();
  }

  function next(){ setActive(idx + 1); }

  function start(){
    if (!autoplay) return;
    stop();
    timer = setInterval(next, interval);
  }

  function stop(){
    if (timer) clearInterval(timer);
    timer = null;
  }

  dotsWrap.addEventListener('click', (e) => {
    const b = e.target.closest('.vs-dot');
    if (!b) return;
    setActive(Number(b.dataset.i));
    start();
  });

  hero.addEventListener('mouseenter', stop);
  hero.addEventListener('mouseleave', start);

  // init
  setActive(0);
  start();
});
