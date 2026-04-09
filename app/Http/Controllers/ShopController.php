<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $category = null)
    {
        $activeCategory = $request->query('category', $category ?: 'all');

        // Base query
        $q = Product::query()
            ->with(['images' => fn($x) => $x->orderByDesc('is_primary')->orderBy('id'), 'category'])
            ->where('is_active', 1);

        // Category filter (slug)
        if ($activeCategory !== 'all') {
            $q->whereHas('category', fn($c) => $c->where('slug', $activeCategory));
        }

        // Condition filter (multi)
        $conditions = array_filter((array)$request->query('condition', []));
        if (!empty($conditions)) {
            $q->whereIn('condition', $conditions);
        }

        // Brand filter (multi)
        $brands = array_filter((array)$request->query('brand', []));
        if (!empty($brands)) {
            $q->whereIn('brand', $brands);
        }

        // Rating filter (min rating)
        $minRating = $request->query('rating');
        if ($minRating !== null && $minRating !== '') {
            $q->where('rating', '>=', (float)$minRating);
        }

        // Price range
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        if ($minPrice !== null && $minPrice !== '') $q->where('price', '>=', (float)$minPrice);
        if ($maxPrice !== null && $maxPrice !== '') $q->where('price', '<=', (float)$maxPrice);

        // Sort
        $sort = $request->query('sort', 'featured');
        match ($sort) {
            'price_asc'  => $q->orderBy('price', 'asc'),
            'price_desc' => $q->orderBy('price', 'desc'),
            'rating'     => $q->orderBy('rating', 'desc'),
            'newest'     => $q->latest('id'),
            default      => $q->orderByDesc('id'), // featured fallback
        };

        // Pagination
        $products = $q->paginate(12)->withQueryString();

        // Real categories only (NO temp)
        // If you have a field like show_in_shop/is_active use it.
        // Otherwise remove “Temp” by name/slug (safe fallback).
        $categories = Category::query()
            ->where('is_active', 1)
            ->where('slug', '!=', 'temp-cat')     // adjust if your temp slug differs
            ->where('name', 'not like', '%temp%') // fallback
            ->orderBy('name')
            ->get();

        // Facets from ACTIVE products (so filters show only available)
        $brandFacet = Product::where('is_active', 1)
            ->selectRaw('brand, COUNT(*) as c')
            ->whereNotNull('brand')->where('brand', '!=', '')
            ->groupBy('brand')->orderBy('brand')
            ->pluck('c', 'brand');

        $condFacet = Product::where('is_active', 1)
            ->selectRaw('`condition`, COUNT(*) as c')
            ->whereNotNull('condition')
            ->whereRaw("`condition` != ''")
            ->groupBy('condition')
            ->orderBy('condition')
            ->pluck('c', 'condition');

        // price bounds for UI (range)
        $priceMinMax = Product::where('is_active', 1)->selectRaw('MIN(price) as minp, MAX(price) as maxp')->first();

        if ($request->ajax()) {
            return response()->json([
                'filters'  => view('pages.shop._filters', compact(
                    'products',
                    'categories',
                    'activeCategory',
                    'brandFacet',
                    'condFacet',
                    'sort',
                    'priceMinMax'
                ))->render(),

                'products' => view('pages.shop._products', compact(
                    'products',
                    'categories',
                    'activeCategory',
                    'brandFacet',
                    'condFacet',
                    'sort',
                    'priceMinMax'
                ))->render(),
            ]);
        }

        return view('pages.shop', compact(
            'products',
            'categories',
            'activeCategory',
            'brandFacet',
            'condFacet',
            'sort',
            'priceMinMax'
        ));
    }
}
