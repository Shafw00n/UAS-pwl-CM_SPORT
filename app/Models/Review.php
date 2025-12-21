<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'produk_id',
        'order_id',
        'rating',
        'comment',
        'images',
        'is_verified_purchase'
    ];

    protected $casts = [
        'is_verified_purchase' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');
    }
}
