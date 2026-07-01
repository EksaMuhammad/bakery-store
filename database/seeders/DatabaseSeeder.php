<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Admin User
        User::updateOrCreate(
            ['email' => 'admin@sweetcrust.com'],
            [
                'name' => 'Admin SweetCrust',
                'password' => Hash::make('admin123'),
            ]
        );

        // Seed Products
        $products = [
            [
                'name' => 'Chocolate Croissant',
                'slug' => 'chocolate-croissant',
                'description' => 'Croissant premium khas Prancis dengan lapisan luar yang renyah dan berongga (flaky), diisi dengan cokelat hitam Belgia premium yang meleleh saat dipanaskan.',
                'category' => 'pastries',
                'price' => 25000,
                'stock' => 15,
                'image' => 'croissant.png',
                'ingredients' => 'Tepung terigu protein tinggi, mentega Prancis (AOP butter), cokelat hitam Belgia 60%, ragi, gula tebu, garam laut.',
                'allergens' => 'Gluten, Susu/Laktosa',
            ],
            [
                'name' => 'Almond Croissant',
                'slug' => 'almond-croissant',
                'description' => 'Croissant klasik dengan isian frangipane (krim almond manis) di dalamnya dan taburan irisan almond renyah serta gula halus di atasnya.',
                'category' => 'pastries',
                'price' => 28000,
                'stock' => 10,
                'image' => 'croissant.png',
                'ingredients' => 'Tepung terigu, mentega Prancis, kacang almond, gula, telur, rum esens (non-alkohol), garam.',
                'allergens' => 'Gluten, Telur, Susu/Laktosa, Kacang-kacangan',
            ],
            [
                'name' => 'Strawberry Shortcake',
                'slug' => 'strawberry-shortcake',
                'description' => 'Kue spons vanilla yang sangat lembut, dilapisi dengan krim kocok segar (fresh whipped cream) yang ringan dan potongan buah stroberi lokal segar yang manis-asam.',
                'category' => 'cakes',
                'price' => 45000,
                'stock' => 8,
                'image' => 'strawberry-cake.png',
                'ingredients' => 'Tepung kue premium, telur ayam kampung, fresh double cream, buah stroberi segar, ekstrak vanilla madagaskar, gula kastor.',
                'allergens' => 'Gluten, Telur, Susu/Laktosa',
            ],
            [
                'name' => 'Classic Sourdough Bread',
                'slug' => 'classic-sourdough',
                'description' => 'Roti panggang artisan tradisional dengan kulit luar yang renyah cokelat keemasan dan bagian dalam yang kenyal lembut berongga, difermentasi alami selama 24 jam.',
                'category' => 'breads',
                'price' => 38000,
                'stock' => 12,
                'image' => 'sourdough.png',
                'ingredients' => 'Tepung gandum utuh organik (unbleached flour), air murni, ragi alami (sourdough starter), garam laut alami.',
                'allergens' => 'Gluten',
            ],
            [
                'name' => 'Whole Wheat Sourdough',
                'slug' => 'whole-wheat-sourdough',
                'description' => 'Variasi roti sourdough tradisional yang dibuat dengan 100% gandum utuh organik, kaya serat, sangat sehat, dan memiliki rasa gandum panggang yang kuat.',
                'category' => 'breads',
                'price' => 42000,
                'stock' => 6,
                'image' => 'sourdough.png',
                'ingredients' => 'Tepung gandum utuh merah (whole wheat flour), air murni, sourdough starter alami, garam laut.',
                'allergens' => 'Gluten',
            ],
            [
                'name' => 'Chocolate Chip Cookies',
                'slug' => 'chocolate-chip-cookies',
                'description' => 'Kue kering panggang bergaya New York yang tebal dengan pinggiran renyah dan bagian tengah yang lembut nan kenyal (chewy), dipenuhi potongan cokelat susu dan taburan garam laut.',
                'category' => 'cookies',
                'price' => 18000,
                'stock' => 20,
                'image' => 'cookies.png',
                'ingredients' => 'Brown butter, gula aren organik, telur, tepung gandum, cokelat keping (milk chocolate chips), sea salt flakes.',
                'allergens' => 'Gluten, Telur, Susu/Laktosa',
            ],
            [
                'name' => 'Double Chocolate Cookies',
                'slug' => 'double-chocolate-cookies',
                'description' => 'Untuk para pecinta cokelat sejati: cookies cokelat pekat dengan adonan cocoa premium dan potongan cokelat hitam Belgia yang meleleh sempurna.',
                'category' => 'cookies',
                'price' => 20000,
                'stock' => 18,
                'image' => 'cookies.png',
                'ingredients' => 'Brown butter, bubuk kakao hitam premium, gula aren, tepung gandum, cokelat hitam Belgia 70%, sea salt.',
                'allergens' => 'Gluten, Telur, Susu/Laktosa',
            ],
        ];

        foreach ($products as $prod) {
            Product::updateOrCreate(
                ['slug' => $prod['slug']],
                $prod
            );
        }
    }
}
