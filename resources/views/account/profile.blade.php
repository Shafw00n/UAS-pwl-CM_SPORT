@extends('layouts.master')

@section('title', 'Akun Saya')

@push('styles')
<style>
    body {
        background-color: #fdfdfd;
        background-image: 
            radial-gradient(at 0% 0%, rgba(230, 0, 35, 0.03) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(20, 20, 20, 0.03) 0px, transparent 50%),
            linear-gradient(#f0f0f0 1.5px, transparent 1.5px), 
            linear-gradient(90deg, #f0f0f0 1.5px, transparent 1.5px);
        background-size: 100% 100%, 100% 100%, 40px 40px, 40px 40px;
        background-position: 0 0, 0 0, -1.5px -1.5px, -1.5px -1.5px;
    }
    .account-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 3.5rem 1.5rem 5rem;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
    .account-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
        position: relative;
    }
    .account-user {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    .account-avatar {
        width: 72px;
        height: 72px;
        border-radius: 999px;
        background: radial-gradient(circle at 30% 0, #ffffff 0, #fecaca 30%, #b91c1c 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        font-size: 2rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.16);
        border: 4px solid #fff;
    }
    .account-user-text {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .account-user-name {
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #111827;
        line-height: 1.2;
    }
    .account-user-email {
        font-size: 1.05rem;
        color: #4b5563;
        font-weight: 500;
    }
    .account-user-meta {
        font-size: 0.95rem;
        color: #9ca3af;
    }
    .account-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .account-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.1rem;
        border-radius: 12px;
        background: #ffffff;
        color: #374151;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .account-chip svg {
        color: #6b7280;
        width: 18px;
        height: 18px;
    }
    .account-chip.primary {
        background: #111827;
        color: #f9fafb;
        border-color: #111827;
    }
    .account-chip.primary svg {
        color: #f9fafb;
    }
    .account-chip:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        color: #111827;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .account-chip.primary:hover {
        background: #27272a;
        border-color: #27272a;
    }
    .account-grid {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(0, 1.4fr);
        gap: 2rem;
    }
    .account-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid rgba(229, 231, 235, 0.5);
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.06);
        padding: 2.25rem;
    }
    .account-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.75rem;
        flex-direction: column;
        gap: 0.5rem;
    }
    .account-card-title {
        font-weight: 800;
        font-size: 1.35rem;
        color: #111827;
        letter-spacing: -0.01em;
    }
    .account-card-sub {
        font-size: 1rem;
        color: #6b7280;
        line-height: 1.5;
    }
    .account-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.25rem 1.5rem;
    }
    .account-form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }
    .account-form-group.full {
        grid-column: 1 / -1;
    }
    .account-label {
        font-size: 0.95rem;
        font-weight: 600;
        color: #374151;
        margin-left: 0.1rem;
    }
    .account-input {
        width: 100%;
        border-radius: 12px;
        border: 1px solid #d1d5db;
        padding: 0.85rem 1rem;
        font-size: 1rem;
        outline: none;
        transition: all 0.2s;
        background-color: #f9fafb;
        color: #111827;
    }
    .account-input:focus {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
        background-color: #ffffff;
    }
    .account-input[disabled] {
        background: #f3f4f6;
        color: #9ca3af;
        cursor: not-allowed;
        border-color: #e5e7eb;
    }
    .account-button-row {
        display: flex;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }
    .account-btn-primary {
        padding: 0.85rem 1.75rem;
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, #ef4444, #b91c1c);
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 10px 20px -5px rgba(239, 68, 68, 0.4);
        transition: all 0.2s;
    }
    .account-btn-primary:hover {
        transform: translateY(-2px);
        background: linear-gradient(135deg, #dc2626, #991b1b);
        box-shadow: 0 15px 25px -5px rgba(239, 68, 68, 0.5);
    }
    .account-btn-primary:active {
        transform: translateY(0);
        box-shadow: 0 5px 10px -5px rgba(239, 68, 68, 0.4);
    }
    .account-alert {
        margin-bottom: 2rem;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
    }
    .account-alert-success {
        background: #ecfdf3;
        border: 1px solid #bbf7d0;
        color: #166534;
    }
    .account-alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
    }
    .account-alert svg {
        flex-shrink: 0;
    }
    @media (max-width: 900px) {
        .account-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }
    @media (max-width: 640px) {
        .account-page {
            padding: 1.5rem 1.1rem 3rem;
        }
        .account-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .account-actions {
            width: 100%;
        }
        .account-chip {
            flex: 1;
            justify-content: center;
        }
        .account-form-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="account-page">
    <div class="account-header">
        <div class="account-user">
            <div class="account-avatar">
                {{ strtoupper(substr(auth()->user()->username ?? auth()->user()->name ?? auth()->user()->email, 0, 1)) }}
            </div>
            <div class="account-user-text">
                <div class="account-user-name">
                    {{ auth()->user()->nama_lengkap ?? auth()->user()->name ?? auth()->user()->username ?? 'Pengguna' }}
                </div>
                <div class="account-user-email">
                    {{ auth()->user()->email }}
                </div>
                <div class="account-user-meta">
                    Akun pelanggan CM SPORT
                </div>
            </div>
        </div>
        <div class="account-actions">
            <a href="{{ route('account.orders') }}" class="account-chip primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10h-4"/>
                    <path d="M16 14h-4"/>
                    <path d="M10 10H8"/>
                </svg>
                Pesanan Saya
            </a>
            <a href="{{ route('account.help') }}" class="account-chip">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9.09 9a3 3 0 1 1 5.83 1c-.36 1.05-1.35 1.5-1.92 1.9-.57.39-.92.86-.92 1.6V15"/>
                    <line x1="12" y1="18" x2="12.01" y2="18"/>
                </svg>
                Pusat Bantuan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="account-alert account-alert-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="account-alert account-alert-error">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="account-grid">
        <div class="account-card">
            <div class="account-card-header">
                <div class="account-card-title">Informasi Profil</div>
                <div class="account-card-sub">Kelola data utama akun Anda</div>
            </div>
            <form action="{{ route('account.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="account-form-grid">
                    <div class="account-form-group">
                        <label class="account-label">Username</label>
                        <input type="text" name="username" value="{{ old('username', auth()->user()->username ?? '') }}" class="account-input">
                    </div>
                    <div class="account-form-group">
                        <label class="account-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', auth()->user()->nama_lengkap ?? auth()->user()->name ?? '') }}" class="account-input">
                    </div>
                    <div class="account-form-group full">
                        <label class="account-label">Alamat Lengkap</label>
                        <input type="text" name="alamat" value="{{ old('alamat', auth()->user()->alamat ?? '') }}" class="account-input">
                    </div>
                    <div class="account-form-group">
                        <label class="account-label">No. Telepon</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', auth()->user()->no_telepon ?? '') }}" class="account-input">
                    </div>
                    <div class="account-form-group">
                        <label class="account-label">Email</label>
                        <input type="email" value="{{ auth()->user()->email }}" class="account-input" disabled>
                    </div>
                </div>
                <div class="account-button-row">
                    <button type="submit" class="account-btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <div class="account-card">
            <div class="account-card-header">
                <div class="account-card-title">Ubah Password</div>
                <div class="account-card-sub">Pastikan password baru Anda kuat dan aman</div>
            </div>
            <form action="{{ route('account.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="account-form-grid">
                    <div class="account-form-group full">
                        <label class="account-label">Password Baru</label>
                        <input type="password" name="password" class="account-input" required>
                    </div>
                    <div class="account-form-group full">
                        <label class="account-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="account-input" required>
                    </div>
                </div>
                <div class="account-button-row">
                    <button type="submit" class="account-btn-primary">
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
