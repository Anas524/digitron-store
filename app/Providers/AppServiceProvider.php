<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        View::composer('admin.partials.sidebar', function ($view) {
            $products = DB::table('products')->count();
            $categories = DB::table('categories')->count();
            $newsletter = DB::table('newsletter_subscriptions')->count();
            $quotes = DB::table('quotes')->count();
            $orders = DB::table('orders')->count();
            $homeShowcase = DB::table('home_showcase_slides')->count();

            $view->with('sidebarCounts', [
                'products'      => $products,
                'categories'    => $categories,
                'newsletter'    => $newsletter,
                'quotes'        => $quotes,
                'orders'        => $orders,
                'home_showcase' => $homeShowcase,
            ]);
        });

        View::composer('partials.navbar', function ($view) {
            $menuParents = Category::query()
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->where('show_in_menu', true)
                ->with([
                    'children' => function ($q) {
                        $q->where('is_active', true)
                            ->where('show_in_menu', true)
                            ->orderBy('sort_order')
                            ->orderBy('name');
                    },
                ])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();

            $parentIds = $menuParents->pluck('id');

            $childCategories = Category::query()
                ->whereIn('parent_id', $parentIds)
                ->where('is_active', true)
                ->where('show_in_menu', true)
                ->select('id', 'name', 'slug', 'parent_id', 'sort_order')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();

            $childIds = $childCategories->pluck('id');

            $products = collect();

            if ($childIds->isNotEmpty()) {
                $products = Product::query()
                    ->where('is_active', true)
                    ->whereIn('category_id', $childIds)
                    ->with('category:id,name,slug,parent_id,sort_order')
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
            }

            $childrenByParent = $childCategories->groupBy('parent_id');

            $megaMenuByParent = $menuParents->mapWithKeys(function ($parent) use ($childrenByParent, $products) {
                $children = $childrenByParent->get($parent->id, collect());
                $childIds = $children->pluck('id');

                $parentProducts = $products
                    ->whereIn('category_id', $childIds)
                    ->sortBy([
                        fn($a, $b) => ($a->category->sort_order ?? 0) <=> ($b->category->sort_order ?? 0),
                        fn($a, $b) => strcmp($a->category->name ?? '', $b->category->name ?? ''),
                        fn($a, $b) => ($a->sort_order ?? 0) <=> ($b->sort_order ?? 0),
                        fn($a, $b) => strcmp($a->name ?? '', $b->name ?? ''),
                    ]);

                $grouped = [
                    'new' => $parentProducts
                        ->filter(fn($p) => $p->condition === 'new')
                        ->groupBy(fn($p) => $p->category->name ?? 'Other'),

                    'used' => $parentProducts
                        ->filter(fn($p) => $p->condition === 'used')
                        ->groupBy(fn($p) => $p->category->name ?? 'Other'),

                    'refurbished' => $parentProducts
                        ->filter(fn($p) => $p->condition === 'refurbished')
                        ->groupBy(fn($p) => $p->category->name ?? 'Other'),
                ];

                return [
                    $parent->id => [
                        'parent' => $parent,
                        'children' => $children,
                        'groups' => $grouped,
                    ],
                ];
            });

            $view->with([
                'menuParents'      => $menuParents,
                'megaMenuByParent' => $megaMenuByParent,
            ]);
        });

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $cartCount = \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity');
            } else {
                $cart = session('cart', []);
                $cartCount = 0;

                foreach ((array) $cart as $qty) {
                    $cartCount += (int) $qty;
                }
            }

            $view->with('cartCount', $cartCount);
        });
    }
}