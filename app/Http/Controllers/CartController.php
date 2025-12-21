<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Produk;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the cart
     */
    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('produk')
            ->get();

        $subtotal = $cartItems->sum('subtotal');
        $tax = $subtotal * 0.11; // 11% tax
        $shipping = $subtotal > 100000 ? 0 : 15000; // Free shipping over 100k
        $total = $subtotal + $tax + $shipping;

        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request, $produkId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk menambahkan ke keranjang');
        }
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
            'ukuran' => 'nullable|string',
            'warna' => 'nullable|string'
        ]);

        $produk = Produk::findOrFail($produkId);

        // Check stock + promo stock quota
        $qty = (int) ($request->input('quantity', 1));
        $bestPromo = $produk->activePromos()
            ->orderBy('produk_promo.promo_price', 'asc')
            ->first();
        $promoStock = $bestPromo?->pivot?->stock;
        $allowedStock = $produk->stok;
        if ($bestPromo) {
            if ($promoStock !== null) {
                $allowedStock = (int) min($allowedStock, (int) $promoStock);
            }
        }
        if ($allowedStock < 1) {
            return back()->with('error', $bestPromo ? 'Stok promo habis' : 'Insufficient stock available');
        }

        // Check if item already exists in cart
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('produk_id', $produkId)
            ->where('ukuran', $request->ukuran)
            ->where('warna', $request->warna)
            ->first();

        // Total quantity of this product in cart (all variants) to enforce promo quota
        $existingTotalForProduct = Cart::where('user_id', auth()->id())
            ->where('produk_id', $produkId)
            ->sum('quantity');

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $qty;
            $newTotalForProduct = $existingTotalForProduct - $cartItem->quantity + $newQuantity;
            if ($newTotalForProduct > $allowedStock) {
                return back()->with('error', $bestPromo ? 'Melebihi kuota promo' : 'Cannot add more items. Stock limit reached');
            }
            
            $cartItem->update(['quantity' => $newQuantity]);
            return redirect()->route('cart')->with('success', 'Cart updated successfully');
        } else {
            // Create new cart item
            $newTotalForProduct = $existingTotalForProduct + $qty;
            if ($newTotalForProduct > $allowedStock) {
                return back()->with('error', $bestPromo ? 'Melebihi kuota promo' : 'Cannot add more items. Stock limit reached');
            }
            Cart::create([
                'user_id' => auth()->id(),
                'produk_id' => $produkId,
                'quantity' => $qty,
                'ukuran' => $request->ukuran,
                'warna' => $request->warna
            ]);
            
            return redirect()->route('cart')->with('success', 'Product added to cart');
        }
    }

    /**
     * Add item to cart via GET fallback (quantity=1)
     */
    public function addGet($produkId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk menambahkan ke keranjang');
        }
        $produk = Produk::findOrFail($produkId);
        $qty = 1;
        $bestPromo = $produk->activePromos()
            ->orderBy('produk_promo.promo_price', 'asc')
            ->first();
        $promoStock = $bestPromo?->pivot?->stock;
        $allowedStock = $produk->stok;
        if ($bestPromo) {
            if ($promoStock !== null) {
                $allowedStock = (int) min($allowedStock, (int) $promoStock);
            }
        }
        if ($allowedStock < 1) {
            return back()->with('error', $bestPromo ? 'Stok promo habis' : 'Insufficient stock available');
        }
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('produk_id', $produkId)
            ->whereNull('ukuran')
            ->whereNull('warna')
            ->first();
        $existingTotalForProduct = Cart::where('user_id', auth()->id())
            ->where('produk_id', $produkId)
            ->sum('quantity');
        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $qty;
            $newTotalForProduct = $existingTotalForProduct - $cartItem->quantity + $newQuantity;
            if ($newTotalForProduct > $allowedStock) {
                return back()->with('error', $bestPromo ? 'Melebihi kuota promo' : 'Cannot add more items. Stock limit reached');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $newTotalForProduct = $existingTotalForProduct + $qty;
            if ($newTotalForProduct > $allowedStock) {
                return back()->with('error', $bestPromo ? 'Melebihi kuota promo' : 'Cannot add more items. Stock limit reached');
            }
            Cart::create([
                'user_id' => auth()->id(),
                'produk_id' => $produkId,
                'quantity' => $qty,
            ]);
        }
        return redirect()->route('cart')->with('success', 'Product added to cart');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('user_id', auth()->id())->findOrFail($id);

        // Check stock + promo quota
        $produk = $cartItem->produk;
        $bestPromo = $produk?->activePromos()
            ->orderBy('produk_promo.promo_price', 'asc')
            ->first();
        $promoStock = $bestPromo?->pivot?->stock;
        $allowedStock = (int) ($produk?->stok ?? 0);
        if ($bestPromo && $promoStock !== null) {
            $allowedStock = (int) min($allowedStock, (int) $promoStock);
        }
        $existingTotalForProduct = Cart::where('user_id', auth()->id())
            ->where('produk_id', $cartItem->produk_id)
            ->sum('quantity');
        $newTotalForProduct = $existingTotalForProduct - $cartItem->quantity + (int) $request->quantity;
        if ($newTotalForProduct > $allowedStock) {
            return response()->json([
                'success' => false,
                'message' => $bestPromo ? 'Melebihi kuota promo' : 'Insufficient stock available'
            ], 400);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        $subtotal = Cart::getCartTotal();
        $tax = $subtotal * 0.11;
        $shipping = $subtotal > 100000 ? 0 : 15000;
        $total = $subtotal + $tax + $shipping;

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'itemSubtotal' => $cartItem->subtotal
            ]);
        }

        return back()->with('success', 'Cart updated successfully');
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $cartItem = Cart::where('user_id', auth()->id())->findOrFail($id);
        $cartItem->delete();

        return back()->with('success', 'Item removed from cart');
    }

    /**
     * Clear all items from cart
     */
    public function clear()
    {
        Cart::clearCart();

        return back()->with('success', 'Cart cleared successfully');
    }

    /**
     * Get cart count (AJAX)
     */
    public function count()
    {
        $count = Cart::getTotalItems();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Apply coupon code
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        // Add your coupon logic here
        // For now, just return error
        return back()->with('error', 'Invalid coupon code');
    }
}
