<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Services\OrderNotificationService;

class AdminController extends Controller
{
    private function orderStatuses(): array
    {
        return [
            'Pesanan diterima',
            'Sedang diproses',
            'Sedang dikirim',
            'Sampai tujuan',
            'Pesanan selesai',
        ];
    }

    private function orderStatusLabel(string $status): string
    {
        return match ($status) {
            'Pesanan diterima' => 'Menunggu diproses',
            'Sedang diproses' => 'Diproses',
            'Sedang dikirim' => 'Dikirim',
            'Sampai tujuan' => 'Sudah sampai',
            'Pesanan selesai' => 'Selesai',
            default => $status,
        };
    }

    private function paymentStatuses(): array
    {
        return [
            'Menunggu Pembayaran',
            'Menunggu Verifikasi',
            'Dibayar',
            'Ditolak',
            'Refund',
            'Bayar Saat Diterima',
        ];
    }

    private function paymentStatusLabel(?string $status): string
    {
        return match ($status) {
            'Menunggu Pembayaran' => 'Menunggu Pembayaran',
            'Menunggu Verifikasi' => 'Menunggu Verifikasi',
            'Dibayar' => 'Dibayar',
            'Ditolak' => 'Ditolak',
            'Refund' => 'Refund',
            'Bayar Saat Diterima' => 'Bayar Saat Diterima',
            default => $status ?: 'Belum ada status',
        };
    }

    private function orderWithItems(int $id)
    {
        $order = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.name as buyer_name')
            ->where('orders.id', $id)
            ->first();

        if (!$order) {
            return null;
        }

        $items = DB::table('order_items')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->where('order_items.order_id', $id)
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

    // Dashboard Admin Sederhana
    public function dashboard() {
        $products = Product::withTrashed()
            ->latest()
            ->get();

        $ordersSummary = DB::table('orders')
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('COALESCE(SUM(total_price), 0) as total_omzet')
            ->first();

        $recentOrders = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.name as buyer_name')
            ->orderBy('orders.created_at', 'desc')
            ->limit(5)
            ->get();

        $totalPesanan = (int) ($ordersSummary->total_orders ?? 0);
        $totalOmzet = (float) ($ordersSummary->total_omzet ?? 0);

        return view('admin_dashboard', compact('products', 'recentOrders', 'totalPesanan', 'totalOmzet'));
    }

    // Tambah Produk Baru
    public function storeProduct(Request $request)
{
    $request->validate([
    'name' => 'required|string|max:255',
    'description' => 'nullable|string|max:1000',
    'category' => 'required|string|max:100',
    'price' => 'required|numeric|min:0',
    'stock' => 'required|integer|min:0',
    'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
]);

    $imagePath = null;

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('products', 'public');
        $imagePath = 'storage/' . $path;
    }

    Product::create([
        'name' => $request->name,
        'description' => $request->description,
        'category' => $request->category,
        'price' => $request->price,
        'stock' => $request->stock,
        'image_url' => $imagePath,
    ]);

    return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil ditambahkan.');
}
    // edit hapus produk
    public function updateProduct(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'category' => 'required|string|max:100',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $product = Product::withTrashed()->findOrFail($id);

    $imagePath = $product->image_url;

    if ($request->hasFile('image')) {

        // Hapus gambar lama kalau gambar lama berasal dari storage lokal
        if ($product->image_url && str_starts_with($product->image_url, 'storage/')) {
            $oldPath = str_replace('storage/', '', $product->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $path = $request->file('image')->store('products', 'public');
        $imagePath = 'storage/' . $path;
    }

    $product->update([
        'name' => $request->name,
        'description' => $request->description,
        'category' => $request->category,
        'price' => $request->price,
        'stock' => $request->stock,
        'image_url' => $imagePath,
    ]);

    return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil diperbarui.');
}

public function deleteProduct($id)
{
    $product = Product::findOrFail($id);

    $product->delete();

    return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil dinonaktifkan dan tidak tampil di katalog.');
}

public function restoreProduct($id)
{
    $product = Product::withTrashed()->findOrFail($id);

    if (! $product->trashed()) {
        return back()->with('success', 'Produk sudah aktif.');
    }

    $product->restore();

    return back()->with('success', 'Produk berhasil diaktifkan kembali.');
}

    // Kelola Stok
    public function updateStock(Request $request, $id) {
        $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        DB::table('products')->where('id', $id)->update([
            'stock' => $request->stock,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Stok sukses diperbarui!');
    }

    // Ubah Status Pesanan (Tracking Pengiriman)
    public function updateStatus(Request $request, $id) {
        $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', $this->orderStatuses())],
        ]);

        $update = [
            'status' => $request->status,
            'updated_at' => now(),
        ];

        if ($request->status === 'Sedang dikirim') {
            $update['shipped_at'] = now();
        }

        if ($request->status === 'Sampai tujuan') {
            $update['delivered_at'] = now();
        }

        DB::table('orders')->where('id', $id)->update($update);

        if ($request->status === 'Sedang dikirim') {
            app(OrderNotificationService::class)->notifyShipped((int) $id);
        } elseif ($request->status === 'Sampai tujuan') {
            app(OrderNotificationService::class)->notifyArrived((int) $id);
        }

        return back()->with('success', 'Status pesanan berhasil diubah!');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => ['required', 'string', 'in:' . implode(',', $this->paymentStatuses())],
            'payment_rejected_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $update = [
            'payment_status' => $request->payment_status,
            'payment_verified_at' => in_array($request->payment_status, ['Dibayar', 'Refund'], true) ? now() : null,
            'payment_rejected_reason' => in_array($request->payment_status, ['Ditolak', 'Refund'], true)
                ? $request->input('payment_rejected_reason')
                : null,
            'updated_at' => now(),
        ];

        DB::table('orders')->where('id', $id)->update($update);

        if ($request->payment_status === 'Dibayar') {
            app(OrderNotificationService::class)->notifyPaymentApproved((int) $id);
        } elseif ($request->payment_status === 'Ditolak') {
            app(OrderNotificationService::class)->notifyPaymentRejected((int) $id, $request->input('payment_rejected_reason'));
        }

        return back()->with('success', 'Status pembayaran berhasil diubah!');
    }

    public function updateShipment(Request $request, $id)
    {
        $request->validate([
            'courier_name' => ['nullable', 'string', 'max:255'],
            'tracking_number' => ['nullable', 'string', 'max:255'],
            'tracking_url' => ['nullable', 'url', 'max:1000'],
        ]);

        DB::table('orders')->where('id', $id)->update([
            'courier_name' => $request->courier_name,
            'tracking_number' => $request->tracking_number,
            'tracking_url' => $request->tracking_url,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Data pengiriman berhasil diperbarui.');
    }

    public function ordersIndex(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status');
        $paymentStatus = $request->input('payment_status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $statuses = $this->orderStatuses();
        $paymentStatuses = $this->paymentStatuses();

        $baseQuery = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id');

        if ($search !== '') {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('orders.id', 'like', '%' . $search . '%')
                    ->orWhere('users.name', 'like', '%' . $search . '%')
                    ->orWhere('orders.receiver_name', 'like', '%' . $search . '%')
                    ->orWhere('orders.phone', 'like', '%' . $search . '%');
            });
        }

        if (!empty($status)) {
            $baseQuery->where('orders.status', $status);
        }

        if (!empty($paymentStatus)) {
            $baseQuery->where('orders.payment_status', $paymentStatus);
        }

        if (!empty($startDate)) {
            $baseQuery->whereDate('orders.created_at', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $baseQuery->whereDate('orders.created_at', '<=', $endDate);
        }

        $orders = (clone $baseQuery)
            ->select('orders.*', 'users.name as buyer_name')
            ->orderBy('orders.created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $summary = (clone $baseQuery)
            ->selectRaw('COUNT(orders.id) as total_orders')
            ->selectRaw('COALESCE(SUM(orders.total_price), 0) as total_omzet')
            ->selectRaw('SUM(CASE WHEN orders.status = "Pesanan diterima" THEN 1 ELSE 0 END) as waiting_orders')
            ->selectRaw('SUM(CASE WHEN orders.status = "Sedang diproses" THEN 1 ELSE 0 END) as processing_orders')
            ->selectRaw('SUM(CASE WHEN orders.status = "Sedang dikirim" THEN 1 ELSE 0 END) as shipping_orders')
            ->selectRaw('SUM(CASE WHEN orders.status = "Sampai tujuan" THEN 1 ELSE 0 END) as arrived_orders')
            ->selectRaw('SUM(CASE WHEN orders.status = "Pesanan selesai" THEN 1 ELSE 0 END) as completed_orders')
            ->selectRaw('SUM(CASE WHEN orders.payment_status = "Menunggu Pembayaran" THEN 1 ELSE 0 END) as waiting_payment_orders')
            ->selectRaw('SUM(CASE WHEN orders.payment_status = "Menunggu Verifikasi" THEN 1 ELSE 0 END) as verifying_payment_orders')
            ->selectRaw('SUM(CASE WHEN orders.payment_status = "Dibayar" THEN 1 ELSE 0 END) as paid_orders')
            ->first();

        return view('admin_orders', [
            'orders' => $orders,
            'summary' => $summary,
            'statuses' => $statuses,
            'paymentStatuses' => $paymentStatuses,
            'search' => $search,
            'status' => $status,
            'paymentStatus' => $paymentStatus,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function orderShow($id)
    {
        $order = $this->orderWithItems((int) $id);

        if (!$order) {
            abort(404);
        }

        $statusLabel = $this->orderStatusLabel($order->status);
        $paymentStatusLabel = $this->paymentStatusLabel($order->payment_status ?? null);
        $paymentStatuses = $this->paymentStatuses();

        return view('admin_order_show', compact('order', 'statusLabel', 'paymentStatusLabel', 'paymentStatuses'));
    }
    
    //laporan penjualan
    public function salesReport(Request $request)
{
    $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
    $endDate = $request->end_date ?? now()->toDateString();
    $status = $request->status;
    $paymentStatus = $request->payment_status;

    $statuses = $this->orderStatuses();
    $paymentStatuses = $this->paymentStatuses();

    /*
    |--------------------------------------------------------------------------
    | 1. Data pesanan detail
    |--------------------------------------------------------------------------
    | Query ini dipakai untuk tabel transaksi.
    | Boleh memakai orders.* karena tidak memakai GROUP BY.
    */

    $ordersQuery = DB::table('orders')
        ->join('users', 'orders.user_id', '=', 'users.id')
        ->select(
            'orders.*',
            'users.name as buyer_name'
        )
        ->whereDate('orders.created_at', '>=', $startDate)
        ->whereDate('orders.created_at', '<=', $endDate);

    if (!empty($status)) {
        $ordersQuery->where('orders.status', $status);
    }

    if (!empty($paymentStatus)) {
        $ordersQuery->where('orders.payment_status', $paymentStatus);
    }

    $orders = $ordersQuery
        ->orderBy('orders.created_at', 'desc')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | 2. Ringkasan utama
    |--------------------------------------------------------------------------
    */

    $totalOmzet = $orders->sum('total_price');
    $totalPesanan = $orders->count();
    $rataRataPesanan = $totalPesanan > 0 ? $totalOmzet / $totalPesanan : 0;
    $pesananSelesai = $orders->where('status', 'Pesanan selesai')->count();

    /*
    |--------------------------------------------------------------------------
    | 3. Grafik penjualan harian
    |--------------------------------------------------------------------------
    | Query ini khusus untuk rekap per tanggal.
    | Jangan memakai orders.* di sini.
    */

    $salesByDateQuery = DB::table('orders')
        ->whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate);

    if (!empty($status)) {
        $salesByDateQuery->where('status', $status);
    }

    if (!empty($paymentStatus)) {
        $salesByDateQuery->where('payment_status', $paymentStatus);
    }

    $salesByDate = $salesByDateQuery
        ->selectRaw('DATE(created_at) as tanggal')
        ->selectRaw('COUNT(id) as total_orders')
        ->selectRaw('COALESCE(SUM(total_price), 0) as total_sales')
        ->groupByRaw('DATE(created_at)')
        ->orderBy('tanggal', 'asc')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | 4. Rekap status pesanan
    |--------------------------------------------------------------------------
    */

    $statusSummary = DB::table('orders')
        ->whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)
        ->select('status')
        ->selectRaw('COUNT(id) as total_orders')
        ->selectRaw('COALESCE(SUM(total_price), 0) as total_sales')
        ->groupBy('status')
        ->orderBy('status', 'asc')
        ->get();

    $paymentStatusSummary = DB::table('orders')
        ->whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)
        ->select('payment_status')
        ->selectRaw('COUNT(id) as total_orders')
        ->selectRaw('COALESCE(SUM(total_price), 0) as total_sales')
        ->groupBy('payment_status')
        ->orderBy('payment_status', 'asc')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | 5. Produk stok menipis
    |--------------------------------------------------------------------------
    */

    $lowStockProducts = DB::table('products')
        ->where('stock', '<=', 5)
        ->orderBy('stock', 'asc')
        ->get();

    return view('admin_sales_report', compact(
        'orders',
        'statuses',
        'paymentStatuses',
        'startDate',
        'endDate',
        'status',
        'paymentStatus',
        'totalOmzet',
        'totalPesanan',
        'rataRataPesanan',
        'pesananSelesai',
        'salesByDate',
        'statusSummary',
        'paymentStatusSummary',
        'lowStockProducts'
    ));
}

public function exportSalesReport(Request $request)
{
    $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
    $endDate = $request->end_date ?? now()->toDateString();
    $status = $request->status;
    $paymentStatus = $request->payment_status;

    $query = DB::table('orders')
        ->join('users', 'orders.user_id', '=', 'users.id')
        ->select(
            'orders.id',
            'users.name as buyer_name',
            'orders.total_price',
            'orders.status',
            'orders.created_at'
        )
        ->whereDate('orders.created_at', '>=', $startDate)
        ->whereDate('orders.created_at', '<=', $endDate);

    if (!empty($status)) {
        $query->where('orders.status', $status);
    }

    if (!empty($paymentStatus)) {
        $query->where('orders.payment_status', $paymentStatus);
    }

    $orders = $query->orderBy('orders.created_at', 'desc')->get();

    $fileName = 'laporan-penjualan-' . $startDate . '-sampai-' . $endDate . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ];

    $callback = function () use ($orders) {
        $file = fopen('php://output', 'w');

        fputcsv($file, [
            'ID Pesanan',
            'Pembeli',
            'Total Harga',
            'Status',
            'Status Pembayaran',
            'Tanggal Pesanan',
        ]);

        foreach ($orders as $order) {
            fputcsv($file, [
                $order->id,
                $order->buyer_name,
                $order->total_price,
                $order->status,
                $order->payment_status ?? '-',
                $order->created_at,
            ]);
        }

        fclose($file);
    };

    return Response::stream($callback, 200, $headers);
 }
}
