<x-app-layout>
    <x-slot name="header">
        <div class="bg-white border border-gray-100 rounded-2xl px-6 py-5 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-bold text-blue-600 uppercase tracking-wider">
                        Pesanan Admin
                    </p>
                    <h2 class="font-extrabold text-2xl text-gray-900 leading-tight">
                        Kelola Pesanan & Tracking
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Pusat kontrol pesanan untuk filter, pencarian, dan update status.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('admin.dashboard') }}"
                       class="btn-secondary inline-flex items-center justify-center px-5 py-3 text-sm">
                        ⬅️ Dashboard
                    </a>

                    <a href="{{ route('admin.reports') }}"
                       class="btn-primary inline-flex items-center justify-center px-5 py-3 text-sm">
                        📊 Laporan Penjualan
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    @php
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

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-5">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Total Pesanan</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 mt-1">
                        {{ (int) ($summary->total_orders ?? 0) }}
                    </h3>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Menunggu Diproses</p>
                    <h3 class="text-3xl font-extrabold text-blue-600 mt-1">
                        {{ (int) ($summary->waiting_orders ?? 0) }}
                    </h3>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Sedang Diproses</p>
                    <h3 class="text-3xl font-extrabold text-yellow-600 mt-1">
                        {{ (int) ($summary->processing_orders ?? 0) }}
                    </h3>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Sedang Dikirim</p>
                    <h3 class="text-3xl font-extrabold text-sky-600 mt-1">
                        {{ (int) ($summary->shipping_orders ?? 0) }}
                    </h3>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Menunggu Bayar</p>
                    <h3 class="text-3xl font-extrabold text-orange-600 mt-1">
                        {{ (int) ($summary->waiting_payment_orders ?? 0) }}
                    </h3>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Selesai</p>
                    <h3 class="text-3xl font-extrabold text-green-600 mt-1">
                        {{ (int) ($summary->completed_orders ?? 0) }}
                    </h3>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 xl:grid-cols-6 gap-4">
                    <div class="xl:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Cari Pesanan</label>
                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Order ID, nama pembeli, penerima, atau nomor HP"
                               class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status Pengiriman</label>
                        <select name="status"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            <option value="">Semua Status</option>
                            @foreach($statuses as $item)
                                <option value="{{ $item }}" {{ $status === $item ? 'selected' : '' }}>
                                    {{ $statusLabels[$item] ?? $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status Pembayaran</label>
                        <select name="payment_status"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            <option value="">Semua Pembayaran</option>
                            @foreach($paymentStatuses as $item)
                                <option value="{{ $item }}" {{ $paymentStatus === $item ? 'selected' : '' }}>
                                    {{ $paymentStatusLabels[$item] ?? $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date"
                               name="start_date"
                               value="{{ $startDate }}"
                               class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Akhir</label>
                        <input type="date"
                               name="end_date"
                               value="{{ $endDate }}"
                               class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    </div>

                    <div class="xl:col-span-6 flex gap-3">
                        <button type="submit" class="btn-primary px-6 py-3 text-sm">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn-secondary px-6 py-3 text-sm">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-xl font-extrabold text-gray-900">
                            Daftar Pesanan
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Klik detail untuk melihat item pesanan dan mengubah status.
                        </p>
                    </div>

                    <span class="inline-flex bg-blue-50 text-blue-700 text-xs font-bold px-4 py-2 rounded-full">
                        {{ $orders->total() }} hasil
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="text-left px-6 py-4 font-bold">Order</th>
                                <th class="text-left px-6 py-4 font-bold">Pembeli</th>
                                <th class="text-left px-6 py-4 font-bold">Pengiriman</th>
                                <th class="text-left px-6 py-4 font-bold">Pembayaran</th>
                                <th class="text-left px-6 py-4 font-bold">Penerima</th>
                                <th class="text-right px-6 py-4 font-bold">Total</th>
                                <th class="text-left px-6 py-4 font-bold">Tanggal</th>
                                <th class="text-right px-6 py-4 font-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($orders as $order)
                                @php
                                    $statusLabel = $statusLabels[$order->status] ?? $order->status;
                                    $statusClass = 'bg-gray-100 text-gray-700';
                                    $paymentLabel = $paymentStatusLabels[$order->payment_status ?? ''] ?? ($order->payment_status ?? 'Belum ada status');
                                    $paymentClass = 'bg-gray-100 text-gray-700';

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

                                    if (($order->payment_status ?? null) === 'Dibayar') {
                                        $paymentClass = 'bg-green-100 text-green-700';
                                    } elseif (($order->payment_status ?? null) === 'Menunggu Verifikasi') {
                                        $paymentClass = 'bg-yellow-100 text-yellow-700';
                                    } elseif (($order->payment_status ?? null) === 'Ditolak') {
                                        $paymentClass = 'bg-red-100 text-red-700';
                                    } elseif (($order->payment_status ?? null) === 'Bayar Saat Diterima') {
                                        $paymentClass = 'bg-sky-100 text-sky-700';
                                    } elseif (($order->payment_status ?? null) === 'Menunggu Pembayaran') {
                                        $paymentClass = 'bg-orange-100 text-orange-700';
                                    }
                                @endphp

                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-extrabold text-gray-900">#{{ $order->id }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $order->buyer_name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex {{ $statusClass }} text-xs font-bold px-3 py-1 rounded-full">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex {{ $paymentClass }} text-xs font-bold px-3 py-1 rounded-full">
                                            {{ $paymentLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $order->receiver_name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-extrabold text-blue-600">
                                        Rp {{ number_format((float) $order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                           class="btn-secondary px-4 py-2 text-xs">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-16 text-center text-gray-500">
                                        Tidak ada pesanan yang cocok dengan filter ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
