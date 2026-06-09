<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    // Dashboard Admin Sederhana
    public function dashboard() {
        $products = DB::table('products')->get();
        
        // Perbaikan di sini: Menggunakan titik (.) bukan titik dua ganda (::)
        $orders = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.name as buyer_name')
            ->orderBy('orders.id', 'desc')
            ->get();
            
        return view('admin_dashboard', compact('products', 'orders'));
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
        'category' => $request->catgory,
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

    $product = Product::findOrFail($id);

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

    if ($product->image_url && str_starts_with($product->image_url, 'storage/')) {
        $oldPath = str_replace('storage/', '', $product->image_url);
        Storage::disk('public')->delete($oldPath);
    }

    $product->delete();

    return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil dihapus.');
}

    // Kelola Stok
    public function updateStock(Request $request, $id) {
        DB::table('products')->where('id', $id)->update(['stock' => $request->stock]);
        return back()->with('success', 'Stok sukses diperbarui!');
    }

    // Ubah Status Pesanan (Tracking Pengiriman)
    public function updateStatus(Request $request, $id) {
        DB::table('orders')->where('id', $id)->update(['status' => $request->status]);
        return back()->with('success', 'Status pesanan berhasil diubah!');
    }
    
    //laporan penjualan
    public function salesReport(Request $request)
{
    $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
    $endDate = $request->end_date ?? now()->toDateString();
    $status = $request->status;

    $statuses = [
        'Pesanan diterima',
        'Sedang diproses',
        'Sedang dikirim',
        'Sampai tujuan',
    ];

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
    $pesananSelesai = $orders->where('status', 'Sampai tujuan')->count();

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
        'startDate',
        'endDate',
        'status',
        'totalOmzet',
        'totalPesanan',
        'rataRataPesanan',
        'pesananSelesai',
        'salesByDate',
        'statusSummary',
        'lowStockProducts'
    ));
}

public function exportSalesReport(Request $request)
{
    $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
    $endDate = $request->end_date ?? now()->toDateString();
    $status = $request->status;

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
            'Tanggal Pesanan',
        ]);

        foreach ($orders as $order) {
            fputcsv($file, [
                $order->id,
                $order->buyer_name,
                $order->total_price,
                $order->status,
                $order->created_at,
            ]);
        }

        fclose($file);
    };

    return Response::stream($callback, 200, $headers);
 }
}