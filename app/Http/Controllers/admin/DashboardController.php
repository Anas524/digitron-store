<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $productsCount = Schema::hasTable('products') ? Product::count() : 0;

        $customersCount = Schema::hasTable('users')
            ? User::where('is_admin', 0)->count()
            : 0;

        $newsletterCount = Schema::hasTable('newsletter_subscriptions')
            ? DB::table('newsletter_subscriptions')->count()
            : 0;

        $quotesCount = 0;
        $pendingQuotesCount = 0;

        if (Schema::hasTable('quotes')) {
            $quotesCount = DB::table('quotes')->count();

            if (Schema::hasColumn('quotes', 'status')) {
                $pendingQuotesCount = DB::table('quotes')
                    ->whereIn('status', ['new', 'pending'])
                    ->count();
            } else {
                $pendingQuotesCount = $quotesCount;
            }
        }

        $newProductsToday = Schema::hasTable('products')
            ? DB::table('products')->whereDate('created_at', today())->count()
            : 0;

        $newCustomersThisMonth = Schema::hasTable('users')
            ? DB::table('users')
            ->where('is_admin', 0)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count()
            : 0;

        $ordersCount = Schema::hasTable('orders')
            ? DB::table('orders')->count()
            : 0;

        $totalRevenue = 0;

        if (Schema::hasTable('orders')) {
            if (Schema::hasColumn('orders', 'total_amount')) {
                $query = DB::table('orders');

                if (Schema::hasColumn('orders', 'payment_status')) {
                    $query->where('payment_status', 'paid');
                }

                $totalRevenue = (float) $query->sum('total_amount');
            }
        }

        $monthlyRevenue = $this->getMonthlyRevenue();
        $activities = $this->getRecentActivities();

        $newQuotesCount = 0;
        $newNewsletterCount = 0;
        $newChatbotLeadsCount = 0;

        $quoteNotifications = collect();
        $newsletterNotifications = collect();
        $chatbotLeadNotifications = collect();

        if (Schema::hasTable('quotes')) {
            if (Schema::hasColumn('quotes', 'status')) {
                $newQuotesCount = DB::table('quotes')
                    ->where('status', 'new')
                    ->count();

                $quoteNotifications = DB::table('quotes')
                    ->where('status', 'new')
                    ->orderByDesc('id')
                    ->limit(8)
                    ->get()
                    ->map(function ($quote) {
                        return [
                            'type' => 'quote',
                            'id' => $quote->id,
                            'title' => $quote->full_name ?? 'Customer',
                            'subtitle' => $quote->quote_type ?? 'Quote request',
                            'time' => !empty($quote->created_at)
                                ? Carbon::parse($quote->created_at)->diffForHumans()
                                : 'recently',
                            'icon' => 'file-text',
                            'color' => 'admin-warning',
                            'sort_time' => !empty($quote->created_at) ? strtotime($quote->created_at) : 0,
                        ];
                    });
            } else {
                $newQuotesCount = DB::table('quotes')->count();
            }
        }

        if (Schema::hasTable('newsletter_subscriptions')) {
            if (Schema::hasColumn('newsletter_subscriptions', 'status')) {
                $newNewsletterCount = DB::table('newsletter_subscriptions')
                    ->where('status', 'new')
                    ->count();

                $newsletterNotifications = DB::table('newsletter_subscriptions')
                    ->where('status', 'new')
                    ->orderByDesc('id')
                    ->limit(8)
                    ->get()
                    ->map(function ($sub) {
                        return [
                            'type' => 'newsletter',
                            'id' => $sub->id,
                            'title' => $sub->email ?? 'New subscriber',
                            'subtitle' => 'Newsletter subscription',
                            'time' => !empty($sub->created_at)
                                ? Carbon::parse($sub->created_at)->diffForHumans()
                                : 'recently',
                            'icon' => 'envelope-paper',
                            'color' => 'admin-primary',
                            'sort_time' => !empty($sub->created_at) ? strtotime($sub->created_at) : 0,
                        ];
                    });
            } else {
                $newNewsletterCount = 0;
                $newsletterNotifications = collect();
            }
        }

        if (Schema::hasTable('chatbot_leads')) {
            if (Schema::hasColumn('chatbot_leads', 'status')) {
                $newChatbotLeadsCount = DB::table('chatbot_leads')
                    ->where('status', 'new')
                    ->count();

                $chatbotLeadNotifications = DB::table('chatbot_leads')
                    ->where('status', 'new')
                    ->orderByDesc('id')
                    ->limit(8)
                    ->get()
                    ->map(function ($lead) {
                        $productName = $lead->product_name ?: 'General inquiry';
                        $message = Str::limit($lead->message ?? 'New chatbot message', 45);

                        return [
                            'type' => 'chatbot_lead',
                            'id' => $lead->id,
                            'title' => $productName,
                            'subtitle' => $message,
                            'time' => !empty($lead->created_at)
                                ? Carbon::parse($lead->created_at)->diffForHumans()
                                : 'recently',
                            'icon' => 'chat-dots',
                            'color' => 'admin-success',
                            'sort_time' => !empty($lead->created_at) ? strtotime($lead->created_at) : 0,
                        ];
                    });
            } else {
                $newChatbotLeadsCount = DB::table('chatbot_leads')->count();
            }
        }

        $notifications = $quoteNotifications
            ->concat($newsletterNotifications)
            ->concat($chatbotLeadNotifications)
            ->sortByDesc('sort_time')
            ->take(10)
            ->values();

        $notificationCount = $newQuotesCount + $newNewsletterCount + $newChatbotLeadsCount;

        $stats = [
            [
                'label' => 'Total Revenue',
                'value' => 'AED ' . number_format($totalRevenue, 2),
                'change' => null,
                'icon' => 'cash-stack',
                'color' => 'admin-primary',
                'chart' => $monthlyRevenue['mini'],
            ],
            [
                'label' => 'Orders',
                'value' => number_format($ordersCount),
                'change' => null,
                'icon' => 'cart-check',
                'color' => 'admin-success',
                'chart' => [20, 28, 32, 30, 35, 40, 45],
            ],
            [
                'label' => 'Products',
                'value' => number_format($productsCount),
                'change' => $newProductsToday > 0 ? '+' . $newProductsToday : '0',
                'icon' => 'box-seam',
                'color' => 'admin-secondary',
                'chart' => [30, 34, 38, 42, 45, 50, 55],
            ],
            [
                'label' => 'Customers',
                'value' => number_format($customersCount),
                'change' => $newCustomersThisMonth > 0 ? '+' . $newCustomersThisMonth : '0',
                'icon' => 'people-fill',
                'color' => 'admin-warning',
                'chart' => [25, 30, 34, 39, 45, 50, 58],
            ],
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
            'activities' => $activities,
            'productsCount' => $productsCount,
            'newsletterCount' => $newsletterCount,
            'quotesCount' => $quotesCount,
            'pendingQuotesCount' => $pendingQuotesCount,
            'newProductsToday' => $newProductsToday,
            'monthlyRevenueLabels' => $monthlyRevenue['labels'],
            'monthlyRevenuePoints' => $monthlyRevenue['points'],
            'notificationCount' => $notificationCount,
            'newQuotesCount' => $newQuotesCount,
            'newNewsletterCount' => $newNewsletterCount,
            'newChatbotLeadsCount' => $newChatbotLeadsCount,
            'notifications' => $notifications,
        ]);
    }

    private function getMonthlyRevenue(): array
    {
        $labels = [];
        $raw = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->copy()->subMonths($i);
            $labels[] = $date->format('M');

            if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'total_amount')) {
                $query = DB::table('orders');

                if (Schema::hasColumn('orders', 'payment_status')) {
                    $query->where('payment_status', 'paid');
                }

                if (Schema::hasColumn('orders', 'created_at')) {
                    $query->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year);
                }

                $sum = (float) $query->sum('total_amount');
            } else {
                $sum = 0;
            }

            $raw[] = $sum;
        }

        $max = max($raw) > 0 ? max($raw) : 1;

        $mini = collect($raw)->map(function ($v) use ($max) {
            return max(20, (int) round(($v / $max) * 100));
        })->values()->all();

        $count = count($raw);
        $stepX = $count > 1 ? 800 / ($count - 1) : 800;

        $points = [];
        foreach ($raw as $index => $value) {
            $x = round($index * $stepX, 2);
            $y = 180 - (($value / $max) * 140);
            $points[] = ['x' => $x, 'y' => round($y, 2), 'value' => $value];
        }

        return [
            'labels' => $labels,
            'mini' => $mini,
            'points' => $points,
        ];
    }

    private function getRecentActivities(): array
    {
        $activities = [];

        if (Schema::hasTable('quotes')) {
            $quotes = DB::table('quotes')->latest()->limit(5)->get();

            foreach ($quotes as $quote) {
                $activities[] = [
                    'title' => 'Quote request',
                    'desc' => $quote->full_name ?? 'New quote received',
                    'time' => !empty($quote->created_at)
                        ? Carbon::parse($quote->created_at)->diffForHumans()
                        : 'recently',
                    'icon' => 'file-text',
                    'color' => 'admin-warning',
                    'sort_time' => !empty($quote->created_at) ? strtotime($quote->created_at) : 0,
                ];
            }
        }

        if (Schema::hasTable('users')) {
            $users = DB::table('users')
                ->where('is_admin', 0)
                ->latest()
                ->limit(5)
                ->get();

            foreach ($users as $user) {
                $activities[] = [
                    'title' => 'New registration',
                    'desc' => $user->email ?? 'New customer joined',
                    'time' => !empty($user->created_at)
                        ? Carbon::parse($user->created_at)->diffForHumans()
                        : 'recently',
                    'icon' => 'person-plus',
                    'color' => 'admin-success',
                    'sort_time' => !empty($user->created_at) ? strtotime($user->created_at) : 0,
                ];
            }
        }

        if (Schema::hasTable('products')) {
            $products = DB::table('products')->latest()->limit(5)->get();

            foreach ($products as $product) {
                $activities[] = [
                    'title' => 'Product added',
                    'desc' => $product->name ?? 'New product created',
                    'time' => !empty($product->created_at)
                        ? Carbon::parse($product->created_at)->diffForHumans()
                        : 'recently',
                    'icon' => 'box-seam',
                    'color' => 'admin-primary',
                    'sort_time' => !empty($product->created_at) ? strtotime($product->created_at) : 0,
                ];
            }
        }

        if (Schema::hasTable('chatbot_leads')) {
            $chatbotLeads = DB::table('chatbot_leads')->latest()->limit(5)->get();

            foreach ($chatbotLeads as $lead) {
                $activities[] = [
                    'title' => 'Chatbot lead',
                    'desc' => $lead->product_name ?: 'General inquiry',
                    'time' => !empty($lead->created_at)
                        ? Carbon::parse($lead->created_at)->diffForHumans()
                        : 'recently',
                    'icon' => 'chat-dots',
                    'color' => 'admin-success',
                    'sort_time' => !empty($lead->created_at) ? strtotime($lead->created_at) : 0,
                ];
            }
        }

        usort($activities, function ($a, $b) {
            return ($b['sort_time'] ?? 0) <=> ($a['sort_time'] ?? 0);
        });

        return array_slice($activities, 0, 8);
    }

    public function search(\Illuminate\Http\Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if ($q === '') {
            return redirect()->route('admin.dashboard');
        }

        // 1. products
        if (Schema::hasTable('products')) {
            $productMatch = DB::table('products')
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%")
                        ->orWhere('sku', 'like', "%{$q}%");
                })
                ->exists();

            if ($productMatch) {
                return redirect()->route('admin.products.index', ['search' => $q]);
            }
        }

        // 2. newsletter
        if (Schema::hasTable('newsletter_subscriptions')) {
            $newsletterMatch = DB::table('newsletter_subscriptions')
                ->where('email', 'like', "%{$q}%")
                ->exists();

            if ($newsletterMatch) {
                return redirect()->route('admin.newsletter.index', ['search' => $q]);
            }
        }

        // 3. quotes
        if (Schema::hasTable('quotes')) {
            $quoteMatch = DB::table('quotes')
                ->where(function ($query) use ($q) {
                    $query->where('full_name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('quote_type', 'like', "%{$q}%");
                })
                ->exists();

            if ($quoteMatch) {
                return redirect()->route('admin.quotes.index', ['search' => $q]);
            }
        }

        return redirect()->route('admin.dashboard')->with('searchError', 'No matching results found.');
    }
}
