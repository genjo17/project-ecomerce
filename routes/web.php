<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK SEBELUM LOGIN
|--------------------------------------------------------------------------
*/

// Beranda sebelum login
Route::get('/', function () {
    return view('welcome');
})->name('beranda');

// Produk publik sebelum login
Route::get('/produk', function (Request $request) {
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
        ->paginate(12)
        ->withQueryString();

    return view('guest.produk', compact('products', 'search', 'category', 'categories'));
})->name('produk.public');

// Bantuan sebelum login
Route::get('/bantuan', function () {
    return view('guest.bantuan');
})->name('bantuan');


/*
|--------------------------------------------------------------------------
| HALAMAN SETELAH LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // MODUL BUYER
    Route::get('/dashboard', [ShopController::class, 'catalog'])->name('dashboard');
    Route::post('/cart/add/{id}', [ShopController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [ShopController::class, 'viewCart'])->name('cart.view');
    Route::post('/checkout', [ShopController::class, 'checkout'])->name('checkout');
    Route::get('/orders', [ShopController::class, 'orderHistory'])->name('orders.history');
    Route::patch('/cart/{id}/update', [ShopController::class, 'updateCart'])->name('cart.update');
     Route::delete('/cart/{id}/remove', [ShopController::class, 'removeFromCart'])->name('cart.remove');

    // MODUL ADMIN
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/product/store', [AdminController::class, 'storeProduct'])->name('product.store');
        Route::post('/product/update-stock/{id}', [AdminController::class, 'updateStock'])->name('product.stock');
        Route::post('/order/update-status/{id}', [AdminController::class, 'updateStatus'])->name('order.status');
        Route::put('/product/update/{id}', [AdminController::class, 'updateProduct'])->name('product.update');
        Route::delete('/product/delete/{id}', [AdminController::class, 'deleteProduct'])->name('product.delete');
        Route::get('/reports', [AdminController::class, 'salesReport'])->name('reports');
        Route::get('/reports/export', [AdminController::class, 'exportSalesReport'])->name('reports.export');
    });

    // PROFILE BAWAAN BREEZE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile/addresses', [AddressController::class, 'store'])->name('profile.addresses.store');
    Route::patch('/profile/addresses/{address}', [AddressController::class, 'update'])->name('profile.addresses.update');
    Route::delete('/profile/addresses/{address}', [AddressController::class, 'destroy'])->name('profile.addresses.destroy');
    Route::patch('/profile/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('profile.addresses.default');
});

require __DIR__.'/auth.php';
