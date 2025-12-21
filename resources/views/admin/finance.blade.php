@extends('layouts.master')

@section('title', 'Kelola Keuangan - CM SPORT')

@push('styles')
<style>
    /* KPI Cards */
    .finance-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .kpi-card-new {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
        transition: transform 0.2s;
    }
    .kpi-card-new:hover { transform: translateY(-3px); }
    
    .kpi-icon-bg {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 5rem;
        opacity: 0.1;
        transform: rotate(-15deg);
    }
    
    .kpi-label { font-size: 0.9rem; color: #666; margin-bottom: 0.5rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
    .kpi-value-lg { font-size: 1.8rem; font-weight: 700; color: #1a1a1a; letter-spacing: -0.5px; }
    
    .kpi-green { border-left: 4px solid #2e7d32; }
    .kpi-green .kpi-value-lg { color: #2e7d32; }
    
    .kpi-red { border-left: 4px solid #c62828; }
    .kpi-red .kpi-value-lg { color: #c62828; }
    
    .kpi-blue { border-left: 4px solid #1565c0; }
    .kpi-blue .kpi-value-lg { color: #1565c0; }

    /* Form Styles */
    .form-label-sm { font-size: 0.85rem; font-weight: 600; color: #444; margin-bottom: 0.4rem; display: block; }
    .form-input-styled {
        width: 100%;
        padding: 0.6rem 1rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.2s;
        background: #fdfdfd;
    }
    .form-input-styled:focus {
        border-color: var(--primary-red);
        outline: none;
        box-shadow: 0 0 0 3px rgba(230, 0, 0, 0.1);
        background: white;
    }
    
    /* Transaction Badge */
    .badge-trans {
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .badge-in { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    .badge-out { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
    
    .text-green { color: #2e7d32; }
    .text-red { color: #c62828; }
</style>
@endpush

@section('content')
<div class="admin-layout">
    @include('admin.partials.sidebar')
    <div class="admin-content">
        <div class="admin-header">
            <div>
                <div class="admin-title">Kelola Keuangan</div>
                <div class="admin-subtitle">Laporan pendapatan dan pengeluaran</div>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.finance.export.csv') }}" class="btn-admin" style="display:flex; align-items:center; gap:0.5rem">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Export CSV
                </a>
            </div>
        </div>

        <div class="finance-kpi-grid">
            <div class="kpi-card-new kpi-green">
                <div class="kpi-label">Penjualan Bulan Ini</div>
                <div class="kpi-value-lg">Rp {{ number_format($ringkasan['penjualan_bulan_ini'],0,',','.') }}</div>
                <div class="kpi-icon-bg">💰</div>
            </div>
            <div class="kpi-card-new kpi-red">
                <div class="kpi-label">Pengeluaran Bulan Ini</div>
                <div class="kpi-value-lg">Rp {{ number_format($ringkasan['pengeluaran_bulan_ini'],0,',','.') }}</div>
                <div class="kpi-icon-bg">💸</div>
            </div>
            <div class="kpi-card-new kpi-blue">
                <div class="kpi-label">Laba Bersih</div>
                <div class="kpi-value-lg">Rp {{ number_format($ringkasan['laba_bulan_ini'],0,',','.') }}</div>
                <div class="kpi-icon-bg">📈</div>
            </div>
        </div>

        <div class="admin-card" style="margin-bottom: 2rem;">
            <div class="card-header" style="background: #fafafa; padding: 1rem 1.5rem; border-bottom: 1px solid #eee;">
                <div style="font-weight: 700; color: #333; display:flex; align-items:center; gap:0.5rem;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Catat Pengeluaran Baru
                </div>
            </div>
            <div style="padding: 1.5rem;">
                <form action="{{ route('admin.expenses.store') }}" method="POST" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:1.2rem; align-items:end">
                    @csrf
                    <div>
                        <label class="form-label-sm">Tanggal</label>
                        <input type="date" name="date" value="{{ now()->toDateString() }}" class="form-input-styled">
                    </div>
                    <div>
                        <label class="form-label-sm">Jumlah Pengeluaran (Rp)</label>
                        <input type="number" name="amount" min="0" step="1" class="form-input-styled" placeholder="0">
                    </div>
                    <div style="grid-column: 1/-1">
                        <label class="form-label-sm">Keterangan / Keperluan</label>
                        <input type="text" name="description" class="form-input-styled" placeholder="Contoh: Gaji Karyawan, Listrik, dll">
                    </div>
                    <div>
                        <button type="submit" class="btn-admin primary" style="width:100%; justify-content:center;">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right:0.4rem"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="admin-card">
            <div class="card-header" style="padding: 1.25rem;">
                <div class="card-title">Riwayat Transaksi (Bulan Ini)</div>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            <th style="text-align:right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $i)
                        <tr>
                            <td style="color:#555; font-family:monospace;">{{ \Carbon\Carbon::parse($i['tanggal'])->format('d M Y') }}</td>
                            <td>
                                @if($i['kategori'] == 'Penjualan')
                                    <span class="badge-trans badge-in">Pemasukan</span>
                                @else
                                    <span class="badge-trans badge-out">Pengeluaran</span>
                                @endif
                            </td>
                            <td style="font-weight:500; color:#333;">{{ $i['keterangan'] }}</td>
                            <td class="{{ $i['jumlah'] < 0 ? 'text-red' : 'text-green' }}" style="text-align:right; font-weight:600;">
                                {{ $i['jumlah'] < 0 ? '-' : '+' }} Rp {{ number_format(abs($i['jumlah']),0,',','.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:2rem; color:#888;">Belum ada data transaksi bulan ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
