<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SABISHOP</title>
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
                <a href="{{ route('bantuan') }}" class="transition hover:text-blue-900">Bantuan</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
        <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-stretch">
            <section class="panel hidden flex-col justify-between bg-blue-950 p-8 text-white lg:flex">
                <div class="overflow-hidden rounded-2xl border border-white/10">
                    <img
                        src="https://images.unsplash.com/photo-1556740749-887f6717d7e4?q=80&w=1200&auto=format&fit=crop"
                        alt="Bantuan akun"
                        class="h-64 w-full object-cover"
                    >
                </div>

                <div class="mt-8">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-blue-100">Bantuan Akun</p>
                    <h1 class="mt-3 text-4xl font-black leading-tight">
                        Jangan khawatir, akun Anda bisa dipulihkan.
                    </h1>
                    <p class="mt-4 max-w-lg text-sm leading-7 text-blue-100">
                        Masukkan email yang terdaftar, lalu sistem akan mengirimkan tautan untuk membuat password baru.
                    </p>
                </div>

                <div class="mt-8 grid grid-cols-3 gap-3">
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-sm font-black">Aman</p>
                        <p class="mt-1 text-xs text-blue-100">Verifikasi email.</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-sm font-black">Cepat</p>
                        <p class="mt-1 text-xs text-blue-100">Link dikirim segera.</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-sm font-black">Mudah</p>
                        <p class="mt-1 text-xs text-blue-100">Reset tanpa ribet.</p>
                    </div>
                </div>
            </section>

            <section class="panel flex items-center p-8 sm:p-10">
                <div class="w-full max-w-md mx-auto">
                    <div class="mb-8">
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-blue-900">Pemulihan Akun</p>
                        <h2 class="mt-3 text-3xl font-black text-slate-950">
                            Lupa Password?
                        </h2>
                        <p class="mt-3 text-sm leading-7 text-slate-500">
                            Masukkan email akun Anda. Kami akan mengirimkan link untuk mengatur ulang password.
                        </p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                                Email
                            </label>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                placeholder="Masukkan email terdaftar"
                                class="form-field w-full border-slate-200 bg-slate-50 px-4 py-3"
                            >

                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                        </div>

                        <button type="submit" class="btn-primary w-full">
                            Kirim Link Reset Password
                        </button>

                        <p class="pt-2 text-center text-sm text-slate-600">
                            Ingat password?
                            <a href="{{ route('login') }}" class="font-bold text-blue-900 transition hover:text-blue-950">
                                Kembali ke login
                            </a>
                        </p>
                    </form>
                </div>
            </section>
        </div>
    </main>

    <footer class="py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} SABISHOP. All Rights Reserved.
    </footer>
</body>
</html>
