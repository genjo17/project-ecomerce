<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SABISHOP - Platform Belanja Online Terpercaya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800">

    @php
        $categories = [
            [
                'name' => 'Fashion',
                'icon' => '👕',
                'description' => 'Pakaian dan produk gaya hidup.',
            ],
            [
                'name' => 'Aksesoris',
                'icon' => '💍',
                'description' => 'Perhiasan dan pelengkap gaya.',
            ],
            [
                'name' => 'Elektronik',
                'icon' => '🔌',
                'description' => 'Produk elektronik pilihan.',
            ],
            [
                'name' => 'Peralatan Rumah',
                'icon' => '🏠',
                'description' => 'Kebutuhan rumah tangga.',
            ],
            [
                'name' => 'Sembako',
                'icon' => '🍞',
                'description' => 'Kebutuhan pokok harian.',
            ],
            [
                'name' => 'Makanan & Minuman',
                'icon' => '🥤',
                'description' => 'Camilan dan minuman favorit.',
            ],
            [
                'name' => 'Kecantikan',
                'icon' => '💄',
                'description' => 'Produk perawatan dan kecantikan.',
            ],
            [
                'name' => 'Lainnya',
                'icon' => '📦',
                'description' => 'Produk lainnya yang tersedia.',
            ],
        ];
    @endphp

    <!-- NAVBAR -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <a href="{{ route('beranda') }}" class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-orange-500 flex items-center justify-center text-white text-xl shadow-sm">
                    🛒
                </div>

                <div>
                    <h1 class="text-xl font-extrabold text-gray-900 tracking-wide">
                        SABISHOP
                    </h1>
                    <p class="text-xs text-gray-500">
                        Platform belanja online terpercaya
                    </p>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-gray-600">
                <a href="{{ route('beranda') }}" class="text-orange-600">
                    Beranda
                </a>

                <a href="{{ route('produk.public') }}" class="hover:text-orange-600 transition">
                    Produk
                </a>

                <a href="{{ route('bantuan') }}" class="hover:text-orange-600 transition">
                    Bantuan
                </a>
            </nav>

            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-orange-600 transition">
                    Login
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-sm transition">
                        Daftar
                    </a>
                @endif
            </div>

        </div>
    </header>

    <!-- HERO -->
    <section class="relative overflow-hidden bg-white">
        <div class="max-w-7xl mx-auto px-6 py-16 lg:py-20 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            <!-- HERO TEXT -->
            <div>
                <span class="inline-flex items-center bg-orange-100 text-orange-700 text-xs font-extrabold px-4 py-2 rounded-full uppercase tracking-wider mb-6">
                    Belanja Online Mudah
                </span>

                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-5">
                    Temukan produk terbaik untuk kebutuhan Anda
                </h2>

                <p class="text-gray-600 text-base md:text-lg leading-relaxed mb-8 max-w-xl">
                    SABISHOP membantu pelanggan melihat produk, memilih kategori, memantau stok,
                    dan melakukan pembelian dengan lebih mudah, cepat, dan aman.
                </p>

                <!-- SEARCH -->
                <form action="{{ route('produk.public') }}" method="GET" class="bg-white border border-gray-200 rounded-2xl p-2 shadow-lg max-w-2xl grid grid-cols-1 sm:grid-cols-12 gap-2">
    
    <div class="relative sm:col-span-6">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
            🔍
        </span>

        <input 
            type="text"
            name="search"
            placeholder="Cari produk yang kamu butuhkan..."
            class="w-full pl-11 pr-4 py-3 rounded-xl border-none bg-gray-50 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-orange-200 outline-none"
        >
    </div>

    <div class="sm:col-span-4">
    <select 
        name="category"
        class="w-full py-3 pl-4 pr-12 rounded-xl border-none bg-gray-50 text-sm font-semibold text-gray-700 focus:ring-2 focus:ring-orange-200 outline-none cursor-pointer"
    >
        <option value="">Semua Kategori</option>
        @foreach($categories as $cat)
            <option value="{{ $cat['name'] }}">
                {{ $cat['name'] }}
            </option>
        @endforeach
    </select>
</div>

    <button type="submit" class="sm:col-span-2 bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-3 rounded-xl transition">
        Cari
    </button>
</form>

                <!-- TRUST -->
                <div class="grid grid-cols-3 gap-4 mt-8 max-w-xl">
                    <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                        <p class="text-2xl mb-1">🔒</p>
                        <h3 class="font-bold text-sm text-gray-900">Aman</h3>
                        <p class="text-xs text-gray-500 mt-1">Akun terlindungi.</p>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                        <p class="text-2xl mb-1">⚡</p>
                        <h3 class="font-bold text-sm text-gray-900">Cepat</h3>
                        <p class="text-xs text-gray-500 mt-1">Proses praktis.</p>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                        <p class="text-2xl mb-1">✅</p>
                        <h3 class="font-bold text-sm text-gray-900">Terpercaya</h3>
                        <p class="text-xs text-gray-500 mt-1">Belanja nyaman.</p>
                    </div>
                </div>
            </div>

            <!-- HERO VISUAL -->
            <div class="relative">
                <div class="absolute -top-8 -right-8 w-40 h-40 bg-orange-100 rounded-full"></div>
                <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-amber-100 rounded-full"></div>

                <div class="relative bg-gradient-to-br from-orange-500 to-amber-500 rounded-[2rem] p-8 shadow-xl text-white overflow-hidden">
                    <div class="absolute right-8 top-8 text-9xl opacity-20 select-none">
                        🛍️
                    </div>

                    <div class="relative z-10">
                        <span class="inline-block bg-white/20 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider mb-6">
                            Katalog SABISHOP
                        </span>

                        <h3 class="text-3xl font-extrabold leading-tight mb-4">
                            Belanja lebih mudah dalam satu tempat
                        </h3>

                        <p class="text-orange-50 leading-relaxed mb-8">
                            Pilih kategori, cek produk terbaru, lalu login untuk membeli produk yang tersedia.
                        </p>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/15 rounded-2xl p-5">
                                <p class="text-3xl mb-2">📦</p>
                                <h4 class="font-bold">Produk Lengkap</h4>
                                <p class="text-sm text-orange-50 mt-1">Banyak pilihan.</p>
                            </div>

                            <div class="bg-white/15 rounded-2xl p-5">
                                <p class="text-3xl mb-2">🏷️</p>
                                <h4 class="font-bold">Kategori Jelas</h4>
                                <p class="text-sm text-orange-50 mt-1">Mudah dicari.</p>
                            </div>

                            <div class="bg-white/15 rounded-2xl p-5">
                                <p class="text-3xl mb-2">💳</p>
                                <h4 class="font-bold">Mudah Dibayar</h4>
                                <p class="text-sm text-orange-50 mt-1">Transaksi praktis.</p>
                            </div>

                            <div class="bg-white/15 rounded-2xl p-5">
                                <p class="text-3xl mb-2">⭐</p>
                                <h4 class="font-bold">Produk Pilihan</h4>
                                <p class="text-sm text-orange-50 mt-1">Kualitas terbaik.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- KATEGORI -->
    <section class="py-14">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">
                        Kategori Produk
                    </h2>
                    <p class="text-gray-500 mt-2">
                        Pilih kategori sesuai kebutuhan belanja Anda.
                    </p>
                </div>

                <a href="{{ route('produk.public') }}" class="hidden md:inline-block text-sm font-bold text-orange-600 hover:text-orange-700">
                    Lihat semua produk →
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                @foreach($categories as $cat)
                    <a href="{{ route('produk.public', ['category' => $cat['name']]) }}"
                       class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition group">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center text-3xl mb-4 group-hover:bg-orange-100 transition">
                            {{ $cat['icon'] }}
                        </div>

                        <h3 class="font-extrabold text-gray-900">
                            {{ $cat['name'] }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-2">
                            {{ $cat['description'] }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- PRODUK UNGGULAN -->
    <section class="py-14 bg-white">
        <div class="max-w-7xl mx-auto px-6">

            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">
                        Produk Unggulan
                    </h2>
                    <p class="text-gray-500 mt-2">
                        Beberapa produk terbaru yang tersedia di SABISHOP.
                    </p>
                </div>

                <a href="{{ route('produk.public') }}" class="hidden md:inline-block text-sm font-bold text-orange-600 hover:text-orange-700">
                    Lihat katalog →
                </a>
            </div>

            @if(isset($featuredProducts) && $featuredProducts->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredProducts as $product)
                        @php
                            $imageSrc = 'https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=1200&auto=format&fit=crop';

                            if (!empty($product->image_url)) {
                                $imageSrc = str_starts_with($product->image_url, 'http')
                                    ? $product->image_url
                                    : asset($product->image_url);
                            }
                        @endphp

                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-xl hover:-translate-y-1 transition duration-300">

                            <div class="relative bg-gray-100 overflow-hidden">
                                <img 
                                    src="{{ $imageSrc }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-48 object-cover hover:scale-105 transition duration-500"
                                >

                                <span class="absolute top-3 left-3 bg-white/90 text-orange-700 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                    {{ $product->category ?? 'Lainnya' }}
                                </span>

                                @if($product->stock <= 5 && $product->stock > 0)
                                    <span class="absolute top-3 right-3 bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                        Stok Menipis
                                    </span>
                                @endif

                                @if($product->stock < 1)
                                    <span class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                        Stok Habis
                                    </span>
                                @endif
                            </div>

                            <div class="p-5">
                                <h3 class="font-extrabold text-gray-900 text-lg line-clamp-2">
                                    {{ $product->name }}
                                </h3>

                                <span class="inline-flex mt-2 bg-orange-50 text-orange-700 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $product->category ?? 'Belum ada kategori' }}
                                </span>

                                <p class="text-gray-500 text-sm mt-2 min-h-[42px] line-clamp-2">
                                    {{ $product->description ?? 'Tidak ada deskripsi produk.' }}
                                </p>

                                <div class="flex items-end justify-between mt-5">
                                    <div>
                                        <p class="text-xs text-gray-400">Harga</p>
                                        <p class="text-xl font-extrabold text-orange-600">
                                            Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-xs text-gray-400">Stok</p>
                                        <p class="text-sm font-bold text-gray-700">
                                            {{ $product->stock }}
                                        </p>
                                    </div>
                                </div>

                                @if($product->stock < 1)
                                    <button disabled class="block w-full text-center mt-5 bg-gray-300 text-white font-bold py-3 rounded-2xl cursor-not-allowed">
                                        Stok Habis
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="block text-center mt-5 bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-2xl transition">
                                        Login untuk Membeli
                                    </a>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 border border-gray-100 rounded-3xl py-16 text-center">
                    <div class="text-5xl mb-4">🛒</div>
                    <h3 class="text-lg font-extrabold text-gray-900">
                        Produk belum tersedia
                    </h3>
                    <p class="text-gray-500 mt-2">
                        Produk akan tampil di halaman ini setelah admin menambahkannya.
                    </p>
                </div>
            @endif

        </div>
    </section>

    <!-- CARA BELANJA -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center max-w-2xl mx-auto mb-10">
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">
                    Cara Belanja di SABISHOP
                </h2>
                <p class="text-gray-500 mt-2">
                    Belanja dibuat sederhana agar pelanggan mudah menemukan dan membeli produk.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm text-center">
                    <div class="w-14 h-14 mx-auto bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center text-2xl mb-4">
                        1
                    </div>
                    <h3 class="font-extrabold text-gray-900">Lihat Produk</h3>
                    <p class="text-sm text-gray-500 mt-2">Cari produk sesuai kebutuhan.</p>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm text-center">
                    <div class="w-14 h-14 mx-auto bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center text-2xl mb-4">
                        2
                    </div>
                    <h3 class="font-extrabold text-gray-900">Login Akun</h3>
                    <p class="text-sm text-gray-500 mt-2">Masuk agar bisa menambahkan produk.</p>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm text-center">
                    <div class="w-14 h-14 mx-auto bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center text-2xl mb-4">
                        3
                    </div>
                    <h3 class="font-extrabold text-gray-900">Masuk Keranjang</h3>
                    <p class="text-sm text-gray-500 mt-2">Tambahkan produk ke keranjang.</p>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm text-center">
                    <div class="w-14 h-14 mx-auto bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center text-2xl mb-4">
                        4
                    </div>
                    <h3 class="font-extrabold text-gray-900">Checkout</h3>
                    <p class="text-sm text-gray-500 mt-2">Selesaikan pesanan dengan mudah.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="bg-gradient-to-br from-orange-500 to-amber-500 rounded-[2rem] p-10 md:p-14 text-white text-center relative overflow-hidden">
                <div class="absolute -top-16 -right-16 w-52 h-52 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-16 -left-16 w-52 h-52 bg-white/10 rounded-full"></div>

                <div class="relative z-10 max-w-2xl mx-auto">
                    <h2 class="text-3xl md:text-4xl font-extrabold mb-4">
                        Siap mulai belanja hari ini?
                    </h2>

                    <p class="text-orange-50 mb-8">
                        Masuk ke akun Anda atau daftar akun baru untuk menikmati pengalaman belanja yang lebih mudah.
                    </p>

                    <div class="flex flex-col sm:flex-row justify-center gap-3">
                        <a href="{{ route('login') }}" class="bg-white text-orange-600 hover:bg-orange-50 font-extrabold px-7 py-3 rounded-xl transition">
                            Login Sekarang
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-orange-700/30 hover:bg-orange-700/40 text-white font-extrabold px-7 py-3 rounded-xl border border-white/30 transition">
                                Daftar Akun
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-8">

            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-orange-500 flex items-center justify-center text-white text-xl">
                        🛒
                    </div>
                    <div>
                        <h3 class="font-extrabold text-lg">SABISHOP</h3>
                        <p class="text-xs text-gray-400">Platform belanja online terpercaya</p>
                    </div>
                </div>

                <p class="text-sm text-gray-400 leading-relaxed">
                    SABISHOP menyediakan pengalaman belanja online yang sederhana, aman, dan mudah digunakan.
                </p>
            </div>

            <div>
                <h4 class="font-extrabold mb-4">Menu</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('beranda') }}" class="hover:text-orange-400">Beranda</a></li>
                    <li><a href="{{ route('produk.public') }}" class="hover:text-orange-400">Produk</a></li>
                    <li><a href="{{ route('bantuan') }}" class="hover:text-orange-400">Bantuan</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-orange-400">Login</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-extrabold mb-4">Kategori Produk</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('produk.public', ['category' => $cat['name']]) }}" class="hover:text-orange-400">
                                {{ $cat['icon'] }} {{ $cat['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>

        <div class="border-t border-white/10 py-5 text-center text-sm text-gray-400">
            &copy; {{ date('Y') }} genjo. All Rights Reserved.
        </div>
    </footer>

</body>
</html>