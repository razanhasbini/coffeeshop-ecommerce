<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if (! Auth::check()) {
            $cart = session('guest_cart', []);
            $productId = (int) $request->product_id;
            $cart[$productId] = ($cart[$productId] ?? 0) + (int) $request->quantity;
            session(['guest_cart' => $cart]);

            return response()->json(['success' => true, 'message' => 'Product added to cart!', 'cartCount' => array_sum($cart)]);
        }

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart!',
            'cartCount' => Cart::where('user_id', Auth::id())->sum('quantity'),
        ]);
    }

    public function viewCart()
    {
        $cartItems = Auth::check()
            ? Cart::with('product')->where('user_id', Auth::id())->get()
            : $this->guestCartItems();
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('cart', compact('cartItems', 'totalPrice'));
    }

    public function remove($id)
    {
        if (! Auth::check()) {
            $cart = session('guest_cart', []);
            unset($cart[(int) $id]);
            session(['guest_cart' => $cart]);
            $items = $this->guestCartItems();
        } else {
            Cart::where('user_id', Auth::id())->where('product_id', $id)->delete();
            $items = Cart::with('product')->where('user_id', Auth::id())->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart!',
            'totalPrice' => $items->sum(fn ($item) => $item->product->price * $item->quantity),
            'cartCount' => $items->sum('quantity'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if (! Auth::check()) {
            $cart = session('guest_cart', []);
            if (isset($cart[(int) $id])) $cart[(int) $id] = (int) $request->quantity;
            session(['guest_cart' => $cart]);
            $cartItems = $this->guestCartItems();
        } else {
            Cart::where('user_id', Auth::id())->where('product_id', $id)->update(['quantity' => $request->quantity]);
            $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        }
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'cart' => $cartItems,
            'totalPrice' => $totalPrice,
            'cartCount' => $cartItems->sum('quantity'),
        ]);
    }

    private function guestCartItems()
    {
        $guestCart = session('guest_cart', []);
        $products = Product::whereIn('id', array_keys($guestCart))->get()->keyBy('id');

        return collect($guestCart)->map(function ($quantity, $productId) use ($products) {
            if (! $products->has((int) $productId)) return null;
            $item = new Cart(['product_id' => (int) $productId, 'quantity' => (int) $quantity]);
            $item->setRelation('product', $products->get((int) $productId));
            return $item;
        })->filter()->values();
    }
}
