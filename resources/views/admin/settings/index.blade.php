@extends('layouts.admin')

@section('title', 'Settings | Digitron Admin')

@section('content')

<div class="max-w-4xl mx-auto">

    <h1 class="text-2xl font-bold mb-6 text-white">Store Settings</h1>

    @if(session('success'))
    <div class="mb-4 p-4 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf

        {{-- TAX --}}
        <div class="glass-panel p-6 rounded-2xl border border-white/10">
            <h2 class="font-bold text-white mb-4">Tax Settings</h2>

            <label class="block text-sm text-gray-400 mb-2">Tax Percentage (%)</label>
            <input type="number" step="0.01"
                name="tax_percent"
                value="{{ $tax }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-brand-accent focus:outline-none">
        </div>

        {{-- SHIPPING --}}
        <div class="glass-panel p-6 rounded-2xl border border-white/10">
            <h2 class="font-bold text-white mb-4">Shipping Settings</h2>

            <label class="block text-sm text-gray-400 mb-2">Shipping Type</label>
            <select name="shipping_type"
                class="w-full px-4 py-3 rounded-xl bg-[#111827] text-white border border-white/10 focus:border-brand-accent focus:outline-none appearance-none"
                style="color-scheme: dark;">
                <option value="free" {{ $shipping_type=='free'?'selected':'' }}>Free Shipping</option>
                <option value="flat" {{ $shipping_type=='flat'?'selected':'' }}>Flat Rate</option>
                <option value="conditional" {{ $shipping_type=='conditional'?'selected':'' }}>Free Above Amount</option>
            </select>

            <div class="mt-4">
                <label class="block text-sm text-gray-400 mb-2">Flat Shipping Rate (AED)</label>
                <input type="number" step="0.01"
                    name="shipping_flat_rate"
                    value="{{ $flat_rate }}"
                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-brand-accent focus:outline-none">
            </div>

            <div class="mt-4">
                <label class="block text-sm text-gray-400 mb-2">Free Shipping Minimum (AED)</label>
                <input type="number" step="0.01"
                    name="free_shipping_min"
                    value="{{ $free_min }}"
                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-brand-accent focus:outline-none">
            </div>
        </div>

        {{-- SAVE --}}
        <div class="text-right">
            <button type="submit"
                class="px-6 py-3 rounded-xl bg-gradient-to-r from-brand-accent to-brand-secondary text-black font-bold hover:shadow-lg transition-all">
                Save Settings
            </button>
        </div>

    </form>
</div>

@endsection