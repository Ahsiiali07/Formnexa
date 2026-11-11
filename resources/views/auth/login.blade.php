@extends('layouts.app')

@section('title', 'Sign In — Formnexa')

@section('content')
<div class="min-h-[calc(100vh-140px)] bg-[#F6F5F1] flex items-center justify-center py-12 px-4 sm:px-6">
    <div class="w-full max-w-md space-y-6">

        <!-- Brand & Context Header -->
        <div class="text-center space-y-2">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2.5 mb-2">
                <img src="{{ asset('images/formnexa-mark.png') }}" alt="Formnexa" class="h-11 w-auto object-contain">
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#111111] tracking-[-0.03em]">
                Sign in to Formnexa
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-sans-inter">
                Access your saved invoices, quotes, receipts, and history across any device.
            </p>
        </div>

        <!-- Optional Note Banner -->
        <div class="p-3.5 rounded-2xl bg-blue-50/80 border border-blue-100 text-xs text-blue-900 flex items-start gap-2.5">
            <svg class="w-4 h-4 text-[#0066FF] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
            <div>
                <strong>Account is optional:</strong> You can generate &amp; download documents anytime without logging in. Sign in only if you want to keep &amp; view your history.
            </div>
        </div>

        <!-- Login Form Card -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/70 border border-slate-200/90 p-7 sm:p-9">
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('success'))
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}" class="space-y-5 font-sans-inter">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@company.com"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#0066FF] focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Password</label>
                    <input id="password" type="password" name="password" required placeholder="Enter your password"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#0066FF] focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="inline-flex items-center gap-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-[#0066FF] focus:ring-[#0066FF]">
                        <span>Keep me signed in</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-slate-900 hover:bg-[#0066FF] text-white text-sm font-bold shadow-lg shadow-slate-900/15 transition">
                    Sign In & View History
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
                Don't have an account yet?
                <a href="{{ route('register') }}" class="font-bold text-[#0066FF] hover:underline ml-1">Create a free account &rarr;</a>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('invoice.studio') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                &larr; Continue as Guest in Document Studio
            </a>
        </div>
    </div>
</div>
@endsection
