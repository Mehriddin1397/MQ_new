<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
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
            'password' => Hash::make('MeRo13$97&'),
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
    }
}
