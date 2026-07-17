<?php

namespace App\Http\Controllers;

use App\Models\ArtisanProfile;
use App\Models\Category;
use App\Models\HelpVideo;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users' => User::where('role', 'user')->count(),
            'artisans' => User::where('role', 'artisan')->count(),
            'pendingArtisans' => ArtisanProfile::where('status', 'pending')->count(),
            'products' => Product::count(),
            'orders' => Order::count(),
            'revenue' => Order::where('status', 'delivered')->sum('total_amount'),
            'monthlyRevenue' => Order::where('status', 'delivered')
                ->whereMonth('created_at', now()->month)->sum('total_amount'),
        ];

        $recentOrders = Order::with('user')->latest()->take(10)->get();
        $pendingArtisans = User::where('role', 'artisan')
            ->whereHas('artisanProfile', fn($q) => $q->where('status', 'pending'))
            ->with('artisanProfile')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'pendingArtisans'));
    }

    // Users
    public function users(Request $request)
    {
        $query = User::withCount('orders')
            ->withSum(['orders as total_spent' => function ($q) {
                $q->where('status', 'delivered');
            }], 'total_amount');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function toggleUserStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'banned' : 'active',
        ]);

        return back()->with('success', 'Foydalanuvchi holati yangilandi!');
    }

    // Artisans
    public function artisans(Request $request)
    {
        $query = User::where('role', 'artisan')->with('artisanProfile');

        if ($request->filled('status')) {
            $query->whereHas('artisanProfile', fn($q) => $q->where('status', $request->status));
        }

        $artisans = $query->latest()->paginate(20)->withQueryString();

        return view('admin.artisans.index', compact('artisans'));
    }

    public function approveArtisan(User $artisan)
    {
        $artisan->artisanProfile->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Hunarmand tasdiqlandi!');
    }

    public function rejectArtisan(Request $request, User $artisan)
    {
        $request->validate(['rejection_reason' => 'nullable|string|max:500']);

        $artisan->artisanProfile->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Hunarmand rad etildi!');
    }

    // Products
    public function products(Request $request)
    {
        $query = Product::with('user', 'category', 'images');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $products = $query->latest()->paginate(20)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function toggleProduct(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return back()->with('success', 'Mahsulot holati yangilandi!');
    }

    // Categories
    public function categories()
    {
        $categories = Category::withCount('products')
            ->orderBy('sort_order')
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $data = $request->only('name', 'description', 'icon', 'parent_id');
        $data['slug'] = \Illuminate\Support\Str::slug($request->name);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return back()->with('success', 'Kategoriya yaratildi!');
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Kategoriya o\'chirildi!');
    }

    // Orders
    public function orders(Request $request)
    {
        $query = Order::with('user', 'items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }


    // Help videos
    public function helpVideos()
    {
        $videos = HelpVideo::latest()->paginate(20);

        return view('admin.help.index', compact('videos'));
    }

    public function storeHelpVideo(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'youtube_link' => 'required|url|max:500',
        ]);

        HelpVideo::create($request->only('name', 'youtube_link'));

        return back()->with('success', 'Video qo\'shildi!');
    }

    public function deleteHelpVideo(HelpVideo $helpVideo)
    {
        $helpVideo->delete();

        return back()->with('success', 'Video o\'chirildi!');
    }

    // Statistics
    public function statistics()
    {
        $isSqlite = config('database.default') === 'sqlite';
        $monthQuery = $isSqlite ? "strftime('%m', created_at)" : 'MONTH(created_at)';

        $monthlyOrders = Order::selectRaw("$monthQuery as month, COUNT(*) as count, SUM(total_amount) as total")
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topProducts = Product::orderByDesc('sales_count')->take(10)->get();
        $topArtisans = User::where('role', 'artisan')
            ->with('artisanProfile')
            ->withCount('products')
            ->orderByDesc('products_count')
            ->take(10)
            ->get();

        return view('admin.statistics', compact('monthlyOrders', 'topProducts', 'topArtisans'));
    }
}
