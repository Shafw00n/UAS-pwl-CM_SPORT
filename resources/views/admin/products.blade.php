@extends('layouts.master')

@section('title', 'Kelola Produk - CM SPORT')

@section('content')
<div class="admin-layout">
    @include('admin.partials.sidebar')
    <div class="admin-content">
        <div class="admin-header">
            <div class="admin-title">Kelola Produk</div>
            <div class="admin-actions">
            </div>
        </div>
        @push('styles')
        <style>
        .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.6);display:none;align-items:center;justify-content:center;z-index:1000}
        .modal-overlay:target{display:flex}
        .modal-card{background:#fff;border-radius:12px;padding:1.2rem;box-shadow:0 12px 32px rgba(0,0,0,.25);width:min(92vw,480px)}
        .modal-title{font-weight:800;font-size:1.1rem;margin-bottom:.35rem;color:#111}
        .modal-sub{color:#666;margin-bottom:.9rem}
        .modal-actions{display:flex;gap:.5rem;justify-content:flex-end}
        .btn-admin.equal{min-width:110px;text-align:center}
        </style>
        @endpush
        <div class="admin-card">
            <form id="productForm" action="{{ isset($editing) ? route('admin.products.update', $editing->produk_id) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" style="display:grid;grid-template-columns:repeat(5,1fr);gap:.8rem;align-items:end">
                @csrf
                @if(isset($editing))
                @method('PUT')
                @endif
                <div style="grid-column:1/-1">
                    <label>Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="btn-admin" style="width:100%">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoriList as $k)
                        <option value="{{ $k->kategori_id }}" {{ isset($editing) && $editing->kategori_id == $k->kategori_id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" id="nama_produk" value="{{ isset($editing) ? $editing->nama_produk : old('nama_produk') }}" class="btn-admin" style="width:100%">
                </div>
                <div>
                    <label>Harga</label>
                    <input type="number" name="harga" id="harga" value="{{ isset($editing) ? $editing->harga : old('harga') }}" class="btn-admin" style="width:100%">
                </div>
                <div>
                    <label>Stok</label>
                    <input type="number" name="stok" id="stok" value="{{ isset($editing) ? $editing->stok : old('stok') }}" class="btn-admin" style="width:100%">
                </div>
                <div>
                    <label>Aktif</label>
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ isset($editing) ? ($editing->is_active ? 'checked' : '') : (old('is_active') ? 'checked' : '') }}>
                </div>
                <div>
                    <label>Gambar Produk</label>
                    <input type="file" name="gambar" id="gambar" accept="image/*" class="btn-admin" style="width:100%">
                    @if(isset($editing) && $editing->gambar)
                    <div style="margin-top:.5rem">
                        <img src="{{ $editing->image_url }}" alt="Preview" style="width:100px;height:100px;object-fit:cover;border-radius:8px;border:1px solid #eee">
                    </div>
                    @endif
                </div>
                <div style="grid-column:1/-1">
                    <label>Deskripsi</label>
                    <input type="text" name="deskripsi" id="deskripsi" value="{{ isset($editing) ? $editing->deskripsi : old('deskripsi') }}" class="btn-admin" style="width:100%">
                </div>
                <div>
                    @if(isset($editing))
                        <a href="#confirm-edit" class="btn-admin primary equal">Simpan Perubahan</a>
                        <a href="{{ route('admin.products') }}" class="btn-admin equal">Batal</a>
                    @else
                        <button type="submit" class="btn-admin primary equal">Tambah</button>
                    @endif
                </div>
            </form>
        </div>
        @if(isset($editing))
        <div id="confirm-edit" class="modal-overlay">
            <div class="modal-card">
                <div class="modal-title">Simpan Perubahan?</div>
                <div class="modal-sub">Perubahan produk akan diterapkan.</div>
                <div class="modal-actions">
                    <button type="submit" form="productForm" class="btn-admin primary equal">Ya, Simpan</button>
                    <a href="#" class="btn-admin equal">Tidak</a>
                </div>
            </div>
        </div>
        @endif
        <div class="admin-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $i)
                    <tr>
                        <td>{{ $i->nama_produk }}</td>
                        <td>{{ $i->kategori->nama_kategori ?? '-' }}</td>
                        <td>Rp {{ number_format($i->harga,0,',','.') }}</td>
                        <td>{{ $i->stok }}</td>
                        <td>
                            <a href="{{ route('admin.products.edit', $i->produk_id) }}" class="btn-admin equal">Edit</a>
                            <a href="#confirm-delete-{{ $i->produk_id }}" class="btn-admin equal">Hapus</a>
                            <div id="confirm-delete-{{ $i->produk_id }}" class="modal-overlay">
                                <div class="modal-card">
                                    <div class="modal-title">Hapus Produk?</div>
                                    <div class="modal-sub">Tindakan ini tidak dapat dibatalkan.</div>
                                    <div class="modal-actions">
                                        <form action="{{ route('admin.products.destroy', $i->produk_id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-admin primary equal">Ya, Hapus</button>
                                        </form>
                                        <a href="#" class="btn-admin equal">Tidak</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var kategori=document.getElementById('kategori_id');
  var fields=['nama_produk','harga','stok','is_active','gambar','deskripsi'];
  function setDisabled(){
    var disabled=!kategori.value;
    fields.forEach(function(id){var el=document.getElementById(id); if(el){el.disabled=disabled;}});
  }
  setDisabled();
  kategori.addEventListener('change',setDisabled);
});
</script>
@endsection
