<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Cart;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display the home page
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $selectedSlug = $request->get('kategori');
        $kategoriList = Kategori::orderBy('nama_kategori')
            ->get(['kategori_id','nama_kategori','slug']);

        $query = Produk::with('kategori')
            ->where('is_active', true);

        if (!empty($selectedSlug)) {
            $query->whereHas('kategori', function ($q) use ($selectedSlug) {
                $q->where('slug', $selectedSlug);
            });
        }

        $produk = $query->orderBy('created_at', 'desc')
            ->take(8)
            ->get()
            ->map(function ($produk) {
                return [
                    'id' => $produk->produk_id,
                    'name' => $produk->nama_produk,
                    'kategori' => $produk->kategori->nama_kategori ?? 'Semua',
                    'kategori_name' => $produk->kategori->nama_kategori ?? 'Semua Kategori',
                    'price' => $produk->harga,
                    'stock' => $produk->stok,
                    'discount' => 0,
                    'price_after_discount' => $produk->harga,
                    'image' => $produk->image_url,  // UPDATED: Gunakan image_url accessor
                    'rating' => (int)($produk->rating ?? 5),
                    'reviews_count' => (int)($produk->total_reviews ?? 0),
                ];
            });

        $brands = collect();

        $cartCount = 0;
        if (Auth::check() && Schema::hasTable('cart')) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $cartCount = $user->cartItems()->count();
        }

        return view('home', compact('produk', 'brands', 'cartCount', 'kategoriList', 'selectedSlug'));
    }

    /**
     * Subscribe to newsletter
     */
    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email'
        ]);

        \App\Models\NewsletterSubscriber::create([
            'email' => $request->email,
            'subscribed_at' => now()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Terima kasih! Anda telah berlangganan newsletter kami.'
            ]);
        }

        return redirect()->back()->with('success', 'Terima kasih! Anda telah berlangganan newsletter kami.');
    }

    /**
     * Get cart count for AJAX requests
     */
    public function getCartCount()
    {
        $count = 0;
        if (Auth::check() && Schema::hasTable('cart')) {
            $count = Cart::getTotalItems();
        }
        return response()->json(['count' => $count]);
    }

    /**
     * Display cart page
     */
    public function cart()
    {
        if (Auth::check() && Schema::hasTable('cart')) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $cartItems = $user->cartItems()->with('produk')->get();
            return view('cart.index', compact('cartItems'));
        }

        return redirect()->route('login')->with('error', 'Silakan login untuk melihat keranjang');
    }
}