@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="relative min-h-full">
    <!-- Mobile top bar -->
    <div class="lg:hidden sticky top-0 z-20 glass-panel border-b border-white/10 px-4 py-4 mb-4">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <button
                    type="button"
                    class="w-11 h-11 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 transition shrink-0"
                    @click.stop="sidebarOpen = true"
                    aria-label="Open admin sidebar">
                    <i class="bi bi-list text-xl"></i>
                </button>

                <div class="min-w-0">
                    <h1 class="text-2xl font-bold leading-tight">Categories</h1>
                    <p class="text-sm text-gray-400 truncate">Manage menu and product grouping.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-0 lg:p-6 min-h-full">
        <div class="grid grid-cols-12 gap-4 lg:gap-6 min-h-0">

            {{-- LEFT: ADD CATEGORY --}}
            <div class="col-span-12 lg:col-span-4">
                <div class="sticky top-6">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                        <h2 class="text-2xl font-bold mb-2">Add Category</h2>
                        <p class="text-sm text-gray-400 mb-4">
                            Create main categories and child categories for menu and product grouping.
                        </p>

                        @if(session('success'))
                        <div class="mb-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-emerald-200">
                            {{ session('success') }}
                        </div>
                        @endif

                        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                            @csrf

                            <div>
                                <label class="text-xs text-gray-400">Parent Category</label>
                                <select name="parent_id" class="mt-1 w-full rounded-xl bg-[#0b1220] border border-white/10 px-3 py-2.5">
                                    <option value="">— None —</option>
                                    @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-[11px] text-gray-500 mt-1">
                                    Leave empty for a main category. Choose a parent to make this a child category.
                                </p>
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Name *</label>
                                <input
                                    name="name"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5"
                                    placeholder="Example: Graphics Cards">
                                <p class="text-[11px] text-gray-500 mt-1">
                                    This is the category text shown in menu, dropdowns, and filters.
                                </p>
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Sort Order</label>
                                <input
                                    type="number"
                                    name="sort_order"
                                    value="0"
                                    class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5">
                                <p class="text-[11px] text-gray-500 mt-1">
                                    Smaller number shows first.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 gap-3">
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="is_active" value="1" checked>
                                    <span>Active</span>
                                </label>

                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="show_in_menu" value="1" checked>
                                    <span>Show in Menu</span>
                                </label>

                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="show_on_home" value="1" checked>
                                    <span>Show on Home</span>
                                </label>
                            </div>

                            <button class="w-full px-4 py-3 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition">
                                Add Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- RIGHT: CATEGORY LIST --}}
            <div class="col-span-12 lg:col-span-8 min-w-0">
                <div class="sticky top-0 z-10 pb-4 bg-[#030b19] rounded-t-2xl">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <label class="text-xs text-gray-400">Search Categories</label>
                        <input
                            type="text"
                            id="categorySearchInput"
                            placeholder="Search by category name, parent, or slug..."
                            class="mt-2 w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 outline-none focus:border-brand-accent text-white">
                    </div>
                </div>

                <div id="categoriesList" class="space-y-4">
                    <div id="categorySearchEmpty" class="hidden rounded-2xl border border-white/10 bg-white/5 p-6 text-center text-gray-400">
                        No categories found.
                    </div>

                    @foreach($categories as $category)
                    <div
                        class="rounded-2xl border border-white/10 bg-white/5 p-5 category-card"
                        data-search="{{ strtolower($category->name . ' ' . ($category->parent?->name ?? 'main category') . ' ' . $category->slug) }}">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
                            <div>
                                <div class="text-xl font-bold text-white">{{ $category->name }}</div>
                                <div class="text-xs text-gray-400 mt-1">
                                    Parent:
                                    <span class="text-gray-200 font-medium">
                                        {{ $category->parent?->name ?? 'Main Category' }}
                                    </span>
                                    •
                                    Slug:
                                    <span class="text-gray-200 font-medium">{{ $category->slug }}</span>
                                </div>
                            </div>

                            <div class="text-left sm:text-right text-xs">
                                <div class="{{ $category->is_active ? 'text-emerald-300' : 'text-red-300' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </div>
                                <div class="text-gray-400 mt-1">
                                    Sort: <span class="text-gray-200">{{ $category->sort_order }}</span>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs text-gray-400">Name</label>
                                    <input
                                        name="name"
                                        value="{{ $category->name }}"
                                        class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5">
                                </div>

                                <div>
                                    <label class="text-xs text-gray-400">Parent Category</label>
                                    <select name="parent_id" class="mt-1 w-full rounded-xl bg-[#0b1220] border border-white/10 px-3 py-2.5">
                                        <option value="">— None —</option>
                                        @foreach($parents as $parent)
                                        @if($parent->id !== $category->id)
                                        <option value="{{ $parent->id }}" @selected($category->parent_id == $parent->id)>
                                            {{ $parent->name }}
                                        </option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="text-xs text-gray-400">Sort Order</label>
                                    <input
                                        type="number"
                                        name="sort_order"
                                        value="{{ $category->sort_order }}"
                                        class="mt-1 w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2.5">
                                </div>

                                <div class="rounded-xl border border-white/10 bg-white/[0.03] p-3">
                                    <div class="text-xs text-gray-500 mb-2">Usage</div>
                                    <div class="space-y-1 text-sm text-gray-300">
                                        <div>Products linked: <span class="text-white font-semibold">{{ $category->products_count ?? $category->products()->count() }}</span></div>
                                        <div>Menu: <span class="text-white font-semibold">{{ $category->show_in_menu ? 'Visible' : 'Hidden' }}</span></div>
                                        <div>Home: <span class="text-white font-semibold">{{ $category->show_on_home ? 'Visible' : 'Hidden' }}</span></div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="is_active" value="1" @checked($category->is_active)>
                                    <span>Active</span>
                                </label>

                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="show_in_menu" value="1" @checked($category->show_in_menu)>
                                    <span>Show in Menu</span>
                                </label>

                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="show_on_home" value="1" @checked($category->show_on_home)>
                                    <span>Show on Home</span>
                                </label>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3">
                                <button class="px-4 py-2.5 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-200">
                                    Save Changes
                                </button>
                        </form>

                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-red-500/15 border border-red-500/30 text-red-200">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</div>
@endsection