<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\ArtisanProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@mq.uz',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Categories
        $categories = [
            ['name' => 'Kuyish va Naqsh', 'icon' => '🔥', 'sub' => ['Yog\'ochga naqsh', 'Kulolchilik']],
            ['name' => 'To\'qish va Tikish', 'icon' => '🧵', 'sub' => ['Adras', 'Kashtachilik', 'Do\'ppido\'zlik']],
            ['name' => 'Zargarlik', 'icon' => '💎', 'sub' => ['Kumush buyumlar', 'Tilla taqinchoqlar']],
            ['name' => 'Milliy o\'yinchoqlar', 'icon' => '🧸', 'sub' => ['Qo\'g\'irchoqlar', 'Yog\'och o\'yinchoqlar']],
            ['name' => 'Pichoqchilik', 'icon' => '🔪', 'sub' => ['Chust pichoqlari', 'G\'ijduvon pichoqlari']],
        ];

        foreach ($categories as $cat) {
            $parent = Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'icon' => $cat['icon'],
            ]);

            foreach ($cat['sub'] as $sub) {
                Category::create([
                    'name' => $sub,
                    'slug' => Str::slug($sub),
                    'parent_id' => $parent->id,
                ]);
            }
        }

        // Artisans
        $artisans = [
            ['name' => 'Usta Abdulla', 'shop' => 'Abdulla Pichoqlari', 'spec' => 'Pichoqsoz'],
            ['name' => 'Malika opa', 'shop' => 'Milliy Liboslar', 'spec' => 'Tikuvchi'],
            ['name' => 'Sherzod Usta', 'shop' => 'G\'ijduvon Ceramics', 'spec' => 'Kulol'],
        ];

        foreach ($artisans as $art) {
            $user = User::create([
                'name' => $art['name'],
                'email' => Str::slug($art['name']) . '@artisan.uz',
                'password' => Hash::make('password'),
                'role' => 'artisan',
                'status' => 'active',
            ]);

            ArtisanProfile::create([
                'user_id' => $user->id,
                'shop_name' => $art['shop'],
                'specialty' => $art['spec'],
                'description' => 'Uzoq yillik tajribaga ega usta.',
                'status' => 'approved',
                'rating' => 4.8,
                'total_reviews' => 12,
                'approved_at' => now(),
            ]);

            // Products for each artisan
            for ($i = 1; $i <= 5; $i++) {
                $product = Product::create([
                    'user_id' => $user->id,
                    'category_id' => Category::whereNotNull('parent_id')->inRandomOrder()->first()->id,
                    'name' => $art['spec'] . ' mahsuloti ' . $i,
                    'slug' => Str::slug($art['spec'] . ' mahsuloti ' . $i . '-' . Str::random(3)),
                    'description' => 'Sifatli milliy mahsulot.',
                    'short_description' => 'Qo\'lda tayyorlangan.',
                    'price' => rand(50000, 500000),
                    'quantity' => 10,
                    'is_active' => true,
                    'is_featured' => rand(0, 1),
                ]);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'demo/product.jpg',
                    'is_primary' => true,
                ]);
            }
        }

        // Standard User
        User::create([
            'name' => 'Test User',
            'email' => 'user@mq.uz',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
        ]);
    }
}
