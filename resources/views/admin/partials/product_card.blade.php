@php
$id = $p?->id;
$isDraft = empty($id);

$anchor = $isDraft ? ('prod-draft-' . uniqid()) : ('prod-' . $id);

// unique key for DOM ids (checkbox/labels) so drafts don’t clash
$domKey = $isDraft ? ('draft-' . uniqid()) : $id;

$placeholder = asset('images/placeholder-product.png');

$data = $isDraft ? [
'id' => null,
'name' => '',
'brand' => '',
'sku' => '',
'condition' => 'new',
'product_type' => 'component',
'category_id' => '',
'price' => '',
'compare_at_price' => '',
'stock_qty' => 0,
'is_active' => 1,
'badge_text' => '',
'rating' => '',
'rating_count' => '',
'delivery_text' => '',
'discount_percent' => '',
'short_description' => '',
'description' => '',
'images_count' => 0,
'primary_image_url' => $placeholder,
] : [
'id' => $p->id,
'name' => $p->name,
'brand' => $p->brand,
'sku' => $p->sku,
'condition' => $p->condition ?? 'new',
'product_type' => $p->product_type ?? 'component',
'category_id' => $p->category_id,
'price' => $p->price,
'compare_at_price' => $p->compare_at_price,
'stock_qty' => $p->stock_qty ?? 0,
'is_active' => $p->is_active ? 1 : 0,
'badge_text' => $p->badge_text,
'rating' => $p->rating,
'rating_count' => $p->rating_count,
'delivery_text' => $p->delivery_text,
'discount_percent' => $p->discount_percent,
'short_description' => $p->short_description,
'description' => $p->description,
'images_count' => $p->images_count ?? 0,
'primary_image_url' => $p->primary_image_url,
];

$updateUrl = $isDraft ? '' : route('admin.products.update', $p);
$deleteUrl = $isDraft ? '' : route('admin.products.destroy', $p);
@endphp

<article id="{{ $anchor }}"
    class="product-card relative rounded-2xl border border-white/10 bg-white/5 overflow-hidden"
    data-product-id="{{ $data['id'] ?? '' }}"
    data-update-url="{{ $updateUrl }}"
    data-delete-url="{{ $deleteUrl }}"
    data-is-draft="{{ $isDraft ? 1 : 0 }}">
    <div class="absolute inset-0 pointer-events-none opacity-[0.06] bg-grid-pattern"></div>

    <div class="p-5">
        <div class="hidden mb-3 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200"
            data-error-box>
        </div>
        {{-- header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
                <div class="text-xs px-2 py-1 rounded-full bg-white/10 border border-white/10 text-gray-300">
                    S.NO <span data-sno>{{ $sno }}</span>
                </div>

                <div class="text-sm text-gray-400">
                    <span class="mr-2">Images: <span class="text-gray-200 font-semibold" data-images-count>{{ $data['images_count'] }}</span></span>
                    <span>•</span>
                    <span class="ml-2">Status:
                        <span class="font-semibold {{ $data['is_active'] ? 'text-emerald-300' : 'text-yellow-300' }}" data-status-pill>
                            {{ $data['is_active'] ? 'Active' : 'Draft' }}
                        </span>
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @php
                $showEditDelete = !$isDraft;
                $showSaveCancel = $isDraft;
                @endphp

                {{-- Edit --}}
                <button type="button"
                    class="relative group icon-btn inline-flex items-center justify-center px-3 py-2 rounded-xl bg-white/10 hover:bg-white/20 {{ $showEditDelete ? '' : 'hidden' }}"
                    data-edit>
                    <i class="bi bi-pencil-square"></i>
                    <span class="pointer-events-none absolute left-1/2 -translate-x-1/2 top-full mt-2
      whitespace-nowrap rounded-lg bg-black/70 backdrop-blur px-2 py-1 text-xs text-white
      opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 transition">
                        Edit
                    </span>
                </button>

                {{-- Save --}}
                <button type="button"
                    class="relative group icon-btn inline-flex items-center justify-center px-3 py-2 rounded-xl bg-emerald-500/15 border border-emerald-500/30
           text-emerald-200 hover:bg-emerald-500/25 {{ $showSaveCancel ? '' : 'hidden' }}"
                    data-save>
                    <i class="bi bi-check2-circle"></i>
                    <span class="pointer-events-none absolute left-1/2 -translate-x-1/2 top-full mt-2
      whitespace-nowrap rounded-lg bg-black/70 backdrop-blur px-2 py-1 text-xs text-white
      opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 transition">
                        Save
                    </span>
                </button>

                {{-- Cancel --}}
                <button type="button"
                    class="relative group icon-btn inline-flex items-center justify-center px-3 py-2 rounded-xl bg-white/10 hover:bg-white/20 {{ $showSaveCancel ? '' : 'hidden' }}"
                    data-cancel>
                    <i class="bi bi-x-circle"></i>
                    <span class="pointer-events-none absolute left-1/2 -translate-x-1/2 top-full mt-2
      whitespace-nowrap rounded-lg bg-black/70 backdrop-blur px-2 py-1 text-xs text-white
      opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 transition">
                        Cancel
                    </span>
                </button>

                {{-- Delete --}}
                <button type="button"
                    class="relative group icon-btn inline-flex items-center justify-center px-3 py-2 rounded-xl bg-red-500/15 border border-red-500/30
           text-red-200 hover:bg-red-500/25 {{ $showEditDelete ? '' : 'hidden' }}"
                    data-delete>
                    <i class="bi bi-trash3"></i>
                    <span class="pointer-events-none absolute left-1/2 -translate-x-1/2 top-full mt-2
      whitespace-nowrap rounded-lg bg-black/70 backdrop-blur px-2 py-1 text-xs text-white
      opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 transition">
                        Delete
                    </span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            {{-- image --}}
            <div class="lg:col-span-3">
                <div class="relative rounded-2xl border border-white/10 bg-black/20 overflow-hidden aspect-square group" data-gallery>
                    <img data-img
                        src="{{ $data['primary_image_url'] ?? $placeholder }}"
                        class="w-full h-full object-cover"
                        onerror="this.onerror=null;this.src='{{ $placeholder }}';"
                        alt="">

                    {{-- Prev --}}
                    <button type="button"
                        class="absolute left-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 backdrop-blur-sm
               border border-white/10 flex items-center justify-center text-white hover:bg-brand-accent hover:text-black
               transition-all opacity-0 group-hover:opacity-100"
                        data-gallery-prev>
                        <i class="bi bi-chevron-left"></i>
                    </button>

                    {{-- Next --}}
                    <button type="button"
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 backdrop-blur-sm
               border border-white/10 flex items-center justify-center text-white hover:bg-brand-accent hover:text-black
               transition-all opacity-0 group-hover:opacity-100"
                        data-gallery-next>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                <div class="mt-3 hidden" data-edit-area>
                    <label class="text-xs text-gray-400">Add images</label>
                    <input type="file" multiple accept="image/*" data-images class="hidden">

                    <button type="button" data-upload-btn
                        class="mt-2 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl
         bg-white/10 border border-white/10 hover:bg-white/15 text-gray-200">
                        <i class="bi bi-cloud-arrow-up"></i>
                        Upload Images
                    </button>

                    <div class="text-[11px] text-gray-500 mt-1" data-upload-hint>
                        No files selected
                    </div>
                    <div class="text-[11px] text-gray-500 mt-1">First new image becomes primary only if none exists.</div>
                </div>

                <div class="flex gap-2 mt-4 overflow-x-auto overflow-y-visible pr-2 pb-2 pt-3 slim-scroll thumb-strip" data-gallery-thumbs>
                    @if(!$isDraft)
                    @foreach($p->images as $k => $im)
                    @php $url = asset('storage/' . ltrim($im->image_path, '/')); @endphp

                    <div class="relative w-14 h-14 shrink-0 overflow-visible pr-16" data-thumb data-image-id="{{ $im->id }}">
                        <button type="button"
                            class="w-14 h-14 rounded-xl overflow-hidden border-2 {{ $im->is_primary ? 'border-brand-accent' : 'border-white/10' }} hover:border-brand-accent transition"
                            data-gallery-thumb
                            data-img="{{ $url }}"
                            data-image-id="{{ $im->id }}">
                            <img src="{{ $url }}" class="w-full h-full object-cover">
                        </button>

                        <div class="absolute -top-2 -right-2 hidden gap-1" data-thumb-actions>
                            @if($im->is_primary)
                            <span class="w-6 h-6 rounded-full bg-black/70 border border-white/10 flex items-center justify-center" title="Main image">
                                <i class="bi bi-star-fill text-yellow-400 text-[12px]"></i>
                            </span>
                            @else
                            <button type="button"
                                class="w-6 h-6 rounded-full bg-black/70 border border-white/10 flex items-center justify-center"
                                title="Set Main"
                                data-set-primary
                                data-image-id="{{ $im->id }}">
                                <i class="bi bi-star text-white text-[12px]"></i>
                            </button>
                            @endif

                            <button type="button"
                                class="w-6 h-6 rounded-full bg-red-500/70 border border-white/10 text-white text-[10px] flex items-center justify-center"
                                title="Delete"
                                data-del-image
                                data-image-id="{{ $im->id }}">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>

            </div>

            {{-- details --}}
            <div class="lg:col-span-9">
                {{-- VIEW MODE --}}
                <div data-view-area>
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-2xl font-display font-bold leading-tight" data-view-name>{{ $data['name'] ?: 'New Product' }}</div>
                            <div class="text-sm text-gray-400">
                                <span data-view-brand>{{ $data['brand'] ?: '—' }}</span> •
                                <span data-view-sku>{{ $data['sku'] ?: '—' }}</span> •
                                <span class="text-gray-300 font-semibold" data-view-condition>{{ ucfirst($data['condition'] ?? '—') }}</span> •
                                <span class="text-gray-300" data-view-product_type>{{ ($data['product_type'] ?? 'component') === 'custom_pc' ? 'Custom PC' : 'Component' }}</span>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-xl font-bold text-brand-accent" data-view-price>
                                AED {{ is_numeric($data['price']) ? number_format($data['price'],2) : ($data['price'] ?: '—') }}
                            </div>
                            @if($data['compare_at_price'])
                            <div class="text-sm text-gray-500 line-through" data-view-compare>
                                AED {{ is_numeric($data['compare_at_price']) ? number_format($data['compare_at_price'],2) : $data['compare_at_price'] }}
                            </div>
                            @endif
                            <div class="text-xs text-gray-400 mt-1">Stock: <span class="text-gray-200 font-semibold" data-view-stock>{{ $data['stock_qty'] }}</span></div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach([
                        ['Badge', 'badge_text'],
                        ['Rating', 'rating'],
                        ['Rating Count', 'rating_count'],
                        ['Delivery', 'delivery_text'],
                        ['Discount %', 'discount_percent'],
                        ['Category ID', 'category_id'],
                        ] as $field)
                        <div class="rounded-xl border border-white/10 bg-white/[0.03] p-3">
                            <div class="text-xs text-gray-500">{{ $field[0] }}</div>
                            <div class="text-sm font-semibold text-gray-200" data-view="{{ $field[1] }}">{{ $data[$field[1]] ?: '—' }}</div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4 rounded-xl border border-white/10 bg-white/[0.03] p-4">
                        <div class="text-xs text-gray-500 mb-1">Short Description</div>
                        <div class="text-sm text-gray-200 whitespace-pre-line" data-view-short_description>{{ $data['short_description'] ?: '—' }}</div>
                    </div>

                    <div class="mt-3 rounded-xl border border-white/10 bg-white/[0.03] p-4">
                        <div class="text-xs text-gray-500 mb-1">Description</div>
                        <div class="text-sm text-gray-200 whitespace-pre-line" data-view-description>{{ $data['description'] ?: '—' }}</div>
                    </div>
                </div>

                {{-- EDIT MODE --}}
                <div class="hidden" data-edit-area>
                    <form data-form>
                        @csrf
                        @if(!$isDraft)
                        @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="text-xs text-gray-400">Name *</label>
                                <input name="name" value="{{ $data['name'] }}"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Brand</label>
                                <input name="brand" value="{{ $data['brand'] }}"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">SKU</label>
                                <input name="sku" value="{{ $data['sku'] }}"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Condition</label>
                                <select name="condition"
                                    class="mt-1 w-full rounded-xl bg-[#0b1220] border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                                    @foreach(['new'=>'New','used'=>'Used','refurbished'=>'Refurbished'] as $k=>$v)
                                    <option value="{{ $k }}" @selected(($data['condition'] ?? 'new' )===$k)>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Category</label>
                                <select name="category_id"
                                    class="mt-1 w-full rounded-xl bg-[#0b1220] border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                                    <option value="">— None —</option>

                                    @foreach(($categories ?? []) as $c)
                                    <option value="{{ $c->id }}" @selected((string)($data['category_id'] ?? '' )===(string)$c->id)>
                                        {{ $c->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Badge</label>
                                <input name="badge_text" value="{{ $data['badge_text'] }}"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>
                        </div>

                        <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="text-xs text-gray-400">Price (AED) *</label>
                                <input type="number" step="0.01" name="price" value="{{ $data['price'] }}"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Compare at</label>
                                <input type="number" step="0.01" name="compare_at_price" value="{{ $data['compare_at_price'] }}"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Stock</label>
                                <input type="number" name="stock_qty" value="{{ $data['stock_qty'] }}"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Rating *</label>
                                <input type="number" step="0.1" name="rating" value="{{ $data['rating'] }}"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Rating count</label>
                                <input type="number" name="rating_count" value="{{ $data['rating_count'] }}"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Discount %</label>
                                <input type="number" step="0.01" name="discount_percent" value="{{ $data['discount_percent'] }}"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs text-gray-400">Delivery text</label>
                                <input name="delivery_text" value="{{ $data['delivery_text'] }}"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">
                            </div>

                            <div class="flex items-center gap-2 mt-6 md:mt-0">
                                <input id="active-{{ $domKey }}" type="checkbox" name="is_active" value="1" @checked($data['is_active'])
                                    class="w-4 h-4">

                                <label for="active-{{ $domKey }}" class="text-sm text-gray-300">Active</label>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="text-xs text-gray-400">Short Description</label>
                            <textarea name="short_description" rows="2"
                                class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">{{ $data['short_description'] }}</textarea>
                        </div>

                        <div class="mt-3">
                            <label class="text-xs text-gray-400">Description</label>
                            <textarea name="description" rows="4"
                                class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5 outline-none focus:border-brand-accent">{{ $data['description'] }}</textarea>
                        </div>

                        {{-- Video upload --}}
                        <div class="mt-4">
                            <div class="text-sm text-white/70 mb-2">Product Video (optional)</div>

                            @if(!$isDraft && $p->video_path)
                            <div class="text-xs text-emerald-300 mb-2">
                                Video uploaded
                            </div>
                            @endif

                            <input type="file"
                                name="video"
                                accept="video/mp4,video/webm,video/quicktime"
                                data-video
                                data-has-video="{{ (!$isDraft && $p->video_path) ? 1 : 0 }}"
                                class="block w-full text-sm text-white/80 file:mr-3 file:px-4 file:py-2 file:rounded-xl file:border-0 file:bg-white/10 file:text-white hover:file:bg-white/15">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</article>