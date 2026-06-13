<x-app-layout>
    <x-slot name="header">
        <div class="panel px-6 py-5">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wider text-blue-600">Pesanan Saya</p>
                    <h2 class="mt-1 text-2xl font-extrabold leading-tight text-slate-950">
                        Riwayat Belanja & Tracking Pesanan
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Pantau status pengiriman dan total pembayaran dari setiap checkout.
                    </p>
                </div>

                <a href="{{ route('dashboard') }}" class="btn-secondary">
                    Kembali ke Katalog
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if($orders->isEmpty())
                <div class="panel px-8 py-14 text-center">
                    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-xl font-black text-blue-700">
                        SB
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-950">Belum ada pesanan</h3>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">
                        Pesanan akan muncul di halaman ini setelah Anda menyelesaikan checkout dari keranjang.
                    </p>
                    <a href="{{ route('dashboard') }}" class="btn-primary mt-6">Mulai Belanja</a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($orders as $order)
                        @php
                            $statusLabel = match ($order->status) {
                                'Pesanan diterima' => 'Menunggu diproses',
                                'Sedang diproses' => 'Diproses seller',
                                'Sedang dikirim' => 'Dalam pengiriman',
                                'Sampai tujuan' => 'Sudah sampai',
                                'Pesanan selesai' => 'Selesai dikonfirmasi',
                                default => $order->status,
                            };

                            $statusClass = match ($order->status) {
                                'Pesanan selesai' => 'bg-green-50 text-green-700 border-green-200',
                                'Sampai tujuan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'Sedang diproses' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                default => 'bg-blue-50 text-blue-700 border-blue-200',
                            };

                            $paymentStatusLabel = match ($order->payment_status ?? null) {
                                'Menunggu Pembayaran' => 'Menunggu Pembayaran',
                                'Menunggu Verifikasi' => 'Menunggu Verifikasi',
                                'Dibayar' => 'Dibayar',
                                'Ditolak' => 'Ditolak',
                                'Refund' => 'Refund',
                                'Bayar Saat Diterima' => 'Bayar Saat Diterima',
                                default => $order->payment_status ?? 'Belum ada status pembayaran',
                            };

                            $paymentStatusClass = match ($order->payment_status ?? null) {
                                'Dibayar' => 'bg-green-50 text-green-700 border-green-200',
                                'Menunggu Verifikasi' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'Ditolak' => 'bg-red-50 text-red-700 border-red-200',
                                'Bayar Saat Diterima' => 'bg-sky-50 text-sky-700 border-sky-200',
                                default => 'bg-orange-50 text-orange-700 border-orange-200',
                            };

                            $canUploadProof = in_array($order->payment_method, ['Transfer Bank', 'QRIS'], true)
                                && !in_array($order->payment_status, ['Dibayar', 'Bayar Saat Diterima'], true);

                            $paymentProofUrl = !empty($order->payment_proof_path) ? asset($order->payment_proof_path) : null;
                        @endphp

                        <article class="panel overflow-hidden">
                            <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-wider text-slate-400">
                                        ID Pesanan #{{ $order->id }}
                                    </p>
                                    <h3 class="mt-1 text-lg font-extrabold text-slate-950">
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}
                                    </h3>
                                </div>

                                <span class="inline-flex w-fit rounded-full border px-3 py-1 text-xs font-extrabold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>

                                <span class="inline-flex w-fit rounded-full border px-3 py-1 text-xs font-extrabold {{ $paymentStatusClass }}">
                                    {{ $paymentStatusLabel }}
                                </span>
                            </div>

                            <div class="grid gap-5 px-6 py-5 md:grid-cols-3">
                                <div class="md:col-span-2 space-y-4">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Penerima</p>
                                        @if(!empty($order->address_label))
                                            <p class="mt-1 text-xs font-extrabold uppercase tracking-wider text-blue-600">
                                                {{ $order->address_label }}
                                            </p>
                                        @endif
                                        <p class="mt-2 text-sm font-semibold text-slate-800">
                                            {{ $order->receiver_name ?? '-' }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            {{ $order->phone ?? '-' }}
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat Kirim</p>
                                        <p class="mt-2 text-sm leading-relaxed text-slate-700">{{ $order->address ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengiriman</p>
                                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                                {{ $order->shipping_method ?? '-' }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pembayaran</p>
                                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                                {{ $order->payment_method ?? '-' }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Pembayaran</p>
                                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                                {{ $paymentStatusLabel }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Resi</p>
                                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                                {{ $order->tracking_number ?? '-' }}
                                            </p>
                                            @if(!empty($order->tracking_url))
                                                <a href="{{ $order->tracking_url }}" target="_blank" class="mt-1 inline-flex text-xs font-semibold text-blue-600 hover:text-blue-800">
                                                    Lihat tracking
                                                </a>
                                            @endif
                                        </div>

                                        <div class="border-t border-slate-200 pt-3">
                                            <div class="flex justify-between gap-3 text-xs text-slate-500">
                                                <span>Subtotal</span>
                                                <span class="font-bold text-slate-700">
                                                    Rp {{ number_format((float) ($order->subtotal_price ?: $order->total_price), 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <div class="mt-2 flex justify-between gap-3 text-xs text-slate-500">
                                                <span>Ongkir</span>
                                                <span class="font-bold text-slate-700">
                                                    Rp {{ number_format((float) ($order->shipping_cost ?? 0), 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <div class="mt-2 flex justify-between gap-3 text-xs text-slate-500">
                                                <span>Biaya bayar</span>
                                                <span class="font-bold text-slate-700">
                                                    Rp {{ number_format((float) ($order->payment_fee ?? 0), 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Bayar</p>
                                            <p class="mt-1 text-2xl font-black text-blue-700">
                                                Rp {{ number_format((float) $order->total_price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-slate-100 px-6 py-5">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">Invoice & Dokumen</p>
                                        <p class="text-sm text-slate-500">Cetak atau unduh invoice untuk pesanan ini.</p>
                                    </div>

                                    <div class="flex flex-wrap gap-3">
                                        <a href="{{ route('orders.invoice', $order->id) }}" class="btn-secondary px-4 py-2 text-sm">
                                            Lihat Invoice
                                        </a>
                                        <a href="{{ route('orders.invoice.download', $order->id) }}" class="btn-secondary px-4 py-2 text-sm">
                                            Unduh
                                        </a>
                                    </div>
                                </div>
                            </div>

                            @if($paymentProofUrl)
                                <div class="border-t border-slate-100 px-6 py-5">
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Bukti Pembayaran</p>
                                    <a href="{{ $paymentProofUrl }}" target="_blank" class="mt-3 block">
                                        <img src="{{ $paymentProofUrl }}" alt="Bukti pembayaran" class="max-w-sm rounded-2xl border border-slate-200">
                                    </a>
                                </div>
                            @endif

                            @if($canUploadProof)
                                <div class="border-t border-slate-100 px-6 py-5 bg-blue-50/30">
                                    <form action="{{ route('orders.payment-proof', $order->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4 md:flex-row md:items-end">
                                        @csrf

                                        <div class="flex-1">
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Upload Bukti Pembayaran</label>
                                            <input type="file" name="payment_proof" accept="image/png,image/jpeg,image/jpg,image/webp,application/pdf" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm">
                                        </div>

                                        <button type="submit" class="btn-primary px-5 py-3">
                                            Upload Bukti
                                        </button>
                                    </form>
                                </div>
                            @endif

                            @if(!empty($order->notes))
                                <div class="border-t border-slate-100 px-6 py-4">
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Catatan</p>
                                    <p class="mt-1 text-sm text-slate-600">{{ $order->notes }}</p>
                                </div>
                            @endif

                            @if(!empty($order->items) && count($order->items))
                                <div class="border-t border-slate-100 px-6 py-5">
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Item Pesanan</p>
                                    <div class="space-y-3">
                                        @foreach($order->items as $item)
                                            <div class="flex items-center justify-between gap-4 rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                                                <div>
                                                    <p class="font-bold text-slate-900">{{ $item->product_name }}</p>
                                                    <p class="text-xs text-slate-500">Qty: {{ $item->quantity }}</p>
                                                </div>
                                                <p class="font-bold text-blue-700">
                                                    Rp {{ number_format((float) ($item->price * $item->quantity), 0, ',', '.') }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($order->status === 'Sampai tujuan')
                                <div class="border-t border-slate-100 bg-emerald-50/50 px-6 py-4">
                                    <form action="{{ route('orders.confirm-received', $order->id) }}"
                                          method="POST"
                                          class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                                          onsubmit="return confirm('Konfirmasi bahwa pesanan ini sudah Anda terima?');">
                                        @csrf
                                        @method('PATCH')

                                        <p class="text-sm font-semibold text-emerald-800">
                                            Pesanan sudah sampai? Konfirmasi agar transaksi ditandai selesai.
                                        </p>

                                        <button type="submit" class="btn-primary w-full sm:w-auto">
                                            Konfirmasi Diterima
                                        </button>
                                    </form>
                                </div>
                            @elseif($order->status === 'Pesanan selesai')
                                <div class="border-t border-slate-100 bg-green-50/60 px-6 py-4">
                                    <p class="text-sm font-semibold text-green-700">
                                        Pesanan ini sudah Anda konfirmasi diterima.
                                    </p>
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
