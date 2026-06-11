<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product.images')
            ->get();

        $total = $cartItems->sum('subtotal');

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate(['quantity' => 'nullable|integer|min:1|max:99']);

        $cart = Cart::firstOrNew([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        $cart->quantity = $cart->exists
            ? $cart->quantity + ($request->quantity ?? 1)
            : ($request->quantity ?? 1);

        $cart->save();

        return back()->with('success', 'Mahsulot savatga qo\'shildi!');
    }

    public function update(Request $request, Cart $cart)
    {
        $this->authorize('update', $cart);
        $request->validate(['quantity' => 'required|integer|min:1|max:99']);

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Savat yangilandi!');
    }

    public function remove(Cart $cart)
    {
        $this->authorize('delete', $cart);
        $cart->delete();

        return back()->with('success', 'Mahsulot savatdan o\'chirildi!');
    }

    public function clear()
    {
        Cart::where('user_id', auth()->id())->delete();
        return back()->with('success', 'Savat tozalandi!');
    }
}
