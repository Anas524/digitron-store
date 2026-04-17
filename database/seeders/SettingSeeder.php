<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'tax_percent'],
            ['value' => 5]
        );

        Setting::updateOrCreate(
            ['key' => 'shipping_type'],
            ['value' => 'free']
        );

        Setting::updateOrCreate(
            ['key' => 'shipping_flat_rate'],
            ['value' => 25]
        );

        Setting::updateOrCreate(
            ['key' => 'free_shipping_min'],
            ['value' => 500]
        );
    }
}
