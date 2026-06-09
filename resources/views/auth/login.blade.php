<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SABISHOP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-800">

    <!-- HEADER -->
    <header class="w-full bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-500 flex items-center justify-center text-white text-xl">
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

            <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
                <a href="{{ route('beranda') }}" class="hover:text-orange-500 transition">Beranda</a>
                <a href="{{ route('produk.public') }}" class="hover:text-orange-500 transition">Produk</a>
                <a href="{{ route('bantuan') }}" class="hover:text-orange-500 transition"> Bantuan</a>
            </nav>
        </div>
    </header>

    <!-- MAIN -->
    <main class="min-h-[calc(100vh-145px)] flex items-center justify-center px-5 py-10">
        <div class="w-full max-w-6xl bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden grid grid-cols-1 lg:grid-cols-2">

            <!-- BAGIAN KIRI -->
            <section class="hidden lg:flex flex-col justify-between bg-gradient-to-br from-orange-500 to-orange-600 p-10 text-white relative overflow-hidden">

                <div class="absolute -top-24 -right-24 w-72 h-72 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-white/10 rounded-full"></div>

                <div class="relative z-10">
                    <span class="inline-block bg-white/20 text-white text-xs font-semibold px-4 py-2 rounded-full mb-6">
                        Belanja Online Mudah
                    </span>

                    <h2 class="text-4xl font-extrabold leading-tight mb-4">
                        Masuk dan lanjutkan pengalaman belanjamu
                    </h2>

                    <p class="text-orange-50 leading-relaxed max-w-md">
                        Temukan produk pilihan, pantau pesanan, dan nikmati proses belanja
                        yang lebih praktis bersama SABISHOP.
                    </p>
                </div>

                <div class="relative z-10 grid grid-cols-2 gap-4 mt-10">
                    <div class="bg-white/15 backdrop-blur rounded-2xl p-5">
                        <p class="text-3xl mb-2">📦</p>
                        <h3 class="font-bold">Produk Lengkap</h3>
                        <p class="text-sm text-orange-50 mt-1">Banyak pilihan kebutuhan.</p>
                    </div>

                    <div class="bg-white/15 backdrop-blur rounded-2xl p-5">
                        <p class="text-3xl mb-2">🚚</p>
                        <h3 class="font-bold">Pengiriman Cepat</h3>
                        <p class="text-sm text-orange-50 mt-1">Pesanan sampai tujuan.</p>
                    </div>

                    <div class="bg-white/15 backdrop-blur rounded-2xl p-5">
                        <p class="text-3xl mb-2">💳</p>
                        <h3 class="font-bold">Pembayaran Mudah</h3>
                        <p class="text-sm text-orange-50 mt-1">Transaksi lebih praktis.</p>
                    </div>

                    <div class="bg-white/15 backdrop-blur rounded-2xl p-5">
                        <p class="text-3xl mb-2">🔒</p>
                        <h3 class="font-bold">Akun Aman</h3>
                        <p class="text-sm text-orange-50 mt-1">Data pengguna terlindungi.</p>
                    </div>
                </div>
            </section>

            <!-- FORM LOGIN -->
            <section class="p-8 sm:p-12 flex items-center">
                <div class="w-full max-w-md mx-auto">

                    <div class="mb-8">
                        <h2 class="text-3xl font-extrabold text-gray-900 mb-2">
                            Login Akun
                        </h2>
                        <p class="text-sm text-gray-500">
                            Masukkan email dan password untuk melanjutkan.
                        </p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- EMAIL -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email
                            </label>

                            <input 
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Masukkan email Anda"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-800 placeholder-gray-400 outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition"
                            >

                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                        </div>

                        <!-- PASSWORD -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password
                            </label>

                            <input 
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan password"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-800 placeholder-gray-400 outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition"
                            >

                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                        </div>

                        <!-- REMEMBER & FORGOT -->
                        <div class="flex items-center justify-between text-sm">
                            <label for="remember_me" class="inline-flex items-center">
                                <input 
                                    id="remember_me"
                                    type="checkbox"
                                    name="remember"
                                    class="rounded border-gray-300 text-orange-500 shadow-sm focus:ring-orange-500"
                                >
                                <span class="ms-2 text-gray-600">Ingat saya</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-orange-600 hover:text-orange-700 font-semibold">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <!-- BUTTON -->
                        <button 
                            type="submit"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-orange-200 transition duration-300"
                        >
                            Login
                        </button>

                        <!-- REGISTER -->
                        @if (Route::has('register'))
                            <div class="text-center text-sm text-gray-600 pt-2">
                                Belum punya akun?
                                <a href="{{ route('register') }}" class="text-orange-600 hover:text-orange-700 font-bold">
                                    Daftar sekarang
                                </a>
                            </div>
                        @endif
                    </form>

                    <!-- INFO -->
                    <div class="mt-8 pt-6 border-t border-gray-200 grid grid-cols-3 gap-3 text-center">
                        <div>
                            <p class="text-lg">🔒</p>
                            <p class="text-xs text-gray-500 mt-1">Aman</p>
                        </div>

                        <div>
                            <p class="text-lg">⚡</p>
                            <p class="text-xs text-gray-500 mt-1">Cepat</p>
                        </div>

                        <div>
                            <p class="text-lg">✅</p>
                            <p class="text-xs text-gray-500 mt-1">Terpercaya</p>
                        </div>
                    </div>

                </div>
            </section>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="w-full text-center py-5 text-sm text-gray-500">
        &copy; {{ date('Y') }} genjo. All Rights Reserved.
    </footer>

</body>
</html>