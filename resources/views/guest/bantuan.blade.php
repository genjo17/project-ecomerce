<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantuan - SABISHOP</title>
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

                <a href="{{ route('produk.public') }}" class="hover:text-orange-600 transition">
                    Produk
                </a>

                <a href="{{ route('bantuan') }}" class="text-orange-600">
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
    <section class="bg-white">
        <div class="max-w-7xl mx-auto px-6 py-14">
            <div class="bg-gradient-to-br from-orange-500 to-amber-500 rounded-[2rem] p-8 md:p-12 text-white relative overflow-hidden shadow-sm">

                <div class="absolute -right-16 -top-16 w-56 h-56 bg-white/10 rounded-full"></div>
                <div class="absolute right-10 bottom-0 text-9xl opacity-20 select-none">
                    💬
                </div>

                <div class="relative z-10 max-w-2xl">
                    <span class="inline-block bg-white/20 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider mb-5">
                        Pusat Bantuan
                    </span>

                    <h2 class="text-3xl md:text-5xl font-extrabold leading-tight mb-4">
                        Ada kendala saat belanja?
                    </h2>

                    <p class="text-orange-50 leading-relaxed">
                        Temukan panduan penggunaan SABISHOP, mulai dari cara daftar akun,
                        melihat produk, login, reset password, hingga melakukan pembelian.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- QUICK HELP -->
    <section class="max-w-7xl mx-auto px-6 -mt-6 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-lg">
                <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    🛒
                </div>
                <h3 class="font-extrabold text-gray-900 text-lg">
                    Cara Belanja
                </h3>
                <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                    Lihat produk, login akun, masukkan produk ke keranjang, lalu checkout pesanan.
                </p>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-lg">
                <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    🔐
                </div>
                <h3 class="font-extrabold text-gray-900 text-lg">
                    Bantuan Akun
                </h3>
                <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                    Pelanggan dapat login, daftar akun baru, dan melakukan reset password jika lupa.
                </p>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-lg">
                <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    📦
                </div>
                <h3 class="font-extrabold text-gray-900 text-lg">
                    Pesanan
                </h3>
                <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                    Setelah login, pelanggan dapat melihat keranjang dan riwayat pesanan.
                </p>
            </div>

        </div>
    </section>

    <!-- CONTENT -->
    <main class="max-w-7xl mx-auto px-6 py-14">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <!-- LEFT CONTENT -->
            <section class="lg:col-span-2 space-y-8">

                <!-- CARA BELANJA -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-7">
                    <h2 class="text-2xl font-extrabold text-gray-900 mb-2">
                        Cara Belanja di SABISHOP
                    </h2>
                    <p class="text-gray-500 text-sm mb-6">
                        Ikuti langkah sederhana berikut untuk mulai membeli produk.
                    </p>

                    <div class="space-y-5">
                        <div class="flex gap-4">
                            <div class="w-10 h-10 shrink-0 rounded-2xl bg-orange-500 text-white flex items-center justify-center font-extrabold">
                                1
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Lihat katalog produk</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Buka halaman produk untuk melihat daftar barang, harga, stok, dan deskripsi produk.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-10 h-10 shrink-0 rounded-2xl bg-orange-500 text-white flex items-center justify-center font-extrabold">
                                2
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Login atau daftar akun</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Login diperlukan agar pelanggan dapat menambahkan produk ke keranjang dan melakukan checkout.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-10 h-10 shrink-0 rounded-2xl bg-orange-500 text-white flex items-center justify-center font-extrabold">
                                3
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Tambahkan produk ke keranjang</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Pilih produk yang tersedia, lalu masukkan ke keranjang belanja.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-10 h-10 shrink-0 rounded-2xl bg-orange-500 text-white flex items-center justify-center font-extrabold">
                                4
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Checkout dan pantau pesanan</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Setelah checkout, pelanggan dapat melihat riwayat pesanan pada menu Pesanan Saya.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-7">
                    <h2 class="text-2xl font-extrabold text-gray-900 mb-2">
                        Pertanyaan Umum
                    </h2>
                    <p class="text-gray-500 text-sm mb-6">
                        Beberapa pertanyaan yang sering diajukan pelanggan.
                    </p>

                    <div class="space-y-3">

                        <details class="group border border-gray-100 rounded-2xl p-5 bg-gray-50">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                Apakah harus login untuk membeli produk?
                                <span class="text-orange-500 group-open:rotate-180 transition">⌄</span>
                            </summary>
                            <p class="text-sm text-gray-500 mt-3 leading-relaxed">
                                Ya. Pelanggan perlu login agar sistem dapat menyimpan keranjang, checkout, dan riwayat pesanan.
                            </p>
                        </details>

                        <details class="group border border-gray-100 rounded-2xl p-5 bg-gray-50">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                Bagaimana jika lupa password?
                                <span class="text-orange-500 group-open:rotate-180 transition">⌄</span>
                            </summary>
                            <p class="text-sm text-gray-500 mt-3 leading-relaxed">
                                Klik menu Lupa Password pada halaman login, masukkan email terdaftar, lalu ikuti link reset password.
                            </p>
                        </details>

                        <details class="group border border-gray-100 rounded-2xl p-5 bg-gray-50">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                Apakah stok produk selalu tersedia?
                                <span class="text-orange-500 group-open:rotate-180 transition">⌄</span>
                            </summary>
                            <p class="text-sm text-gray-500 mt-3 leading-relaxed">
                                Stok produk mengikuti data yang dikelola admin. Jika stok habis, tombol pembelian akan dinonaktifkan.
                            </p>
                        </details>

                        <details class="group border border-gray-100 rounded-2xl p-5 bg-gray-50">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                Bagaimana melihat pesanan saya?
                                <span class="text-orange-500 group-open:rotate-180 transition">⌄</span>
                            </summary>
                            <p class="text-sm text-gray-500 mt-3 leading-relaxed">
                                Setelah login, pelanggan dapat membuka menu Pesanan Saya untuk melihat riwayat pembelian.
                            </p>
                        </details>

                        <details class="group border border-gray-100 rounded-2xl p-5 bg-gray-50">
                            <summary class="cursor-pointer font-bold text-gray-900 flex justify-between items-center">
                                Bagaimana menghubungi admin?
                                <span class="text-orange-500 group-open:rotate-180 transition">⌄</span>
                            </summary>
                            <p class="text-sm text-gray-500 mt-3 leading-relaxed">
                                Pelanggan dapat menghubungi admin melalui tombol WhatsApp yang tersedia pada halaman bantuan ini.
                            </p>
                        </details>

                    </div>
                </div>

            </section>

            <!-- RIGHT SIDEBAR -->
            <aside class="space-y-6">

                <!-- CONTACT CARD -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-7">
                    <div class="w-14 h-14 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center text-3xl mb-5">
                        💬
                    </div>

                    <h2 class="text-xl font-extrabold text-gray-900">
                        Butuh Bantuan Langsung?
                    </h2>

                    <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                        Hubungi admin jika mengalami kendala saat login, reset password, melihat produk, atau checkout pesanan.
                    </p>

                    <a href="https://wa.me/6281234567890"
                       target="_blank"
                       class="block text-center mt-6 bg-orange-500 hover:bg-orange-600 text-white font-extrabold py-3 rounded-2xl transition">
                        Hubungi Admin
                    </a>

                    <p class="text-xs text-gray-400 mt-3 text-center">
                        Ganti nomor WhatsApp pada kode sesuai nomor admin toko.
                    </p>
                </div>

                <!-- INFO CARD -->
                <div class="bg-gray-900 rounded-3xl p-7 text-white relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-orange-500/20 rounded-full"></div>

                    <div class="relative z-10">
                        <h2 class="text-xl font-extrabold">
                            Jam Layanan
                        </h2>

                        <div class="mt-5 space-y-4 text-sm">
                            <div class="flex justify-between gap-4 border-b border-white/10 pb-3">
                                <span class="text-gray-300">Senin - Jumat</span>
                                <span class="font-bold">08.00 - 17.00</span>
                            </div>

                            <div class="flex justify-between gap-4 border-b border-white/10 pb-3">
                                <span class="text-gray-300">Sabtu</span>
                                <span class="font-bold">08.00 - 14.00</span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-300">Minggu</span>
                                <span class="font-bold">Libur</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACTION CARD -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-7">
                    <h2 class="text-xl font-extrabold text-gray-900">
                        Mulai Belanja
                    </h2>

                    <p class="text-sm text-gray-500 mt-2">
                        Lihat katalog produk atau masuk ke akun Anda untuk mulai membeli.
                    </p>

                    <div class="mt-6 space-y-3">
                        <a href="{{ route('produk.public') }}"
                           class="block text-center bg-orange-500 hover:bg-orange-600 text-white font-extrabold py-3 rounded-2xl transition">
                            Lihat Produk
                        </a>

                        <a href="{{ route('login') }}"
                           class="block text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-extrabold py-3 rounded-2xl transition">
                            Login Akun
                        </a>
                    </div>
                </div>

            </aside>

        </div>

    </main>

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