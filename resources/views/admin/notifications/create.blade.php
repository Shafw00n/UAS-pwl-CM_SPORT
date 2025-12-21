@extends('layouts.master')

@section('title', 'Kirim Notifikasi Promo')

@section('content')
<div class="admin-layout">
    @include('admin.partials.sidebar')
    <div class="admin-content">
        <div class="admin-header">
            <div>
                <div class="admin-title">Kirim Notifikasi Promo</div>
                <div class="admin-subtitle">Broadcast promo ke seluruh pengguna</div>
            </div>
        </div>

        <div class="promo-grid">
            <!-- Form Section -->
            <div class="admin-card">
                <div class="card-header-styled">
                    <div class="header-icon">📢</div>
                    <h3>Buat Pesan Promo</h3>
                </div>
                
                <form action="{{ route('admin.notifications.store') }}" method="POST" class="promo-form">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Judul Promo</label>
                        <div class="input-wrapper">
                            <input type="text" name="title" id="input-title" class="form-input-styled" required placeholder="Contoh: Diskon Akhir Tahun 50%">
                            <span class="input-icon">🏷️</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pesan Notifikasi</label>
                        <div class="input-wrapper">
                            <textarea name="message" id="input-message" class="form-input-styled" rows="5" required placeholder="Tuliskan detail promo menarik di sini..."></textarea>
                        </div>
                        <div class="char-count"><span id="char-count">0</span>/255 karakter</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Link Tujuan (Opsional)</label>
                        <div class="input-wrapper">
                            <input type="url" name="link" class="form-input-styled" placeholder="https://cmsport.com/promo-spesial">
                            <span class="input-icon">🔗</span>
                        </div>
                        <small style="color:#666; font-size:0.8rem; margin-top:0.3rem; display:block">
                            *Biarkan kosong jika hanya info teks
                        </small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-admin primary btn-block">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right:0.5rem">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Kirim ke Semua User
                        </button>
                    </div>
                </form>
            </div>

            <!-- Preview Section -->
            <div class="preview-section">
                <div class="preview-label">Live Preview</div>
                
                <!-- Notification Dropdown Preview -->
                <div class="preview-card">
                    <div class="preview-header">
                        <span class="dot"></span> Notifikasi Baru
                    </div>
                    <div class="preview-item">
                        <div class="preview-icon-box">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div class="preview-content">
                            <div class="preview-title" id="preview-title">Judul Promo...</div>
                            <div class="preview-message" id="preview-message">Isi pesan akan muncul di sini...</div>
                            <div class="preview-time">Baru saja</div>
                        </div>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="tips-card">
                    <h4>💡 Tips Promo Efektif</h4>
                    <ul>
                        <li>Gunakan judul yang singkat dan 'catchy' (maks 30-40 karakter).</li>
                        <li>Sertakan Call-to-Action yang jelas di dalam pesan.</li>
                        <li>Pastikan link tujuan valid jika disertakan.</li>
                        <li>Waktu terbaik mengirim promo adalah jam 12 siang atau 7 malam.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .promo-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 2rem;
        align-items: start;
    }

    @media (max-width: 900px) {
        .promo-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Card Styling */
    .admin-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        padding: 2rem;
    }

    .card-header-styled {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }

    .header-icon {
        width: 40px;
        height: 40px;
        background: #fff5f5;
        color: var(--primary-red);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    /* Form Styling */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--medium-gray);
        font-size: 0.95rem;
    }

    .input-wrapper {
        position: relative;
    }

    .form-input-styled {
        width: 100%;
        padding: 0.8rem 1rem;
        padding-right: 2.5rem;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.2s;
        background: #fdfdfd;
        font-family: inherit;
    }

    .form-input-styled:focus {
        border-color: var(--primary-red);
        outline: none;
        box-shadow: 0 0 0 3px rgba(232, 0, 29, 0.1);
        background: white;
    }

    .input-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.1rem;
        opacity: 0.5;
        pointer-events: none;
    }

    textarea.form-input-styled {
        padding-right: 1rem;
        resize: vertical;
        min-height: 100px;
    }

    .char-count {
        text-align: right;
        font-size: 0.75rem;
        color: #999;
        margin-top: 0.25rem;
    }

    .btn-block {
        width: 100%;
        justify-content: center;
        padding: 1rem;
        font-size: 1rem;
        display: flex;
        align-items: center;
    }

    /* Preview Styling */
    .preview-section {
        position: sticky;
        top: 2rem;
    }

    .preview-label {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #999;
        margin-bottom: 1rem;
    }

    .preview-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid #eee;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .preview-header {
        background: #f8f9fa;
        padding: 0.8rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: #666;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .dot {
        width: 8px;
        height: 8px;
        background: var(--primary-red);
        border-radius: 50%;
        display: inline-block;
    }

    .preview-item {
        padding: 1rem;
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        background: #fff; /* Unread bg usually white/highlighted */
    }

    .preview-icon-box {
        width: 40px;
        height: 40px;
        background: #fff5f5;
        color: var(--primary-red);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .preview-content {
        flex: 1;
    }

    .preview-title {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.2rem;
        color: var(--black);
        line-height: 1.3;
    }

    .preview-message {
        font-size: 0.85rem;
        color: #666;
        line-height: 1.4;
        margin-bottom: 0.5rem;
        word-break: break-word;
    }

    .preview-time {
        font-size: 0.75rem;
        color: #aaa;
    }

    /* Tips Card */
    .tips-card {
        background: #e3f2fd;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #bbdefb;
    }

    .tips-card h4 {
        color: #1565c0;
        font-size: 0.95rem;
        margin-bottom: 0.8rem;
    }

    .tips-card ul {
        padding-left: 1.2rem;
        margin: 0;
    }

    .tips-card li {
        color: #1976d2;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@endsection
