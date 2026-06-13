<x-app-layout>
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
                        <div class="border-b border-slate-200 bg-blue-950 p-6 text-white">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-blue-100">
                                Selamat Datang
                            </p>
                            <h3 class="mt-2 text-xl font-extrabold">
                                Katalog Belanja
                            </h3>
                            <p class="mt-2 text-sm text-blue-100">
                                Pilih produk terbaik untuk kebutuhan Anda.
                            </p>
                        </div>

                        <!-- MENU -->
                        <div class="p-5">
                            <h3 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-3">
                                Main Menu
                            </h3>

                            <nav class="space-y-2">
                                <a href="{{ route('dashboard') }}"
                                   class="flex items-center gap-3 rounded-xl {{ empty($activeCategory) ? 'bg-blue-950 text-white' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-950' }} px-4 py-3 text-sm font-bold transition">
                                    <span class="text-lg">🏠</span>
                                    <span>Katalog Produk</span>
                                </a>

                                <a href="{{ route('cart.view') }}"
                                   class="flex items-center gap-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-950 px-4 py-3 text-sm font-semibold transition">
                                    <span class="text-lg">🛒</span>
                                    <span>Keranjang</span>
                                </a>

                                <a href="{{ route('orders.history') }}"
                                   class="flex items-center gap-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-950 px-4 py-3 text-sm font-semibold transition">
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
                                       class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ empty($activeCategory) ? 'bg-blue-950 text-white' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-950' }}">
                                        <span>🛍️</span>
                                        <span>Semua Kategori</span>
                                    </a>
                                </li>

                                @foreach($categories as $cat)
                                    <li>
                                        <a href="{{ route('dashboard', ['category' => $cat, 'search' => $search ?? null]) }}"
                                           class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ $activeCategory == $cat ? 'bg-blue-950 text-white' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-950' }}">
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
                    <section class="panel overflow-hidden">
                        <div class="grid gap-0 lg:grid-cols-[1.1fr_0.9fr]">
                            <div class="p-8">
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.22em] text-blue-900">
                                    Promo Minggu Ini
                                </span>

                                <h1 class="mt-4 max-w-2xl text-3xl font-black leading-tight text-slate-950 md:text-4xl">
                                    Belanja kebutuhan harian jadi lebih mudah
                                </h1>

                                <p class="mt-3 max-w-xl text-sm leading-7 text-slate-500 md:text-base">
                                    Temukan produk pilihan dengan harga terbaik, stok tersedia,
                                    dan proses belanja yang lebih praktis.
                                </p>

                                <div class="mt-6 grid grid-cols-3 gap-3">
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                        <p class="text-sm font-extrabold text-slate-950">Lengkap</p>
                                        <p class="mt-1 text-xs text-slate-500">Banyak pilihan produk.</p>
                                    </div>

                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                        <p class="text-sm font-extrabold text-slate-950">Cepat</p>
                                        <p class="mt-1 text-xs text-slate-500">Cari dan checkout mudah.</p>
                                    </div>

                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                        <p class="text-sm font-extrabold text-slate-950">Aman</p>
                                        <p class="mt-1 text-xs text-slate-500">Belanja lebih nyaman.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-100">
                                <img
                                    src="https://images.unsplash.com/photo-1556740749-887f6717d7e4?q=80&w=1200&auto=format&fit=crop"
                                    alt="Belanja online"
                                    class="h-full min-h-[280px] w-full object-cover"
                                >
                            </div>
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
                                    class="w-full rounded-2xl border border-gray-200 bg-gray-50 pl-11 pr-4 py-3 text-sm text-gray-700 placeholder-gray-400 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition"
                                >
                            </div>

                            <select 
                                name="category"
                                class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition"
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
                                class="btn-primary px-7 py-3">
                                Cari
                            </button>

                            @if(!empty($search) || !empty($activeCategory))
                                <a href="{{ route('dashboard') }}"
                                   class="btn-secondary px-6 py-3 text-sm text-center">
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

                                        <span class="absolute top-3 left-3 rounded-full bg-white/90 text-blue-700 px-3 py-1 text-[11px] font-bold shadow-sm">
                                            {{ $product->category ?? 'Lainnya' }}
                                        </span>

                                        @if($product->stock <= 5 && $product->stock > 0)
                                            <span class="absolute top-3 right-3 rounded-full bg-sky-500 px-3 py-1 text-[11px] font-bold text-white shadow-sm">
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
                                            <h3 class="font-extrabold text-gray-900 text-base hover:text-blue-600 transition">
                                                {{ $product->name }}
                                            </h3>

                                            <span class="inline-flex mt-2 bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">
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
                                                    <p class="text-xl font-extrabold text-blue-600">
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
                class="w-24 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-center text-sm font-bold text-gray-700 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none"
            >
        </div>

        <button 
            type="submit"
            class="btn-primary w-full px-4 py-3 text-sm">
            🛒 Tambah ke Keranjang
        </button>
    @endif
</form>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $products->links() }}
                        </div>
                    @endif

                </main>

            </div>
        </div>
    </div>
</x-app-layout>
