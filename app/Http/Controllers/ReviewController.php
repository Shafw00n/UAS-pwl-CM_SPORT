<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Produk;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,produk_id',
            'order_id' => 'required|exists:orders,order_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $userId = Auth::id();
        
        // Verify purchase and completion
        $hasPurchased = Order::where('order_id', $request->order_id)
            ->where('user_id', $userId)
            ->where('status', 'selesai')
            ->whereHas('items', function($q) use ($request) {
                $q->where('produk_id', $request->produk_id);
            })
            ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'Validasi gagal: Pesanan harus selesai untuk memberikan ulasan.');
        }

        // Check if already reviewed
        $exists = Review::where('user_id', $userId)
            ->where('produk_id', $request->produk_id)
            ->where('order_id', $request->order_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah mengulas produk ini.');
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews', 'public');
        }

        DB::transaction(function() use ($request, $userId, $imagePath) {
            Review::create([
                'user_id' => $userId,
                'produk_id' => $request->produk_id,
                'order_id' => $request->order_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'images' => $imagePath,
                'is_verified_purchase' => true,
            ]);

            // Update Product Rating
            $avg = Review::where('produk_id', $request->produk_id)->avg('rating');
            $count = Review::where('produk_id', $request->produk_id)->count();
            
            DB::table('produk')
                ->where('produk_id', $request->produk_id)
                ->update([
                    'rating' => round($avg, 2),
                    'total_reviews' => $count,
                    'updated_at' => now(),
                ]);
        });

        return back()->with('success', 'Terima kasih! Ulasan Anda telah diterbitkan.');
    }
}
