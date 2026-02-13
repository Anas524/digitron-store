<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(?string $category = null)
    {
        $categories = Category::orderBy('name')->get();

        $query = Product::query()
            ->where('is_active', true)
            ->with('primaryImage');

        if ($category && $category !== 'all') {
            $cat = Category::where('slug', $category)->first();

            // If slug not found, fallback to all
            if ($cat) {
                $query->where('category_id', $cat->id);
            }
        }

        $products = $query->orderBy('sort_order')->latest('id')->paginate(12);

        return view('pages.shop', [
            'products' => $products,
            'categories' => $categories,
            'activeCategory' => $category ?: 'all',
        ]);
    }
}
