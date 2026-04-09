<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomerAuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $guestCart = (array) $request->session()->get('cart', []);

        $user = User::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
            'is_admin' => 0,
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        $this->mergeSessionCartIntoUser($guestCart, $user->id);
        $request->session()->forget('cart');

        $redirect = $request->input('redirect_to');

        if ($redirect) {
            return redirect($redirect)
                ->with('openAccountPanel', 1)
                ->with('accountTab', 'account');
        }

        return redirect()->route('home')
            ->with('openAccountPanel', 1)
            ->with('accountTab', 'account');
    }

    public function login(Request $request)
    {
        $creds = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = (bool) $request->input('remember');
        $guestCart = (array) $request->session()->get('cart', []);

        if (!Auth::attempt($creds, $remember)) {
            return back()
                ->withInput(['email' => $request->email])
                ->with('openAccountPanel', 1)
                ->with('accountTab', 'login')
                ->withErrors(['login' => 'Invalid email or password.']);
        }

        $request->session()->regenerate();

        if (Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        $this->mergeSessionCartIntoUser($guestCart, Auth::id());
        $request->session()->forget('cart');

        $redirect = $request->input('redirect_to');

        if ($redirect) {
            return redirect($redirect)
                ->with('openAccountPanel', 1)
                ->with('accountTab', 'account');
        }

        return redirect()->route('home')
            ->with('openAccountPanel', 1)
            ->with('accountTab', 'account');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function mergeSessionCartIntoUser(array $guestCart, int $userId): void
    {
        foreach ($guestCart as $productId => $qty) {
            $qty = max(1, min(10, (int) $qty));

            $existing = CartItem::where('user_id', $userId)
                ->where('product_id', (int) $productId)
                ->first();

            if ($existing) {
                $existing->quantity = min(10, (int) $existing->quantity + $qty);
                $existing->save();
            } else {
                CartItem::create([
                    'user_id' => $userId,
                    'product_id' => (int) $productId,
                    'quantity' => $qty,
                ]);
            }
        }
    }
}
