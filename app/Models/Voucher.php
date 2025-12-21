<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'description',
        'discount_type',
        'discount_value',
        'min_purchase',
        'max_discount',
        'start_date',
        'end_date',
        'is_active',
        'quota',
        'used_count'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'quota' => 'integer',
        'used_count' => 'integer',
        'discount_value' => 'integer',
        'min_purchase' => 'integer',
        'max_discount' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }

    public function getStatusAttribute(): string
    {
        if (!$this->is_active) return 'Nonaktif';
        if ($this->start_date > now()) return 'Akan Datang';
        if ($this->end_date < now()) return 'Berakhir';
        if ($this->quota > 0 && $this->used_count >= $this->quota) return 'Habis';
        return 'Aktif';
    }
}
