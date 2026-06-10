<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ShopController extends Controller
{
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
        ->get();

    return view('dashboard', compact('products', 'search', 'category', 'categories'));
}

    // Masuk Keranjang Belanja
    public function addToCart(Request $request, $id)
{
    $product = DB::table('products')->where('id', $id)->first();

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
        ->select(
            'carts.*',
            'products.name',
            'products.price',
            'products.stock',
            'products.image_url as image'
        )
        ->get();

    return view('cart', compact('items'));
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
    public function checkout(Request $request) {
        $items = DB::table('carts')->where('user_id', Auth::id())->get();
        if($items->isEmpty()) return back()->with('error', 'Keranjang kosong');

        $totalPrice = 0;
        foreach ($items as $item) {
    $product = DB::table('products')->where('id', $item->product_id)->first();

    if (!$product) {
        return back()->with('error', 'Ada produk yang tidak ditemukan.');
    }

    if ($item->quantity > $product->stock) {
        return back()->with('error', 'Stok produk "' . $product->name . '" tidak mencukupi.');
    }

    $totalPrice += ($product->price * $item->quantity);

    DB::table('products')
        ->where('id', $item->product_id)
        ->decrement('stock', $item->quantity);
}

        // Simpan ke tabel Order
        $orderId = DB::table('orders')->insertGetId([
            'user_id' => Auth::id(),
            'total_price' => $totalPrice,
            'address' => $request->address,
            'status' => 'Pesanan diterima',
            'created_at' => now()
        ]);

        // Pindah item ke order_items
        foreach ($items as $item) {
            $product = DB::table('products')->where('id', $item->product_id)->first();
            DB::table('order_items')->insert([
                'order_id' => $orderId,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $product->price,
                'created_at' => now()
            ]);
        }

        // Kosongkan keranjang
        DB::table('carts')->where('user_id', Auth::id())->delete();

        return redirect()->route('orders.history')->with('success', 'Checkout Berhasil!');
    }

    // Riwayat Pesanan & Tracking Status
    public function orderHistory() {
        $orders = DB::table('orders')->where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        return view('orders_history', compact('orders'));
    }
}