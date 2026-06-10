<x-app-layout>
    @php
        $catalogRoute = Route::has('dashboard') ? route('dashboard') : '#';
        $checkoutRoute = Route::has('checkout') ? route('checkout') : '#';

        $subtotal = $items->sum(function ($item) {
            return (float) data_get($item, 'price', 0) * (int) data_get($item, 'quantity', 1);
        });

        $shippingCost = 0;
        $discount = 0;
        $grandTotal = $subtotal + $shippingCost - $discount;
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
                    <div class="w-24 h-24 mx-auto rounded-full bg-orange-50 flex items-center justify-center text-5xl mb-6">
                        🛒
                    </div>

                    <h2 class="text-2xl font-extrabold text-gray-900 mb-3">
                        Keranjang Anda masih kosong
                    </h2>

                    <p class="text-gray-500 mb-8">
                        Silakan pilih produk terlebih dahulu dari katalog belanja.
                    </p>

                    <a href="{{ $catalogRoute }}"
                       class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-orange-500 hover:bg-orange-600 text-white font-extrabold shadow-sm transition">
                        Mulai Belanja
                    </a>
                </div>

            @else

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

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
    $itemImage = data_get($item, 'image_url');

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
                                                        <p class="text-xl font-extrabold text-orange-600">
                                                            Rp {{ number_format($itemTotal, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                {{-- ACTION AREA --}}
                                                <div class="mt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                                                    {{-- QUANTITY --}}
                                                    <div>
                                                        @if($hasUpdateRoute)
                                                            <form action="{{ route('cart.update', $itemId) }}"
                                                                  method="POST"
                                                                  class="flex items-center gap-3">
                                                                @csrf
                                                                @method('PATCH')

                                                                <label class="text-sm font-bold text-gray-600">
                                                                    Jumlah
                                                                </label>

                                                                <input type="number"
                                                                       name="quantity"
                                                                       value="{{ $itemQty }}"
                                                                       min="1"
                                                                       @if(!is_null($itemStock)) max="{{ $itemStock }}" @endif
                                                                       class="w-20 rounded-xl border-gray-300 text-center font-bold focus:border-orange-500 focus:ring-orange-500">

                                                                <button type="submit"
                                                                        class="px-4 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold transition">
                                                                    Update
                                                                </button>
                                                            </form>
                                                        @else
                                                            <div class="flex items-center gap-3">
                                                            <span class="text-sm font-bold text-gray-600">
                                                            Jumlah
                                                            </span>

    <form action="{{ route('cart.update', $itemId) }}" method="POST">
        @csrf
        @method('PATCH')

        <input type="hidden" name="action" value="decrease">

        <button type="submit"
                class="w-9 h-9 rounded-xl border border-gray-300 bg-white hover:bg-orange-50 hover:border-orange-300 hover:text-orange-600 font-bold transition">
            −
        </button>
    </form>

    <div class="min-w-[45px] h-9 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center font-extrabold text-gray-900">
        {{ $itemQty }}
    </div>

    <form action="{{ route('cart.update', $itemId) }}" method="POST">
        @csrf
        @method('PATCH')

        <input type="hidden" name="action" value="increase">

        <button type="submit"
                class="w-9 h-9 rounded-xl border border-gray-300 bg-white hover:bg-orange-50 hover:border-orange-300 hover:text-orange-600 font-bold transition">
            +
        </button>
    </form>
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
                                                                        class="text-sm font-bold text-red-500 hover:text-red-700 transition">
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
                                    Isi data penerima dan metode checkout.
                                </p>
                            </div>

                            <form id="checkoutForm"
                                  action="{{ $checkoutRoute }}"
                                  method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin memproses checkout pesanan ini?')">
                                @csrf

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                    {{-- NAMA --}}
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">
                                            Nama Penerima
                                        </label>
                                        <input type="text"
                                               name="receiver_name"
                                               value="{{ old('receiver_name') }}"
                                               required
                                               placeholder="Contoh: Dadan"
                                               class="w-full rounded-2xl border-gray-300 px-4 py-3 focus:border-orange-500 focus:ring-orange-500">
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
                                               value="{{ old('phone') }}"
                                               required
                                               placeholder="Contoh: 081234567890"
                                               class="w-full rounded-2xl border-gray-300 px-4 py-3 focus:border-orange-500 focus:ring-orange-500">
                                        @error('phone')
                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- SHIPPING --}}
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">
                                            Metode Pengiriman
                                        </label>
                                        <select name="shipping_method"
                                                required
                                                class="w-full rounded-2xl border-gray-300 px-4 py-3 focus:border-orange-500 focus:ring-orange-500">
                                            <option value="">Pilih metode pengiriman</option>
                                            <option value="Ambil di Tempat" {{ old('shipping_method') == 'Ambil di Tempat' ? 'selected' : '' }}>
                                                Ambil di Tempat
                                            </option>
                                            <option value="Kurir Toko" {{ old('shipping_method') == 'Kurir Toko' ? 'selected' : '' }}>
                                                Kurir Toko
                                            </option>
                                            <option value="Jasa Pengiriman" {{ old('shipping_method') == 'Jasa Pengiriman' ? 'selected' : '' }}>
                                                Jasa Pengiriman
                                            </option>
                                        </select>
                                        @error('shipping_method')
                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- PAYMENT --}}
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">
                                            Metode Pembayaran
                                        </label>
                                        <select name="payment_method"
                                                required
                                                class="w-full rounded-2xl border-gray-300 px-4 py-3 focus:border-orange-500 focus:ring-orange-500">
                                            <option value="">Pilih metode pembayaran</option>
                                            <option value="COD" {{ old('payment_method') == 'COD' ? 'selected' : '' }}>
                                                COD / Bayar di Tempat
                                            </option>
                                            <option value="Transfer Bank" {{ old('payment_method') == 'Transfer Bank' ? 'selected' : '' }}>
                                                Transfer Bank
                                            </option>
                                            <option value="QRIS" {{ old('payment_method') == 'QRIS' ? 'selected' : '' }}>
                                                QRIS
                                            </option>
                                        </select>
                                        @error('payment_method')
                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                </div>

                                {{-- ADDRESS --}}
                                <div class="mt-5">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">
                                        Alamat Lengkap Pengiriman
                                    </label>
                                    <textarea name="address"
                                              required
                                              rows="4"
                                              placeholder="Tulis alamat lengkap, nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten..."
                                              class="w-full rounded-2xl border-gray-300 px-4 py-3 focus:border-orange-500 focus:ring-orange-500">{{ old('address') }}</textarea>
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
                                              class="w-full rounded-2xl border-gray-300 px-4 py-3 focus:border-orange-500 focus:ring-orange-500">{{ old('notes') }}</textarea>
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
                                    <span class="font-bold text-gray-900">
                                        Rp {{ number_format($shippingCost, 0, ',', '.') }}
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
                                        <span class="text-2xl font-extrabold text-orange-600 text-right">
                                            Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 rounded-2xl bg-orange-50 border border-orange-100 px-4 py-3">
                                <p class="text-sm text-orange-700 leading-relaxed">
                                    Pesanan akan diproses setelah data pengiriman dan metode pembayaran dilengkapi.
                                </p>
                            </div>

                            <button type="submit"
                                    form="checkoutForm"
                                    class="mt-6 w-full rounded-2xl bg-orange-500 hover:bg-orange-600 text-white font-extrabold py-4 px-5 shadow-sm transition">
                                Buat Pesanan
                            </button>

                            <a href="{{ $catalogRoute }}"
                               class="mt-4 w-full inline-flex justify-center items-center rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-extrabold py-4 px-5 transition">
                                Kembali ke Katalog
                            </a>
                        </div>
                    </div>

                </div>
            @endif

        </div>
    </div>
</x-app-layout>