@extends('layouts.master')

@section('title', $produk->nama_produk . ' - CM SPORT')
@section('description', Str::limit($produk->deskripsi, 160))

@section('content')
<div class="product-detail-page">
    <div class="container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span class="divider">/</span>
            <a href="{{ route('produk.all') }}">Produk</a>
            <span class="divider">/</span>
            <span class="current">{{ $produk->nama_produk }}</span>
        </nav>

        <div class="product-main">
            <!-- Image Gallery -->
            <div class="product-gallery">
                <div class="main-image">
                    <img src="{{ $produk->image_url }}" alt="{{ $produk->nama_produk }}">
                    @if($produk->hasActivePromo())
                        <div class="discount-badge">-{{ $produk->getDiscountPercentage() }}%</div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <h1 class="product-title">{{ $produk->nama_produk }}</h1>
                
                <div class="product-meta-top">
                    <div class="rating">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($produk->rating))
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="#FFC107" stroke="none"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            @else
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="#E0E0E0" stroke="none"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            @endif
                        @endfor
                        <span class="review-count">({{ $produk->total_reviews ?? 0 }} ulasan)</span>
                    </div>
                    <div class="meta-divider">|</div>
                    <div class="stock-status">Stok: {{ $produk->stok }}</div>
                </div>

                <div class="product-price-box">
                    @if($produk->hasActivePromo())
                        <div class="price-wrapper">
                            <span class="current-price">Rp {{ number_format($produk->getLowestPromoPrice(), 0, ',', '.') }}</span>
                            <span class="original-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                        </div>
                    @else
                        <span class="current-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                    @endif
                </div>

                <div class="product-description">
                    <p>{{ $produk->deskripsi }}</p>
                </div>

                <!-- Add to Cart Form -->
                <form action="{{ route('cart.add', $produk->produk_id) }}" method="POST" class="add-to-cart-form">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Jumlah:</label>
                        <div class="qty-control" style="border:none; background:none;">
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $produk->stok }}" class="qty-input form-control" style="width: 100px; text-align: left; padding-left: 1rem; border: 1px solid #ddd; border-radius: 8px;">
                        </div>
                        <span class="stock-info">Tersisa {{ $produk->stok }} buah</span>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn-add-cart">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            Tambah ke Keranjang
                        </button>
                    </div>
                </form>

                <div class="product-meta-bottom">
                    <div class="meta-row">
                        <span class="meta-label">Kategori:</span>
                        <a href="{{ route('produk.all', ['kategori' => $produk->kategori->slug ?? '']) }}" class="meta-link">
                            {{ $produk->kategori->nama_kategori ?? 'Umum' }}
                        </a>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Berat:</span>
                        <span>{{ $produk->berat ?? 1000 }} gram</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="reviews-section" id="reviews">
            <h2 class="section-title">Ulasan Pembeli ({{ $produk->reviews->count() }})</h2>

            @if(isset($userCanReview) && $userCanReview)
            <div class="review-form-card">
                <h3>Tulis Ulasan Anda</h3>
                <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $produk->produk_id }}">
                    <input type="hidden" name="order_id" value="{{ $reviewableOrderId }}">
                    
                    <div class="form-group">
                        <label class="form-label">Rating:</label>
                        <div class="star-rating-input">
                            @for($i=5; $i>=1; $i--)
                                <input type="radio" id="star{{$i}}" name="rating" value="{{$i}}" required />
                                <label for="star{{$i}}" title="{{$i}} stars">
                                    <svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                </label>
                            @endfor
                        </div>
                    </div>
                    
                    <div class="form-group" style="display:block">
                        <label class="form-label" for="comment" style="margin-bottom:0.5rem; display:block">Komentar:</label>
                        <textarea name="comment" id="comment" rows="4" class="form-control" placeholder="Bagaimana pengalaman Anda menggunakan produk ini?"></textarea>
                    </div>
                    
                    <button type="submit" class="btn-submit-review">Kirim Ulasan</button>
                </form>
            </div>
            @endif

            <div class="reviews-list">
                @forelse($produk->reviews->sortByDesc('created_at') as $review)
                <div class="review-item">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <span class="reviewer-name">{{ $review->user->name ?? 'Pengguna' }}</span>
                            @if($review->is_verified_purchase)
                                <span class="verified-badge">Verified Purchase</span>
                            @endif
                        </div>
                        <span class="review-date">{{ $review->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="review-rating">
                        @for($i=1; $i<=5; $i++)
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="{{ $i <= $review->rating ? '#FFC107' : '#E0E0E0' }}" stroke="none"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        @endfor
                    </div>
                    <div class="review-content">
                        <p>{{ $review->comment }}</p>
                    </div>
                </div>
                @empty
                <div class="no-reviews">
                    <p>Belum ada ulasan untuk produk ini.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProduk->count() > 0)
        <div class="related-products">
            <h2 class="related-title">Produk Sejenis</h2>
            <div class="related-grid">
                @foreach($relatedProduk as $item)
                <div class="related-card">
                    <a href="{{ route('produk.show', $item->produk_id) }}">
                        <div class="related-image-wrapper">
                            <img src="{{ $item->image_url }}" alt="{{ $item->nama_produk }}" class="related-img">
                            @if($item->hasActivePromo())
                                <div class="mini-badge">-{{ $item->getDiscountPercentage() }}%</div>
                            @endif
                        </div>
                        <div class="related-info">
                            <h3 class="related-name">{{ $item->nama_produk }}</h3>
                            <div class="related-price-box">
                                @if($item->hasActivePromo())
                                    <span class="related-price">Rp {{ number_format($item->getLowestPromoPrice(), 0, ',', '.') }}</span>
                                    <span class="related-original">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                @else
                                    <span class="related-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .product-detail-page {
        padding: 2rem 0 5rem;
        background: transparent;
    }
    
    .container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 2rem;
    }
    
    /* Breadcrumb */
    .breadcrumb {
        margin-bottom: 2rem;
        color: #666;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        background: rgba(255,255,255,0.8);
        padding: 0.8rem 1.5rem;
        border-radius: 16px;
        backdrop-filter: blur(10px);
        width: fit-content;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .breadcrumb a {
        color: #666;
        text-decoration: none;
        transition: color 0.2s;
        font-weight: 500;
    }
    .breadcrumb a:hover {
        color: var(--primary-red);
    }
    .breadcrumb .divider {
        margin: 0 0.8rem;
        color: #ccc;
    }
    .breadcrumb .current {
        color: #333;
        font-weight: 700;
    }
    
    /* Layout */
    .product-main {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
        background: #fff;
        padding: 2.5rem;
        border-radius: 32px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.02);
        border: 1px solid rgba(0,0,0,0.04);
    }
    
    @media(max-width: 900px) {
        .product-main {
            grid-template-columns: 1fr;
            gap: 2rem;
            padding: 1.5rem;
            border-radius: 24px;
        }
    }
    
    /* Gallery */
    .main-image {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        background: #f9f9f9;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .main-image img {
        width: 100%;
        height: auto;
        display: block;
        max-height: 550px;
        object-fit: contain;
        transition: transform 0.3s;
    }
    .main-image:hover img {
        transform: scale(1.02);
    }
    .discount-badge {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        background: var(--primary-red);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 800;
        font-size: 1rem;
        box-shadow: 0 4px 15px rgba(232, 0, 29, 0.3);
        z-index: 2;
    }

    /* Info */
    .product-info {
        padding-top: 0.5rem;
        display: flex;
        flex-direction: column;
    }
    .product-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        line-height: 1.2;
        color: #1a1a1a;
        letter-spacing: -0.02em;
    }
    .product-meta-top {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-bottom: 2rem;
    }
    .rating {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .review-count {
        margin-left: 0.5rem;
        color: #666;
        font-size: 0.95rem;
        font-weight: 500;
    }
    .meta-divider {
        color: #ddd;
    }
    .stock-status {
        color: #00C853;
        font-weight: 700;
        background: #e8f5e9;
        padding: 0.3rem 0.8rem;
        border-radius: 8px;
        font-size: 0.9rem;
    }
    
    .product-price-box {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #eee;
    }
    .price-wrapper {
        display: flex;
        align-items: flex-end;
        gap: 1rem;
    }
    .current-price {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--primary-red);
        line-height: 1;
    }
    .original-price {
        font-size: 1.4rem;
        color: #999;
        text-decoration: line-through;
        margin-bottom: 0.4rem;
    }
    
    .product-description {
        color: #555;
        line-height: 1.8;
        margin-bottom: 2.5rem;
        font-size: 1.05rem;
    }
    
    /* Form */
    .add-to-cart-form {
        background: #f8f9fa;
        padding: 2rem;
        border-radius: 24px;
        margin-bottom: 2.5rem;
        border: 1px solid rgba(0,0,0,0.03);
    }
    .form-group {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .form-label {
        font-weight: 700;
        color: #333;
        font-size: 1.1rem;
    }
    .qty-control {
        display: flex;
        border: 1px solid #ddd;
        border-radius: 12px;
        overflow: hidden;
        background: white;
    }
    .qty-btn {
        background: #fff;
        border: none;
        width: 48px;
        height: 48px;
        cursor: pointer;
        font-size: 1.4rem;
        color: #333;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .qty-btn:hover {
        background: #f0f0f0;
    }
    .qty-input {
        width: 70px;
        border: none;
        text-align: center;
        font-weight: 700;
        font-size: 1.1rem;
        color: #333;
        -moz-appearance: textfield;
        appearance: textfield;
        border-left: 1px solid #eee;
        border-right: 1px solid #eee;
    }
    .stock-info {
        color: #666;
        font-size: 0.95rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
    }
    .btn-add-cart {
        flex: 1;
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: white;
        border: none;
        padding: 1.2rem;
        border-radius: 16px;
        font-weight: 700;
        font-size: 1.2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(232, 0, 29, 0.3);
    }
    .btn-add-cart:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(232, 0, 29, 0.4);
    }
    
    .product-meta-bottom {
        font-size: 0.95rem;
        color: #666;
        margin-top: auto;
    }
    .meta-row {
        display: flex;
        margin-bottom: 0.8rem;
        align-items: center;
    }
    .meta-label {
        width: 100px;
        font-weight: 600;
        color: #444;
    }
    .meta-link {
        color: var(--primary-red);
        text-decoration: none;
        font-weight: 600;
        background: rgba(232, 0, 29, 0.05);
        padding: 0.2rem 0.8rem;
        border-radius: 6px;
        transition: all 0.2s;
    }
    .meta-link:hover {
        background: rgba(232, 0, 29, 0.1);
        text-decoration: none;
    }

    /* Related Products */
    .related-products {
        margin-top: 4rem;
        padding-top: 2rem;
    }
    .related-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 2rem;
        color: #1a1a1a;
        text-align: center;
    }
    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 2rem;
    }
    
    .related-card {
        background: #fff;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }
    .related-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -5px rgba(0,0,0,0.12);
        border-color: rgba(232, 0, 29, 0.15);
    }
    .related-card a {
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .related-image-wrapper {
        position: relative;
        padding-top: 100%; /* 1:1 Aspect Ratio */
        background: #f9f9f9;
        overflow: hidden;
    }
    .related-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .related-card:hover .related-img {
        transform: scale(1.05);
    }
    .mini-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: var(--primary-red);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 800;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        z-index: 2;
    }
    .related-info {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .related-name {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #1a1a1a;
    }
    .related-price-box {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: auto;
    }
    .related-price {
        font-weight: 800;
        color: var(--primary-red);
        font-size: 1.2rem;
    }
    .related-original {
        font-size: 0.9rem;
        color: #999;
        text-decoration: line-through;
    }

    /* Reviews */
    .reviews-section {
        margin-top: 3rem;
        background: #fff;
        padding: 2.5rem;
        border-radius: 32px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.02);
        border: 1px solid rgba(0,0,0,0.04);
    }
    .section-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 2rem;
        color: #1a1a1a;
    }
    
    .review-form-card {
        background: #f8f9fa;
        padding: 2rem;
        border-radius: 24px;
        margin-bottom: 3rem;
        border: 1px solid rgba(0,0,0,0.03);
    }
    .review-form-card h3 {
        margin-bottom: 1.5rem;
        font-size: 1.3rem;
        font-weight: 700;
    }
    
    .star-rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 0.5rem;
    }
    .star-rating-input input {
        display: none;
    }
    .star-rating-input label {
        cursor: pointer;
        color: #ddd;
        transition: color 0.2s;
    }
    .star-rating-input label:hover,
    .star-rating-input label:hover ~ label,
    .star-rating-input input:checked ~ label {
        color: #FFC107;
    }
    
    .form-control {
        width: 100%;
        padding: 1rem;
        border: 1px solid #ddd;
        border-radius: 12px;
        font-family: inherit;
        resize: vertical;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: var(--primary-red);
        outline: none;
    }
    .btn-submit-review {
        background: var(--black);
        color: white;
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 1rem;
    }
    .btn-submit-review:hover {
        background: var(--primary-red);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(232, 0, 29, 0.3);
    }
    
    .review-item {
        border-bottom: 1px solid #eee;
        padding: 2rem 0;
    }
    .review-item:last-child {
        border-bottom: none;
    }
    .review-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        align-items: center;
    }
    .reviewer-name {
        font-weight: 700;
        margin-right: 0.5rem;
        font-size: 1.1rem;
    }
    .verified-badge {
        background: #e8f5e9;
        color: #2e7d32;
        font-size: 0.75rem;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-weight: 700;
    }
    .review-date {
        color: #999;
        font-size: 0.9rem;
    }
    .review-rating {
        display: flex;
        gap: 2px;
        margin-bottom: 1rem;
    }
    .review-content {
        color: #444;
        line-height: 1.6;
        font-size: 1rem;
    }
    .no-reviews {
        text-align: center;
        padding: 3rem;
        color: #666;
        background: #f9f9f9;
        border-radius: 16px;
    }
</style>
@endpush
@endsection