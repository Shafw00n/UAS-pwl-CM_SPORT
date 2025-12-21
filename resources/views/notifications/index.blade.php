@extends('layouts.master')

@section('title', 'Notifikasi')

@section('content')
<div class="admin-layout {{ !auth()->user()->isAdmin() ? 'single-column' : '' }}">
    @if(auth()->check() && auth()->user()->isAdmin())
    @include('admin.partials.sidebar')
    @endif
    <div class="admin-content {{ !auth()->user()->isAdmin() ? 'centered-content' : '' }}">
        
        <div class="notif-header-container">
            <div class="admin-title">Notifikasi</div>
            
            <div class="notif-controls">
                <div class="notif-filters">
                    <a href="{{ route('notifications.all', ['filter' => 'all']) }}" class="filter-pill {{ $filter==='all' ? 'active' : '' }}">Semua</a>
                    <a href="{{ route('notifications.all', ['filter' => 'unread']) }}" class="filter-pill {{ $filter==='unread' ? 'active' : '' }}">
                        Belum Dibaca 
                        @if(($unreadCount ?? 0) > 0)
                        <span class="badge-count">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('notifications.all', ['filter' => 'order']) }}" class="filter-pill {{ $filter==='order' ? 'active' : '' }}">Pesanan</a>
                    <a href="{{ route('notifications.all', ['filter' => 'promo']) }}" class="filter-pill {{ $filter==='promo' ? 'active' : '' }}">Promo</a>
                </div>

                <div class="notif-global-actions">
                    @if(($unreadCount ?? 0) > 0)
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-action">
                            <i class="fas fa-check-double"></i> Tandai Semua
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('notifications.delete-all-read') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action danger">
                            <i class="fas fa-trash-alt"></i> Bersihkan Dibaca
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="admin-card" style="color:#0a0;background:#e6ffed;border:1px solid #a7f3d0;margin-bottom:1rem">{{ session('success') }}</div>
        @endif

        @if($notifications->count() > 0)
        <div class="notif-list">
            @foreach($notifications as $notification)
            <div class="notif-item {{ !$notification->is_read ? 'is-new' : '' }}">
                <div class="notif-main">
                    <div class="notif-icon-wrapper {{ $notification->type === 'promo' ? 'promo' : '' }}">
                        <i class="fas {{ $notification->icon ?? 'fa-bell' }}"></i>
                    </div>
                    <div class="notif-info">
                        <div class="notif-top">
                            <span class="notif-title">{{ $notification->title }}</span>
                            @if($notification->type === 'promo')
                            <span class="tag-promo">PROMO</span>
                            @endif
                            <span class="notif-time">{{ $notification->created_at?->diffForHumans() }}</span>
                        </div>
                        <div class="notif-message">{{ $notification->message }}</div>
                    </div>
                </div>
                
                <div class="notif-actions">
                    @if($notification->link)
                    <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-item primary">Buka</button>
                    </form>
                    @elseif(!$notification->is_read)
                    <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-item">Tandai</button>
                    </form>
                    @endif
                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-item danger-outline">Hapus</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pagination-wrapper">{{ $notifications->links() }}</div>
        @else
        <div class="empty-state">
            <i class="fas fa-bell-slash"></i>
            <h3>Tidak Ada Notifikasi</h3>
            <p>
                @if($filter === 'unread')
                    Semua notifikasi sudah dibaca.
                @elseif($filter === 'read')
                    Belum ada notifikasi yang dibaca.
                @elseif($filter === 'order')
                    Belum ada notifikasi pesanan.
                @elseif($filter === 'promo')
                    Belum ada notifikasi promo.
                @else
                    Belum ada notifikasi.
                @endif
            </p>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    body {
        background-color: #fdfdfd;
        background-image: 
            radial-gradient(at 0% 0%, rgba(230, 0, 35, 0.03) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(20, 20, 20, 0.03) 0px, transparent 50%),
            linear-gradient(#f0f0f0 1.5px, transparent 1.5px), 
            linear-gradient(90deg, #f0f0f0 1.5px, transparent 1.5px);
        background-size: 100% 100%, 100% 100%, 40px 40px, 40px 40px;
        background-position: 0 0, 0 0, -1.5px -1.5px, -1.5px -1.5px;
    }

    .single-column{display:block}
    .centered-content{margin:0 auto;max-width:900px;width:100%}
    
    /* Header & Controls */
    .notif-header-container {
        margin-bottom: 2.5rem;
        margin-top: 1rem;
    }
    .admin-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        color: #111;
        letter-spacing: -0.02em;
    }
    .notif-controls {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        justify-content: space-between;
        align-items: center;
    }
    .notif-filters {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .filter-pill {
        padding: 0.6rem 1.25rem;
        border-radius: 999px;
        background: #fff;
        border: 1px solid #e5e7eb;
        color: #4b5563;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .filter-pill:hover {
        background: #f9fafb;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        color: #111;
    }
    .filter-pill.active {
        background: #111;
        color: #fff;
        border-color: #111;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .badge-count {
        background: #ef4444;
        color: white;
        font-size: 0.75rem;
        padding: 0.15rem 0.5rem;
        border-radius: 99px;
        line-height: 1;
    }
    
    .notif-global-actions {
        display: flex;
        gap: 0.75rem;
    }
    .btn-action {
        background: #fff;
        border: 1px solid #e5e7eb;
        color: #374151;
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }
    .btn-action:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        transform: translateY(-1px);
    }
    .btn-action.danger {
        color: #dc2626;
        border-color: #fee2e2;
        background: #fef2f2;
    }
    .btn-action.danger:hover {
        background: #fee2e2;
        border-color: #fecaca;
    }

    /* Notification List */
    .notif-list {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }
    .notif-item {
        background: #fff;
        border: 1px solid rgba(229, 231, 235, 0.7);
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1.5rem;
        transition: all 0.2s;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    }
    .notif-item:hover {
        border-color: #d1d5db;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        transform: translateY(-1px);
    }
    .notif-item.is-new {
        background: #fffafa;
        border-left: 5px solid #ef4444;
    }
    
    .notif-main {
        display: flex;
        gap: 1.25rem;
        align-items: flex-start;
        flex: 1;
    }
    .notif-icon-wrapper {
        width: 56px;
        height: 56px;
        flex-shrink: 0;
        border-radius: 14px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        font-size: 1.25rem;
    }
    .notif-icon-wrapper.promo {
        background: #fef2f2;
        color: #ef4444;
    }
    
    .notif-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        flex: 1;
    }
    .notif-top {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .notif-title {
        font-weight: 700;
        color: #111;
        font-size: 1.15rem;
        line-height: 1.3;
    }
    .tag-promo {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        font-size: 0.7rem;
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        font-weight: 700;
        letter-spacing: 0.025em;
        text-transform: uppercase;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
    }
    .notif-time {
        font-size: 0.9rem;
        color: #9ca3af;
    }
    .notif-message {
        color: #4b5563;
        font-size: 1.05rem;
        line-height: 1.6;
    }
    
    /* Action Buttons */
    .notif-actions {
        display: flex;
        gap: 0.75rem;
        align-self: center;
    }
    .btn-item {
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid #e5e7eb;
        background: white;
        color: #374151;
        white-space: nowrap;
    }
    .btn-item:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        color: #111;
    }
    .btn-item.primary {
        background: #111;
        color: white;
        border-color: #111;
    }
    .btn-item.primary:hover {
        background: #27272a;
        border-color: #27272a;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .btn-item.danger-outline {
        color: #ef4444;
        border-color: #fee2e2;
        background: transparent;
    }
    .btn-item.danger-outline:hover {
        background: #fef2f2;
        border-color: #fecaca;
        color: #dc2626;
    }

    /* Pagination & Empty State */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 2.5rem;
    }
    .empty-state {
        text-align: center;
        padding: 6rem 2rem;
        background: #fff;
        border-radius: 24px;
        border: 1px dashed #e5e7eb;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .empty-state i {
        font-size: 4rem;
        color: #e5e7eb;
        margin-bottom: 1.5rem;
    }
    .empty-state h3 {
        font-size: 1.5rem;
        margin-bottom: 0.75rem;
        color: #111;
        font-weight: 700;
    }
    .empty-state p {
        color: #6b7280;
        font-size: 1.1rem;
        max-width: 400px;
    }

    /* Mobile Responsive */
    @media (max-width: 640px) {
        .notif-controls {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }
        .notif-filters {
            overflow-x: auto;
            padding-bottom: 0.5rem;
            flex-wrap: nowrap;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .notif-filters::-webkit-scrollbar {
            display: none;
        }
        .filter-pill {
            flex-shrink: 0;
            white-space: nowrap;
        }
        .notif-item {
            flex-direction: column;
            gap: 1.25rem;
            padding: 1.25rem;
        }
        .notif-actions {
            width: 100%;
            justify-content: flex-end;
            padding-top: 1rem;
            border-top: 1px solid #f3f4f6;
        }
        .btn-item {
            flex: 1;
            text-align: center;
            justify-content: center;
        }
    }
</style>
@endpush
@endsection
