<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('shipping_address_id')
                ->nullable()
                ->after('total_price')
                ->constrained('user_addresses')
                ->nullOnDelete();
            $table->string('address_label')->nullable()->after('shipping_address_id');
            $table->string('receiver_name')->nullable()->after('address_label');
            $table->string('phone', 20)->nullable()->after('receiver_name');
            $table->string('shipping_method')->nullable()->after('phone');
            $table->string('payment_method')->nullable()->after('shipping_method');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('shipping_address_id');
            $table->dropColumn([
                'address_label',
                'receiver_name',
                'phone',
                'shipping_method',
                'payment_method',
            ]);
        });
    }
};
