@extends('layouts.master')

@section('title', 'Kelola Kategori - CM SPORT')

@push('styles')
<style>
    /* Layout Grid */
    .admin-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 1.5rem;
        align-items: start;
    }
    
    @media (max-width: 900px) {
        .admin-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Cards */
    .admin-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border: 1px solid #f0f0f0;
        overflow: hidden;
    }

    .sticky-form {
        position: sticky;
        top: 20px;
        align-self: start;
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

    .card-body {
        padding: 1.5rem;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #444;
        font-size: 0.9rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .form-input:focus {
        border-color: var(--primary-red);
        outline: none;
        box-shadow: 0 0 0 3px rgba(232, 0, 29, 0.1);
    }

    .form-helper {
        font-size: 0.8rem;
        color: #888;
        margin-top: 0.25rem;
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

    .custom-table tr:last-child td {
        border-bottom: none;
    }

    .custom-table tr:hover {
        background-color: #fdfdfd;
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .badge-blue {
        background: #e3f2fd;
        color: #1565c0;
    }
    
    .badge-gray {
        background: #f5f5f5;
        color: #616161;
    }

    /* Buttons */
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background: #fff3e0;
        color: #ef6c00;
        margin-right: 0.25rem;
    }
    
    .btn-edit:hover {
        background: #ffe0b2;
    }

    .btn-delete {
        background: #ffebee;
        color: #c62828;
    }
    
    .btn-delete:hover {
        background: #ffcdd2;
    }

    .btn-submit {
        width: 100%;
        padding: 0.85rem;
        background: var(--primary-red);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-submit:hover {
        background: var(--dark-red);
        transform: translateY(-1px);
    }
    
    .btn-cancel {
        width: 100%;
        padding: 0.85rem;
        background: #f5f5f5;
        color: #333;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
        display: block;
        text-decoration: none;
        margin-top: 0.5rem;
    }
    
    .btn-cancel:hover {
        background: #e0e0e0;
    }

    /* Modal Overlay (Reusing existing style but refined) */
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
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .empty-state {
        padding: 3rem;
        text-align: center;
        color: #888;
    }
    
    .alert-box {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }
    
    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }
    
    .alert-error {
        background: #ffebee;
        color: #c62828;
        border: 1px solid #ffcdd2;
    }
</style>
@endpush

@section('content')
<div class="admin-layout">
    @include('admin.partials.sidebar')
    
    <div class="admin-content">
        <div class="admin-header">
            <div class="admin-title">Kelola Kategori</div>
            <div class="admin-subtitle">Manajemen kategori produk untuk toko Anda</div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert-box alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-box alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-box alert-error">
                <ul style="padding-left: 1rem; margin: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="admin-grid">
            {{-- LEFT COLUMN: FORM --}}
            <div class="admin-card sticky-form">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ isset($editing) ? 'Edit Kategori' : 'Tambah Kategori' }}
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ isset($editing) ? route('admin.categories.update', $editing->kategori_id) : route('admin.categories.store') }}" method="POST">
                        @csrf
                        @if(isset($editing))
                            @method('PUT')
                        @endif

                        <div class="form-group">
                            <label class="form-label">Nama Kategori <span style="color:red">*</span></label>
                            <input type="text" name="nama_kategori" 
                                   value="{{ isset($editing) ? $editing->nama_kategori : old('nama_kategori') }}" 
                                   class="form-input" 
                                   placeholder="Contoh: Sepatu Lari"
                                   required>
                            <div class="form-helper">Slug akan digenerate otomatis.</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-input" rows="3" placeholder="Deskripsi singkat kategori (opsional)">{{ isset($editing) ? $editing->deskripsi : old('deskripsi') }}</textarea>
                        </div>

                        <button type="submit" class="btn-submit">
                            @if(isset($editing))
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Perubahan
                            @else
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Kategori
                            @endif
                        </button>

                        @if(isset($editing))
                            <a href="{{ route('admin.categories') }}" class="btn-cancel">Batal Edit</a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- RIGHT COLUMN: LIST --}}
            <div class="admin-card">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Daftar Kategori
                    </div>
                    <div class="badge badge-gray">{{ $items->count() }} Item</div>
                </div>
                
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Produk</th>
                                <th style="text-align:right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                            <tr>
                                <td>
                                    <div style="font-weight:600; color:#222;">{{ $item->nama_kategori }}</div>
                                    <div style="font-size:0.8rem; color:#888;">/{{ $item->slug }}</div>
                                </td>
                                <td>
                                    {{ Str::limit($item->deskripsi, 50) ?: '-' }}
                                </td>
                                <td>
                                    <span class="badge badge-blue">
                                        {{ $item->produk_count }} Produk
                                    </span>
                                </td>
                                <td style="text-align:right">
                                    <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                                        <a href="{{ route('admin.categories.edit', $item->kategori_id) }}" class="btn-action btn-edit" title="Edit">
                                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <a href="#delete-modal-{{ $item->kategori_id }}" class="btn-action btn-delete" title="Hapus">
                                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </a>
                                    </div>

                                    {{-- Delete Modal (Pure CSS/HTML) --}}
                                    <div id="delete-modal-{{ $item->kategori_id }}" class="modal-overlay">
                                        <div class="modal-card">
                                            <div class="modal-title" style="color:#d32f2f">Hapus Kategori?</div>
                                            <div class="modal-sub">
                                                Apakah Anda yakin ingin menghapus kategori <strong>{{ $item->nama_kategori }}</strong>?
                                                <br><br>
                                                <small style="color:#666">
                                                    @if($item->produk_count > 0)
                                                        <span style="color:red">Peringatan: Kategori ini memiliki {{ $item->produk_count }} produk. Penghapusan mungkin gagal.</span>
                                                    @else
                                                        Tindakan ini tidak dapat dibatalkan.
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="modal-actions">
                                                <a href="#" class="btn-cancel" style="width:auto; display:inline-block; margin:0">Batal</a>
                                                <form action="{{ route('admin.categories.destroy', $item->kategori_id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-submit" style="background:#d32f2f; width:auto; display:inline-flex; margin:0">Ya, Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="empty-state">
                                    <svg width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="#e0e0e0" style="margin-bottom:1rem">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                    <p>Belum ada kategori yang ditambahkan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection