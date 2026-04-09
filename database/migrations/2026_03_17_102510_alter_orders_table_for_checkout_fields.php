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
        Schema::table('orders', function (Blueprint $table) {
            // Rename old columns to new names
            $table->renameColumn('order_no', 'order_number');
            $table->renameColumn('status', 'order_status');
            $table->renameColumn('total', 'total_amount');

            // Add new columns
            $table->string('full_name')->after('order_number');
            $table->string('email')->after('full_name');
            $table->string('phone', 50)->after('email');
            $table->string('city', 120)->after('phone');
            $table->text('address')->after('city');

            $table->decimal('tax', 12, 2)->default(0)->after('subtotal');
            $table->string('payment_method')->default('cash_on_delivery')->after('total_amount');
            $table->string('payment_status')->default('unpaid')->after('payment_method');

            // Remove old columns no longer needed
            $table->dropColumn('discount_total');
            $table->dropColumn('shipping_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add removed columns back
            $table->decimal('discount_total', 12, 2)->default(0)->after('subtotal');
            $table->json('shipping_address')->nullable()->after('total_amount');

            // Drop newly added columns
            $table->dropColumn([
                'full_name',
                'email',
                'phone',
                'city',
                'address',
                'tax',
                'payment_method',
                'payment_status',
            ]);

            // Rename back
            $table->renameColumn('order_number', 'order_no');
            $table->renameColumn('order_status', 'status');
            $table->renameColumn('total_amount', 'total');
        });
    }
};
