@extends('layouts.app')

@section('title', 'Create Free Account — Formnexa')

@section('content')
<div class="min-h-[calc(100vh-140px)] bg-[#F8FAFC] flex items-center justify-center py-10 sm:py-14 px-4 sm:px-6">
    <div class="w-full max-w-md space-y-5 sm:space-y-6">

        <!-- Brand & Context Header -->
        <div class="text-center space-y-2">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2.5 mb-1">
                <div class="w-11 h-11 rounded-xl bg-primary-container flex items-center justify-center shadow-md shadow-[#0066FF]/25">
                    <img src="{{ asset('images/formnexa-icon-white.png') }}" alt="Formnexa" class="w-6 h-6 object-contain">
                </div>
            </a>
            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-[#0b1c30] tracking-[-0.03em]">
                Create a free account
            </h1>
            <p class="text-xs sm:text-sm text-[#565e74]">
                Save your billing history, track payment status, and reload invoices anytime.
            </p>
        </div>

        <!-- Optional Note Banner -->
        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200/80 text-xs text-emerald-950 flex items-start gap-2.5">
            <span class="material-symbols-outlined text-emerald-600 text-[18px] shrink-0 mt-0.5">verified</span>
            <div>
                <strong>Optional &amp; Free Forever:</strong> Creating an account unlocks cloud invoice history without restricting any document generator tools.
            </div>
        </div>

        <!-- Signup Form Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-border-subtle p-6 sm:p-8">
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-xs text-rose-700 font-medium space-y-1">
                    @foreach ($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-[#565e74] mb-1.5">Full Name or Business Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe / Acme Studio"
                           class="w-full px-3.5 py-2.5 rounded-lg bg-white border border-border-subtle text-sm text-[#0b1c30] placeholder:text-[#727687]/70 focus:border-[#0066FF] focus:ring-2 focus:ring-[#0066FF]/15 focus:outline-none transition">
                </div>

                <div>
                    <label for="email" class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-[#565e74] mb-1.5">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="you@company.com"
                           class="w-full px-3.5 py-2.5 rounded-lg bg-white border border-border-subtle text-sm text-[#0b1c30] placeholder:text-[#727687]/70 focus:border-[#0066FF] focus:ring-2 focus:ring-[#0066FF]/15 focus:outline-none transition">
                </div>

                <div>
                    <label for="password" class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-[#565e74] mb-1.5">Password (Min. 6 chars)</label>
                    <input id="password" type="password" name="password" required placeholder="Create a password"
                           class="w-full px-3.5 py-2.5 rounded-lg bg-white border border-border-subtle text-sm text-[#0b1c30] placeholder:text-[#727687]/70 focus:border-[#0066FF] focus:ring-2 focus:ring-[#0066FF]/15 focus:outline-none transition">
                </div>

                <div>
                    <label for="password_confirmation" class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-[#565e74] mb-1.5">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Re-enter password"
                           class="w-full px-3.5 py-2.5 rounded-lg bg-white border border-border-subtle text-sm text-[#0b1c30] placeholder:text-[#727687]/70 focus:border-[#0066FF] focus:ring-2 focus:ring-[#0066FF]/15 focus:outline-none transition">
                </div>

                <button type="submit" class="w-full py-3 px-6 rounded-lg bg-primary-container hover:bg-electric-blue-hover text-white text-sm font-bold shadow-md shadow-[#0066FF]/20 transition mt-2">
                    Create Free Account
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-border-subtle text-center text-xs text-[#565e74]">
                Already have an account?
                <a href="{{ route('login') }}" class="font-bold text-[#0050cb] hover:underline ml-1">Sign In &rarr;</a>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('invoice.studio') }}" class="text-xs font-semibold text-[#565e74] hover:text-[#0b1c30] transition">
                &larr; Continue as Guest without Signing Up
            </a>
        </div>
    </div>
</div>
@endsection
