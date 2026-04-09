<form id="shopFiltersForm" method="GET" action="{{ route('shop') }}" class="space-y-4">
    {{-- keep category in request always --}}
    <input type="hidden" name="category" value="{{ $activeCategory ?? 'all' }}">

    {{-- keep sort --}}
    <input type="hidden" name="sort" id="sortInput" value="{{ request('sort','featured') }}">

    {{-- Active Filters --}}
    @php
    $activeConds = array_values(array_filter((array) request('condition', [])));
    $activeBrands = array_values(array_filter((array) request('brand', [])));
    $activeRating = request('rating');
    $minP = request('min_price');
    $maxP = request('max_price');

    $hasActiveFilters =
    count($activeConds) ||
    count($activeBrands) ||
    ($activeRating !== null && $activeRating !== '') ||
    (($minP !== null && $minP !== '') || ($maxP !== null && $maxP !== ''));
    @endphp

    <div class="glass-panel rounded-2xl border border-white/10 {{ $hasActiveFilters ? 'p-5' : 'p-4' }}">
        <div class="flex items-center justify-between {{ $hasActiveFilters ? 'mb-4' : '' }}">
            <div class="text-sm font-bold text-white flex items-center gap-2">
                <i class="bi bi-sliders"></i> Filters
            </div>

            @if($hasActiveFilters)
            <a href="{{ route('shop') }}"
                class="text-xs text-brand-accent hover:text-white transition-colors">Clear All</a>
            @endif
        </div>

        @if($hasActiveFilters)
        <div class="flex flex-wrap gap-2">
            {{-- Condition tags --}}
            @foreach($activeConds as $c)
            <a href="{{ route('shop', array_merge(request()->except('page'), [
                                        'condition' => array_values(array_diff($activeConds, [$c]))
                                    ])) }}"
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-brand-accent/20 text-brand-accent text-xs border border-brand-accent/30">
                {{ ucfirst($c) }} <i class="bi bi-x-circle-fill"></i>
            </a>
            @endforeach

            {{-- Brand tags --}}
            @foreach($activeBrands as $b)
            <a href="{{ route('shop', array_merge(request()->except('page'), [
                                        'brand' => array_values(array_diff($activeBrands, [$b]))
                                    ])) }}"
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-brand-accent/20 text-brand-accent text-xs border border-brand-accent/30">
                {{ $b }} <i class="bi bi-x-circle-fill"></i>
            </a>
            @endforeach

            {{-- Rating tag --}}
            @if($activeRating !== null && $activeRating !== '')
            <a href="{{ route('shop', request()->except('page','rating')) }}"
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-brand-accent/20 text-brand-accent text-xs border border-brand-accent/30">
                {{ $activeRating }}★ & Up <i class="bi bi-x-circle-fill"></i>
            </a>
            @endif

            {{-- Price tag --}}
            @if(($minP !== null && $minP !== '') || ($maxP !== null && $maxP !== ''))
            <a href="{{ route('shop', request()->except('page','min_price','max_price')) }}"
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-brand-accent/20 text-brand-accent text-xs border border-brand-accent/30">
                Price <i class="bi bi-x-circle-fill"></i>
            </a>
            @endif
        </div>
        @else
        <div class="text-xs text-gray-500">No filters applied</div>
        @endif
    </div>

    {{-- Condition Filter --}}
    <div class="glass-panel rounded-2xl border border-white/10 p-5 filter-section">
        <button type="button" class="flex items-center justify-between w-full text-sm font-bold text-white mb-4 group">
            <span>Condition</span>
            <i class="bi bi-chevron-down transition-transform group-hover:text-brand-accent"></i>
        </button>
        <div class="space-y-3">
            @php $selectedConditions = (array) request('condition', []); @endphp

            @foreach($condFacet as $condition => $count)
            @php $val = strtolower($condition); @endphp
            <label class="flex items-center justify-between group cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <input name="condition[]" value="{{ $val }}" type="checkbox"
                            class="peer sr-only"
                            {{ in_array($val, $selectedConditions) ? 'checked' : '' }}
                            onchange="document.getElementById('shopFiltersForm').submit()">
                        <div class="w-5 h-5 rounded border-2 border-white/20 peer-checked:bg-brand-accent peer-checked:border-brand-accent transition-all"></div>
                        <i class="bi bi-check-lg absolute top-0.5 left-0.5 text-black text-xs opacity-0 peer-checked:opacity-100"></i>
                    </div>
                    <span class="text-sm text-gray-300 group-hover:text-white transition-colors">{{ ucfirst($val) }}</span>
                </div>
                <span class="text-xs text-gray-500">{{ $count }}</span>
            </label>
            @endforeach
        </div>
    </div>

    {{-- Brand Filter --}}
    <div class="glass-panel rounded-2xl border border-white/10 p-5 filter-section">
        <button type="button" class="flex items-center justify-between w-full text-sm font-bold text-white mb-4 group">
            <span>Brand</span>
            <i class="bi bi-chevron-down transition-transform group-hover:text-brand-accent"></i>
        </button>
        <div class="space-y-3 max-h-48 overflow-y-auto custom-scrollbar">
            @php $selectedBrands = (array) request('brand', []); @endphp

            @foreach($brandFacet as $brand => $count)
            <label class="flex items-center justify-between group cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <input name="brand[]" value="{{ $brand }}" type="checkbox"
                            class="peer sr-only"
                            {{ in_array($brand, $selectedBrands) ? 'checked' : '' }}
                            onchange="document.getElementById('shopFiltersForm').submit()">
                        <div class="w-5 h-5 rounded border-2 border-white/20 peer-checked:bg-brand-accent peer-checked:border-brand-accent transition-all"></div>
                        <i class="bi bi-check-lg absolute top-0.5 left-0.5 text-black text-xs opacity-0 peer-checked:opacity-100"></i>
                    </div>
                    <span class="text-sm text-gray-300 group-hover:text-white transition-colors">{{ $brand }}</span>
                </div>
                <span class="text-xs text-gray-500">{{ $count }}</span>
            </label>
            @endforeach
        </div>
    </div>

    {{-- Price Range --}}
    @php
    $minBound = (int)floor($priceMinMax->minp ?? 0);
    $maxBound = (int)ceil($priceMinMax->maxp ?? 0);

    // start from 0 (as you requested)
    $minBound = 0;

    $minVal = (int) request('min_price', $minBound);
    $maxVal = (int) request('max_price', $maxBound);

    // safety clamp
    $minVal = max($minBound, min($minVal, $maxBound));
    $maxVal = max($minBound, min($maxVal, $maxBound));
    if($minVal > $maxVal){ [$minVal, $maxVal] = [$maxVal, $minVal]; }
    @endphp

    <div class="glass-panel rounded-2xl border border-white/10 p-5 filter-section">

        <button type="button" class="flex items-center justify-between w-full text-sm font-bold text-white mb-4 group">
            <span>Price Range (AED)</span>
            <i class="bi bi-chevron-down transition-transform group-hover:text-brand-accent"></i>
        </button>

        <div class="space-y-4">

            {{-- Slider --}}
            <div class="relative h-2 bg-white/10 rounded-full overflow-visible">

                {{-- fill --}}
                <div id="priceFill"
                    class="absolute h-full rounded-full bg-gradient-to-r from-brand-accent to-brand-secondary"
                    style="left:0%; width:100%;"></div>

                {{-- draggable sliders (must be inside the same relative container) --}}
                <input id="priceMinRange"
                    type="range"
                    min="{{ $minBound }}"
                    max="{{ $maxBound }}"
                    step="1"
                    value="{{ $minVal }}"
                    class="price-range price-range--min" />

                <input id="priceMaxRange"
                    type="range"
                    min="{{ $minBound }}"
                    max="{{ $maxBound }}"
                    step="1"
                    value="{{ $maxVal }}"
                    class="price-range price-range--max" />

                {{-- dots --}}
                <div id="priceDotMin"
                    class="absolute w-4 h-4 bg-white rounded-full shadow-lg"
                    style="top:-4px; left:0;"></div>

                <div id="priceDotMax"
                    class="absolute w-4 h-4 bg-white rounded-full shadow-lg"
                    style="top:-4px; left:calc(100% - 16px);"></div>
            </div>

            {{-- Inputs --}}
            <div class="flex items-center gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">AED</span>
                    <input id="minPriceInput"
                        name="min_price"
                        type="number"
                        min="{{ $minBound }}"
                        max="{{ $maxBound }}"
                        value="{{ $minVal }}"
                        class="w-full rounded-xl border border-white/10 bg-white/5 pl-10 pr-3 py-2.5 text-sm text-white outline-none focus:border-brand-accent transition-colors price-number" />
                </div>

                <span class="text-gray-500">-</span>

                <div class="relative flex-1">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">AED</span>
                    <input id="maxPriceInput"
                        name="max_price"
                        type="number"
                        min="{{ $minBound }}"
                        max="{{ $maxBound }}"
                        value="{{ $maxVal }}"
                        class="w-full rounded-xl border border-white/10 bg-white/5 pl-10 pr-3 py-2.5 text-sm text-white outline-none focus:border-brand-accent transition-colors price-number" />
                </div>
            </div>

        </div>
    </div>

    {{-- Rating Filter --}}
    <div class="glass-panel rounded-2xl border border-white/10 p-5 filter-section">
        <button type="button" class="flex items-center justify-between w-full text-sm font-bold text-white mb-4 group">
            <span>Rating</span>
            <i class="bi bi-chevron-down transition-transform group-hover:text-brand-accent"></i>
        </button>
        <div class="space-y-2">
            @php $selectedRating = request('rating'); @endphp

            @foreach([5,4,3,2,1] as $stars)
            <label class="flex items-center gap-3 cursor-pointer group">
                <input type="radio" name="rating" value="{{ $stars }}"
                    class="sr-only peer"
                    {{ (string)$selectedRating === (string)$stars ? 'checked' : '' }}
                    onchange="document.getElementById('shopFiltersForm').submit()">

                <div class="flex-1 flex items-center gap-1">
                    @for($i=0;$i<5;$i++)
                        <i class="bi bi-star-fill text-xs {{ $i < $stars ? 'text-yellow-400' : 'text-gray-600' }}"></i>
                        @endfor
                        <span class="text-xs text-gray-400 ml-2">& Up</span>
                </div>

                <div class="w-4 h-4 rounded-full border-2 border-white/20 peer-checked:border-brand-accent peer-checked:bg-brand-accent transition-all relative">
                    <div class="absolute inset-1 rounded-full bg-black opacity-0 peer-checked:opacity-100"></div>
                </div>
            </label>
            @endforeach

            {{-- Clear rating --}}
            @if($selectedRating !== null && $selectedRating !== '')
            <button type="button"
                onclick="(function(){
                                    const f=document.getElementById('shopFiltersForm');
                                    const r=f.querySelectorAll('input[name=rating]');
                                    r.forEach(x=>x.checked=false);
                                    // remove rating param by submitting to URL without it:
                                    const url=new URL(window.location.href); url.searchParams.delete('rating'); window.location.href=url.toString();
                                })()"
                class="mt-3 text-xs text-brand-accent hover:text-white transition-colors">
                Clear rating
            </button>
            @endif
        </div>
    </div>
</form>