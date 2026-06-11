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
                            $statusClass = $order->status == 'Sampai tujuan'
                                ? 'bg-green-50 text-green-700 border-green-200'
                                : 'bg-blue-50 text-blue-700 border-blue-200';
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
                                    {{ $order->status }}
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

                            @if(!empty($order->notes))
                                <div class="border-t border-slate-100 px-6 py-4">
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Catatan</p>
                                    <p class="mt-1 text-sm text-slate-600">{{ $order->notes }}</p>
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
