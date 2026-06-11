<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('subtotal_price')->default(0)->after('user_id');
            $table->integer('shipping_cost')->default(0)->after('total_price');
            $table->integer('payment_fee')->default(0)->after('shipping_cost');
            $table->text('notes')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal_price',
                'shipping_cost',
                'payment_fee',
                'notes',
            ]);
        });
    }
};
