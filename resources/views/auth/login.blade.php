@extends('layouts.app')

@section('title', 'Sign In — Formnexa')

@section('content')
<x-auth-shell
    badge="Welcome Back"
    title="Sign in to Formnexa"
    subtitle="Access your saved invoices, quotes, receipts, and ATS resumes from any device."
    panelTitle="Pick up right where you left off."
    panelSubtitle="Sign in to sync your document history across desktop and mobile, track invoice status, and export PDFs anytime."
    footerBackRoute="invoice.studio"
    footerBackLabel="Continue as Guest in Document Studio">

    <!-- Optional Guest Notice -->
    <div class="mb-5 p-3.5 rounded-xl bg-[#e8f7ed] border border-[#c8f3dc] text-xs text-[#17211b] flex items-start gap-2.5">
        <x-icon name="info" class="w-[18px] h-[18px] text-[#184f3a] mt-0.5" />
        <div>
            <strong>Account is optional:</strong> You can always create and download documents without logging in. Sign in only when you want cloud history sync.
        </div>
    </div>

    <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-[#536058] mb-1.5">
                Email Address
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="you@company.com"
                   class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#dfe4df] text-sm text-[#17211b] placeholder:text-[#68736d]/70 focus:border-[#184f3a] focus:ring-2 focus:ring-[#184f3a]/15 focus:outline-none transition">
        </div>

        <x-auth-password-field
            id="password"
            name="password"
            label="Password"
            placeholder="Enter your password"
            autocomplete="current-password" />

        <div class="flex items-center justify-between gap-2 text-xs pt-0.5">
            <label class="inline-flex items-center gap-2 text-[#536058] cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-[#c6d1ca] text-[#184f3a] focus:ring-[#184f3a]">
                <span>Keep me signed in</span>
            </label>

            <a href="{{ route('password.request') }}" class="font-bold text-[#184f3a] hover:underline">
                Forgot password?
            </a>
        </div>

        <button type="submit" class="w-full py-3 px-6 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-sm font-bold shadow-md shadow-[#184f3a]/20 transition cursor-pointer mt-1">
            Sign In &amp; View History &rarr;
        </button>
    </form>

    <div class="mt-6 pt-5 border-t border-[#dfe4df] text-center text-xs text-[#536058]">
        Don't have an account yet?
        <a href="{{ route('register') }}" class="font-bold text-[#184f3a] hover:underline ml-1">Create a free account &rarr;</a>
    </div>
</x-auth-shell>
@endsection
