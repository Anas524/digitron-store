(function () {
    if (window.__productsInited) return;
    window.__productsInited = true;

    // Build config from DOM (Option C)
    const el = document.getElementById('adminProductsCfg');
    const cfg = {
        csrf: el?.dataset.csrf || '',
        storeUrl: el?.dataset.storeUrl || '',
    };

    // expose (optional, but fine)
    window.__ADMIN_PRODUCTS = cfg;

    // hard fail if missing
    if (!cfg.csrf || !cfg.storeUrl) {
        console.warn('Admin config missing: #adminProductsCfg not found or missing data-*');
        return;
    }

    const cardsWrap = document.getElementById('cardsWrap');
    const draftTpl = document.getElementById('draftCardTpl');
    const railList = document.getElementById('railList');
    const rightScroll = document.getElementById('adminRightScroll');

    function getPendingImages(card) {
        if (!card._pendingImages) card._pendingImages = [];
        return card._pendingImages;
    }

    function fileKey(file) {
        return [file.name, file.size, file.lastModified, file.type].join('__');
    }

    function setUploadHint(card) {
        const hint = card.querySelector('[data-upload-hint]');
        if (!hint) return;

        const pending = getPendingImages(card);
        hint.textContent = pending.length ? `${pending.length} file(s) selected` : 'No files selected';
    }

    function previewPendingImage(card) {
        const img = card.querySelector('img[data-img]');
        const pending = getPendingImages(card);
        if (!img || !pending.length) return;

        const url = URL.createObjectURL(pending[0]);

        if (img.dataset.previewUrl) {
            try { URL.revokeObjectURL(img.dataset.previewUrl); } catch { }
        }

        img.src = url;
        img.dataset.previewUrl = url;
    }

    const setScrollVar = () => {
        const y = rightScroll ? rightScroll.scrollTop : 0;
        document.documentElement.style.setProperty('--scrollY', `${y}px`);
    };

    rightScroll?.addEventListener('scroll', setScrollVar, { passive: true });
    setScrollVar();

    function getEffectiveScrollContainer() {
        const right = document.getElementById('adminRightScroll');

        if (right && right.scrollHeight > right.clientHeight + 20) {
            return right;
        }

        const adminMain = document.querySelector('.admin-main-scroll');
        if (adminMain && adminMain.scrollHeight > adminMain.clientHeight + 20) {
            return adminMain;
        }

        return document.scrollingElement || document.documentElement;
    }

    function scrollCardIntoView(card) {
        const scroller = getEffectiveScrollContainer();
        if (!card || !scroller) return;

        const offset = 24;

        // window/document scrolling
        if (
            scroller === document.scrollingElement ||
            scroller === document.documentElement ||
            scroller === document.body
        ) {
            const pageTop = window.pageYOffset || document.documentElement.scrollTop || 0;
            const cardTop = card.getBoundingClientRect().top + pageTop - offset;
            window.scrollTo({ top: cardTop, behavior: 'smooth' });
            return;
        }

        // inner container scrolling
        const scrollerRect = scroller.getBoundingClientRect();
        const cardRect = card.getBoundingClientRect();
        const top = (cardRect.top - scrollerRect.top) + scroller.scrollTop - offset;

        scroller.scrollTo({ top, behavior: 'smooth' });
    }

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.rail-item[data-jump], .rail-item-mobile[data-jump]');
        if (!btn) return;

        const targetId = btn.getAttribute('data-jump');
        const card = document.getElementById(targetId);
        if (!card) return;

        document.querySelectorAll('.rail-item, .rail-item-mobile').forEach(x =>
            x.classList.remove('ring-2', 'ring-brand-accent', 'outline', 'outline-2', 'outline-brand-accent', 'outline-offset-0')
        );

        btn.classList.add('outline', 'outline-2', 'outline-brand-accent', 'outline-offset-0');

        scrollCardIntoView(card);

        card.classList.add('outline', 'outline-2', 'outline-brand-accent', 'outline-offset-2');
        setTimeout(() => {
            card.classList.remove('outline', 'outline-2', 'outline-brand-accent', 'outline-offset-2');
        }, 900);
    });

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-upload-btn]');
        if (!btn) return;

        const card = btn.closest('.product-card');
        const input = card?.querySelector('input[data-images]');
        if (!input) return;

        input.value = '';
        input.click();
    });

    document.addEventListener('change', (e) => {
        const input = e.target.closest('input[data-images]');
        if (!input) return;

        const card = input.closest('.product-card');
        if (!card) return;

        const selected = Array.from(input.files || []);
        if (!selected.length) return;

        const pending = getPendingImages(card);
        const existingKeys = new Set(pending.map(fileKey));

        selected.forEach(file => {
            const key = fileKey(file);
            if (!existingKeys.has(key)) {
                pending.push(file);
                existingKeys.add(key);
            }
        });

        setUploadHint(card);
        previewPendingImage(card);
    });

    document.addEventListener('change', (e) => {
        const input = e.target.closest('input[data-video]');
        if (!input) return;

        const hasVideo = String(input.dataset.hasVideo || '0') === '1';

        if (hasVideo && input.files && input.files.length) {
            const ok = confirm('A video already exists for this product. Replace it? The old video will be deleted.');
            if (!ok) input.value = '';
        }
    });

    // rail search
    function bindRailSearch(inputId, listId, itemSelector) {
        const input = document.getElementById(inputId);
        const list = document.getElementById(listId);
        if (!input || !list) return;

        input.addEventListener('input', () => {
            const q = (input.value || '').trim().toLowerCase();
            list.querySelectorAll(itemSelector).forEach(it => {
                const name = it.getAttribute('data-name') || '';
                it.style.display = (!q || name.includes(q)) ? '' : 'none';
            });
        });
    }

    bindRailSearch('prodSearch', 'railList', '.rail-item');
    bindRailSearch('prodSearchMobile', 'railListMobile', '.rail-item-mobile');

    let addingDraft = false;

    const addDraft = async () => {
        if (addingDraft) return;
        addingDraft = true;

        const btn = document.getElementById('addProductBtn');
        if (btn) {
            btn.disabled = true;
            btn.dataset.originalHtml = btn.innerHTML;
            btn.innerHTML = `<span class="inline-flex items-center gap-2">
      <span class="h-4 w-4 rounded-full border-2 border-black/40 border-t-black animate-spin"></span>
      Adding...
    </span>`;
        }

        try {
            if (!draftTpl || !cardsWrap) return;

            const node = draftTpl.content.cloneNode(true);
            cardsWrap.prepend(node);

            const card = cardsWrap.querySelector('.product-card'); // new first
            if (!card) return;

            const draftId = `prod-draft-${Date.now()}-${Math.random().toString(16).slice(2)}`;
            card.id = draftId;

            toggleEdit(card, true);

            const right = document.getElementById('adminRightScroll');
            right?.scrollTo({ top: 0, behavior: 'smooth' });

        } finally {
            addingDraft = false;
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = btn.dataset.originalHtml || `<i class="bi bi-plus-circle"></i> Add Product`;
            }
        }
    };

    document.getElementById('addProductBtn')?.addEventListener('click', (e) => {
        e.preventDefault();
        addDraft();
    });

    document.getElementById('addProductBtnMobile')?.addEventListener('click', (e) => {
        e.preventDefault();
        addDraft();
    });

    document.getElementById('addProductBtnTop')?.addEventListener('click', addDraft);

    function toggleEdit(card, on) {
        const view = card.querySelector('[data-view-area]');
        const edits = card.querySelectorAll('[data-edit-area]');

        const btnEdit = card.querySelector('[data-edit]');
        const btnSave = card.querySelector('[data-save]');
        const btnCancel = card.querySelector('[data-cancel]');
        const btnDelete = card.querySelector('[data-delete]');

        if (on) {
            view?.classList.add('hidden');
            edits.forEach(e => e.classList.remove('hidden'));

            // show only Save + Cancel
            btnEdit?.classList.add('hidden');
            btnDelete?.classList.add('hidden');

            btnSave?.classList.remove('hidden');
            btnCancel?.classList.remove('hidden');

            card.dataset.editing = '1';

            card.querySelectorAll('[data-thumb-actions]').forEach(x => {
                x.classList.remove('hidden');
                x.classList.add('flex');
            });
        } else {
            view?.classList.remove('hidden');
            edits.forEach(e => e.classList.add('hidden'));

            // show only Edit + Delete
            btnSave?.classList.add('hidden');
            btnCancel?.classList.add('hidden');

            btnEdit?.classList.remove('hidden');
            btnDelete?.classList.remove('hidden');

            card.dataset.editing = '0';

            card.querySelectorAll('[data-thumb-actions]').forEach(x => {
                x.classList.add('hidden');
                x.classList.remove('flex');
            });
        }
    }

    // Force all existing cards to start in VIEW mode
    document.querySelectorAll('#cardsWrap .product-card').forEach(card => {
        if (card.getAttribute('data-is-draft') === '1') {
            toggleEdit(card, true);   // drafts start in edit mode
        } else {
            toggleEdit(card, false);  // normal products start in view mode
        }
    });

    async function saveCard(card) {
        if (!cfg.storeUrl || !cfg.csrf) {
            alert('Admin config missing. Check window.__ADMIN_PRODUCTS');
            return;
        }

        const isDraft = card.getAttribute('data-is-draft') === '1';
        const form = card.querySelector('form[data-form]');
        if (!form) return;
        clearCardError(card);

        const fd = new FormData(form);

        // remove any pre-filled images fields (strings/hidden inputs)
        fd.delete('images');
        fd.delete('images[]');

        const imagesInput = card.querySelector('input[data-images]');
        const pendingImages = getPendingImages(card);

        if (pendingImages.length) {
            pendingImages.forEach(f => fd.append('images[]', f));
        }

        let url = cfg.storeUrl;
        let method = 'POST';

        if (!isDraft) {
            url = card.getAttribute('data-update-url');
            // Laravel PUT via _method for FormData POST
            fd.append('_method', 'PUT');
            method = 'POST';
        }

        // If user selected a thumbnail while editing, make it primary on save
        const primaryCandidate = card.dataset.primaryCandidate;
        if (primaryCandidate && !isDraft) {
            fd.append('primary_image_id', primaryCandidate);
        }

        // basic required fields
        const name = (form.querySelector('input[name="name"]')?.value || '').trim();
        const price = (form.querySelector('input[name="price"]')?.value || '').trim();

        if (!name || !price) {
            showCardError(card, 'Please fill required fields: Name and Price.');
            if (!name) form.querySelector('input[name="name"]')?.focus();
            else form.querySelector('input[name="price"]')?.focus();
            return;
        }

        for (const [k, v] of fd.entries()) {
            console.log(k, v, v instanceof File ? v.type : '(not file)');
        }

        const res = await fetch(url, {
            method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': cfg.csrf,
                'Accept': 'application/json',
            },
            body: fd
        });

        if (!res.ok) {
            if (res.status === 422) {
                const err = await res.json();
                const msg = err?.errors
                    ? Object.values(err.errors).flat().join('\n')
                    : (err?.message || 'Validation error.');
                showCardError(card, msg);
                return;
            }

            // For 500 / other errors: show generic to user, log details to console
            const txt = await res.text();
            console.error('Save failed:', res.status, txt);
            showCardError(card, 'Could not save. Please try again.');
            return;
        }

        const json = await res.json();
        if (!json.ok) {
            alert('Save failed.');
            return;
        }

        applyPayload(card, json.product);

        card.dataset.primaryCandidate = '';

        // clear selected files after successful save
        const hint = card.querySelector('[data-upload-hint]');
        if (imagesInput) imagesInput.value = '';
        card._pendingImages = [];
        if (hint) hint.textContent = 'No files selected';

        const mainImg = card.querySelector('img[data-img]');
        if (mainImg?.dataset.previewUrl) {
            try { URL.revokeObjectURL(mainImg.dataset.previewUrl); } catch { }
            delete mainImg.dataset.previewUrl;
        }

        showCardSuccess(card, 'Saved successfully.');

        // after saving draft, convert to normal card
        if (isDraft) {
            card.dataset.isDraft = '0';
        }

        toggleEdit(card, false);
    }

    function normalizeImages(images) {
        // Already array
        if (Array.isArray(images)) return images;

        // Laravel might send as object {0:...,1:...}
        if (images && typeof images === 'object') {
            // If it has "data" (pagination/resource), use that
            if (Array.isArray(images.data)) return images.data;

            // Convert object values -> array
            return Object.values(images);
        }

        return [];
    }

    function rebuildThumbs(card, product) {
        const wrap = card.querySelector('[data-gallery-thumbs]');
        if (!wrap) return;

        const images = normalizeImages(product?.images);

        const seen = new Set();
        const unique = [];

        images.forEach((img, idx) => {
            const url = typeof img === 'string' ? img : (img.url || '');
            const id = typeof img === 'string' ? null : (img.id ?? null);

            const key = id ? `id:${id}` : `url:${url}`;
            if (!url || seen.has(key)) return;

            seen.add(key);
            unique.push(img);
        });

        wrap.innerHTML = '';

        unique.forEach((img, idx) => {
            const url = typeof img === 'string' ? img : (img.url || '');
            const id = typeof img === 'string' ? null : (img.id ?? null);
            const isPrimary = typeof img === 'string' ? (idx === 0) : !!img.is_primary;

            if (!url) return;

            // star button:
            // - primary => show yellow star (no click)
            // - non-primary => show "set main" button (clickable)
            const starHtml = isPrimary
                ? `
                <span class="w-6 h-6 rounded-full bg-black/70 border border-white/10 flex items-center justify-center"
                    title="Main image">
                <i class="bi bi-star-fill text-yellow-400 text-[12px]"></i>
                </span>
            `
                : (id ? `
                <button type="button"
                class="w-6 h-6 rounded-full bg-black/70 border border-white/10 flex items-center justify-center"
                title="Set Main" data-set-primary data-image-id="${id}">
                <i class="bi bi-star text-white text-[12px]"></i>
                </button>
            ` : '');

            const delHtml = id ? `
            <button type="button"
                class="w-6 h-6 rounded-full bg-red-500/70 border border-white/10 text-white text-[10px] flex items-center justify-center"
                title="Delete" data-del-image data-image-id="${id}">
                <i class="bi bi-x-lg"></i>
            </button>
            ` : '';

            const outer = document.createElement('div');
            outer.className = 'relative w-14 h-14 shrink-0';
            outer.setAttribute('data-thumb', '');
            if (id) outer.dataset.imageId = id;

            outer.innerHTML = `
            <button type="button"
                class="w-14 h-14 rounded-xl overflow-hidden border-2 ${isPrimary ? 'border-brand-accent' : 'border-white/10'} hover:border-brand-accent transition"
                data-gallery-thumb
                data-img="${url}"
                ${id ? `data-image-id="${id}"` : ''}>
                <img src="${url}" class="w-full h-full object-cover" />
            </button>

            <div class="absolute -top-2 -right-2 hidden gap-1" data-thumb-actions>
                ${starHtml}
                ${delHtml}
            </div>
            `;

            wrap.appendChild(outer);
        });

        // keep actions visible only in edit mode
        if (card.dataset.editing === '1') {
            card.querySelectorAll('[data-thumb-actions]').forEach(x => {
                x.classList.remove('hidden');
                x.classList.add('flex');
            });
        } else {
            card.querySelectorAll('[data-thumb-actions]').forEach(x => {
                x.classList.add('hidden');
                x.classList.remove('flex');
            });
        }

        // always sync main preview with backend primary
        if (product?.primary_image_url) {
            const main = card.querySelector('img[data-img]');
            if (main) main.src = product.primary_image_url;
        }
    }

    function applyPayload(card, p) {
        card.dataset.productId = p.id || '';
        if (p.id) {
            card.id = `prod-${p.id}`;
            card.setAttribute('data-update-url', `/admin/products/${p.id}`);
            card.setAttribute('data-delete-url', `/admin/products/${p.id}`);
        }

        // image + counts
        const img = card.querySelector('[data-img]');
        if (img && p.primary_image_url) img.src = p.primary_image_url;

        const countEl = card.querySelector('[data-images-count]');
        if (countEl) countEl.textContent = p.images_count ?? 0;

        const vidInput = card.querySelector('input[data-video]');
        if (vidInput) vidInput.dataset.hasVideo = (p.has_video ? '1' : '0');

        // status pill
        const status = card.querySelector('[data-status-pill]');
        if (status) {
            status.textContent = p.is_active ? 'Active' : 'Draft';
            status.classList.toggle('text-emerald-300', !!p.is_active);
            status.classList.toggle('text-yellow-300', !p.is_active);
        }

        // view text fields
        const setView = (k, val) => {
            const el = card.querySelector(`[data-view="${k}"]`);
            if (el) el.textContent = (val === null || val === '' || typeof val === 'undefined') ? '—' : val;
        };

        card.querySelector('[data-view-name]') && (card.querySelector('[data-view-name]').textContent = p.name || '—');
        card.querySelector('[data-view-brand]') && (card.querySelector('[data-view-brand]').textContent = p.brand || '—');
        card.querySelector('[data-view-sku]') && (card.querySelector('[data-view-sku]').textContent = p.sku || '—');
        card.querySelector('[data-view-condition]') && (card.querySelector('[data-view-condition]').textContent = (p.condition ? p.condition[0].toUpperCase() + p.condition.slice(1) : '—'));

        const priceEl = card.querySelector('[data-view-price]');
        if (priceEl) priceEl.textContent = p.price ? `AED ${Number(p.price).toFixed(2)}` : 'AED —';

        const stockEl = card.querySelector('[data-view-stock]');
        if (stockEl) stockEl.textContent = p.stock_qty ?? 0;

        setView('badge_text', p.badge_text);
        setView('rating', p.rating);
        setView('rating_count', p.rating_count);
        setView('delivery_text', p.delivery_text);
        setView('discount_percent', p.discount_percent);
        setView('category_id', p.category_name || '—');
        setView('product_type', p.product_type_label || '—');

        const sd = card.querySelector('[data-view-short_description]');
        if (sd) sd.textContent = p.short_description || '—';

        const desc = card.querySelector('[data-view-description]');
        if (desc) desc.textContent = p.description || '—';

        rebuildThumbs(card, p);

        // reset gallery index for this card
        card.dataset.gIndex = '0';
    }

    async function deleteCard(card) {
        if (!cfg.storeUrl || !cfg.csrf) {
            alert('Admin config missing. Check window.__ADMIN_PRODUCTS');
            return;
        }

        const isDraft = card.getAttribute('data-is-draft') === '1';
        if (isDraft) {
            card.remove();
            return;
        }

        if (!confirm('Delete this product?')) return;

        const url = card.getAttribute('data-delete-url');
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': cfg.csrf,
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ _method: 'DELETE' })
        });

        if (!res.ok) {
            alert('Delete failed.');
            return;
        }

        card.remove();
        reindexSno();
    }

    // card actions
    document.addEventListener('click', (e) => {
        const card = e.target.closest('.product-card');
        if (!card) return;

        if (e.target.closest('[data-edit]')) toggleEdit(card, true);
        if (e.target.closest('[data-cancel]')) {
            // if draft and empty -> remove
            if (card.getAttribute('data-is-draft') === '1') {
                const name = card.querySelector('input[name="name"]')?.value?.trim();
                if (!name) return card.remove();
            }
            toggleEdit(card, false);
        }
        if (e.target.closest('[data-save]')) saveCard(card);
        if (e.target.closest('[data-delete]')) deleteCard(card);
    });

    // Delete + Set primary (one handler)
    document.addEventListener('click', async (e) => {
        const delBtn = e.target.closest('[data-del-image]');
        const primBtn = e.target.closest('[data-set-primary]');
        if (!delBtn && !primBtn) return;

        const card = e.target.closest('.product-card');
        if (!card) return;

        // lock per card to prevent double fire
        if (card.dataset.imgBusy === '1') return;
        card.dataset.imgBusy = '1';

        try {
            if (delBtn) {
                const imageId = delBtn.getAttribute('data-image-id');
                if (!imageId || !confirm('Delete this image?')) return;

                const json = await deleteThumb(imageId);
                if (!json?.ok) return alert(json?.message || 'Delete failed');

                applyPayload(card, json.product);
                return;
            }

            if (primBtn) {
                const imageId = primBtn.getAttribute('data-image-id');
                if (!imageId) return;

                const json = await setPrimaryThumb(imageId);
                if (!json?.ok) return alert(json?.message || 'Failed');

                applyPayload(card, json.product);
            }
        } finally {
            card.dataset.imgBusy = '0';
        }
    });

    (function wheelRoute() {
        function nearestScrollable(el) {
            while (el && el !== document.body) {
                const st = getComputedStyle(el);
                const canScrollY = /(auto|scroll)/.test(st.overflowY);
                if (canScrollY && el.scrollHeight > el.clientHeight + 1) return el;
                el = el.parentElement;
            }
            return null;
        }

        document.addEventListener(
            "wheel",
            (e) => {
                // allow pinch zoom
                if (e.ctrlKey) return;

                const fallback = document.getElementById("adminRightScroll");
                const scroller = nearestScrollable(e.target);
                const target = scroller || fallback;
                if (!target) return;

                // IMPORTANT: kill other wheel handlers (Lenis / overlays)
                e.preventDefault();
                // e.stopImmediatePropagation();

                target.scrollTop += e.deltaY;
            },
            { passive: false, capture: true } // capture makes it run before Lenis
        );
    })();

    function reindexSno() {
        const cards = document.querySelectorAll('#cardsWrap .product-card');
        cards.forEach((card, idx) => {
            const snoEl = card.querySelector('[data-sno]');
            if (snoEl) snoEl.textContent = (idx + 1);
        });

        // also reindex left rail "S.NO"
        document.querySelectorAll('#railList .rail-item').forEach((btn, idx) => {
            const sno = btn.querySelector('[data-rail-sno]');
            if (sno) sno.textContent = `S.NO ${idx + 1}`;
        });
    }

    function showCardError(card, msg) {
        const box = card.querySelector('[data-error-box]');
        if (!box) return;
        box.textContent = msg;
        box.classList.remove('hidden');
    }

    function clearCardError(card) {
        const box = card.querySelector('[data-error-box]');
        if (!box) return;
        box.textContent = '';
        box.classList.add('hidden');
    }

    function showCardSuccess(card, msg) {
        const box = card.querySelector('[data-success-box]');
        if (!box) return;
        box.textContent = msg;
        box.classList.remove('hidden');
        setTimeout(() => box.classList.add('hidden'), 1800);
    }

    document.addEventListener('input', (e) => {
        const card = e.target.closest('.product-card');
        if (!card) return;
        if (e.target.matches('input, textarea, select')) clearCardError(card);
    });

    (function initAdminProductCardGallery() {
        if (!document.querySelector('.product-card')) return;

        function setActive(card, i) {
            const thumbs = Array.from(card.querySelectorAll('[data-gallery-thumb]'));
            if (!thumbs.length) return;

            const t = thumbs[i];
            if (!t) return;

            const img = t.getAttribute('data-img');
            if (img) {
                const main = card.querySelector('[data-img]');
                if (main) main.src = img;
            }

            thumbs.forEach(x => {
                x.classList.remove('border-brand-accent');
                x.classList.add('border-white/10');
            });

            t.classList.remove('border-white/10');
            t.classList.add('border-brand-accent');

            card.dataset.gIndex = String(i);

            // if in edit mode, remember the selected image id as "make this primary on save"
            if (card.dataset.editing === '1') {
                const selectedId = t.getAttribute('data-image-id');
                if (selectedId) card.dataset.primaryCandidate = selectedId;
            }
        }

        // init
        document.querySelectorAll('.product-card').forEach(card => {
            card.dataset.gIndex = '0';
        });

        // thumb click
        document.addEventListener('click', (e) => {
            const thumb = e.target.closest('[data-gallery-thumb]');
            if (!thumb) return;

            const card = thumb.closest('.product-card');
            if (!card) return;

            const thumbs = Array.from(card.querySelectorAll('[data-gallery-thumb]'));
            setActive(card, thumbs.indexOf(thumb));
        });

        // prev
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-gallery-prev]');
            if (!btn) return;

            const card = btn.closest('.product-card');
            if (!card) return;

            const thumbs = Array.from(card.querySelectorAll('[data-gallery-thumb]'));
            if (!thumbs.length) return;

            const cur = Number(card.dataset.gIndex || 0);
            const next = (cur - 1 + thumbs.length) % thumbs.length;
            setActive(card, next);
        });

        // next
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-gallery-next]');
            if (!btn) return;

            const card = btn.closest('.product-card');
            if (!card) return;

            const thumbs = Array.from(card.querySelectorAll('[data-gallery-thumb]'));
            if (!thumbs.length) return;

            const cur = Number(card.dataset.gIndex || 0);
            const next = (cur + 1) % thumbs.length;
            setActive(card, next);
        });
    })();

    async function deleteThumb(imageId) {
        const res = await fetch(`/admin/product-images/${imageId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': cfg.csrf,
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ _method: 'DELETE' })
        });

        return res.json();
    }

    async function setPrimaryThumb(imageId) {
        const res = await fetch(`/admin/product-images/${imageId}/primary`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': cfg.csrf,
            }
        });

        return res.json();
    }

    document.addEventListener('click', async (e) => {
        const item = e.target.closest('.notification-item[data-quote-id]');
        if (!item) return;

        const quoteId = item.getAttribute('data-quote-id');
        if (!quoteId) return;

        try {
            await fetch(`/admin/quotes/${quoteId}/mark-seen`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });
        } catch (err) {
            // ignore silently
            console.debug('quote notification mark-seen failed', err);
        }
    });

})();
