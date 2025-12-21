@extends('layouts.master')

@section('title', 'Admin Dashboard - CM SPORT')

@section('content')
<div class="admin-layout">
    @include('admin.partials.sidebar')
    <div class="admin-content">
        <div class="admin-header">
            <div>
                <div class="admin-title">Dashboard Overview</div>
                <p style="color: #666; margin-top: 0.2rem;">Selamat datang kembali, Admin! Berikut ringkasan toko hari ini.</p>
            </div>
            <div class="date-badge">
                {{ now()->format('d M Y') }}
            </div>
        </div>

        <!-- KPI Stats Grid -->
        <div class="admin-kpi">
            <div class="kpi-item primary-kpi">
                <div class="kpi-icon-wrapper">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                </div>
                <div>
                    <div class="kpi-label">Produk Aktif</div>
                    <div class="kpi-value">{{ $produkAktif }}</div>
                    <div class="kpi-sub">Item tersedia di katalog</div>
                </div>
            </div>
            
            <div class="kpi-item">
                <div class="kpi-icon-wrapper" style="background: #e3f2fd; color: #1565c0;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                </div>
                <div>
                    <div class="kpi-label">Order Hari Ini</div>
                    <div class="kpi-value">{{ $pesananBaru }}</div>
                    <div class="kpi-sub">Pesanan masuk baru</div>
                </div>
            </div>

            <div class="kpi-item">
                <div class="kpi-icon-wrapper" style="background: #e8f5e9; color: #2e7d32;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
                <div>
                    <div class="kpi-label">Omzet Hari Ini</div>
                    <div class="kpi-value">Rp {{ number_format($nilaiKeranjangHariIni,0,',','.') }}</div>
                    <div class="kpi-sub">Total pendapatan kotor</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; color: #333;">Akses Cepat</h3>
        <div class="quick-actions-grid">
            <a href="{{ route('admin.categories') }}" class="action-card">
                <div class="action-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                </div>
                <div class="action-info">
                    <span class="action-title">Kelola Kategori</span>
                    <span class="action-desc">Atur kategori produk</span>
                </div>
                <div class="action-arrow">→</div>
            </a>

            <a href="{{ route('admin.products') }}" class="action-card">
                <div class="action-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                </div>
                <div class="action-info">
                    <span class="action-title">Kelola Produk</span>
                    <span class="action-desc">Tambah/Edit produk</span>
                </div>
                <div class="action-arrow">→</div>
            </a>

            <a href="{{ route('admin.orders') }}" class="action-card">
                <div class="action-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <div class="action-info">
                    <span class="action-title">Kelola Transaksi</span>
                    <span class="action-desc">Cek pesanan masuk</span>
                </div>
                <div class="action-arrow">→</div>
            </a>

            <a href="{{ route('admin.finance') }}" class="action-card">
                <div class="action-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                </div>
                <div class="action-info">
                    <span class="action-title">Laporan Keuangan</span>
                    <span class="action-desc">Rekap pemasukan</span>
                </div>
                <div class="action-arrow">→</div>
            </a>
            
             <a href="{{ route('admin.notifications.create') }}" class="action-card">
                <div class="action-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                </div>
                <div class="action-info">
                    <span class="action-title">Kirim Notifikasi</span>
                    <span class="action-desc">Broadcast ke user</span>
                </div>
                <div class="action-arrow">→</div>
            </a>
        </div>
    </div>
</div>

<style>
/* Dashboard Specific Styles */
.date-badge {
    background: #fff;
    border: 1px solid #e0e0e0;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    color: #444;
    font-size: 0.9rem;
}

.kpi-item {
    display: flex;
    align-items: center;
    gap: 1.2rem;
    padding: 1.5rem;
    border-radius: 16px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.kpi-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
}

.primary-kpi .kpi-icon-wrapper {
    background: #fff5f6;
    color: #E8001D;
}

.kpi-icon-wrapper {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.kpi-label {
    font-size: 0.85rem;
    color: #666;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.2rem;
}

.kpi-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: #111;
    line-height: 1.2;
}

.kpi-sub {
    font-size: 0.8rem;
    color: #888;
    margin-top: 0.2rem;
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}

.action-card {
    background: #fff;
    border: 1px solid #eee;
    padding: 1.2rem;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 1rem;
    text-decoration: none;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.action-card:hover {
    border-color: #E8001D;
    background: #fff;
    box-shadow: 0 4px 12px rgba(232, 0, 29, 0.08);
}

.action-icon {
    width: 42px;
    height: 42px;
    background: #f8f9fa;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #444;
    transition: all 0.2s;
}

.action-card:hover .action-icon {
    background: #E8001D;
    color: #fff;
}

.action-info {
    flex: 1;
}

.action-title {
    display: block;
    font-weight: 700;
    color: #111;
    font-size: 1rem;
    margin-bottom: 0.2rem;
}

.action-desc {
    display: block;
    font-size: 0.8rem;
    color: #666;
}

.action-arrow {
    font-size: 1.2rem;
    color: #ddd;
    font-weight: 300;
    transition: transform 0.2s;
}

.action-card:hover .action-arrow {
    color: #E8001D;
    transform: translateX(4px);
}
</style>
@endsection
