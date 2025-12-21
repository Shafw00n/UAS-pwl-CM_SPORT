<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Notification;
use App\Models\Order;

class AdminController extends Controller
{
    public function dashboard()
    {
        $produkAktif = Produk::where('is_active', true)
            ->where('stok', '>', 0)
            ->count();

        $pesananBaru = 0;
        $nilaiKeranjangHariIni = 0;
        if (Schema::hasTable('orders')) {
            $pesananBaru = DB::table('orders')
                ->whereDate('created_at', now()->toDateString())
                ->count();
            $nilaiKeranjangHariIni = DB::table('orders')
                ->whereDate('created_at', now()->toDateString())
                ->sum('total');
        } elseif (Schema::hasTable('cart')) {
            $pesananBaru = DB::table('cart')
                ->whereDate('created_at', now()->toDateString())
                ->count();
            $nilaiKeranjangHariIni = DB::table('cart')
                ->join('produk', 'cart.produk_id', '=', 'produk.produk_id')
                ->whereDate('cart.created_at', now()->toDateString())
                ->sum(DB::raw('cart.quantity * produk.harga'));
        }

        return view('admin.dashboard', compact('produkAktif', 'pesananBaru', 'nilaiKeranjangHariIni'));
    }

    public function categories()
    {
        $items = Kategori::withCount([
                'produk as produk_count',
                'produkAktif as aktif_count',
            ])
            ->orderBy('nama_kategori')
            ->get();
        return view('admin.categories', compact('items'));
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => ['required','string','max:100', 'unique:kategori,nama_kategori'],
            'deskripsi' => ['nullable','string'],
        ]);
        Kategori::create($data);
        return redirect()->route('admin.categories')->with('success','Kategori ditambahkan');
    }

    public function editCategory($id)
    {
        $editing = Kategori::findOrFail($id);
        $items = Kategori::withCount([
                'produk as produk_count',
                'produkAktif as aktif_count',
            ])
            ->orderBy('nama_kategori')
            ->get();
        return view('admin.categories', compact('items','editing'));
    }

    public function updateCategory(Request $request, $id)
    {
        $data = $request->validate([
            'nama_kategori' => ['required','string','max:100', 'unique:kategori,nama_kategori,'.$id.',kategori_id'],
            'deskripsi' => ['nullable','string'],
        ]);
        $model = Kategori::findOrFail($id);
        $model->update($data);
        return redirect()->route('admin.categories')->with('success','Kategori diperbarui');
    }

    public function destroyCategory($id)
    {
        $model = Kategori::withCount('produk')->findOrFail($id);
        
        if ($model->produk_count > 0) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus kategori yang memiliki produk.');
        }

        $model->delete();
        return redirect()->route('admin.categories')->with('success','Kategori dihapus');
    }

    public function products()
    {
        $items = Produk::with('kategori')
            ->orderBy('created_at','desc')
            ->get();
        $kategoriList = Kategori::orderBy('nama_kategori')->get();
        return view('admin.products', compact('items','kategoriList'));
    }

    public function storeProduct(Request $request)
    {
        $data = $request->validate([
            'nama_produk' => ['required','string','max:150'],
            'kategori_id' => ['required','integer','exists:kategori,kategori_id'],
            'harga' => ['required','numeric','min:0'],
            'stok' => ['required','integer','min:0'],
            'deskripsi' => ['nullable','string'],
            'is_active' => ['nullable','boolean'],
            'gambar' => ['nullable','image','max:4096'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $ext = strtolower($file->getClientOriginalExtension());
            $name = Str::slug($data['nama_produk']) ?: 'produk';
            $filename = time().'_'.$name.'.'.$ext;
            $dir = public_path('products');
            if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
            $file->move($dir, $filename);
            $data['gambar'] = 'products/'.$filename;
        }
        Produk::create($data);
        return redirect()->route('admin.products')->with('success','Produk ditambahkan');
    }

    public function editProduct($id)
    {
        $editing = Produk::with('kategori')->findOrFail($id);
        $items = Produk::with('kategori')->orderBy('created_at','desc')->get();
        $kategoriList = Kategori::orderBy('nama_kategori')->get();
        return view('admin.products', compact('items','kategoriList','editing'));
    }

    public function updateProduct(Request $request, $id)
    {
        $data = $request->validate([
            'nama_produk' => ['required','string','max:150'],
            'kategori_id' => ['required','integer','exists:kategori,kategori_id'],
            'harga' => ['required','numeric','min:0'],
            'stok' => ['required','integer','min:0'],
            'deskripsi' => ['nullable','string'],
            'is_active' => ['nullable','boolean'],
            'gambar' => ['nullable','image','max:4096'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $model = Produk::findOrFail($id);
        if ($request->hasFile('gambar')) {
            if (!empty($model->gambar)) {
                $oldPublic = public_path($model->gambar);
                if (file_exists($oldPublic)) { @unlink($oldPublic); }
                Storage::disk('public')->delete(ltrim($model->gambar,'/'));
            }
            $file = $request->file('gambar');
            $ext = strtolower($file->getClientOriginalExtension());
            $name = Str::slug($data['nama_produk']) ?: 'produk';
            $filename = time().'_'.$name.'.'.$ext;
            $dir = public_path('products');
            if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
            $file->move($dir, $filename);
            $data['gambar'] = 'products/'.$filename;
        }
        $model->update($data);
        return redirect()->route('admin.products')->with('success','Produk diperbarui');
    }

    public function destroyProduct($id)
    {
        // Cek apakah produk ada di dalam pesanan
        if (Schema::hasTable('order_items')) {
            $inOrder = DB::table('order_items')->where('produk_id', $id)->exists();
            if ($inOrder) {
                return redirect()->back()->with('error', 'Produk tidak dapat dihapus karena terdapat dalam riwayat pesanan. Silakan nonaktifkan produk ini saja.');
            }
        }

        $model = Produk::findOrFail($id);
        $model->delete();
        return redirect()->route('admin.products')->with('success','Produk dihapus');
    }

    public function transactions()
    {
        return redirect()->route('admin.orders');
    }

    public function orders()
    {
        $notifUnread = 0;
        $notifRecent = collect();
        if (Schema::hasTable('notifications') && auth()->check()) {
            $notifUnread = Notification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count();
            $notifRecent = Notification::where('user_id', auth()->id())
                ->latest()
                ->limit(5)
                ->get();
        }

        $orders = [];
        if (Schema::hasTable('orders')) {
            $orders = Order::with(['user', 'items.produk'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('admin.orders', compact('orders', 'notifUnread', 'notifRecent'));
    }

    public function approvePayment($id)
    {
        $order = DB::table('orders')->where('order_id', $id)->first();
        DB::table('orders')->where('order_id', $id)->update([
            'payment_status' => 'approved',
            'status' => 'diproses',
            'paid_at' => now(),
            'admin_note' => null,
        ]);

        if ($order) {
            Notification::create([
                'user_id' => $order->user_id,
                'type' => 'order',
                'title' => 'Pembayaran Diterima',
                'message' => 'Pembayaran untuk pesanan #' . $id . ' telah diterima. Pesanan sedang diproses.',
                'link' => route('account.orders.detail', $id),
                'icon' => 'fa-check-circle',
                'is_read' => false,
            ]);
        }

        return back()->with('success','Pembayaran disetujui');
    }

    public function rejectPayment(Request $request, $id)
    {
        $request->validate(['reason' => 'nullable|string|max:255']);
        $order = Order::with('items.produk')->where('order_id', $id)->first();
        
        if ($order && !in_array($order->status, ['dibatalkan', 'ditolak'])) {
            // Restore stock
            foreach($order->items as $item) {
                if ($item->produk) {
                    $item->produk->increment('stok', $item->quantity);
                    $item->produk->decrement('terjual', $item->quantity);
                }
            }
        }

        DB::table('orders')->where('order_id', $id)->update([
            'payment_status' => 'rejected',
            'status' => 'ditolak',
            'admin_note' => $request->reason,
        ]);

        if ($order) {
            Notification::create([
                'user_id' => $order->user_id,
                'type' => 'order',
                'title' => 'Pembayaran Ditolak',
                'message' => 'Pembayaran untuk pesanan #' . $id . ' ditolak. Alasan: ' . ($request->reason ?? '-'),
                'link' => route('account.orders.detail', $id),
                'icon' => 'fa-times-circle',
                'is_read' => false,
            ]);
        }

        return back()->with('success','Pembayaran ditolak dan stok dikembalikan');
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);
        $order = Order::with('items.produk')->where('order_id', $id)->first();
        
        if ($order && in_array($request->status, ['dibatalkan', 'ditolak']) && !in_array($order->status, ['dibatalkan', 'ditolak'])) {
            // Restore stock if cancelling
            foreach($order->items as $item) {
                if ($item->produk) {
                    $item->produk->increment('stok', $item->quantity);
                    $item->produk->decrement('terjual', $item->quantity);
                }
            }
        }
        
        DB::table('orders')->where('order_id', $id)->update([
            'status' => $request->status
        ]);

        if ($order) {
            $statusLabel = ucfirst(str_replace('_', ' ', $request->status));
            Notification::create([
                'user_id' => $order->user_id,
                'type' => 'order',
                'title' => 'Status Pesanan Diperbarui',
                'message' => "Status pesanan #{$id} berubah menjadi: {$statusLabel}",
                'link' => route('account.orders.detail', $id),
                'icon' => 'fa-box',
                'is_read' => false,
            ]);
        }

        return back()->with('success','Status pesanan diperbarui');
    }

    public function createNotification()
    {
        return view('admin.notifications.create');
    }

    public function storeNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link' => 'nullable|url'
        ]);

        $users = \App\Models\User::all();
        
        foreach($users as $user) {
            Notification::create([
                'user_id' => $user->user_id ?? $user->id,
                'type' => 'promo',
                'title' => $request->title,
                'message' => $request->message,
                'link' => $request->link,
                'icon' => 'fa-bullhorn',
                'is_read' => false,
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Notifikasi promo dikirim ke semua pengguna.');
    }

    public function finance()
    {
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        $penjualan = 0;
        $items = collect();

        if (\Illuminate\Support\Facades\Schema::hasTable('orders')) {
            $ordersApproved = \Illuminate\Support\Facades\DB::table('orders')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('status', 'selesai')
                ->orderBy('created_at','desc')
                ->get();

            $penjualan = (int) $ordersApproved->sum('total');

            foreach ($ordersApproved as $o) {
                $items->push([
                    'tanggal' => \Carbon\Carbon::parse($o->created_at)->toDateString(),
                    'kategori' => 'Penjualan',
                    'keterangan' => 'Order #'.$o->order_id,
                    'jumlah' => (int) $o->total,
                ]);
            }
        }

        $pengeluaran = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('expenses')) {
            $expenses = \Illuminate\Support\Facades\DB::table('expenses')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->orderBy('date','desc')
                ->get();
            $pengeluaran = (int) $expenses->sum('amount');
            foreach ($expenses as $ex) {
                $items->push([
                    'tanggal' => \Carbon\Carbon::parse($ex->date)->toDateString(),
                    'kategori' => 'Pengeluaran',
                    'keterangan' => $ex->description ?? 'Pengeluaran',
                    'jumlah' => -1 * (int) $ex->amount,
                ]);
            }
        }

        // Urutkan terbaru
        $items = $items->sortByDesc('tanggal')->values();

        $ringkasan = [
            'penjualan_bulan_ini' => $penjualan,
            'pengeluaran_bulan_ini' => $pengeluaran,
            'laba_bulan_ini' => $penjualan - $pengeluaran,
        ];

        return view('admin.finance', compact('ringkasan', 'items'));
    }

    public function financeExportCsv()
    {
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        $rows = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('orders')) {
            $orders = \Illuminate\Support\Facades\DB::table('orders')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('status', 'selesai')
                ->orderBy('created_at','desc')
                ->get();
            foreach ($orders as $o) {
                $rows[] = [
                    \Carbon\Carbon::parse($o->created_at)->toDateString(),
                    'Penjualan',
                    'Order #'.$o->order_id,
                    (int) $o->total,
                ];
            }
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('expenses')) {
            $expenses = \Illuminate\Support\Facades\DB::table('expenses')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->orderBy('date','desc')
                ->get();
            foreach ($expenses as $ex) {
                $rows[] = [
                    \Carbon\Carbon::parse($ex->date)->toDateString(),
                    'Pengeluaran',
                    $ex->description ?? 'Pengeluaran',
                    -1 * (int) $ex->amount,
                ];
            }
        }

        $filename = 'finance_'.now()->format('Y_m').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"'
        ];

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fwrite($out, "\xEF\xBB\xBF");
            
            // Title Section
            fputcsv($out, ['LAPORAN KEUANGAN CM SPORT']);
            fputcsv($out, ['Periode', now()->translatedFormat('F Y')]);
            fputcsv($out, ['Dicetak pada', now()->format('d-m-Y H:i')]);
            fputcsv($out, []); // Empty row
            
            // Table Header
            fputcsv($out, ['Tanggal', 'Kategori', 'Keterangan', 'Jumlah (IDR)']);
            
            // Data Rows
            $totalPemasukan = 0;
            $totalPengeluaran = 0;
            
            // Sort rows by date desc (optional, but good practice)
            usort($rows, function($a, $b) {
                return strcmp($b[0], $a[0]);
            });

            foreach ($rows as $r) { 
                fputcsv($out, $r);
                
                // Calculate totals
                $amount = $r[3];
                if ($amount > 0) {
                    $totalPemasukan += $amount;
                } else {
                    $totalPengeluaran += abs($amount);
                }
            }
            
            fputcsv($out, []); // Empty row
            
            // Summary Section
            fputcsv($out, ['RINGKASAN']);
            fputcsv($out, ['Total Pemasukan', $totalPemasukan]);
            fputcsv($out, ['Total Pengeluaran', $totalPengeluaran]);
            fputcsv($out, ['Laba Bersih', $totalPemasukan - $totalPengeluaran]);
            
            fclose($out);
        }, $filename, $headers);
    }

    public function storeExpense(Request $request)
    {
        $data = $request->validate([
            'date' => ['required','date'],
            'description' => ['nullable','string','max:255'],
            'amount' => ['required','numeric','min:0'],
        ]);
        if (!Schema::hasTable('expenses')) {
            return back()->with('error', 'Tabel expenses belum tersedia');
        }
        DB::table('expenses')->insert([
            'date' => $data['date'],
            'description' => $data['description'] ?? null,
            'amount' => (int) $data['amount'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->route('admin.finance')->with('success', 'Pengeluaran ditambahkan');
    }
}
