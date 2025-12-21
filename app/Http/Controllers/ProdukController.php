<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    /**
     * Display all products
     */
    public function index(Request $request)
    {
        $query = Produk::query()->with('kategori');

        if ($request->filled('kategori')) {
            $slugs = (array) $request->input('kategori');
            $query->whereHas('kategori', function ($q) use ($slugs) {
                $q->whereIn('slug', $slugs);
            });
        }

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_produk', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('kategori', function ($k) use ($searchTerm) {
                      $k->where('nama_kategori', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        $rangeQuery = clone $query;
        $minHarga = (int) ($rangeQuery->min('harga') ?? 0);
        $maxHarga = (int) ($rangeQuery->max('harga') ?? 0);

        $minInput = $request->input('min_price');
        $maxInput = $request->input('max_price');
        if ($minInput !== null && $maxInput !== null && $minInput !== '' && $maxInput !== '' && $minInput > $maxInput) {
            $tmp = $minInput; $minInput = $maxInput; $maxInput = $tmp;
        }
        if ($minInput !== null && $minInput !== '') {
            $query->where('harga', '>=', $minInput);
        }
        if ($maxInput !== null && $maxInput !== '') {
            $query->where('harga', '<=', $maxInput);
        }

        $ratings = (array) $request->get('rating', []);
        if (!empty($ratings)) {
            $threshold = min(array_map('intval', $ratings));
            $query->where('rating', '>=', $threshold);
        }

        $sortBy = $request->get('sort', 'terbaru');
        switch ($sortBy) {
            case 'harga-terendah':
                $query->orderBy('harga', 'ASC');
                break;
            case 'harga-tertinggi':
                $query->orderBy('harga', 'DESC');
                break;
            case 'terlaris':
                $query->orderBy('terjual', 'DESC');
                break;
            case 'rating':
                $query->orderBy('rating', 'DESC');
                break;
            default:
                $query->orderBy('created_at', 'DESC');
        }

        $produk = $query->paginate(24)->withQueryString();

        return view('produk.index', compact('produk', 'minHarga', 'maxHarga'));
    }
    
    /**
     * Display products by category
     */
    public function kategori(Request $request, $slug)
    {
        $kategori = Kategori::where('slug', $slug)->first();
        
        if (!$kategori) {
            abort(404, 'Kategori tidak ditemukan');
        }
        $query = Produk::query()->with('kategori');

        $selectedCats = (array) $request->get('kategori', []);
        if (!empty($selectedCats)) {
            $query->whereHas('kategori', function ($q) use ($selectedCats) {
                $q->whereIn('slug', $selectedCats);
            });
        } else {
            $query->whereHas('kategori', function ($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        $rangeQuery = clone $query;
        $minHarga = (int) ($rangeQuery->min('harga') ?? 0);
        $maxHarga = (int) ($rangeQuery->max('harga') ?? 0);

        $minInput = $request->get('min_price');
        $maxInput = $request->get('max_price');
        if ($minInput !== null && $maxInput !== null && $minInput !== '' && $maxInput !== '' && $minInput > $maxInput) {
            $tmp = $minInput; $minInput = $maxInput; $maxInput = $tmp;
        }
        if ($minInput !== null && $minInput !== '') {
            $query->where('harga', '>=', $minInput);
        }
        if ($maxInput !== null && $maxInput !== '') {
            $query->where('harga', '<=', $maxInput);
        }

        $ratings = (array) $request->get('rating', []);
        if (!empty($ratings)) {
            $threshold = min(array_map('intval', $ratings));
            $query->where('rating', '>=', $threshold);
        }

        $sortBy = $request->get('sort', 'terbaru');
        switch ($sortBy) {
            case 'harga-terendah':
                $query->orderBy('harga', 'ASC');
                break;
            case 'harga-tertinggi':
                $query->orderBy('harga', 'DESC');
                break;
            case 'terlaris':
                $query->orderBy('terjual', 'DESC');
                break;
            case 'rating':
                $query->orderBy('rating', 'DESC');
                break;
            default:
                $query->orderBy('created_at', 'DESC');
        }

        $produk = $query->paginate(24)->withQueryString();

        return view('produk.index', compact('produk', 'kategori', 'minHarga', 'maxHarga'));
    }
    
    /**
     * Display single product detail
     */
    public function show($id)
    {
        $produk = \App\Models\Produk::with(['kategori', 'reviews.user'])->find($id);
        
        if (!$produk) {
            abort(404, 'Produk tidak ditemukan');
        }
        
        // Get related products (same category)
        $relatedProduk = \App\Models\Produk::where('kategori_id', $produk->kategori_id)
            ->where('produk_id', '!=', $id)
            ->limit(8)
            ->get();
            
        // Check if user can review
        $userCanReview = false;
        $reviewableOrderId = null;
        
        if (Auth::check()) {
            $userId = Auth::id();
            $orders = \App\Models\Order::where('user_id', $userId)
                ->where('status', 'selesai')
                ->whereHas('items', function($q) use ($id) {
                    $q->where('produk_id', $id);
                })
                ->get();
                
            foreach($orders as $order) {
                $hasReview = \App\Models\Review::where('user_id', $userId)
                    ->where('produk_id', $id)
                    ->where('order_id', $order->order_id)
                    ->exists();
                    
                if (!$hasReview) {
                    $userCanReview = true;
                    $reviewableOrderId = $order->order_id;
                    break;
                }
            }
        }
        
        return view('produk.detail', compact('produk', 'relatedProduk', 'userCanReview', 'reviewableOrderId'));
    }
    
    /**
     * Search products
     */
    public function search(Request $request)
    {
        $searchTerm = $request->get('q', '');
        if (empty($searchTerm)) {
            return redirect()->route('produk.all');
        }

        $query = Produk::query()->with('kategori');
        $query->where(function ($q) use ($searchTerm) {
            $q->where('nama_produk', 'LIKE', "%{$searchTerm}%")
              ->orWhere('deskripsi', 'LIKE', "%{$searchTerm}%");
        })->orWhereHas('kategori', function ($q) use ($searchTerm) {
            $q->where('nama_kategori', 'LIKE', "%{$searchTerm}%");
        });

        $produk = $query->orderBy('created_at', 'DESC')
            ->paginate(24)
            ->withQueryString();

        return view('produk.index', compact('produk', 'searchTerm'));
    }
    
    /**
     * Add product to cart (AJAX)
     */
    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login untuk menambahkan ke keranjang'
            ], 401);
        }
        $request->validate([
            'produk_id' => 'required|exists:produk,produk_id',
            'quantity' => 'integer|min:1'
        ]);
        
        $produkId = $request->produk_id;
        $quantity = $request->get('quantity', 1);
        
        // Get product details
        $produk = DB::table('produk')->where('produk_id', $produkId)->first();
        
        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
        
        // Simpan ke tabel cart (menggunakan model)
        $cartItem = \App\Models\Cart::where('user_id', Auth::id())
            ->where('produk_id', $produkId)
            ->first();

        if ($cartItem) {
            $newQty = (int)$cartItem->quantity + (int)$quantity;
            $cartItem->update(['quantity' => $newQty]);
        } else {
            \App\Models\Cart::create([
                'user_id' => Auth::id(),
                'produk_id' => $produkId,
                'quantity' => (int)$quantity,
            ]);
        }

        $count = \App\Models\Cart::getTotalItems();
        $total = \App\Models\Cart::getCartTotal();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'cart_count' => $count,
            'cart_total' => $total
        ]);
    }
    
    /**
     * Submit rating for a product
     */
    public function rate(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk memberi rating');
        }
        $request->validate([
            'rating' => ['required','integer','min:1','max:5'],
        ]);
        
        $produk = DB::table('produk')->where('produk_id', $id)->first();
        if (!$produk) {
            return back()->with('error', 'Produk tidak ditemukan');
        }
        
        DB::table('reviews')->insert([
            'user_id' => Auth::id(),
            'produk_id' => $id,
            'rating' => (int) $request->rating,
            'comment' => null,
            'images' => null,
            'is_verified_purchase' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $currentTotal = (int) ($produk->total_reviews ?? 0);
        $currentAvg = (float) ($produk->rating ?? 0);
        $newTotal = $currentTotal + 1;
        $newAvg = round((($currentAvg * $currentTotal) + (int)$request->rating) / max(1, $newTotal), 2);
        
        DB::table('produk')
            ->where('produk_id', $id)
            ->update([
                'rating' => $newAvg,
                'total_reviews' => $newTotal,
                'updated_at' => now(),
            ]);
        
        return back()->with('success', 'Terima kasih atas rating Anda');
    }
    
    /**
     * Add product to wishlist (AJAX)
     */
    public function addToWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ], 401);
        }
        
        $request->validate([
            'produk_id' => 'required|exists:produk,produk_id'
        ]);
        
        $userId = Auth::id();
        $produkId = $request->produk_id;
        
        // Check if already in wishlist
        $exists = DB::table('wishlist')
            ->where('user_id', $userId)
            ->where('produk_id', $produkId)
            ->exists();
        
        if ($exists) {
            // Remove from wishlist
            DB::table('wishlist')
                ->where('user_id', $userId)
                ->where('produk_id', $produkId)
                ->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Produk dihapus dari wishlist',
                'action' => 'removed'
            ]);
        } else {
            // Add to wishlist
            DB::table('wishlist')->insert([
                'user_id' => $userId,
                'produk_id' => $produkId,
                'created_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Produk ditambahkan ke wishlist',
                'action' => 'added'
            ]);
        }
    }
}
