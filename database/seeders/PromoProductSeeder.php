<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promo;
use App\Models\Produk;

class PromoProductSeeder extends Seeder
{
    public function run()
    {
        $promo = Promo::first();
        if (!$promo) {
            $promo = Promo::create([
                'title' => 'Flash Sale',
                'description' => 'Diskon Kilat',
                'start_date' => now(),
                'end_date' => now()->addDays(5),
                'is_active' => true,
            ]);
        }

        $products = Produk::limit(5)->get();
        
        foreach ($products as $prod) {
            $promo->produk()->syncWithoutDetaching([
                $prod->produk_id => [
                    'promo_price' => $prod->harga * 0.7, // 30% off
                    'stock' => 50,
                    'start_date' => now(),
                    'end_date' => now()->addDays(rand(1, 3)), // Random end dates for variety
                    'is_active' => true
                ]
            ]);
            $this->command->info("Attached {$prod->nama_produk} to promo");
        }
    }
}
