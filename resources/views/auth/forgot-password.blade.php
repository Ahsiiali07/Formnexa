@extends('layouts.app')

@section('title', 'Forgot Password — Formnexa')

@section('content')
<x-auth-shell
    badge="Account Recovery"
    title="Reset your password"
    subtitle="Enter your account email and we will send a 6-digit recovery code valid for 10 minutes."
    panelTitle="Fast, OTP-protected recovery."
    panelSubtitle="No temporary plain-text passwords—just enter your one-time verification code and choose a strong new password."
    :steps="['Request Reset Code', 'Verify Code & Reset']"
    :activeStep="1"
    footerBackRoute="login"
    footerBackLabel="Back to Sign In">

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-[#536058] mb-1.5">
                Account Email Address
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="you@company.com"
                   class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#dfe4df] text-sm text-[#17211b] placeholder:text-[#68736d]/70 focus:border-[#184f3a] focus:ring-2 focus:ring-[#184f3a]/15 focus:outline-none transition">
        </div>

        <button type="submit" class="w-full py-3 px-6 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-sm font-bold shadow-md shadow-[#184f3a]/20 transition cursor-pointer">
            Send 6-Digit Reset Code &rarr;
        </button>
    </form>

    <div class="mt-6 pt-5 border-t border-[#dfe4df] flex flex-wrap items-center justify-between gap-2 text-xs text-[#536058]">
        <span>Already have a code?</span>
        <a href="{{ route('password.reset.form') }}" class="font-bold text-[#184f3a] hover:underline">
            Enter reset code &rarr;
        </a>
    </div>
</x-auth-shell>
@endsection
