@extends('layouts.master')

@section('title', 'CM SPORT - Toko Peralatan Olahraga Terlengkap')
@section('description', 'Temukan koleksi sepatu, pakaian, dan peralatan olahraga berkualitas tinggi untuk semua jenis aktivitas. Gratis ongkir & harga terbaik!')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-container">
        <div class="hero-content">
            <span class="hero-badge">New Collection for 2026</span>
            <h1 class="hero-title">Wujudkan <span>Performa</span> Terbaikmu</h1>
            <p class="hero-description">
                Koleksi peralatan olahraga premium untuk atlet profesional maupun pemula. 
                Dapatkan diskon hingga 50% untuk produk pilihan!
            </p>
            <div class="hero-buttons">
                <a href="{{ route('produk.all') }}" class="btn-primary">Belanja Sekarang</a>
                <a href="#produk" class="btn-secondary">Lihat Koleksi</a>
            </div>
        </div>
        <div class="hero-image">
            <div class="hero-image-container">
                <img src="{{ asset('template/img/hero-store.jpg') }}" alt="CM SPORT Store Interior" class="store-image">
                <div class="image-gradient"></div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>
                <h3>Gratis Ongkir</h3>
                <p>Pengiriman gratis untuk pembelian minimal Rp 500.000</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <h3>Pengiriman Cepat</h3>
                <p>Pesanan diproses dalam 24 jam dan dikirim langsung</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <h3>Produk Original</h3>
                <p>100% produk original dengan garansi resmi</p>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="container">
        <div class="about-grid">
            <div class="about-image">
                <img src="{{ asset('template/img/cmlogo.png') }}" alt="CM SPORT Store">
            </div>
            <div class="about-content">
                <span class="section-subtitle">Tentang Kami</span>
                <h2 class="section-title">Toko Olahraga Terpercaya di Indonesia</h2>
                <p class="about-description">
                    <strong>CM SPORT</strong> adalah destinasi bagi pecinta olahraga dan gaya hidup aktif.
                    Sejak didirikan pada tahun 2014, kami telah melayani banyak pelanggan dengan menyediakan
                    peralatan olahraga berkualitas.
                </p>
                <p class="about-description">
                    Kami berkomitmen memberikan pengalaman berbelanja yang baik dengan produk original,
                    harga kompetitif, dan layanan pelanggan yang responsif. Menyediakan alat perlengkapan
                    olahraga lengkap untuk mendukung aktivitas Anda.
                </p>
                <div class="about-stats">
                    <div class="stat-item">
                        <h3>50K+</h3>
                        <p>Pelanggan Puas</p>
                    </div>
                    <div class="stat-item">
                        <h3>10+</h3>
                        <p>Tahun Berpengalaman</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Produk Section -->
<section class="produk-section" id="produk">
    <div class="container">
        <div class="section-header">
            <span class="section-subtitle">Produk Pilihan</span>
            <h2 class="section-title">Koleksi Terbaru Kami</h2>
            <p class="section-description">
                Temukan produk-produk terbaik untuk mendukung aktivitas olahraga Anda
            </p>
        </div>

        <!-- kategori Tabs (Dinamis) -->
        <div class="kategori-tabs">
            <a href="{{ route('home') }}" class="tab-btn {{ empty($selectedSlug) ? 'active' : '' }}">Semua</a>
            @foreach(($kategoriList ?? collect()) as $kat)
                <a href="{{ route('home', ['kategori' => $kat->slug]) }}" class="tab-btn {{ ($selectedSlug ?? '') === $kat->slug ? 'active' : '' }}">{{ $kat->nama_kategori }}</a>
            @endforeach
        </div>

        <!-- Produk Grid -->
        <div class="produk-grid">
            @forelse($produk as $item)
            <div class="produk-card" data-kategori="{{ $item['kategori'] }}">
                <div class="produk-image">
                    @if(!empty($item['image']))
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                    @else
                        <img src="{{ asset('template/img/undraw_posting_photo.svg') }}" alt="{{ $item['name'] }}">
                    @endif
                    @if($item['discount'] > 0)
                    <span class="produk-badge">-{{ $item['discount'] }}%</span>
                    @endif
                    <div class="produk-overlay">
                        <form action="{{ route('cart.add', $item['id']) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" 
                                    class="overlay-btn" 
                                    title="Tambah ke Keranjang">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="9" cy="21" r="1"/>
                                    <circle cx="20" cy="21" r="1"/>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                                </svg>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('produk.rate', $item['id']) }}" class="overlay-btn">
                            @csrf
                            <input type="hidden" name="rating" value="5">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#E8001D">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            <button type="submit" style="display:none"></button>
                        </form>
                        <a href="{{ route('produk.show', $item['id']) }}" class="overlay-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.35-4.35"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="produk-info">
                    <span class="produk-kategori">{{ $item['kategori'] }}</span>
                    <h3 class="produk-name">{{ $item['name'] }}</h3>
                    <div class="produk-rating">
                        @for($i = 0; $i < 5; $i++)
                            @if($i < $item['rating'])
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="#E8001D">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            @else
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="#ddd">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            @endif
                        @endfor
                        <span>({{ $item['reviews_count'] }})</span>
                    </div>
                    <div class="produk-price">
                        @if($item['discount'] > 0)
                        <span class="price-original">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                        <span class="price-current">Rp {{ number_format($item['price_after_discount'], 0, ',', '.') }}</span>
                        @else
                        <span class="price-current">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
                <div class="no-products" style="text-align:center;padding:2rem;color:#666">Belum ada produk pada kategori ini.</div>
            @endforelse
        </div>

        <div class="section-footer">
            <a href="{{ route('produk.all') }}" class="btn-primary">Lihat Semua Produk</a>
        </div>
    </div>
</section>

<!-- Brands Section removed -->

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Promo Spesial Hari Ini</h2>
            <p>Dapatkan penawaran terbaik untuk produk pilihan. Jangan lewatkan!</p>
            <div class="cta-actions">
                <a href="{{ route('promo.index') }}" class="btn-primary">Lihat Promo</a>
            </div>
            <div class="cta-benefits">
                <div class="benefit">
                    <span class="benefit-icon">🚚</span>
                    <span>Gratis Ongkir di atas Rp 500.000</span>
                </div>
                <div class="benefit">
                    <span class="benefit-icon">✅</span>
                    <span>Produk Original Bergaransi</span>
                </div>
                <div class="benefit">
                    <span class="benefit-icon">⚡</span>
                    <span>Flash Deal Setiap Hari</span>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
/* Hero Section */
.hero-section {
    background: #000;
    padding: 0;
    overflow: hidden;
    min-height: 100vh;
    display: flex;
    align-items: center;
}

.hero-container {
    width: 100%;
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: stretch;
    height: 100vh;
    gap: 0;
}

.hero-content {
    color: #fff;
    padding: 4rem 6rem 12rem 6rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.hero-badge {
    display: inline-block;
    width: fit-content;
    background: rgba(232, 0, 29, 0.2);
    color: #E8001D;
    padding: 0.5rem 1.2rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    border: 1px solid #E8001D;
}

.hero-title {
    font-size: 4rem;
    font-weight: 900;
    line-height: 1.1;
    margin-bottom: 1.5rem;
    text-transform: uppercase;
}

.hero-title span {
    color: #E8001D;
    position: relative;
}

.hero-description {
    font-size: 1.2rem;
    line-height: 1.8;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 2.5rem;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
}

.btn-primary, .btn-secondary {
    padding: 1rem 2.5rem;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 700;
    font-size: 1rem;
    text-transform: uppercase;
    transition: all 0.3s;
    display: inline-block;
}

.btn-primary {
    background: linear-gradient(135deg, #E8001D 0%, #C20015 100%);
    color: #fff;
    box-shadow: 0 4px 15px rgba(232, 0, 29, 0.4);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(232, 0, 29, 0.6);
}

.btn-secondary {
    background: transparent;
    color: #fff;
    border: 2px solid #fff;
}

.btn-secondary:hover {
    background: #fff;
    color: #000;
}

.hero-image-container {
    position: relative;
    height: 100%;
}

.store-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.image-gradient {
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, 
        #000 0%, 
        #000 15%, 
        rgba(0,0,0,0.9) 25%, 
        rgba(0,0,0,0.7) 40%, 
        rgba(0,0,0,0.4) 60%, 
        transparent 100%
    );
    pointer-events: none;
}

.hero-image { position: relative; }

@media (max-width: 768px) {
    .hero-section {
        min-height: auto;
        padding-top: 0;
        height: auto;
    }
    
    .hero-container {
        display: flex;
        flex-direction: column;
        height: auto;
    }
    
    .hero-content {
        padding: 3rem 1.5rem 4rem;
        text-align: center;
        align-items: center;
        order: 2;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-image {
        height: 40vh;
        min-height: 300px;
        width: 100%;
        order: 1;
    }
    
    .hero-image-container {
        height: 100%;
    }
    
    .store-image {
        object-position: center;
    }
}

/* Features Section */
.features-section {
    padding: 4rem 2rem;
    background: transparent;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.feature-card {
    background: #fff;
    padding: 2.5rem 2rem;
    border-radius: 24px;
    text-align: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
}

.feature-card:hover {
    transform: translateY(-8px);
    border-color: rgba(232, 0, 29, 0.15);
    box-shadow: 0 20px 40px -5px rgba(0,0,0,0.12);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #E8001D 0%, #C20015 100%);
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: #fff;
    box-shadow: 0 10px 20px -5px rgba(232, 0, 29, 0.3);
    transform: rotate(-5deg);
    transition: transform 0.3s;
}

.feature-card:hover .feature-icon {
    transform: rotate(0deg) scale(1.1);
}

.feature-card h3 {
    font-size: 1.3rem;
    margin-bottom: 0.8rem;
    color: #000;
}

.feature-card p {
    color: #666;
    line-height: 1.6;
}

/* About Section */
.about-section {
    padding: 6rem 2rem;
}

.about-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.about-image {
    position: relative;
}

.about-image img {
    width: 80%;
    display: block;
    margin: 0 auto;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
}

.about-badge {
    position: absolute;
    bottom: 2rem;
    right: 2rem;
    background: linear-gradient(135deg, #E8001D 0%, #C20015 100%);
    color: #fff;
    padding: 1.5rem;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(232, 0, 29, 0.4);
}

.badge-number {
    display: block;
    font-size: 2.5rem;
    font-weight: 900;
}

.badge-text {
    font-size: 0.9rem;
    font-weight: 600;
}

.section-subtitle {
    color: #E8001D;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-size: 0.9rem;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 900;
    margin: 1rem 0;
    color: #000;
}

.about-description {
    color: #666;
    line-height: 1.8;
    margin-bottom: 1.5rem;
}

.about-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin-top: 2rem;
}

.stat-item h3 {
    font-size: 2.5rem;
    font-weight: 900;
    color: #E8001D;
    margin-bottom: 0.5rem;
}

.stat-item p {
    color: #666;
    font-weight: 600;
}

/* Produk Section */
.produk-section {
    padding: 6rem 2rem;
    background: #f5f5f5;
}

.section-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-description {
    color: #666;
    font-size: 1.1rem;
    max-width: 600px;
    margin: 1rem auto 0;
}

.kategori-tabs {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}

.tab-btn {
    padding: 0.8rem 2rem;
    background: #fff;
    border: 2px solid #ddd;
    border-radius: 30px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
}

.tab-btn.active, .tab-btn:hover {
    background: #E8001D;
    color: #fff;
    border-color: #E8001D;
}

.produk-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.produk-card {
    background: #fff;
    border-radius: 24px;
    overflow: hidden;
    transition: all 0.3s;
}

.produk-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.produk-image {
    position: relative;
    overflow: hidden;
    padding-top: 100%;
}

.produk-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.produk-card:hover .produk-image img {
    transform: scale(1.1);
}

.produk-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: #E8001D;
    color: #fff;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.9rem;
    z-index: 2;
}

.produk-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s;
}

.produk-card:hover .produk-overlay {
    opacity: 1;
}

.overlay-btn {
    width: 45px;
    height: 45px;
    background: #fff;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    color: #000;
}

.overlay-btn:hover {
    background: #E8001D;
    color: #fff;
    transform: scale(1.1);
}

.produk-info {
    padding: 1.5rem;
}

.produk-kategori {
    color: #999;
    font-size: 0.85rem;
    text-transform: uppercase;
    font-weight: 600;
}

.produk-name {
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0.5rem 0;
    color: #000;
}

.produk-rating {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    margin: 0.5rem 0;
}

.produk-rating span {
    color: #666;
    font-size: 0.9rem;
}

.produk-price {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    margin-top: 1rem;
}

.price-original {
    color: #999;
    text-decoration: line-through;
    font-size: 0.9rem;
}

.price-current {
    color: #E8001D;
    font-weight: 900;
    font-size: 1.3rem;
}

.section-footer {
    text-align: center;
}

/* Brands Section */
.brands-section {
    padding: 4rem 2rem;
}

.brands-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 2rem;
    align-items: center;
}

.brand-item {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    filter: grayscale(100%);
    opacity: 0.6;
    transition: all 0.3s;
}

.brand-item:hover {
    filter: grayscale(0%);
    opacity: 1;
}

.brand-item img {
    max-width: 120px;
    height: auto;
}

/* CTA Section */
.cta-section {
    background: linear-gradient(135deg, #000 0%, #1a1a1a 100%);
    padding: 6rem 2rem;
    text-align: center;
    color: #fff;
    position: relative;
}

.cta-content h2 {
    font-size: 2.5rem;
    font-weight: 900;
    margin-bottom: 1rem;
}

.cta-content p {
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 2rem;
}

.cta-benefits {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
    margin-top: 2rem;
}
.benefit {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    color: #fff;
    border-radius: 12px;
    padding: .9rem 1rem;
    display: flex;
    align-items: center;
    gap: .6rem;
    justify-content: center;
}
.benefit-icon { font-size: 1.2rem; }

.cta-form {
    max-width: 600px;
    margin: 0 auto;
    display: flex;
    gap: 1rem;
}

.cta-form input {
    flex: 1;
    padding: 1rem 1.5rem;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 30px;
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    font-size: 1rem;
    outline: none;
}

.cta-form input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

/* Responsive */
@media (max-width: 1024px) {
    .hero-title { font-size: 3rem; }
    .section-title { font-size: 2rem; }
}

@media (max-width: 768px) {
    .hero-container, .about-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .hero-section { padding: 4rem 1rem; }
    .hero-title { font-size: 2.5rem; }
    .hero-buttons { flex-direction: column; }
    
    .produk-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
    
    .cta-form {
        flex-direction: column;
    }
}
</style>

 
@endsection
