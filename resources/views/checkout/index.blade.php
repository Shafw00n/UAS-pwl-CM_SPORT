@extends('layouts.master')

@section('title', 'Checkout - Selesaikan Pesanan')

@section('content')
<div class="checkout-page">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 text-center md:text-left">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Checkout</h1>
            <p class="text-gray-500">Lengkapi informasi pengiriman dan pembayaran Anda.</p>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm" class="checkout-form" enctype="multipart/form-data">
            @csrf
            @if(isset($selectedIds) && $selectedIds->isNotEmpty())
                @foreach($selectedIds as $sid)
                    <input type="hidden" name="selected[]" value="{{ $sid }}">
                @endforeach
            @else
                @foreach((array) request('selected') as $sid)
                    <input type="hidden" name="selected[]" value="{{ $sid }}">
                @endforeach
            @endif

            <div class="checkout-grid">
                <!-- LEFT COLUMN: Forms & Info -->
                <div class="checkout-main space-y-6">
                    
                    <!-- 1. Alamat Pengiriman -->
                    <div class="checkout-card animate-fade-in-up" style="animation-delay: 0.1s;">
                        <div class="card-header">
                            <div class="step-badge">
                                <span>1</span>
                            </div>
                            <h2 class="card-title">Alamat Pengiriman</h2>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="form-group">
                                    <label class="form-label">Nama Penerima</label>
                                    <div class="input-with-icon">
                                        <div class="icon-wrapper">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        </div>
                                        <input type="text" name="recipient_name" class="form-input" required placeholder="Nama lengkap penerima" value="{{ old('recipient_name', auth()->user()->name ?? '') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">No. Telepon</label>
                                    <div class="input-with-icon">
                                        <div class="icon-wrapper">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                        </div>
                                        <input type="text" name="recipient_phone" class="form-input" required placeholder="08xxxxxxxxxx" value="{{ old('recipient_phone') }}">
                                    </div>
                                </div>
                                <div class="form-group col-span-1 md:col-span-2">
                                    <label class="form-label">Alamat Lengkap</label>
                                    <div class="input-with-icon textarea-icon">
                                        <div class="icon-wrapper">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                        </div>
                                        <textarea name="address" class="form-input" rows="3" required placeholder="Nama jalan, nomor rumah, RT/RW">{{ old('address') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Kota / Kecamatan</label>
                                    <input type="text" name="city" class="form-input pl-4" required placeholder="Contoh: Jakarta Selatan" value="{{ old('city') }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Kode Pos</label>
                                    <input type="text" name="zip" class="form-input pl-4" required placeholder="xxxxx" value="{{ old('zip') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Barang Pesanan -->
                    <div class="checkout-card animate-fade-in-up" style="animation-delay: 0.2s;">
                        <div class="card-header">
                            <div class="step-badge">
                                <span>2</span>
                            </div>
                            <h2 class="card-title">Barang Pesanan</h2>
                        </div>
                        <div class="card-body p-0">
                            <div class="order-items-list">
                                @foreach($items as $i)
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="{{ $i->produk?->image_url ?? asset('images/placeholder-produk.jpg') }}" alt="{{ $i->produk?->nama_produk }}">
                                    </div>
                                    <div class="item-details">
                                        <h3 class="item-name">{{ $i->produk?->nama_produk ?? 'Produk tidak tersedia' }}</h3>
                                        <div class="item-meta">
                                            @if($i->size || $i->color)
                                                <span class="variant">{{ $i->size }} {{ $i->color ? '• '.$i->color : '' }}</span>
                                            @endif
                                            <span class="qty">Qty: {{ $i->quantity }}</span>
                                        </div>
                                    </div>
                                    <div class="item-price">
                                        Rp {{ number_format(($i->produk?->harga ?? 0) * $i->quantity, 0, ',', '.') }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- 3. Pengiriman & Catatan -->
                    <div class="checkout-card animate-fade-in-up" style="animation-delay: 0.3s;">
                        <div class="card-header">
                            <div class="step-badge">
                                <span>3</span>
                            </div>
                            <h2 class="card-title">Metode Pengiriman</h2>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 gap-5">
                                <div class="form-group">
                                    <label class="form-label">Pilih Kurir</label>
                                    <div class="select-wrapper relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                        </div>
                                        <select name="shipping_method" class="form-select pl-10" id="shippingMethod">
                                            <option value="regular" {{ old('shipping_method', $shippingMethod) == 'regular' ? 'selected' : '' }}>Reguler (Estimasi 2-3 hari) - Rp 15.000</option>
                                            <option value="express" {{ old('shipping_method', $shippingMethod) == 'express' ? 'selected' : '' }}>Express (Estimasi 1 hari) - Rp 30.000</option>
                                            <option value="pickup" {{ old('shipping_method', $shippingMethod) == 'pickup' ? 'selected' : '' }}>Ambil di Toko (Gratis)</option>
                                        </select>
                                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                        </div>
                                    </div>
                                    <button type="submit" formaction="{{ route('checkout.update') }}" class="mt-3 inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-bold transition-colors border border-gray-200">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.3"/></svg>
                                        Update Ongkir
                                    </button>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Pesan untuk Penjual (Opsional)</label>
                                    <textarea name="note_to_seller" class="form-input" rows="2" placeholder="Warna cadangan, instruksi pengiriman, dll..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Metode Pembayaran -->
                    <div class="checkout-card animate-fade-in-up" style="animation-delay: 0.4s;">
                        <div class="card-header">
                            <div class="step-badge">
                                <span>4</span>
                            </div>
                            <h2 class="card-title">Metode Pembayaran</h2>
                        </div>
                        <div class="card-body">
                            <div class="payment-methods-grid">
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="cod" {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}>
                                    <div class="payment-box">
                                        <div class="icon-box">
                                            <span class="emoji">💵</span>
                                        </div>
                                        <div class="info">
                                            <span class="name">COD (Bayar di Tempat)</span>
                                            <span class="desc">Bayar tunai saat kurir tiba</span>
                                        </div>
                                        <div class="check-circle"></div>
                                    </div>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="transfer" {{ old('payment_method') == 'transfer' ? 'checked' : '' }}>
                                    <div class="payment-box">
                                        <div class="icon-box">
                                            <span class="emoji">🏦</span>
                                        </div>
                                        <div class="info">
                                            <span class="name">Transfer Bank</span>
                                            <span class="desc">BCA, Mandiri, BRI, BNI</span>
                                        </div>
                                        <div class="check-circle"></div>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- Payment Proof Section (Shown if Transfer selected) -->
                            <div id="paymentProofSection" class="mt-5 animate-fade-in {{ old('payment_method') == 'transfer' ? '' : 'hidden' }}">
                                <div class="proof-upload-box">
                                    <div class="upload-icon">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    </div>
                                    <div class="upload-content">
                                        <p class="upload-title">Upload Bukti Transfer</p>
                                        <p class="upload-desc" id="uploadDesc">Format: JPG, PNG. Maks 2MB.</p>
                                        <p id="fileNameDisplay" class="text-sm text-green-600 mt-1 font-bold hidden"></p>
                                        <input type="file" name="payment_proof" id="paymentProof" accept="image/*" class="file-input-custom">
                                    </div>
                                </div>
                                <div class="mt-2 text-center">
                                    <button type="submit" formaction="{{ route('checkout.update') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800 underline">Update Metode Pembayaran</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN: Sticky Summary -->
                <div class="checkout-sidebar">
                    <div class="summary-card sticky-card animate-fade-in-up" style="animation-delay: 0.5s;">
                        <!-- Header -->
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shadow-sm">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                                </div>
                                Rincian Pembayaran
                            </h2>
                        </div>
                        
                        <!-- Voucher Section -->
                        <div class="p-6 border-b border-gray-50 bg-white">
                            <label class="text-sm font-bold text-gray-700 mb-3 block flex items-center gap-2">
                                <svg width="16" height="16" class="text-yellow-500" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><circle cx="7" cy="7" r="2" fill="white"/></svg>
                                Kode Voucher
                            </label>
                            <div class="flex gap-3">
                                <div class="relative flex-1 group">
                                    <input type="text" name="voucher_code" class="w-full pl-10 pr-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all uppercase font-semibold placeholder-gray-400" id="voucherInput" placeholder="MASUKKAN KODE" value="{{ old('voucher_code', $voucherCode) }}">
                                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-red-500 transition-colors">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                                    </div>
                                </div>
                                <button type="submit" formaction="{{ route('checkout.update') }}" class="bg-gray-900 hover:bg-black text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-gray-200 transition-all hover:-translate-y-0.5 active:translate-y-0" id="btnApplyVoucher">
                                    Pakai
                                </button>
                            </div>
                            
                            <!-- Available Vouchers -->
                            <div class="mt-5">
                                <p class="text-xs font-semibold text-gray-500 mb-3 flex items-center gap-1">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    Voucher Tersedia:
                                </p>
                                <div class="flex flex-wrap gap-2.5">
                                    <button type="submit" name="apply_voucher" value="PROMO10" formaction="{{ route('checkout.update') }}" class="voucher-chip select-voucher group">
                                        <div class="chip-icon bg-red-50 text-red-600 group-hover:bg-red-500 group-hover:text-white transition-colors">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="19" y1="5" x2="5" y2="19"></line><circle cx="6.5" cy="6.5" r="2.5"></circle><circle cx="17.5" cy="17.5" r="2.5"></circle></svg>
                                        </div>
                                        <span class="text-xs font-bold text-gray-700 group-hover:text-red-600 transition-colors">PROMO10</span>
                                    </button>
                                    <button type="submit" name="apply_voucher" value="ONGKIRFREE" formaction="{{ route('checkout.update') }}" class="voucher-chip select-voucher group">
                                        <div class="chip-icon bg-blue-50 text-blue-600 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                        </div>
                                        <span class="text-xs font-bold text-gray-700 group-hover:text-blue-600 transition-colors">ONGKIRFREE</span>
                                    </button>
                                    <button type="submit" name="apply_voucher" value="SHIP5K" formaction="{{ route('checkout.update') }}" class="voucher-chip select-voucher group">
                                        <div class="chip-icon bg-green-50 text-green-600 group-hover:bg-green-500 group-hover:text-white transition-colors">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                        </div>
                                        <span class="text-xs font-bold text-gray-700 group-hover:text-green-600 transition-colors">SHIP5K</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Details -->
                        <div class="p-6 space-y-4 bg-white">
                            <div class="flex justify-between items-center text-sm group">
                                <span class="text-gray-500 font-medium flex items-center gap-2.5 group-hover:text-gray-800 transition-colors">
                                    <div class="w-6 h-6 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600 transition-colors">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                    </div>
                                    Subtotal Produk
                                </span>
                                <span class="font-bold text-gray-900 text-base" id="sumSubtotal">Rp {{ number_format($subtotal,0,',','.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm group">
                                <span class="text-gray-500 font-medium flex items-center gap-2.5 group-hover:text-gray-800 transition-colors">
                                    <div class="w-6 h-6 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600 transition-colors">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                    </div>
                                    Biaya Pengiriman
                                </span>
                                <span class="font-bold text-gray-900 text-base" id="sumShipping">Rp {{ number_format($shipping,0,',','.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm group">
                                <span class="text-gray-500 font-medium flex items-center gap-2.5 group-hover:text-gray-800 transition-colors">
                                    <div class="w-6 h-6 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600 transition-colors">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                    </div>
                                    Biaya Layanan
                                </span>
                                <span class="font-bold text-gray-900 text-base" id="sumService">Rp {{ number_format(2500,0,',','.') }}</span>
                            </div>
                            
                            <!-- Discounts -->
                            <div class="pt-3 pb-3 border-t border-dashed border-gray-200 space-y-3">
                                <div class="flex justify-between items-center text-sm text-green-600 bg-green-50 p-2.5 rounded-lg border border-green-100">
                                    <span class="flex items-center gap-2 font-medium">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                        Diskon Pengiriman
                                    </span>
                                    <span class="font-bold" id="sumShipDisc">-Rp 0</span>
                                </div>
                                <div class="flex justify-between items-center text-sm text-green-600 bg-green-50 p-2.5 rounded-lg border border-green-100">
                                    <span class="flex items-center gap-2 font-medium">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                        Diskon Voucher
                                    </span>
                                    <span class="font-bold" id="sumVoucherDisc">-Rp 0</span>
                                </div>
                            </div>

                            <div class="pt-5 border-t-2 border-gray-100">
                                <div class="flex justify-between items-end mb-1">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-500">Total Pembayaran</span>
                                        <span class="text-xs text-gray-400">Termasuk pajak</span>
                                    </div>
                                    <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-red-800" id="sumTotal">Rp {{ number_format($subtotal + $tax + $shipping + 2500,0,',','.') }}</span>
                                </div>
                                
                                <div class="mt-6">
                                    <button type="submit" class="checkout-btn w-full group">
                                        <span>Buat Pesanan Sekarang</span>
                                        <svg class="group-hover:translate-x-1 transition-transform" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    :root {
        --primary-red: #e60023;
        --dark-red: #b3001b;
        --text-dark: #1f2937;
        --text-gray: #6b7280;
        --bg-light: #f9fafb;
    }

    .checkout-page {
        background-color: transparent;
        min-height: 100vh;
        font-family: 'Inter', sans-serif;
        padding-bottom: 5rem;
    }

    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
        align-items: start;
    }

    /* Cards */
    .checkout-card, .summary-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.04);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .checkout-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -5px rgba(0, 0, 0, 0.1);
        border-color: rgba(232, 0, 29, 0.1);
    }

    .card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        background: #fff;
    }

    .card-body {
        padding: 2rem;
    }

    /* Step Badge */
    .step-badge {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary-red), var(--dark-red));
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(230, 0, 35, 0.2);
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text-dark);
        margin: 0;
        letter-spacing: -0.01em;
    }

    /* Forms */
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }

    .form-label {
        font-size: 0.95rem;
        font-weight: 700;
        color: #374151;
        margin-left: 2px;
    }

    .input-with-icon {
        position: relative;
    }
    
    .input-with-icon .icon-wrapper {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        transition: color 0.2s;
    }
    
    .input-with-icon.textarea-icon .icon-wrapper {
        top: 20px;
        transform: none;
    }

    .form-input, .form-select {
        padding: 1rem 1.2rem;
        padding-left: 3rem; /* Space for icon */
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        font-size: 1rem;
        transition: all 0.2s;
        background: #fcfcfc;
        width: 100%;
        outline: none;
        color: #1f2937;
    }
    
    .form-input:not(.input-with-icon .form-input) {
        padding-left: 1.2rem;
    }

    .form-input:focus, .form-select:focus {
        background: white;
        border-color: var(--primary-red);
        box-shadow: 0 0 0 4px rgba(230, 0, 35, 0.05);
    }
    
    .input-with-icon:focus-within .icon-wrapper {
        color: var(--primary-red);
    }

    /* Order Items */
    .order-item {
        display: flex;
        gap: 1.5rem;
        padding: 1.5rem 0;
        border-bottom: 1px solid #f3f4f6;
        align-items: center;
        transition: background 0.2s;
    }
    .order-item:last-child { border-bottom: none; }

    .item-image {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        flex-shrink: 0;
        background: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }
    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-details { flex: 1; }
    .item-name { font-weight: 700; font-size: 1rem; color: var(--text-dark); line-height: 1.4; margin-bottom: 0.35rem; }
    .item-meta { font-size: 0.9rem; color: var(--text-gray); display: flex; gap: 0.6rem; align-items: center; font-weight: 500; }
    .item-price { font-weight: 800; color: var(--text-dark); font-size: 1.1rem; }
    
    .variant { 
        background: #f3f4f6; padding: 3px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; color: #4b5563;
    }

    /* Payment Methods */
    .payment-methods-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.2rem;
    }

    .payment-option { cursor: pointer; position: relative; }
    .payment-option input { display: none; }
    
    .payment-box {
        border: 2px solid #f3f4f6;
        border-radius: 20px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        background: #fff;
    }
    
    .payment-box:hover {
        border-color: #e5e7eb;
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    }

    .payment-box .icon-box { 
        width: 48px; height: 48px; 
        background: #f9fafb; 
        border-radius: 12px; 
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 0.25rem;
    }
    .payment-box .info { display: flex; flex-direction: column; }
    .payment-box .name { font-weight: 800; color: var(--text-dark); font-size: 1rem; }
    .payment-box .desc { font-size: 0.85rem; color: var(--text-gray); margin-top: 4px; font-weight: 500; }

    .check-circle {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 24px;
        height: 24px;
        border: 2px solid #e5e7eb;
        border-radius: 50%;
        transition: 0.2s;
    }
    .check-circle::after {
        content: '';
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%) scale(0);
        width: 14px;
        height: 14px;
        background: var(--primary-red);
        border-radius: 50%;
        transition: 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .payment-option input:checked + .payment-box {
        border-color: var(--primary-red);
        background: #fff9f9;
        box-shadow: 0 8px 20px rgba(230, 0, 35, 0.1);
    }
    .payment-option input:checked + .payment-box .check-circle {
        border-color: var(--primary-red);
    }
    .payment-option input:checked + .payment-box .check-circle::after {
        transform: translate(-50%, -50%) scale(1);
    }
    
    /* Proof Upload */
    .proof-upload-box {
        border: 2px dashed #bfdbfe;
        background: #eff6ff;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        position: relative;
    }
    .upload-icon { color: #3b82f6; }
    .upload-title { font-weight: 700; color: #1e40af; font-size: 1rem; }
    .upload-desc { font-size: 0.9rem; color: #60a5fa; }
    .file-input-custom {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    /* Buttons */
    .checkout-btn {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: white;
        font-weight: 800;
        padding: 1.25rem 2rem;
        border-radius: 16px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(232, 0, 29, 0.3);
        font-size: 1.25rem;
        letter-spacing: 0.01em;
    }
    .checkout-btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(232, 0, 29, 0.4);
        filter: brightness(1.1);
    }

    .btn-apply-voucher {
        background: var(--text-dark);
        color: white;
        border: none;
        padding: 0 1.5rem;
        font-weight: 700;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .btn-apply-voucher:hover { 
        background: #000; 
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    .voucher-chip {
        background: #fff;
        color: var(--text-dark);
        border: 1px solid rgba(0,0,0,0.08);
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .voucher-chip .icon { opacity: 0.6; }
    .voucher-chip:hover {
        background: #fff0f0;
        border-color: var(--primary-red);
        color: var(--primary-red);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(232, 0, 29, 0.1);
    }

    /* Sticky Summary */
    .sticky-card {
        position: sticky;
        top: 2rem;
        z-index: 10;
    }
    
    /* Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0; /* Initial state */
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease forwards;
    }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    /* Responsive */
    @media (max-width: 1024px) {
        .checkout-grid {
            grid-template-columns: 1fr;
        }
        .checkout-sidebar {
            grid-row: 1; /* Show summary first on mobile? Maybe not, usually below forms */
            grid-row: auto;
        }
    }
    @media (max-width: 640px) {
        .payment-methods-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
        const paymentProofSection = document.getElementById('paymentProofSection');

        function toggleProofSection() {
            const selected = document.querySelector('input[name="payment_method"]:checked');
            if (selected && selected.value === 'transfer') {
                paymentProofSection.classList.remove('hidden');
            } else {
                paymentProofSection.classList.add('hidden');
            }
        }

        paymentRadios.forEach(radio => {
            radio.addEventListener('change', toggleProofSection);
        });

        // Initial check
        toggleProofSection();
        
        // File Upload Filename Display
        const fileInput = document.getElementById('paymentProof');
        const fileNameDisplay = document.getElementById('fileNameDisplay');
        const uploadDesc = document.getElementById('uploadDesc');

        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const fileName = e.target.files[0].name;
                    fileNameDisplay.textContent = '✓ ' + fileName;
                    fileNameDisplay.classList.remove('hidden');
                    if(uploadDesc) uploadDesc.classList.add('hidden');
                } else {
                    fileNameDisplay.classList.add('hidden');
                    if(uploadDesc) uploadDesc.classList.remove('hidden');
                }
            });
        }
        
        // Auto-submit on shipping method change (optional enhancement)
        const shippingSelect = document.getElementById('shippingMethod');
        if (shippingSelect) {
            shippingSelect.addEventListener('change', function() {
                // Find the update button and click it to trigger server-side recalculation
                // Or submit the form to the update route
                const form = document.getElementById('checkoutForm');
                // We need to change the action to update route temporarily
                const originalAction = form.action;
                form.action = "{{ route('checkout.update') }}";
                form.submit();
            });
        }
    });
</script>
@endpush
@endsection
