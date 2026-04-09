<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class AdminProductController extends Controller
{

    public function index(Request $request)
    {
        $products = Product::query()
            ->with(['primaryImage', 'images'])
            ->withCount('images')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('brand', 'like', '%' . $search . '%')
                        ->orWhere('sku', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(30)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        $sidebarCounts = [
            'products' => Product::count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'sidebarCounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'         => ['nullable', 'exists:categories,id'],
            'name'                => ['required', 'string', 'max:255'],
            'sku'                 => ['nullable', 'string', 'max:255'],
            'condition'           => ['required', 'in:new,used,refurbished'],
            'brand'               => ['nullable', 'string', 'max:255'],
            'short_description'   => ['nullable', 'string'],
            'description'         => ['nullable', 'string'],
            'price'               => ['required', 'numeric', 'min:0'],
            'compare_at_price'    => ['nullable', 'numeric'],
            'stock_qty'           => ['nullable', 'integer'],
            'badge_text'          => ['nullable', 'string', 'max:255'],
            'rating'              => ['nullable', 'numeric'],
            'rating_count'        => ['nullable', 'integer'],
            'delivery_text'       => ['nullable', 'string', 'max:255'],
            'discount_percent'    => ['nullable', 'numeric'],
            'is_active'           => ['nullable'],
            'sort_order'          => ['nullable', 'integer'],
            'images'              => ['nullable', 'array'],
            'images.*'            => ['file', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
            'video'               => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:51200'], // 50MB
        ], [
            'images.*.mimes' => 'Images must be JPG, PNG, WEBP, or AVIF.',
            'images.*.max'   => 'Each image must be 5MB or less.',
        ]);

        // Defaults to avoid DB NOT NULL crashes
        $data['rating_count'] = (int) ($request->input('rating_count') ?: 0);
        $data['stock_qty']    = (int) ($request->input('stock_qty') ?: 0);

        $data['is_active']    = $request->boolean('is_active');

        $data['product_type'] = $this->resolveProductTypeFromCategory(
            isset($data['category_id']) && $data['category_id'] !== '' ? (int) $data['category_id'] : null
        );

        $base = Str::slug($data['name']);
        $slug = $base;
        $i = 2;
        while (Product::where('slug', $slug)->when($product ?? null, fn($q) => $q->where('id', '!=', $product->id))->exists()) {
            $slug = $base . '-' . $i++;
        }
        $data['slug'] = $slug;

        unset($data['images']);

        $product = DB::transaction(function () use ($data, $request) {
            $product = Product::create($data);

            // optional: multi-image upload
            if ($request->hasFile('images')) {
                $i = 0;
                foreach ($request->file('images') as $file) {
                    $path = $file->store('products', 'public'); // products/xxx.png
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $i === 0 ? 1 : 0,
                        'sort_order' => $i,
                    ]);
                    $i++;
                }
            }

            if ($request->hasFile('video')) {
                $v = $request->file('video');
                $videoPath = $v->store('product-videos', 'public');

                $product->update([
                    'video_path' => $videoPath,
                    'video_size' => $v->getSize(),
                ]);
            }

            return $product;
        });

        $product->load(['primaryImage', 'images']);
        $product->images_count = $product->images()->count();

        // inline UI expects JSON
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'product' => $this->productPayload($product),
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id'         => ['nullable', 'exists:categories,id'],
            'name'                => ['required', 'string', 'max:255'],
            'sku'                 => ['nullable', 'string', 'max:255'],
            'condition'           => ['required', 'in:new,used,refurbished'],
            'brand'               => ['nullable', 'string', 'max:255'],
            'short_description'   => ['nullable', 'string'],
            'description'         => ['nullable', 'string'],
            'price'               => ['required', 'numeric'],
            'compare_at_price'    => ['nullable', 'numeric'],
            'stock_qty'           => ['nullable', 'integer'],
            'badge_text'          => ['nullable', 'string', 'max:255'],
            'rating'              => ['nullable', 'numeric'],
            'rating_count'        => ['nullable', 'integer'],
            'delivery_text'       => ['nullable', 'string', 'max:255'],
            'discount_percent'    => ['nullable', 'numeric'],
            'is_active'           => ['nullable'],
            'sort_order'          => ['nullable', 'integer'],
            'images'              => ['nullable', 'array'],
            'images.*'            => ['file', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
            'video'               => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:51200'], // 50MB
        ], [
            'images.*.mimes' => 'Images must be JPG, PNG, WEBP, or AVIF.',
            'images.*.max'   => 'Each image must be 5MB or less.',
        ]);

        $data['rating_count'] = (int) ($request->input('rating_count') ?: 0);
        $data['stock_qty']    = (int) ($request->input('stock_qty') ?: 0);

        $data['is_active'] = (bool)($data['is_active'] ?? false);

        $data['product_type'] = $this->resolveProductTypeFromCategory(
            isset($data['category_id']) && $data['category_id'] !== '' ? (int) $data['category_id'] : null
        );

        $base = Str::slug($data['name']);
        $slug = $base;
        $i = 2;
        while (Product::where('slug', $slug)->when($product ?? null, fn($q) => $q->where('id', '!=', $product->id))->exists()) {
            $slug = $base . '-' . $i++;
        }
        $data['slug'] = $slug;

        unset($data['images']);

        DB::transaction(function () use ($product, $data, $request) {
            $product->update($data);

            // optional: add new images (doesn't delete old)
            if ($request->hasFile('images')) {
                $currentMax = (int) $product->images()->max('sort_order');
                $start = is_null($currentMax) ? 0 : $currentMax + 1;

                $i = 0;
                foreach ($request->file('images') as $file) {
                    $path = $file->store('products', 'public');
                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => 0,
                        'sort_order' => $start + $i,
                    ]);
                    $i++;
                }

                // if no primary exists, set first as primary
                if (!$product->primaryImage()->exists()) {
                    $first = $product->images()->orderBy('sort_order')->first();
                    if ($first) {
                        $first->update(['is_primary' => 1]);
                    }
                }
            }

            if ($request->hasFile('video')) {
                // delete old
                if ($product->video_path) {
                    Storage::disk('public')->delete(ltrim($product->video_path, '/'));
                }

                $v = $request->file('video');
                $videoPath = $v->store('product-videos', 'public');

                $product->update([
                    'video_path' => $videoPath,
                    'video_size' => $v->getSize(),
                ]);
            }
        });

        $product->load(['primaryImage', 'images']);
        $product->images_count = $product->images()->count();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'product' => $this->productPayload($product),
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated');
    }

    public function destroy(Request $request, Product $product)
    {
        $product->delete();

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Product deleted');
    }

    private function productPayload(Product $p): array
    {
        $p->loadMissing(['images', 'primaryImage']);

        $images = ($p->images ?? collect())->map(function ($img) {
            $path = $img->image_path;
            if (!$path) return null;

            $url = (!str_starts_with($path, 'http') && !str_starts_with($path, 'images/'))
                ? asset('storage/' . ltrim($path, '/'))
                : asset(ltrim($path, '/'));

            return [
                'id' => $img->id,
                'url' => $url,
                'is_primary' => (bool)$img->is_primary,
            ];
        })->filter()->values()->toArray();

        return [
            'id' => $p->id,
            'name' => $p->name,
            'slug' => $p->slug,
            'brand' => $p->brand,
            'sku' => $p->sku,
            'condition' => $p->condition,
            'product_type' => $p->product_type,
            'product_type_label' => $this->productTypeLabel($p->product_type),
            'category_id' => $p->category_id,
            'short_description' => $p->short_description,
            'description' => $p->description,
            'price' => $p->price,
            'compare_at_price' => $p->compare_at_price,
            'stock_qty' => $p->stock_qty,
            'is_active' => (bool)$p->is_active,
            'badge_text' => $p->badge_text,
            'rating' => $p->rating,
            'rating_count' => $p->rating_count,
            'delivery_text' => $p->delivery_text,
            'discount_percent' => $p->discount_percent,
            'sort_order' => $p->sort_order,
            'images_count' => count($images),
            'primary_image_url' => $p->primaryImage
                ? asset('storage/' . ltrim($p->primaryImage->image_path, '/'))
                : (count($images) ? $images[0]['url'] : null),

            'images' => $images,
            'video_url' => $p->video_url,
            'has_video' => (bool) $p->video_path,
        ];
    }

    public function deleteImage(ProductImage $image)
    {
        $product = $image->product;
        if (!$product) return response()->json(['ok' => false, 'message' => 'Product not found'], 404);

        $wasPrimary = (bool) $image->is_primary;

        if ($image->image_path) {
            Storage::disk('public')->delete(ltrim($image->image_path, '/'));
        }

        $image->delete();

        if ($wasPrimary) {
            $newPrimary = $product->images()->orderBy('sort_order')->first();
            if ($newPrimary) {
                $product->images()->update(['is_primary' => 0]);
                $newPrimary->update(['is_primary' => 1]);
            }
        }

        $product->load(['primaryImage', 'images']);

        return response()->json([
            'ok' => true,
            'product' => $this->productPayload($product),
        ]);
    }

    public function setPrimary(ProductImage $image)
    {
        $product = $image->product;
        if (!$product) {
            return response()->json(['ok' => false, 'message' => 'Product not found'], 404);
        }

        $product->images()->update(['is_primary' => 0]);
        $image->update(['is_primary' => 1]);

        $product->load(['primaryImage', 'images']);
        $product->images_count = $product->images()->count();

        return response()->json([
            'ok' => true,
            'product' => $this->productPayload($product),
        ]);
    }

    private function resolveProductTypeFromCategory(?int $categoryId): string
    {
        if (!$categoryId) {
            return 'component';
        }

        $category = Category::select('id', 'name', 'parent_id')->find($categoryId);
        if (!$category) {
            return 'component';
        }

        // climb to the top parent
        $current = $category;
        while ($current && $current->parent_id) {
            $current = Category::select('id', 'name', 'parent_id')->find($current->parent_id);
        }

        $rootName = strtolower(trim($current?->name ?? $category->name ?? ''));

        return $rootName === 'custom pcs' ? 'custom_pc' : 'component';
    }

    private function productTypeLabel(string $type): string
    {
        return $type === 'custom_pc' ? 'Custom PC' : 'Component';
    }
}
