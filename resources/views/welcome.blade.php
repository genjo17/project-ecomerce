<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SABISHOP - Platform Belanja Online Terpercaya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-800">
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

        $heroImage = 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1200&auto=format&fit=crop';
    @endphp

    <header class="sticky top-0 z-50 border-b border-slate-200 border-t-4 border-t-blue-950 bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('beranda') }}" class="flex items-center gap-3">
                <span class="brand-mark">SB</span>

                <span class="hidden sm:block">
                    <span class="block text-sm font-black tracking-wide text-slate-950">SABISHOP</span>
                    <span class="block text-xs font-medium text-slate-500">Platform belanja online terpercaya</span>
                </span>
            </a>

            <nav class="hidden items-center gap-8 text-sm font-semibold text-slate-600 md:flex">
                <a href="{{ route('beranda') }}" class="text-blue-950">Beranda</a>
                <a href="{{ route('produk.public') }}" class="transition hover:text-blue-900">Produk</a>
                <a href="{{ route('bantuan') }}" class="transition hover:text-blue-900">Bantuan</a>
            </nav>

            <div class="hidden items-center gap-3 md:flex">
                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 transition hover:text-blue-900">
                    Login
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary px-5 py-2.5 text-sm">
                        Daftar
                    </a>
                @endif
            </div>
        </div>
    </header>

    <main>
        <section class="border-b border-slate-200 bg-slate-50">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
                <div class="grid items-center gap-10 lg:grid-cols-2">
                    <div class="space-y-6">
                        <div class="space-y-3">
                            <h1 class="max-w-xl text-4xl font-black leading-tight tracking-tight text-slate-950 sm:text-5xl">
                                Temukan produk terbaik untuk kebutuhan Anda.
                            </h1>

                            <p class="max-w-xl text-base leading-7 text-slate-600">
                                SABISHOP membantu pelanggan melihat produk, memilih kategori, memantau stok,
                                dan melakukan pembelian cepat, aman, dan nyaman.
                            </p>
                        </div>

                        <form action="{{ route('produk.public') }}" method="GET" class="panel p-3">
                            <div class="grid gap-3 md:grid-cols-[1fr_220px_auto]">
                                <label class="relative block">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                        🔍
                                    </span>
                                    <input
                                        type="text"
                                        name="search"
                                        placeholder="Cari produk..."
                                        class="form-field w-full border-slate-200 bg-slate-50 py-3 pl-11 pr-4"
                                    >
                                </label>

                                <select name="category" class="form-field w-full border-slate-200 bg-slate-50 px-4 py-3">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat['name'] }}">
                                            {{ $cat['name'] }}
                                        </option>
                                    @endforeach
                                </select>

                                <button type="submit" class="btn-primary px-7 py-3">
                                    Cari
                                </button>
                            </div>
                        </form>

                        <div class="grid max-w-xl grid-cols-1 gap-3 sm:grid-cols-3">
                            <div class="panel p-4">
                                <div class="flex items-start gap-3">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-lg">🔒</span>
                                    <div>
                                        <h3 class="text-sm font-extrabold text-slate-950">Aman</h3>
                                        <p class="mt-1 text-xs text-slate-500">Akun terlindungi.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="panel p-4">
                                <div class="flex items-start gap-3">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-lg">⚡</span>
                                    <div>
                                        <h3 class="text-sm font-extrabold text-slate-950">Cepat</h3>
                                        <p class="mt-1 text-xs text-slate-500">Proses praktis.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="panel p-4">
                                <div class="flex items-start gap-3">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-lg">✅</span>
                                    <div>
                                        <h3 class="text-sm font-extrabold text-slate-950">Terpercaya</h3>
                                        <p class="mt-1 text-xs text-slate-500">Belanja nyaman.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-4">
                        <div class="panel overflow-hidden">
                            <img
                                src="{{ $heroImage }}"
                                alt="Belanja dan pengemasan produk"
                                class="h-64 w-full object-cover sm:h-72"
                            >
                        </div>

                        <div class="overflow-hidden rounded-[2rem] shadow-[0_20px_40px_rgba(15,23,42,0.16)]">
                            <img
                                src="{{ asset('images/catalog-sabishop-panel.svg') }}"
                                alt="Panel katalog SABISHOP"
                                class="block w-full"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-white">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-black text-slate-950 sm:text-3xl">Kategori Produk</h2>
                        <p class="mt-2 text-sm text-slate-500">Pilih kategori sesuai kebutuhan belanja Anda.</p>
                    </div>

                    <a href="{{ route('produk.public') }}" class="hidden text-sm font-bold text-blue-900 transition hover:text-blue-950 md:inline-flex">
                        Lihat semua produk →
                    </a>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-4 md:grid-cols-4">
                    @foreach($categories as $cat)
                        <a href="{{ route('produk.public', ['category' => $cat['name']]) }}" class="panel p-5 transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                            <div class="flex items-start gap-3">
                                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-xl">
                                    {{ $cat['icon'] }}
                                </span>

                                <div class="min-w-0">
                                    <h3 class="text-sm font-extrabold text-slate-950">{{ $cat['name'] }}</h3>
                                    <p class="mt-2 text-xs leading-5 text-slate-500">{{ $cat['description'] }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="border-t border-slate-200 bg-slate-50">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-black text-slate-950 sm:text-3xl">Produk Unggulan</h2>
                        <p class="mt-2 text-sm text-slate-500">Beberapa produk terbaru yang tersedia di SABISHOP.</p>
                    </div>

                    <a href="{{ route('produk.public') }}" class="hidden text-sm font-bold text-blue-900 transition hover:text-blue-950 md:inline-flex">
                        Lihat katalog →
                    </a>
                </div>

                @if(isset($featuredProducts) && $featuredProducts->count() > 0)
                    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach($featuredProducts as $product)
                            @php
                                $imageSrc = 'https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=1200&auto=format&fit=crop';

                                if (!empty($product->image_url)) {
                                    $imageSrc = str_starts_with($product->image_url, 'http')
                                        ? $product->image_url
                                        : asset($product->image_url);
                                }
                            @endphp

                            <div class="panel flex flex-col overflow-hidden">
                                <div class="relative bg-slate-100">
                                    <img
                                        src="{{ $imageSrc }}"
                                        alt="{{ $product->name }}"
                                        class="h-48 w-full object-cover"
                                    >

                                    <span class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-[11px] font-bold text-blue-950 shadow-sm">
                                        {{ $product->category ?? 'Lainnya' }}
                                    </span>

                                    @if($product->stock <= 5 && $product->stock > 0)
                                        <span class="absolute right-3 top-3 rounded-full bg-blue-950 px-3 py-1 text-[11px] font-bold text-white shadow-sm">
                                            Stok Menipis
                                        </span>
                                    @endif

                                    @if($product->stock < 1)
                                        <span class="absolute right-3 top-3 rounded-full bg-slate-900 px-3 py-1 text-[11px] font-bold text-white shadow-sm">
                                            Stok Habis
                                        </span>
                                    @endif
                                </div>

                                <div class="flex flex-1 flex-col p-5">
                                    <h3 class="line-clamp-2 text-lg font-extrabold text-slate-950">
                                        {{ $product->name }}
                                    </h3>

                                    <p class="mt-2 line-clamp-2 min-h-[44px] text-sm leading-relaxed text-slate-500">
                                        {{ $product->description ?? 'Tidak ada deskripsi produk.' }}
                                    </p>

                                    <div class="mt-4 flex items-end justify-between gap-3">
                                        <div>
                                            <p class="text-xs text-slate-400">Harga</p>
                                            <p class="text-xl font-black text-blue-950">
                                                Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-xs text-slate-400">Stok</p>
                                            <p class="text-sm font-bold text-slate-700">{{ $product->stock }}</p>
                                        </div>
                                    </div>

                                    @if($product->stock < 1)
                                        <button disabled class="mt-5 w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-extrabold text-slate-400">
                                            Stok Habis
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="mt-5 w-full rounded-xl bg-blue-950 px-4 py-3 text-center text-sm font-extrabold text-white transition hover:bg-blue-900">
                                            Login untuk Membeli
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="panel mt-6 py-16 text-center">
                        <h3 class="text-lg font-extrabold text-slate-950">Produk belum tersedia</h3>
                        <p class="mt-2 text-sm text-slate-500">
                            Produk akan tampil di halaman ini setelah admin menambahkannya.
                        </p>
                    </div>
                @endif
            </div>
        </section>

        <section class="bg-white">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="panel bg-blue-950 px-6 py-10 text-center text-white sm:px-10 sm:py-12">
                    <h2 class="text-3xl font-black sm:text-4xl">Siap mulai belanja hari ini?</h2>
                    <p class="mx-auto mt-3 max-w-2xl text-sm leading-7 text-blue-100">
                        Masuk ke akun Anda atau daftar akun baru untuk menikmati pengalaman belanja yang lebih mudah.
                    </p>

                    <div class="mt-7 flex flex-col justify-center gap-3 sm:flex-row">
                        <a href="{{ route('login') }}" class="rounded-xl bg-white px-6 py-3 text-sm font-extrabold text-blue-950 transition hover:bg-blue-50">
                            Login Sekarang
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-xl border border-white/20 bg-white/10 px-6 py-3 text-sm font-extrabold text-white transition hover:bg-white/15">
                                Daftar Akun
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200 bg-slate-50">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 md:grid-cols-3 lg:px-8">
            <div>
                <div class="flex items-center gap-3">
                    <span class="brand-mark">SB</span>
                    <div>
                        <h3 class="text-lg font-black text-slate-950">SABISHOP</h3>
                        <p class="text-xs font-medium text-slate-500">Platform belanja online terpercaya</p>
                    </div>
                </div>
                <p class="mt-4 max-w-sm text-sm leading-7 text-slate-500">
                    SABISHOP menyediakan pengalaman belanja online yang sederhana, aman, dan mudah digunakan.
                </p>
            </div>

            <div>
                <h4 class="text-sm font-black uppercase tracking-[0.18em] text-slate-950">Menu</h4>
                <ul class="mt-4 space-y-2 text-sm text-slate-500">
                    <li><a href="{{ route('beranda') }}" class="transition hover:text-blue-900">Beranda</a></li>
                    <li><a href="{{ route('produk.public') }}" class="transition hover:text-blue-900">Produk</a></li>
                    <li><a href="{{ route('bantuan') }}" class="transition hover:text-blue-900">Bantuan</a></li>
                    <li><a href="{{ route('login') }}" class="transition hover:text-blue-900">Login</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-black uppercase tracking-[0.18em] text-slate-950">Kategori Produk</h4>
                <ul class="mt-4 space-y-2 text-sm text-slate-500">
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('produk.public', ['category' => $cat['name']]) }}" class="transition hover:text-blue-900">
                                {{ $cat['icon'] }} {{ $cat['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-200 py-5 text-center text-sm text-slate-400">
            &copy; {{ date('Y') }} SABISHOP. All Rights Reserved.
        </div>
    </footer>
</body>
</html>
