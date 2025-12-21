@extends('layouts.master')

@section('title', 'Pesanan Saya')

@section('content')
@php
    $tabs = [
        'menunggu_pembayaran' => 'Menunggu Pembayaran',
        'sedang_dikemas' => 'Sedang Dikemas',
        'dikirim' => 'Dikirim',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ];
    
    function getStatusColor($s) {
        return match($s) {
            'menunggu_pembayaran' => ['bg' => '#fff7ed', 'text' => '#ea580c', 'border' => '#fdba74'],
            'sedang_dikemas' => ['bg' => '#eef2ff', 'text' => '#4f46e5', 'border' => '#c7d2fe'],
            'dikirim' => ['bg' => '#ecfeff', 'text' => '#0891b2', 'border' => '#a5f3fc'],
            'selesai' => ['bg' => '#f0fdf4', 'text' => '#16a34a', 'border' => '#86efac'],
            'dibatalkan' => ['bg' => '#fef2f2', 'text' => '#dc2626', 'border' => '#fca5a5'],
            default => ['bg' => '#f3f4f6', 'text' => '#4b5563', 'border' => '#d1d5db'],
        };
    }
@endphp

<style>
    .orders-container {
        max-width: 1000px;
        margin: 2rem auto 5rem;
        padding: 0 2rem;
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: 800;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 1rem;
        letter-spacing: -0.02em;
    }
    
    .page-actions {
        display: flex;
        gap: 1rem;
    }
    
    .btn-secondary {
        background: white;
        border: 1px solid rgba(0,0,0,0.08);
        color: #4b5563;
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    
    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        color: #111827;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    /* Tabs */
    .tabs-wrapper {
        margin-bottom: 2.5rem;
        overflow-x: auto;
        padding-bottom: 1rem;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .tabs-wrapper::-webkit-scrollbar {
        display: none;
    }
    
    .tabs-container {
        display: flex;
        gap: 1rem;
        min-width: max-content;
    }
    
    .tab-item {
        padding: 0.8rem 1.5rem;
        border-radius: 9999px;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        color: #6b7280;
        background: white;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 0.6rem;
        white-space: nowrap;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    
    .tab-item:hover {
        border-color: rgba(232, 0, 29, 0.2);
        color: var(--primary-red);
        transform: translateY(-2px);
    }
    
    .tab-item.active {
        background: var(--primary-red);
        color: white;
        border-color: var(--primary-red);
        box-shadow: 0 4px 15px rgba(232, 0, 29, 0.3);
    }
    
    .count-badge {
        background: rgba(232, 0, 29, 0.1);
        color: var(--primary-red);
        font-size: 0.8rem;
        padding: 0.15rem 0.5rem;
        border-radius: 99px;
        min-width: 1.4rem;
        text-align: center;
        font-weight: 800;
    }
    
    .tab-item.active .count-badge {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    /* Order Card */
    .order-card {
        background: white;
        border-radius: 24px;
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 2rem;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -5px rgba(0,0,0,0.1);
        border-color: rgba(232, 0, 29, 0.15);
    }
    
    .card-header {
        padding: 1.2rem 2rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fcfcfc;
    }
    
    .order-meta {
        display: flex;
        align-items: center;
        gap: 1.2rem;
        font-size: 0.95rem;
        color: #6b7280;
    }
    
    .order-id {
        font-weight: 700;
        color: #111827;
        font-family: 'Inter', monospace;
        font-size: 1rem;
    }
    
    .status-badge {
        font-size: 0.85rem;
        font-weight: 700;
        padding: 0.4rem 1rem;
        border-radius: 9999px;
        text-transform: capitalize;
        border: 1px solid transparent;
        letter-spacing: 0.02em;
    }
    
    .card-body {
        padding: 2rem;
    }
    
    .product-preview {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .product-preview:last-child {
        margin-bottom: 0;
    }
    
    .product-img {
        width: 90px;
        height: 90px;
        border-radius: 16px;
        object-fit: cover;
        border: 1px solid rgba(0,0,0,0.05);
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }
    
    .product-info {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .product-name {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-size: 1.1rem;
    }
    
    .product-meta {
        font-size: 0.95rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    .more-items {
        font-size: 0.9rem;
        color: #6b7280;
        padding-top: 1rem;
        margin-top: 1rem;
        border-top: 1px dashed #e5e7eb;
        text-align: center;
        font-weight: 600;
    }
    
    .card-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
    }
    
    .total-section {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }
    
    .total-label {
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 600;
    }
    
    .total-amount {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--primary-red);
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
    }
    
    .btn-action {
        padding: 0.7rem 1.5rem;
        border-radius: 14px;
        font-size: 0.95rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(232, 0, 29, 0.2);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(232, 0, 29, 0.3);
    }
    
    .btn-outline {
        background: white;
        border: 1px solid rgba(0,0,0,0.1);
        color: #4b5563;
    }
    
    .btn-outline:hover {
        border-color: #d1d5db;
        background: #f9fafb;
        color: #111827;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    
    .btn-danger-outline {
        background: white;
        border: 1px solid #fca5a5;
        color: #dc2626;
    }
    
    .btn-danger-outline:hover {
        background: #fef2f2;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(232, 0, 29, 0.1);
    }

    /* Alerts */
    .alert {
        padding: 1.2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    
    .alert-success {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .alert-error {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 32px;
        border: 1px solid rgba(0,0,0,0.04);
        box-shadow: 0 4px 24px rgba(0,0,0,0.02);
    }
    
    .empty-icon {
        width: 100px;
        height: 100px;
        margin-bottom: 2rem;
        color: #e5e7eb;
    }
    
    .empty-text {
        color: #1f2937;
        font-size: 1.4rem;
        font-weight: 800;
        margin-bottom: 0.8rem;
    }
    
    .empty-sub {
        color: #6b7280;
        margin-bottom: 2.5rem;
        font-size: 1.1rem;
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 3rem;
        display: flex;
        justify-content: center;
    }
    
    @media (max-width: 640px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
        }
        
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .status-badge {
            align-self: flex-start;
        }
        
        .card-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
        }
        
        .action-buttons {
            width: 100%;
            justify-content: flex-end;
        }
        
        .orders-container {
            padding: 0 1rem;
        }
    }
</style>

<div class="orders-container">
    <div class="page-header">
        <div class="page-title">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            Pesanan Saya
        </div>
        <div class="page-actions">
            <a href="{{ route('account.profile') }}" class="btn-secondary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Akun
            </a>
            <a href="{{ route('account.help') }}" class="btn-secondary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Bantuan
            </a>
        </div>
    </div>

    <div class="tabs-wrapper">
        <div class="tabs-container">
            <a href="{{ route('account.orders') }}" class="tab-item {{ !request('status') ? 'active' : '' }}">
                Semua
            </a>
            @foreach($tabs as $k => $v)
                <a href="{{ route('account.orders', ['status' => $k]) }}" class="tab-item {{ request('status') === $k ? 'active' : '' }}">
                    {{ $v }}
                    @if(isset($counts[$k]) && $counts[$k] > 0)
                        <span class="count-badge">{{ $counts[$k] }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-error">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        {{ session('error') }}
    </div>
    @endif

    <div class="orders-list">
        @forelse($orders as $o)
            @php $statusStyle = getStatusColor($o->status); @endphp
            <div class="order-card">
                <div class="card-header">
                    <div class="order-meta">
                        <span class="order-id">#{{ $o->order_id }}</span>
                        <span style="width: 1px; height: 14px; background: #d1d5db;"></span>
                        <span>{{ \Carbon\Carbon::parse($o->created_at)->format('d M Y, H:i') }}</span>
                    </div>
                    <span class="status-badge" style="background: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['text'] }}; border-color: {{ $statusStyle['border'] }}">
                        {{ $tabs[$o->status] ?? ucfirst(str_replace('_', ' ', $o->status)) }}
                    </span>
                </div>
                
                <div class="card-body">
                    @php $itemCount = $o->items->count(); @endphp
                    @foreach($o->items->take(1) as $item)
                        <div class="product-preview">
                            <img src="{{ $item->produk->image_url ?? asset('images/placeholder-produk.jpg') }}" alt="Product" class="product-img">
                            <div class="product-info">
                                <div class="product-name">{{ $item->produk->nama_produk ?? 'Produk tidak tersedia' }}</div>
                                <div class="product-meta">
                                    {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($itemCount > 1)
                        <div class="more-items">
                            + {{ $itemCount - 1 }} produk lainnya
                        </div>
                    @endif
                </div>
                
                <div class="card-footer">
                    <div class="total-section">
                        <span class="total-label">Total Pesanan</span>
                        <span class="total-amount">Rp {{ number_format($o->total, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="action-buttons">
                        <a href="{{ route('account.orders.detail', $o->order_id) }}" class="btn-action btn-outline">
                            Detail
                        </a>
                        
                        @if($o->status == 'menunggu_pembayaran')
                            <a href="{{ route('checkout.success', $o->order_id) }}" class="btn-action btn-primary">
                                Bayar Sekarang
                            </a>
                        @endif

                        @if($o->status == 'dikirim')
                            <form action="{{ route('account.orders.complete', $o->order_id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-action btn-primary" onclick="return confirm('Apakah Anda yakin pesanan sudah diterima dengan baik?')">
                                    Diterima
                                </button>
                            </form>
                        @endif

                        @if(!in_array($o->status, ['dikirim','selesai','dibatalkan']))
                            <form action="{{ route('account.orders.cancel', $o->order_id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-action btn-danger-outline" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                    Batalkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <div class="empty-text">Belum ada pesanan</div>
                <div class="empty-sub">Yuk, mulai belanja dan penuhi kebutuhanmu sekarang!</div>
                <a href="{{ url('/') }}" class="btn-action btn-primary" style="display:inline-flex;">
                    Mulai Belanja
                </a>
            </div>
        @endforelse
    </div>

    <div class="pagination-wrapper">
        {{ $orders->links() }}
    </div>
</div>
@endsection

