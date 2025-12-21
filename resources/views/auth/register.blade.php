@extends('layouts.master')

@section('title', 'Daftar Akun - CM SPORT')
@push('styles')
<style>
    :root {
        --primary-red: #e60023;
        --dark-red: #b3001b;
        --bg-pattern: #f8f9fa;
    }

    .auth-page { 
        display:flex; 
        align-items:center; 
        justify-content:center; 
        min-height:calc(100vh - 70px); 
        padding:2rem; 
        background-color: #fdfdfd;
        background-image: 
            radial-gradient(at 0% 0%, rgba(230, 0, 35, 0.03) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(20, 20, 20, 0.03) 0px, transparent 50%),
            linear-gradient(#f0f0f0 1.5px, transparent 1.5px), 
            linear-gradient(90deg, #f0f0f0 1.5px, transparent 1.5px);
        background-size: 100% 100%, 100% 100%, 40px 40px, 40px 40px;
        background-position: 0 0, 0 0, -1.5px -1.5px, -1.5px -1.5px;
    }
    
    .auth-wrap { width:100%; max-width:1000px; perspective: 1000px; }
    
    .auth-card { 
        display:grid; 
        grid-template-columns: 1.1fr 1fr; 
        background: var(--white); 
        border-radius:28px; 
        box-shadow: 
            0 20px 40px -10px rgba(0,0,0,0.1), 
            0 0 0 1px rgba(0,0,0,0.05); 
        overflow:hidden; 
        opacity: 0;
        animation: slideUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        min-height: 600px;
    }
    
    @keyframes slideUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .auth-brand { 
        position:relative; 
        background: #0a0a0a;
        color: var(--white); 
        padding:4rem 3rem; 
        display:flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
        z-index: 1;
    }
    
    /* Dynamic Background for Brand Section */
    .auth-brand::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: 
            radial-gradient(circle at 80% 10%, rgba(230, 0, 35, 0.15) 0%, transparent 40%),
            radial-gradient(circle at 10% 90%, rgba(50, 50, 50, 0.3) 0%, transparent 40%);
        z-index: -1;
    }
    
    .brand-pattern {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23333333' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.5;
        z-index: -1;
    }
    
    /* Floating shapes */
    .shape {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        z-index: -1;
        opacity: 0.6;
    }
    .shape-1 { width: 300px; height: 300px; background: rgba(230, 0, 35, 0.15); top: -100px; right: -100px; animation: float 10s infinite ease-in-out alternate; }
    .shape-2 { width: 200px; height: 200px; background: rgba(255, 255, 255, 0.05); bottom: -50px; left: -50px; animation: float 15s infinite ease-in-out alternate-reverse; }

    @keyframes float {
        0% { transform: translate(0, 0) rotate(0deg); }
        100% { transform: translate(20px, 30px) rotate(10deg); }
    }
    
    .brand-logo { 
        font-size:2rem; 
        font-weight:900; 
        letter-spacing:-1px; 
        display:flex; 
        align-items:center; 
        gap:.75rem; 
        margin-bottom: 2rem;
    }
    .brand-logo span { color: var(--primary-red); }
    
    .brand-content { margin-top: auto; position: relative; }
    .brand-title { 
        font-size: 2.5rem; 
        font-weight: 800; 
        line-height: 1.1; 
        margin-bottom: 1.5rem; 
        letter-spacing: -1px; 
        background: linear-gradient(to bottom right, #ffffff, #aaaaaa); 
        -webkit-background-clip: text; 
        -webkit-text-fill-color: transparent; 
    }
    .brand-sub { font-size:1.1rem; opacity:.8; line-height: 1.6; margin-bottom: 3rem; font-weight: 400; max-width: 90%; }
    
    .brand-features { display:grid; gap:1.25rem; }
    .brand-features li { 
        display:flex; 
        align-items:center; 
        gap:1rem; 
        font-weight:500; 
        font-size: 1rem; 
        color: rgba(255,255,255,0.9);
        transform: translateX(-20px);
        opacity: 0;
        animation: slideInRight 0.5s forwards;
    }
    
    .brand-features li:nth-child(1) { animation-delay: 0.4s; }
    .brand-features li:nth-child(2) { animation-delay: 0.6s; }
    .brand-features li:nth-child(3) { animation-delay: 0.8s; }

    @keyframes slideInRight {
        to { transform: translateX(0); opacity: 1; }
    }

    .brand-icon { 
        width:24px; 
        height:24px; 
        color: #ff3b5c; 
        background: rgba(255,255,255,0.08); 
        padding:5px; 
        border-radius: 8px; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .auth-panel { 
        padding:2.5rem 3rem; 
        display:flex; 
        flex-direction: column; 
        justify-content: center;
        position: relative;
        overflow-y: auto;
        max-height: 800px;
    }
    
    .auth-heading { font-size:2rem; font-weight:800; letter-spacing:-0.5px; color: #111; margin-bottom: 0.5rem; }
    .auth-sub { font-size:1rem; color: #666; margin-bottom: 2rem; }
    
    .auth-alert { 
        background: #FEF2F2; 
        border-left: 4px solid #EF4444; 
        color: #991B1B; 
        padding:1rem; 
        border-radius:8px; 
        font-weight:500; 
        font-size: 0.9rem; 
        margin-bottom: 2rem; 
        display: flex; 
        align-items: center; 
        gap: 0.75rem;
        box-shadow: 0 4px 6px rgba(239, 68, 68, 0.05);
    }
    
    .input-group { position:relative; margin-bottom: 1.25rem; }
    .input-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.4rem;
        margin-left: 0.2rem;
    }
    
    .input-icon { position:absolute; left:1.2rem; top:2.35rem; transform:translateY(-50%); color: #9ca3af; transition: 0.2s; pointer-events: none; }
    
    .auth-input { 
        width:100%; 
        padding:0.9rem 1rem 0.9rem 3rem; 
        border:2px solid #e5e7eb; 
        border-radius:12px; 
        outline:none; 
        font-size:0.95rem; 
        background:#fff; 
        transition:all 0.2s; 
        color: #111; 
        font-weight: 500; 
    }
    .auth-input:focus { 
        border-color: var(--primary-red); 
        box-shadow: 0 0 0 4px rgba(230, 0, 35, 0.1); 
    }
    .auth-input:focus + .input-icon { color: var(--primary-red); }
    
    .auth-button { 
        background: var(--primary-red); 
        color: var(--white); 
        border:none; 
        border-radius:12px; 
        padding:1.1rem; 
        font-weight:700; 
        font-size: 1rem; 
        letter-spacing:0.5px; 
        width:100%; 
        cursor:pointer; 
        transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        box-shadow: 0 10px 20px -5px rgba(230, 0, 35, 0.4); 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 0.75rem; 
        margin-top: 1.5rem;
    }
    .auth-button:hover { 
        background: var(--dark-red); 
        transform: translateY(-3px); 
        box-shadow: 0 15px 30px -5px rgba(230, 0, 35, 0.5); 
    }
    .auth-button:active { transform: translateY(-1px); }
    
    .auth-footer { margin-top:2rem; text-align: center; font-size: 0.95rem; color: #666; }
    .auth-link { color: var(--primary-red); text-decoration:none; font-weight:700; transition: 0.2s; }
    .auth-link:hover { color: var(--dark-red); text-decoration:underline; }

    @media (max-width: 900px) { 
        .auth-card { grid-template-columns: 1fr; min-height: auto; } 
        .auth-brand { padding: 3rem 2rem; }
        .shape-1 { top: -50px; right: -50px; width: 200px; height: 200px; }
        .auth-panel { padding: 2.5rem 2rem; max-height: none; overflow-y: visible; }
        .brand-title { font-size: 2rem; }
    }
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-wrap">
        <div class="auth-card">
            <div class="auth-brand">
                <div class="brand-pattern"></div>
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                
                <div class="brand-logo">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color:var(--primary-red)"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                    CM <span>SPORT</span>
                </div>
                
                <div class="brand-content">
                    <div class="brand-title">Bergabung Bersama Kami.</div>
                    <div class="brand-sub">Nikmati kemudahan berbelanja peralatan olahraga dengan berbagai keuntungan eksklusif member.</div>
                    
                    <ul class="brand-features">
                        <li>
                            <svg class="brand-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                            Akses ke Produk Terbaru
                        </li>
                        <li>
                            <svg class="brand-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            Promo & Diskon Spesial
                        </li>
                        <li>
                            <svg class="brand-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            Komunitas Olahraga Aktif
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="auth-panel">
                <div>
                    <div class="auth-heading">Buat Akun Baru</div>
                    <div class="auth-sub">Lengkapi data diri Anda untuk mendaftar.</div>
                </div>

                @if($errors->any())
                <div class="auth-alert">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Mohon periksa kembali inputan Anda.
                </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}">
                    @csrf
                    
                    <div class="input-group">
                        <label class="input-label" for="username">Username</label>
                        <input type="text" id="username" name="username" class="auth-input" value="{{ old('username') }}" placeholder="Pilih username unik" required>
                        <span class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="email">Alamat Email</label>
                        <input type="email" id="email" name="email" class="auth-input" value="{{ old('email') }}" placeholder="nama@email.com" required>
                        <span class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </span>
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="nama_lengkap">Nama Lengkap <span style="font-weight:400; color:#888; font-size:0.8em;">(Opsional)</span></label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" class="auth-input" value="{{ old('nama_lengkap') }}" placeholder="Nama lengkap Anda">
                        <span class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                    </div>
                    
                    <div class="input-group">
                        <label class="input-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="auth-input" placeholder="Minimal 6 karakter" required>
                        <span class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="auth-input" placeholder="Ulangi password" required>
                        <span class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </span>
                    </div>
                    
                    <button type="submit" class="auth-button">
                        Daftar Sekarang
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </button>
                    
                    <div class="auth-footer">
                        Sudah punya akun? <a href="{{ route('login') }}" class="auth-link">Masuk Disini</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection