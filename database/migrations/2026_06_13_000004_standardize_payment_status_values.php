<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'payment_status')) {
            return;
        }

        DB::table('orders')->where('payment_status', 'Menunggu pembayaran')->update(['payment_status' => 'Menunggu Pembayaran']);
        DB::table('orders')->where('payment_status', 'Menunggu verifikasi')->update(['payment_status' => 'Menunggu Verifikasi']);
        DB::table('orders')->where('payment_status', 'Pembayaran diterima')->update(['payment_status' => 'Dibayar']);
        DB::table('orders')->where('payment_status', 'Pembayaran ditolak')->update(['payment_status' => 'Ditolak']);
        DB::table('orders')->where('payment_status', 'Bayar saat diterima')->update(['payment_status' => 'Bayar Saat Diterima']);
    }

    public function down(): void
    {
        if (!Schema::hasColumn('orders', 'payment_status')) {
            return;
        }

        DB::table('orders')->where('payment_status', 'Menunggu Pembayaran')->update(['payment_status' => 'Menunggu pembayaran']);
        DB::table('orders')->where('payment_status', 'Menunggu Verifikasi')->update(['payment_status' => 'Menunggu verifikasi']);
        DB::table('orders')->where('payment_status', 'Dibayar')->update(['payment_status' => 'Pembayaran diterima']);
        DB::table('orders')->where('payment_status', 'Ditolak')->update(['payment_status' => 'Pembayaran ditolak']);
        DB::table('orders')->where('payment_status', 'Bayar Saat Diterima')->update(['payment_status' => 'Bayar saat diterima']);
    }
};
