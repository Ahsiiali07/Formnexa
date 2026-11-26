@extends('layouts.app')

@section('title', 'Enter Reset Code & New Password — Formnexa')

@section('content')
<x-auth-shell
    badge="Password Reset"
    title="Set a new password"
    subtitle="Enter the 6-digit recovery code sent to your inbox along with your new password."
    panelTitle="Secure your account with a fresh password."
    panelSubtitle="Once reset, any old sessions stay protected and you can immediately sign back in to your Formnexa workspace."
    :steps="['Request Reset Code', 'Verify Code & Reset']"
    :activeStep="2"
    footerBackRoute="password.request"
    footerBackLabel="Try a Different Email Address">

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf

        <div>
            <label for="reset_email" class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-[#536058] mb-1.5">
                Email Address
            </label>
            <input id="reset_email" type="email" name="email" value="{{ $email }}" required autocomplete="email" placeholder="you@company.com"
                   class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#dfe4df] text-sm text-[#17211b] placeholder:text-[#68736d]/70 focus:border-[#184f3a] focus:ring-2 focus:ring-[#184f3a]/15 focus:outline-none transition">
        </div>

        <x-auth-otp-input name="code" label="6-Digit Recovery Code" :initialValue="old('code', '')" />

        <x-auth-password-field
            id="password"
            name="password"
            label="New Password"
            hint="Min. 8 characters"
            placeholder="Create a strong new password"
            autocomplete="new-password"
            :showStrength="true" />

        <x-auth-password-field
            id="password_confirmation"
            name="password_confirmation"
            label="Confirm New Password"
            placeholder="Re-enter your new password"
            autocomplete="new-password" />

        <button type="submit" class="w-full py-3 px-6 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-sm font-bold shadow-md shadow-[#184f3a]/20 transition cursor-pointer mt-1">
            Verify Code &amp; Update Password &rarr;
        </button>
    </form>

    <x-auth-resend-otp
        :action="route('password.reset.resend')"
        :email="$email"
        :cooldownSeconds="$cooldownSeconds"
        buttonLabel="Resend Reset Code" />

    <div class="mt-4 text-center text-xs text-[#536058]">
        Remembered your password?
        <a href="{{ route('login') }}" class="font-bold text-[#184f3a] hover:underline ml-1">Back to Sign In &rarr;</a>
    </div>
</x-auth-shell>
@endsection
