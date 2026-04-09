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
        Schema::table('order_items', function (Blueprint $table) {
            // Make product_id nullable if needed
            $table->foreignId('product_id')->nullable()->change();

            // Rename old columns
            $table->renameColumn('qty', 'quantity');
            $table->renameColumn('unit_price', 'product_price');
            $table->renameColumn('line_total', 'subtotal');

            // Add new columns
            $table->string('product_name')->after('product_id');
            $table->string('product_sku')->nullable()->after('product_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['product_name', 'product_sku']);

            $table->renameColumn('quantity', 'qty');
            $table->renameColumn('product_price', 'unit_price');
            $table->renameColumn('subtotal', 'line_total');

            $table->foreignId('product_id')->nullable(false)->change();
        });
    }
};
