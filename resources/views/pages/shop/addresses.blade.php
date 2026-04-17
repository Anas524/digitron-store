@extends('layouts.app')

@section('title', 'Saved Addresses | Digitron Computers UAE')
@section('page', 'addresses')

@section('content')
<section class="pb-16" style="padding-top: calc(var(--headerH, 96px) + 2rem);">
    <div class="max-w-5xl mx-auto">
        <div class="mb-8">
            <h1 class="text-4xl font-display font-bold text-white mb-2">Saved Addresses</h1>
            <p class="text-gray-400">Manage your delivery addresses for faster checkout.</p>
        </div>

        @if(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 text-emerald-300 px-4 py-3">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 text-red-200 px-4 py-3">
            <ul class="list-disc ps-5 space-y-1">
                @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="glass-panel rounded-2xl p-6 border border-white/10 mb-8">
            <h2 class="text-xl font-bold mb-4">Add New Address</h2>

            <form method="POST" action="{{ route('addresses.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input name="full_name" type="text" value="{{ old('full_name', auth()->user()->name) }}" placeholder="Full Name"
                        class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">

                    <input name="email" type="email" value="{{ old('email', auth()->user()->email) }}" placeholder="Email Address"
                        class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input name="phone" type="text" value="{{ old('phone') }}" placeholder="Phone Number"
                        class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">

                    <input name="city" type="text" value="{{ old('city') }}" placeholder="City"
                        class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">
                </div>

                <textarea name="address" rows="4" placeholder="Full Delivery Address"
                    class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">{{ old('address') }}</textarea>

                <button type="submit"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-brand-accent text-black font-bold hover:opacity-90">
                    Save Address
                </button>
            </form>
        </div>

        <div class="space-y-6">
            @forelse($addresses as $address)
            <div class="glass-panel rounded-2xl p-6 border border-white/10">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-lg font-bold text-white">{{ $address->full_name }}</h3>
                            @if($address->is_default)
                            <span class="px-3 py-1 rounded-full bg-brand-accent/10 text-brand-accent text-xs font-medium">Default</span>
                            @endif
                        </div>

                        <div class="space-y-1 text-sm text-gray-300">
                            <div>{{ $address->email }}</div>
                            <div>{{ $address->phone }}</div>
                            <div>{{ $address->city }}</div>
                            <div>{{ $address->address }}</div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button type="button"
                            onclick="document.getElementById('edit-address-{{ $address->id }}').classList.toggle('hidden')"
                            class="px-4 py-2 rounded-xl border border-brand-accent/20 text-brand-accent hover:bg-brand-accent/10">
                            Edit
                        </button>

                        @if(!$address->is_default)
                        <form method="POST" action="{{ route('addresses.default', $address) }}">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 rounded-xl border border-white/10 text-white hover:bg-white/5">
                                Make Default
                            </button>
                        </form>
                        @endif

                        <form method="POST" action="{{ route('addresses.delete', $address) }}"
                            onsubmit="return confirm('Delete this address?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 rounded-xl border border-red-500/20 text-red-300 hover:bg-red-500/10">
                                Delete
                            </button>
                        </form>
                    </div>

                    <div id="edit-address-{{ $address->id }}" class="hidden mt-6 border-t border-white/10 pt-6">
                        <h4 class="text-lg font-bold mb-4 text-white">Edit Address</h4>

                        <form method="POST" action="{{ route('addresses.update', $address) }}" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input name="full_name" type="text" value="{{ old('full_name', $address->full_name) }}" placeholder="Full Name"
                                    class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">

                                <input name="email" type="email" value="{{ old('email', $address->email) }}" placeholder="Email Address"
                                    class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input name="phone" type="text" value="{{ old('phone', $address->phone) }}" placeholder="Phone Number"
                                    class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">

                                <input name="city" type="text" value="{{ old('city', $address->city) }}" placeholder="City"
                                    class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">
                            </div>

                            <textarea name="address" rows="4" placeholder="Full Delivery Address"
                                class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">{{ old('address', $address->address) }}</textarea>

                            <div class="flex gap-3">
                                <button type="submit"
                                    class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-brand-accent text-black font-bold hover:opacity-90">
                                    Update Address
                                </button>

                                <button type="button"
                                    onclick="document.getElementById('edit-address-{{ $address->id }}').classList.add('hidden')"
                                    class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-white/10 text-white hover:bg-white/5">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="glass-panel rounded-2xl p-10 border border-white/10 text-center text-gray-500">
                No saved addresses yet.
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection