<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - SABISHOP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800">

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
                <a href="{{ route('beranda') }}" class="hover:text-orange-600 transition">
                    Beranda
                </a>

                <a href="{{ route('produk.public') }}" class="text-orange-600">
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

    <!-- HERO PRODUK -->
    <section class="bg-white">
        <div class="max-w-7xl mx-auto px-6 py-14">
            <div class="bg-gradient-to-br from-orange-500 to-amber-500 rounded-[2rem] p-8 md:p-12 text-white relative overflow-hidden shadow-sm">
                
                <div class="absolute -right-16 -top-16 w-56 h-56 bg-white/10 rounded-full"></div>
                <div class="absolute right-10 bottom-0 text-9xl opacity-20 select-none">
                    🛍️
                </div>

                <div class="relative z-10 max-w-2xl">
                    <span class="inline-block bg-white/20 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider mb-5">
                        Katalog Produk
                    </span>

                    <h2 class="text-3xl md:text-5xl font-extrabold leading-tight mb-4">
                        Temukan produk terbaik untuk kebutuhan Anda
                    </h2>

                    <p class="text-orange-50 leading-relaxed">
                        Lihat daftar produk yang tersedia di SABISHOP. Untuk membeli produk,
                        silakan login terlebih dahulu ke akun Anda.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- SEARCH -->
    <!-- SEARCH & FILTER -->
<section class="max-w-7xl mx-auto px-6 -mt-6 relative z-20">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-lg p-5">
        <form action="{{ route('produk.public') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3">

            <!-- SEARCH INPUT -->
            <div class="relative md:col-span-7">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    🔍
                </span>

                <input 
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari nama produk..."
                    class="w-full rounded-2xl border border-gray-200 bg-gray-50 pl-11 pr-4 py-3 text-sm text-gray-700 placeholder-gray-400 outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition"
                >
            </div>

            <!-- CATEGORY DROPDOWN -->
            <div class="md:col-span-3">
                <select 
                    name="category"
                    class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-700 outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition cursor-pointer"
                >
                    <option value="">Semua Kategori</option>

                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ ($category ?? '') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- BUTTON -->
            <div class="md:col-span-2">
                <button 
                    type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold px-7 py-3 rounded-2xl transition">
                    Cari
                </button>
            </div>

        </form>

        @if(!empty($search) || !empty($category))
            <div class="text-sm text-gray-500 mt-4 space-y-1">
                @if(!empty($search))
                    <p>
                        Menampilkan hasil pencarian untuk:
                        <strong class="text-gray-900">"{{ $search }}"</strong>
                    </p>
                @endif

                @if(!empty($category))
                    <p>
                        Kategori:
                        <strong class="text-gray-900">{{ $category }}</strong>
                    </p>
                @endif

                <a href="{{ route('produk.public') }}"
                   class="inline-block mt-2 text-orange-600 hover:text-orange-700 font-bold">
                    Reset filter
                </a>
            </div>
        @endif
    </div>
</section>

    <!-- PRODUCT LIST -->
    <main class="max-w-7xl mx-auto px-6 py-12">

        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">
                    Semua Produk
                </h2>
                <p class="text-gray-500 mt-2">
                    Produk yang tersedia berdasarkan data dari admin.
                </p>
            </div>
        </div>

        @if($products->isEmpty())
            <div class="bg-white border border-gray-100 rounded-3xl py-16 text-center shadow-sm">
                <div class="text-5xl mb-4">🛒</div>
                <h3 class="text-lg font-extrabold text-gray-900">
                    Produk tidak ditemukan
                </h3>
                <p class="text-gray-500 mt-2">
                    Belum ada produk yang tersedia atau kata kunci pencarian tidak cocok.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col">

                        <!-- IMAGE -->
                        <div class="relative bg-gray-100 overflow-hidden">
                            <img 
                                <img src="{{ $product->image_url ? asset($product->image_url) : 'https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=500&auto=format&fit=crop' }}"
                                alt="{{ $product->name }}"
                                class="w-full h-48 object-cover hover:scale-105 transition duration-500"
                            >

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

                        <!-- DETAIL -->
                        <div class="p-5 flex flex-col flex-grow">
                            <div class="flex-grow">
                                <h3 class="font-extrabold text-gray-900 text-lg line-clamp-1">
                                    {{ $product->name }}
                                </h3>
                                
                                <span class="inline-flex mt-2 bg-orange-50 text-orange-700 text-xs font-bold px-3 py-1 rounded-full">
    {{ $product->category ?? 'Lainnya' }}
</span>

                                <p class="text-gray-500 text-sm mt-2 leading-relaxed min-h-[42px] line-clamp-2">
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
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
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

                                @if($product->stock < 1)
                                    <button disabled class="w-full bg-gray-300 text-white font-bold py-3 rounded-2xl cursor-not-allowed">
                                        Stok Habis
                                    </button>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="block text-center w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-2xl transition">
                                        Login untuk Membeli
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- PAGINATION -->
            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @endif

    </main>

    <!-- CTA -->
    <section class="bg-white py-14">
        <div class="max-w-7xl mx-auto px-6">
            <div class="bg-gray-900 rounded-[2rem] p-10 md:p-12 text-white text-center relative overflow-hidden">
                <div class="absolute -top-16 -right-16 w-52 h-52 bg-orange-500/20 rounded-full"></div>
                <div class="absolute -bottom-16 -left-16 w-52 h-52 bg-orange-500/20 rounded-full"></div>

                <div class="relative z-10 max-w-2xl mx-auto">
                    <h2 class="text-3xl md:text-4xl font-extrabold mb-4">
                        Ingin membeli produk?
                    </h2>

                    <p class="text-gray-300 mb-8">
                        Login ke akun Anda untuk menambahkan produk ke keranjang dan melakukan checkout.
                    </p>

                    <a href="{{ route('login') }}"
                       class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-extrabold px-8 py-3 rounded-xl transition">
                        Login Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-500 flex items-center justify-center text-white text-xl">
                    🛒
                </div>
                <div>
                    <h3 class="font-extrabold">SABISHOP</h3>
                    <p class="text-xs text-gray-400">Platform belanja online terpercaya</p>
                </div>
            </div>

            <p class="text-sm text-gray-400">
                &copy; {{ date('Y') }} genjo. All Rights Reserved.
            </p>
        </div>
    </footer>

</body>
</html>