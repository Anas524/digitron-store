@extends('layouts.app')

@section('content')
@php
  $name = $productName ?? 'Product Name';
@endphp

<section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid gap-8 lg:grid-cols-2">
        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
            <div class="aspect-square rounded-2xl border border-white/10 bg-slate-950/40 grid place-items-center text-slate-500">
                Product Image
            </div>

            <div class="mt-4 grid grid-cols-4 gap-3">
                @for($i=0; $i<4; $i++)
                    <div class="aspect-square rounded-xl border border-white/10 bg-slate-950/40 grid place-items-center text-slate-600">IMG</div>
                @endfor
            </div>
        </div>

        <div>
            <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs text-slate-300">
                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                In Stock
            </div>

            <h1 class="mt-4 text-2xl sm:text-3xl font-extrabold tracking-tight">{{ $name }}</h1>

            <div class="mt-3 flex items-center gap-3">
                <div class="text-xl font-bold">AED 2,799</div>
                <div class="text-sm text-slate-400 line-through">AED 3,099</div>
            </div>

            <p class="mt-4 text-sm text-slate-300">
                Premium product description goes here. Clean, tech-oriented layout with compatibility-focused specs.
            </p>

            <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
                @foreach(['Socket: AM5','RAM: DDR5','Chipset: B650','GPU: PCIe 4.0','Warranty: 1 Year','Condition: New'] as $spec)
                    <div class="rounded-xl border border-white/10 bg-white/5 p-3 text-xs text-slate-300">
                        {{ $spec }}
                    </div>
                @endforeach
            </div>

            <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                <button class="w-full sm:w-auto rounded-xl bg-white px-6 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-200">
                    Add to Cart
                </button>
                <button class="w-full sm:w-auto rounded-xl border border-white/10 bg-white/5 px-6 py-3 text-sm font-semibold hover:bg-white/10">
                    Buy Now
                </button>
            </div>

            <div class="mt-10 rounded-2xl border border-white/10 bg-white/5 p-5">
                <div class="text-sm font-semibold">Compatibility Notes</div>
                <ul class="mt-3 space-y-2 text-sm text-slate-300 list-disc pl-5">
                    <li>Ensure motherboard socket matches CPU socket.</li>
                    <li>Check RAM type and supported frequency.</li>
                    <li>Verify PSU wattage for GPU requirements.</li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection
