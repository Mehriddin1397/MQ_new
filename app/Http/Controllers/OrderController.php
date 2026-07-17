<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items.product.images')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('items.product.images', 'items.artisan');
        return view('orders.show', compact('order'));
    }

    public function checkout()
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product.images', 'product.user')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Savat bo\'sh!');
        }

        $total = $cartItems->sum('subtotal');

        return view('orders.checkout', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:cash',
            'promo_code' => 'nullable|string|exists:discounts,code',
        ]);

        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Savat bo\'sh!');
        }

        $order = DB::transaction(function () use ($request, $cartItems) {
            $subtotal = $cartItems->sum('subtotal');
            $discountAmount = 0;

            if ($request->filled('promo_code')) {
                $discount = Discount::where('code', $request->promo_code)->first();
                if ($discount && $discount->isValid()) {
                    $discountAmount = $discount->calculateDiscount($subtotal);
                    $discount->increment('used_count');
                }
            }

            $total = max(0, $subtotal - $discountAmount);

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'total_amount' => $total,
                'discount_amount' => $discountAmount,
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'notes' => $request->notes,
                'payment_method' => $request->payment_method,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'artisan_id' => $item->product->user_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'discount_price' => $item->product->discount_price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ]);

                $item->product->decrement('quantity', $item->quantity);
                $item->product->increment('sales_count', $item->quantity);
            }

            Cart::where('user_id', auth()->id())->delete();

            return $order;
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'Buyurtma muvaffaqiyatli yaratildi!');
    }

    public function cancel(Order $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Faqat kutilayotgan buyurtmani bekor qilish mumkin!');
        }

        $order->update(['status' => 'cancelled']);
        return back()->with('success', 'Buyurtma bekor qilindi!');
    }

    public function applyPromo(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $discount = Discount::where('code', $request->code)->first();

        if (!$discount || !$discount->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Promokod xato yoki muddati o\'tgan'
            ]);
        }

        $cartItems = Cart::where('user_id', auth()->id())->get();
        $subtotal = $cartItems->sum('subtotal');

        if ($discount->min_order_amount && $subtotal < $discount->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal buyurtma miqdori: ' . number_format($discount->min_order_amount) . ' so\'m'
            ]);
        }

        $amount = $discount->calculateDiscount($subtotal);
        $newTotal = max(0, $subtotal - $amount);

        return response()->json([
            'success' => true,
            'message' => 'Promokod qabul qilindi!',
            'discount' => $amount,
            'discount_formatted' => number_format($amount, 0, '.', ' ') . ' so\'m',
            'new_total' => $newTotal,
            'new_total_formatted' => number_format($newTotal, 0, '.', ' ') . ' so\'m'
        ]);
    }
}
