<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    private function products()
    {
        return [
            [
                'name' => 'RTX 4070 Super',
                'slug' => Str::slug('RTX 4070 Super'),
                'image' => asset('images/products/rtx-4070-super.png'),
                'thumbs' => [
                    asset('images/products/rtx-4070-super.png'),
                    asset('images/products/rtx-4070-super-2.png'),
                    asset('images/products/rtx-4070-super-3.png'),
                    asset('images/products/rtx-4070-super-4.png'),
                    asset('images/products/rtx-4070-super-5.png'),
                ],
                'price' => '2,799',
                'tag' => 'New',
                'badge' => 'hot',
                'category' => 'Graphics Cards',
            ],
            [
                'name' => 'Ryzen 7 7800X3D',
                'slug' => Str::slug('Ryzen 7 7800X3D'),
                'image' => asset('images/products/ryzen-7-7800x3d.png'),
                'thumbs' => [
                    asset('images/products/ryzen-7-7800x3d.png'),
                    asset('images/products/ryzen-7-7800x3d-2.png'),
                    asset('images/products/ryzen-7-7800x3d-3.png'),
                    asset('images/products/ryzen-7-7800x3d-4.png'),
                    asset('images/products/ryzen-7-7800x3d-5.png'),
                ],
                'price' => '1,699',
                'tag' => 'Hot',
                'badge' => 'bestseller',
                'category' => 'Processors',
            ],
            [
                'name' => 'DDR5 32GB 6000MHz',
                'slug' => Str::slug('DDR5 32GB 6000MHz'),
                'image' => asset('images/products/ddr5-32gb.png'),
                'thumbs' => [
                    asset('images/products/ddr5-32gb.png'),
                    asset('images/products/ddr5-32gb-2.png'),
                    asset('images/products/ddr5-32gb-3.png'),
                    asset('images/products/ddr5-32gb-4.png'),
                    asset('images/products/ddr5-32gb-5.png'),
                ],
                'price' => '489',
                'tag' => 'New',
                'badge' => '',
                'category' => 'Memory'
            ],
            [
                'name' => 'NVMe SSD 1TB Gen4',
                'slug' => Str::slug('NVMe SSD 1TB Gen4'),
                'image' => asset('images/products/nvme-1tb.png'),
                'thumbs' => [
                    asset('images/products/nvme-1tb.png'),
                    asset('images/products/nvme-1tb-2.png'),
                    asset('images/products/nvme-1tb-3.png'),
                    asset('images/products/nvme-1tb-4.png'),
                    asset('images/products/nvme-1tb-5.png'),
                ],
                'price' => '299',
                'tag' => 'Fast',
                'badge' => 'sale',
                'category' => 'Storage'
            ],
            [
                'name' => 'Used GTX 1660 Super',
                'slug' => Str::slug('Used GTX 1660 Super'),
                'image' => asset('images/products/gtx-1660-super.png'),
                'thumbs' => [
                    asset('images/products/gtx-1660-super.png'),
                    asset('images/products/gtx-1660-super-2.png'),
                    asset('images/products/gtx-1660-super-3.png'),
                    asset('images/products/gtx-1660-super-4.png'),
                    asset('images/products/gtx-1660-super-5.png'),
                ],
                'price' => '499',
                'tag' => 'Used',
                'badge' => 'used',
                'category' => 'Graphics Cards'
            ],
            [
                'name' => '750W Gold PSU',
                'slug' => Str::slug('750W Gold PSU'),
                'image' => asset('images/products/psu-750w.png'),
                'thumbs' => [
                    asset('images/products/psu-750w.png'),
                    asset('images/products/psu-750w-2.png'),
                    asset('images/products/psu-750w-3.png'),
                    asset('images/products/psu-750w-4.png'),
                    asset('images/products/psu-750w-5.png'),
                ],
                'price' => '349',
                'tag' => 'Stable',
                'badge' => '',
                'category' => 'Power Supplies'
            ],
            [
                'name' => 'Intel Core i9-14900K',
                'slug' => Str::slug('Intel Core i9-14900K'),
                'image' => asset('images/products/i9-14900k.png'),
                'thumbs' => [
                    asset('images/products/i9-14900k.png'),
                    asset('images/products/i9-14900k-2.png'),
                    asset('images/products/i9-14900k-3.png'),
                    asset('images/products/i9-14900k-4.png'),
                    asset('images/products/i9-14900k-5.png'),
                ],
                'price' => '1,899',
                'tag' => 'New',
                'badge' => 'hot',
                'category' => 'Processors'
            ],
            [
                'name' => 'ASUS ROG Motherboard',
                'slug' => Str::slug('ASUS ROG Motherboard'),
                'image' => asset('images/products/asus-rog-mobo.png'),
                'thumbs' => [
                    asset('images/products/asus-rog-mobo.png'),
                    asset('images/products/asus-rog-mobo-2.png'),
                    asset('images/products/asus-rog-mobo-3.png'),
                    asset('images/products/asus-rog-mobo-4.png'),
                    asset('images/products/asus-rog-mobo-5.png'),
                ],
                'price' => '899',
                'tag' => 'Premium',
                'badge' => '',
                'category' => 'Motherboards'
            ],
        ];
    }

    public function show($slug)
    {
        return view('pages.product', [
            'slug' => $slug,
            'products' => $this->products(),
        ]);
    }
}
