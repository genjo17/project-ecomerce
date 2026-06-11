<x-app-layout>
    <x-slot name="header">
        <div class="bg-white border border-gray-100 rounded-2xl px-6 py-5 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-sm font-bold text-blue-600 uppercase tracking-wider">
                        Panel Admin
                    </p>
                    <h2 class="font-extrabold text-2xl text-gray-900 leading-tight">
                        Kelola Toko SABISHOP
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Atur produk, kategori, stok, pesanan, dan status pengiriman toko.
                    </p>
                </div>
                <a href="{{ route('admin.reports') }}"
                   class="btn-primary inline-flex items-center justify-center px-5 py-3 text-sm">
                   📊 Laporan Penjualan
                </a>

                <a href="{{ route('dashboard') }}"
                   class="btn-secondary inline-flex items-center justify-center px-5 py-3 text-sm">
                    ⬅️ Keluar ke Toko Utama
                </a>
            </div>
        </div>
    </x-slot>

    @php
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

        $totalProduk = $products->count();
        $stokMenipis = $products->where('stock', '<=', 5)->where('stock', '>', 0)->count();
        $stokHabis = $products->where('stock', '<=', 0)->count();
        $totalPesanan = $orders->count();
        $totalOmzet = $orders->sum('total_price');
    @endphp

    <div class="min-h-screen bg-gray-50 py-8">

        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl font-semibold">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                    <p class="font-bold mb-2">Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- RINGKASAN ADMIN -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl mb-4">
                        📦
                    </div>
                    <p class="text-sm text-gray-500">Total Produk</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 mt-1">
                        {{ $totalProduk }}
                    </h3>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <div class="w-12 h-12 rounded-2xl bg-yellow-100 text-yellow-600 flex items-center justify-center text-2xl mb-4">
                        ⚠️
                    </div>
                    <p class="text-sm text-gray-500">Stok Menipis</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 mt-1">
                        {{ $stokMenipis }}
                    </h3>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-2xl mb-4">
                        ❌
                    </div>
                    <p class="text-sm text-gray-500">Stok Habis</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 mt-1">
                        {{ $stokHabis }}
                    </h3>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl mb-4">
                        🧾
                    </div>
                    <p class="text-sm text-gray-500">Total Pesanan</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 mt-1">
                        {{ $totalPesanan }}
                    </h3>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <div class="w-12 h-12 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-4">
                        💰
                    </div>
                    <p class="text-sm text-gray-500">Total Omzet</p>
                    <h3 class="text-xl font-extrabold text-gray-900 mt-1">
                        Rp {{ number_format((float) $totalOmzet, 0, ',', '.') }}
                    </h3>
                </div>

            </div>

            <!-- MAIN CONTENT -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <!-- FORM TAMBAH PRODUK -->
                <section class="lg:col-span-1">
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden sticky top-6">
                        <div class="border-b border-slate-200 bg-blue-950 p-6 text-white">
                            <p class="text-xs font-bold uppercase tracking-[0.22em] text-blue-100">
                                Produk Baru
                            </p>
                            <h3 class="mt-2 text-2xl font-extrabold">
                                Tambah Produk
                            </h3>
                            <p class="mt-2 text-sm text-blue-100">
                                Masukkan data produk agar tampil di katalog toko.
                            </p>
                        </div>

                        <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                            @csrf

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Produk
                                </label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required
                                       placeholder="Contoh: Kaos Polos"
                                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Deskripsi
                                </label>
                                <textarea name="description"
                                          rows="3"
                                          placeholder="Tulis deskripsi singkat produk"
                                          class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">{{ old('description') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kategori Produk
                                </label>

                                <select name="category"
                                        required
                                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Harga Produk
                                </label>
                                <input type="number"
                                       name="price"
                                       value="{{ old('price') }}"
                                       required
                                       min="0"
                                       placeholder="Contoh: 150000"
                                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Stok Awal
                                </label>
                                <input type="number"
                                       name="stock"
                                       value="{{ old('stock') }}"
                                       required
                                       min="0"
                                       placeholder="Contoh: 25"
                                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Gambar Produk
                                </label>

                                <input type="file"
                                       name="image"
                                       accept="image/png, image/jpeg, image/jpg, image/webp"
                                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                                <p class="text-xs text-gray-400 mt-2">
                                    Format gambar: JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                                </p>
                            </div>

                            <button type="submit"
                                    class="btn-primary w-full py-3">
                                Simpan Produk
                            </button>
                        </form>
                    </div>
                </section>

                <!-- KONTEN KANAN -->
                <section class="lg:col-span-2 space-y-8">

                    <!-- KELOLA PRODUK -->
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

                        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div>
                                <h3 class="text-xl font-extrabold text-gray-900">
                                    Kelola Produk
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Update stok, edit detail produk, atau hapus produk dari katalog.
                                </p>
                            </div>

                            <span class="inline-flex items-center bg-blue-50 text-blue-700 text-xs font-bold px-4 py-2 rounded-full">
                                {{ $totalProduk }} produk
                            </span>
                        </div>

                        <div class="divide-y divide-gray-100">
                            @forelse($products as $p)
                                @php
                                    $imageSrc = 'https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=500&auto=format&fit=crop';

                                    if (!empty($p->image_url)) {
                                        $imageSrc = str_starts_with($p->image_url, 'http')
                                            ? $p->image_url
                                            : asset($p->image_url);
                                    }
                                @endphp

                                <div class="p-5">

                                    <!-- BARIS UTAMA PRODUK -->
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-16 rounded-2xl bg-gray-100 overflow-hidden border border-gray-100 shrink-0">
                                                <img src="{{ $imageSrc }}"
                                                     alt="{{ $p->name }}"
                                                     class="w-full h-full object-cover">
                                            </div>

                                            <div>
                                                <h4 class="font-extrabold text-gray-900">
                                                    {{ $p->name }}
                                                </h4>

                                                <p class="text-sm text-gray-500">
                                                    Rp {{ number_format((float) $p->price, 0, ',', '.') }}
                                                </p>

                                                <span class="inline-flex mt-2 bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">
                                                    {{ $p->category ?? 'Belum ada kategori' }}
                                                </span>

                                                @if($p->stock < 1)
                                                    <span class="inline-flex mt-2 bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full">
                                                        Stok Habis
                                                    </span>
                                                @elseif($p->stock <= 5)
                                                    <span class="inline-flex mt-2 bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">
                                                        Stok Menipis
                                                    </span>
                                                @else
                                                    <span class="inline-flex mt-2 bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">
                                                        Stok Aman
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- UPDATE STOK CEPAT -->
                                        <div class="flex flex-col sm:flex-row gap-3 sm:items-center">

                                            <form action="{{ route('admin.product.stock', $p->id) }}" method="POST" class="flex items-center gap-3">
                                                @csrf

                                                <input type="number"
                                                       name="stock"
                                                       value="{{ $p->stock }}"
                                                       min="0"
                                                       class="w-24 rounded-xl border border-gray-300 px-3 py-2 text-center text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                                                <button type="submit"
                                                        class="bg-gray-900 hover:bg-gray-800 text-white px-5 py-2.5 text-sm font-bold rounded-xl transition">
                                                    Update Stok
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.product.delete', $p->id) }}" method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus produk ini? Produk yang dihapus tidak akan tampil lagi di katalog.');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 text-sm font-bold rounded-xl transition">
                                                    Hapus
                                                </button>
                                            </form>

                                        </div>
                                    </div>

                                    <!-- EDIT PRODUK -->
                                    <details class="mt-5 bg-gray-50 border border-gray-100 rounded-2xl p-5">
                                        <summary class="cursor-pointer font-extrabold text-blue-600">
                                            Edit Detail Produk
                                        </summary>

                                        <form action="{{ route('admin.product.update', $p->id) }}" method="POST" enctype="multipart/form-data" class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Nama Produk
                                                </label>
                                                <input type="text"
                                                       name="name"
                                                       value="{{ $p->name }}"
                                                       required
                                                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Kategori Produk
                                                </label>

                                                <select name="category"
                                                        required
                                                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                                    <option value="">-- Pilih Kategori --</option>
                                                    @foreach($categories as $cat)
                                                        <option value="{{ $cat }}" {{ ($p->category ?? '') == $cat ? 'selected' : '' }}>
                                                            {{ $cat }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Harga
                                                </label>
                                                <input type="number"
                                                       name="price"
                                                       value="{{ $p->price }}"
                                                       required
                                                       min="0"
                                                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Stok
                                                </label>
                                                <input type="number"
                                                       name="stock"
                                                       value="{{ $p->stock }}"
                                                       required
                                                       min="0"
                                                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                            </div>

                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Ganti Gambar Produk
                                                </label>

                                                @if(!empty($p->image_url))
                                                    <img src="{{ $imageSrc }}"
                                                         alt="{{ $p->name }}"
                                                         class="w-24 h-24 object-cover rounded-2xl border border-gray-200 mb-3">
                                                @endif

                                                <input type="file"
                                                       name="image"
                                                       accept="image/png, image/jpeg, image/jpg, image/webp"
                                                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                                                <p class="text-xs text-gray-400 mt-2">
                                                    Kosongkan jika tidak ingin mengganti gambar.
                                                </p>
                                            </div>

                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Deskripsi
                                                </label>
                                                <textarea name="description"
                                                          rows="3"
                                                          class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">{{ $p->description }}</textarea>
                                            </div>

                                            <div class="md:col-span-2 flex justify-end">
                                                <button type="submit"
                                                        class="btn-primary px-6 py-3 text-sm">
                                                    Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </details>

                                </div>
                            @empty
                                <div class="p-10 text-center">
                                    <div class="text-5xl mb-4">📦</div>
                                    <h4 class="font-extrabold text-gray-900">
                                        Belum ada produk
                                    </h4>
                                    <p class="text-sm text-gray-500 mt-2">
                                        Tambahkan produk pertama melalui form tambah produk.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- KELOLA PESANAN -->
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

                        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div>
                                <h3 class="text-xl font-extrabold text-gray-900">
                                    Kelola Pesanan & Tracking
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Pantau pesanan pelanggan dan ubah status pengiriman.
                                </p>
                            </div>

                            <span class="inline-flex items-center bg-blue-50 text-blue-700 text-xs font-bold px-4 py-2 rounded-full">
                                {{ $totalPesanan }} pesanan
                            </span>
                        </div>

                        <div class="p-6 space-y-4">
                            @forelse($orders as $o)
                                @php
                                    $statusClass = 'bg-gray-100 text-gray-700';

                                    if ($o->status == 'Pesanan diterima') {
                                        $statusClass = 'bg-blue-100 text-blue-700';
                                    } elseif ($o->status == 'Sedang diproses') {
                                        $statusClass = 'bg-yellow-100 text-yellow-700';
                                    } elseif ($o->status == 'Sedang dikirim') {
                                        $statusClass = 'bg-blue-100 text-blue-700';
                                    } elseif ($o->status == 'Sampai tujuan') {
                                        $statusClass = 'bg-green-100 text-green-700';
                                    }
                                @endphp

                                <div class="border border-gray-100 rounded-3xl p-5 bg-gray-50">
                                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-5">
                                        <div>
                                            <div class="flex items-center gap-2 mb-2">
                                                <h4 class="font-extrabold text-gray-900">
                                                    Order #{{ $o->id }}
                                                </h4>

                                                <span class="inline-flex {{ $statusClass }} text-xs font-bold px-3 py-1 rounded-full">
                                                    {{ $o->status }}
                                                </span>
                                            </div>

                                            <p class="text-sm text-gray-500">
                                                Pembeli:
                                                <span class="font-bold text-gray-700">
                                                    {{ $o->buyer_name }}
                                                </span>
                                            </p>
                                        </div>

                                        <div class="md:text-right">
                                            <p class="text-xs text-gray-400">
                                                Total Pembayaran
                                            </p>
                                            <p class="text-xl font-extrabold text-blue-600">
                                                Rp {{ number_format((float) $o->total_price, 0, ',', '.') }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                Ongkir Rp {{ number_format((float) ($o->shipping_cost ?? 0), 0, ',', '.') }}
                                                @if(($o->payment_fee ?? 0) > 0)
                                                    + biaya bayar Rp {{ number_format((float) $o->payment_fee, 0, ',', '.') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mb-5 grid gap-3 rounded-2xl bg-white p-4 text-sm md:grid-cols-3">
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Penerima</p>
                                            <p class="mt-1 font-bold text-gray-800">{{ $o->receiver_name ?? '-' }}</p>
                                            <p class="text-gray-500">{{ $o->phone ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Pengiriman</p>
                                            <p class="mt-1 font-bold text-gray-800">{{ $o->shipping_method ?? '-' }}</p>
                                            <p class="text-gray-500">{{ $o->payment_method ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Alamat</p>
                                            <p class="mt-1 line-clamp-2 text-gray-600">{{ $o->address ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <form action="{{ route('admin.order.status', $o->id) }}" method="POST" class="flex flex-col md:flex-row md:items-center gap-3">
                                        @csrf

                                        <label class="text-sm font-semibold text-gray-700">
                                            Ubah Status:
                                        </label>

                                        <select name="status"
                                                class="flex-grow rounded-xl border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                            <option value="Pesanan diterima" {{ $o->status == 'Pesanan diterima' ? 'selected' : '' }}>
                                                Pesanan diterima
                                            </option>
                                            <option value="Sedang diproses" {{ $o->status == 'Sedang diproses' ? 'selected' : '' }}>
                                                Sedang diproses
                                            </option>
                                            <option value="Sedang dikirim" {{ $o->status == 'Sedang dikirim' ? 'selected' : '' }}>
                                                Sedang dikirim
                                            </option>
                                            <option value="Sampai tujuan" {{ $o->status == 'Sampai tujuan' ? 'selected' : '' }}>
                                                Sampai tujuan
                                            </option>
                                        </select>

                                        <button type="submit"
                                                class="btn-primary px-5 py-2.5 text-sm">
                                            Update Status
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="py-12 text-center">
                                    <div class="text-5xl mb-4">🧾</div>
                                    <h4 class="font-extrabold text-gray-900">
                                        Belum ada pesanan
                                    </h4>
                                    <p class="text-sm text-gray-500 mt-2">
                                        Pesanan pelanggan akan tampil di bagian ini setelah checkout.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </section>

            </div>
        </div>
    </div>
</x-app-layout>
