<nav class="navbar-main">
    <div class="navbar-container">
        <!-- Logo -->
        <div class="navbar-logo">
            <a href="{{ route('home') }}">
                <span class="logo-text">CM <span class="logo-highlight">SPORT</span></span>
                @auth
                @if(auth()->user()->isAdmin())
                <span class="admin-badge">Admin</span>
                @endif
                @endauth
            </a>
        </div>

        <input type="checkbox" id="nav-toggle" style="display:none;">
        <label for="nav-toggle" class="navbar-toggle" aria-label="Toggle navigation">
            <span></span>
            <span></span>
            <span></span>
        </label>

        <!-- Menu (static) -->

        <!-- Navigation Links -->
        <ul class="navbar-menu" id="navbarMenu">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link {{ Request::is('/') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <span>BERANDA</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('produk.all') }}" class="nav-link {{ Request::is('produk*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    <span>Produk</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('trending.index') }}" class="nav-link {{ Request::is('trending') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                        <polyline points="17 6 23 6 23 12"/>
                    </svg>
                    <span>Trending</span>
                    <span class="trending-badge">HOT 🔥</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('promo.index') }}" class="nav-link {{ Request::is('promo') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                    </svg>
                    <span>Promo</span>
                    <span class="promo-badge">SALE</span>
                </a>
            </li>
        </ul>

        <!-- Right Actions -->
        <div class="navbar-actions">
            <!-- Notifications (link) -->
            @auth
            <a href="{{ route('notifications.all') }}" class="action-btn" title="Notifikasi">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
            </a>
            @endauth

            <!-- Cart (hanya tampil ketika login, non-admin) -->
            @auth
            @if(!auth()->user()->isAdmin())
            <a href="{{ route('cart') }}" class="action-btn" title="Keranjang">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"/>
                    <circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                @php
                    $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                @endphp
                @if($cartCount > 0)
                <span class="cart-count">{{ $cartCount }}</span>
                @endif
            </a>
            @endif
            @endauth

            <!-- User Account -->
            @auth
            @include('layouts.partials.user_menu')
            @else
            <a href="{{ route('login') }}" class="btn-login">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Login
            </a>
            @endauth
        </div>
    </div>

    <!-- Search Bar (static) -->
    <div class="search-bar">
        <div class="search-container">
            <form action="{{ route('produk.search') }}" method="GET">
                <input type="text" name="q" placeholder="Cari produk olahraga..." class="search-input" autocomplete="off">
                <button type="submit" class="search-submit">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</nav>

<style>
:root {
    --primary-red: #E8001D;
    --dark-red: #C20015;
    --black: #000000;
    --dark-gray: #1a1a1a;
    --light-gray: #f5f5f5;
    --white: #ffffff;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.navbar-main {
    background: linear-gradient(135deg, var(--black) 0%, var(--dark-gray) 100%);
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 4px 20px rgba(232, 0, 29, 0.3);
    border-bottom: 2px solid var(--primary-red);
}

.navbar-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 70px;
}

.navbar-logo a {
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.navbar-logo .logo-text {
    font-size: 1.8rem;
    font-weight: 900;
    color: var(--white);
    letter-spacing: 1px;
    text-transform: uppercase;
}

.navbar-logo .logo-highlight {
    color: var(--primary-red);
    position: relative;
}

.navbar-logo .logo-highlight::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--primary-red);
}

.navbar-toggle {
    display: none;
    gap: 5px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 5px;
}

.navbar-toggle span {
    width: 25px;
    height: 3px;
    background: var(--white);
    transition: all 0.3s;
    border-radius: 2px;
}

.navbar-menu {
    display: flex;
    list-style: none;
    gap: 2rem;
    margin: 0;
    padding: 0;
    align-items: center;
}

.nav-item {
    position: relative;
}

.nav-link {
    color: var(--white);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: color 0.3s;
    position: relative;
}

.nav-link svg {
    transition: transform 0.3s;
}

.nav-link:hover svg {
    transform: scale(1.1);
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--primary-red);
    transition: width 0.3s;
}

.nav-link:hover,
.nav-link.active {
    color: var(--primary-red);
}

.nav-link:hover::after,
.nav-link.active::after {
    width: 100%;
}

.dropdown-arrow {
    transition: transform 0.3s;
}

.dropdown:hover .dropdown-arrow {
    transform: rotate(180deg);
}

.trending-badge, .promo-badge {
    background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
    color: var(--white);
    font-size: 0.6rem;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 6px;
    margin-left: 4px;
    letter-spacing: 0.5px;
    position: relative;
    top: -8px;
    animation: pulse 2s infinite;
    box-shadow: 0 2px 5px rgba(232, 0, 29, 0.4);
}

.nav-link:hover .trending-badge,
.nav-link.active .trending-badge,
.nav-link:hover .promo-badge,
.nav-link.active .promo-badge {
    color: var(--white);
    transform: scale(1.1);
}

.nav-link:hover .promo-badge,
.nav-link.active .promo-badge {
    color: var(--white);
}

.admin-badge {
    display:inline-block;
    background:#111;
    color:#fff;
    border:1px solid #2c2c2c;
    padding:2px 8px;
    border-radius:10px;
    font-size:0.7rem;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.dropdown-menu {
    position: absolute;
    top: calc(100% + 10px);
    left: 0;
    background: var(--dark-gray);
    min-width: 250px;
    padding: 0.5rem 0;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s;
    border: 1px solid rgba(232, 0, 29, 0.3);
}

.dropdown:hover .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-header {
    padding: 0.75rem 1.5rem;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0.75rem 1.5rem;
    color: var(--white);
    text-decoration: none;
    transition: all 0.3s;
    font-size: 0.9rem;
    background: none;
    border: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
}

.dropdown-item:hover {
    background: rgba(232, 0, 29, 0.1);
    color: var(--primary-red);
    padding-left: 2rem;
}

.dropdown-item.featured {
    background: rgba(232, 0, 29, 0.2);
    font-weight: 700;
    margin-top: 0.5rem;
    border-top: 1px solid rgba(232, 0, 29, 0.3);
}

.dropdown-divider {
    border: none;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    margin: 0.5rem 0;
}

.dropdown-user-info {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 1rem 1.5rem;
}

.user-avatar {
    width: 45px;
    height: 45px;
    background: rgba(232, 0, 29, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-red);
}

.user-details {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.user-details strong {
    color: var(--white);
    font-size: 0.95rem;
}

.user-details span {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.8rem;
}

.navbar-actions {
    display: flex;
    gap: 1rem;
}



.action-btn {
    background: none;
    border: none;
    color: var(--white);
    cursor: pointer;
    padding: 0.6rem;
    border-radius: 50%;
    transition: all 0.3s;
    position: relative;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-btn:hover {
    background: rgba(232, 0, 29, 0.2);
    color: var(--primary-red);
    transform: scale(1.1);
}

.cart-count,
.notification-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    background: var(--primary-red);
    color: var(--white);
    font-size: 0.7rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    min-width: 18px;
    text-align: center;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
}

.btn-login {
    background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
    color: var(--white);
    padding: 0.6rem 1.5rem;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(232, 0, 29, 0.3);
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-login:hover {
    transform: translateY(-2px);
}

.dropdown-menu-right {
    left: auto;
    right: 0;
}

.logout-btn {
    color: var(--primary-red) !important;
}

.logout-btn:hover {
    background: rgba(232, 0, 29, 0.2) !important;
}

.search-bar {
    background: var(--dark-gray);
    padding: 1rem 0;
    border-top: 1px solid rgba(232, 0, 29, 0.3);
    display: none;
}

.search-bar.active {
    display: block;
    animation: slideDown 0.3s;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.search-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.search-container form {
    flex: 1;
    display: flex;
    gap: 0.5rem;
}

.search-input {
    flex: 1;
    background: var(--black);
    border: 2px solid rgba(232, 0, 29, 0.3);
    color: var(--white);
    padding: 0.8rem 1.5rem;
    border-radius: 30px;
    font-size: 1rem;
    outline: none;
    transition: border-color 0.3s;
}

.search-input:focus {
    border-color: var(--primary-red);
}

.search-input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.search-submit {
    background: var(--primary-red);
    border: none;
    color: var(--white);
    padding: 0.8rem 1.5rem;
    border-radius: 30px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-submit:hover {
    background: var(--dark-red);
    transform: scale(1.05);
}

.search-close {
    background: none;
    border: none;
    color: var(--white);
    cursor: pointer;
    padding: 0.5rem;
    transition: color 0.3s;
}

.search-close:hover {
    color: var(--primary-red);
}

/* Notification Panel */
.notification-panel {
    position: fixed;
    top: 70px;
    right: -400px;
    width: 380px;
    max-height: calc(100vh - 70px);
    background: var(--dark-gray);
    box-shadow: -5px 0 20px rgba(0, 0, 0, 0.3);
    transition: right 0.3s;
    z-index: 999;
    display: flex;
    flex-direction: column;
    border-left: 2px solid rgba(232, 0, 29, 0.3);
}

.notification-panel.active {
    right: 0;
}

.notification-header {
    padding: 1.5rem;
    background: var(--black);
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid rgba(232, 0, 29, 0.3);
}

.notification-header h3 {
    color: var(--white);
    font-size: 1.2rem;
    font-weight: 700;
}

.mark-read {
    background: none;
    border: none;
    color: var(--primary-red);
    font-size: 0.8rem;
    cursor: pointer;
    font-weight: 600;
    transition: color 0.3s;
}

.mark-read:hover {
    color: var(--dark-red);
}

.notification-list {
    flex: 1;
    overflow-y: auto;
}

.notification-item {
    display: flex;
    gap: 12px;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    transition: background 0.3s;
    cursor: pointer;
}

.notification-item:hover {
    background: rgba(232, 0, 29, 0.05);
}

.notification-item.unread {
    background: rgba(232, 0, 29, 0.1);
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.notification-icon.promo {
    background: rgba(255, 193, 7, 0.2);
    color: #FFC107;
}

.notification-icon.order {
    background: rgba(76, 175, 80, 0.2);
    color: #4CAF50;
}

.notification-icon.info {
    background: rgba(33, 150, 243, 0.2);
    color: #2196F3;
}

.notification-content {
    flex: 1;
}

.notification-content strong {
    display: block;
    color: var(--white);
    font-size: 0.95rem;
    margin-bottom: 4px;
}

.notification-content p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.85rem;
    margin-bottom: 6px;
    line-height: 1.4;
}

.notification-time {
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.75rem;
}

.notification-footer {
    padding: 1rem 1.5rem;
    background: var(--black);
    border-top: 2px solid rgba(232, 0, 29, 0.3);
    text-align: center;
}

.notification-footer a {
    color: var(--primary-red);
    font-weight: 600;
    font-size: 0.9rem;
    transition: color 0.3s;
}

.notification-footer a:hover {
    color: var(--dark-red);
}

@media (max-width: 1024px) {
    .navbar-menu {
        gap: 1.5rem;
    }
    
    .nav-link {
        font-size: 0.9rem;
    }
}

@media (max-width: 768px) {
    .navbar-toggle {
        display: flex;
        z-index: 1001;
    }
    
    .navbar-menu {
        position: fixed;
        top: 70px;
        left: -100%;
        width: 100%;
        height: calc(100vh - 70px);
        background: var(--dark-gray);
        flex-direction: column;
        gap: 0;
        padding: 2rem;
        transition: left 0.3s;
        overflow-y: auto;
        align-items: flex-start;
    }
    
    .navbar-menu.active {
        left: 0;
    }
    
    .nav-item {
        width: 100%;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .nav-link {
        padding: 1rem 0;
        width: 100%;
        justify-content: space-between;
    }
    
    .dropdown-menu {
        position: static;
        opacity: 1;
        visibility: visible;
        transform: none;
        box-shadow: none;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 0;
        margin-top: 0.5rem;
    }
    
    .dropdown.active .dropdown-menu {
    }
    
    .navbar-actions {
        gap: 0.5rem;
    }
    
    .btn-login {
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
    }
    
    .notification-panel {
        width: 100%;
        right: -100%;
    }
}

@media (max-width: 576px) {
    .navbar-container {
        padding: 0 1rem;
    }
    
    .navbar-logo .logo-text {
        font-size: 1.4rem;
    }
    
    .admin-badge {
        font-size: 0.6rem;
        padding: 1px 6px;
    }
    
    /* Reorder items: Logo (Left) - Actions (Right) - Toggle (Right) */
    .navbar-logo {
        order: 1;
        margin-right: auto;
        flex-shrink: 0;
    }

    .navbar-logo a {
        white-space: nowrap;
    }
    
    .navbar-actions {
        order: 2;
        gap: 0.3rem;
        flex-shrink: 1;
    }
    
    .navbar-toggle {
        order: 3;
        margin-left: 0.5rem;
    }
    
    .action-btn {
        padding: 0.4rem;
    }
    
    .action-btn svg {
        width: 18px;
        height: 18px;
    }

    /* Compact Login Button on Mobile */
    .btn-login {
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
    }
    
    .btn-login svg {
        width: 14px;
        height: 14px;
    }
}
</style>