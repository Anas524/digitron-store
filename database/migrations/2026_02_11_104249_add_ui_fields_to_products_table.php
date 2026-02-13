<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('badge_text')->nullable()->after('sku');              // HOT / SALE
            $table->decimal('rating', 2, 1)->default(0)->after('badge_text');    // 4.8
            $table->unsignedInteger('rating_count')->default(0)->after('rating'); // 128

            $table->string('delivery_text')->nullable()->after('description');  // delivery line under price

            $table->unsignedTinyInteger('discount_percent')->nullable()
                ->after('compare_at_price'); // optional -12%

            $table->unsignedInteger('sort_order')->default(0)->after('is_active'); // shop order
            $table->json('meta')->nullable()->after('sort_order');               // specs cards
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'badge_text',
                'rating',
                'rating_count',
                'delivery_text',
                'discount_percent',
                'sort_order',
                'meta'
            ]);
        });
    }
};
