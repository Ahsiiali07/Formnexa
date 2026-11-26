<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    public const PURPOSE_SIGNUP_VERIFY = 'signup_verify';
    public const PURPOSE_PASSWORD_RESET = 'password_reset';

    public const EXPIRY_MINUTES = 10;
    public const RESEND_COOLDOWN_SECONDS = 60;
    public const MAX_ATTEMPTS = 5;

    protected $fillable = [
        'email',
        'purpose',
        'code_hash',
        'attempts',
        'expires_at',
        'resend_available_at',
        'consumed_at',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'attempts' => 'integer',
            'expires_at' => 'datetime',
            'resend_available_at' => 'datetime',
            'consumed_at' => 'datetime',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isConsumed(): bool
    {
        return $this->consumed_at !== null;
    }

    public function cooldownRemainingSeconds(): int
    {
        if (!$this->resend_available_at || $this->resend_available_at->isPast()) {
            return 0;
        }

        return max(1, (int) now()->diffInSeconds($this->resend_available_at, false));
    }
}
