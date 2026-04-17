<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * FRONTEND (optional demo array)
     */
    private function products()
    {
        return [
            [
                'name' => 'RTX 4070 Super',
                'slug' => Str::slug('RTX 4070 Super'),
                'image' => asset('images/products/rtx-4070-super.png'),
                'thumbs' => [
                    asset('images/products/rtx-4070-super.png'),
                    asset('images/products/rtx-4070-super-2.png'),
                    asset('images/products/rtx-4070-super-3.png'),
                    asset('images/products/rtx-4070-super-4.png'),
                    asset('images/products/rtx-4070-super-5.png'),
                ],
                'price' => '2,799',
                'tag' => 'New',
                'badge' => 'hot',
                'category' => 'Graphics Cards',
            ],
            // ...
        ];
    }

    /**
     * FRONTEND product page (by slug)
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with([
                'images' => function ($q) {
                    $q->orderByDesc('is_primary')
                        ->orderBy('sort_order');
                },
                'category'
            ])
            ->firstOrFail();

        // Images already ordered: primary first
        $imgs = $product->images ?? collect();

        $mainImg = $imgs->first()
            ? asset('storage/' . ltrim($imgs->first()->image_path, '/'))
            : asset('images/placeholder.png');

        $thumbs = $imgs->map(fn($img) => asset('storage/' . ltrim($img->image_path, '/')))->toArray();

        if (empty($thumbs)) $thumbs = [$mainImg];

        $related = Product::query()
            ->with(['images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn($q) => $q->where('category_id', $product->category_id))
            ->orderByDesc('id')
            ->take(8)
            ->get();

        $inWishlist = false;

        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            $inWishlist = $user->wishlistProducts()
                ->whereKey($product->id)   // cleaner than where('product_id', ...)
                ->exists();
        } else {
            $ids = session('wishlist_ids', []);
            $inWishlist = in_array((int)$product->id, array_map('intval', (array)$ids), true);
        }

        // Track recently viewed products (store product IDs in session)
        $recent = session()->get('recently_viewed', []);

        // remove if already exists (so it moves to top)
        $recent = array_values(array_filter($recent, fn($id) => (int)$id !== (int)$product->id));

        // add to beginning
        array_unshift($recent, $product->id);

        // keep only last 8
        $recent = array_slice($recent, 0, 8);

        session()->put('recently_viewed', $recent);

        $recentIds = session()->get('recently_viewed', []);

        // remove current product ONLY IF more than 1 item exists
        if (count($recentIds) > 1) {
            $recentIds = array_values(array_filter($recentIds, fn($id) => (int)$id !== (int)$product->id));
        }

        $recentProducts = Product::whereIn('id', $recentIds)
            ->with(['images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
            ->get()
            ->sortBy(fn($p) => array_search($p->id, $recentIds))
            ->take(4);

        if ($recentProducts->isEmpty()) {
            $recentProducts = Product::with(['images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
                ->where('id', '!=', $product->id)
                ->where('is_active', true)
                ->latest()
                ->take(4)
                ->get();
        }

        return view('pages.product', compact(
            'product',
            'thumbs',
            'mainImg',
            'related',
            'inWishlist',
            'recentProducts'
        ));
    }

    /**
     * ADMIN: store
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'brand'             => ['nullable', 'string', 'max:120'],
            'sku'               => ['nullable', 'string', 'max:120'],
            'condition'         => ['required', 'in:new,used,refurbished'],
            'product_type'      => ['required', 'in:component,custom_pc'],
            'category_id'       => ['nullable', 'integer', 'exists:categories,id'],
            'price'             => ['required', 'numeric', 'min:0'],
            'compare_at_price'  => ['nullable', 'numeric', 'min:0'],
            'stock_qty'         => ['nullable', 'integer', 'min:0'],
            'badge_text'        => ['nullable', 'string', 'max:40'],
            'rating'            => ['nullable', 'numeric', 'min:0', 'max:5'],
            'rating_count'      => ['nullable', 'integer', 'min:0'],
            'delivery_text'     => ['nullable', 'string', 'max:255'],
            'discount_percent'  => ['nullable', 'numeric', 'min:0'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description'       => ['nullable', 'string'],
            'is_active'         => ['nullable', 'boolean'],
            'images'            => ['nullable', 'array'],
            'images.*'          => ['image', 'max:5120'],
        ]);

        $data['is_active'] = (bool)($request->input('is_active', 0));

        return DB::transaction(function () use ($request, $data) {
            $baseSlug = Str::slug($data['name']);
            $slug = $baseSlug;

            $i = 2;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $i;
                $i++;
            }

            $product = Product::create([
                'name'              => $data['name'],
                'slug'              => $slug,
                'brand'             => $data['brand'] ?? null,
                'sku'               => $data['sku'] ?? null,
                'condition'         => $data['condition'],
                'product_type'      => $data['product_type'],
                'category_id'       => $data['category_id'] ?? null,
                'price'             => $data['price'],
                'compare_at_price'  => $data['compare_at_price'] ?? null,
                'stock_qty'         => $data['stock_qty'] ?? 0,
                'badge_text'        => $data['badge_text'] ?? null,
                'rating'            => $data['rating'] ?? null,
                'rating_count'      => $data['rating_count'] ?? 0,
                'delivery_text'     => $data['delivery_text'] ?? null,
                'discount_percent'  => $data['discount_percent'] ?? null,
                'short_description' => $data['short_description'] ?? null,
                'description'       => $data['description'] ?? null,
                'is_active'         => $data['is_active'],
            ]);

            if ($request->hasFile('images')) {
                $files = $request->file('images');
                foreach ($files as $idx => $file) {
                    $path = $file->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $idx === 0 ? 1 : 0,
                        'sort_order' => $idx,
                    ]);
                }
            }

            $product->load([
                'category:id,name,slug,parent_id',
                'images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('id'),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'ok' => true,
                    'product' => $this->productPayload($product),
                ]);
            }

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product created');
        });
    }

    /**
     * ADMIN: edit form
     */
    public function edit(Product $product)
    {
        $product->load(['images', 'primaryImage']);
        return view('admin.products.edit', compact('product'));
    }

    /**
     * ADMIN: update
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'brand'             => ['nullable', 'string', 'max:120'],
            'sku'               => ['nullable', 'string', 'max:120'],
            'condition'         => ['required', 'in:new,used,refurbished'],
            'product_type'      => ['required', 'in:component,custom_pc'],
            'category_id'       => ['nullable', 'integer', 'exists:categories,id'],
            'price'             => ['required', 'numeric', 'min:0'],
            'compare_at_price'  => ['nullable', 'numeric', 'min:0'],
            'stock_qty'         => ['nullable', 'integer', 'min:0'],
            'badge_text'        => ['nullable', 'string', 'max:40'],
            'rating'            => ['nullable', 'numeric', 'min:0', 'max:5'],
            'rating_count'      => ['nullable', 'integer', 'min:0'],
            'delivery_text'     => ['nullable', 'string', 'max:255'],
            'discount_percent'  => ['nullable', 'numeric', 'min:0'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description'       => ['nullable', 'string'],
            'is_active'         => ['nullable', 'boolean'],
            'images'            => ['nullable', 'array'],
            'images.*'          => ['image', 'max:5120'],
            'primary_image_id'  => ['nullable', 'integer'],
        ]);

        $data['is_active'] = (bool)($request->input('is_active', 0));

        return DB::transaction(function () use ($request, $data, $product) {
            if ($product->name !== $data['name']) {
                $baseSlug = Str::slug($data['name']);
                $slug = $baseSlug;
                $i = 2;

                while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = $baseSlug . '-' . $i;
                    $i++;
                }

                $product->slug = $slug;
            }

            $product->update([
                'name'              => $data['name'],
                'brand'             => $data['brand'] ?? null,
                'sku'               => $data['sku'] ?? null,
                'condition'         => $data['condition'],
                'product_type'      => $data['product_type'],
                'category_id'       => $data['category_id'] ?? null,
                'price'             => $data['price'],
                'compare_at_price'  => $data['compare_at_price'] ?? null,
                'stock_qty'         => $data['stock_qty'] ?? 0,
                'badge_text'        => $data['badge_text'] ?? null,
                'rating'            => $data['rating'] ?? null,
                'rating_count'      => $data['rating_count'] ?? 0,
                'delivery_text'     => $data['delivery_text'] ?? null,
                'discount_percent'  => $data['discount_percent'] ?? null,
                'short_description' => $data['short_description'] ?? null,
                'description'       => $data['description'] ?? null,
                'is_active'         => $data['is_active'],
            ]);

            // keep your existing image logic / append logic here

            $primaryImageId = $request->input('primary_image_id');
            if ($primaryImageId) {
                $img = ProductImage::where('id', $primaryImageId)
                    ->where('product_id', $product->id)
                    ->first();

                if ($img) {
                    ProductImage::where('product_id', $product->id)->update(['is_primary' => 0]);
                    $img->is_primary = 1;
                    $img->save();
                }
            }

            $product->load([
                'category:id,name,slug,parent_id',
                'images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('id'),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'ok' => true,
                    'product' => $this->productPayload($product),
                ]);
            }

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product updated');
        });
    }

    /**
     * ADMIN: delete
     */
    public function destroy(Product $product)
    {
        return DB::transaction(function () use ($product) {
            $product->load('images');

            foreach ($product->images as $img) {
                if ($img->image_path) {
                    Storage::disk('public')->delete($img->image_path);
                }
            }

            ProductImage::where('product_id', $product->id)->delete();
            $product->delete();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product deleted');
        });
    }

    private function productPayload(Product $product): array
    {
        $product->load([
            'category:id,name,slug,parent_id',
            'images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('id'),
        ]);

        $primary = $product->images->firstWhere('is_primary', 1) ?? $product->images->first();

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'brand' => $product->brand,
            'sku' => $product->sku,
            'condition' => $product->condition,
            'product_type' => $product->product_type,
            'product_type_label' => $product->product_type === 'custom_pc' ? 'Custom PC' : 'Component',
            'category_id' => $product->category_id,
            'category_name' => $product->category?->name,
            'price' => $product->price,
            'compare_at_price' => $product->compare_at_price,
            'stock_qty' => $product->stock_qty,
            'is_active' => (bool) $product->is_active,
            'badge_text' => $product->badge_text,
            'rating' => $product->rating,
            'rating_count' => $product->rating_count,
            'delivery_text' => $product->delivery_text,
            'discount_percent' => $product->discount_percent,
            'short_description' => $product->short_description,
            'description' => $product->description,
            'images_count' => $product->images->count(),
            'primary_image_url' => $primary
                ? asset('storage/' . ltrim($primary->image_path, '/'))
                : asset('images/placeholder-product.png'),
            'images' => $product->images->map(function ($img) {
                return [
                    'id' => $img->id,
                    'url' => asset('storage/' . ltrim($img->image_path, '/')),
                    'is_primary' => (bool) $img->is_primary,
                ];
            })->values(),
        ];
    }
}
