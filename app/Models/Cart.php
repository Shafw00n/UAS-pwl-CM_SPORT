<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'session_id',
        'produk_id',
        'quantity',
        'ukuran',
        'warna',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');
    }

    public function getSubtotalAttribute(): int
    {
        $price = (int) ($this->produk?->harga ?? 0);
        $promoPrice = $this->produk?->getLowestPromoPrice();
        
        if ($promoPrice && $promoPrice < $price) {
            $price = $promoPrice;
        }

        return $price * (int) $this->quantity;
    }

    public static function getTotalItems(): int
    {
        return static::where('user_id', auth()->id())->sum('quantity');
    }

    public static function getCartTotal(): int
    {
        $items = static::where('user_id', auth()->id())->with('produk')->get();
        return (int) $items->sum(fn($item) => $item->subtotal);
    }

    public static function clearCart(): void
    {
        static::where('user_id', auth()->id())->delete();
    }
}
