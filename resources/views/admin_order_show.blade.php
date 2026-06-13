<x-app-layout>
    <x-slot name="header">
        <div class="bg-white border border-gray-100 rounded-2xl px-6 py-5 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-bold text-blue-600 uppercase tracking-wider">
                        Detail Pesanan
                    </p>
                    <h2 class="font-extrabold text-2xl text-gray-900 leading-tight">
                        Order #{{ $order->id }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $statusLabel }} - {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('orders.invoice', $order->id) }}"
                       class="btn-secondary inline-flex items-center justify-center px-5 py-3 text-sm">
                        Invoice
                    </a>
                    <a href="{{ route('admin.orders.index') }}"
                       class="btn-secondary inline-flex items-center justify-center px-5 py-3 text-sm">
                        ⬅️ Kembali ke Daftar
                    </a>
                    <a href="{{ route('admin.dashboard') }}"
                       class="btn-primary inline-flex items-center justify-center px-5 py-3 text-sm">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    @php
        $statusClass = 'bg-gray-100 text-gray-700';

        if ($order->status === 'Pesanan diterima') {
            $statusClass = 'bg-blue-100 text-blue-700';
        } elseif ($order->status === 'Sedang diproses') {
            $statusClass = 'bg-yellow-100 text-yellow-700';
        } elseif ($order->status === 'Sedang dikirim') {
            $statusClass = 'bg-sky-100 text-sky-700';
        } elseif ($order->status === 'Sampai tujuan') {
            $statusClass = 'bg-emerald-100 text-emerald-700';
        } elseif ($order->status === 'Pesanan selesai') {
            $statusClass = 'bg-green-100 text-green-700';
        }

        $statusLabels = [
            'Pesanan diterima' => 'Menunggu diproses',
            'Sedang diproses' => 'Diproses',
            'Sedang dikirim' => 'Dikirim',
            'Sampai tujuan' => 'Sudah sampai',
            'Pesanan selesai' => 'Selesai',
        ];

        $paymentStatusLabels = [
            'Menunggu Pembayaran' => 'Menunggu Pembayaran',
            'Menunggu Verifikasi' => 'Menunggu Verifikasi',
            'Dibayar' => 'Dibayar',
            'Ditolak' => 'Ditolak',
            'Refund' => 'Refund',
            'Bayar Saat Diterima' => 'Bayar Saat Diterima',
        ];

        $paymentStatusClass = 'bg-gray-100 text-gray-700';

        if (($order->payment_status ?? null) === 'Dibayar') {
            $paymentStatusClass = 'bg-green-100 text-green-700';
        } elseif (($order->payment_status ?? null) === 'Menunggu Verifikasi') {
            $paymentStatusClass = 'bg-yellow-100 text-yellow-700';
        } elseif (($order->payment_status ?? null) === 'Ditolak') {
            $paymentStatusClass = 'bg-red-100 text-red-700';
        } elseif (($order->payment_status ?? null) === 'Bayar Saat Diterima') {
            $paymentStatusClass = 'bg-sky-100 text-sky-700';
        } elseif (($order->payment_status ?? null) === 'Menunggu Pembayaran') {
            $paymentStatusClass = 'bg-orange-100 text-orange-700';
        }
    @endphp

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if(session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-5">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Status Pengiriman</p>
                    <span class="mt-2 inline-flex {{ $statusClass }} text-xs font-bold px-3 py-1 rounded-full">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Status Pembayaran</p>
                    <span class="mt-2 inline-flex {{ $paymentStatusClass }} text-xs font-bold px-3 py-1 rounded-full">
                        {{ $paymentStatusLabel }}
                    </span>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Pembeli</p>
                    <h3 class="text-xl font-extrabold text-gray-900 mt-1">{{ $order->buyer_name }}</h3>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Total Bayar</p>
                    <h3 class="text-2xl font-extrabold text-blue-600 mt-1">
                        Rp {{ number_format((float) $order->total_price, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">
                <section class="xl:col-span-2 space-y-8">
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                        <div class="flex items-start justify-between gap-4 mb-6">
                            <div>
                                <h3 class="text-xl font-extrabold text-gray-900">Info Pesanan</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Detail penerima, alamat, pengiriman, dan pembayaran.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                            <div class="rounded-2xl bg-gray-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Penerima</p>
                                <p class="mt-2 font-bold text-gray-900">{{ $order->receiver_name ?? '-' }}</p>
                                <p class="text-gray-500">{{ $order->phone ?? '-' }}</p>
                                @if(!empty($order->address_label))
                                    <p class="mt-3 text-xs font-bold uppercase tracking-wider text-blue-600">
                                        {{ $order->address_label }}
                                    </p>
                                @endif
                            </div>

                            <div class="rounded-2xl bg-gray-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Pengiriman</p>
                                <p class="mt-2 font-bold text-gray-900">{{ $order->shipping_method ?? '-' }}</p>
                                <p class="mt-4 text-xs font-bold uppercase tracking-wider text-gray-400">Pembayaran</p>
                                <p class="mt-2 font-bold text-gray-900">{{ $order->payment_method ?? '-' }}</p>
                                <p class="mt-4 text-xs font-bold uppercase tracking-wider text-gray-400">Nomor Resi</p>
                                <p class="mt-2 font-bold text-gray-900">{{ $order->tracking_number ?? '-' }}</p>
                                <p class="mt-4 text-xs font-bold uppercase tracking-wider text-gray-400">Kurir</p>
                                <p class="mt-2 font-bold text-gray-900">{{ $order->courier_name ?? '-' }}</p>
                                @if(!empty($order->tracking_url))
                                    <a href="{{ $order->tracking_url }}" target="_blank" class="mt-2 inline-flex text-sm font-semibold text-blue-600 hover:text-blue-800">
                                        Lihat Tracking
                                    </a>
                                @endif
                            </div>

                            <div class="md:col-span-2 rounded-2xl bg-gray-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Alamat</p>
                                <p class="mt-2 leading-relaxed text-gray-700">{{ $order->address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-xl font-extrabold text-gray-900">Item Pesanan</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Daftar produk yang dibeli di transaksi ini.
                            </p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-500">
                                    <tr>
                                        <th class="text-left px-6 py-4 font-bold">Produk</th>
                                        <th class="text-right px-6 py-4 font-bold">Qty</th>
                                        <th class="text-right px-6 py-4 font-bold">Harga</th>
                                        <th class="text-right px-6 py-4 font-bold">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($order->items ?? [] as $item)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-gray-900">{{ $item->product_name }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-right text-gray-700">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 text-right text-gray-700">
                                                Rp {{ number_format((float) $item->price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-extrabold text-blue-600">
                                                Rp {{ number_format((float) ($item->price * $item->quantity), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                                Tidak ada item pesanan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <aside class="space-y-8">
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-xl font-extrabold text-gray-900 mb-5">Update Pengiriman</h3>

                        <form action="{{ route('admin.order.shipping', $order->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status Pengiriman
                                </label>

                                <select name="status"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                    @foreach($statusLabels as $value => $label)
                                        <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kurir
                                </label>
                                <input type="text"
                                       name="courier_name"
                                       value="{{ $order->courier_name ?? '' }}"
                                       placeholder="Contoh: JNE, J&T, Kurir Toko"
                                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nomor Resi
                                </label>
                                <input type="text"
                                       name="tracking_number"
                                       value="{{ $order->tracking_number ?? '' }}"
                                       placeholder="Masukkan nomor resi"
                                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Link Tracking
                                </label>
                                <input type="url"
                                       name="tracking_url"
                                       value="{{ $order->tracking_url ?? '' }}"
                                       placeholder="https://..."
                                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            </div>

                            <button type="submit" class="btn-primary w-full py-3">
                                Simpan Pengiriman
                            </button>
                        </form>
                    </div>

                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-xl font-extrabold text-gray-900 mb-5">Update Pembayaran</h3>

                        <form action="{{ route('admin.order.payment-status', $order->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status Pembayaran
                                </label>

                                <select name="payment_status"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                    @foreach($paymentStatuses as $value)
                                        <option value="{{ $value }}" {{ ($order->payment_status ?? '') === $value ? 'selected' : '' }}>
                                            {{ $paymentStatusLabels[$value] ?? $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Alasan Ditolak / Refund
                                </label>
                                <textarea name="payment_rejected_reason"
                                          rows="3"
                                          placeholder="Opsional"
                                          class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">{{ $order->payment_rejected_reason ?? '' }}</textarea>
                            </div>

                            <button type="submit" class="btn-primary w-full py-3">
                                Simpan Pembayaran
                            </button>
                        </form>
                    </div>

                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-xl font-extrabold text-gray-900 mb-4">Bukti Pembayaran</h3>

                        @if(!empty($order->payment_proof_path))
                            <a href="{{ asset($order->payment_proof_path) }}" target="_blank">
                                <img src="{{ asset($order->payment_proof_path) }}" alt="Bukti pembayaran" class="w-full rounded-2xl border border-gray-200 object-cover">
                            </a>
                        @else
                            <div class="rounded-2xl bg-gray-50 p-5 text-sm text-gray-500">
                                Belum ada bukti pembayaran diunggah.
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-xl font-extrabold text-gray-900 mb-4">Ringkasan Biaya</h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between gap-3 text-gray-600">
                                <span>Subtotal</span>
                                <span class="font-bold text-gray-900">
                                    Rp {{ number_format((float) ($order->subtotal_price ?? $order->total_price), 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between gap-3 text-gray-600">
                                <span>Ongkir</span>
                                <span class="font-bold text-gray-900">
                                    Rp {{ number_format((float) ($order->shipping_cost ?? 0), 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between gap-3 text-gray-600">
                                <span>Biaya bayar</span>
                                <span class="font-bold text-gray-900">
                                    Rp {{ number_format((float) ($order->payment_fee ?? 0), 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="border-t border-gray-100 pt-3 flex justify-between gap-3">
                                <span class="font-bold text-gray-900">Total</span>
                                <span class="font-extrabold text-blue-600">
                                    Rp {{ number_format((float) $order->total_price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
