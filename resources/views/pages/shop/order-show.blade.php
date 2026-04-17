@extends('layouts.app')

@section('title', 'Order Details | Digitron Computers UAE')
@section('page', 'order-show')

@section('content')
<section class="pb-16" style="padding-top: calc(var(--headerH, 96px) + 2rem);">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-4xl font-display font-bold text-white mb-2">Order Details</h1>
                <p class="text-gray-400">Track your order and review purchased items.</p>
            </div>
            <a href="{{ route('my.orders') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-white/10 text-white hover:bg-white/5">
                <i class="bi bi-arrow-left"></i> Back to Orders
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 glass-panel rounded-2xl p-6 border border-white/10">
                <h2 class="text-2xl font-bold mb-4 text-white">Order Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                    <div>
                        <div class="text-gray-500">Order Number</div>
                        <div class="text-white font-semibold">{{ $order->order_number }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Date</div>
                        <div class="text-white font-semibold">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Payment Method</div>
                        <div class="text-white font-semibold">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Payment Status</div>
                        <div class="text-white font-semibold">{{ ucfirst($order->payment_status) }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Order Status</div>
                        <div class="text-white font-semibold">{{ ucfirst($order->order_status) }}</div>
                    </div>
                </div>

                <div class="border-t border-white/10 pt-4">
                    <h3 class="text-lg font-bold mb-4">Items</h3>

                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between gap-4 border-b border-white/5 pb-3">
                                <div>
                                    <div class="text-white font-medium">{{ $item->product_name }}</div>
                                    <div class="text-xs text-gray-500">
                                        Qty: {{ $item->quantity }}
                                        @if($item->product_sku)
                                            | SKU: {{ $item->product_sku }}
                                        @endif
                                    </div>
                                </div>
                                <div class="text-white font-bold">AED {{ number_format($item->subtotal, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-white/10 pt-4 mt-6">
                    <h3 class="text-lg font-bold mb-4">Shipping Information</h3>
                    <div class="space-y-2 text-sm text-gray-300">
                        <div><span class="text-gray-500">Name:</span> {{ $order->full_name }}</div>
                        <div><span class="text-gray-500">Email:</span> {{ $order->email }}</div>
                        <div><span class="text-gray-500">Phone:</span> {{ $order->phone }}</div>
                        <div><span class="text-gray-500">City:</span> {{ $order->city }}</div>
                        <div><span class="text-gray-500">Address:</span> {{ $order->address }}</div>
                    </div>
                </div>
            </div>

            <div class="glass-panel rounded-2xl p-6 border border-white/10">
                <h3 class="text-xl font-bold mb-4">Summary</h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-gray-400">
                        <span>Subtotal</span>
                        <span>AED {{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-400">
                        <span>Tax</span>
                        <span>AED {{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-white text-lg font-bold border-t border-white/10 pt-3">
                        <span>Total</span>
                        <span>AED {{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection