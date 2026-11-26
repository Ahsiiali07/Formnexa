<?php

namespace App\Http\Controllers;

use App\Mail\OtpVerificationMail;
use App\Mail\PasswordResetOtpMail;
use App\Models\OtpCode;
use App\Models\User;
use App\Services\InvoiceService;
use App\Services\OtpService;
use App\Services\ResumeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        protected OtpService $otpService,
    ) {}

    /**
     * Show Login Page.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('invoice.history.page');
        }

        return view('auth.login');
    }

    /**
     * Authenticate an existing user (enforcing email OTP verification).
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = $this->otpService->normalizeEmail($credentials['email']);
        $throttleKey = 'login:' . sha1($email . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 8)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withErrors(['email' => "Too many sign-in attempts. Please try again in {$seconds} seconds."])
                ->onlyInput('email');
        }

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            return back()
                ->withErrors(['email' => 'The provided credentials do not match our records.'])
                ->onlyInput('email');
        }

        RateLimiter::clear($throttleKey);

        if ($user->email_verified_at === null) {
            $request->session()->put('otp_pending_email', $user->email);

            $cooldown = $this->otpService->cooldownRemaining($user->email, OtpCode::PURPOSE_SIGNUP_VERIFY);
            if ($cooldown === 0) {
                $issued = $this->otpService->issue($user->email, OtpCode::PURPOSE_SIGNUP_VERIFY, $request->ip());
                $this->sendSignupOtpMail($user, $issued['code']);
            }

            return redirect()
                ->route('verification.notice')
                ->with('info', 'Please verify your email address with the 6-digit code sent to your inbox to finish signing in.');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $this->claimGuestSessionInvoices($user->id, $request);

        $defaultTarget = $user->is_admin
            ? route('admin.dashboard', [], false)
            : route('invoice.history.page', [], false);

        return redirect()
            ->intended($defaultTarget)
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }

    /**
     * Show Registration / Sign Up Page.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('invoice.history.page');
        }

        return view('auth.register');
    }

    /**
     * Create an unverified account (or refresh a pending unverified signup) and dispatch an email OTP.
     */
    public function register(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => $this->otpService->normalizeEmail((string) $request->input('email')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $existing = User::where('email', $validated['email'])->first();
        if ($existing && $existing->email_verified_at !== null) {
            throw ValidationException::withMessages([
                'email' => 'An account with this email already exists. Please sign in or reset your password.',
            ]);
        }

        if ($existing) {
            $existing->forceFill([
                'name' => $validated['name'],
                'password' => $validated['password'],
            ])->save();
            $user = $existing;
        } else {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);
        }

        $issued = $this->otpService->issue($user->email, OtpCode::PURPOSE_SIGNUP_VERIFY, $request->ip());
        $mailSent = $this->sendSignupOtpMail($user, $issued['code']);

        $request->session()->put('otp_pending_email', $user->email);

        if (!$mailSent) {
            return redirect()
                ->route('verification.notice')
                ->with('error', 'Your account was saved, but we could not send the verification email right now. Please try Resend Code in a moment.');
        }

        return redirect()
            ->route('verification.notice')
            ->with('success', "We sent a 6-digit verification code to {$this->maskEmail($user->email)}.");
    }

    /**
     * Show Signup Email OTP Verification Page.
     */
    public function showVerifyOtp(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('invoice.history.page');
        }

        $email = $this->resolvePendingVerificationEmail($request);
        if (!$email) {
            return redirect()
                ->route('register')
                ->with('info', 'Create an account or sign in to receive a verification code.');
        }

        return view('auth.verify-otp', [
            'email' => $email,
            'maskedEmail' => $this->maskEmail($email),
            'cooldownSeconds' => $this->otpService->cooldownRemaining($email, OtpCode::PURPOSE_SIGNUP_VERIFY),
        ]);
    }

    /**
     * Verify the 6-digit signup OTP, mark email verified, claim guest docs, and log the user in.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $sessionEmail = $this->resolvePendingVerificationEmail($request);
        if (!$sessionEmail && $request->filled('email')) {
            $request->merge([
                'email' => $this->otpService->normalizeEmail((string) $request->input('email')),
            ]);
        }

        $validated = $request->validate([
            'email' => [$sessionEmail ? 'nullable' : 'required', 'email'],
            'code' => ['required', 'regex:/^\d{6}$/'],
        ], [
            'code.required' => 'Please enter the 6-digit verification code.',
            'code.regex' => 'The verification code must be exactly 6 digits.',
        ]);

        $email = $sessionEmail ?: $this->otpService->normalizeEmail($validated['email']);
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()
                ->route('register')
                ->withErrors(['email' => 'Pending account not found. Please sign up again.']);
        }

        if ($user->email_verified_at !== null) {
            $request->session()->forget('otp_pending_email');

            return redirect()
                ->route('login')
                ->with('success', 'Your email is already verified. Please sign in.');
        }

        $result = $this->otpService->verify($email, OtpCode::PURPOSE_SIGNUP_VERIFY, $validated['code']);
        if (!$result['valid']) {
            return back()
                ->withErrors(['code' => $result['message']])
                ->onlyInput('email');
        }

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        $request->session()->forget('otp_pending_email');

        Auth::login($user, true);
        $request->session()->regenerate();
        $this->claimGuestSessionInvoices($user->id, $request);

        $defaultTarget = $user->is_admin
            ? route('admin.dashboard', [], false)
            : route('invoice.history.page', [], false);

        return redirect()
            ->intended($defaultTarget)
            ->with('success', 'Email verified! Your Formnexa workspace and document history are now active.')
            ->with('signup_completed', true);
    }

    /**
     * Resend the 6-digit signup OTP with 60-second cooldown enforcement.
     */
    public function resendOtp(Request $request): RedirectResponse|JsonResponse
    {
        $email = $this->resolvePendingVerificationEmail($request);
        if (!$email && $request->filled('email')) {
            $candidate = $this->otpService->normalizeEmail((string) $request->input('email'));
            $pending = User::where('email', $candidate)->whereNull('email_verified_at')->first();
            if ($pending) {
                $email = $pending->email;
                $request->session()->put('otp_pending_email', $email);
            }
        }

        if (!$email) {
            $msg = 'No pending verification session was found. Please sign up again.';

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : redirect()->route('register')->with('error', $msg);
        }

        $user = User::where('email', $email)->whereNull('email_verified_at')->first();
        if (!$user) {
            $request->session()->forget('otp_pending_email');
            $msg = 'This account is already verified. You can sign in directly.';

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : redirect()->route('login')->with('success', $msg);
        }

        $issued = $this->otpService->issue(
            $user->email,
            OtpCode::PURPOSE_SIGNUP_VERIFY,
            $request->ip(),
            enforceCooldown: true
        );

        if (!$issued['sent']) {
            $retryAfter = $issued['retry_after'];
            $msg = "Please wait {$retryAfter} seconds before requesting a new code.";

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg, 'retry_after' => $retryAfter], 429)
                : back()->with('error', $msg);
        }

        $mailSent = $this->sendSignupOtpMail($user, $issued['code']);
        if (!$mailSent) {
            $msg = 'We could not deliver the verification email right now. Please try again shortly.';

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg], 500)
                : back()->with('error', $msg);
        }

        $msg = "A fresh 6-digit code was sent to {$this->maskEmail($user->email)}.";

        return $request->expectsJson()
            ? response()->json(['success' => true, 'message' => $msg, 'retry_after' => OtpCode::RESEND_COOLDOWN_SECONDS])
            : back()->with('success', $msg);
    }

    /**
     * Show Forgot Password Page.
     */
    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Dispatch a password-reset OTP (requires an existing registered email).
     */
    public function sendResetOtp(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => $this->otpService->normalizeEmail((string) $request->input('email')),
        ]);

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = $validated['email'];
        $throttleKey = 'forgot-password:' . sha1($email . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 6)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withErrors(['email' => "Too many reset requests. Please try again in {$seconds} seconds."])
                ->onlyInput('email');
        }

        RateLimiter::hit($throttleKey, 60);

        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()
                ->withErrors(['email' => 'No account was found with this email address.'])
                ->onlyInput('email');
        }

        $issued = $this->otpService->issue(
            $user->email,
            OtpCode::PURPOSE_PASSWORD_RESET,
            $request->ip(),
            enforceCooldown: true
        );

        if ($issued['sent']) {
            $mailSent = $this->sendPasswordResetOtpMail($user, $issued['code']);
            if (!$mailSent) {
                return back()
                    ->withErrors(['email' => 'We could not deliver the reset code right now. Please try again shortly.'])
                    ->onlyInput('email');
            }
        }

        $request->session()->put('password_reset_email', $user->email);

        return redirect()
            ->route('password.reset.form')
            ->with('success', "We sent a 6-digit password reset code to {$this->maskEmail($user->email)}.");
    }

    /**
     * Show Reset Password with OTP Page.
     */
    public function showResetPassword(Request $request): View
    {
        $email = $this->otpService->normalizeEmail(
            (string) old('email', $request->session()->get('password_reset_email', $request->query('email', '')))
        );

        $cooldownSeconds = $email !== ''
            ? $this->otpService->cooldownRemaining($email, OtpCode::PURPOSE_PASSWORD_RESET)
            : 0;

        return view('auth.reset-password', [
            'email' => $email,
            'maskedEmail' => $email !== '' ? $this->maskEmail($email) : null,
            'cooldownSeconds' => $cooldownSeconds,
        ]);
    }

    /**
     * Resend Password Reset OTP with 60s cooldown enforcement.
     */
    public function resendResetOtp(Request $request): RedirectResponse|JsonResponse
    {
        $candidate = $request->input('email', $request->session()->get('password_reset_email', ''));
        $email = $this->otpService->normalizeEmail((string) $candidate);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = 'Please provide a valid email address first.';

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : redirect()->route('password.request')->withErrors(['email' => $msg]);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            $msg = 'No account was found with this email address.';

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : redirect()->route('password.request')->withErrors(['email' => $msg])->withInput(['email' => $email]);
        }

        $request->session()->put('password_reset_email', $user->email);

        $cooldown = $this->otpService->cooldownRemaining($user->email, OtpCode::PURPOSE_PASSWORD_RESET);
        if ($cooldown > 0) {
            $msg = "Please wait {$cooldown} seconds before requesting another reset code.";

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg, 'retry_after' => $cooldown], 429)
                : back()->with('error', $msg);
        }

        $issued = $this->otpService->issue(
            $user->email,
            OtpCode::PURPOSE_PASSWORD_RESET,
            $request->ip(),
            enforceCooldown: true
        );

        if ($issued['sent']) {
            $mailSent = $this->sendPasswordResetOtpMail($user, $issued['code']);
            if (!$mailSent) {
                $msg = 'We could not deliver the reset code right now. Please try again shortly.';

                return $request->expectsJson()
                    ? response()->json(['success' => false, 'message' => $msg], 500)
                    : back()->with('error', $msg);
            }
        }

        $msg = "A fresh 6-digit reset code was sent to {$this->maskEmail($user->email)}.";

        return $request->expectsJson()
            ? response()->json(['success' => true, 'message' => $msg, 'retry_after' => OtpCode::RESEND_COOLDOWN_SECONDS])
            : back()->with('success', $msg);
    }

    /**
     * Validate Password Reset OTP and update the user's password.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => $this->otpService->normalizeEmail((string) $request->input('email', $request->session()->get('password_reset_email', ''))),
        ]);

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'code' => ['required', 'regex:/^\d{6}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'code.required' => 'Please enter the 6-digit reset code from your email.',
            'code.regex' => 'The reset code must be exactly 6 digits.',
        ]);

        $user = User::where('email', $validated['email'])->first();
        if (!$user) {
            return back()
                ->withErrors(['code' => 'The verification code is invalid or has expired.'])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        $result = $this->otpService->verify($user->email, OtpCode::PURPOSE_PASSWORD_RESET, $validated['code']);
        if (!$result['valid']) {
            return back()
                ->withErrors(['code' => $result['message']])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        $user->forceFill([
            'password' => $validated['password'],
            'email_verified_at' => $user->email_verified_at ?? now(),
            'remember_token' => Str::random(60),
        ])->save();

        $request->session()->forget(['password_reset_email', 'otp_pending_email']);

        return redirect()
            ->route('login')
            ->with('success', 'Your password has been reset successfully. Please sign in with your new password.');
    }

    /**
     * Show Authenticated Account Security & Change Password Page.
     */
    public function showChangePassword(): View
    {
        return view('auth.change-password', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update Password for the currently authenticated user.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.current_password' => 'Your current password does not match our records.',
            'password.different' => 'Your new password must be different from your current password.',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->forceFill([
            'password' => $validated['password'],
            'remember_token' => Str::random(60),
        ])->save();

        $request->session()->regenerate();

        return back()->with('success', 'Your password has been updated securely.');
    }

    /**
     * Sign out the user.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been signed out.');
    }

    /**
     * Attach any unassigned invoices or resumes created in this guest session to the authenticated user.
     */
    protected function claimGuestSessionInvoices(int $userId, Request $request): void
    {
        $guestUuids = $request->session()->pull('guest_invoice_uuids', []);
        if (!empty($guestUuids) && is_array($guestUuids)) {
            app(InvoiceService::class)->claimGuestInvoices($userId, $guestUuids);
        }

        $guestResumeUuids = $request->session()->pull('guest_resume_uuids', []);
        if (!empty($guestResumeUuids) && is_array($guestResumeUuids)) {
            app(ResumeService::class)->claimGuestResumes($userId, $guestResumeUuids);
        }
    }

    protected function resolvePendingVerificationEmail(Request $request): ?string
    {
        $candidate = $request->session()->get('otp_pending_email') ?: $request->query('email');
        if (!is_string($candidate) || trim($candidate) === '') {
            return null;
        }

        $normalized = $this->otpService->normalizeEmail($candidate);
        $pending = User::where('email', $normalized)->whereNull('email_verified_at')->first();
        if (!$pending) {
            $request->session()->forget('otp_pending_email');

            return null;
        }

        $request->session()->put('otp_pending_email', $pending->email);

        return $pending->email;
    }

    protected function sendSignupOtpMail(User $user, string $code): bool
    {
        try {
            Mail::to($user->email)->send(new OtpVerificationMail($user->name, $code));

            return true;
        } catch (\Throwable $e) {
            Log::error('Failed to send signup OTP email.', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    protected function sendPasswordResetOtpMail(User $user, string $code): bool
    {
        try {
            Mail::to($user->email)->send(new PasswordResetOtpMail($user->name, $code));

            return true;
        } catch (\Throwable $e) {
            Log::error('Failed to send password reset OTP email.', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    protected function maskEmail(string $email): string
    {
        $parts = explode('@', $email, 2);
        if (count($parts) !== 2) {
            return $email;
        }

        [$local, $domain] = $parts;
        if (strlen($local) <= 2) {
            return substr($local, 0, 1) . str_repeat('*', max(1, strlen($local) - 1)) . '@' . $domain;
        }

        return substr($local, 0, 2) . str_repeat('*', max(2, strlen($local) - 2)) . '@' . $domain;
    }
}
