<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - SABISHOP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-800">
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

        $activeCategory = $category ?? request('category');
        $searchValue = $search ?? request('search');
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
                <a href="{{ route('beranda') }}" class="transition hover:text-blue-900">Beranda</a>
                <a href="{{ route('produk.public') }}" class="text-blue-950">Produk</a>
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

    <main class="border-b border-slate-200 bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
            <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr] lg:items-stretch">
                <div class="panel p-8">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-blue-900">Katalog Produk</p>
                    <h1 class="mt-3 max-w-2xl text-4xl font-black leading-tight text-slate-950">
                        Temukan produk terbaik untuk kebutuhan Anda.
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600">
                        Lihat daftar produk yang tersedia di SABISHOP dan gunakan filter untuk menemukan
                        kategori yang paling relevan.
                    </p>

                    <div class="mt-8 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm font-extrabold text-slate-950">Lengkap</p>
                            <p class="mt-1 text-xs text-slate-500">Produk tersusun rapi per kategori.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm font-extrabold text-slate-950">Cepat Dicari</p>
                            <p class="mt-1 text-xs text-slate-500">Gunakan pencarian dan filter.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm font-extrabold text-slate-950">Profesional</p>
                            <p class="mt-1 text-xs text-slate-500">Tampilan belanja yang bersih.</p>
                        </div>
                    </div>
                </div>

                <div class="panel overflow-hidden">
                    <img
                        src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?q=80&w=1200&auto=format&fit=crop"
                        alt="Produk dan kemasan belanja"
                        class="h-full min-h-[260px] w-full object-cover"
                    >
                </div>
            </div>
        </div>
    </main>

    <section class="-mt-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="panel p-4">
                <form action="{{ route('produk.public') }}" method="GET" class="grid gap-3 md:grid-cols-[1fr_220px_auto]">
                    <label class="relative block">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">🔍</span>
                        <input
                            type="text"
                            name="search"
                            value="{{ $searchValue ?? '' }}"
                            placeholder="Cari nama produk..."
                            class="form-field w-full border-slate-200 bg-slate-50 py-3 pl-11 pr-4"
                        >
                    </label>

                    <select
                        name="category"
                        class="form-field w-full border-slate-200 bg-slate-50 px-4 py-3"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ ($activeCategory ?? '') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn-primary px-7 py-3">
                        Cari
                    </button>
                </form>

                @if(!empty($searchValue) || !empty($activeCategory))
                    <div class="mt-4 space-y-1 text-sm text-slate-500">
                        @if(!empty($searchValue))
                            <p>
                                Menampilkan hasil pencarian untuk:
                                <strong class="text-slate-900">"{{ $searchValue }}"</strong>
                            </p>
                        @endif

                        @if(!empty($activeCategory))
                            <p>
                                Kategori:
                                <strong class="text-slate-900">{{ $activeCategory }}</strong>
                            </p>
                        @endif

                        <a href="{{ route('produk.public') }}" class="inline-flex text-sm font-bold text-blue-900 transition hover:text-blue-950">
                            Reset filter
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-950 sm:text-3xl">Semua Produk</h2>
                <p class="mt-2 text-sm text-slate-500">Produk yang tersedia berdasarkan data dari admin.</p>
            </div>
        </div>

        @if($products->isEmpty())
            <div class="panel py-16 text-center">
                <h3 class="text-lg font-extrabold text-slate-950">Produk tidak ditemukan</h3>
                <p class="mt-2 text-sm text-slate-500">
                    Belum ada produk yang tersedia atau kata kunci pencarian tidak cocok.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($products as $product)
                    <div class="panel flex flex-col overflow-hidden">
                        <div class="relative bg-slate-100">
                            <img
                                src="{{ $product->image_url ? asset($product->image_url) : 'https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=900&auto=format&fit=crop' }}"
                                alt="{{ $product->name }}"
                                class="h-48 w-full object-cover"
                            >

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
                            <div class="flex-1">
                                <h3 class="line-clamp-1 text-lg font-extrabold text-slate-950">
                                    {{ $product->name }}
                                </h3>

                                <span class="mt-2 inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-900">
                                    {{ $product->category ?? 'Lainnya' }}
                                </span>

                                <p class="mt-3 line-clamp-2 min-h-[44px] text-sm leading-relaxed text-slate-500">
                                    {{ $product->description ?? 'Tidak ada deskripsi produk.' }}
                                </p>
                            </div>

                            <div class="mt-5 flex items-end justify-between gap-3">
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
        @endif
    </main>

    <footer class="border-t border-slate-200 bg-white">
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
                <h4 class="text-sm font-black uppercase tracking-[0.18em] text-slate-950">Kategori</h4>
                <ul class="mt-4 space-y-2 text-sm text-slate-500">
                    @foreach($categories as $cat)
                        <li>{{ $cat }}</li>
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
