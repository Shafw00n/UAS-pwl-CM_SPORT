<aside class="admin-sidebar">
    <div class="admin-brand">CM SPORT Admin</div>
    <nav class="admin-nav">
        <a href="{{ route('admin.dashboard') }}" class="admin-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.categories') }}" class="admin-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <span>Kelola Kategori</span>
        </a>
        <a href="{{ route('admin.products') }}" class="admin-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
            <span>Kelola Produk</span>
        </a>
        <a href="{{ route('admin.orders') }}" class="admin-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <span>Kelola Transaksi</span>
        </a>
        <a href="{{ route('admin.finance') }}" class="admin-link {{ request()->routeIs('admin.finance*') ? 'active' : '' }}">
            <span>Kelola Keuangan</span>
        </a>
        <a href="{{ route('admin.notifications.create') }}" class="admin-link {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}">
            <span>Kirim Notifikasi</span>
        </a>
    </nav>
</aside>
<style>
.admin-layout{
    display:grid;
    grid-template-columns:260px 1fr;
    gap:0;
    min-height:calc(100vh - 80px); /* Adjusted for navbar height */
}

.admin-sidebar {
    background: #000;
    border-right: 1px solid #222;
    color: #fff;
    padding: 2rem 1.5rem;
    height: 100%;
}

.admin-brand {
    font-size: 1.2rem;
    font-weight: 800;
    letter-spacing: 0.5px;
    margin-bottom: 2rem;
    color: #fff;
    padding-left: 0.5rem;
}

.admin-nav {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.admin-link {
    display: flex;
    align-items: center;
    color: #888;
    text-decoration: none;
    padding: 0.8rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.admin-link:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(4px);
}

.admin-link.active {
    background: #E8001D;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(232, 0, 29, 0.3);
}

.admin-link.active:hover {
    transform: none;
}

/* Common Admin Styles that might be needed */
.admin-content {
    padding: 2rem 2.5rem;
    background: #f8f9fa;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.admin-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: #111;
}

.btn-admin {
    padding: 0.6rem 1.2rem;
    border: 1px solid #ddd;
    background: #fff;
    color: #333;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.2s;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-admin:hover {
    background: #f5f5f5;
    border-color: #ccc;
}

.btn-admin.primary {
    background: #E8001D;
    color: #fff;
    border-color: #E8001D;
}

.btn-admin.primary:hover {
    background: #c20015;
    border-color: #c20015;
}

.admin-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02);
}

.admin-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.admin-table th {
    padding: 1rem;
    background: #f8f9fa;
    font-weight: 600;
    color: #444;
    text-align: left;
    border-bottom: 2px solid #eee;
}

.admin-table td {
    padding: 1rem;
    border-bottom: 1px solid #eee;
    color: #333;
}

.admin-table tr:last-child td {
    border-bottom: none;
}

/* KPI Cards */
.admin-kpi {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.kpi-item {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02);
}

.kpi-item div:first-child {
    color: #666;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.kpi-value {
    font-size: 2rem;
    font-weight: 800;
    color: #111;
}
</style>
