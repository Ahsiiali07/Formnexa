<?php

namespace App\Mail;

use App\Models\OtpCode;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpVerificationMail extends Mailable
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
            subject: "{$this->code} is your Formnexa verification code",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth-otp',
            with: [
                'heading' => 'Verify your email address',
                'intro' => 'Welcome to Formnexa! Use the 6-digit verification code below to confirm your email address and activate your workspace.',
                'actionNote' => 'Enter this code on the verification screen. Never share this code with anyone.',
                'recipientName' => $this->recipientName,
                'code' => $this->code,
                'expiresMinutes' => $this->expiresMinutes,
            ],
        );
    }
}
