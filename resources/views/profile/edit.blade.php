<x-app-layout>
    <x-slot name="header">
        <div class="panel px-6 py-5">
            <p class="text-sm font-bold uppercase tracking-wider text-blue-600">Profil Akun</p>
            <h2 class="mt-1 text-2xl font-extrabold text-slate-950">
                Kelola data akun dan alamat pengiriman
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Simpan nomor HP dan beberapa alamat agar checkout lebih cepat.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    <div class="panel p-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <div class="panel p-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="panel p-6">
                        @include('profile.partials.manage-addresses', ['addresses' => $addresses])
                    </div>

                    <div class="panel p-6">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
