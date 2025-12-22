<?php

use App\Models\Promo;
use App\Models\Produk;

$promo = Promo::find(1);

if (!$promo) {
    echo "Promo not found!\n";
    exit;
}

$existingIds = $promo->produk()->pluck('produk.produk_id')->toArray();
$products = Produk::whereNotIn('produk_id', $existingIds)
    ->inRandomOrder()
    ->take(8)
    ->get();

foreach($products as $p) {
    $discount = rand(10, 30);
    $promoPrice = $p->harga * (100 - $discount) / 100;
    
    $promo->produk()->attach($p->produk_id, [
        'promo_price' => $promoPrice,
        'stock' => rand(5, 20),
        'start_date' => $promo->start_date,
        'end_date' => $promo->end_date,
        'is_active' => true
    ]);
    
    echo "Attached {$p->nama_produk} to promo.\n";
}

echo "Done.\n";
