<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $items = $user->wishlistProducts()
            ->with(['images' => fn($q) => $q->orderBy('id')])
            ->get();

        $wishlistCount = $items->count();
        $wishlistTotal = $items->sum(fn($p) => (int) ($p->price ?? 0));

        $recommended = Product::query()
            ->whereNotIn('id', $items->pluck('id'))
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('pages.shop.wishlist', [
            'items' => $items,
            'recommended' => $recommended,
            'wishlistCount' => $wishlistCount,
            'wishlistTotal' => $wishlistTotal,
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $request->user()->wishlistProducts()->syncWithoutDetaching([$product->id]);

        return response()->json([
            'ok' => true,
        ]);
    }

    public function destroy(Request $request, Product $product)
    {
        $request->user()->wishlistProducts()->detach($product->id);

        return response()->json([
            'ok' => true,
        ]);
    }

    public function clear(Request $request)
    {
        $request->user()->wishlistProducts()->detach();

        return response()->json([
            'ok' => true,
        ]);
    }

    public function toggle(Request $request, Product $product)
    {
        // Logged in: DB
        if ($request->user()) {
            $rel = $request->user()->wishlistProducts();
            $exists = $rel->where('product_id', $product->id)->exists();

            if ($exists) $rel->detach($product->id);
            else $rel->attach($product->id);

            $count = $request->user()->wishlistProducts()->count();

            return response()->json([
                'ok' => true,
                'in_wishlist' => !$exists,
                'added' => !$exists,
                'count' => $count,
            ]);
        }

        // Guest: Session
        $ids = $request->session()->get('wishlist_ids', []);
        $ids = array_map('intval', (array) $ids);

        $exists = in_array((int) $product->id, $ids, true);

        if ($exists) {
            $ids = array_values(array_diff($ids, [(int) $product->id]));
        } else {
            $ids[] = (int) $product->id;
            $ids = array_values(array_unique($ids));
        }

        $request->session()->put('wishlist_ids', $ids);

        return response()->json([
            'ok' => true,
            'in_wishlist' => !$exists,
            'added' => !$exists,
            'count' => count($ids),
        ]);
    }
}
