@extends('layouts.master')

@section('title', 'Lupa Password - CM SPORT')
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
    
    .auth-panel { 
        padding:4rem; 
        display:flex; 
        flex-direction: column; 
        justify-content: center;
        position: relative;
    }
    
    .auth-heading { font-size:2rem; font-weight:800; letter-spacing:-0.5px; color: #111; margin-bottom: 0.5rem; }
    .auth-sub { font-size:1rem; color: #666; margin-bottom: 2.5rem; }
    
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
    
    .input-group { position:relative; margin-bottom: 1.5rem; }
    .input-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        margin-left: 0.2rem;
    }
    
    .input-icon { position:absolute; left:1.2rem; top:2.4rem; transform:translateY(-50%); color: #9ca3af; transition: 0.2s; pointer-events: none; }
    
    .auth-input { 
        width:100%; 
        padding:1rem 1rem 1rem 3rem; 
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
    }
    .auth-button:hover { 
        background: var(--dark-red); 
        transform: translateY(-3px); 
        box-shadow: 0 15px 30px -5px rgba(230, 0, 35, 0.5); 
    }
    .auth-button:active { transform: translateY(-1px); }
    
    .auth-footer { margin-top:2.5rem; text-align: center; font-size: 0.95rem; color: #666; }
    .auth-link { color: var(--primary-red); text-decoration:none; font-weight:700; transition: 0.2s; }
    .auth-link:hover { color: var(--dark-red); text-decoration:underline; }

    @media (max-width: 900px) { 
        .auth-card { grid-template-columns: 1fr; min-height: auto; } 
        .auth-brand { padding: 3rem 2rem; }
        .shape-1 { top: -50px; right: -50px; width: 200px; height: 200px; }
        .auth-panel { padding: 2.5rem 2rem; }
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
                    <div class="brand-title">Reset Password.</div>
                    <div class="brand-sub">Jangan khawatir, kami akan membantu Anda mengatur ulang kata sandi Anda agar aman kembali.</div>
                </div>
            </div>
            
            <div class="auth-panel">
                <div>
                    <div class="auth-heading">Lupa Password? 🔒</div>
                    <div class="auth-sub">Masukkan email Anda untuk menerima link reset password.</div>
                </div>

                @if (session('status'))
                    <div class="auth-alert" style="background: #ecfdf5; border-color: #10b981; color: #047857;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                <div class="auth-alert">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="input-group">
                        <label class="input-label" for="email">Alamat Email</label>
                        <input type="email" id="email" name="email" class="auth-input" value="{{ old('email') }}" placeholder="Contoh: nama@email.com" required>
                        <span class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </span>
                    </div>
                    
                    <button type="submit" class="auth-button">
                        Kirim Link Reset
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                    
                    <div class="auth-footer">
                        Ingat password Anda? <a href="{{ route('login') }}" class="auth-link">Masuk Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
