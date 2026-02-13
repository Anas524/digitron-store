<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gpu  = Category::firstOrCreate(['slug' => 'graphics-cards'], ['name' => 'Graphics Cards']);
        $cpu  = Category::firstOrCreate(['slug' => 'processors'],     ['name' => 'Processors']);
        $mem  = Category::firstOrCreate(['slug' => 'memory'],         ['name' => 'Memory']);
        $sto  = Category::firstOrCreate(['slug' => 'storage'],        ['name' => 'Storage']);
        $psu  = Category::firstOrCreate(['slug' => 'power-supply'],   ['name' => 'Power Supply']);
        $mobo = Category::firstOrCreate(['slug' => 'motherboards'],   ['name' => 'Motherboards']);

        $items = [
            ['cat' => $gpu,  'name' => 'RTX 4070 Super',        'price' => 2799, 'condition' => 'new',  'badge_text' => 'hot',        'image' => 'images/products/rtx-4070-super.png'],
            ['cat' => $cpu,  'name' => 'Ryzen 7 7800X3D',       'price' => 1699, 'condition' => 'new',  'badge_text' => 'bestseller', 'image' => 'images/products/ryzen-7-7800x3d.png'],
            ['cat' => $mem,  'name' => 'DDR5 32GB 6000MHz',     'price' => 489,  'condition' => 'new',  'badge_text' => null,         'image' => 'images/products/ddr5-32gb.png'],
            ['cat' => $sto,  'name' => 'NVMe SSD 1TB Gen4',     'price' => 299,  'condition' => 'new',  'badge_text' => 'sale',        'image' => 'images/products/nvme-1tb.png'],
            ['cat' => $gpu,  'name' => 'Used GTX 1660 Super',   'price' => 499,  'condition' => 'used', 'badge_text' => 'used',        'image' => 'images/products/gtx-1660-super.png'],
            ['cat' => $psu,  'name' => '750W Gold PSU',         'price' => 349,  'condition' => 'new',  'badge_text' => null,         'image' => 'images/products/psu-750w.png'],
            ['cat' => $cpu,  'name' => 'Intel Core i9-14900K',  'price' => 1899, 'condition' => 'new',  'badge_text' => 'hot',         'image' => 'images/products/i9-14900k.png'],
            ['cat' => $mobo, 'name' => 'ASUS ROG Motherboard',  'price' => 899,  'condition' => 'new',  'badge_text' => null,         'image' => 'images/products/asus-rog-mobo.png'],
        ];

        foreach ($items as $i) {
            $slug = Str::slug($i['name']);

            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $i['cat']->id,
                    'name' => $i['name'],
                    'sku' => null,
                    'price' => $i['price'],
                    'condition' => $i['condition'],
                    'badge_text' => $i['badge_text'],
                    'is_active' => true,
                    'sort_order' => 0,
                    'stock_qty' => 10,
                ]
            );

            // ensure ONLY one primary image
            ProductImage::where('product_id', $product->id)->update(['is_primary' => 0]);

            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'image_path' => $i['image']],
                ['is_primary' => 1, 'sort_order' => 0]
            );
        }
    }
}
