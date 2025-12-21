<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Voucher;

class PromoController extends Controller
{
    public function index()
    {
        $sort = request()->get('sort', 'ending_soon');
        $now = now();

        $products = \App\Models\Produk::select('produk.*')
            ->selectSub(function ($q) use ($now) {
                $q->from('produk_promo')
                    ->whereColumn('produk_promo.produk_id', 'produk.produk_id')
                    ->where('is_active', 1)
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now)
                    ->selectRaw('MIN(promo_price)');
            }, 'best_promo_price')
            ->selectSub(function ($q) use ($now) {
                $q->from('produk_promo')
                    ->whereColumn('produk_promo.produk_id', 'produk.produk_id')
                    ->where('is_active', 1)
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now)
                    ->selectRaw('MIN(end_date)');
            }, 'soonest_end_date')
            ->whereHas('activePromos')
            ->with(['activePromos', 'kategori'])
            ->when($sort === 'discount', function ($q) {
                $q->selectRaw("CASE WHEN harga > 0 AND best_promo_price IS NOT NULL THEN ROUND(((harga - best_promo_price)/harga)*100) ELSE 0 END AS discount_percent_calc")
                  ->orderBy('discount_percent_calc', 'DESC');
            }, function ($q) {
                $q->orderBy('soonest_end_date', 'ASC');
            })
            ->paginate(12)
            ->withQueryString();

        $products->getCollection()->transform(function($product) {
            $bestPromo = $product->activePromos->sortBy('pivot.promo_price')->first();
                
            if ($bestPromo) {
                $product->promo_price = $product->best_promo_price ?? $bestPromo->pivot->promo_price;
                $product->promo_end_date = isset($product->soonest_end_date)
                    ? \Carbon\Carbon::parse($product->soonest_end_date)
                    : \Carbon\Carbon::parse($bestPromo->pivot->end_date);
                $product->promo_stock = (int) ($bestPromo->pivot->stock ?? 0);
                $product->discount_percent = 0;
                if ($product->harga > 0) {
                    $basePromo = $product->promo_price ?? $bestPromo->pivot->promo_price;
                    $computed = (($product->harga - $basePromo) / $product->harga) * 100;
                    $product->discount_percent = max(0, min(100, round($computed)));
                }
            }
            return $product;
        });

        $vouchers = Voucher::active()
            ->orderBy('end_date', 'asc')
            ->get();

        return view('pages.promo', compact('products', 'vouchers', 'sort'));
    }

    public function detail($id)
    {
        $promo = Promo::with(['produk.kategori'])
            ->withCount('produk')
            ->findOrFail($id);

        return view('pages.promo_detail', compact('promo'));
    }
}
