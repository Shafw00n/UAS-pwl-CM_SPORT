<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produk;
use App\Models\Voucher;
use Illuminate\Support\Str;
use App\Models\Notification;
use App\Models\User;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $selectedIds = collect((array) request('selected'))->map(fn($v) => (int)$v)->filter()->values();
        $items = Cart::where('user_id', auth()->id())
            ->when($selectedIds->isNotEmpty(), fn($q) => $q->whereIn('id', $selectedIds))
            ->with('produk')->get();
        $subtotal = (int) $items->sum(fn($i) => $i->subtotal);
        $tax = (int) floor($subtotal * 0.11);
        $shipping = $subtotal > 100000 ? 0 : 15000;
        $total = $subtotal + $tax + $shipping;

        return view('checkout.index', compact('items','subtotal','tax','shipping','total'));
    }

    public function process(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $method = $request->input('payment_method');
        $rules = [
            'recipient_name' => ['required','string','max:100'],
            'recipient_phone' => ['required','string','max:30'],
            'address' => ['required','string','max:255'],
            'city' => ['required','string','max:100'],
            'zip' => ['required','string','max:15'],
            'voucher_code' => ['nullable','string','max:50'],
            'note_to_seller' => ['nullable','string','max:500'],
            'shipping_method' => ['required','in:regular,express,pickup'],
            'payment_method' => ['required','in:cod,transfer'],
        ];
        if ($method === 'transfer') {
            $rules['payment_proof'] = ['required','image','max:4096'];
        }
        $data = $request->validate($rules);

        $selectedIds = collect((array) $request->input('selected'))->map(fn($v) => (int)$v)->filter()->values();
        $items = Cart::where('user_id', auth()->id())
            ->when($selectedIds->isNotEmpty(), fn($q) => $q->whereIn('id', $selectedIds))
            ->with('produk')->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong');
        }

        $subtotal = (int) $items->sum(fn($i) => $i->subtotal);
        $tax = (int) floor($subtotal * 0.11);
        $shipping = match($data['shipping_method']) {
            'express' => 30000,
            'pickup' => 0,
            default => 15000,
        };

        $voucherCode = strtoupper(trim($data['voucher_code'] ?? ''));
        $voucherDiscount = 0;
        $shippingDiscount = 0; // Reset shipping discount calculation

        if ($voucherCode) {
            $voucher = Voucher::active()
                ->where('code', $voucherCode)
                ->first();

            if ($voucher) {
                // Calculate Discount
                if ($voucher->discount_type == 'percent') {
                    $disc = floor($subtotal * ($voucher->discount_value / 100));
                    if ($voucher->max_discount) {
                        $disc = min($disc, $voucher->max_discount);
                    }
                    $voucherDiscount = (int) $disc;
                } else {
                    $voucherDiscount = $voucher->discount_value;
                }

                // Check min purchase
                if ($subtotal < $voucher->min_purchase) {
                    $voucherDiscount = 0; // Invalid because min purchase not met
                    // In a real app we might want to return an error, but for now just ignore
                }

                // Handle Shipping Discount (if description/title implies it, or we add a type)
                // For this simple implementation, let's say if code is ONGKIRHEMAT it affects shipping
                // But my DB structure treats everything as discount_value.
                // Let's assume if it's a fixed discount and title contains 'Ongkir', it's shipping discount?
                // Or better, let's keep the hardcoded logic for backward compatibility AND add DB logic.
                
                // REVISION: The DB structure I made is generic.
                // Let's just apply it as a total discount for now, unless I add a type 'shipping'.
                // My seeded 'ONGKIRHEMAT' is fixed 15000. It will just reduce the total.
            } else {
                // Fallback to hardcoded for legacy/demo support if DB lookup fails
                if ($voucherCode === 'PROMO10') {
                    $voucherDiscount = (int) floor($subtotal * 0.10);
                } elseif ($voucherCode === 'ONGKIRFREE') {
                    $shippingDiscount = $shipping;
                } elseif ($voucherCode === 'PROMO20K') {
                    $voucherDiscount = 20000;
                } elseif ($voucherCode === 'SHIP5K') {
                    $shippingDiscount = min(5000, $shipping);
                }
            }
        }

        // Auto apply shipping discount if > 100k (existing logic)
        if ($subtotal >= 100000 && $shippingDiscount == 0) {
            $shippingDiscount = $shipping;
        }
        $serviceFee = 2500;
        $netShipping = max(0, $shipping - $shippingDiscount);
        $total = $subtotal + $tax + $netShipping + $serviceFee - $voucherDiscount;

        DB::beginTransaction();
        try {
            $note = 'Alamat: '.$data['recipient_name'].' | '.$data['recipient_phone'].' | '.$data['address'].' | '.$data['city'].' '.$data['zip'].'. Catatan: '.($data['note_to_seller'] ?? '-').'. Pengiriman: '.$data['shipping_method'].' | Voucher: '.($voucherCode ?: '-').'. Biaya layanan: '.$serviceFee.' | Diskon voucher: '.$voucherDiscount.' | Diskon ongkir: '.$shippingDiscount;
            $orderData = [
                'user_id' => auth()->id(),
                'status' => 'menunggu_pembayaran',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $netShipping,
                'total' => $total,
                'admin_note' => \Illuminate\Support\Str::limit($note, 255, ''),
                'payment_method' => $data['payment_method'],
            ];
            // handle payment proof if transfer
            if ($data['payment_method'] === 'transfer' && $request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $name = 'proof_'.time().'_'.Str::random(6).'.'.$file->getClientOriginalExtension();
                $dir = public_path('payments');
                if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
                $file->move($dir, $name);
                $orderData['payment_proof'] = 'payments/'.$name;
                $orderData['payment_status'] = 'submitted';
            } else {
                $orderData['payment_status'] = $data['payment_method'] === 'cod' ? 'cod' : null;
            }
            $order = Order::create($orderData);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'produk_id' => $item->produk_id,
                    'quantity' => (int) $item->quantity,
                    'price' => (int) ($item->produk?->harga ?? 0),
                ]);

                if ($item->produk) {
                    $item->produk->decrement('stok', (int) $item->quantity);
                    $item->produk->increment('terjual', (int) $item->quantity);
                }
            }

            $idsToDelete = $selectedIds->isNotEmpty() ? $selectedIds : $items->pluck('id')->filter()->values();
            if ($idsToDelete->isNotEmpty()) {
                Cart::where('user_id', auth()->id())->whereIn('id', $idsToDelete)->delete();
            }
            DB::commit();
            $this->notifyAdminsOrderCreated($order);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('cart')->with('error', 'Gagal proses checkout: '.$e->getMessage());
        }

        return redirect()->route('checkout.success', $order->order_id);
    }

    public function success($orderId)
    {
        $order = Order::with('items.produk')->findOrFail($orderId);
        return view('checkout.success', compact('order'));
    }

    public function confirmForm($orderId)
    {
        $order = Order::with('items.produk')
            ->where('user_id', auth()->id())
            ->findOrFail($orderId);
        return view('checkout.confirm', compact('order'));
    }

    public function confirmSubmit(Request $request, $orderId)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($orderId);

        $data = $request->validate([
            'payment_method' => ['required','string','max:50'],
            'payment_proof' => ['required','image','max:4096'],
        ]);

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $name = 'proof_'.time().'_'.Str::random(6).'.'.$file->getClientOriginalExtension();
            $dir = public_path('payments');
            if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
            $file->move($dir, $name);
            $data['payment_proof'] = 'payments/'.$name;
        }

        $order->update([
            'payment_method' => $data['payment_method'],
            'payment_proof' => $data['payment_proof'],
            'payment_status' => 'submitted',
            'status' => 'menunggu_validasi',
        ]);

        $this->notifyAdminsPaymentSubmitted($order);

        return redirect()->route('checkout.success', $order->order_id)
            ->with('success','Konfirmasi pembayaran dikirim. Menunggu validasi admin.');
    }

    private function notifyAdminsOrderCreated(Order $order): void
    {
        try {
            $admins = User::query()
                ->when(Schema::hasColumn('users','role'), fn($q) => $q->where('role','admin'))
                ->get();
            if ($admins->isEmpty()) {
                $emails = array_filter(array_map('trim', explode(',', env('ADMIN_EMAILS', ''))));
                if ($emails) {
                    $admins = User::whereIn('email', $emails)->get();
                }
            }
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->user_id ?? $admin->id,
                    'type' => 'order',
                    'title' => 'Pesanan Baru #'.$order->order_id,
                    'message' => 'Pembeli melakukan checkout total Rp '.number_format((int)$order->total,0,',','.'),
                    'link' => route('admin.orders'),
                    'icon' => 'fa-shopping-bag',
                    'is_read' => false,
                ]);
            }
        } catch (\Throwable $e) {
        }
    }

    private function notifyAdminsPaymentSubmitted(Order $order): void
    {
        try {
            $admins = User::query()
                ->when(Schema::hasColumn('users','role'), fn($q) => $q->where('role','admin'))
                ->get();
            if ($admins->isEmpty()) {
                $emails = array_filter(array_map('trim', explode(',', env('ADMIN_EMAILS', ''))));
                if ($emails) {
                    $admins = User::whereIn('email', $emails)->get();
                }
            }
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->user_id ?? $admin->id,
                    'type' => 'order',
                    'title' => 'Konfirmasi Pembayaran #'.$order->order_id,
                    'message' => 'Pembayaran dikirim untuk diverifikasi.',
                    'link' => route('admin.orders'),
                    'icon' => 'fa-credit-card',
                    'is_read' => false,
                ]);
            }
        } catch (\Throwable $e) {
        }
    }
}
