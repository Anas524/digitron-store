@extends('layouts.admin')

@section('title', 'Order Details | Digitron Command Center')

@section('content')
<div class="relative min-h-full text-white">
    <!-- Mobile top bar -->
    <div class="lg:hidden sticky top-0 z-20 glass-panel border-b border-white/10 px-4 py-4 mb-4">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <button
                    type="button"
                    class="w-11 h-11 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 transition shrink-0"
                    @click.stop="sidebarOpen = true"
                    aria-label="Open admin sidebar">
                    <i class="bi bi-list text-xl"></i>
                </button>

                <div class="min-w-0">
                    <h1 class="text-2xl font-bold leading-tight">Order Details</h1>
                    <p class="text-sm text-slate-400 truncate">{{ $order->order_number }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6 p-0 lg:p-6">
        <div class="hidden lg:block">
            <h1 class="text-3xl font-bold text-white">Order Details</h1>
            <p class="text-slate-400">{{ $order->order_number }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Customer Information</h2>
                <div class="space-y-2 text-slate-300">
                    <p><span class="text-white font-medium">Name:</span> {{ $order->full_name }}</p>
                    <p class="break-all"><span class="text-white font-medium">Email:</span> {{ $order->email }}</p>
                    <p><span class="text-white font-medium">Phone:</span> {{ $order->phone }}</p>
                    <p><span class="text-white font-medium">City:</span> {{ $order->city }}</p>
                    <p><span class="text-white font-medium">Address:</span> {{ $order->address }}</p>
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Order Information</h2>

                <div class="space-y-2 text-slate-300 mb-5">
                    <p><span class="text-white font-medium">Payment Method:</span> {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
                    <p><span class="text-white font-medium">Payment Status:</span> {{ ucfirst($order->payment_status) }}</p>
                    <p><span class="text-white font-medium">Subtotal:</span> AED {{ number_format($order->subtotal, 2) }}</p>
                    <p><span class="text-white font-medium">Tax:</span> AED {{ number_format($order->tax, 2) }}</p>
                    <p><span class="text-white font-medium">Total:</span> AED {{ number_format($order->total_amount, 2) }}</p>
                </div>

                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}" class="space-y-3">
                    @csrf
                    @method('PUT')

                    <label class="block text-sm font-medium text-white">Update Order Status</label>
                    <select name="order_status" class="w-full rounded-xl border border-white/10 bg-slate-900 text-white px-4 py-3">
                        <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>

                    <button type="submit"
                        class="rounded-xl bg-brand-accent px-5 py-3 font-semibold text-black hover:opacity-90">
                        Save Status
                    </button>
                </form>
            </div>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Items</h2>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[650px] text-sm">
                    <thead>
                        <tr class="border-b border-white/10 text-slate-400">
                            <th class="py-3 text-left">Product</th>
                            <th class="py-3 text-left">SKU</th>
                            <th class="py-3 text-left">Price</th>
                            <th class="py-3 text-left">Qty</th>
                            <th class="py-3 text-left">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr class="border-b border-white/5">
                                <td class="py-3 text-white">{{ $item->product_name }}</td>
                                <td class="py-3 text-slate-300">{{ $item->product_sku }}</td>
                                <td class="py-3 text-white whitespace-nowrap">AED {{ number_format($item->product_price, 2) }}</td>
                                <td class="py-3 text-white">{{ $item->quantity }}</td>
                                <td class="py-3 text-white whitespace-nowrap">AED {{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection