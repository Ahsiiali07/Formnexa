@extends('layouts.app')

@section('title', 'Sign In — Formnexa')

@section('content')
<div class="min-h-[calc(100vh-140px)] bg-[#f8f8f3] flex items-center justify-center py-10 sm:py-14 px-4 sm:px-6">
    <div class="w-full max-w-md space-y-5 sm:space-y-6">

        <!-- Brand & Context Header -->
        <div class="text-center space-y-2">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2.5 mb-1">
                <span class="fn-brand-mark" style="width: 44px; height: 44px; border-radius: 13px; gap: 3.5px;" aria-hidden="true">
                    <i class="b1" style="width: 4.5px; height: 21px;"></i>
                    <i class="b2" style="width: 4.5px; height: 15px;"></i>
                    <i class="b3" style="width: 4.5px; height: 22px;"></i>
                </span>
            </a>
            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-[#17211b] tracking-[-0.03em]">
                Sign in to Formnexa
            </h1>
            <p class="text-xs sm:text-sm text-[#536058]">
                Access your saved invoices, quotes, receipts, and history across any device.
            </p>
        </div>

        <!-- Optional Note Banner -->
        <div class="p-3.5 rounded-xl bg-[#e8f7ed] border border-[#c8f3dc] text-xs text-[#17211b] flex items-start gap-2.5">
            <span class="material-symbols-outlined text-[#184f3a] text-[18px] shrink-0 mt-0.5">info</span>
            <div>
                <strong>Account is optional:</strong> You can generate &amp; download documents anytime without logging in. Sign in only if you want to sync &amp; view your history.
            </div>
        </div>

        <!-- Login Form Card -->
        <div class="bg-white rounded-2xl shadow-lg shadow-[#17211b]/5 border border-[#dfe4df] p-6 sm:p-8">
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-xs text-rose-700 font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('success'))
                <div class="mb-5 p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-[#536058] mb-1.5">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@company.com"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#dfe4df] text-sm text-[#17211b] placeholder:text-[#68736d]/70 focus:border-[#184f3a] focus:ring-2 focus:ring-[#184f3a]/15 focus:outline-none transition">
                </div>

                <div>
                    <label for="password" class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-[#536058] mb-1.5">Password</label>
                    <input id="password" type="password" name="password" required placeholder="Enter your password"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#dfe4df] text-sm text-[#17211b] placeholder:text-[#68736d]/70 focus:border-[#184f3a] focus:ring-2 focus:ring-[#184f3a]/15 focus:outline-none transition">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="inline-flex items-center gap-2 text-[#536058] cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-[#c6d1ca] text-[#184f3a] focus:ring-[#184f3a]">
                        <span>Keep me signed in</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 px-6 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-sm font-bold shadow-md shadow-[#184f3a]/20 transition">
                    Sign In &amp; View History
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-[#dfe4df] text-center text-xs text-[#536058]">
                Don't have an account yet?
                <a href="{{ route('register') }}" class="font-bold text-[#184f3a] hover:underline ml-1">Create a free account &rarr;</a>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('invoice.studio') }}" class="text-xs font-semibold text-[#536058] hover:text-[#17211b] transition">
                &larr; Continue as Guest in Document Studio
            </a>
        </div>
    </div>
</div>
@endsection
