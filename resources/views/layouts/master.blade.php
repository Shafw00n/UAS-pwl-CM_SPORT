<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'CM SPORT')</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', 'CM SPORT - Toko online peralatan olahraga terlengkap dengan harga terbaik. Jual sepatu olahraga, pakaian olahraga, dan aksesoris fitness berkualitas.')">
    <meta name="keywords" content="@yield('keywords', 'toko olahraga, sepatu olahraga, pakaian olahraga, peralatan gym, aksesoris fitness')">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', 'CM SPORT')">
    <meta property="og:description" content="@yield('description', 'Toko online peralatan olahraga terlengkap')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary-red: #E8001D;
            --dark-red: #C20015;
            --black: #000000;
            --dark-gray: #1a1a1a;
            --medium-gray: #333333;
            --light-gray: #f5f5f5;
            --white: #ffffff;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #fdfdfd;
            background-image: 
                radial-gradient(at 0% 0%, rgba(230, 0, 35, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(20, 20, 20, 0.03) 0px, transparent 50%),
                linear-gradient(#f0f0f0 1.5px, transparent 1.5px), 
                linear-gradient(90deg, #f0f0f0 1.5px, transparent 1.5px);
            background-size: 100% 100%, 100% 100%, 40px 40px, 40px 40px;
            background-position: 0 0, 0 0, -1.5px -1.5px, -1.5px -1.5px;
            color: var(--black);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .main-content {
            min-height: calc(100vh - 70px);
        }
        
        
        
        /* Footer Styles */
        .footer {
            background: linear-gradient(135deg, var(--black) 0%, var(--dark-gray) 100%);
            color: var(--white);
            padding: 4rem 0 2rem;
            border-top: 3px solid var(--primary-red);
        }
        
        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .footer-center {
            display: flex;
            align-items: baseline;
            justify-content: flex-start;
            gap: .6rem;
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--white);
            letter-spacing: 0;
            line-height: 1;
            min-height: 40px;
        }
        .footer-center .cm { color: var(--white); }
        .footer-center .sport { color: var(--primary-red); position: relative; display: inline-block; padding-bottom: 3px; }
        .footer-center .sport::after { content: none; display: none; }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }
        .footer-col h4 {
            font-size: 1.1rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        .footer-links {
            list-style: none;
            display: grid;
            gap: .5rem;
        }
        .footer-links a {
            color: rgba(255,255,255,.85);
            text-decoration: none;
            transition: color .2s;
        }
        .footer-links a:hover { color: var(--primary-red); }
        .footer-social {
            display: flex;
            gap: .6rem;
            align-items: center;
        }
        .footer-social .icon {
            width: 36px; height: 36px;
            display:flex; align-items:center; justify-content:center;
            border-radius: 50%;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            color: var(--white);
        }
        .footer-payments { display:flex; gap:.6rem; flex-wrap:wrap; align-items:center; }
        .payment-pill {
            padding: .35rem .6rem;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,.15);
            color: rgba(255,255,255,.9);
            background: rgba(255,255,255,.08);
            font-size: .85rem;
        }
        .footer-newsletter {
            display: flex; gap: .5rem; margin-top: .75rem;
        }
        .footer-newsletter input {
            flex:1; padding:.6rem .8rem;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,.2);
            background: rgba(255,255,255,.1);
            color: var(--white);
            outline: none;
        }
        .footer-newsletter input::placeholder { color: rgba(255,255,255,.6); }
        .footer-newsletter button {
            padding:.6rem 1rem; border-radius:8px; border:none;
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
            color: var(--white); font-weight:700;
        }
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 2rem;
            text-align: center;
        }
        
        .footer-bottom-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .copyright {
            color: rgba(255, 255, 255, 0.6);
        }
        
        
        
        /* Notification Toast - Static Alert Style */
        .toast-container {
            width: 100%;
            margin-bottom: 1.5rem;
        }
        
        .toast {
            background: var(--dark-gray);
            color: var(--white);
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-red);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .toast.success {
            border-left-color: #00C853;
            background: #e8f5e9;
            color: #1b5e20;
            border: 1px solid #c8e6c9;
            border-left-width: 4px;
        }
        
        .toast.error {
            border-left-color: var(--primary-red);
            background: #ffebee;
            color: #b71c1c;
            border: 1px solid #ffcdd2;
            border-left-width: 4px;
        }
        
        .toast.info {
            border-left-color: #2196F3;
            background: #e3f2fd;
            color: #0d47a1;
            border: 1px solid #bbdefb;
            border-left-width: 4px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .container {
                padding: 0 1rem;
            }
            
            .footer-bottom-content {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-bottom-links {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .scroll-top {
                bottom: 1rem;
                right: 1rem;
                width: 45px;
                height: 45px;
            }
            
            .toast-container {
                right: 1rem;
                left: 1rem;
                max-width: none;
                bottom: 1rem;
            }
        }
        
        @media (max-width: 1024px) {
            .container {
                padding: 0 1.5rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="{{ auth()->check() && auth()->user()->isAdmin() ? 'admin-view' : '' }}">
    
    
    <!-- Navbar -->
    @include('layouts.navbar')
    
    <!-- PHP Flash Messages (Toast) -->
    @if(session('success'))
    <div class="toast-container" style="position: fixed; top: 100px; right: 20px; z-index: 9999;">
        <div class="toast success" style="display: flex; animation: slideIn 0.5s ease-out;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="toast-container" style="position: fixed; top: 100px; right: 20px; z-index: 9999;">
        <div class="toast error" style="display: flex; animation: slideIn 0.5s ease-out;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif
    
    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
    
    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>
    
    @if(!request()->is('admin*'))
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-center"><span class="cm">CM</span><span class="sport">SPORT</span></div>
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>Belanja</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('produk.all') }}">Produk</a></li>
                        <li><a href="{{ route('trending.index') }}">Trending</a></li>
                        <li><a href="{{ route('promo.index') }}">Sale</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Bantuan</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                        <li><a href="{{ route('shipping') }}">Pengiriman</a></li>
                        <li><a href="{{ route('terms') }}">Syarat & Ketentuan</a></li>
                        <li><a href="{{ route('privacy') }}">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Kontak</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('contact') }}">Hubungi Kami</a></li>
                        <li><a href="{{ route('about') }}">Tentang CM SPORT</a></li>
                        <li><span class="payment-pill">WhatsApp: 08xx-xxxx-xxxx</span></li>
                        <li><span class="payment-pill">Email: support@cmsport.id</span></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Ikuti Kami</h4>
                    <div class="footer-social">
                        <span class="icon">💬</span>
                        <span class="icon">📺</span>
                        <span class="icon">📷</span>
                        <span class="icon">🐦</span>
                    </div>
                    <h4 style="margin-top:1rem">Pembayaran</h4>
                    <div class="footer-payments">
                        <span class="payment-pill">Visa</span>
                        <span class="payment-pill">Mastercard</span>
                        <span class="payment-pill">Gopay</span>
                        <span class="payment-pill">OVO</span>
                        <span class="payment-pill">Bank Transfer</span>
                    </div>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="footer-newsletter">
                        @csrf
                        <input type="email" name="email" placeholder="Email untuk promo terbaru">
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <div class="copyright">© {{ date('Y') }} CM SPORT. All rights reserved.</div>
                    <div class="footer-bottom-links" style="display:flex; gap:1rem;">
                        <a href="{{ route('terms') }}" style="color:rgba(255,255,255,.7); text-decoration:none;">Terms</a>
                        <a href="{{ route('privacy') }}" style="color:rgba(255,255,255,.7); text-decoration:none;">Privacy</a>
                        <a href="{{ route('contact') }}" style="color:rgba(255,255,255,.7); text-decoration:none;">Contact</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    @endif
    
    @stack('scripts')
</body>
</html>
