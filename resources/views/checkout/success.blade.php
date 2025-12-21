@extends('layouts.master')

@section('title', 'Checkout Berhasil')

@section('content')
<style>
    .success-page-container {
        min-height: calc(100vh - 80px);
        background: #f9fafb;
        padding: 3rem 1rem;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }
    .success-card {
        max-width: 800px;
        margin: 0 auto 2rem;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        overflow: hidden;
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .success-header {
        background: linear-gradient(90deg, #dc2626, #b91c1c);
        padding: 3rem 2rem;
        text-align: center;
        color: #fff;
        position: relative;
    }
    .success-icon-wrapper {
        width: 80px;
        height: 80px;
        background: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        animation: bounce-slow 2s infinite ease-in-out;
    }
    .order-id-badge {
        display: inline-flex;
        align-items: center;
        background: rgba(255,255,255,0.2);
        padding: 0.5rem 1.2rem;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(4px);
        margin-top: 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .content-padding {
        padding: 2.5rem;
    }
    .status-card {
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        display: flex;
        gap: 1.2rem;
        align-items: start;
    }
    .status-card.blue { background: #eff6ff; border: 1px solid #dbeafe; }
    .status-card.yellow { background: #fefce8; border: 1px solid #fef9c3; display: block; }
    .status-card.green { background: #f0fdf4; border: 1px solid #dcfce7; }
    
    .status-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .blue .status-icon { background: #dbeafe; color: #2563eb; }
    .yellow .status-icon { background: #fef9c3; color: #ca8a04; }
    .green .status-icon { background: #dcfce7; color: #16a34a; }
    
    .status-title { font-weight: 800; font-size: 1.1rem; margin-bottom: 0.5rem; }
    .status-text { font-size: 0.95rem; line-height: 1.6; }
    .blue .status-title { color: #1e40af; }
    .blue .status-text { color: #1e3a8a; }
    .yellow .status-title { color: #854d0e; }
    .yellow .status-text { color: #713f12; }
    .green .status-title { color: #166534; }
    .green .status-text { color: #14532d; }

    .bank-info-card {
        background: #fff;
        border: 1px solid #eab308;
        border-radius: 10px;
        padding: 1.2rem;
        margin-bottom: 1.5rem;
        margin-top: 1rem;
    }
    .bank-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
    .bank-number-row { display: flex; justify-content: space-between; align-items: center; }
    .bank-number { font-family: monospace; font-size: 1.25rem; font-weight: 700; color: #1f2937; }
    .copy-btn { color: #dc2626; font-weight: 600; font-size: 0.9rem; cursor: pointer; border: none; background: none; }
    .copy-btn:hover { text-decoration: underline; }

    .confirm-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        background: #dc2626;
        color: white;
        font-weight: 700;
        padding: 1rem;
        border-radius: 10px;
        text-decoration: none;
        box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.3);
        transition: background 0.2s;
    }
    .confirm-btn:hover { background: #b91c1c; }

    .order-summary {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }
    .summary-header {
        background: #f9fafb;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .summary-header h3 { font-weight: 700; color: #1f2937; margin: 0; }
    .summary-date { font-size: 0.9rem; color: #6b7280; }
    .summary-body { padding: 1.5rem; }
    
    .item-row {
        display: flex;
        align-items: center;
        gap: 1.2rem;
        margin-bottom: 1.2rem;
        padding-bottom: 1.2rem;
        border-bottom: 1px solid #f3f4f6;
    }
    .item-row:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
    
    .item-image {
        width: 64px; /* Fixed width to prevent "kebesaran" */
        height: 64px; /* Fixed height */
        background: #f3f4f6;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        flex-shrink: 0;
    }
    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .item-details { flex: 1; }
    .item-name {
        font-weight: 700;
        color: #1f2937;
        font-size: 0.95rem;
        margin-bottom: 0.2rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .item-meta { font-size: 0.9rem; color: #6b7280; }
    .item-price { font-weight: 700; color: #111827; font-size: 1rem; }

    .summary-totals {
        border-top: 2px dashed #e5e7eb;
        padding-top: 1.5rem;
        margin-top: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }
    .total-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.95rem;
        color: #4b5563;
    }
    .grand-total {
        border-top: 1px solid #e5e7eb;
        margin-top: 0.8rem;
        padding-top: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .grand-total-label { font-weight: 800; font-size: 1.1rem; color: #1f2937; }
    .grand-total-value { font-weight: 800; font-size: 1.4rem; color: #dc2626; }

    .action-buttons {
        margin-top: 2.5rem;
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    .btn {
        padding: 0.9rem 2.5rem;
        border-radius: 12px;
        font-weight: 700;
        text-align: center;
        transition: all 0.2s;
        text-decoration: none;
        font-size: 1rem;
    }
    .btn-outline {
        background: #fff;
        border: 2px solid #e5e7eb;
        color: #4b5563;
    }
    .btn-outline:hover { background: #f9fafb; border-color: #d1d5db; color: #1f2937; }
    .btn-primary {
        background: #111827;
        color: #fff;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 2px solid transparent;
    }
    .btn-primary:hover { background: #000; transform: translateY(-2px); }
    
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @media (max-width: 640px) {
        .content-padding { padding: 1.5rem; }
        .action-buttons { flex-direction: column; }
        .btn { width: 100%; }
        .success-header { padding: 2rem 1rem; }
    }
</style>

<div class="success-page-container">
    <div class="success-card">
        <div class="success-header">
            <!-- Background Pattern -->
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.1; pointer-events: none;">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            <div style="position:relative; z-index:1;">
                <div class="success-icon-wrapper">
                    <svg width="40" height="40" fill="none" stroke="#dc2626" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem;">Terima Kasih!</h1>
                <p style="color: #fca5a5; font-size: 1.1rem;">Pesanan Anda berhasil dibuat.</p>
                <div class="order-id-badge">
                    Order ID: #{{ $order->order_id }}
                </div>
            </div>
        </div>

        <div class="content-padding">
            <!-- Payment Status Section -->
            <div class="mb-8">
                @if($order->payment_method === 'transfer')
                    @if($order->payment_status === 'submitted')
                        <div class="status-card blue">
                            <div class="status-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            </div>
                            <div>
                                <h3 class="status-title">Menunggu Validasi</h3>
                                <p class="status-text">Bukti pembayaran Anda telah kami terima dan sedang dalam proses verifikasi oleh admin. Kami akan memberitahu Anda setelah pembayaran dikonfirmasi.</p>
                            </div>
                        </div>
                    @else
                        <div class="status-card yellow">
                            <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem;">
                                <div class="status-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                </div>
                                <div>
                                    <h3 class="status-title">Selesaikan Pembayaran</h3>
                                    <p class="status-text">Silakan transfer sesuai total tagihan di bawah ini</p>
                                </div>
                            </div>
                            
                            <div class="bank-info-card">
                                <div class="bank-header">
                                    <span style="color: #6b7280; font-size: 0.9rem;">Bank BCA</span>
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" style="height: 1rem;">
                                </div>
                                <div class="bank-number-row">
                                    <span class="bank-number">123 456 7890</span>
                                </div>
                                <div style="margin-top: 0.5rem; font-size: 0.9rem; color: #6b7280;">a.n. CM Sport Indonesia</div>
                            </div>

                            <div style="text-align: center;">
                                <a href="{{ route('checkout.confirm', $order->order_id) }}" class="confirm-btn">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    Konfirmasi Pembayaran
                                </a>
                            </div>
                        </div>
                    @endif
                @elseif($order->payment_method === 'cod')
                    <div class="status-card green">
                        <div class="status-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                        </div>
                        <div>
                            <h3 class="status-title">Siapkan Uang Tunai</h3>
                            <p class="status-text">Kurir kami akan segera mengirimkan pesanan Anda. Mohon siapkan uang pas sebesar total tagihan saat barang tiba.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <div class="summary-header">
                    <h3>Rincian Pesanan</h3>
                    <span class="summary-date">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="summary-body">
                    <div style="margin-bottom: 1.5rem;">
                        @foreach($order->items as $i)
                        <div class="item-row">
                            <div class="item-image">
                                <img src="{{ $i->produk->image_url ?? asset('images/placeholder-produk.jpg') }}" alt="{{ $i->produk->nama_produk ?? 'Produk' }}">
                            </div>
                            <div class="item-details">
                                <h4 class="item-name">{{ $i->produk->nama_produk ?? 'Produk tidak tersedia' }}</h4>
                                <p class="item-meta">{{ $i->quantity }} x Rp {{ number_format($i->price,0,',','.') }}</p>
                            </div>
                            <div class="item-price">
                                Rp {{ number_format($i->price * $i->quantity, 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="summary-totals">
                        <div class="total-row">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($order->subtotal,0,',','.') }}</span>
                        </div>
                        <div class="total-row">
                            <span>Ongkir</span>
                            <span>Rp {{ number_format($order->shipping,0,',','.') }}</span>
                        </div>
                        @if($order->tax > 0)
                        <div class="total-row">
                            <span>Pajak</span>
                            <span>Rp {{ number_format($order->tax,0,',','.') }}</span>
                        </div>
                        @endif
                        <div class="grand-total">
                            <span class="grand-total-label">Total</span>
                            <span class="grand-total-value">Rp {{ number_format($order->total,0,',','.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="action-buttons">
                <a href="{{ route('home') }}" class="btn btn-outline">
                    Kembali ke Beranda
                </a>
                <a href="{{ route('produk.all') }}" class="btn btn-primary">
                    Lanjut Belanja
                </a>
            </div>
        </div>
    </div>
    
    <p style="text-align: center; color: #9ca3af; font-size: 0.9rem;">
        Butuh bantuan? <a href="#" style="color: #dc2626; text-decoration: none;">Hubungi CS kami</a>
    </p>
</div>

@endsection
