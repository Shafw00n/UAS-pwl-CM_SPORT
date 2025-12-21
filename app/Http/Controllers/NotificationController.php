<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationController extends Controller
{
    /**
     * Display all notifications
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        
        if (!Schema::hasTable('notifications')) {
            $notifications = new LengthAwarePaginator([], 0, 20, 1, [
                'path' => url()->current(),
                'pageName' => 'page',
            ]);
            $unreadCount = 0;
            return view('notifications.index', compact('notifications', 'unreadCount', 'filter'));
        }

        $query = auth()->user()->notifications()->latest();

        if ($filter === 'unread') {
            $query->unread();
        } elseif ($filter === 'read') {
            $query->read();
        } elseif ($filter === 'order') {
            $query->where('type', 'order');
        } elseif ($filter === 'promo') {
            $query->where('type', 'promo');
        }

        $notifications = $query->paginate(20);
        $unreadCount = auth()->user()->notifications()->unread()->count();

        return view('notifications.index', compact('notifications', 'unreadCount', 'filter'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        if (Schema::hasTable('notifications')) {
            auth()->user()->notifications()->unread()->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }

        return back()->with('success', 'All notifications marked as read');
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notification deleted');
    }

    /**
     * Delete all read notifications
     */
    public function deleteAllRead()
    {
        if (Schema::hasTable('notifications')) {
            auth()->user()->notifications()->read()->delete();
        }

        return back()->with('success', 'All read notifications deleted');
    }

    /**
     * Get unread notifications count (for AJAX)
     */
    public function getUnreadCount()
    {
        $count = Schema::hasTable('notifications')
            ? auth()->user()->notifications()->unread()->count()
            : 0;
        
        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications (for dropdown)
     */
    public function getRecent()
    {
        $notifications = Schema::hasTable('notifications')
            ? auth()->user()->notifications()->latest()->limit(5)->get()
            : collect();

        return response()->json($notifications);
    }
}
