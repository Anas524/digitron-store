<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\HomeShowcaseSlide;

class HomeController extends Controller
{
    public function index()
    {
        $builder = [
            'cpu' => Product::query()
                ->whereHas('category', fn($q) => $q->where('slug', 'processors'))
                ->with(['images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
                ->where('is_active', 1)
                ->orderByDesc('id')
                ->take(20)
                ->get(),

            'gpu' => Product::query()
                ->whereHas('category', fn($q) => $q->where('slug', 'graphics-cards'))
                ->with(['images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
                ->where('is_active', 1)
                ->orderByDesc('id')
                ->take(20)
                ->get(),

            'ram' => Product::query()
                ->whereHas('category', fn($q) => $q->where('slug', 'memory'))
                ->with(['images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
                ->where('is_active', 1)
                ->orderByDesc('id')
                ->take(20)
                ->get(),
        ];

        $homeShowcaseCategories = Category::query()
            ->where('is_active', 1)
            ->where('show_on_home', 1)
            ->whereHas('products', function ($q) {
                $q->where('is_active', 1);
            })
            ->with([
                'products' => function ($q) {
                    $q->where('is_active', 1)
                        ->with('primaryImage')
                        ->orderBy('sort_order')
                        ->orderBy('name');
                }
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                $firstProduct = $category->products->first();

                return (object) [
                    'category' => $category,
                    'primaryImage' => $firstProduct?->primaryImage,
                ];
            })
            ->take(10)
            ->values();

        $homePromoBanners = HomeShowcaseSlide::query()
            ->where('is_active', 1)
            ->latest()
            ->get()
            ->map(function ($slide) {
                return [
                    'image' => asset('storage/' . $slide->image_path),
                ];
            })
            ->values()
            ->toArray();

        $showcasePromos = [
            [
                'href' => route('shop', ['category' => 'processors']),
                'icon' => 'bi-cpu-fill',
                'title' => 'Processors',
                'text' => 'Explore live CPU listings from our current collection.',
                'tone' => 'accent',
            ],
            [
                'href' => route('shop', ['category' => 'graphics-cards']),
                'icon' => 'bi-gpu-card',
                'title' => 'Graphics Cards',
                'text' => 'Browse powerful GPUs for gaming and creator builds.',
                'tone' => 'emerald',
            ],
            [
                'href' => route('shop', ['category' => 'memory']),
                'icon' => 'bi-memory',
                'title' => 'Memory & Performance',
                'text' => 'Find RAM and performance upgrades available right now.',
                'tone' => 'secondary',
            ],
        ];

        $activeProductsCount = Product::where('is_active', 1)->count();

        $activeCategoriesCount = Category::whereHas('products', function ($q) {
            $q->where('is_active', 1);
        })->count();

        $activeBrandsCount = Product::where('is_active', 1)
            ->whereNotNull('brand')
            ->distinct()
            ->count('brand');

        $showcaseStats = [
            [
                'value' => number_format($activeProductsCount),
                'label' => 'Live Products',
                'tone' => 'text-white',
            ],
            [
                'value' => number_format($activeCategoriesCount),
                'label' => 'Active Categories',
                'tone' => 'text-brand-accent',
            ],
            [
                'value' => number_format($activeBrandsCount),
                'label' => 'Available Brands',
                'tone' => 'text-brand-secondary',
            ],
            [
                'value' => '24-48h',
                'label' => 'UAE Delivery',
                'tone' => 'text-emerald-400',
            ],
        ];

        $categorySpotlights = Category::query()
            ->where('is_active', 1)
            ->where('show_on_home', 1)
            ->whereHas('products', function ($q) {
                $q->where('is_active', 1);
            })
            ->with([
                'products' => function ($q) {
                    $q->where('is_active', 1)
                        ->with('primaryImage')
                        ->orderBy('sort_order')
                        ->orderBy('name');
                }
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                $item = $category->products->first();

                if (!$item) return null;

                $name = $category->name;
                $slug = $category->slug;

                $metaMap = [
                    'processors' => [
                        'kicker' => 'Boost performance',
                        'title' => 'Next-Gen Processors',
                        'subtitle' => 'Intel / AMD CPUs',
                        'desc' => 'Upgrade your build with modern processors for gaming, editing, and multitasking workloads.',
                        'accent' => '#22c55e',
                    ],
                    'motherboards' => [
                        'kicker' => 'Build stability',
                        'title' => 'Feature-Rich Motherboards',
                        'subtitle' => 'ATX / mATX / ITX',
                        'desc' => 'Strong VRMs, fast connectivity, and upgrade-ready platforms for your next build.',
                        'accent' => '#38bdf8',
                    ],
                    'memory' => [
                        'kicker' => 'Speed matters',
                        'title' => 'High-Performance Memory',
                        'subtitle' => 'DDR4 / DDR5 Kits',
                        'desc' => 'Push smoother multitasking, faster loading, and more responsive performance.',
                        'accent' => '#a78bfa',
                    ],
                    'graphics-cards' => [
                        'kicker' => 'Power your visuals',
                        'title' => 'Graphics Cards',
                        'subtitle' => 'NVIDIA / AMD GPUs',
                        'desc' => 'High-performance graphics cards for gaming, rendering, streaming, and creator workflows.',
                        'accent' => '#fb7185',
                    ],
                    'storage' => [
                        'kicker' => 'Instant loading',
                        'title' => 'Ultra-Fast Storage',
                        'subtitle' => 'NVMe / SATA Storage',
                        'desc' => 'Faster boot times, game loads, and file transfers with reliable storage performance.',
                        'accent' => '#f59e0b',
                    ],
                    'power-supply' => [
                        'kicker' => 'Safe & efficient',
                        'title' => 'Reliable Power Supplies',
                        'subtitle' => '80+ Rated PSUs',
                        'desc' => 'Efficient, stable, and protected power delivery for every type of system build.',
                        'accent' => '#60a5fa',
                    ],
                ];

                $meta = $metaMap[$slug] ?? [
                    'kicker' => 'Explore category',
                    'title' => $name,
                    'subtitle' => 'Digitron Collection',
                    'desc' => 'Discover products, upgrades, and performance-focused options from this category.',
                    'accent' => '#38bdf8',
                ];

                return [
                    'cat' => $slug,
                    'kicker' => $meta['kicker'],
                    'title' => $meta['title'],
                    'name' => $meta['subtitle'],
                    'desc' => $meta['desc'],
                    'accent' => $meta['accent'],
                    'img' => $item->primaryImage
                        ? asset('storage/' . $item->primaryImage->image_path)
                        : asset('images/placeholder-product.png'),
                ];
            })
            ->filter()
            ->take(6)
            ->values();

        return view('pages.home', compact(
            'builder',
            'homeShowcaseCategories',
            'homePromoBanners',
            'showcasePromos',
            'showcaseStats',
            'categorySpotlights'
        ));
    }
}
