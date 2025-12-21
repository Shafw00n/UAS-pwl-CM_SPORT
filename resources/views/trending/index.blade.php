@extends('layouts.master')

@section('title', 'Trending Produk - CM SPORT')
@section('description', 'Produk olahraga paling trending dan populer minggu ini di CM SPORT')

@section('content')
<div class="trending-page">
    <!-- Hero Section -->
    <div class="trending-hero">
        <div class="container">
            <div class="hero-content">
                <span class="hero-badge">🔥 HOT THIS WEEK</span>
                <h1 class="hero-title">Trending Produk</h1>
                <p class="hero-subtitle">Produk paling dicari dan dibeli minggu ini</p>
            </div>
            
            <div class="trending-stats">
                <div class="stat-card">
                    <div class="stat-icon">📈</div>
                    <div class="stat-info">
                        <h3>{{ $totalTrending }}</h3>
                        <p>Trending Produk</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🛒</div>
                    <div class="stat-info">
                        <h3>{{ number_format($totalViews) }}</h3>
                        <p>Total Views</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⭐</div>
                    <div class="stat-info">
                        <h3>{{ number_format($avgRating, 1) }}</h3>
                        <p>Average Rating</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Filter Tabs -->
        <div class="trending-tabs">
            <a href="{{ route('trending.index') }}?period=week" class="tab-btn active">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                Minggu Ini
            </a>
            <a href="{{ route('trending.index') }}?period=month" class="tab-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                </svg>
                Bulan Ini
            </a>
            <a href="{{ route('trending.index') }}?period=all" class="tab-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                All Time
            </a>
        </div>

        <!-- Trending Products Grid -->
        <div class="produk-grid" id="trendingGrid">
            @foreach($trendingProduk as $index => $item)
            <div class="produk-card" data-rank="{{ $index + 1 }}">
                <!-- Rank Badge Overlay -->
                <div class="rank-badge">
                    @if($index + 1 <= 3)
                        <span class="rank-medal">{{ $index + 1 == 1 ? '🥇' : ($index + 1 == 2 ? '🥈' : '🥉') }}</span>
                    @else
                        <span class="rank-number">#{{ $index + 1 }}</span>
                    @endif
                </div>

                <a href="{{ route('produk.show', $item->produk_id) }}" class="produk-link">
                    <div class="produk-image">
                        <img src="{{ $item->image_url }}" alt="{{ $item->nama_produk }}">
                        
                        @if($item->discount > 0)
                        <div class="produk-badge discount">-{{ $item->discount }}%</div>
                        @endif
                    </div>
                    
                    <div class="produk-info">
                        <h3 class="produk-name">{{ $item->nama_produk }}</h3>
                        <p class="produk-description">{{ Str::limit($item->deskripsi, 60) }}</p>
                        
                        <div class="produk-rating">
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= ($item->rating ?? 5))
                                        <span class="star filled">⭐</span>
                                    @else
                                        <span class="star">☆</span>
                                    @endif
                                @endfor
                            </div>
                            <span class="rating-count">({{ $item->total_reviews ?? 0 }} ulasan)</span>
                        </div>

                        <div class="produk-meta">
                            <div class="meta-item">
                                <span>Terjual: {{ $item->sold_period ?? 0 }} pcs</span>
                            </div>
                            <div class="meta-item">
                                <span>Total Views: {{ number_format($item->views ?? 0) }}</span>
                            </div>
                        </div>
                        
                        <div class="produk-footer">
                            <div class="produk-price">
                                @if($item->discount > 0)
                                    <span class="price-original">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                    <span class="price-discounted">Rp {{ number_format($item->harga * (1 - $item->discount/100), 0, ',', '.') }}</span>
                                @else
                                    <span class="price-current">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
                
                <div class="produk-actions">
                    <form action="{{ url('cart/add') }}/{{ $item->produk_id }}" method="POST" style="flex: 1;">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" 
                                class="btn-action btn-cart" 
                                title="Tambah ke Keranjang"
                                style="width: 100%;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"/>
                                <circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Load More -->
        @if($trendingProduk->hasMorePages())
        <div class="load-more-section">
            <a href="{{ $trendingProduk->nextPageUrl() }}" class="btn-load-more">
                Load More Trending Produk
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <polyline points="19 12 12 19 5 12"/>
                </svg>
            </a>
        </div>
        @endif
    </div>

    <!-- Why Trending Section -->
    <div class="why-trending-section">
        <div class="container">
            <h2 class="section-title">Kenapa Produk Ini Trending?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Berdasarkan Data Real-Time</h3>
                    <p>Trending produk ditentukan dari views, pembelian, dan interaksi pengguna secara real-time</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Update Setiap Hari</h3>
                    <p>Daftar trending diperbarui setiap hari untuk menampilkan produk paling populer saat ini</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3>Pilihan Terbaik</h3>
                    <p>Produk trending adalah pilihan favorit pelanggan lain, dijamin berkualitas tinggi</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cart Modal Removed -->

<style>
.trending-page {
    background: var(--light-gray);
    min-height: 100vh;
}

/* Hero Section */
.trending-hero {
    background: linear-gradient(135deg, var(--black) 0%, var(--dark-gray) 100%);
    padding: 3rem 0;
    margin-bottom: 2rem;
    border-bottom: 3px solid var(--primary-red);
    position: relative;
    overflow: hidden;
}

.trending-hero::before {
    content: '🔥';
    position: absolute;
    font-size: 300px;
    opacity: 0.05;
    top: -50px;
    right: -50px;
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.hero-content {
    text-align: center;
    margin-bottom: 2rem;
}

.hero-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
    color: var(--white);
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    font-weight: 700;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    animation: pulse 2s infinite;
}

.hero-title {
    color: var(--white);
    font-size: 3rem;
    font-weight: 900;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.hero-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.2rem;
}

.trending-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    max-width: 900px;
    margin: 0 auto;
}

.stat-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s;
}

.stat-card:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-5px);
}

.stat-icon {
    font-size: 3rem;
}

.stat-info h3 {
    color: var(--white);
    font-size: 2rem;
    font-weight: 900;
    margin-bottom: 0.25rem;
}

.stat-info p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
}

/* Trending Tabs */
.trending-tabs {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    justify-content: center;
    flex-wrap: wrap;
}

.tab-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem 1.5rem;
    background: var(--white);
    border: 2px solid #ddd;
    border-radius: 25px;
    color: #666;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.tab-btn:hover {
    border-color: var(--primary-red);
    color: var(--primary-red);
}

.tab-btn.active {
    background: var(--primary-red);
    border-color: var(--primary-red);
    color: var(--white);
}

/* Produk Grid (replaces Trending Grid) */
.produk-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.2rem;
    margin-bottom: 3rem;
}

.produk-card {
    background: var(--white);
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    transition: all 0.3s;
    border: 1px solid #f0f0f0;
}

.produk-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    transform: translateY(-4px);
    border-color: transparent;
}

/* Rank Badge (Specific to Trending) */
.rank-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    z-index: 2;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 900;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.rank-badge.rank-1,
.rank-badge.rank-2,
.rank-badge.rank-3 {
    font-size: 1.5rem;
}

.rank-medal {
    font-size: 1.5rem;
}

.rank-number {
    color: var(--white);
    font-size: 1.2rem;
}

.produk-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.produk-image {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background: var(--light-gray);
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
    top: 0.5rem;
    left: 0.5rem;
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    z-index: 1;
}

.produk-badge.discount {
    background: var(--primary-red);
    color: var(--white);
}

.produk-badge.new {
    background: #00C853;
    color: var(--white);
    left: auto;
    right: 0.5rem;
}

.produk-info {
    padding: 1rem;
}

.produk-name {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--black, #111);
    margin-bottom: 0.5rem;
    line-height: 1.4;
    max-height: calc(1.4em * 2);
    overflow: hidden;
}

.produk-description {
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 0.5rem;
    line-height: 1.4;
    max-height: calc(1.4em * 2);
    overflow: hidden;
}

.produk-rating {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    margin-bottom: 0.8rem;
}

.stars {
    display: flex;
    gap: 2px;
    font-size: 0.8rem;
}

.star.filled {
    color: #FFA500;
}

.star {
    color: #ddd;
}

.rating-count {
    font-size: 0.75rem;
    color: #999;
}

.produk-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}

.produk-price {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.price-original {
    font-size: 0.8rem;
    color: #999;
    text-decoration: line-through;
}

.price-discounted,
.price-current {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary-red);
}

.produk-actions {
    display: flex;
    gap: 0.5rem;
    padding: 0.8rem 1rem;
    border-top: 1px solid var(--light-gray);
}

.btn-action {
    flex: 1;
    padding: 0.6rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    background: var(--white);
    color: #666;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.btn-action:hover {
    border-color: var(--primary-red);
    color: var(--primary-red);
    transform: translateY(-2px);
}

.btn-cart {
    background: var(--primary-red);
    color: var(--white);
    border-color: var(--primary-red);
}

.btn-cart:hover {
    background: var(--dark-red);
    color: var(--white);
}

/* Load More */
.load-more-section {
    text-align: center;
    margin: 3rem 0;
}

.btn-load-more {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    background: var(--primary-red);
    color: var(--white);
    text-decoration: none;
    border-radius: 30px;
    font-weight: 700;
    transition: all 0.3s;
}

.btn-load-more:hover {
    background: var(--dark-red);
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(232, 0, 29, 0.3);
}

/* Why Trending Section */
.why-trending-section {
    background: var(--white);
    padding: 4rem 0;
    margin-top: 3rem;
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 900;
    color: var(--black);
    margin-bottom: 3rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.feature-card {
    text-align: center;
    padding: 2rem;
}

.feature-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.feature-card h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--black);
    margin-bottom: 0.75rem;
}

.feature-card p {
    color: #666;
    line-height: 1.6;
}

/* Responsive */
@media (max-width: 1024px) {
    .hero-title {
        font-size: 2.6rem;
    }
    .produk-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
}
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .produk-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .produk-card {
        font-size: 0.9rem;
    }
    
    .btn-action {
        font-size: 0.8rem;
        padding: 0.6rem;
    }
    
    .section-title {
        font-size: 1.8rem;
    }
}

@media (max-width: 576px) {
    .produk-grid {
        grid-template-columns: 1fr;
        gap: 0.9rem;
    }
}
@media (max-width: 480px) {
    .trending-stats {
        grid-template-columns: 1fr;
    }
}

@endsection
