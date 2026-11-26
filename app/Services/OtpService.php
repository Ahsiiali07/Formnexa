<?php

namespace App\Services;

use App\Models\OtpCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OtpService
{
    /**
     * Normalize email for consistent lookup.
     */
    public function normalizeEmail(string $email): string
    {
        return Str::lower(trim($email));
    }

    /**
     * Generate and store a new 6-digit OTP code.
     *
     * @return array{sent: bool, retry_after: int, code?: string, otp?: OtpCode}
     */
    public function issue(string $email, string $purpose, ?string $ip = null, bool $enforceCooldown = false): array
    {
        $normalized = $this->normalizeEmail($email);

        if ($enforceCooldown) {
            $retryAfter = $this->cooldownRemaining($normalized, $purpose);
            if ($retryAfter > 0) {
                return [
                    'sent' => false,
                    'retry_after' => $retryAfter,
                ];
            }
        }

        // Invalidate any active unconsumed OTPs for this email & purpose.
        OtpCode::where('email', $normalized)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->update(['consumed_at' => now()]);

        $plainCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $record = OtpCode::create([
            'email' => $normalized,
            'purpose' => $purpose,
            'code_hash' => Hash::make($plainCode),
            'attempts' => 0,
            'expires_at' => now()->addMinutes(OtpCode::EXPIRY_MINUTES),
            'resend_available_at' => now()->addSeconds(OtpCode::RESEND_COOLDOWN_SECONDS),
            'ip_address' => $ip,
        ]);

        return [
            'sent' => true,
            'retry_after' => OtpCode::RESEND_COOLDOWN_SECONDS,
            'code' => $plainCode,
            'otp' => $record,
        ];
    }

    /**
     * Validate a submitted 6-digit OTP code.
     *
     * @return array{valid: bool, reason?: string, message?: string, attempts_remaining?: int, otp?: OtpCode}
     */
    public function verify(string $email, string $purpose, string $submittedCode): array
    {
        $normalized = $this->normalizeEmail($email);
        $cleanCode = preg_replace('/\D+/', '', $submittedCode) ?? '';

        /** @var OtpCode|null $record */
        $record = OtpCode::where('email', $normalized)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->latest('id')
            ->first();

        if (!$record) {
            return [
                'valid' => false,
                'reason' => 'not_found',
                'message' => 'No active verification code was found. Please request a new code.',
            ];
        }

        if ($record->isExpired()) {
            $record->update(['consumed_at' => now()]);

            return [
                'valid' => false,
                'reason' => 'expired',
                'message' => 'This verification code has expired. Please request a new one.',
            ];
        }

        if ($record->attempts >= OtpCode::MAX_ATTEMPTS) {
            $record->update(['consumed_at' => now()]);

            return [
                'valid' => false,
                'reason' => 'too_many_attempts',
                'message' => 'Too many incorrect attempts. Please request a fresh verification code.',
            ];
        }

        if (strlen($cleanCode) !== 6 || !Hash::check($cleanCode, $record->code_hash)) {
            $nextAttempts = $record->attempts + 1;
            $updates = ['attempts' => $nextAttempts];
            if ($nextAttempts >= OtpCode::MAX_ATTEMPTS) {
                $updates['consumed_at'] = now();
            }
            $record->update($updates);

            $remaining = max(0, OtpCode::MAX_ATTEMPTS - $nextAttempts);

            return [
                'valid' => false,
                'reason' => $remaining === 0 ? 'too_many_attempts' : 'invalid',
                'attempts_remaining' => $remaining,
                'message' => $remaining === 0
                    ? 'Too many incorrect attempts. Please request a fresh verification code.'
                    : "Invalid verification code. {$remaining} attempt" . ($remaining === 1 ? '' : 's') . ' remaining.',
            ];
        }

        $record->update(['consumed_at' => now()]);

        return [
            'valid' => true,
            'otp' => $record,
        ];
    }

    /**
     * Get remaining resend cooldown seconds for an email & purpose.
     */
    public function cooldownRemaining(string $email, string $purpose): int
    {
        $normalized = $this->normalizeEmail($email);

        /** @var OtpCode|null $latest */
        $latest = OtpCode::where('email', $normalized)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->latest('id')
            ->first();

        if (!$latest) {
            return 0;
        }

        return $latest->cooldownRemainingSeconds();
    }
}
