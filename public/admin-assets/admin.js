// CSRF for fetch requests (if needed later)
const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Auto-hide toast
(function(){
  const toasts = document.querySelectorAll('.toast');
  toasts.forEach(t => {
    setTimeout(() => {
      t.style.transition = 'all .3s ease';
      t.style.opacity = '0';
      t.style.transform = 'translateY(-8px)';
      setTimeout(()=> t.remove(), 300);
    }, 3000);
  });

  // confirm delete buttons (data-confirm)
  document.addEventListener('click', function(e){
    const btn = e.target.closest('[data-confirm]');
    if(!btn) return;
    const msg = btn.getAttribute('data-confirm') || 'Are you sure?';
    if(!confirm(msg)) e.preventDefault();
  });
})();
