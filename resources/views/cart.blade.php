<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">🛒 Keranjang Belanja Anda</h2>
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold py-2 px-4 rounded-lg transition shadow-sm">
                <span>⬅️</span> <span>Kembali ke Katalog</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                @if($items->isEmpty())
                    <p class="text-center text-gray-500">Keranjang Anda masih kosong. Yuk belanja!</p>
                @else
                    @foreach($items as $item)
                    <div class="flex justify-between items-center border-b py-4">
                        <div>
                            <h4 class="font-bold text-lg text-gray-800">{{ $item->name }}</h4>
                            <p class="text-sm text-gray-500">Jumlah: {{ $item->quantity }} x Rp {{ number_format($item->price) }}</p>
                        </div>
                        <span class="font-bold text-blue-600">Rp {{ number_format($item->price * $item->quantity) }}</span>
                    </div>
                    @endforeach
                    
                    <!-- Form Formulir Alamat & Checkout Sederhana -->
                    <form action="{{ route('checkout') }}" method="POST" class="mt-8">
                        @csrf
                        <div class="mb-4">
                            <label class="block font-bold text-gray-700 mb-2">Alamat Lengkap Pengiriman</label>
                            <textarea name="address" required class="w-full border rounded p-2" placeholder="Tulis alamat rumah lengkap..."></textarea>
                        </div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded shadow-lg">
                            Proses Checkout Sekarang
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>