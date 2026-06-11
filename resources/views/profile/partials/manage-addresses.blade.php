<section class="space-y-6">
    <header>
        <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
            Buku Alamat
        </p>
        <h2 class="mt-1 text-xl font-extrabold text-slate-950">
            Alamat Pengiriman
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Simpan beberapa alamat agar checkout tinggal pilih dari daftar.
        </p>
    </header>

    @php
        $addressStatus = session('status');
        $addressStatusMessage = match ($addressStatus) {
            'address-created' => 'Alamat baru berhasil disimpan.',
            'address-updated' => 'Alamat berhasil diperbarui.',
            'address-deleted' => 'Alamat berhasil dihapus.',
            'address-defaulted' => 'Alamat utama berhasil diganti.',
            default => null,
        };
    @endphp

    @if($addressStatusMessage)
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ $addressStatusMessage }}
        </div>
    @endif

    <div class="rounded-2xl border border-blue-100 bg-blue-50/60 px-4 py-3 text-sm text-blue-700">
        Alamat utama akan otomatis menjadi pilihan awal di keranjang.
    </div>

    <div class="space-y-3">
        @forelse($addresses as $address)
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="truncate text-sm font-extrabold text-slate-950">
                                {{ $address->label }}
                            </h3>

                            @if($address->is_default)
                                <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-extrabold text-blue-700">
                                    Utama
                                </span>
                            @endif
                        </div>

                        <p class="mt-2 text-sm font-semibold text-slate-700">
                            {{ $address->recipient_name }}
                        </p>
                        <p class="text-sm text-slate-500">
                            {{ $address->phone }}
                        </p>
                        <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-600">
                            {{ $address->address_line }}
                        </p>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-2">
                    @unless($address->is_default)
                        <form method="POST" action="{{ route('profile.addresses.default', $address) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-secondary px-3 py-2 text-xs">
                                Jadikan Utama
                            </button>
                        </form>
                    @endunless

                    <details class="w-full">
                        <summary class="btn-secondary inline-flex cursor-pointer px-3 py-2 text-xs">
                            Edit
                        </summary>

                        <form method="POST" action="{{ route('profile.addresses.update', $address) }}" class="mt-4 space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <x-input-label for="label-{{ $address->id }}" :value="__('Label Alamat')" />
                                <x-text-input id="label-{{ $address->id }}" name="label" type="text" class="mt-1 block w-full" :value="$address->label" required />
                                <x-input-error class="mt-2" :messages="$errors->get('label')" />
                            </div>

                            <div>
                                <x-input-label for="recipient_name-{{ $address->id }}" :value="__('Nama Penerima')" />
                                <x-text-input id="recipient_name-{{ $address->id }}" name="recipient_name" type="text" class="mt-1 block w-full" :value="$address->recipient_name" required />
                                <x-input-error class="mt-2" :messages="$errors->get('recipient_name')" />
                            </div>

                            <div>
                                <x-input-label for="phone-{{ $address->id }}" :value="__('Nomor HP')" />
                                <x-text-input id="phone-{{ $address->id }}" name="phone" type="text" class="mt-1 block w-full" :value="$address->phone" required />
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>

                            <div>
                                <x-input-label for="address_line-{{ $address->id }}" :value="__('Alamat Lengkap')" />
                                <textarea id="address_line-{{ $address->id }}"
                                          name="address_line"
                                          rows="4"
                                          class="form-field mt-1 block w-full"
                                          required>{{ $address->address_line }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('address_line')" />
                            </div>

                            <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" name="is_default" value="1" @checked($address->is_default) class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                Jadikan alamat utama
                            </label>

                            <div class="flex flex-wrap gap-2">
                                <x-primary-button>Simpan</x-primary-button>
                            </div>
                        </form>
                    </details>

                    <form method="POST" action="{{ route('profile.addresses.destroy', $address) }}" onsubmit="return confirm('Hapus alamat ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-secondary px-3 py-2 text-xs text-red-600 hover:bg-red-50">
                            Hapus
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center">
                <p class="text-sm font-bold text-slate-900">Belum ada alamat tersimpan</p>
                <p class="mt-1 text-sm text-slate-500">
                    Tambahkan alamat pertama di bawah agar checkout lebih cepat.
                </p>
            </div>
        @endforelse
    </div>

    <div class="border-t border-slate-200 pt-6">
        <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-900">
            Tambah Alamat Baru
        </h3>

        <form action="{{ route('profile.addresses.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf

            <div>
                <x-input-label for="new_label" :value="__('Label Alamat')" />
                <x-text-input id="new_label" name="label" type="text" class="mt-1 block w-full" :value="old('label')" placeholder="Rumah, Kantor, dsb." required />
                <x-input-error class="mt-2" :messages="$errors->get('label')" />
            </div>

            <div>
                <x-input-label for="new_recipient_name" :value="__('Nama Penerima')" />
                <x-text-input id="new_recipient_name" name="recipient_name" type="text" class="mt-1 block w-full" :value="old('recipient_name', $user->name ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('recipient_name')" />
            </div>

            <div>
                <x-input-label for="new_phone" :value="__('Nomor HP')" />
                <x-text-input id="new_phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div>
                <x-input-label for="new_address_line" :value="__('Alamat Lengkap')" />
                <textarea id="new_address_line"
                          name="address_line"
                          rows="4"
                          class="form-field mt-1 block w-full"
                          placeholder="Tulis alamat lengkap penerima..."
                          required>{{ old('address_line') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('address_line')" />
            </div>

            <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                Jadikan alamat utama
            </label>

            <div class="flex flex-wrap gap-2">
                <x-primary-button>Simpan Alamat</x-primary-button>
            </div>
        </form>
    </div>
</section>
