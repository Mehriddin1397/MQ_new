<?php

namespace App\Http\Controllers;

use App\Models\ArtisanProfile;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArtisanDashboardController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $productsCount = $user->products()->count();
        $ordersCount = OrderItem::where('artisan_id', $user->id)->count();
        $totalSales = OrderItem::where('artisan_id', $user->id)
            ->whereHas('order', fn($q) => $q->where('status', 'delivered'))
            ->sum('subtotal');
        $recentOrders = OrderItem::where('artisan_id', $user->id)
            ->with('order.user', 'product.images')
            ->latest()
            ->take(5)
            ->get();

        return view('artisan.dashboard', compact('user', 'productsCount', 'ordersCount', 'totalSales', 'recentOrders'));
    }

    public function pending()
    {
        return view('artisan.pending');
    }

    // Products
    public function products()
    {
        $products = auth()->user()->products()
            ->with('images', 'category')
            ->latest()
            ->paginate(12);

        return view('artisan.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('artisan.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'quantity' => 'required|integer|min:0',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $product = Product::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(5),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'quantity' => $request->quantity,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'is_primary' => $i === 0,
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('artisan.products')
            ->with('success', 'Mahsulot muvaffaqiyatli yaratildi!');
    }

    public function editProduct(Product $product)
    {
        $this->authorizeProduct($product);
        $categories = Category::active()->orderBy('name')->get();
        $product->load('images');
        return view('artisan.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'quantity' => 'required|integer|min:0',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $product->update($request->only(
            'name',
            'category_id',
            'description',
            'short_description',
            'price',
            'discount_price',
            'quantity'
        ));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'sort_order' => $product->images()->count() + $i,
                ]);
            }
        }

        return redirect()->route('artisan.products')
            ->with('success', 'Mahsulot yangilandi!');
    }

    public function deleteProduct(Product $product)
    {
        $this->authorizeProduct($product);
        $product->delete();
        return back()->with('success', 'Mahsulot o\'chirildi!');
    }

    // Orders
    public function orders()
    {
        $orderItems = OrderItem::where('artisan_id', auth()->id())
            ->with('order.user', 'product.images')
            ->latest()
            ->paginate(15);

        return view('artisan.orders.index', compact('orderItems'));
    }

    public function updateOrderStatus(Request $request, OrderItem $orderItem)
    {
        if ($orderItem->artisan_id !== auth()->id())
            abort(403);

        $request->validate([
            'status' => 'required|in:confirmed,processing,shipped,delivered,cancelled',
        ]);

        $orderItem->update(['status' => $request->status]);

        // Sync main order status
        $order = $orderItem->order;
        $allStatuses = $order->items()->pluck('status')->toArray();

        $newStatus = $order->status;

        if (collect($allStatuses)->every(fn($s) => $s === 'delivered')) {
            $newStatus = 'delivered';
        } elseif (collect($allStatuses)->every(fn($s) => $s === 'cancelled')) {
            $newStatus = 'cancelled';
        } elseif (collect($allStatuses)->contains(fn($s) => in_array($s, ['shipped', 'processing', 'confirmed']))) {
            // If any item is progressed beyond pending, the order is at least confirmed/processing
            if (collect($allStatuses)->every(fn($s) => in_array($s, ['shipped', 'delivered', 'cancelled']))) {
                $newStatus = 'shipped';
            } else {
                $newStatus = 'processing';
            }
        }

        if ($newStatus !== $order->status) {
            $order->update(['status' => $newStatus]);
        }

        return back()->with('success', 'Buyurtma holati yangilandi!');
    }

    // Discounts
    public function discounts()
    {
        $discounts = Discount::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('artisan.discounts.index', compact('discounts'));
    }

    public function createDiscount()
    {
        return view('artisan.discounts.create');
    }

    public function storeDiscount(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:discounts',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
        ]);

        Discount::create([
            'user_id' => auth()->id(),
            ...$request->only('name', 'code', 'type', 'value', 'min_order_amount', 'max_uses', 'starts_at', 'expires_at'),
        ]);

        return redirect()->route('artisan.discounts')
            ->with('success', 'Chegirma yaratildi!');
    }

    public function toggleDiscount(Discount $discount)
    {
        if ($discount->user_id !== auth()->id())
            abort(403);
        $discount->update(['is_active' => !$discount->is_active]);
        return back()->with('success', 'Chegirma holati yangilandi!');
    }

    // Analytics
    public function analytics()
    {
        $user = auth()->user();
        $totalSales = OrderItem::where('artisan_id', $user->id)
            ->whereHas('order', fn($q) => $q->where('status', 'delivered'))
            ->sum('subtotal');

        $monthlySales = OrderItem::where('artisan_id', $user->id)
            ->whereHas('order', fn($q) => $q->where('status', 'delivered'))
            ->whereMonth('created_at', now()->month)
            ->sum('subtotal');

        $totalOrders = OrderItem::where('artisan_id', $user->id)->count();
        $productsCount = $user->products()->count();
        $avgRating = $user->artisanProfile->rating;

        return view('artisan.analytics', compact(
            'totalSales',
            'monthlySales',
            'totalOrders',
            'productsCount',
            'avgRating'
        ));
    }

    // Profile
    public function profile()
    {
        $user = auth()->user();
        $user->load('artisanProfile');
        return view('artisan.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'shop_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'specialty' => 'nullable|string|max:255',
            'instagram' => 'nullable|url|max:255',
            'telegram' => 'nullable|string|max:255',
            'facebook' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
            'avatar' => 'nullable|image|max:2048',
            'banner_image' => 'nullable|image|max:4096',
        ]);

        $user = auth()->user();
        $userData = $request->only('name', 'phone');

        if ($request->hasFile('avatar')) {
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($userData);

        $profileData = $request->only(
            'shop_name',
            'description',
            'specialty',
            'instagram',
            'telegram',
            'facebook',
            'youtube',
            'website'
        );

        if ($request->hasFile('banner_image')) {
            $profileData['banner_image'] = $request->file('banner_image')->store('banners', 'public');
        }

        $user->artisanProfile->update($profileData);

        return back()->with('success', 'Profil yangilandi!');
    }

    private function authorizeProduct(Product $product): void
    {
        if ($product->user_id !== auth()->id())
            abort(403);
    }
}
