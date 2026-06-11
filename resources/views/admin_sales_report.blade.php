<x-app-layout>
    <x-slot name="header">
        <div class="bg-white border border-gray-100 rounded-2xl px-6 py-5 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-sm font-bold text-blue-600 uppercase tracking-wider">
                        Laporan Penjualan
                    </p>
                    <h2 class="font-extrabold text-2xl text-gray-900 leading-tight">
                        Ringkasan Performa Toko
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Pantau omzet, pesanan, status transaksi, dan stok produk.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('admin.dashboard') }}"
                       class="btn-secondary inline-flex items-center justify-center px-5 py-3 text-sm">
                        ⬅️ Kembali ke Admin
                    </a>

                    <a href="{{ route('admin.reports.export', [
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'status' => $status
                        ]) }}"
                       class="btn-primary inline-flex items-center justify-center px-5 py-3 text-sm">
                        ⬇️ Export CSV
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- FILTER -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                <form action="{{ route('admin.reports') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Mulai
                        </label>
                        <input type="date"
                               name="start_date"
                               value="{{ $startDate }}"
                               class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Akhir
                        </label>
                        <input type="date"
                               name="end_date"
                               value="{{ $endDate }}"
                               class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Status Pesanan
                        </label>
                        <select name="status"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            <option value="">Semua Status</option>
                            @foreach($statuses as $item)
                                <option value="{{ $item }}" {{ $status == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="btn-primary w-full py-3">
                            Tampilkan
                        </button>

                        <a href="{{ route('admin.reports') }}"
                           class="btn-secondary w-full text-center py-3">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- SUMMARY CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <div class="w-12 h-12 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-4">
                        💰
                    </div>
                    <p class="text-sm text-gray-500">Total Omzet</p>
                    <h3 class="text-2xl font-extrabold text-gray-900 mt-1">
                        Rp {{ number_format((float) $totalOmzet, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl mb-4">
                        🧾
                    </div>
                    <p class="text-sm text-gray-500">Total Pesanan</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 mt-1">
                        {{ $totalPesanan }}
                    </h3>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl mb-4">
                        📊
                    </div>
                    <p class="text-sm text-gray-500">Rata-rata Pesanan</p>
                    <h3 class="text-2xl font-extrabold text-gray-900 mt-1">
                        Rp {{ number_format((float) $rataRataPesanan, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mb-4">
                        ✅
                    </div>
                    <p class="text-sm text-gray-500">Pesanan Selesai</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 mt-1">
                        {{ $pesananSelesai }}
                    </h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <!-- LEFT -->
                <section class="lg:col-span-2 space-y-8">

                    <!-- SALES CHART -->
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                        <div class="flex items-start justify-between gap-4 mb-6">
                            <div>
                                <h3 class="text-xl font-extrabold text-gray-900">
                                    Grafik Penjualan Harian
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Perbandingan omzet per hari berdasarkan tanggal pesanan.
                                </p>
                            </div>
                        </div>

                        @php
                            $maxSales = (float) ($salesByDate->max('total_sales') ?? 0);
                            if ($maxSales <= 0) {
                                $maxSales = 1;
                            }
                        @endphp

                        @if($salesByDate->isEmpty())
                            <div class="text-center py-12">
                                <div class="text-5xl mb-4">📊</div>
                                <h4 class="font-extrabold text-gray-900">
                                    Belum ada data penjualan
                                </h4>
                                <p class="text-sm text-gray-500 mt-2">
                                    Data akan muncul setelah ada pesanan pada periode yang dipilih.
                                </p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($salesByDate as $row)
                                    @php
                                        $percentage = ((float) $row->total_sales / $maxSales) * 100;
                                    @endphp

                                    <div>
                                        <div class="flex justify-between text-sm mb-2">
                                            <span class="font-bold text-gray-700">
                                                {{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}
                                            </span>
                                            <span class="text-gray-500">
                                                {{ $row->total_orders }} pesanan -
                                                Rp {{ number_format((float) $row->total_sales, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        <div class="w-full h-4 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-blue-950 rounded-full"
                                                 style="width: {{ $percentage }}%">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- ORDER TABLE -->
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-xl font-extrabold text-gray-900">
                                Daftar Transaksi
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Semua pesanan pada periode laporan yang dipilih.
                            </p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-500">
                                    <tr>
                                        <th class="text-left px-6 py-4 font-bold">Order</th>
                                        <th class="text-left px-6 py-4 font-bold">Pembeli</th>
                                        <th class="text-left px-6 py-4 font-bold">Tanggal</th>
                                        <th class="text-left px-6 py-4 font-bold">Status</th>
                                        <th class="text-right px-6 py-4 font-bold">Ongkir</th>
                                        <th class="text-right px-6 py-4 font-bold">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($orders as $order)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 font-extrabold text-gray-900">
                                                #{{ $order->id }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-700">
                                                {{ $order->buyer_name }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-500">
                                                {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right font-bold text-gray-600">
                                                Rp {{ number_format((float) ($order->shipping_cost ?? 0), 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-extrabold text-blue-600">
                                                Rp {{ number_format((float) $order->total_price, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                                Belum ada transaksi pada periode ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </section>

                <!-- RIGHT -->
                <aside class="space-y-8">

                    <!-- STATUS SUMMARY -->
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-xl font-extrabold text-gray-900 mb-2">
                            Rekap Status Pesanan
                        </h3>
                        <p class="text-sm text-gray-500 mb-5">
                            Jumlah pesanan berdasarkan status.
                        </p>

                        <div class="space-y-3">
                            @forelse($statusSummary as $row)
                                <div class="flex items-center justify-between border border-gray-100 rounded-2xl px-4 py-3">
                                    <div>
                                        <p class="font-bold text-gray-800">
                                            {{ $row->status }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $row->total_orders }} pesanan
                                        </p>
                                    </div>
                                    <p class="font-extrabold text-blue-600">
                                        Rp {{ number_format((float) $row->total_sales, 0, ',', '.') }}
                                    </p>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    Belum ada data status.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- LOW STOCK -->
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-xl font-extrabold text-gray-900 mb-2">
                            Produk Stok Menipis
                        </h3>
                        <p class="text-sm text-gray-500 mb-5">
                            Produk dengan stok 5 atau kurang.
                        </p>

                        <div class="space-y-3">
                            @forelse($lowStockProducts as $product)
                                <div class="flex items-center justify-between border border-gray-100 rounded-2xl px-4 py-3">
                                    <div>
                                        <p class="font-bold text-gray-800">
                                            {{ $product->name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $product->category ?? 'Tanpa kategori' }}
                                        </p>
                                    </div>

                                    <span class="bg-red-50 text-red-700 text-xs font-extrabold px-3 py-1 rounded-full">
                                        Stok {{ $product->stock }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    Semua stok masih aman.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </aside>

            </div>
        </div>
    </div>
</x-app-layout>
