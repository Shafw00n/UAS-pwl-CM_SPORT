<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id',
        'status',
        'subtotal',
        'tax',
        'shipping',
        'total',
        'payment_status',
        'payment_method',
        'payment_proof',
        'paid_at',
        'admin_note',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function getPaymentProofUrlAttribute(): ?string
    {
        if (!$this->payment_proof) return null;
        $path = ltrim($this->payment_proof, '/');
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        }
        if (file_exists(public_path($path))) {
            return asset($path);
        }
        if (file_exists(public_path('storage/'.$path))) {
            return asset('storage/'.$path);
        }
        return null;
    }
}
