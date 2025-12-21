@extends('layouts.master')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="cart-page">
    <div class="container mx-auto px-4 py-8">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-6 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 mb-1">Keranjang Belanja</h1>
                <p class="text-gray-500">Kelola item belanjaan Anda sebelum checkout.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('produk.all') }}" class="btn-continue group">
                    <div class="icon-circle">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
                    </div>
                    <span>Lanjut Belanja</span>
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert-box success mb-6">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert-box error mb-6">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ session('error') }}
        </div>
        @endif

        @if($cartItems->count() > 0)
        <div class="cart-grid">
            <!-- Left Column: Cart Items -->
            <div class="cart-items-col">
                
                <div class="bundle-banner mb-6">
                    <div class="flex items-center gap-3">
                        <span class="bundle-icon">🎁</span>
                        <div>
                            <span class="bundle-title">Paket Diskon Tersedia</span>
                            <span class="bundle-desc">Beli 2 produk kategori sama, dapatkan potongan ekstra!</span>
                        </div>
                    </div>
                    <a href="{{ route('produk.all') }}" class="bundle-btn">Lihat Produk</a>
                </div>

                <div class="cart-list space-y-6">
                    @foreach($cartItems as $item)
                    <div class="cart-card group">
                        <!-- Product Image & Checkbox -->
                        <div class="cart-media">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="selected[]" value="{{ $item->id }}" checked>
                                <span class="checkmark"></span>
                            </label>
                            <div class="img-wrapper">
                                <img src="{{ $item->produk?->image_url ?? asset('images/placeholder-produk.jpg') }}" 
                                     alt="{{ $item->produk?->nama_produk ?? 'Produk' }}">
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div class="cart-details">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="product-title">
                                        @if($item->produk)
                                            <a href="{{ route('produk.show', $item->produk->produk_id) }}">{{ $item->produk->nama_produk }}</a>
                                        @else
                                            <span class="text-gray-400 italic">Produk tidak tersedia</span>
                                        @endif
                                    </h3>
                                    
                                    @if($item->size || $item->color)
                                    <div class="variant-badge">
                                        @if($item->size) <span>Size: {{ $item->size }}</span> @endif
                                        @if($item->color) <span>{{ $item->size ? '• ' : '' }}{{ $item->color }}</span> @endif
                                    </div>
                                    @endif

                                    @php($stok = (int)($item->produk?->stok ?? 0))
                                    @if($stok < $item->quantity)
                                        <div class="stock-status error">Stok tidak mencukupi (Sisa: {{ $stok }})</div>
                                    @elseif($stok > 0 && $stok < 5)
                                        <div class="stock-status warning">Stok menipis: sisa {{ $stok }}</div>
                                    @endif
                                </div>

                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="delete-btn" title="Hapus Item">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2"/></svg>
                                    </button>
                                </form>
                            </div>

                            <div class="cart-footer mt-4">
                                <div class="price-block">
                                    @php($harga = (int)($item->produk?->harga ?? 0))
                                    @php($promo = (int)($item->produk?->getLowestPromoPrice() ?? 0))
                                    
                                    @if($promo > 0 && $promo < $harga)
                                        <div class="price-discounted">
                                            <span class="original">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                                            <span class="badge">-{{ (int)($item->produk?->getDiscountPercentage() ?? 0) }}%</span>
                                        </div>
                                        <div class="price-final">Rp {{ number_format($promo, 0, ',', '.') }}</div>
                                    @else
                                        <div class="price-final">Rp {{ number_format($harga, 0, ',', '.') }}</div>
                                    @endif
                                </div>

                                <div class="actions-block">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="qty-control">
                                        @csrf @method('PUT')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $stok }}" class="qty-input">
                                        <button type="submit" class="qty-btn update" title="Update Jumlah">↻</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="cart-bottom-actions mt-8">
                    <form id="clearCartForm" action="{{ route('cart.clear') }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="button" class="clear-cart-btn" onclick="showClearModal()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-1 1-1h6c1 0 1 1 1 1v2"/></svg>
                            Kosongkan Keranjang
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Summary -->
            <div class="cart-summary-col">
                <div class="summary-card sticky top-24">
                    <h3 class="summary-title">Ringkasan Belanja</h3>
                    
                    <div class="summary-rows">
                        <div class="row">
                            <span>Total Item</span>
                            <span>{{ $cartItems->sum('quantity') }} Pcs</span>
                        </div>
                        <div class="row">
                            <span>Total Harga</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($cartItems->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="total-row">
                        <span>Total Belanja</span>
                        <span class="total-amount cart-total-amount">Rp {{ number_format($cartItems->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>

                    <form id="checkoutForm" method="GET" action="{{ route('checkout.index') }}">
                        <button type="submit" class="checkout-btn w-full">
                            Checkout Sekarang
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                        </button>
                    </form>
                    
                    <div class="secure-badge">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Transaksi Aman & Terenkripsi
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="9" cy="21" r="1"/>
                    <circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
            </div>
            <h2>Keranjang Anda Kosong</h2>
            <p>Wah, keranjang belanjaanmu masih kosong nih. Yuk isi dengan produk olahraga favoritmu!</p>
            <a href="{{ route('produk.all') }}" class="btn-shop">Mulai Belanja</a>
        </div>
        @endif
        
        <!-- Custom Modal -->
        <div id="clearModal" class="modal-overlay">
            <div class="modal-content bounce-in">
                <div class="modal-icon-wrapper">
                    <div class="modal-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h6a1 1 0 011 1v2M10 11v6M14 11v6" />
                        </svg>
                    </div>
                </div>
                <h3 class="modal-title">Kosongkan Keranjang?</h3>
                <p class="modal-desc">Tindakan ini tidak dapat dibatalkan. Semua item dalam keranjang Anda akan dihapus.</p>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="hideClearModal()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        Batal
                    </button>
                    <button type="button" class="btn-confirm" onclick="submitClearForm()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                        Ya, Hapus Semua
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --primary-red: #e60023;
        --dark-red: #b3001b;
        --bg-gray: #f8f9fa;
        --text-dark: #111827;
        --text-gray: #6b7280;
    }

    body {
        background-color: #fdfdfd;
        background-image: 
            radial-gradient(at 0% 0%, rgba(230, 0, 35, 0.03) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(20, 20, 20, 0.03) 0px, transparent 50%),
            linear-gradient(#f0f0f0 1.5px, transparent 1.5px), 
            linear-gradient(90deg, #f0f0f0 1.5px, transparent 1.5px);
        background-size: 100% 100%, 100% 100%, 40px 40px, 40px 40px;
        background-position: 0 0, 0 0, -1.5px -1.5px, -1.5px -1.5px;
    }

    .cart-page {
        min-height: 80vh;
    }

    .text-primary { color: var(--primary-red); }
    
    /* Alerts */
    .alert-box {
        padding: 1rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
    }
    .alert-box.success { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .alert-box.error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    /* Layout Grid */
    .cart-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    @media (min-width: 1024px) {
        .cart-grid { grid-template-columns: 2fr 1fr; }
    }

    /* Bundle Banner */
    .bundle-banner {
        background: white;
        border: 1px dashed var(--primary-red);
        border-radius: 16px;
        padding: 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        background-image: radial-gradient(#e60023 0.5px, transparent 0.5px);
        background-size: 10px 10px;
        background-color: rgba(255, 255, 255, 0.95);
    }
    .bundle-icon { font-size: 1.5rem; }
    .bundle-title { display: block; font-weight: 800; color: var(--primary-red); }
    .bundle-desc { font-size: 0.9rem; color: var(--text-gray); }
    .bundle-btn {
        background: var(--primary-red);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: 0.2s;
    }
    .bundle-btn:hover { background: var(--dark-red); }

    /* Cart Card */
    .cart-card {
        background: white;
        border-radius: 24px;
        padding: 1.75rem;
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
        display: flex;
        gap: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }
    .cart-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -5px rgba(0,0,0,0.12);
        border-color: rgba(230, 0, 35, 0.15);
    }
    
    .cart-media {
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
    }
    .img-wrapper {
        width: 120px;
        height: 120px;
        border-radius: 16px;
        overflow: hidden;
        background: #f3f4f6;
        box-shadow: inset 0 0 0 1px rgba(0,0,0,0.05);
    }
    .img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }
    .cart-card:hover .img-wrapper img {
        transform: scale(1.05);
    }

    /* Custom Checkbox */
    .custom-checkbox {
        position: relative;
        cursor: pointer;
        width: 24px;
        height: 24px;
        margin-top: 48px; /* Center vertically relative to image */
    }
    .custom-checkbox input { opacity: 0; cursor: pointer; }
    .checkmark {
        position: absolute;
        top: 0; left: 0;
        height: 24px; width: 24px;
        background-color: #f3f4f6;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        transition: 0.2s;
    }
    .custom-checkbox:hover input ~ .checkmark { border-color: #d1d5db; }
    .custom-checkbox input:checked ~ .checkmark { 
        background-color: var(--primary-red); 
        border-color: var(--primary-red);
    }
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        left: 7px; top: 3px;
        width: 6px; height: 12px;
        border: solid white;
        border-width: 0 2.5px 2.5px 0;
        transform: rotate(45deg);
    }
    .custom-checkbox input:checked ~ .checkmark:after { display: block; }

    /* Cart Details */
    .cart-details { flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
    .product-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        line-height: 1.4;
        letter-spacing: -0.01em;
    }
    .product-title a { text-decoration: none; color: inherit; transition: color 0.2s; }
    .product-title a:hover { color: var(--primary-red); }
    
    .variant-badge {
        display: inline-flex;
        align-items: center;
        background: #f8f9fa;
        padding: 0.35rem 0.85rem;
        border-radius: 8px;
        font-size: 0.9rem;
        color: var(--text-gray);
        font-weight: 600;
        margin-top: 0.5rem;
        border: 1px solid rgba(0,0,0,0.03);
    }
    
    .stock-status { font-size: 0.9rem; margin-top: 0.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
    .stock-status.error { color: #dc2626; }
    .stock-status.warning { color: #ea580c; }

    .delete-btn {
        color: #9ca3af;
        background: white;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        padding: 0.6rem;
        border-radius: 10px;
        transition: 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .delete-btn:hover { 
        color: #ef4444; 
        background: #fee2e2; 
        border-color: #fecaca;
        transform: scale(1.05);
    }

    /* Footer in Card */
    .cart-footer {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px dashed #f3f4f6;
    }
    .price-block { display: flex; flex-direction: column; }
    .price-final { font-size: 1.35rem; font-weight: 900; color: var(--text-dark); letter-spacing: -0.02em; }
    .price-discounted { display: flex; gap: 0.5rem; align-items: center; font-size: 0.95rem; margin-bottom: 4px; }
    .price-discounted .original { text-decoration: line-through; color: #9ca3af; }
    .price-discounted .badge { color: var(--primary-red); font-weight: 700; background: rgba(230,0,35,0.08); padding: 2px 6px; border-radius: 6px; font-size: 0.85rem; }

    /* Qty Control */
    .qty-control {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border: 2px solid transparent;
        border-radius: 12px;
        overflow: hidden;
        transition: 0.2s;
    }
    .qty-control:hover { border-color: #e5e7eb; background: white; }
    .qty-btn {
        background: transparent;
        border: none;
        width: 36px;
        height: 36px;
        font-weight: 700;
        color: var(--text-dark);
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    .qty-btn:hover:not(:disabled) { background: white; color: var(--primary-red); box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-radius: 8px; margin: 2px; height: 32px; width: 32px; }
    .qty-btn:disabled { color: #d1d5db; cursor: not-allowed; }
    .qty-input {
        width: 60px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 700;
        color: var(--text-dark);
        font-size: 1.05rem;
        outline: none;
    }

    /* Summary Card */
    .summary-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.05);
    }
    .summary-title { font-size: 1.35rem; font-weight: 800; margin-bottom: 1.5rem; color: var(--text-dark); letter-spacing: -0.01em; }
    .summary-rows .row { display: flex; justify-content: space-between; margin-bottom: 1.25rem; color: var(--text-gray); font-size: 1rem; font-weight: 500; }
    .divider { height: 1px; background: #e5e7eb; margin: 1.5rem 0; border: none; }
    .total-row { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; }
    .total-row span:first-child { font-weight: 700; color: var(--text-dark); font-size: 1.1rem; }
    .total-amount { font-size: 1.75rem; font-weight: 900; color: var(--primary-red); letter-spacing: -0.02em; line-height: 1; }
    
    .checkout-btn {
        width: 100%;
        background: var(--primary-red);
        color: white;
        padding: 1rem;
        border-radius: 12px;
        font-weight: 700;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.75rem;
        transition: 0.3s;
        border: none;
        cursor: pointer;
        box-shadow: 0 10px 20px -5px rgba(230, 0, 35, 0.4);
    }
    .checkout-btn:hover {
        background: var(--dark-red);
        transform: translateY(-2px);
        box-shadow: 0 15px 30px -5px rgba(230, 0, 35, 0.5);
    }
    .secure-badge {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
        font-size: 0.8rem;
        color: #9ca3af;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }
    .empty-icon {
        color: #d1d5db;
        margin-bottom: 1.5rem;
    }
    .empty-state h2 { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text-dark); }
    .empty-state p { color: var(--text-gray); margin-bottom: 2rem; max-width: 400px; margin-left: auto; margin-right: auto; }
    .btn-shop {
        display: inline-block;
        background: var(--text-dark);
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-shop:hover { background: black; transform: translateY(-2px); }

    .clear-cart-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #ef4444;
        font-weight: 600;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: 0.2s;
    }
    .clear-cart-btn:hover { background: #fee2e2; }

    /* Button Continue */
    .btn-continue {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.6rem 1.25rem;
        background-color: white;
        border: 2px solid #e5e7eb;
        border-radius: 99px; /* Pill shape */
        font-weight: 700;
        color: var(--text-dark);
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 5px rgba(0,0,0,0.03);
    }
    .icon-circle {
        width: 28px; height: 28px;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
        color: var(--text-gray);
    }
    .btn-continue:hover {
        border-color: var(--text-dark);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .btn-continue:hover .icon-circle {
        background: var(--text-dark);
        color: white;
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s;
        backdrop-filter: blur(5px);
    }
    .modal-overlay.active { display: flex; opacity: 1; }
    
    .modal-content {
        background: white;
        padding: 2.5rem 2rem;
        border-radius: 24px;
        width: 90%;
        max-width: 420px;
        text-align: center;
        transform: scale(0.95);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .modal-overlay.active .modal-content { transform: scale(1); opacity: 1; }

    .modal-icon-wrapper {
        display: inline-flex;
        padding: 12px;
        border-radius: 50%;
        background: #fee2e2;
        margin-bottom: 1.5rem;
        animation: pulse-red 2s infinite;
    }
    .modal-icon {
        width: 56px; height: 56px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ef4444;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    @keyframes pulse-red {
        0% { box-shadow: 0 0 0 0 rgba(254, 226, 226, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(254, 226, 226, 0); }
        100% { box-shadow: 0 0 0 0 rgba(254, 226, 226, 0); }
    }

    .modal-title { font-size: 1.5rem; font-weight: 800; color: #111827; margin-bottom: 0.75rem; }
    .modal-desc { color: #6b7280; margin-bottom: 2rem; line-height: 1.6; font-size: 1rem; }
    
    .modal-actions { 
        display: flex; 
        gap: 1rem; 
        justify-content: center;
    }
    .modal-actions button {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.95rem;
    }

    .btn-cancel {
        border: 1px solid #e5e7eb;
        background: white;
        color: #374151;
    }
    .btn-cancel:hover { background: #f9fafb; border-color: #d1d5db; }
    
    .btn-confirm {
        border: none;
        background: #ef4444;
        color: white;
        box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.3);
    }
    .btn-confirm:hover { 
        background: #dc2626; 
        transform: translateY(-1px);
        box-shadow: 0 6px 8px -1px rgba(239, 68, 68, 0.4);
    }

    @media (max-width: 640px) {
        .cart-card { flex-direction: column; gap: 1rem; }
        .cart-media { width: 100%; }
        .custom-checkbox { margin-top: 0; }
        .cart-details { width: 100%; }
        .img-wrapper { width: 80px; height: 80px; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Modal Functions
  window.showClearModal = function() {
      const modal = document.getElementById('clearModal');
      modal.classList.add('active');
  }
  
  window.hideClearModal = function() {
      const modal = document.getElementById('clearModal');
      modal.classList.remove('active');
  }
  
  window.submitClearForm = function() {
      document.getElementById('clearCartForm').submit();
  }
  
  // Close modal when clicking outside
  document.getElementById('clearModal').addEventListener('click', function(e) {
      if(e.target === this) hideClearModal();
  });

  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number).replace('Rp', 'Rp ');
  }

  function updateCart(itemId, quantity, inputEl) {
    // Disable input while updating to prevent spam
    inputEl.disabled = true;
    inputEl.style.opacity = '0.7';
    
    // Find related elements
    const form = inputEl.closest('[data-qty-form]');
    const minusBtn = form.querySelector('.minus');
    const plusBtn = form.querySelector('.plus');
    
    if(minusBtn) minusBtn.disabled = true;
    if(plusBtn) plusBtn.disabled = true;
    
    fetch(`/cart/update/${itemId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        inputEl.disabled = false;
        inputEl.style.opacity = '1';
        
        // Update buttons state
        if(minusBtn) minusBtn.disabled = quantity <= 1;
        if(plusBtn) plusBtn.disabled = quantity >= parseInt(inputEl.getAttribute('max') || 999);
        
        if (data.success) {
            // Update Cart Total
            document.querySelectorAll('.cart-total-amount').forEach(el => {
                el.textContent = formatRupiah(data.total);
            });
            // Update Topbar Count if exists
            const countBadge = document.getElementById('cartCountBadge');
            if(countBadge) countBadge.textContent = data.count;
            
        } else {
            alert(data.message || 'Gagal mengupdate keranjang');
            // Revert value
            inputEl.value = inputEl.getAttribute('value');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        inputEl.disabled = false;
        inputEl.style.opacity = '1';
        if(minusBtn) minusBtn.disabled = false;
        if(plusBtn) plusBtn.disabled = false;
        alert('Terjadi kesalahan koneksi');
    });
  }

  document.querySelectorAll('[data-qty-form]').forEach(function(form){
    var minus = form.querySelector('.qty-btn.minus');
    var plus = form.querySelector('.qty-btn.plus');
    var input = form.querySelector('.qty-input');
    
    if (!input) return;
    
    var min = parseInt(input.getAttribute('min') || '1', 10);
    var max = parseInt(input.getAttribute('max') || '999', 10);
    var itemId = form.getAttribute('data-id');

    function clamp(v){ v = Math.max(min, v); v = Math.min(max, v); return v; }

    if (minus) {
        minus.addEventListener('click', function(e){ 
            e.preventDefault(); 
            var v = parseInt(input.value || '1', 10); 
            var newValue = clamp(v - 1);
            if (v !== newValue) {
                input.value = newValue;
                updateCart(itemId, newValue, input);
            }
        });
    }

    if (plus) {
        plus.addEventListener('click', function(e){ 
            e.preventDefault(); 
            var v = parseInt(input.value || '1', 10); 
            var newValue = clamp(v + 1);
            if (v !== newValue) {
                input.value = newValue;
                updateCart(itemId, newValue, input);
            }
        });
    }
  });
});
</script>
@endpush
