@extends('layouts.master')

@section('title', 'Kelola Transaksi - CM SPORT')

@section('content')
<div class="admin-layout">
    @include('admin.partials.sidebar')
    <div class="admin-content">
        <div class="admin-header">
            <div class="admin-title">Kelola Transaksi</div>
            <div class="admin-actions"></div>
        </div>
        <div class="admin-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $i)
                    <tr>
                        <td>{{ $i['kode'] }}</td>
                        <td>{{ $i['tipe'] }}</td>
                        <td>Rp {{ number_format($i['jumlah'],0,',','.') }}</td>
                        <td>{{ $i['tanggal'] }}</td>
                        <td>
                            <a href="#" class="btn-admin">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
