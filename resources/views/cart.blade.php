<x-app-layout>
    @php
        $catalogRoute = Route::has('dashboard') ? route('dashboard') : '#';
        $checkoutRoute = Route::has('checkout') ? route('checkout') : '#';
        $savedAddresses = collect($addresses ?? []);
        $defaultAddress = $savedAddresses->firstWhere('is_default', true) ?? $savedAddresses->first();
        $currentUser = auth()->user();

        $subtotal = $items->sum(function ($item) {
            return (float) data_get($item, 'price', 0) * (int) data_get($item, 'quantity', 1);
        });

        $shippingMethods = collect($shippingOptions ?? []);
        $paymentMethods = collect($paymentOptions ?? []);
        $oldShippingMethod = old('shipping_method', $shippingMethods->keys()->first());
        $oldPaymentMethod = old('payment_method', $paymentMethods->keys()->first());
        $shippingCost = (int) data_get($shippingMethods->get($oldShippingMethod), 'cost', 0);
        $paymentFee = (int) data_get($paymentMethods->get($oldPaymentMethod), 'fee', 0);
        $discount = 0;
        $grandTotal = $subtotal + $shippingCost + $paymentFee - $discount;

        $oldShippingAddressId = old('shipping_address_id');
        $initialSelectedAddress = $defaultAddress;
        $matchedOldAddress = null;

        if ($oldShippingAddressId !== null && $oldShippingAddressId !== '' && $oldShippingAddressId !== 'manual') {
            $matchedOldAddress = $savedAddresses->firstWhere('id', (string) $oldShippingAddressId)
                ?? $savedAddresses->firstWhere('id', (int) $oldShippingAddressId)
                ?? $defaultAddress;
        }

        $initialShippingAddressId = $oldShippingAddressId !== null
            ? ($oldShippingAddressId !== '' ? ((string) ($matchedOldAddress?->id ?? $defaultAddress?->id ?? 'manual')) : 'manual')
            : ($defaultAddress ? (string) $defaultAddress->id : 'manual');
        $initialSelectedAddress = $matchedOldAddress ?? $defaultAddress;
        $initialReceiverName = old('receiver_name', $initialSelectedAddress->recipient_name ?? $currentUser->name ?? '');
        $initialPhone = old('phone', $initialSelectedAddress->phone ?? $currentUser->phone ?? '');
        $initialAddress = old('address', $initialSelectedAddress->address_line ?? '');
    @endphp

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- TOP BAR --}}
            <div>
    <h1 class="text-3xl font-extrabold text-gray-900">
        Keranjang Belanja
    </h1>

    <p class="text-gray-500 mt-2">
        Periksa produk dan lengkapi data checkout sebelum membuat pesanan.
    </p>
    </div>

            {{-- ALERT --}}
            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700 font-medium">
                    {{ session('error') }}
                </div>
            @endif

            @if($items->isEmpty())

                {{-- EMPTY CART --}}
                <div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-12 text-center">
                    <div class="w-24 h-24 mx-auto rounded-full bg-blue-50 flex items-center justify-center text-5xl mb-6">
                        🛒
                    </div>

                    <h2 class="text-2xl font-extrabold text-gray-900 mb-3">
                        Keranjang Anda masih kosong
                    </h2>

                    <p class="text-gray-500 mb-8">
                        Silakan pilih produk terlebih dahulu dari katalog belanja.
                    </p>

                    <a href="{{ $catalogRoute }}"
                       class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-blue-500 hover:bg-blue-600 text-white font-extrabold shadow-sm transition">
                        Mulai Belanja
                    </a>
                </div>

            @else

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8"
                     x-data="{
                         subtotal: @js((int) $subtotal),
                         discount: @js((int) $discount),
                         selectedShipping: @js($oldShippingMethod),
                         selectedPayment: @js($oldPaymentMethod),
                         shippingOptions: @js($shippingMethods),
                         paymentOptions: @js($paymentMethods),
                         selectedAddressId: @js($initialShippingAddressId),
                         addresses: @js($savedAddresses->map(function ($address) {
                             return [
                                 'id' => (string) $address->id,
                                 'label' => $address->label,
                                 'recipient_name' => $address->recipient_name,
                                 'phone' => $address->phone,
                                 'address_line' => $address->address_line,
                                 'is_default' => (bool) $address->is_default,
                             ];
                         })->values()),
                         manualDefaults: {
                             receiver_name: @js($initialReceiverName),
                             phone: @js($initialPhone),
                             address: @js($initialAddress),
                         },
                         fields: {
                             receiver_name: @js($initialReceiverName),
                             phone: @js($initialPhone),
                             address: @js($initialAddress),
                         },
                         get selectedAddress() {
                             return this.addresses.find((address) => String(address.id) === String(this.selectedAddressId)) || null;
                         },
                         get shippingCost() {
                             return Number(this.shippingOptions[this.selectedShipping]?.cost || 0);
                         },
                         get paymentFee() {
                             return Number(this.paymentOptions[this.selectedPayment]?.fee || 0);
                         },
                         get grandTotal() {
                             return this.subtotal + this.shippingCost + this.paymentFee - this.discount;
                         },
                         rupiah(value) {
                             return new Intl.NumberFormat('id-ID', {
                                 style: 'currency',
                                 currency: 'IDR',
                                 maximumFractionDigits: 0,
                             }).format(Number(value || 0));
                         },
                         syncAddress() {
                             if (this.selectedAddressId === 'manual' || ! this.selectedAddressId) {
                                 this.fields = { ...this.manualDefaults };
                                 return;
                             }

                             const selected = this.selectedAddress;

                             if (selected) {
                                 this.fields = {
                                     receiver_name: selected.recipient_name,
                                     phone: selected.phone,
                                     address: selected.address_line,
                                 };
                             }
                         },
                         useManual() {
                             this.selectedAddressId = 'manual';
                             this.fields = { ...this.manualDefaults };
                         },
                     }"
                     x-init="syncAddress()">

                    {{-- LEFT CONTENT --}}
                    <div class="lg:col-span-8 space-y-6">

                        {{-- PRODUCT CARD --}}
                        <div class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-gray-100">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <h2 class="text-xl font-extrabold text-gray-900">
                                            Produk di Keranjang
                                        </h2>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $items->count() }} produk siap diproses.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="divide-y divide-gray-100">
                                @foreach($items as $item)
                                    @php
    $itemId = data_get($item, 'id') ?? data_get($item, 'product_id') ?? $loop->index;

    $itemName = data_get($item, 'name') ?? 'Nama Produk Tidak Tersedia';
    $itemPrice = (float) (data_get($item, 'price') ?? 0);
    $itemQty = (int) data_get($item, 'quantity', 1);
    $itemStock = data_get($item, 'stock');
    $itemImage = data_get($item, 'image_url') ?? data_get($item, 'image');

    $itemTotal = $itemPrice * $itemQty;

    $hasUpdateRoute = Route::has('cart.update');
    $hasRemoveRoute = Route::has('cart.remove');

    $imageUrl = null;

    if (!empty($itemImage)) {
        $itemImage = trim($itemImage);

        if (\Illuminate\Support\Str::startsWith($itemImage, ['http://', 'https://'])) {
            $imageUrl = $itemImage;
        } elseif (\Illuminate\Support\Str::startsWith($itemImage, ['/storage/'])) {
            $imageUrl = asset(ltrim($itemImage, '/'));
        } elseif (\Illuminate\Support\Str::startsWith($itemImage, ['storage/'])) {
            $imageUrl = asset($itemImage);
        } elseif (\Illuminate\Support\Str::startsWith($itemImage, ['/images/', 'images/', '/assets/', 'assets/', '/uploads/', 'uploads/'])) {
            $imageUrl = asset(ltrim($itemImage, '/'));
        } else {
            $imageUrl = asset('storage/' . $itemImage);
           
        }
    }
@endphp

                                    <div class="p-6">
                                        <div class="flex flex-col sm:flex-row gap-5">

                                            {{-- IMAGE --}}
                                            <div class="w-full sm:w-28 h-28 rounded-2xl bg-gray-50 border border-gray-200 overflow-hidden flex items-center justify-center shrink-0">
                                                @if($imageUrl)
                                                    <img src="{{ $imageUrl }}"
                                                         alt="{{ $itemName }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="text-4xl">
                                                        🛍️
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- PRODUCT INFO --}}
                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                                    <div class="min-w-0">
                                                        <h3 class="text-lg font-extrabold text-gray-900 leading-snug">
                                                            {{ $itemName }}
                                                        </h3>

                                                        <p class="text-sm text-gray-500 mt-2">
                                                            Harga satuan:
                                                            <span class="font-bold text-gray-800">
                                                                Rp {{ number_format($itemPrice, 0, ',', '.') }}
                                                            </span>
                                                        </p>

                                                        @if(!is_null($itemStock))
                                                            <p class="text-sm mt-1 {{ $itemStock >= $itemQty ? 'text-green-600' : 'text-red-600' }}">
                                                                @if($itemStock >= $itemQty)
                                                                    Stok tersedia: {{ $itemStock }}
                                                                @else
                                                                    Stok tidak mencukupi. Stok tersedia hanya {{ $itemStock }}.
                                                                @endif
                                                            </p>
                                                        @endif
                                                    </div>

                                                    <div class="md:text-right shrink-0">
                                                        <p class="text-sm text-gray-500">
                                                            Total
                                                        </p>
                                                        <p class="text-xl font-extrabold text-blue-600">
                                                            Rp {{ number_format($itemTotal, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                {{-- ACTION AREA --}}
                                                <div class="mt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                                                    {{-- QUANTITY --}}
                                                    <div class="flex items-center gap-3">
                                                        <span class="text-sm font-bold text-gray-600">
                                                            Jumlah
                                                        </span>

                                                        @if($hasUpdateRoute)
                                                            <div class="inline-flex items-center rounded-2xl border border-gray-200 bg-gray-50 p-1 shadow-sm">
                                                                <form action="{{ route('cart.update', $itemId) }}" method="POST">
                                                                    @csrf
                                                                    @method('PATCH')

                                                                    <input type="hidden" name="action" value="decrease">

                                                                <button type="submit"
                                                                        @disabled($itemQty <= 1)
                                                                        aria-label="Kurangi jumlah {{ $itemName }}"
                                                                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-transparent bg-blue-600 text-lg font-black text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-gray-300 disabled:text-gray-200">
                                                                    -
                                                                    </button>
                                                                </form>

                                                                <div class="flex h-10 min-w-[52px] items-center justify-center px-3 text-center text-base font-extrabold text-gray-950">
                                                                    {{ $itemQty }}
                                                                </div>

                                                                <form action="{{ route('cart.update', $itemId) }}" method="POST">
                                                                    @csrf
                                                                    @method('PATCH')

                                                                    <input type="hidden" name="action" value="increase">

                                                                <button type="submit"
                                                                        @disabled(!is_null($itemStock) && $itemQty >= $itemStock)
                                                                        aria-label="Tambah jumlah {{ $itemName }}"
                                                                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-transparent bg-blue-600 text-lg font-black text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-gray-300">
                                                                    +
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @else
                                                            <div class="rounded-xl bg-gray-100 px-4 py-2 text-sm font-extrabold text-gray-800">
                                                                {{ $itemQty }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- REMOVE --}}
                                                    <div>
                                                        @if($hasRemoveRoute)
                                                            <form action="{{ route('cart.remove', $itemId) }}"
                                                                  method="POST"
                                                                  onsubmit="return confirm('Yakin ingin menghapus produk ini dari keranjang?')">
                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit"
                                                                        class="inline-flex items-center justify-center rounded-xl border border-blue-100 bg-white px-4 py-2 text-sm font-extrabold text-blue-950 transition hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-100">
                                                                    Hapus Produk
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- CHECKOUT FORM --}}
                        <div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-6">
                            <div class="mb-6">
                                <h2 class="text-xl font-extrabold text-gray-900">
                                    Informasi Pengiriman
                                </h2>
                                <p class="text-sm text-gray-500 mt-1">
                                    Pilih alamat tersimpan atau gunakan alamat baru untuk pesanan ini.
                                </p>
                            </div>

                            <form id="checkoutForm"
                                  action="{{ $checkoutRoute }}"
                                  method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin memproses checkout pesanan ini?')">
                                @csrf

                                <input type="hidden" name="shipping_address_id" :value="selectedAddressId === 'manual' ? '' : selectedAddressId">

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">
                                            Alamat Tersimpan
                                        </label>
                                        <select x-model="selectedAddressId"
                                                @change="syncAddress()"
                                                class="w-full rounded-2xl border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                                            <option value="manual">Gunakan alamat baru</option>
                                            @foreach($savedAddresses as $address)
                                                <option value="{{ $address->id }}">
                                                    {{ $address->label }}
                                                    - {{ $address->recipient_name }}
                                                    {{ $address->is_default ? '(Utama)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($savedAddresses->isEmpty())
                                            <p class="mt-2 text-xs text-slate-500">
                                                Belum ada alamat tersimpan. Tambahkan dari halaman profil.
                                            </p>
                                        @else
                                            <p class="mt-2 text-xs text-slate-500">
                                                Ubah alamat di profil jika ingin menyimpan perubahan secara permanen.
                                            </p>
                                        @endif
                                    </div>

                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                        <template x-if="selectedAddressId === 'manual'">
                                            <div>
                                                <p class="text-sm font-extrabold text-slate-900">Alamat baru</p>
                                                <p class="mt-1 text-sm text-slate-500">
                                                    Isi detail pengiriman di bawah ini untuk pesanan saat ini.
                                                </p>
                                            </div>
                                        </template>

                                        <template x-if="selectedAddressId !== 'manual'">
                                            <div>
                                                <p class="text-sm font-extrabold text-slate-900" x-text="selectedAddress?.label"></p>
                                                <p class="mt-1 text-sm text-slate-700" x-text="selectedAddress?.recipient_name"></p>
                                                <p class="text-sm text-slate-500" x-text="selectedAddress?.phone"></p>
                                                <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-600" x-text="selectedAddress?.address_line"></p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                    {{-- NAMA --}}
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">
                                            Nama Penerima
                                        </label>
                                        <input type="text"
                                               name="receiver_name"
                                               x-model="fields.receiver_name"
                                               value="{{ $initialReceiverName }}"
                                               :readonly="selectedAddressId !== 'manual'"
                                               required
                                               placeholder="Contoh: Dadan"
                                               class="w-full rounded-2xl border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                                               :class="selectedAddressId !== 'manual' ? 'bg-slate-50 text-slate-600' : 'bg-white'">
                                        @error('receiver_name')
                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- PHONE --}}
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">
                                            Nomor HP
                                        </label>
                                        <input type="text"
                                               name="phone"
                                               x-model="fields.phone"
                                               value="{{ $initialPhone }}"
                                               :readonly="selectedAddressId !== 'manual'"
                                               required
                                               placeholder="Contoh: 081234567890"
                                               class="w-full rounded-2xl border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                                               :class="selectedAddressId !== 'manual' ? 'bg-slate-50 text-slate-600' : 'bg-white'">
                                        @error('phone')
                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                </div>

                                {{-- SHIPPING --}}
                                <div class="mt-6">
                                    <div class="mb-3 flex items-end justify-between gap-4">
                                        <div>
                                            <h3 class="text-sm font-extrabold text-gray-900">
                                                Metode Pengiriman
                                            </h3>
                                            <p class="mt-1 text-xs text-gray-500">
                                                Pilih layanan yang sesuai dengan kebutuhan pesanan.
                                            </p>
                                        </div>
                                        <p class="text-sm font-extrabold text-blue-600" x-text="rupiah(shippingCost)"></p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach($shippingMethods as $key => $option)
                                            <label class="relative cursor-pointer rounded-2xl border p-4 transition"
                                                   :class="selectedShipping === @js($key) ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-100' : 'border-gray-200 bg-white hover:border-blue-200'">
                                                <input type="radio"
                                                       name="shipping_method"
                                                       value="{{ $key }}"
                                                       x-model="selectedShipping"
                                                       required
                                                       class="sr-only">
                                                <div class="flex items-start justify-between gap-4">
                                                    <div>
                                                        <p class="font-extrabold text-gray-900">
                                                            {{ $option['label'] }}
                                                        </p>
                                                        <p class="mt-1 text-sm leading-relaxed text-gray-500">
                                                            {{ $option['description'] }}
                                                        </p>
                                                        <p class="mt-2 text-xs font-bold uppercase tracking-wide text-gray-400">
                                                            {{ $option['eta'] }}
                                                        </p>
                                                    </div>
                                                    <span class="shrink-0 text-sm font-extrabold text-gray-900">
                                                        {{ $option['cost'] > 0 ? 'Rp ' . number_format($option['cost'], 0, ',', '.') : 'Gratis' }}
                                                    </span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                    @error('shipping_method')
                                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- PAYMENT --}}
                                <div class="mt-6">
                                    <div class="mb-3 flex items-end justify-between gap-4">
                                        <div>
                                            <h3 class="text-sm font-extrabold text-gray-900">
                                                Metode Pembayaran
                                            </h3>
                                            <p class="mt-1 text-xs text-gray-500">
                                                Total akan menyesuaikan jika ada biaya layanan.
                                            </p>
                                        </div>
                                        <p class="text-sm font-extrabold text-blue-600" x-text="paymentFee > 0 ? rupiah(paymentFee) : 'Tanpa biaya'"></p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        @foreach($paymentMethods as $key => $option)
                                            <label class="relative cursor-pointer rounded-2xl border p-4 transition"
                                                   :class="selectedPayment === @js($key) ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-100' : 'border-gray-200 bg-white hover:border-blue-200'">
                                                <input type="radio"
                                                       name="payment_method"
                                                       value="{{ $key }}"
                                                       x-model="selectedPayment"
                                                       required
                                                       class="sr-only">
                                                <p class="font-extrabold text-gray-900">
                                                    {{ $option['label'] }}
                                                </p>
                                                <p class="mt-1 text-sm leading-relaxed text-gray-500">
                                                    {{ $option['description'] }}
                                                </p>
                                                <p class="mt-3 text-sm font-extrabold text-gray-900">
                                                    {{ $option['fee'] > 0 ? '+ Rp ' . number_format($option['fee'], 0, ',', '.') : 'Biaya layanan gratis' }}
                                                </p>
                                            </label>
                                        @endforeach
                                    </div>

                                    @error('payment_method')
                                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- ADDRESS --}}
                                <div class="mt-5">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">
                                            Alamat Lengkap Pengiriman
                                        </label>
                                        <textarea name="address"
                                              x-model="fields.address"
                                              :readonly="selectedAddressId !== 'manual'"
                                              required
                                              rows="4"
                                              placeholder="Tulis alamat lengkap, nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten..."
                                              class="w-full rounded-2xl border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                                              :class="selectedAddressId !== 'manual' ? 'bg-slate-50 text-slate-600' : 'bg-white'">{{ $initialAddress }}</textarea>
                                        @error('address')
                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                {{-- NOTES --}}
                                <div class="mt-5">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">
                                        Catatan untuk Penjual
                                        <span class="text-gray-400 font-normal">(Opsional)</span>
                                    </label>
                                    <textarea name="notes"
                                              rows="3"
                                              placeholder="Contoh: Tolong dikemas rapi, kirim sore hari, atau catatan tambahan lainnya..."
                                              class="w-full rounded-2xl border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </form>
                        </div>

                    </div>

                    {{-- RIGHT SUMMARY --}}
                    <div class="lg:col-span-4">
                        <div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-6 sticky top-6">
                            <h2 class="text-xl font-extrabold text-gray-900 mb-6">
                                Ringkasan Pesanan
                            </h2>

                            <div class="space-y-4">
                                <div class="flex justify-between gap-4 text-gray-600">
                                    <span>Subtotal</span>
                                    <span class="font-bold text-gray-900">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-4 text-gray-600">
                                    <span>Ongkos Kirim</span>
                                    <span class="font-bold text-gray-900" x-text="shippingCost > 0 ? rupiah(shippingCost) : 'Gratis'">
                                        {{ $shippingCost > 0 ? 'Rp ' . number_format($shippingCost, 0, ',', '.') : 'Gratis' }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-4 text-gray-600">
                                    <span>Biaya Pembayaran</span>
                                    <span class="font-bold text-gray-900" x-text="paymentFee > 0 ? rupiah(paymentFee) : 'Gratis'">
                                        {{ $paymentFee > 0 ? 'Rp ' . number_format($paymentFee, 0, ',', '.') : 'Gratis' }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-4 text-gray-600">
                                    <span>Diskon</span>
                                    <span class="font-bold text-gray-900">
                                        Rp {{ number_format($discount, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="border-t border-gray-100 pt-5">
                                    <div class="flex justify-between gap-4 items-start">
                                        <span class="font-extrabold text-gray-900">
                                            Total Pembayaran
                                        </span>
                                        <span class="text-2xl font-extrabold text-blue-600 text-right" x-text="rupiah(grandTotal)">
                                            Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 rounded-2xl bg-blue-50 border border-blue-100 px-4 py-3">
                                <p class="text-sm text-blue-700 leading-relaxed">
                                    Pesanan akan diproses setelah admin mengonfirmasi pembayaran dan ketersediaan pengiriman.
                                </p>
                            </div>

                            <button type="submit"
                                    form="checkoutForm"
                                    class="mt-6 w-full rounded-2xl bg-blue-950 hover:bg-blue-900 text-white font-extrabold py-4 px-5 shadow-sm transition focus:outline-none focus:ring-4 focus:ring-blue-100">
                                Buat Pesanan
                            </button>

                            <a href="{{ $catalogRoute }}"
                               class="mt-4 w-full inline-flex justify-center items-center rounded-2xl border border-blue-100 bg-white text-blue-950 font-extrabold py-4 px-5 transition hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-100">
                                Kembali ke Katalog
                            </a>
                        </div>
                    </div>

                </div>
            @endif

        </div>
    </div>
</x-app-layout>
