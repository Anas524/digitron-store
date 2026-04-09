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
        Schema::table('quotes', function (Blueprint $table) {
            // Flexible storage for new/extra quote fields from the updated quote page
            $table->json('details')->nullable()->after('message');

            // (Optional but useful) if you want to know from where it came
            $table->string('source')->nullable()->after('details'); // e.g. "quote_page", "footer_quote", etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['details', 'source']);
        });
    }
};
