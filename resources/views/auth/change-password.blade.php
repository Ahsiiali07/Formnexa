@extends('layouts.app')

@section('title', 'Account Security & Change Password — Formnexa')

@section('content')
<div class="min-h-[calc(100vh-140px)] bg-[#f8f8f3] py-8 sm:py-12 px-4 sm:px-6">
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Breadcrumb Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="text-xs font-mono font-bold uppercase tracking-wider text-[#184f3a] mb-1">Account Settings</div>
                <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-[#17211b] tracking-[-0.03em]">
                    Security &amp; Password
                </h1>
                <p class="text-xs sm:text-sm text-[#536058] mt-1">
                    Keep your Formnexa workspace secure by updating your password regularly.
                </p>
            </div>
            <a href="{{ route('invoice.history.page') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-[#dfe4df] hover:border-[#184f3a] text-xs font-bold text-[#17211b] transition self-start sm:self-auto">
                &larr; Back to Document History
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Profile Summary Sidebar -->
            <div class="lg:col-span-5 bg-white rounded-2xl border border-[#dfe4df] shadow-sm p-6 space-y-5">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-[#184f3a] text-white flex items-center justify-center font-display font-extrabold text-lg uppercase shrink-0">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <div class="font-bold text-[#17211b] truncate">{{ $user->name }}</div>
                        <div class="text-xs text-[#536058] truncate">{{ $user->email }}</div>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#dfe4df] space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-[#536058]">Email Status</span>
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#e8f7ed] text-[#184f3a] font-bold text-[11px]">
                                <x-icon name="check-circle" class="w-3.5 h-3.5" />
                                Verified
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 font-bold text-[11px]">
                                Pending Verification
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[#536058]">Member Since</span>
                        <span class="font-semibold text-[#17211b]">{{ $user->created_at?->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[#536058]">Role</span>
                        <span class="font-semibold text-[#17211b]">{{ $user->is_admin ? 'Administrator' : 'Member' }}</span>
                    </div>
                </div>

                <div class="p-3.5 rounded-xl bg-[#f8f8f3] border border-[#dfe4df] text-xs text-[#536058] space-y-1.5">
                    <div class="font-bold text-[#17211b] flex items-center gap-1.5">
                        <x-icon name="shield-check" class="w-4 h-4 text-[#184f3a]" />
                        <span>Password Tips</span>
                    </div>
                    <p class="leading-relaxed">
                        Use at least 8 characters combining uppercase letters, numbers, and symbols that you do not reuse on other websites.
                    </p>
                </div>
            </div>

            <!-- Change Password Form Card -->
            <div class="lg:col-span-7 bg-white rounded-2xl border border-[#dfe4df] shadow-lg shadow-[#17211b]/5 p-6 sm:p-8">
                <h2 class="font-display text-xl font-bold text-[#17211b] mb-1">Change Password</h2>
                <p class="text-xs text-[#536058] mb-5">
                    Verify your current password before choosing a new password.
                </p>

                @if ($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 font-medium space-y-1">
                        @foreach ($errors->all() as $err)
                            <div>{{ $err }}</div>
                        @endforeach
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('account.password.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <x-auth-password-field
                        id="current_password"
                        name="current_password"
                        label="Current Password"
                        placeholder="Enter your current password"
                        autocomplete="current-password"
                        :autofocus="true" />

                    <x-auth-password-field
                        id="password"
                        name="password"
                        label="New Password"
                        hint="Min. 8 characters"
                        placeholder="Choose a new password"
                        autocomplete="new-password"
                        :showStrength="true" />

                    <x-auth-password-field
                        id="password_confirmation"
                        name="password_confirmation"
                        label="Confirm New Password"
                        placeholder="Re-enter your new password"
                        autocomplete="new-password" />

                    <div class="pt-2 flex flex-wrap items-center justify-between gap-3">
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#536058] hover:text-[#184f3a] transition">
                            Forgot current password? Reset via Email OTP &rarr;
                        </a>

                        <button type="submit" class="py-2.5 px-6 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-sm font-bold shadow-md shadow-[#184f3a]/20 transition cursor-pointer">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
