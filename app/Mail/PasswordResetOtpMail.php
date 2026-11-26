<?php

namespace App\Mail;

use App\Models\OtpCode;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $recipientName,
        public string $code,
        public int $expiresMinutes = OtpCode::EXPIRY_MINUTES,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->code} — Reset your Formnexa password",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth-otp',
            with: [
                'heading' => 'Reset your password',
                'intro' => 'We received a request to reset the password for your Formnexa account. Enter the 6-digit code below to choose a new password.',
                'actionNote' => 'If you did not request a password reset, you can safely ignore this email — your password will remain unchanged.',
                'recipientName' => $this->recipientName,
                'code' => $this->code,
                'expiresMinutes' => $this->expiresMinutes,
            ],
        );
    }
}
