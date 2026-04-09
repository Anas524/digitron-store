<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopStateController extends Controller
{
    private function productCard(Product $p): array
    {
        $img = $p->image ?? $p->thumb ?? $p->cover ?? null;

        if (!$img) {
            $imgUrl = asset('images/placeholder.png');
        } elseif (str_starts_with($img, 'http')) {
            $imgUrl = $img;
        } elseif (str_starts_with($img, 'storage/') || str_starts_with($img, '/storage/')) {
            $imgUrl = asset(ltrim($img, '/'));
        } else {
            // handles "products/xxx.jpg" from disk('public')
            $imgUrl = asset('storage/' . ltrim($img, '/'));
            // OR you can use: $imgUrl = Storage::disk('public')->url($img);
        }

        return [
            'id' => $p->id,
            'name' => $p->name,
            'price' => (float)($p->price ?? 0),
            'image' => $imgUrl,
            'slug' => $p->slug ?? null,
        ];
    }

    public function state(Request $request)
    {
        $cart = ['items' => [], 'count' => 0, 'subtotal' => 0.0];
        $wish = ['items' => [], 'count' => 0];

        if (Auth::check()) {
            $items = CartItem::with('product')->where('user_id', Auth::id())->get();
            foreach ($items as $it) {
                if (!$it->product) continue;
                $p = $this->productCard($it->product);
                $cart['items'][] = ['key' => (string)$it->product_id, 'product' => $p, 'qty' => (int)$it->quantity];
                $cart['count'] += (int)$it->quantity;
                $cart['subtotal'] += $p['price'] * (int)$it->quantity;
            }

            $witems = WishlistItem::with('product')->where('user_id', Auth::id())->get();
            foreach ($witems as $w) {
                if (!$w->product) continue;
                $wish['items'][] = $this->productCard($w->product);
            }
            $wish['count'] = count($wish['items']);
        } else {
            // Guest cart in session
            $sess = $request->session()->get('cart', []); // [product_id => qty]
            $products = Product::whereIn('id', array_keys($sess))->get()->keyBy('id');
            foreach ($sess as $pid => $qty) {
                $pModel = $products->get((int)$pid);
                if (!$pModel) continue;
                $p = $this->productCard($pModel);
                $qty = (int)$qty;
                $cart['items'][] = ['key' => (string)$pid, 'product' => $p, 'qty' => $qty];
                $cart['count'] += $qty;
                $cart['subtotal'] += $p['price'] * $qty;
            }
            // Guest wishlist optional (keep simple: require login)
        }

        return response()->json(['ok' => true, 'cart' => $cart, 'wishlist' => $wish, 'auth' => Auth::check()]);
    }

    public function cartAdd(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $pid = (int)$data['product_id'];
        $qty = (int)($data['qty'] ?? 1);

        if (Auth::check()) {
            $row = CartItem::firstOrNew(['user_id' => Auth::id(), 'product_id' => $pid]);
            $row->quantity = min(99, ((int)$row->quantity ?: 0) + $qty);
            $row->save();
        } else {
            $cart = $request->session()->get('cart', []);
            $cart[$pid] = min(99, ((int)($cart[$pid] ?? 0)) + $qty);
            $request->session()->put('cart', $cart);
        }

        return $this->state($request);
    }

    public function cartUpdate(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $pid = (int)$data['product_id'];
        $qty = (int)$data['qty'];

        if (Auth::check()) {
            CartItem::updateOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $pid],
                ['quantity' => $qty]
            );
        } else {
            $cart = $request->session()->get('cart', []);
            $cart[$pid] = $qty;
            $request->session()->put('cart', $cart);
        }

        return $this->state($request);
    }

    public function cartRemove(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $pid = (int)$data['product_id'];

        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->where('product_id', $pid)->delete();
        } else {
            $cart = $request->session()->get('cart', []);
            unset($cart[$pid]);
            $request->session()->put('cart', $cart);
        }

        return $this->state($request);
    }

    public function wishlistToggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['ok' => false, 'needsLogin' => true], 401);
        }

        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $pid = (int)$data['product_id'];

        $exists = WishlistItem::where('user_id', Auth::id())->where('product_id', $pid)->exists();
        if ($exists) {
            WishlistItem::where('user_id', Auth::id())->where('product_id', $pid)->delete();
        } else {
            WishlistItem::create(['user_id' => Auth::id(), 'product_id' => $pid]);
        }

        return $this->state($request);
    }
}
