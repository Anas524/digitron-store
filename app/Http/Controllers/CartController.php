<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private function cart(Request $request): array
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())
                ->pluck('quantity', 'product_id')
                ->map(fn($q) => (int) $q)
                ->toArray();
        }

        return (array) $request->session()->get('cart', []); // [productId => qty]
    }

    private function saveCart(Request $request, array $cart): void
    {
        $cart = array_filter($cart, fn($q) => (int) $q > 0);

        if (Auth::check()) {
            $userId = Auth::id();

            $existing = CartItem::where('user_id', $userId)->get()->keyBy('product_id');

            foreach ($cart as $productId => $qty) {
                if ($existing->has((int) $productId)) {
                    $item = $existing[(int) $productId];
                    $item->quantity = (int) $qty;
                    $item->save();
                } else {
                    CartItem::create([
                        'user_id' => $userId,
                        'product_id' => (int) $productId,
                        'quantity' => (int) $qty,
                    ]);
                }
            }

            // remove deleted items from DB
            CartItem::where('user_id', $userId)
                ->whereNotIn('product_id', array_map('intval', array_keys($cart)))
                ->delete();

            return;
        }

        $request->session()->put('cart', $cart);
    }

    public function index(Request $request)
    {
        $cart = $this->cart($request);

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $items = collect($cart)->map(function ($qty, $pid) use ($products) {
            $p = $products->get((int) $pid);
            if (!$p) return null;

            $price = (int) ($p->price ?? 0);
            $qty = max(1, min(10, (int) $qty));

            $img = $p->primary_image_url ?? ($p->image ? asset($p->image) : asset('images/placeholder-product.png'));

            $stockQty = (int) ($p->stock_qty ?? 0);
            $stockLabel = $stockQty <= 0 ? 'Out of Stock' : ($stockQty <= 5 ? 'Low Stock' : 'In Stock');

            return [
                'id' => $p->id,
                'name' => $p->name,
                'specs' => $p->short_specs ?? $p->specs ?? '',
                'price' => $price,
                'qty' => $qty,
                'image' => $img,
                'stock' => $stockLabel,
                'sku' => $p->sku ?? ('SKU-' . $p->id),
                'warranty' => $p->warranty ?? '—',
            ];
        })->filter()->values();

        $subtotal = $items->sum(fn($it) => $it['price'] * $it['qty']);
        $tax = (int) round($subtotal * 0.05);
        $total = $subtotal + $tax;

        $recentIds = session()->get('recently_viewed', []);
        $inCartIds = array_map('intval', array_keys($cart));
        $recentIds = array_values(array_filter($recentIds, fn($id) => !in_array((int) $id, $inCartIds, true)));

        $recentProducts = collect();

        if (!empty($recentIds)) {
            $recentProducts = Product::whereIn('id', $recentIds)
                ->with(['images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
                ->get()
                ->sortBy(fn($p) => array_search($p->id, $recentIds))
                ->values()
                ->take(4)
                ->map(function ($p) {
                    $imgs = $p->images ?? collect();

                    $img = $imgs->first()
                        ? asset('storage/' . ltrim($imgs->first()->image_path, '/'))
                        : asset('images/placeholder.png');

                    return [
                        'id'    => $p->id,
                        'name'  => $p->name,
                        'slug'  => $p->slug,
                        'price' => (int) ($p->price ?? 0),
                        'image' => $img,
                        'url'   => route('product.show', $p->slug),
                    ];
                });
        }

        return view('pages.shop.cart', [
            'items' => $items,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'count' => $items->sum('qty'),
            'recentProducts' => $recentProducts,
        ]);
    }

    public function add(Request $request, Product $product)
    {
        $qty = (int) ($request->input('qty', $request->input('quantity', 1)));
        $qty = max(1, min(10, $qty));

        if (!$product->is_active) {
            return response()->json([
                'ok' => false,
                'message' => 'This product is not available.',
            ], 422);
        }

        if ((int) $product->stock_qty <= 0) {
            return response()->json([
                'ok' => false,
                'message' => 'This product is out of stock and cannot be added to cart.',
            ], 422);
        }

        $cart = $this->cart($request);
        $pid = (string) $product->id;

        $existingQty = (int) ($cart[$pid] ?? 0);
        $newQty = $existingQty + $qty;

        if ($newQty > (int) $product->stock_qty) {
            return response()->json([
                'ok' => false,
                'message' => 'Requested quantity exceeds available stock.',
            ], 422);
        }

        $cart[$pid] = $newQty;

        $this->saveCart($request, $cart);

        return response()->json([
            'ok' => true,
            'count' => array_sum($cart),
            'message' => 'Product added to cart.',
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $qty = (int) $request->input('qty', 1);
        $qty = max(1, min(10, $qty));

        if (!$product->is_active) {
            return response()->json([
                'ok' => false,
                'message' => 'This product is not available.',
            ], 422);
        }

        if ((int) $product->stock_qty <= 0) {
            return response()->json([
                'ok' => false,
                'message' => 'This product is out of stock.',
            ], 422);
        }

        if ($qty > (int) $product->stock_qty) {
            return response()->json([
                'ok' => false,
                'message' => 'Requested quantity exceeds available stock.',
            ], 422);
        }

        $cart = $this->cart($request);
        $cart[(string) $product->id] = $qty;

        $this->saveCart($request, $cart);

        return response()->json([
            'ok' => true,
            'count' => array_sum($cart),
            'message' => 'Cart updated.',
        ]);
    }

    public function remove(Request $request, Product $product)
    {
        $cart = $this->cart($request);
        unset($cart[(string) $product->id]);

        $this->saveCart($request, $cart);

        return response()->json([
            'ok' => true,
            'count' => array_sum($cart),
        ]);
    }

    public function clear(Request $request)
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            $request->session()->forget('cart');
        }

        return response()->json([
            'ok' => true,
            'count' => 0,
        ]);
    }

    public function checkout(Request $request)
    {
        $cart = $this->cart($request);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $invalid = collect($cart)->first(function ($qty, $pid) use ($products) {
            $p = $products->get((int) $pid);

            if (!$p) return true;
            if (!(bool) $p->is_active) return true;
            if ((int) $p->stock_qty <= 0) return true;
            if ((int) $qty > (int) $p->stock_qty) return true;

            return false;
        });

        if ($invalid !== null) {
            return redirect()->route('cart')->with('error', 'Some items in your cart are out of stock or unavailable. Please update your cart before checkout.');
        }

        $items = collect($cart)->map(function ($qty, $pid) use ($products) {
            $p = $products->get((int) $pid);
            if (!$p) return null;

            $price = (int) ($p->price ?? 0);
            $qty = max(1, min((int) $p->stock_qty, (int) $qty));

            $img = $p->primary_image_url ?? ($p->image ? asset($p->image) : asset('images/placeholder-product.png'));

            return [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $price,
                'qty' => $qty,
                'image' => $img,
                'sku' => $p->sku ?? ('SKU-' . $p->id),
                'subtotal' => $price * $qty,
            ];
        })->filter()->values();

        if ($items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $subtotal = $items->sum('subtotal');
        $tax = (int) round($subtotal * 0.05);
        $total = $subtotal + $tax;

        return view('pages.shop.checkout', [
            'items' => $items,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'count' => $items->sum('qty'),
        ]);
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:120'],
            'address' => ['required', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:cash_on_delivery'],
        ]);

        $cart = $this->cart($request);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $items = collect($cart)->map(function ($qty, $pid) use ($products) {
            $p = $products->get((int) $pid);
            if (!$p) return null;

            $price = (float) ($p->price ?? 0);
            $qty = max(1, min(10, (int) $qty));

            return [
                'product_id' => $p->id,
                'product_name' => $p->name,
                'product_sku' => $p->sku ?? ('SKU-' . $p->id),
                'product_price' => $price,
                'quantity' => $qty,
                'subtotal' => $price * $qty,
            ];
        })->filter()->values();

        if ($items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $subtotal = (float) $items->sum('subtotal');
        $tax = (float) round($subtotal * 0.05, 2);
        $total = $subtotal + $tax;

        $order = DB::transaction(function () use ($data, $items, $subtotal, $tax, $total, $request) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'DCUAE-' . now()->format('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6)),
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'city' => $data['city'],
                'address' => $data['address'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total_amount' => $total,
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
            ]);

            foreach ($items as $item) {
                $order->items()->create($item);
            }

            if (Auth::check()) {
                \App\Models\CartItem::where('user_id', Auth::id())->delete();
            } else {
                $request->session()->forget('cart');
            }

            return $order;
        });

        return redirect()->route('checkout.complete', $order->id)
            ->with('success', 'Your order has been placed successfully.');
    }

    public function complete(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load('items');

        return view('pages.shop.complete', compact('order'));
    }

    public function myOrders()
    {
        $orders = Order::with('items')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pages.shop.my-orders', compact('orders'));
    }
}
