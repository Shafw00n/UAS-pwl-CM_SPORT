<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promo extends Model
{
    use HasFactory;

    protected $table = 'promo';

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
    ];

    public function produk()
    {
        return $this->belongsToMany(
            Produk::class,
            'produk_promo',
            'promo_id',
            'produk_id'
        )->withPivot([
            'promo_price',
            'stock',
            'start_date',
            'end_date',
            'is_active'
        ]);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }

    public function getStatusAttribute(): string
    {
        if (!$this->is_active) return 'Nonaktif';
        if ($this->start_date > now()) return 'Akan Datang';
        if ($this->end_date < now()) return 'Berakhir';
        return 'Aktif';
    }
}
