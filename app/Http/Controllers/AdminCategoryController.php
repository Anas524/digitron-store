<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['parent'])
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $parents = Category::whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $sidebarCounts = [
            'products' => Product::count(),
            'categories' => Category::count(),
        ];

        return view('admin.categories.index', compact('categories', 'parents', 'sidebarCounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'parent_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
            'show_in_menu' => ['nullable'],
            'show_on_home' => ['nullable'],
        ]);

        $base = Str::slug($data['name']);
        $slug = $base;
        $i = 2;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        Category::create([
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'],
            'slug' => $slug,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
            'show_in_menu' => $request->boolean('show_in_menu'),
            'show_on_home' => $request->boolean('show_on_home'),
        ]);

        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'parent_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
            'show_in_menu' => ['nullable'],
            'show_on_home' => ['nullable'],
        ]);

        // Prevent self-parent
        if (!empty($data['parent_id']) && (int) $data['parent_id'] === (int) $category->id) {
            return back()->withErrors([
                'parent_id' => 'A category cannot be its own parent.',
            ])->withInput();
        }

        $base = Str::slug($data['name']);
        $slug = $base;
        $i = 2;

        while (
            Category::where('slug', $slug)
                ->where('id', '!=', $category->id)
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        $category->update([
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'],
            'slug' => $slug,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
            'show_in_menu' => $request->boolean('show_in_menu'),
            'show_on_home' => $request->boolean('show_on_home'),
        ]);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        // Move children to top level before delete
        Category::where('parent_id', $category->id)->update([
            'parent_id' => null,
        ]);

        // Optional safety: prevent delete if products exist
        if ($category->products()->exists()) {
            return back()->withErrors([
                'delete' => "Cannot delete '{$category->name}' because products are linked to it.",
            ]);
        }

        $category->delete();

        return back()->with('success', 'Category deleted successfully.');
    }
}