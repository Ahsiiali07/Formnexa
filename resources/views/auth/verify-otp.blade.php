@extends('layouts.app')

@section('title', 'Verify Email OTP — Formnexa')

@section('content')
<x-auth-shell
    badge="Email Verification"
    title="Check your inbox"
    subtitle="Enter the 6-digit verification code sent to your email address to activate your Formnexa workspace."
    panelTitle="One quick security step."
    panelSubtitle="Verifying your email ensures only you can access saved invoices, claim guest drafts, or reset your password."
    :steps="['Create Account', 'Verify Email OTP', 'Enter Workspace']"
    :activeStep="2"
    footerBackRoute="register"
    footerBackLabel="Use a Different Email Address">

    <div class="mb-5 p-3.5 rounded-xl bg-[#f8f8f3] border border-[#dfe4df] flex flex-wrap items-center justify-between gap-2 text-xs">
        <div class="flex items-center gap-2.5 text-[#17211b]">
            <span class="w-8 h-8 rounded-lg bg-[#e8f7ed] text-[#184f3a] flex items-center justify-center font-bold shrink-0">@</span>
            <div>
                <div class="text-[11px] text-[#68736d] uppercase tracking-wider font-mono">Code sent to</div>
                <div class="font-bold text-[#17211b]">{{ $maskedEmail }}</div>
            </div>
        </div>
        <span class="px-2.5 py-1 rounded-full bg-[#e8f7ed] text-[#184f3a] font-mono text-[10px] font-bold uppercase tracking-wider">
            Valid 10 min
        </span>
    </div>

    <form method="POST" action="{{ route('verification.verify') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <x-auth-otp-input name="code" label="6-Digit Verification Code" :initialValue="old('code', '')" />

        <button type="submit" class="w-full py-3 px-6 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-sm font-bold shadow-md shadow-[#184f3a]/20 transition cursor-pointer">
            Verify Email &amp; Continue &rarr;
        </button>
    </form>

    <x-auth-resend-otp
        :action="route('verification.resend')"
        :email="$email"
        :cooldownSeconds="$cooldownSeconds"
        buttonLabel="Resend Verification Code" />

    <div class="mt-4 text-center text-xs text-[#536058]">
        Already verified?
        <a href="{{ route('login') }}" class="font-bold text-[#184f3a] hover:underline ml-1">Sign In &rarr;</a>
    </div>
</x-auth-shell>
@endsection
