@extends('layouts.app')

@section('title', 'Create Free Account — Formnexa')

@section('content')
<x-auth-shell
    badge="Free Forever"
    title="Create your free account"
    subtitle="Sign up in seconds and verify your inbox with a 6-digit OTP code to unlock synced document history."
    panelTitle="Build, save &amp; track every document."
    panelSubtitle="Create invoices, receipts, resignation letters, paystubs, and ATS-ready resumes—backed by verified account security."
    :steps="['Create Account', 'Verify Email OTP', 'Enter Workspace']"
    :activeStep="1"
    footerBackRoute="invoice.studio"
    footerBackLabel="Continue as Guest without Signing Up">

    <form method="POST" action="{{ route('register.submit') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-[#536058] mb-1.5">
                Full Name or Business Name
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe / Acme Studio"
                   class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#dfe4df] text-sm text-[#17211b] placeholder:text-[#68736d]/70 focus:border-[#184f3a] focus:ring-2 focus:ring-[#184f3a]/15 focus:outline-none transition">
        </div>

        <div>
            <label for="email" class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-[#536058] mb-1.5">
                Email Address
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@company.com"
                   class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#dfe4df] text-sm text-[#17211b] placeholder:text-[#68736d]/70 focus:border-[#184f3a] focus:ring-2 focus:ring-[#184f3a]/15 focus:outline-none transition">
        </div>

        <x-auth-password-field
            id="password"
            name="password"
            label="Password"
            hint="Min. 8 characters"
            placeholder="Create a strong password"
            autocomplete="new-password"
            :showStrength="true" />

        <x-auth-password-field
            id="password_confirmation"
            name="password_confirmation"
            label="Confirm Password"
            placeholder="Re-enter your password"
            autocomplete="new-password" />

        <div class="p-3 rounded-xl bg-[#f8f8f3] border border-[#dfe4df] text-[11px] text-[#536058] flex items-center gap-2">
            <x-icon name="shield-check" class="w-4 h-4 text-[#184f3a]" />
            <span>We will email a 6-digit verification code to confirm your inbox.</span>
        </div>

        <button type="submit" class="w-full py-3 px-6 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-sm font-bold shadow-md shadow-[#184f3a]/20 transition cursor-pointer">
            Create Account &amp; Send OTP &rarr;
        </button>
    </form>

    <div class="mt-6 pt-5 border-t border-[#dfe4df] text-center text-xs text-[#536058]">
        Already have an account?
        <a href="{{ route('login') }}" class="font-bold text-[#184f3a] hover:underline ml-1">Sign In &rarr;</a>
    </div>
</x-auth-shell>
@endsection
