@extends('layouts.master')

@section('title', 'Semua Produk - CM SPORT')
@section('description', 'Jelajahi koleksi lengkap produk olahraga berkualitas di CM SPORT dengan harga terbaik')

@section('content')
<div class="produk-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Semua Produk</h1>
            <p class="page-subtitle">Temukan peralatan olahraga terbaik untuk kebutuhan Anda</p>
        </div>
    </div>

    <div class="container">
        <div class="produk-wrapper">
            <!-- Sidebar Filter -->
            <aside class="sidebar-filter">
                <form method="GET" action="{{ route('produk.all') }}">
                <!-- Preserve other query params -->
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif

                <div class="filter-section">
                    <h3 class="filter-title">Kategori</h3>
                    <div class="filter-options">
                        @php($filterCategories = \App\Models\Kategori::orderBy('nama_kategori')->get())
                        @php($selectedCats = collect((array) request('kategori')))
                        {{-- If on category page and no filter selected, mark current category as selected for UI consistency --}}
                        @if(Route::currentRouteName() == 'produk.kategori' && $selectedCats->isEmpty() && request()->route('slug'))
                            @php($selectedCats->push(request()->route('slug')))
                        @endif
                        
                        @foreach($filterCategories as $cat)
                        <label class="filter-option">
                            <input type="checkbox" name="kategori[]" value="{{ $cat->slug }}" {{ $selectedCats->contains($cat->slug) ? 'checked' : '' }}>
                            <span>{{ $cat->nama_kategori }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="filter-section">
                    <h3 class="filter-title">Rentang Harga</h3>
                    <div class="price-range">
                        <input type="number" class="price-input" id="minPrice" name="min_price" min="0" step="1000" value="{{ request('min_price') }}" placeholder="{{ isset($minHarga) ? 'Min (Rp '.number_format($minHarga,0,',','.') .')' : 'Min' }}">
                        <span>-</span>
                        <input type="number" class="price-input" id="maxPrice" name="max_price" min="0" step="1000" value="{{ request('max_price') }}" placeholder="{{ isset($maxHarga) ? 'Max (Rp '.number_format($maxHarga,0,',','.') .')' : 'Max' }}">
                    </div>
                    @if(isset($minHarga) && isset($maxHarga) && $maxHarga > 0)
                    <div class="price-hint">Rentang tersedia: Rp {{ number_format($minHarga,0,',','.') }} – Rp {{ number_format($maxHarga,0,',','.') }}</div>
                    @endif
                </div>

                <div class="filter-section">
                    <h3 class="filter-title">Rating</h3>
                    <div class="filter-options">
                        <label class="filter-option">
                            <input type="checkbox" name="rating[]" value="5" {{ in_array('5', (array)request('rating')) ? 'checked' : '' }}>
                            <span>⭐⭐⭐⭐⭐</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" name="rating[]" value="4" {{ in_array('4', (array)request('rating')) ? 'checked' : '' }}>
                            <span>⭐⭐⭐⭐ ke atas</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" name="rating[]" value="3" {{ in_array('3', (array)request('rating')) ? 'checked' : '' }}>
                            <span>⭐⭐⭐ ke atas</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-apply-filter">Terapkan Filter</button>
                <a href="{{ url()->current() }}" class="btn-reset-filter">Reset Filter</a>
                </form>
            </aside>

            <!-- Main Content -->
            <div class="produk-content">
                <!-- Toolbar -->
                <div class="produk-toolbar">
                    <div class="toolbar-left">
                        <span class="produk-count">Menampilkan <strong>{{ $produk->total() }}</strong> produk</span>
                    </div>
                    <div class="toolbar-right">
                        <form method="GET" action="{{ route('produk.all') }}" class="sort-form">
                            @foreach(request()->except('sort') as $name => $value)
                                @if(is_array($value))
                                    @foreach($value as $v)
                                        <input type="hidden" name="{{ $name }}[]" value="{{ $v }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <label>Urutkan:</label>
                            <select class="sort-select" name="sort">
                                <option value="terbaru" {{ request('sort')=='terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="terlaris" {{ request('sort')=='terlaris' ? 'selected' : '' }}>Terlaris</option>
                                <option value="harga-terendah" {{ request('sort')=='harga-terendah' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="harga-tertinggi" {{ request('sort')=='harga-tertinggi' ? 'selected' : '' }}>Harga Tertinggi</option>
                                <option value="rating" {{ request('sort')=='rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                            </select>
                            <button type="submit" class="btn-sort-go">Go</button>
                        </form>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="produk-grid" id="produkGrid">
                    @forelse($produk as $item)
                    <div class="produk-card">
                        <a href="{{ route('produk.show', $item->produk_id) }}" class="produk-link">
                            <div class="produk-image">
                                <img src="{{ $item->image_url }}" alt="{{ $item->nama_produk }}">
                                
                                @if($item->discount ?? false)
                                <div class="produk-badge discount">-{{ $item->discount }}%</div>
                                @endif
                                
                                @if($item->is_new ?? false)
                                <div class="produk-badge new">Baru</div>
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

                                <div class="produk-meta" style="font-size: 0.85rem; color: #666; margin-bottom: 0.5rem; display: flex; flex-direction: column; gap: 0.2rem;">
                                    <div class="meta-item">
                                        <span>Terjual: {{ $item->terjual ?? 0 }} pcs</span>
                                    </div>
                                    <div class="meta-item">
                                        <span>Rating: {{ number_format($item->rating ?? 5, 1) }} / 5.0</span>
                                    </div>
                                    <div class="meta-item" style="color: #00C853;">
                                        <span>🚚 Estimasi Tiba: 2-3 Hari</span>
                                    </div>
                                </div>
                                
                                <div class="produk-footer">
                                <div class="produk-price">
                                    @if($item->discount ?? false)
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
                        <form action="{{ route('cart.add', $item->produk_id) }}" method="POST" style="width: 100%;">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn-cart-block" title="Tambah ke Keranjang">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                <span>Tambah</span>
                            </button>
                        </form>
                    </div>
                </div>
                    @empty
                    <div class="no-produk">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M16 16s-1.5-2-4-2-4 2-4 2"/>
                            <line x1="9" y1="9" x2="9.01" y2="9"/>
                            <line x1="15" y1="9" x2="15.01" y2="9"/>
                        </svg>
                        <h3>Tidak ada produk ditemukan</h3>
                        <p>Coba ubah filter atau kata kunci pencarian Anda</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($produk->hasPages())
                <div class="pagination-wrapper">
                    {{ $produk->links('pagination::simple-bootstrap-4') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div id="cartModal" class="cart-modal">
    <div class="cart-modal-backdrop"></div>
    <div class="cart-modal-content">
        <button type="button" class="close-modal">&times;</button>
        <div class="cart-modal-body">
            <div class="modal-product-image">
                <img src="" alt="" id="modalImage">
            </div>
            <div class="modal-product-details">
                <h3 id="modalName"></h3>
                <p class="modal-price" id="modalPrice"></p>
                <p class="modal-stock" id="modalStock"></p>
                
                <form id="addToCartForm" method="POST" action="">
                    @csrf
                    <div class="quantity-control">
                        <label for="modalQuantity">Jumlah:</label>
                        <div class="qty-wrapper">
                            <button type="button" class="qty-btn minus">-</button>
                            <input type="number" name="quantity" id="modalQuantity" value="1" min="1" class="qty-input">
                            <button type="button" class="qty-btn plus">+</button>
                        </div>
                    </div>
                    
                    <div class="modal-total">
                        <span>Total:</span>
                        <span id="modalTotal"></span>
                    </div>
                    
                    <button type="submit" class="btn-primary w-full mt-4">Tambah ke Keranjang</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Product Page Styles */
.produk-page {
    padding-bottom: 4rem;
}

.page-header {
    background: transparent;
    padding: 3rem 0 2rem;
    text-align: center;
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    letter-spacing: -0.02em;
    color: var(--black);
}

.page-subtitle {
    color: #666;
    font-size: 1.1rem;
}

.produk-wrapper {
    display: flex;
    gap: 2rem;
    align-items: flex-start;
}

.sidebar-filter {
    width: 280px;
    flex-shrink: 0;
    background: #fff;
    padding: 1.5rem;
    border-radius: 24px;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    position: sticky;
    top: 100px;
}

.filter-section {
    margin-bottom: 2rem;
    border-bottom: 1px solid #eee;
    padding-bottom: 1.5rem;
}

.filter-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.filter-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.filter-option {
    display: flex;
    align-items: center;
    margin-bottom: 0.8rem;
    cursor: pointer;
    font-size: 0.95rem;
    color: #444;
    transition: color 0.2s;
}

.filter-option:hover {
    color: #E8001D;
}

.filter-option input[type="checkbox"] {
    accent-color: #E8001D;
    width: 18px;
    height: 18px;
    margin-right: 10px;
    vertical-align: middle;
    cursor: pointer;
}

.price-range {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.price-input {
    width: 100%;
    padding: 0.6rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: border-color 0.2s;
}

.price-input:focus {
    border-color: #E8001D;
    outline: none;
}

.btn-apply-filter {
    background: #000;
    color: #fff;
    border: none;
    padding: 0.8rem;
    width: 100%;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.btn-apply-filter:hover {
    background: #E8001D;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(232, 0, 29, 0.2);
}

.btn-reset-filter {
    display: block;
    text-align: center;
    margin-top: 1.5rem;
    color: #666;
    text-decoration: underline;
    font-size: 0.9rem;
    transition: color 0.2s;
}

.btn-reset-filter:hover {
    color: #E8001D;
}

/* Content Area */
.produk-content {
    flex-grow: 1;
}

.produk-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    background: #fff;
    padding: 1rem 1.5rem;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
}

.produk-count {
    color: #666;
    font-size: 0.95rem;
}

.sort-form {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.sort-select {
    padding: 0.5rem 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 0.9rem;
    outline: none;
    cursor: pointer;
    transition: border-color 0.2s;
}

.sort-select:focus {
    border-color: #E8001D;
}

/* Product Grid */
.produk-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 2rem;
}

.produk-card {
    background: #fff;
    border-radius: 24px;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    display: flex;
    flex-direction: column;
}

.produk-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px -5px rgba(0,0,0,0.12);
    border-color: rgba(232, 0, 29, 0.15);
}

.produk-link {
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.produk-image {
    position: relative;
    padding-top: 100%; /* 1:1 Aspect Ratio */
    overflow: hidden;
    background: #f8f8f8;
}

.produk-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.produk-card:hover .produk-image img {
    transform: scale(1.05);
}

.produk-badge {
    position: absolute;
    top: 1rem;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    color: #fff;
    z-index: 2;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.produk-badge.discount {
    right: 1rem;
    background: #E8001D;
}

.produk-badge.new {
    left: 1rem;
    background: #00C853;
}

.produk-info {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.produk-name {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    line-height: 1.4;
    color: #1a1a1a;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.produk-description {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 1rem;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.produk-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.stars {
    color: #FFD700;
    font-size: 0.9rem;
}

.rating-count {
    font-size: 0.8rem;
    color: #888;
}

.produk-meta {
    margin-top: auto;
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.produk-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f0f0f0;
}

.produk-price {
    display: flex;
    flex-direction: column;
}

.price-current {
    font-size: 1.2rem;
    font-weight: 800;
    color: #E8001D;
}

.price-original {
    font-size: 0.9rem;
    color: #888;
    text-decoration: line-through;
}

.price-discounted {
    font-size: 1.2rem;
    font-weight: 800;
    color: #E8001D;
}

.produk-location {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.8rem;
    color: #888;
}

.produk-actions {
    padding: 0 1.5rem 1.5rem;
    margin-top: auto;
    z-index: 3;
    position: relative;
    width: 100%;
}

.btn-cart-block {
    width: 100%;
    padding: 0.8rem;
    border-radius: 12px;
    background: #E8001D;
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    font-weight: 600;
    gap: 8px;
    font-size: 0.95rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-cart-block:hover {
    background: #c40018;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(232, 0, 29, 0.3);
}

.produk-card:hover .produk-actions {
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

.btn-action {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #fff;
    border: 1px solid #eee;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    color: #333;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.btn-action:hover {
    background: #E8001D;
    color: #fff;
    border-color: #E8001D;
    transform: scale(1.1);
}

.no-produk {
    text-align: center;
    padding: 4rem 2rem;
    background: #fff;
    border-radius: 24px;
    grid-column: 1 / -1;
    color: #666;
    border: 1px dashed #ddd;
}

/* Pagination Styles */
.pagination-wrapper {
    margin-top: 4rem;
    display: flex;
    justify-content: center;
}

.pagination {
    margin-bottom: 0;
    display: flex;
    gap: 0.5rem;
    list-style: none;
    padding: 0;
}

.page-item {
    margin: 0;
}

.page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.6rem 1.2rem;
    min-width: 44px;
    border-radius: 12px;
    background: #fff;
    color: #000;
    text-decoration: none;
    font-weight: 600;
    border: 1px solid #ddd;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.page-link:hover {
    background: #f5f5f5;
    color: #E8001D;
    border-color: #E8001D;
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.page-item.active .page-link {
    background: #E8001D;
    color: #fff;
    border-color: #E8001D;
    box-shadow: 0 4px 12px rgba(232, 0, 29, 0.3);
}

.page-item.disabled .page-link {
    background: #f9f9f9;
    color: #ccc;
    border-color: #eee;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.page-link svg {
    width: 18px;
    height: 18px;
}

/* Cart Modal Styles */
.cart-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(5px);
}

.cart-modal.active {
    display: flex;
}

.cart-modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
}

.cart-modal-content {
    background: #fff;
    width: 90%;
    max-width: 650px;
    border-radius: 24px;
    padding: 2.5rem;
    position: relative;
    z-index: 1001;
    animation: modalSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

@keyframes modalSlideUp {
    from { transform: translateY(40px) scale(0.95); opacity: 0; }
    to { transform: translateY(0) scale(1); opacity: 1; }
}

.close-modal {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    background: #f5f5f5;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
    transition: all 0.2s;
    line-height: 1;
}

.close-modal:hover {
    background: #E8001D;
    color: #fff;
    transform: rotate(90deg);
}

.cart-modal-body {
    display: flex;
    gap: 2rem;
}

.modal-product-image {
    width: 40%;
}

.modal-product-image img {
    width: 100%;
    border-radius: 16px;
    object-fit: cover;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.modal-product-details {
    flex: 1;
}

#modalName {
    font-size: 1.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    color: #1a1a1a;
}

.modal-price {
    font-size: 1.25rem;
    color: #E8001D;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.modal-stock {
    font-size: 0.95rem;
    color: #666;
    margin-bottom: 2rem;
    background: #f5f5f5;
    display: inline-block;
    padding: 0.2rem 0.8rem;
    border-radius: 20px;
}

.quantity-control {
    margin-bottom: 2rem;
}

.qty-wrapper {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.qty-btn {
    width: 40px;
    height: 40px;
    border: 1px solid #ddd;
    background: #fff;
    border-radius: 12px;
    cursor: pointer;
    font-weight: bold;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.qty-btn:hover {
    background: #f5f5f5;
    border-color: #bbb;
}

.qty-input {
    width: 60px;
    height: 40px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
}

.modal-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 800;
    font-size: 1.2rem;
    border-top: 1px solid #eee;
    padding-top: 1.5rem;
    margin-bottom: 1.5rem;
}

.w-full { width: 100%; }
.mt-4 { margin-top: 1rem; }
.btn-primary {
    background: #E8001D;
    color: #fff;
    padding: 1rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 1rem;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 15px rgba(232, 0, 29, 0.3);
}
.btn-primary:hover {
    background: #c20015;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(232, 0, 29, 0.4);
}

@media (max-width: 900px) {
    .produk-wrapper {
        flex-direction: column;
    }
    .sidebar-filter {
        width: 100%;
        position: static;
        margin-bottom: 2rem;
    }
    .produk-grid {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    }
}
</style>

<style>
.produk-page {
    background: var(--light-gray);
    min-height: 100vh;
    padding-bottom: 3rem;
}

.page-header {
    background: linear-gradient(135deg, var(--black) 0%, var(--dark-gray) 100%);
    padding: 3rem 0;
    margin-bottom: 2rem;
    border-bottom: 3px solid var(--primary-red);
}

.page-title {
    color: var(--white);
    font-size: 2.5rem;
    font-weight: 900;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
}

.page-subtitle {
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.1rem;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
}

.produk-wrapper {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2rem;
}

/* Sidebar Filter */
.sidebar-filter {
    background: var(--white);
    padding: 1.5rem;
    border-radius: 12px;
    height: fit-content;
    position: sticky;
    top: 90px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.filter-section {
    padding: 1.5rem 0;
    border-bottom: 1px solid var(--light-gray);
}

.filter-section:last-child {
    border-bottom: none;
}

.filter-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--black);
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-options {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
}

.filter-option {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: color 0.3s;
}

.filter-option:hover {
    color: var(--primary-red);
}

.filter-option input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--primary-red);
}

.price-range {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    width: 100%;
}

.price-input {
    flex: 1 1 0;
    min-width: 0;
    padding: 0.6rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 0.9rem;
    outline: none;
}

.price-range span {
    flex: 0 0 auto;
}

.price-input:focus {
    border-color: var(--primary-red);
}

.price-hint {
    font-size: 0.85rem;
    color: #666;
    margin-top: 0.4rem;
}

.btn-apply-price,
.btn-reset-filter {
    width: 100%;
    padding: 0.8rem;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-transform: uppercase;
    font-size: 0.85rem;
}

.btn-apply-price {
    background: var(--primary-red);
    color: var(--white);
}

.btn-apply-price:hover {
    background: var(--dark-red);
    transform: translateY(-2px);
}

.btn-reset-filter {
    background: var(--light-gray);
    color: var(--black);
    margin-top: 1rem;
}

.btn-reset-filter:hover {
    background: #e0e0e0;
}

/* Produk Content */
.produk-content {
    background: var(--white);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.produk-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--light-gray);
}

.produk-count {
    color: #666;
    font-size: 0.95rem;
}

.produk-count strong {
    color: var(--primary-red);
    font-weight: 700;
}

.toolbar-right {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.toolbar-right label {
    font-weight: 600;
    color: #666;
    font-size: 0.9rem;
}

.sort-select {
    padding: 0.6rem 2.5rem 0.6rem 1rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 0.9rem;
    cursor: pointer;
    outline: none;
    background: var(--white);
    transition: border-color 0.3s;
}

.sort-select:hover,
.sort-select:focus {
    border-color: var(--primary-red);
}

/* Produk Grid */
.produk-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.2rem;
}

.produk-card {
    background: var(--white);
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s;
    border: 1px solid #f0f0f0;
    position: relative;
}

.produk-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    transform: translateY(-4px);
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

.produk-location {
    display: flex;
    align-items: center;
    gap: 0.2rem;
    font-size: 0.75rem;
    color: #999;
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
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
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

/* No Produk */
.no-produk {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
}

.no-produk svg {
    margin-bottom: 1rem;
    color: #ccc;
}

.no-produk h3 {
    font-size: 1.5rem;
    color: var(--black, #111);
    margin-bottom: 0.5rem;
}

.no-produk p {
    color: #999;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid var(--light-gray);
}

/* Responsive */
@media (max-width: 1200px) {
    .produk-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
}

@media (max-width: 992px) {
    .produk-wrapper {
        grid-template-columns: 1fr;
    }
    
    .sidebar-filter {
        position: static;
    }
    
    .produk-grid {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    }
}

@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .produk-toolbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .toolbar-right {
        width: 100%;
        justify-content: space-between;
    }
    
    .produk-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.8rem;
    }
    
    .produk-info {
        padding: 0.8rem;
    }
    
    .produk-name {
        font-size: 0.85rem;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 1rem;
    }
    
    .produk-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.6rem;
    }
}
</style>

 
@endsection
