<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - MY-OLSHOP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-800">

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

        <!-- LEFT BRAND SECTION -->
        <section class="hidden lg:flex flex-col justify-between bg-gradient-to-br from-orange-500 to-amber-500 p-12 text-white relative overflow-hidden">

            <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/10 rounded-full"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-white/10 rounded-full"></div>
            <div class="absolute right-12 bottom-20 text-9xl opacity-20 select-none">
                🛍️
            </div>

            <a href="{{ route('login') }}" class="relative z-10 flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center text-2xl">
                    🛒
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold tracking-wide">
                        MY-OLSHOP
                    </h1>
                    <p class="text-sm text-orange-50">
                        Belanja mudah, aman, dan terpercaya
                    </p>
                </div>
            </a>

            <div class="relative z-10 max-w-lg">
                <span class="inline-block bg-white/20 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider mb-5">
                    Buat Password Baru
                </span>

                <h2 class="text-4xl font-extrabold leading-tight mb-4">
                    Amankan kembali akun belanja Anda
                </h2>

                <p class="text-orange-50 leading-relaxed">
                    Buat password baru yang mudah diingat tetapi sulit ditebak agar akun
                    MY-OLSHOP Anda tetap aman.
                </p>
            </div>

            <div class="relative z-10 grid grid-cols-3 gap-3 text-center">
                <div class="bg-white/15 rounded-2xl p-4">
                    <p class="text-2xl">🔒</p>
                    <p class="text-xs mt-1">Aman</p>
                </div>

                <div class="bg-white/15 rounded-2xl p-4">
                    <p class="text-2xl">⚡</p>
                    <p class="text-xs mt-1">Cepat</p>
                </div>

                <div class="bg-white/15 rounded-2xl p-4">
                    <p class="text-2xl">✅</p>
                    <p class="text-xs mt-1">Praktis</p>
                </div>
            </div>

        </section>

        <!-- FORM SECTION -->
        <section class="flex items-center justify-center px-6 py-10">
            <div class="w-full max-w-md">

                <!-- MOBILE BRAND -->
                <div class="lg:hidden mb-8 text-center">
                    <div class="mx-auto w-14 h-14 rounded-2xl bg-orange-500 flex items-center justify-center text-white text-2xl mb-3">
                        🛒
                    </div>
                    <h1 class="text-2xl font-extrabold text-gray-900">
                        MY-OLSHOP
                    </h1>
                    <p class="text-sm text-gray-500">
                        Belanja mudah, aman, dan terpercaya
                    </p>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-xl p-8">

                    <div class="mb-7">
                        <h2 class="text-3xl font-extrabold text-gray-900 mb-2">
                            Reset Password
                        </h2>
                        <p class="text-sm text-gray-500 leading-relaxed">
                            Masukkan password baru untuk memulihkan akses akun Anda.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                        @csrf

                        <!-- TOKEN WAJIB ADA -->
                        <input type="hidden" name="token" value="{{ request()->route('token') }}">

                        <!-- EMAIL -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email
                            </label>

                            <input 
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', request()->email) }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Masukkan email akun Anda"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-800 placeholder-gray-400 outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition"
                            >

                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                        </div>

                        <!-- PASSWORD BARU -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password Baru
                            </label>

                            <input 
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Masukkan password baru"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-800 placeholder-gray-400 outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition"
                            >

                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                        </div>

                        <!-- KONFIRMASI PASSWORD -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                Konfirmasi Password
                            </label>

                            <input 
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Ulangi password baru"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-800 placeholder-gray-400 outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition"
                            >

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-sm" />
                        </div>

                        <button 
                            type="submit"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-orange-100 transition">
                            Simpan Password Baru
                        </button>

                        <div class="text-center text-sm text-gray-600">
                            Sudah ingat akun?
                            <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-700 font-bold">
                                Kembali ke login
                            </a>
                        </div>
                    </form>

                </div>

                <p class="text-center text-xs text-gray-400 mt-6">
                    &copy; {{ date('Y') }} MY-OLSHOP. All Rights Reserved.
                </p>

            </div>
        </section>

    </div>

</body>
</html>