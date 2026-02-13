<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['primaryImage'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'        => ['nullable','integer'],
            'name'               => ['required','string','max:255'],
            'sku'                => ['nullable','string','max:100'],
            'condition'          => ['nullable','string','max:50'],
            'brand'              => ['nullable','string','max:100'],
            'short_description'  => ['nullable','string'],
            'description'        => ['nullable','string'],
            'price'              => ['required','numeric'],
            'compare_at_price'   => ['nullable','numeric'],
            'stock_qty'          => ['nullable','integer'],
            'is_active'          => ['nullable','boolean'],
            'sort_order'         => ['nullable','integer'],

            'badge_text'         => ['nullable','string','max:50'],
            'rating'             => ['nullable','numeric','min:0','max:5'],
            'rating_count'       => ['nullable','integer','min:0'],
            'delivery_text'      => ['nullable','string','max:120'],
            'discount_percent'   => ['nullable','integer','min:0','max:100'],

            'images.*'           => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = (bool)($data['is_active'] ?? true);

        $product = Product::create($data);

        // upload images
        if ($request->hasFile('images')) {
            $i = 0;
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $i === 0, // first image primary
                    'sort_order' => $i,
                ]);
                $i++;
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        $product->load('images');
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id'        => ['nullable','integer'],
            'name'               => ['required','string','max:255'],
            'sku'                => ['nullable','string','max:100'],
            'condition'          => ['nullable','string','max:50'],
            'brand'              => ['nullable','string','max:100'],
            'short_description'  => ['nullable','string'],
            'description'        => ['nullable','string'],
            'price'              => ['required','numeric'],
            'compare_at_price'   => ['nullable','numeric'],
            'stock_qty'          => ['nullable','integer'],
            'is_active'          => ['nullable','boolean'],
            'sort_order'         => ['nullable','integer'],

            'badge_text'         => ['nullable','string','max:50'],
            'rating'             => ['nullable','numeric','min:0','max:5'],
            'rating_count'       => ['nullable','integer','min:0'],
            'delivery_text'      => ['nullable','string','max:120'],
            'discount_percent'   => ['nullable','integer','min:0','max:100'],

            'images.*'           => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = (bool)($data['is_active'] ?? false);

        $product->update($data);

        // add new images (keep old)
        if ($request->hasFile('images')) {
            $currentMax = (int)($product->images()->max('sort_order') ?? -1);
            foreach ($request->file('images') as $file) {
                $currentMax++;
                $path = $file->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => false,
                    'sort_order' => $currentMax,
                ]);
            }
        }

        return redirect()->route('admin.products.edit', $product)->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->load('images');

        // delete image files
        foreach ($product->images as $img) {
            if ($img->image_path) Storage::disk('public')->delete($img->image_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    // Set image as primary
    public function setPrimary(ProductImage $image)
    {
        $productId = $image->product_id;

        ProductImage::where('product_id', $productId)->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary image updated.');
    }

    // Delete a single image
    public function deleteImage(ProductImage $image)
    {
        if ($image->image_path) Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Image deleted.');
    }
}
