@extends('layouts.app')

@section('title', 'Checkout | Digitron Computers UAE')

@section('whatsapp_message', 'Hello Digitron Computers UAE, I need help with my checkout and order process.')

@section('page', 'checkout')

@section('fullwidth')
<section class="relative h-[34vh] min-h-[260px] overflow-hidden flex items-center justify-center pt-28 sm:pt-32 md:pt-36 pb-16">
    <div class="absolute inset-0 w-full h-full z-0">
        <img src="{{ asset('images/slide1.jpg') }}"
            alt="Checkout Background"
            class="w-full h-full object-cover opacity-40 scale-110">
        <div class="absolute inset-0 bg-gradient-to-b from-[#070A12]/20 via-[#070A12]/45 to-[#070A12]/70"></div>
    </div>

    <div class="absolute inset-0 bg-grid-pattern opacity-[0.05] pointer-events-none"></div>

    <div class="relative z-10 text-center px-4">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-brand-accent/30 bg-brand-accent/10 mb-5">
            <i class="bi bi-credit-card text-brand-accent"></i>
            <span class="text-brand-accent text-sm font-bold uppercase tracking-wider">Secure Checkout</span>
        </div>

        <h1 class="text-4xl md:text-6xl font-display font-black mb-3 tracking-tight">
            CHECK<span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-brand-secondary">OUT</span>
        </h1>

        <p class="text-base md:text-lg text-gray-400 max-w-2xl mx-auto">
            Complete your shipping details and place your Cash on Delivery order.
        </p>
    </div>
</section>
@endsection

@section('content')
<section class="py-12 -mt-8 relative z-20">
    <form method="POST" action="{{ route('checkout.place') }}" id="checkoutForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-6">
                <div class="glass-panel rounded-2xl p-6 border border-white/10">
                    <div class="flex items-center justify-between relative">
                        <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-white/10 -translate-y-1/2"></div>
                        <div class="absolute top-1/2 left-0 w-full h-0.5 bg-brand-accent -translate-y-1/2"></div>

                        @foreach(['Cart', 'Shipping', 'Complete'] as $index => $step)
                            <div class="relative flex flex-col items-center gap-2 z-10">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all bg-brand-accent text-black shadow-lg shadow-brand-accent/50">
                                    {{ $index + 1 }}
                                </div>
                                <span class="text-xs text-white">{{ $step }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="glass-panel rounded-2xl p-6 border border-white/10">
                    <h2 class="text-xl font-bold mb-4">Shipping Details</h2>

                    @if ($errors->any())
                        <div class="mb-4 rounded-xl border border-red-500/30 bg-red-500/10 text-red-200 text-sm px-4 py-3">
                            <ul class="list-disc ps-4 space-y-1">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input name="full_name" type="text" value="{{ old('full_name', $defaultAddress->full_name ?? auth()->user()->name) }}" placeholder="Full Name"
                            class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">

                        <input name="email" type="email" value="{{ old('email', $defaultAddress->email ?? auth()->user()->email) }}" placeholder="Email Address"
                            class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <input name="phone" type="text" value="{{ old('phone', $defaultAddress->phone ?? '') }}" placeholder="Phone Number"
                            class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">

                        <input name="city" type="text" value="{{ old('city', $defaultAddress->city ?? '') }}" placeholder="City"
                            class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">
                    </div>

                    <textarea name="address" placeholder="Full Delivery Address" rows="4"
                        class="w-full mt-4 rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">{{ old('address', $defaultAddress->address ?? '') }}</textarea>
                </div>

                <div class="glass-panel rounded-2xl p-6 border border-white/10">
                    <h2 class="text-xl font-bold mb-4">Payment Method</h2>

                    <div class="space-y-3">
                        <label class="flex items-center gap-3 rounded-xl border border-brand-accent/30 bg-brand-accent/10 px-4 py-4 cursor-pointer">
                            <input type="radio" name="payment_method" value="cash_on_delivery" checked>
                            <span>Cash on Delivery</span>
                        </label>

                        <label class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 px-4 py-4 cursor-pointer opacity-60">
                            <input type="radio" disabled>
                            <span>Card Payment (Coming Soon)</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="sticky top-24 glass-panel rounded-2xl p-6 border border-white/10">
                    <h3 class="text-xl font-bold mb-6">Order Summary</h3>

                    <div class="space-y-4 mb-6">
                        @foreach($items as $item)
                            <div class="flex items-center gap-3">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-14 h-14 rounded-lg object-cover bg-white/5">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-white truncate">{{ $item['name'] }}</div>
                                    <div class="text-xs text-gray-500">Qty: {{ $item['qty'] }}</div>
                                </div>
                                <div class="text-sm font-bold text-white">AED {{ number_format($item['subtotal']) }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-3 text-sm border-t border-white/10 pt-4">
                        <div class="flex justify-between text-gray-400">
                            <span>Subtotal</span>
                            <span>AED {{ number_format($subtotal) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>Tax</span>
                            <span>AED {{ number_format($tax) }}</span>
                        </div>
                        <div class="flex justify-between text-white text-lg font-bold pt-2 border-t border-white/10">
                            <span>Total</span>
                            <span>AED {{ number_format($total) }}</span>
                        </div>
                    </div>

                    <button id="placeOrderBtn" type="submit"
                        class="w-full mt-6 py-4 rounded-xl bg-gradient-to-r from-brand-accent to-brand-secondary text-black font-bold text-lg flex items-center justify-center gap-2">
                        <span class="btn-label">Place Order</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection