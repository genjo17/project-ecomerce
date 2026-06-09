<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">📦 Riwayat Belanja & Tracking Pesanan</h2>
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold py-2 px-4 rounded-lg transition shadow-sm">
                <span>⬅️</span> <span>Kembali ke Katalog</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @foreach($orders as $order)
            <div class="bg-white p-6 rounded shadow mb-4 border-l-4 border-blue-500">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <span class="text-xs text-gray-400">ID PESANAN: #{{ $order->id }}</span>
                        <h4 class="text-gray-500 text-sm">Tanggal: {{ $order->created_at }}</h4>
                    </div>
                    <!-- INDIKATOR STATUS TRACKING -->
                    <span class="px-3 py-1 text-xs font-bold rounded-full 
                        {{ $order->status == 'Sampai tujuan' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        🚚 Status: {{ $order->status }}
                    </span>
                </div>
                <p class="text-sm text-gray-700"><strong>Alamat Kirim:</strong> {{ $order->address }}</p>
                <div class="text-right mt-2">
                    <span class="text-sm text-gray-500">Total Bayar:</span>
                    <span class="text-lg font-extrabold text-blue-600 ml-2">Rp {{ number_format($order->total_price) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>