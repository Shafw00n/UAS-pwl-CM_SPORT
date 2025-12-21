<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\User;
use App\Models\Cart;

class AccountController extends Controller
{
    public function profile()
    {
        return view('account.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'username' => ['nullable','string','max:100'],
            'nama_lengkap' => ['nullable','string','max:150'],
            'alamat' => ['nullable','string','max:255'],
            'no_telepon' => ['nullable','string','max:30'],
            'name' => ['nullable','string','max:100'],
        ];
        $data = $request->validate($rules);
        $updates = [];
        foreach (['username','nama_lengkap','alamat','no_telepon','name'] as $f) {
            if (array_key_exists($f, $data) && Schema::hasColumn('users', $f)) {
                $updates[$f] = $data[$f];
            }
        }
        if ($updates) {
            $user->update($updates);
        }
        return back()->with('success', 'Profil diperbarui');
    }

    public function orders()
    {
        $status = request('status');
        $q = Order::where('user_id', auth()->id())->with('items.produk')->orderBy('created_at','desc');
        if ($status) {
            $q->where('status', $status);
        }
        $orders = $q->paginate(20)->withQueryString();
        $counts = [
            'menunggu_pembayaran' => Order::where('user_id', auth()->id())->where('status','menunggu_pembayaran')->count(),
            'sedang_dikemas' => Order::where('user_id', auth()->id())->where('status','sedang_dikemas')->count(),
            'dikirim' => Order::where('user_id', auth()->id())->where('status','dikirim')->count(),
            'selesai' => Order::where('user_id', auth()->id())->where('status','selesai')->count(),
            'dibatalkan' => Order::where('user_id', auth()->id())->where('status','dibatalkan')->count(),
        ];
        return view('account.orders', compact('orders','counts','status'));
    }

    public function orderDetail($id)
    {
        $order = Order::with('items.produk')->where('user_id', auth()->id())->findOrFail($id);
        return view('account.order_detail', compact('order'));
    }

    public function wishlist()
    {
        $items = collect();
        return view('account.wishlist', compact('items'));
    }

    public function settings()
    {
        return view('account.settings');
    }

    public function updateSettings()
    {
        return back()->with('success', 'Pengaturan diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required','string','min:6','confirmed']
        ]);
        $user = auth()->user();
        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password diperbarui');
    }

    public function help()
    {
        return view('account.help');
    }

    public function cancelOrder($orderId)
    {
        $order = Order::with('items.produk')->where('user_id', auth()->id())->findOrFail($orderId);
        if (in_array($order->status, ['dikirim','selesai'])) {
            return back()->with('error','Tidak dapat membatalkan pesanan ini');
        }

        if (!in_array($order->status, ['dibatalkan', 'ditolak'])) {
            // Restore stock
            foreach($order->items as $item) {
                if ($item->produk) {
                    $item->produk->increment('stok', $item->quantity);
                    $item->produk->decrement('terjual', $item->quantity);
                }
            }
        }

        $order->update(['status' => 'dibatalkan']);
        return back()->with('success','Pesanan dibatalkan');
    }

    public function completeOrder($orderId)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($orderId);
        if ($order->status !== 'dikirim') {
            return back()->with('error','Pesanan belum dikirim atau sudah selesai');
        }
        $order->update(['status' => 'selesai']);
        return back()->with('success','Pesanan diterima dan selesai. Terima kasih!');
    }
}
