<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'produk_id'; // 🔴 WAJIB
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'kategori_id',
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'gambar',
        'is_active',
    ];

    /**
     * =====================
     * RELASI KATEGORI
     * =====================
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * =====================
     * RELASI PROMO
     * =====================
     */
    public function promos()
    {
        return $this->belongsToMany(
            Promo::class,
            'produk_promo',
            'produk_id',
            'promo_id'
        )->withPivot([
            'promo_price',
            'stock',
            'start_date',
            'end_date',
            'is_active'
        ]);
    }

    public function activePromos()
    {
        return $this->promos()
            ->wherePivot('is_active', 1)
            ->wherePivot('start_date', '<=', now())
            ->wherePivot('end_date', '>=', now());
    }

    /**
     * =====================
     * HELPER PROMO
     * =====================
     */
    public function hasActivePromo(): bool
    {
        return $this->activePromos()->exists();
    }

    public function getLowestPromoPrice(): ?int
    {
        $promo = $this->activePromos()
            ->orderBy('produk_promo.promo_price', 'asc')
            ->first();

        return $promo?->pivot?->promo_price;
    }

    public function getDiscountPercentage(): int
    {
        $promoPrice = $this->getLowestPromoPrice();

        if ($promoPrice && $this->harga > 0) {
            return (int) round((($this->harga - $promoPrice) / $this->harga) * 100);
        }

        return 0;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'produk_id', 'produk_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'produk_id', 'produk_id');
    }

    public function getImageUrlAttribute(): string
    {
        $path = $this->gambar;
        if (!$path) {
            return asset('images/placeholder-produk.jpg');
        }
        $path = ltrim($path, '/');
        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }
        if (file_exists(public_path('storage/' . $path))) {
            return asset('storage/' . $path);
        }
        if (file_exists(public_path($path))) {
            return asset($path);
        }
        if (file_exists(public_path('template/img/' . $path))) {
            return asset('template/img/' . $path);
        }
        return asset('template/img/undraw_posting_photo.svg');
    }
}
