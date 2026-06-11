<nav x-data="{ open: false }" class="sticky top-0 z-50 border-t-4 border-t-blue-950 border-b border-slate-200 bg-white/95 backdrop-blur">
    @php
        $user = Auth::user();
        $isAdmin = $user && $user->role === 'admin';
    @endphp

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">
            <div class="flex min-w-0 items-center gap-8">
                <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-3">
                    <span class="brand-mark">SB</span>
                    <span class="hidden min-w-0 sm:block">
                        <span class="block text-sm font-black tracking-wide text-slate-950">SABISHOP</span>
                        <span class="block truncate text-xs font-medium text-slate-500">Platform belanja online terpercaya</span>
                    </span>
                </a>

                <div class="hidden items-center gap-1 md:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Katalog
                    </x-nav-link>

                    <x-nav-link :href="route('cart.view')" :active="request()->routeIs('cart.view')">
                        Keranjang
                    </x-nav-link>

                    <x-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.history')">
                        Pesanan
                    </x-nav-link>

                    @if($isAdmin)
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            Admin
                        </x-nav-link>
                        <x-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')">
                            Laporan
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden items-center gap-3 sm:flex">
                @if($isAdmin && !request()->routeIs('admin.*'))
                    <a href="{{ route('admin.dashboard') }}" class="btn-primary px-4 py-2 text-sm">
                        Panel Admin
                    </a>
                @endif

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-bold text-slate-700 shadow-sm transition hover:border-blue-200 hover:text-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-100">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-black text-slate-700">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                            <span class="max-w-32 truncate">{{ $user->name }}</span>
                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profil Saya
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <button @click="open = ! open" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-blue-100 sm:hidden">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-slate-200 bg-white sm:hidden">
        <div class="space-y-1 px-4 py-3">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Katalog</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart.view')" :active="request()->routeIs('cart.view')">Keranjang</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.history')">Pesanan</x-responsive-nav-link>
            @if($isAdmin)
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Admin</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')">Laporan</x-responsive-nav-link>
            @endif
        </div>

        <div class="border-t border-slate-200 px-4 py-4">
            <p class="font-bold text-slate-900">{{ $user->name }}</p>
            <p class="text-sm text-slate-500">{{ $user->email }}</p>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">Profil Saya</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        Keluar
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
