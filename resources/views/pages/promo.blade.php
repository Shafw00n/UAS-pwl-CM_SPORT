@extends('layouts.master')

@section('title', 'Flash Sale - CM SPORT')

@section('content')
<style>
    .promo-page {
        background: linear-gradient(135deg, #f5f7fa 0%, #ffffff 100%);
        min-height: 100vh;
        padding: 3rem 0;
    }
    
    .promo-header {
        text-align: center;
        margin-bottom: 4rem;
        padding: 2rem 1rem;
        position: relative;
    }
    
    .promo-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(90deg, #E8001D 0%, #FF6B6B 100%);
        border-radius: 2px;
    }
    
    .promo-header h1 {
        font-size: 3.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #E8001D 0%, #FF6B6B 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    
    .promo-header p {
        font-size: 1.25rem;
        color: #666;
        font-weight: 500;
    }
    
    .promo-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .product-card {
        background: white;
        border: 2px solid #f0f0f0;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .product-card:hover {
        border-color: #E8001D;
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(232, 0, 29, 0.15);
    }
    
    .product-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
        border-radius: 12px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        position: relative;
        overflow: hidden;
    }
    
    .discount-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #E8001D;
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-weight: 800;
        font-size: 0.85rem;
        box-shadow: 0 2px 10px rgba(232, 0, 29, 0.3);
        z-index: 2;
    }
    
    .product-info h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        line-height: 1.4;
        min-height: 3em;
    }
    
    .product-category {
        display: inline-block;
        background: #f0f0f0;
        color: #666;
        font-size: 0.85rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        margin-bottom: 0.75rem;
        font-weight: 600;
    }
    
    .product-pricing {
        margin-top: auto;
    }
    
    .original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 0.95rem;
        margin-right: 0.5rem;
    }
    
    .promo-price {
        color: #E8001D;
        font-size: 1.5rem;
        font-weight: 800;
        display: block;
        margin-top: 0.25rem;
    }
    
    .view-all-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: linear-gradient(135deg, #E8001D 0%, #C20015 100%);
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-weight: 800;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(232, 0, 29, 0.3);
        border: none;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .view-all-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(232, 0, 29, 0.4);
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 4rem;
        flex-wrap: wrap;
    }
    
    .no-promo {
        text-align: center;
        padding: 6rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        grid-column: 1 / -1;
    }
    
    .no-promo-icon {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .promo-header h1 { font-size: 2rem; }
        .products-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .product-image { height: 140px; }
        .promo-price { font-size: 1.2rem; }
        .view-all-btn { padding: 0.75rem 1rem; font-size: 0.9rem; }
    }
</style>

<div class="promo-page">
    <div class="promo-container">
        <!-- Header -->
        <div class="promo-header">
            <h1>⚡ FLASH SALE</h1>
            <p>Diskon Terbesar dengan Waktu Terbatas!</p>
        </div>



        <!-- Flash Sale Products Grid -->
        <div class="products-grid">
            @forelse($products as $produk)
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ $produk->image_url }}" alt="{{ $produk->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                        <span class="discount-badge">-{{ $produk->discount_percent }}%</span>
                    </div>
                    
                    <div class="product-info">
                        <!-- Promo Info (PHP based) -->
                        <div class="sale-info" style="background: #ffebee; color: #c62828; padding: 0.5rem; border-radius: 8px; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; justify-content: center;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="info-text promo-countdown" data-end="{{ $produk->promo_end_date->format('c') }}">
                                Berakhir: {{ $produk->promo_end_date->translatedFormat('d M H:i') }}
                            </span>
                        </div>

                        @if(isset($produk->promo_stock))
                        <div class="promo-stock" style="margin-bottom: .75rem; font-size: .9rem; color: #666;">
                            Stok Promo: <strong>{{ $produk->promo_stock }}</strong>
                        </div>
                        @endif
                        
                        <span class="product-category">{{ $produk->kategori->nama_kategori ?? 'Umum' }}</span>
                        
                        <h4>{{ $produk->nama_produk }}</h4>
                        
                        <div class="product-pricing">
                            <span class="original-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                            <span class="promo-price">Rp {{ number_format($produk->promo_price, 0, ',', '.') }}</span>
                        </div>
                        
                        @php
                            $promoEnded = $produk->promo_end_date->isPast();
                            $promoOut = isset($produk->promo_stock) && $produk->promo_stock <= 0;
                        @endphp
                        @if($promoEnded || $promoOut)
                            <button class="view-all-btn" style="width: 100%; justify-content: center; margin-top: 1rem; padding: 0.75rem; background: #ccc; cursor: not-allowed;">
                                {{ $promoOut ? 'Stok Habis' : 'Promo Berakhir' }}
                            </button>
                        @else
                            <a href="{{ route('produk.show', $produk->produk_id) }}" class="view-all-btn" style="width: 100%; justify-content: center; margin-top: 1rem; padding: 0.75rem;">
                                Beli Sekarang
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="no-promo">
                    <div class="no-promo-icon">🛍️</div>
                    <h3>Belum Ada Flash Sale</h3>
                    <p>Nantikan promo menarik berikutnya!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="pagination">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const countdowns = document.querySelectorAll('.promo-countdown');
        
        function updateCountdowns() {
            const now = new Date().getTime();
            
            countdowns.forEach(el => {
                const endDate = new Date(el.dataset.end).getTime();
                const distance = endDate - now;
                
                if (distance < 0) {
                    el.innerHTML = 'Promo Berakhir';
                    return;
                }
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                let text = 'Berakhir: ';
                if (days > 0) text += `${days}h `;
                text += `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                el.innerHTML = text;
            });
        }
        
        setInterval(updateCountdowns, 1000);
        updateCountdowns(); // Initial call
    });
</script>
@endpush
