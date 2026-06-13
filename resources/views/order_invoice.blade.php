<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wider text-blue-600">Invoice</p>
                    <h1 class="mt-1 text-3xl font-extrabold text-slate-950">Order #{{ $order->id }}</h1>
                    <p class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="button" onclick="window.print()" class="btn-secondary px-4 py-2 text-sm">
                        Cetak
                    </button>
                    <a href="{{ route('orders.invoice.download', $order->id) }}" class="btn-primary px-4 py-2 text-sm">
                        Unduh
                    </a>
                    <a href="{{ route('orders.history') }}" class="btn-secondary px-4 py-2 text-sm">
                        Kembali
                    </a>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="panel p-6 lg:col-span-2 space-y-6">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Penerima</p>
                            <p class="mt-2 font-bold text-slate-900">{{ $order->receiver_name ?? '-' }}</p>
                            <p class="text-sm text-slate-600">{{ $order->phone ?? '-' }}</p>
                            <p class="mt-2 text-sm text-slate-600">{{ $order->address ?? '-' }}</p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Status</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">Pengiriman: {{ $order->status ?? '-' }}</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900">Pembayaran: {{ $order->payment_status ?? '-' }}</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900">Kurir: {{ $order->courier_name ?? '-' }}</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900">Resi: {{ $order->tracking_number ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 text-slate-500">
                                <tr>
                                    <th class="px-4 py-3 text-left font-bold">Produk</th>
                                    <th class="px-4 py-3 text-right font-bold">Qty</th>
                                    <th class="px-4 py-3 text-right font-bold">Harga</th>
                                    <th class="px-4 py-3 text-right font-bold">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($order->items ?? [] as $item)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="font-bold text-slate-900">{{ $item->product_name }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-right">{{ $item->quantity }}</td>
                                        <td class="px-4 py-3 text-right">Rp {{ number_format((float) $item->price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right font-bold text-blue-700">
                                            Rp {{ number_format((float) ($item->price * $item->quantity), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-slate-500">
                                            Tidak ada item.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="panel p-6 space-y-3">
                        <h2 class="text-xl font-extrabold text-slate-950">Ringkasan</h2>
                        <div class="flex justify-between text-sm text-slate-600">
                            <span>Subtotal</span>
                            <span class="font-bold text-slate-950">Rp {{ number_format((float) ($order->subtotal_price ?? $order->total_price), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-slate-600">
                            <span>Ongkir</span>
                            <span class="font-bold text-slate-950">Rp {{ number_format((float) ($order->shipping_cost ?? 0), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-slate-600">
                            <span>Biaya bayar</span>
                            <span class="font-bold text-slate-950">Rp {{ number_format((float) ($order->payment_fee ?? 0), 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-slate-200 pt-3 flex justify-between">
                            <span class="font-bold text-slate-950">Total</span>
                            <span class="text-lg font-black text-blue-700">Rp {{ number_format((float) $order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if(!empty($order->payment_proof_path))
                        <div class="panel p-6">
                            <h2 class="text-lg font-extrabold text-slate-950 mb-3">Bukti Pembayaran</h2>
                            <a href="{{ asset($order->payment_proof_path) }}" target="_blank">
                                <img src="{{ asset($order->payment_proof_path) }}" alt="Bukti pembayaran" class="w-full rounded-2xl border border-slate-200">
                            </a>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
