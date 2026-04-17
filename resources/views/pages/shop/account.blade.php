@extends('layouts.app')

@section('title', 'My Account | Digitron Computers UAE')
@section('page', 'account')

@section('content')
<section class="pb-16" style="padding-top: calc(var(--headerH, 96px) + 2rem);">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-4xl font-display font-bold text-white mb-2">My Account</h1>
            <p class="text-gray-400">Manage your profile information.</p>
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

        <div class="glass-panel rounded-2xl p-6 border border-white/10">
            <form method="POST" action="{{ route('account.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Full Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Email Address</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-brand-accent">
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-brand-accent text-black font-bold hover:opacity-90">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection