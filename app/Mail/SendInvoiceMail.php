<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invoice $invoice;
    public string $emailSubject;
    public string $emailMessage;
    public string $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct(Invoice $invoice, string $emailSubject, string $emailMessage, string $pdfContent)
    {
        $this->invoice = $invoice;
        $this->emailSubject = $emailSubject ?: "Invoice {$invoice->invoice_number} from " . ($invoice->from_name ?: config('app.name'));
        $this->emailMessage = $emailMessage;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
            with: [
                'invoice' => $this->invoice,
                'customMessage' => $this->emailMessage,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $fileName = 'invoice-' . ($this->invoice->invoice_number ?: $this->invoice->uuid) . '.pdf';

        return [
            Attachment::fromData(fn () => $this->pdfContent, $fileName)
                ->withMime('application/pdf'),
        ];
    }
}
