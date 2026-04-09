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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('description');
            $table->string('icon')->nullable()->after('image_path');
            $table->boolean('show_in_menu')->default(true)->after('is_active');
            $table->boolean('show_on_home')->default(true)->after('show_in_menu');
            $table->boolean('is_featured')->default(false)->after('show_on_home');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'image_path',
                'icon',
                'show_in_menu',
                'show_on_home',
                'is_featured',
            ]);
        });
    }
};
