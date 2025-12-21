@extends('layouts.master')

@section('title', 'Kelola Transaksi - CM SPORT')

@push('styles')
<style>
    /* Cards */
    .admin-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border: 1px solid #f0f0f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .card-header {
        padding: 1.25rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fafafa;
    }

    .card-title {
        font-weight: 700;
        font-size: 1.1rem;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Table Styles */
    .table-responsive {
        overflow-x: auto;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table th {
        text-align: left;
        padding: 1rem 1.5rem;
        background: #f8f9fa;
        font-weight: 600;
        color: #555;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #eee;
    }

    .custom-table td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
        color: #333;
    }

    .custom-table tr:hover {
        background-color: #fdfdfd;
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.8rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .badge-gray { background: #f5f5f5; color: #616161; border: 1px solid #e0e0e0; }
    .badge-yellow { background: #fff8e1; color: #f57f17; border: 1px solid #ffe0b2; }
    .badge-green { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    .badge-blue { background: #e3f2fd; color: #1565c0; border: 1px solid #bbdefb; }
    .badge-red { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
    .badge-purple { background: #f3e5f5; color: #7b1fa2; border: 1px solid #e1bee7; }

    /* Buttons */
    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        font-weight: 500;
    }
    
    .btn-outline { background: white; border: 1px solid #ddd; color: #333; }
    .btn-outline:hover { background: #f5f5f5; border-color: #ccc; }
    
    .btn-primary { background: var(--primary-red); color: white; border: 1px solid var(--primary-red); }
    .btn-primary:hover { background: var(--dark-red); }
    
    .btn-success { background: #2e7d32; color: white; border: 1px solid #2e7d32; }
    .btn-success:hover { background: #1b5e20; }
    
    .btn-danger { background: #c62828; color: white; border: 1px solid #c62828; }
    .btn-danger:hover { background: #b71c1c; }

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
    
    .modal-overlay:target {
        display: flex;
        animation: fadeIn 0.2s ease-out;
    }

    .modal-card {
        background: white;
        border-radius: 12px;
        width: min(90vw, 700px);
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }

    .modal-header {
        padding: 1.25rem;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-body {
        padding: 1.5rem;
        overflow-y: auto;
    }
    
    .modal-footer {
        padding: 1.25rem;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
        background: #fafafa;
        border-radius: 0 0 12px 12px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-box {
        background: #f9f9f9;
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid #eee;
    }
    
    .info-label { font-size: 0.8rem; color: #666; margin-bottom: 0.25rem; }
    .info-value { font-weight: 600; color: #333; }

    .product-preview-stack {
        display: flex;
        align-items: center;
    }
    .product-preview-stack img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid white;
        object-fit: cover;
        margin-left: -10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .product-preview-stack img:first-child { margin-left: 0; }
    .product-preview-stack .more-count {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f0f0f0;
        border: 2px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #666;
        margin-left: -10px;
        font-weight: 600;
    }

    /* Notification Item */
    .notif-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s;
    }
    .notif-item:last-child { border-bottom: none; }
    .notif-item:hover { background: #fafafa; }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.98); }
        to { opacity: 1; transform: scale(1); }
    }
</style>
@endpush

@section('content')
<div class="admin-layout">
    @include('admin.partials.sidebar')
    
    <div class="admin-content">
        <div class="admin-header">
            <div>
                <div class="admin-title">Kelola Transaksi</div>
                <div class="admin-subtitle">Pantau dan kelola pesanan masuk</div>
            </div>
            <div class="admin-actions">
                <a href="{{ route('notifications.all') }}" class="btn-sm btn-outline" style="position:relative">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Notifikasi
                    @if(($notifUnread ?? 0) > 0)
                        <span style="position:absolute; top:-5px; right:-5px; background:red; color:white; font-size:10px; padding:2px 6px; border-radius:10px; border:2px solid white;">{{ $notifUnread }}</span>
                    @endif
                </a>
            </div>
        </div>

        {{-- Notifications Panel (Collapsible/Conditional) --}}
        @if(($notifRecent ?? collect())->count() > 0)
        <div class="admin-card">
            <div class="card-header">
                <div class="card-title" style="font-size:0.95rem">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#f57f17">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Aktivitas Terbaru
                </div>
                <a href="{{ route('notifications.all') }}" style="font-size:0.85rem; color:var(--primary-red); text-decoration:none;">Lihat Semua</a>
            </div>
            <div style="max-height: 200px; overflow-y: auto;">
                @foreach($notifRecent as $n)
                <div class="notif-item">
                    <div style="flex:1">
                        <div style="font-weight:600; font-size:0.9rem; margin-bottom:0.1rem;">{{ $n->title }}</div>
                        <div style="color:#666; font-size:0.85rem;">{{ $n->message }}</div>
                        <div style="color:#999; font-size:0.75rem; margin-top:0.25rem;">{{ $n->created_at?->diffForHumans() }}</div>
                    </div>
                    <div style="display:flex; gap:0.5rem; align-items:center">
                        @if(!$n->is_read)
                        <form action="{{ route('notifications.mark-read', $n->id) }}" method="POST">
                            @csrf @method('PUT')
                            <button type="submit" class="btn-sm btn-outline" style="padding:0.2rem 0.5rem; font-size:0.75rem;">Tandai Baca</button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Orders Table --}}
        <div class="admin-card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Daftar Pesanan
                </div>
                <div class="badge badge-gray">{{ count($orders) }} Transaksi</div>
            </div>
            
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Produk</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status Pesanan</th>
                            <th>Pembayaran</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $o)
                        <tr>
                            <td>
                                <span style="font-family:monospace; font-weight:600; color:#444;">#{{ $o->order_id }}</span>
                            </td>
                            <td>
                                <div class="product-preview-stack">
                                    @foreach($o->items->take(3) as $item)
                                        @if($item->produk)
                                            <img src="{{ $item->produk->image_url }}" alt="Prod" title="{{ $item->produk->nama_produk }}">
                                        @endif
                                    @endforeach
                                    @if($o->items->count() > 3)
                                        <div class="more-count">+{{ $o->items->count() - 3 }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div style="font-weight:600; color:#222;">{{ $o->user->name ?? 'User Missing' }}</div>
                                <div style="font-size:0.8rem; color:#888;">{{ $o->user->email ?? '-' }}</div>
                            </td>
                            <td style="font-weight:600;">Rp {{ number_format($o->total,0,',','.') }}</td>
                            <td>
                                @php
                                    $statusClass = 'badge-gray';
                                    if($o->status == 'menunggu_pembayaran') $statusClass = 'badge-yellow';
                                    elseif($o->status == 'diproses') $statusClass = 'badge-blue';
                                    elseif($o->status == 'dikirim') $statusClass = 'badge-purple';
                                    elseif($o->status == 'selesai') $statusClass = 'badge-green';
                                    elseif($o->status == 'dibatalkan' || $o->status == 'ditolak') $statusClass = 'badge-red';
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $o->status)) }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size:0.85rem; font-weight:500;">{{ strtoupper($o->payment_status ?? 'UNPAID') }}</div>
                                @if($o->payment_proof_url)
                                    <a href="{{ $o->payment_proof_url }}" target="_blank" style="font-size:0.75rem; color:var(--primary-red); text-decoration:none;">Lihat Bukti &nearr;</a>
                                @endif
                            </td>
                            <td style="font-size:0.9rem; color:#555;">
                                {{ \Carbon\Carbon::parse($o->created_at)->format('d M Y') }}<br>
                                <span style="font-size:0.75rem; color:#999;">{{ \Carbon\Carbon::parse($o->created_at)->format('H:i') }}</span>
                            </td>
                            <td>
                                <a href="#detail-{{ $o->order_id }}" class="btn-sm btn-outline">
                                    Detail
                                </a>

                                {{-- Modal Detail --}}
                                <div id="detail-{{ $o->order_id }}" class="modal-overlay">
                                    <div class="modal-card">
                                        <div class="modal-header">
                                            <div class="card-title">Detail Pesanan #{{ $o->order_id }}</div>
                                            <a href="#" style="color:#999; text-decoration:none; font-size:1.5rem; line-height:1;">&times;</a>
                                        </div>
                                        
                                        <div class="modal-body">
                                            {{-- Status Bar --}}
                                            <div class="info-grid">
                                                <div class="info-box">
                                                    <div class="info-label">Status Pesanan</div>
                                                    <div class="info-value">
                                                        <span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $o->status)) }}</span>
                                                    </div>
                                                </div>
                                                <div class="info-box">
                                                    <div class="info-label">Pembayaran</div>
                                                    <div class="info-value">
                                                        {{ strtoupper($o->payment_status ?? '-') }}
                                                    </div>
                                                </div>
                                                <div class="info-box">
                                                    <div class="info-label">Tanggal Order</div>
                                                    <div class="info-value">{{ \Carbon\Carbon::parse($o->created_at)->format('d F Y, H:i') }}</div>
                                                </div>
                                            </div>

                                            {{-- Customer Note --}}
                                            @if($o->admin_note)
                                            <div style="margin-bottom:1.5rem; background:#fff3e0; padding:1rem; border-radius:8px; border:1px solid #ffe0b2;">
                                                <div class="info-label" style="color:#ef6c00;">Catatan / Alamat Pengiriman</div>
                                                <div style="color:#333; font-size:0.9rem; margin-top:0.25rem;">{{ $o->admin_note }}</div>
                                            </div>
                                            @endif

                                            {{-- Products Table --}}
                                            <div style="margin-bottom:1.5rem;">
                                                <h4 style="margin-bottom:0.75rem; color:#333;">Produk Dipesan</h4>
                                                <table class="custom-table" style="border:1px solid #eee;">
                                                    <thead style="background:#f9f9f9;">
                                                        <tr>
                                                            <th style="padding:0.75rem;">Produk</th>
                                                            <th style="padding:0.75rem; text-align:center;">Qty</th>
                                                            <th style="padding:0.75rem; text-align:right;">Harga</th>
                                                            <th style="padding:0.75rem; text-align:right;">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($o->items as $item)
                                                        <tr>
                                                            <td style="padding:0.75rem;">
                                                                <div style="display:flex; align-items:center; gap:0.75rem;">
                                                                    <div style="width:40px; height:40px; border-radius:4px; overflow:hidden; background:#eee; flex-shrink:0;">
                                                                        @if($item->produk)
                                                                            <img src="{{ $item->produk->image_url }}" 
                                                                                 alt="{{ $item->produk->nama_produk }}"
                                                                                 style="width:100%; height:100%; object-fit:cover;"
                                                                                 onerror="this.onerror=null;this.src='https://via.placeholder.com/40?text=IMG';">
                                                                        @else
                                                                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#999; font-size:0.6rem;">No Img</div>
                                                                        @endif
                                                                    </div>
                                                                    <div style="font-weight:500; font-size:0.9rem;">
                                                                        {{ $item->produk->nama_produk ?? 'Produk dihapus' }}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td style="text-align:center; padding:0.75rem;">{{ $item->quantity }}</td>
                                                            <td style="text-align:right; padding:0.75rem;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                                            <td style="text-align:right; padding:0.75rem;">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot style="background:#fafafa;">
                                                        <tr>
                                                            <td colspan="3" style="text-align:right; padding:0.75rem; font-weight:600;">Subtotal</td>
                                                            <td style="text-align:right; padding:0.75rem; font-weight:600;">Rp {{ number_format($o->subtotal, 0, ',', '.') }}</td>
                                                        </tr>
                                                        @if($o->shipping > 0)
                                                        <tr>
                                                            <td colspan="3" style="text-align:right; padding:0.75rem; color:#666;">Ongkos Kirim</td>
                                                            <td style="text-align:right; padding:0.75rem; color:#666;">Rp {{ number_format($o->shipping, 0, ',', '.') }}</td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <td colspan="3" style="text-align:right; padding:0.75rem; font-weight:700; font-size:1.1rem; color:var(--primary-red);">Total Bayar</td>
                                                            <td style="text-align:right; padding:0.75rem; font-weight:700; font-size:1.1rem; color:var(--primary-red);">Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>

                                            {{-- Payment Proof --}}
                                            @if($o->payment_proof_url)
                                            <div style="margin-bottom:1.5rem;">
                                                <h4 style="margin-bottom:0.75rem; color:#333;">Bukti Pembayaran</h4>
                                                <a href="{{ $o->payment_proof_url }}" target="_blank" style="display:inline-block; border:1px solid #ddd; border-radius:8px; overflow:hidden;">
                                                    <img src="{{ $o->payment_proof_url }}" alt="Bukti Transfer" style="max-width:200px; display:block;">
                                                </a>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="modal-footer">
                                            {{-- Action Buttons Logic --}}
                                            @if($o->payment_status === 'submitted')
                                                <form action="{{ route('admin.orders.approve', $o->order_id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn-sm btn-success">
                                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                        Terima Pembayaran
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.orders.reject', $o->order_id) }}" method="POST" onsubmit="return confirm('Tolak pembayaran ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn-sm btn-danger">
                                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                        Tolak
                                                    </button>
                                                </form>
                                            
                                            @elseif($o->status === 'menunggu_pembayaran' || $o->status === 'pending')
                                                <form action="{{ route('admin.orders.status', $o->order_id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="diproses">
                                                    <button type="submit" class="btn-sm btn-success">Proses Pesanan</button>
                                                </form>
                                                <form action="{{ route('admin.orders.status', $o->order_id) }}" method="POST" onsubmit="return confirm('Batalkan pesanan?')">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="dibatalkan">
                                                    <button type="submit" class="btn-sm btn-danger">Batalkan</button>
                                                </form>

                                            @elseif($o->status === 'diproses' || $o->status === 'sedang_dikemas')
                                                <form action="{{ route('admin.orders.status', $o->order_id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="dikirim">
                                                    <button type="submit" class="btn-sm btn-primary">
                                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                                        Kirim Barang
                                                    </button>
                                                </form>

                                            @elseif($o->status === 'dikirim')
                                                <form action="{{ route('admin.orders.status', $o->order_id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="selesai">
                                                    <button type="submit" class="btn-sm btn-success">
                                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                        Selesaikan Pesanan
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <a href="#" class="btn-sm btn-outline">Tutup</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:3rem; color:#888;">
                                <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#eee" style="margin-bottom:1rem">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p>Belum ada transaksi pesanan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
