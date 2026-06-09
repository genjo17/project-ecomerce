<x-app-layout>
    <x-slot name="header">
        <div class="bg-white border border-gray-100 rounded-2xl px-6 py-5 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-orange-500 flex items-center justify-center text-white text-2xl shadow-sm">
                        🛍️
                    </div>

                    <div>
                        <h2 class="font-extrabold text-2xl text-gray-900 tracking-wide">
                            SABISHOP
                        </h2>
                        <p class="text-sm text-gray-500">
                            Belanja mudah, cepat, dan terpercaya
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                     @if(auth()->user()->role === 'admin')
                     <a href="{{ route('admin.dashboard') }}"
                     class="inline-flex items-center justify-center rounded-xl bg-orange-500 hover:bg-orange-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition">
                     🛠️ Panel Admin
                </a>
                     @endif
                </div>

            </div>
        </div>
    </x-slot>

    @php
        $categories = $categories ?? [
            'Fashion',
            'Aksesoris',
            'Elektronik',
            'Peralatan Rumah',
            'Sembako',
            'Makanan & Minuman',
            'Kecantikan',
            'Lainnya',
        ];

        $categoryIcons = [
            'Fashion' => '👕',
            'Aksesoris' => '💍',
            'Elektronik' => '🔌',
            'Peralatan Rumah' => '🏠',
            'Sembako' => '🍞',
            'Makanan & Minuman' => '🥤',
            'Kecantikan' => '💄',
            'Lainnya' => '📦',
        ];

        $activeCategory = $category ?? request('category');
    @endphp

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">

                <!-- SIDEBAR -->
                <aside class="lg:col-span-1">
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden sticky top-6">
                        
                        <div class="bg-gradient-to-br from-orange-500 to-amber-500 p-6 text-white relative overflow-hidden">
                            <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-white/15 rounded-full"></div>
                            <div class="absolute right-8 top-8 text-6xl opacity-20 select-none">
                                🛒
                            </div>

                            <div class="relative z-10">
                                <p class="text-xs font-semibold uppercase tracking-wider text-orange-50 mb-2">
                                    Selamat Datang
                                </p>
                                <h3 class="text-xl font-extrabold">
                                    Katalog Belanja
                                </h3>
                                <p class="text-sm text-orange-50 mt-1">
                                    Pilih produk terbaik untuk kebutuhan Anda.
                                </p>
                            </div>
                        </div>

                        <!-- MENU -->
                        <div class="p-5">
                            <h3 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-3">
                                Main Menu
                            </h3>

                            <nav class="space-y-2">
                                <a href="{{ route('dashboard') }}"
                                   class="flex items-center gap-3 rounded-xl {{ empty($activeCategory) ? 'bg-orange-50 text-orange-700 border border-orange-100' : 'text-gray-600 hover:bg-orange-50 hover:text-orange-700' }} px-4 py-3 text-sm font-bold transition">
                                    <span class="text-lg">🏠</span>
                                    <span>Katalog Produk</span>
                                </a>

                                <a href="{{ route('cart.view') }}"
                                   class="flex items-center gap-3 rounded-xl text-gray-600 hover:bg-orange-50 hover:text-orange-700 px-4 py-3 text-sm font-semibold transition">
                                    <span class="text-lg">🛒</span>
                                    <span>Keranjang</span>
                                </a>

                                <a href="{{ route('orders.history') }}"
                                   class="flex items-center gap-3 rounded-xl text-gray-600 hover:bg-orange-50 hover:text-orange-700 px-4 py-3 text-sm font-semibold transition">
                                    <span class="text-lg">📦</span>
                                    <span>Pesanan Saya</span>
                                </a>
                            </nav>
                        </div>

                        <!-- KATEGORI -->
                        <div class="px-5 pb-5">
                            <h3 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-3">
                                Kategori Produk
                            </h3>

                            <ul class="space-y-2">
                                <li>
                                    <a href="{{ route('dashboard') }}"
                                       class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ empty($activeCategory) ? 'bg-orange-50 text-orange-700' : 'text-gray-600 hover:bg-gray-50 hover:text-orange-600' }}">
                                        <span>🛍️</span>
                                        <span>Semua Kategori</span>
                                    </a>
                                </li>

                                @foreach($categories as $cat)
                                    <li>
                                        <a href="{{ route('dashboard', ['category' => $cat, 'search' => $search ?? null]) }}"
                                           class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ $activeCategory == $cat ? 'bg-orange-50 text-orange-700' : 'text-gray-600 hover:bg-gray-50 hover:text-orange-600' }}">
                                            <span>{{ $categoryIcons[$cat] ?? '📦' }}</span>
                                            <span>{{ $cat }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="border-t border-gray-100 px-5 py-4 bg-gray-50">
                            <div class="grid grid-cols-3 gap-2 text-center">
                                <div>
                                    <p class="text-lg">🔒</p>
                                    <p class="text-[11px] text-gray-500">Aman</p>
                                </div>
                                <div>
                                    <p class="text-lg">⚡</p>
                                    <p class="text-[11px] text-gray-500">Cepat</p>
                                </div>
                                <div>
                                    <p class="text-lg">✅</p>
                                    <p class="text-[11px] text-gray-500">Terpercaya</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </aside>

                <!-- CONTENT -->
                <main class="lg:col-span-3 space-y-6">

                    <!-- HERO -->
                    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-orange-500 via-orange-500 to-amber-500 p-8 text-white shadow-sm">
                        <div class="absolute -right-12 -top-12 w-56 h-56 rounded-full bg-white/10"></div>
                        <div class="absolute right-8 bottom-0 text-9xl opacity-20 select-none">
                            🛍️
                        </div>

                        <div class="relative z-10 max-w-2xl">
                            <span class="inline-flex items-center rounded-full bg-white/20 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-white">
                                Promo Minggu Ini
                            </span>

                            <h1 class="mt-4 text-3xl md:text-4xl font-extrabold leading-tight">
                                Belanja kebutuhan harian jadi lebih mudah
                            </h1>

                            <p class="mt-3 text-sm md:text-base text-orange-50 max-w-xl">
                                Temukan produk pilihan dengan harga terbaik, stok tersedia,
                                dan proses belanja yang lebih praktis.
                            </p>
                        </div>
                    </section>

                    <!-- SEARCH + FILTER -->
                    <section class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5">
                        <form action="{{ route('dashboard') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                            <div class="relative flex-grow">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                    🔍
                                </span>

                                <input 
                                    type="text"
                                    name="search"
                                    value="{{ $search ?? '' }}"
                                    placeholder="Cari nama produk di sini..."
                                    class="w-full rounded-2xl border border-gray-200 bg-gray-50 pl-11 pr-4 py-3 text-sm text-gray-700 placeholder-gray-400 outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition"
                                >
                            </div>

                            <select 
                                name="category"
                                class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition"
                            >
                                <option value="">Semua Kategori</option>

                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ $activeCategory == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>

                            <button 
                                type="submit"
                                class="rounded-2xl bg-orange-500 hover:bg-orange-600 px-7 py-3 text-sm font-bold text-white shadow-sm transition">
                                Cari
                            </button>

                            @if(!empty($search) || !empty($activeCategory))
                                <a href="{{ route('dashboard') }}"
                                   class="rounded-2xl bg-gray-100 hover:bg-gray-200 px-6 py-3 text-sm font-bold text-gray-600 transition text-center">
                                    Reset
                                </a>
                            @endif
                        </form>

                        @if(!empty($search) || !empty($activeCategory))
                            <div class="text-sm text-gray-500 mt-4 space-y-1">
                                @if(!empty($search))
                                    <p>
                                        Menampilkan hasil pencarian untuk:
                                        <strong class="text-gray-800">"{{ $search }}"</strong>
                                    </p>
                                @endif

                                @if(!empty($activeCategory))
                                    <p>
                                        Kategori:
                                        <strong class="text-gray-800">{{ $activeCategory }}</strong>
                                    </p>
                                @endif
                            </div>
                        @endif
                    </section>

                    <!-- TITLE -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-extrabold text-gray-900">
                                {{ !empty($activeCategory) ? 'Produk Kategori ' . $activeCategory : 'Produk Unggulan' }}
                            </h2>
                            <p class="text-sm text-gray-500 mt-1">
                                Pilihan produk terbaik yang tersedia di toko.
                            </p>
                        </div>
                    </div>

                    <!-- PRODUCTS -->
                    @if($products->isEmpty())
                        <div class="bg-white text-center py-16 rounded-3xl border border-gray-100 shadow-sm">
                            <div class="text-5xl mb-4">🛒</div>
                            <h3 class="text-lg font-bold text-gray-800">
                                Produk belum tersedia
                            </h3>
                            <p class="text-gray-500 text-sm mt-2">
                                Belum ada produk yang dijual atau produk tidak ditemukan.
                            </p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                @php
                                    $imageSrc = 'https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=1200&auto=format&fit=crop';

                                    if (!empty($product->image_url)) {
                                        $imageSrc = str_starts_with($product->image_url, 'http')
                                            ? $product->image_url
                                            : asset($product->image_url);
                                    }
                                @endphp

                                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col">

                                    <!-- IMAGE -->
                                    <div class="relative bg-gray-100 overflow-hidden">
                                        <img 
                                            src="{{ $imageSrc }}"
                                            alt="{{ $product->name }}"
                                            class="w-full h-48 object-cover hover:scale-105 transition duration-500"
                                        >

                                        <span class="absolute top-3 left-3 rounded-full bg-white/90 text-orange-700 px-3 py-1 text-[11px] font-bold shadow-sm">
                                            {{ $product->category ?? 'Lainnya' }}
                                        </span>

                                        @if($product->stock <= 5 && $product->stock > 0)
                                            <span class="absolute top-3 right-3 rounded-full bg-amber-500 px-3 py-1 text-[11px] font-bold text-white shadow-sm">
                                                Stok Menipis
                                            </span>
                                        @endif

                                        @if($product->stock < 1)
                                            <span class="absolute top-3 right-3 rounded-full bg-red-500 px-3 py-1 text-[11px] font-bold text-white shadow-sm">
                                                Stok Habis
                                            </span>
                                        @endif
                                    </div>

                                    <!-- DETAIL -->
                                    <div class="p-5 flex flex-col flex-grow">
                                        <div class="flex-grow">
                                            <h3 class="font-extrabold text-gray-900 text-base hover:text-orange-600 transition">
                                                {{ $product->name }}
                                            </h3>

                                            <span class="inline-flex mt-2 bg-orange-50 text-orange-700 text-xs font-bold px-3 py-1 rounded-full">
                                                {{ $product->category ?? 'Belum ada kategori' }}
                                            </span>

                                            <p class="text-gray-500 text-sm mt-2 leading-relaxed min-h-[44px]">
                                                {{ $product->description ?? 'Tidak ada deskripsi produk.' }}
                                            </p>
                                        </div>

                                        <div class="mt-5">
                                            <div class="flex items-end justify-between gap-3 mb-4">
                                                <div>
                                                    <p class="text-xs text-gray-400 mb-1">
                                                        Harga
                                                    </p>
                                                    <p class="text-xl font-extrabold text-orange-600">
                                                        Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                                                    </p>
                                                </div>

                                                <div class="text-right">
                                                    <p class="text-xs text-gray-400 mb-1">
                                                        Stok
                                                    </p>
                                                    <p class="text-sm font-bold text-gray-700">
                                                        {{ $product->stock }}
                                                    </p>
                                                </div>
                                            </div>

          <form action="{{ route('cart.add', $product->id) }}" method="POST">
    @csrf

    @if($product->stock < 1)
        <button 
            type="button"
            disabled
            class="w-full rounded-2xl px-4 py-3 text-sm font-extrabold text-white shadow-sm bg-gray-300 cursor-not-allowed"
        >
            Stok Habis
        </button>
    @else
        <div class="flex items-center justify-between gap-3 mb-3">
            <label 
                for="quantity-{{ $product->id }}" 
                class="text-sm font-bold text-gray-700"
            >
                Jumlah
            </label>

            <input
                type="number"
                id="quantity-{{ $product->id }}"
                name="quantity"
                value="1"
                min="1"
                max="{{ $product->stock }}"
                class="w-24 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-center text-sm font-bold text-gray-700 focus:border-orange-400 focus:ring-2 focus:ring-orange-200 outline-none"
            >
        </div>

        <button 
            type="submit"
            class="w-full rounded-2xl px-4 py-3 text-sm font-extrabold text-white shadow-sm transition bg-orange-500 hover:bg-orange-600"
        >
            🛒 Tambah ke Keranjang
        </button>
    @endif
</form>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endif

                </main>

            </div>
        </div>
    </div>
</x-app-layout>