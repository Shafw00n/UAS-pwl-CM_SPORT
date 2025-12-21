@extends('layouts.master')

@section('title', 'Detail Pesanan')

@section('content')
@php
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
    $statusStyle = getStatusColor($order->status);
@endphp

<style>
    .detail-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 0 1rem;
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #4b5563;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }
    
    .btn-back:hover {
        color: #1f2937;
    }

    .info-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    
    .card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fcfcfc;
    }
    
    .header-title {
        font-weight: 600;
        color: #1f2937;
        font-size: 1rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }

    .status-badge {
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        text-transform: capitalize;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    
    .info-item label {
        display: block;
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }
    
    .info-item div {
        font-weight: 500;
        color: #1f2937;
        font-size: 1rem;
    }

    /* Table */
    .items-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .items-table th {
        text-align: left;
        padding: 0.75rem 1rem;
        background: #f9fafb;
        color: #6b7280;
        font-weight: 500;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .items-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    
    .items-table tr:last-child td {
        border-bottom: none;
    }
    
    .product-cell {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .product-thumb {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #f3f4f6;
    }
    
    .product-name {
        font-weight: 500;
        color: #1f2937;
    }

    .btn-review {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        color: white;
        background: #dc2626;
        border: none;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .btn-review:hover {
        background: #b91c1c;
    }
    
    .badge-reviewed {
        background: #f3f4f6;
        color: #6b7280;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(2px);
    }
    
    .modal-content {
        background: white;
        width: 90%;
        max-width: 500px;
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        animation: modalSlideIn 0.3s ease-out;
    }
    
    @keyframes modalSlideIn {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .modal-header {
        padding: 1.25rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
    }
    
    .modal-close {
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        font-size: 1.5rem;
        line-height: 1;
        padding: 0;
    }
    
    .modal-close:hover {
        color: #4b5563;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-input, .form-textarea {
        width: 100%;
        padding: 0.6rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }
    
    .form-input:focus, .form-textarea:focus {
        outline: none;
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .star-rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 0.25rem;
    }
    
    .star-rating-input input {
        display: none;
    }
    
    .star-rating-input label {
        cursor: pointer;
        font-size: 1.5rem;
        color: #e5e7eb;
        transition: color 0.2s;
    }
    
    .star-rating-input input:checked ~ label,
    .star-rating-input label:hover,
    .star-rating-input label:hover ~ label {
        color: #fbbf24;
    }

    .btn-submit {
        width: 100%;
        background: #dc2626;
        color: white;
        border: none;
        padding: 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .btn-submit:hover {
        background: #b91c1c;
    }

    @media (max-width: 640px) {
        .page-header {
            flex-direction: column-reverse;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="detail-container">
    <div class="page-header">
        <div class="page-title">
            Detail Pesanan #{{ $order->order_id }}
        </div>
        <a href="{{ route('account.orders') }}" class="btn-back">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="info-card">
        <div class="card-header">
            <div class="header-title">Informasi Pesanan</div>
            <span class="status-badge" style="background: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['text'] }}; border-color: {{ $statusStyle['border'] }}">
                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
            </span>
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <label>Tanggal Pemesanan</label>
                    <div>{{ \Carbon\Carbon::parse($order->created_at)->format('d F Y, H:i') }}</div>
                </div>
                <div class="info-item">
                    <label>Metode Pembayaran</label>
                    <div>{{ $order->payment_method ? strtoupper($order->payment_method) : '-' }}</div>
                </div>
                <div class="info-item">
                    <label>Status Pembayaran</label>
                    <div>{{ ucfirst($order->payment_status) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="info-card">
        <div class="card-header">
            <div class="header-title">Rincian Produk</div>
        </div>
        <div style="overflow-x: auto;">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th style="text-align: right">Total</th>
                        @if($order->status === 'selesai')
                        <th style="text-align: center">Ulasan</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $i)
                    <tr>
                        <td>
                            <div class="product-cell">
                                <img src="{{ $i->produk->image_url ?? asset('images/placeholder-produk.jpg') }}" class="product-thumb">
                                <span class="product-name">{{ $i->produk->nama_produk ?? 'Produk dihapus' }}</span>
                            </div>
                        </td>
                        <td>Rp {{ number_format((int)$i->price,0,',','.') }}</td>
                        <td>{{ (int)$i->quantity }}</td>
                        <td style="text-align: right">Rp {{ number_format((int)$i->price * $i->quantity, 0, ',', '.') }}</td>
                        @if($order->status === 'selesai' && $i->produk)
                        <td style="text-align: center">
                            @php
                                $hasReviewed = \App\Models\Review::where('user_id', auth()->id())
                                    ->where('produk_id', $i->produk_id)
                                    ->where('order_id', $order->order_id)
                                    ->exists();
                            @endphp
                            
                            @if($hasReviewed)
                                <span class="badge-reviewed">Sudah Diulas</span>
                            @else
                                <details class="review-details">
                                    <summary class="btn-review" style="list-style: none;">Beri Ulasan</summary>
                                    <div class="review-form-container" style="margin-top: 10px; padding: 15px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; position: absolute; right: 2rem; z-index: 10; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                                        <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="produk_id" value="{{ $i->produk_id }}">
                                            <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                                            
                                            <div style="margin-bottom: 10px;">
                                                <label style="display: block; font-size: 0.85rem; color: #4b5563; margin-bottom: 5px;">Rating</label>
                                                <div class="star-rating-input">
                                                    @for($r = 5; $r >= 1; $r--)
                                                    <input type="radio" id="star{{$r}}-{{$i->produk_id}}" name="rating" value="{{$r}}" required>
                                                    <label for="star{{$r}}-{{$i->produk_id}}" title="{{$r}} stars">★</label>
                                                    @endfor
                                                </div>
                                            </div>

                                            <div style="margin-bottom: 10px;">
                                                <label style="display: block; font-size: 0.85rem; color: #4b5563; margin-bottom: 5px;">Komentar</label>
                                                <textarea name="comment" rows="3" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px;" placeholder="Bagaimana kualitas produk ini?"></textarea>
                                            </div>

                                            <div style="margin-bottom: 10px;">
                                                <label style="display: block; font-size: 0.85rem; color: #4b5563; margin-bottom: 5px;">Foto (Opsional)</label>
                                                <input type="file" name="image" accept="image/*" style="font-size: 0.85rem;">
                                            </div>

                                            <button type="submit" style="width: 100%; background: #dc2626; color: white; border: none; padding: 8px; border-radius: 6px; font-weight: 600; cursor: pointer;">Kirim Ulasan</button>
                                        </form>
                                    </div>
                                </details>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-body" style="background: #f9fafb; border-top: 1px solid #e5e7eb;">
            <div style="max-width: 300px; margin-left: auto;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #6b7280; font-size: 0.9rem;">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format((int)$order->subtotal,0,',','.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #6b7280; font-size: 0.9rem;">
                    <span>Ongkos Kirim</span>
                    <span>Rp {{ number_format((int)$order->shipping,0,',','.') }}</span>
                </div>
                @if($order->tax > 0)
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #6b7280; font-size: 0.9rem;">
                    <span>Pajak</span>
                    <span>Rp {{ number_format((int)$order->tax,0,',','.') }}</span>
                </div>
                @endif
                <div style="display: flex; justify-content: space-between; margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed #d1d5db; font-weight: 700; font-size: 1.1rem; color: #1f2937;">
                    <span>Total Bayar</span>
                    <span style="color: #dc2626;">Rp {{ number_format((int)$order->total,0,',','.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Review Modal Removed --}}
@endsection
