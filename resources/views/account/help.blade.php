@extends('layouts.master')

@section('title', 'Pusat Bantuan')

@section('content')
<div class="admin-content" style="padding:2rem 1.2rem;max-width:900px;margin:0 auto">
    <div class="admin-header">
        <div class="admin-title">Pusat Bantuan</div>
        <div class="admin-actions">
            <a href="{{ route('account.profile') }}" class="btn-admin">Akun</a>
            <a href="{{ route('account.orders') }}" class="btn-admin">Pesanan</a>
        </div>
    </div>
    <div class="admin-card">
        <div style="display:grid;gap:.6rem">
            <div>
                <strong>Cara Membatalkan Pesanan</strong>
                <div>Masuk ke menu Pesanan, buka pesanan, pilih Batalkan jika status belum dikirim.</div>
            </div>
            <div>
                <strong>Status Pesanan</strong>
                <div>Menunggu Pembayaran, Sedang Dikemas, Dikirim, Selesai, Dibatalkan.</div>
            </div>
            <div>
                <strong>Hubungi Kami</strong>
                <div>Email: support@cmsport.local, Telepon: 0812-0000-0000</div>
            </div>
        </div>
    </div>
</div>
@endsection

