<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantuan - SABISHOP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-800">
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
                <a href="{{ route('produk.public') }}" class="transition hover:text-blue-900">Produk</a>
                <a href="{{ route('bantuan') }}" class="text-blue-950">Bantuan</a>
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
            <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr] lg:items-stretch">
                <div class="panel p-8">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-blue-900">Pusat Bantuan</p>
                    <h1 class="mt-3 max-w-2xl text-4xl font-black leading-tight text-slate-950">
                        Ada kendala saat belanja?
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600">
                        Temukan panduan penggunaan SABISHOP, mulai dari cara daftar akun, melihat produk,
                        login, reset password, hingga melakukan pembelian.
                    </p>

                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm font-extrabold text-slate-950">Cara Belanja</p>
                            <p class="mt-1 text-xs text-slate-500">Alur pembelian yang ringkas.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm font-extrabold text-slate-950">Bantuan Akun</p>
                            <p class="mt-1 text-xs text-slate-500">Login, daftar, dan reset password.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm font-extrabold text-slate-950">Pesanan</p>
                            <p class="mt-1 text-xs text-slate-500">Pantau status checkout dan riwayat.</p>
                        </div>
                    </div>
                </div>

                <div class="panel overflow-hidden">
                    <img
                        src="https://images.unsplash.com/photo-1556742031-c6961e8560b0?q=80&w=1200&auto=format&fit=crop"
                        alt="Layanan bantuan belanja online"
                        class="h-full min-h-[260px] w-full object-cover"
                    >
                </div>
            </div>
        </div>
    </main>

    <section class="-mt-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-5 md:grid-cols-3">
                <div class="panel p-6">
                    <h3 class="text-lg font-extrabold text-slate-950">Cara Belanja</h3>
                    <p class="mt-2 text-sm leading-7 text-slate-500">
                        Lihat produk, login akun, masukkan produk ke keranjang, lalu checkout pesanan.
                    </p>
                </div>

                <div class="panel p-6">
                    <h3 class="text-lg font-extrabold text-slate-950">Bantuan Akun</h3>
                    <p class="mt-2 text-sm leading-7 text-slate-500">
                        Pelanggan dapat login, daftar akun baru, dan melakukan reset password jika lupa.
                    </p>
                </div>

                <div class="panel p-6">
                    <h3 class="text-lg font-extrabold text-slate-950">Pesanan</h3>
                    <p class="mt-2 text-sm leading-7 text-slate-500">
                        Setelah login, pelanggan dapat melihat keranjang dan riwayat pesanan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <section class="space-y-8">
                <div class="panel p-7">
                    <h2 class="text-2xl font-black text-slate-950">Cara Belanja di SABISHOP</h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Ikuti langkah sederhana berikut untuk mulai membeli produk.
                    </p>

                    <div class="mt-6 space-y-5">
                        <div class="flex gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-950 text-sm font-black text-white">1</div>
                            <div>
                                <h3 class="font-bold text-slate-950">Lihat katalog produk</h3>
                                <p class="mt-1 text-sm leading-7 text-slate-500">
                                    Buka halaman produk untuk melihat daftar barang, harga, stok, dan deskripsi produk.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-950 text-sm font-black text-white">2</div>
                            <div>
                                <h3 class="font-bold text-slate-950">Login atau daftar akun</h3>
                                <p class="mt-1 text-sm leading-7 text-slate-500">
                                    Login diperlukan agar pelanggan dapat menambahkan produk ke keranjang dan melakukan checkout.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-950 text-sm font-black text-white">3</div>
                            <div>
                                <h3 class="font-bold text-slate-950">Tambahkan produk ke keranjang</h3>
                                <p class="mt-1 text-sm leading-7 text-slate-500">
                                    Pilih produk yang tersedia, lalu masukkan ke keranjang belanja.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-950 text-sm font-black text-white">4</div>
                            <div>
                                <h3 class="font-bold text-slate-950">Checkout dan pantau pesanan</h3>
                                <p class="mt-1 text-sm leading-7 text-slate-500">
                                    Setelah checkout, pelanggan dapat melihat riwayat pesanan pada menu Pesanan Saya.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel p-7">
                    <h2 class="text-2xl font-black text-slate-950">Pertanyaan Umum</h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Beberapa pertanyaan yang sering diajukan pelanggan.
                    </p>

                    <div class="mt-6 space-y-3">
                        <details class="group rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <summary class="flex cursor-pointer items-center justify-between font-bold text-slate-950">
                                Apakah harus login untuk membeli produk?
                                <span class="text-blue-900 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-3 text-sm leading-7 text-slate-500">
                                Ya. Pelanggan perlu login agar sistem dapat menyimpan keranjang, checkout, dan riwayat pesanan.
                            </p>
                        </details>

                        <details class="group rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <summary class="flex cursor-pointer items-center justify-between font-bold text-slate-950">
                                Bagaimana jika lupa password?
                                <span class="text-blue-900 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-3 text-sm leading-7 text-slate-500">
                                Klik menu Lupa Password pada halaman login, masukkan email terdaftar, lalu ikuti link reset password.
                            </p>
                        </details>

                        <details class="group rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <summary class="flex cursor-pointer items-center justify-between font-bold text-slate-950">
                                Bagaimana melihat pesanan saya?
                                <span class="text-blue-900 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-3 text-sm leading-7 text-slate-500">
                                Setelah login, pelanggan dapat membuka menu Pesanan Saya untuk melihat riwayat pembelian.
                            </p>
                        </details>

                        <details class="group rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <summary class="flex cursor-pointer items-center justify-between font-bold text-slate-950">
                                Bagaimana menghubungi admin?
                                <span class="text-blue-900 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-3 text-sm leading-7 text-slate-500">
                                Pelanggan dapat menghubungi admin melalui tombol WhatsApp yang tersedia pada halaman bantuan ini.
                            </p>
                        </details>
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <div class="panel p-6">
                    <h3 class="text-lg font-extrabold text-slate-950">Butuh bantuan cepat?</h3>
                    <p class="mt-2 text-sm leading-7 text-slate-500">
                        Gunakan login atau daftar akun untuk mulai mengakses katalog, keranjang, dan riwayat pesanan.
                    </p>

                    <div class="mt-5 flex flex-col gap-3 sm:flex-row lg:flex-col">
                        <a href="{{ route('login') }}" class="btn-primary w-full">
                            Login Sekarang
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-secondary w-full">
                                Daftar Akun
                            </a>
                        @endif
                    </div>
                </div>

                <div class="panel p-6">
                    <h3 class="text-lg font-extrabold text-slate-950">Kontak</h3>
                    <p class="mt-2 text-sm leading-7 text-slate-500">
                        Tambahkan channel kontak toko Anda di sini jika sudah tersedia.
                    </p>
                </div>
            </aside>
        </div>
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
                <h4 class="text-sm font-black uppercase tracking-[0.18em] text-slate-950">Bantuan</h4>
                <ul class="mt-4 space-y-2 text-sm text-slate-500">
                    <li>Cara belanja</li>
                    <li>Akun dan password</li>
                    <li>Pesanan dan checkout</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-200 py-5 text-center text-sm text-slate-400">
            &copy; {{ date('Y') }} SABISHOP. All Rights Reserved.
        </div>
    </footer>
</body>
</html>
