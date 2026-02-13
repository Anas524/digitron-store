@extends('layouts.app')

@section('title','Admin Login')

@section('content')
<div class="max-w-md mx-auto mt-28 p-6 rounded-2xl border border-white/10 bg-white/5">
    <h1 class="text-2xl font-bold mb-2">Admin Login</h1>

    @if(session('error'))
        <div class="mb-3 rounded-xl border border-red-500/30 bg-red-500/10 text-red-200 text-sm px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-3 rounded-xl border border-red-500/30 bg-red-500/10 text-red-200 text-sm px-4 py-3">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-3">
        @csrf

        <div>
            <label class="text-xs text-gray-400">Email</label>
            <input name="email" type="email" required
                class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none"
                value="{{ old('email') }}">
        </div>

        <div>
            <label class="text-xs text-gray-400">Password</label>
            <input name="password" type="password" required
                class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none">
        </div>

        <button type="submit"
            class="w-full py-3 rounded-xl bg-brand-accent text-black font-bold hover:bg-white transition">
            Login
        </button>
    </form>
</div>
@endsection
