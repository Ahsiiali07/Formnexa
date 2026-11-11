@extends('layouts.app')

@section('title', 'Create Free Account — Formnexa')

@section('content')
<div class="min-h-[calc(100vh-140px)] bg-[#F6F5F1] flex items-center justify-center py-12 px-4 sm:px-6">
    <div class="w-full max-w-md space-y-6">

        <!-- Brand & Context Header -->
        <div class="text-center space-y-2">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2.5 mb-2">
                <img src="{{ asset('images/formnexa-mark.png') }}" alt="Formnexa" class="h-11 w-auto object-contain">
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#111111] tracking-[-0.03em]">
                Create a free account
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-sans-inter">
                Save your billing history, track payment status, and reload invoices anytime.
            </p>
        </div>

        <!-- Optional Note Banner -->
        <div class="p-3.5 rounded-2xl bg-emerald-50/80 border border-emerald-200/70 text-xs text-emerald-950 flex items-start gap-2.5">
            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <strong>Optional &amp; Free Forever:</strong> Creating an account unlocks cloud invoice history without restricting any document generator tools.
            </div>
        </div>

        <!-- Signup Form Card -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/70 border border-slate-200/90 p-7 sm:p-9">
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 font-medium space-y-1">
                    @foreach ($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}" class="space-y-4 font-sans-inter">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Full Name or Business Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe / Acme Studio"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#0066FF] focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="you@company.com"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#0066FF] focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Password (Min. 6 chars)</label>
                    <input id="password" type="password" name="password" required placeholder="Create a password"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#0066FF] focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Re-enter password"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#0066FF] focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition">
                </div>

                <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-[#0066FF] hover:bg-blue-700 text-white text-sm font-bold shadow-lg shadow-blue-600/20 transition mt-2">
                    Create Free Account
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
                Already have an account?
                <a href="{{ route('login') }}" class="font-bold text-[#0066FF] hover:underline ml-1">Sign In &rarr;</a>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('invoice.studio') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                &larr; Continue as Guest without Signing Up
            </a>
        </div>
    </div>
</div>
@endsection
