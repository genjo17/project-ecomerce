<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if (Schema::hasColumn('users', 'role')) {
            DB::table('users')
                ->where('role', 'buyer')
                ->update(['role' => 'customer']);

            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE users ALTER role SET DEFAULT 'customer'");
            }
        }

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'product_name')) {
                $table->string('product_name')->nullable()->after('product_id');
            }

            if (!Schema::hasColumn('order_items', 'product_image')) {
                $table->string('product_image')->nullable()->after('product_name');
            }
        });

        DB::table('order_items')
            ->whereNull('product_name')
            ->orderBy('id')
            ->chunkById(100, function ($items) {
                $products = DB::table('products')
                    ->whereIn('id', $items->pluck('product_id')->filter()->unique()->values())
                    ->get()
                    ->keyBy('id');

                foreach ($items as $item) {
                    $product = $products->get($item->product_id);

                    if (!$product) {
                        continue;
                    }

                    DB::table('order_items')
                        ->where('id', $item->id)
                        ->update([
                            'product_name' => $product->name,
                            'product_image' => $product->image_url,
                        ]);
                }
            });

        if ($driver === 'mysql') {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropForeign(['product_id']);
                $table->foreignId('product_id')->nullable()->change();
                $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropForeign(['product_id']);
                $table->foreignId('product_id')->nullable(false)->change();
                $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            });
        }

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['product_name', 'product_image']);
        });

        if (Schema::hasColumn('users', 'role')) {
            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE users ALTER role SET DEFAULT 'buyer'");
            }
        }
    }
};
