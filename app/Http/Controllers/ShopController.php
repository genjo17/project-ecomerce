<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\UserAddress;
use App\Services\OrderNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ShopController extends Controller
{
    private function shippingOptions(): array
    {
        return [
            'pickup' => [
                'label' => 'Ambil di Tempat',
                'description' => 'Ambil langsung di toko tanpa biaya ongkir.',
                'eta' => 'Siap diambil hari ini',
                'cost' => 0,
            ],
            'store_courier' => [
                'label' => 'Kurir Toko',
                'description' => 'Diantar oleh kurir toko untuk area sekitar.',
                'eta' => '1-2 hari kerja',
                'cost' => 12000,
            ],
            'regular' => [
                'label' => 'Reguler',
                'description' => 'Pengiriman standar dengan jasa ekspedisi.',
                'eta' => '2-4 hari kerja',
                'cost' => 18000,
            ],
            'express' => [
                'label' => 'Express',
                'description' => 'Prioritas lebih cepat untuk pesanan mendesak.',
                'eta' => '1 hari kerja',
                'cost' => 30000,
            ],
        ];
    }

    private function paymentOptions(): array
    {
        return [
            'bank_transfer' => [
                'label' => 'Transfer Bank',
                'description' => 'Bayar ke rekening toko setelah pesanan dibuat.',
                'fee' => 0,
            ],
            'qris' => [
                'label' => 'QRIS',
                'description' => 'Scan QRIS dari admin untuk pembayaran cepat.',
                'fee' => 0,
            ],
            'cod' => [
                'label' => 'COD / Bayar di Tempat',
                'description' => 'Bayar tunai saat pesanan diterima.',
                'fee' => 2000,
            ],
        ];
    }

    private function orderQuery()
    {
        return DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.name as buyer_name', 'users.email as buyer_email');
    }

    private function orderWithItems(int $orderId)
    {
        $query = $this->orderQuery()->where('orders.id', $orderId);

        if (Auth::user()?->role !== 'admin') {
            $query->where('orders.user_id', Auth::id());
        }

        $order = $query->first();

        if (!$order) {
            return null;
        }

        $items = DB::table('order_items')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->where('order_items.order_id', $orderId)
            ->select(
                'order_items.*',
                DB::raw('COALESCE(order_items.product_name, products.name, "Produk dihapus") as product_name'),
                DB::raw('COALESCE(order_items.product_image, products.image_url) as product_image')
            )
            ->orderBy('order_items.id')
            ->get();

        $order->items = $items;

        return $order;
    }

    // Katalog Produk dengan Fitur Pencarian Freetext
    public function catalog(Request $request)
{
    $search = $request->search;
    $category = $request->category;

    $categories = [
        'Fashion',
        'Aksesoris',
        'Elektronik',
        'Peralatan Rumah',
        'Sembako',
        'Makanan & Minuman',
        'Kecantikan',
        'Lainnya',
    ];

    $products = Product::query()
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        })
        ->when($category, function ($query, $category) {
            $query->where('category', $category);
        })
        ->latest()
        ->paginate(9)
        ->withQueryString();

    return view('dashboard', compact('products', 'search', 'category', 'categories'));
}

    // Masuk Keranjang Belanja
    public function addToCart(Request $request, $id)
{
    $product = DB::table('products')
        ->where('id', $id)
        ->whereNull('deleted_at')
        ->first();

    if (!$product) {
        return back()->with('error', 'Produk tidak ditemukan!');
    }

    if ($product->stock < 1) {
        return back()->with('error', 'Stok habis!');
    }

    $request->validate([
        'quantity' => 'required|integer|min:1|max:' . $product->stock,
    ]);

    $quantity = (int) $request->quantity;

    $existing = DB::table('carts')
        ->where('user_id', Auth::id())
        ->where('product_id', $id)
        ->first();

    if ($existing) {
        $newQuantity = $existing->quantity + $quantity;

        if ($newQuantity > $product->stock) {
            return back()->with('error', 'Jumlah produk di keranjang melebihi stok yang tersedia!');
        }

        DB::table('carts')
            ->where('id', $existing->id)
            ->update([
                'quantity' => $newQuantity,
                'updated_at' => now(),
            ]);
    } else {
        DB::table('carts')->insert([
            'user_id' => Auth::id(),
            'product_id' => $id,
            'quantity' => $quantity,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return back()->with('success', 'Berhasil ditambah ke keranjang!');
}

   
    // Lihat Keranjang
public function viewCart()
{
    $items = DB::table('carts')
        ->join('products', 'carts.product_id', '=', 'products.id')
        ->where('carts.user_id', Auth::id())
        ->whereNull('products.deleted_at')
        ->select(
            'carts.*',
            'products.name',
            'products.price',
            'products.stock',
            'products.image_url as image'
        )
        ->get();

    $addresses = Auth::user()
        ->addresses()
        ->orderByDesc('is_default')
        ->orderByDesc('updated_at')
        ->get();

    $shippingOptions = $this->shippingOptions();
    $paymentOptions = $this->paymentOptions();

    return view('cart', compact('items', 'addresses', 'shippingOptions', 'paymentOptions'));
}
// Update Jumlah Produk di Keranjang
public function updateCart(Request $request, $id)
{
    $cart = DB::table('carts')
        ->where('id', $id)
        ->where('user_id', Auth::id())
        ->first();

    if (!$cart) {
        return back()->with('error', 'Item keranjang tidak ditemukan.');
    }

    $product = DB::table('products')
        ->where('id', $cart->product_id)
        ->whereNull('deleted_at')
        ->first();

    if (!$product) {
        return back()->with('error', 'Produk tidak ditemukan.');
    }

    $action = $request->input('action');
    $quantity = (int) $cart->quantity;

    if ($action === 'increase') {
        $quantity++;
    } elseif ($action === 'decrease') {
        $quantity--;
    } else {
        $quantity = (int) $request->input('quantity', $quantity);
    }

    if ($quantity < 1) {
        return back()->with('error', 'Jumlah produk minimal 1.');
    }

    if ($quantity > $product->stock) {
        return back()->with('error', 'Jumlah produk melebihi stok yang tersedia.');
    }

    DB::table('carts')
        ->where('id', $id)
        ->where('user_id', Auth::id())
        ->update([
            'quantity' => $quantity,
            'updated_at' => now(),
        ]);

    return back()->with('success', 'Jumlah produk berhasil diperbarui.');
}

// Hapus Produk dari Keranjang
public function removeFromCart($id)
{
    $cart = DB::table('carts')
        ->where('id', $id)
        ->where('user_id', Auth::id())
        ->first();

    if (!$cart) {
        return back()->with('error', 'Item keranjang tidak ditemukan.');
    }

    DB::table('carts')
        ->where('id', $id)
        ->where('user_id', Auth::id())
        ->delete();

    return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
}
    // Proses Checkout
    public function checkout(Request $request)
    {
        $items = DB::table('carts')->where('user_id', Auth::id())->get();
        if ($items->isEmpty()) {
            return back()->with('error', 'Keranjang kosong');
        }

        $request->validate([
            'shipping_address_id' => [
                'nullable',
                'integer',
                Rule::exists('user_addresses', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
            'receiver_name' => ['required_without:shipping_address_id', 'nullable', 'string', 'max:255'],
            'phone' => ['required_without:shipping_address_id', 'nullable', 'string', 'max:20'],
            'address' => ['required_without:shipping_address_id', 'nullable', 'string', 'max:1000'],
            'shipping_method' => ['required', 'string', Rule::in(array_keys($this->shippingOptions()))],
            'payment_method' => ['required', 'string', Rule::in(array_keys($this->paymentOptions()))],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $shippingOption = $this->shippingOptions()[$request->shipping_method];
        $paymentOption = $this->paymentOptions()[$request->payment_method];
        $shippingCost = (int) $shippingOption['cost'];
        $paymentFee = (int) $paymentOption['fee'];
        $selectedAddress = null;

        if ($request->filled('shipping_address_id')) {
            $selectedAddress = UserAddress::where('id', $request->shipping_address_id)
                ->where('user_id', Auth::id())
                ->first();
        }

        $receiverName = $selectedAddress?->recipient_name ?? $request->receiver_name;
        $phone = $selectedAddress?->phone ?? $request->phone;
        $shippingAddress = $selectedAddress?->address_line ?? $request->address;
        $addressLabel = $selectedAddress?->label;

        $orderId = null;

        try {
            DB::transaction(function () use ($items, $shippingCost, $paymentFee, $shippingOption, $paymentOption, $selectedAddress, $receiverName, $phone, $shippingAddress, $addressLabel, $request) {
                $products = [];
                $totalPrice = 0;

                foreach ($items as $item) {
                    $product = DB::table('products')
                        ->where('id', $item->product_id)
                        ->whereNull('deleted_at')
                        ->lockForUpdate()
                        ->first();

                    if (!$product) {
                        throw ValidationException::withMessages([
                            'cart' => 'Ada produk yang tidak ditemukan.',
                        ]);
                    }

                    if ($item->quantity > $product->stock) {
                        throw ValidationException::withMessages([
                            'cart' => 'Stok produk "' . $product->name . '" tidak mencukupi.',
                        ]);
                    }

                    $products[$item->product_id] = $product;
                    $totalPrice += ($product->price * $item->quantity);
                }

                $grandTotal = $totalPrice + $shippingCost + $paymentFee;

                foreach ($items as $item) {
                    $updated = DB::table('products')
                        ->where('id', $item->product_id)
                        ->whereNull('deleted_at')
                        ->where('stock', '>=', $item->quantity)
                        ->decrement('stock', $item->quantity);

                    if ($updated === 0) {
                        $product = $products[$item->product_id];

                        throw ValidationException::withMessages([
                            'cart' => 'Stok produk "' . $product->name . '" baru saja habis. Silakan cek keranjang lagi.',
                        ]);
                    }
                }

                $orderId = DB::table('orders')->insertGetId([
                    'user_id' => Auth::id(),
                    'subtotal_price' => $totalPrice,
                    'shipping_cost' => $shippingCost,
                    'payment_fee' => $paymentFee,
                    'total_price' => $grandTotal,
                    'shipping_address_id' => $selectedAddress?->id,
                    'address_label' => $addressLabel,
                    'receiver_name' => $receiverName,
                    'phone' => $phone,
                    'shipping_method' => $shippingOption['label'],
                    'payment_method' => $paymentOption['label'],
                    'payment_status' => $request->payment_method === 'cod' ? 'Bayar Saat Diterima' : 'Menunggu Pembayaran',
                    'address' => $shippingAddress,
                    'notes' => $request->notes,
                    'status' => 'Pesanan diterima',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($items as $item) {
                    $product = $products[$item->product_id];

                    DB::table('order_items')->insert([
                        'order_id' => $orderId,
                        'product_id' => $item->product_id,
                        'product_name' => $product->name,
                        'product_image' => $product->image_url,
                        'quantity' => $item->quantity,
                        'price' => $product->price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('carts')->where('user_id', Auth::id())->delete();

                $orderId = $orderId ?? null;
            });
        } catch (ValidationException $exception) {
            $message = collect($exception->errors())->flatten()->first() ?? 'Checkout gagal. Silakan coba lagi.';

            return back()->with('error', $message);
        }

        $latestOrder = DB::table('orders')
            ->where('user_id', Auth::id())
            ->orderByDesc('id')
            ->first();

        if ($latestOrder) {
            app(OrderNotificationService::class)->notifyCreated((int) $latestOrder->id);
        }

        return redirect()->route('orders.history')->with('success', 'Checkout Berhasil!');
    }

    // Riwayat Pesanan & Tracking Status
    public function orderHistory()
    {
        $orders = $this->orderQuery()
            ->where('orders.user_id', Auth::id())
            ->orderBy('orders.id', 'desc')
            ->get();

        $orderIds = $orders->pluck('id')->all();
        $itemsByOrder = collect();

        if (!empty($orderIds)) {
            $itemsByOrder = DB::table('order_items')
                ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
                ->whereIn('order_items.order_id', $orderIds)
                ->select(
                    'order_items.order_id',
                    'order_items.quantity',
                    'order_items.price',
                    'order_items.product_name as snapshot_name',
                    'order_items.product_image as snapshot_image',
                    DB::raw('COALESCE(order_items.product_name, products.name, "Produk dihapus") as product_name'),
                    DB::raw('COALESCE(order_items.product_image, products.image_url) as product_image')
                )
                ->orderBy('order_items.id')
                ->get()
                ->groupBy('order_id');
        }

        $orders->transform(function ($order) use ($itemsByOrder) {
            $order->items = $itemsByOrder->get($order->id, collect());
            return $order;
        });

        return view('orders_history', compact('orders'));
    }

    public function uploadPaymentProof(Request $request, $id)
    {
        $order = DB::table('orders')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        $request->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
        ]);

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        DB::table('orders')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->update([
                'payment_proof_path' => 'storage/' . $path,
                'payment_proof_uploaded_at' => now(),
                'payment_status' => 'Menunggu Verifikasi',
                'payment_rejected_reason' => null,
                'updated_at' => now(),
            ]);

        app(OrderNotificationService::class)->notifyPaymentProofUploaded((int) $id);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah.');
    }

    public function invoice($id)
    {
        $order = $this->orderWithItems((int) $id);

        if (!$order) {
            abort(404);
        }

        return view('order_invoice', compact('order'));
    }

    public function downloadInvoice($id)
    {
        $order = $this->orderWithItems((int) $id);

        if (!$order) {
            abort(404);
        }

        $html = view('order_invoice', compact('order'))->render();
        $fileName = 'invoice-order-' . $order->id . '.html';

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function confirmReceived($id)
    {
        $order = DB::table('orders')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        if ($order->status !== 'Sampai tujuan') {
            return back()->with('error', 'Pesanan hanya bisa dikonfirmasi setelah sampai tujuan.');
        }

        DB::table('orders')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->update([
                'status' => 'Pesanan selesai',
                'updated_at' => now(),
            ]);

        app(OrderNotificationService::class)->notifyCompleted((int) $id);

        return back()->with('success', 'Pesanan berhasil dikonfirmasi diterima.');
    }
}
