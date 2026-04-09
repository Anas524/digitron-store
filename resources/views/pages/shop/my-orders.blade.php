@extends('layouts.app')

@section('title', 'My Orders | Digitron Computers UAE')
@section('page', 'my-orders')

@section('content')
<section class="pb-16" style="padding-top: calc(var(--headerH, 96px) + 2rem);">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-4xl font-display font-bold text-white mb-2">My Orders</h1>
            <p class="text-gray-400">Track your orders and view order history.</p>
        </div>

        <div class="space-y-6">
            @forelse($orders as $order)
                <div class="glass-panel rounded-2xl p-6 border border-white/10">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                        <div>
                            <div class="text-sm text-gray-500">Order Number</div>
                            <div class="text-lg font-bold text-white">{{ $order->order_number }}</div>
                        </div>

                        <div class="flex flex-wrap gap-3 text-sm">
                            <span class="px-3 py-1 rounded-full bg-white/5 text-gray-300">{{ ucfirst($order->order_status) }}</span>
                            <span class="px-3 py-1 rounded-full bg-brand-accent/10 text-brand-accent">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                            <span class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-300">{{ ucfirst($order->payment_status) }}</span>
                        </div>
                    </div>

                    <div class="space-y-3 mb-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between gap-4 border-b border-white/5 pb-3">
                                <div>
                                    <div class="text-white font-medium">{{ $item->product_name }}</div>
                                    <div class="text-xs text-gray-500">Qty: {{ $item->quantity }} | SKU: {{ $item->product_sku }}</div>
                                </div>
                                <div class="text-white font-bold">AED {{ number_format($item->subtotal, 2) }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                        <div class="text-xl font-bold text-brand-accent">AED {{ number_format($order->total_amount, 2) }}</div>
                    </div>
                </div>
            @empty
                <div class="glass-panel rounded-2xl p-10 border border-white/10 text-center text-gray-500">
                    No orders found yet.
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection