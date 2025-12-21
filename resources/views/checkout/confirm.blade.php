@extends('layouts.master')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
<div class="admin-content" style="padding:2rem 1.2rem">
    <div class="admin-header">
        <div class="admin-title">Konfirmasi Pembayaran Order #{{ $order->order_id }}</div>
    </div>
    <div class="admin-card" style="max-width:600px">
        <form action="{{ route('checkout.confirm.submit', $order->order_id) }}" method="POST" enctype="multipart/form-data" style="display:grid;grid-template-columns:1fr;gap:.8rem">
            @csrf
            <div>
                <label>Metode Pembayaran</label>
                <select name="payment_method" class="btn-admin" style="width:100%">
                    <option value="transfer">Transfer Bank</option>
                    <option value="ewallet">E-Wallet</option>
                </select>
            </div>
            <div>
                <label>Bukti Pembayaran</label>
                <input type="file" name="payment_proof" accept="image/*" class="btn-admin" style="width:100%">
            </div>
            <div>
                <button type="submit" class="btn-admin primary">Kirim Konfirmasi</button>
                <a href="{{ route('checkout.success', $order->order_id) }}" class="btn-admin">Batal</a>
            </div>
        </form>
    </div>
    <div class="admin-card">
        <div><strong>Total Dibayar:</strong> Rp {{ number_format($order->total,0,',','.') }}</div>
    </div>
</div>
@endsection

