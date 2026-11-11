<?php

namespace App\Services;

use App\Mail\SendInvoiceMail;
use App\Models\Invoice;
use App\Support\MediaHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class InvoiceService
{
    /**
     * Normalize multipart/JSON request payloads (decodes JSON-encoded `items` in multipart forms).
     */
    public function normalizeInput(Request $request): array
    {
        $data = $request->all();

        if (isset($data['items']) && is_string($data['items'])) {
            $decoded = json_decode($data['items'], true);
            if (is_array($decoded)) {
                $data['items'] = $decoded;
            }
        }

        return $data;
    }

    /**
     * Validation rules for creating or updating an invoice.
     *
     * @return array<string, mixed>
     */
    public function validationRules(): array
    {
        return [
            'uuid' => 'nullable|string|max:64',
            'invoice_number' => 'nullable|string|max:50',
            'invoice_title' => 'nullable|string|max:100',
            'currency' => 'nullable|string|max:10',
            'currency_symbol' => 'nullable|string|max:10',
            'issue_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'payment_terms' => 'nullable|string|max:100',
            'po_number' => 'nullable|string|max:50',
            'from_name' => 'nullable|string|max:255',
            'from_email' => 'nullable|email|max:255',
            'from_phone' => 'nullable|string|max:50',
            'from_address' => 'nullable|string',
            'from_tax_id' => 'nullable|string|max:50',
            'to_name' => 'nullable|string|max:255',
            'to_email' => 'nullable|email|max:255',
            'to_phone' => 'nullable|string|max:50',
            'to_address' => 'nullable|string',
            'to_tax_id' => 'nullable|string|max:50',
            'discount_type' => 'nullable|in:percent,fixed',
            'discount_rate' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0',
            'shipping_fee' => 'nullable|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'logo_data' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'signature_data' => 'nullable|string',
            'logo_file' => 'nullable|image|max:5120',
            'cover_file' => 'nullable|image|max:5120',
            'signature_file' => 'nullable|image|max:5120',
            'payment_link' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:draft,sent,paid,overdue',
            'accent_color' => 'nullable|string|max:30',
            'template_style' => 'nullable|string|max:30',
            'items' => 'required|array|min:1',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];
    }

    /**
     * Save or update an invoice inside a database transaction.
     *
     * @return array{invoice: Invoice, created: bool}
     */
    public function save(
        array $validated,
        ?int $userId,
        ?Request $request = null,
        ?string $forcedUuid = null,
        bool $forbidForeignOwner = false
    ): array {
        if ($request !== null) {
            if ($request->hasFile('logo_file')) {
                $validated['logo_data'] = MediaHelper::uploadedImageToDataUri($request->file('logo_file'));
            }
            if ($request->hasFile('cover_file')) {
                $validated['cover_image'] = MediaHelper::uploadedImageToDataUri($request->file('cover_file'));
            }
            if ($request->hasFile('signature_file')) {
                $validated['signature_data'] = MediaHelper::uploadedImageToDataUri($request->file('signature_file'));
            }
        }

        $targetUuid = $forcedUuid ?: ($validated['uuid'] ?? null);

        return DB::transaction(function () use ($validated, $userId, $targetUuid, $forbidForeignOwner) {
            $subtotal = 0.0;
            $itemsData = [];

            foreach ($validated['items'] as $index => $item) {
                $qty = (float) ($item['quantity'] ?? 0);
                $unitPrice = (float) ($item['unit_price'] ?? 0);
                $amount = round($qty * $unitPrice, 2);
                $subtotal += $amount;

                $itemsData[] = [
                    'description' => $item['description'] ?? '',
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'amount' => $amount,
                    'sort_order' => $index,
                ];
            }

            $discountType = $validated['discount_type'] ?? 'percent';
            $discountRate = (float) ($validated['discount_rate'] ?? 0);
            $taxRate = (float) ($validated['tax_rate'] ?? 0);
            $shippingFee = (float) ($validated['shipping_fee'] ?? 0);
            $amountPaid = (float) ($validated['amount_paid'] ?? 0);

            if ($discountType === 'percent') {
                $discountAmount = round(($subtotal * $discountRate) / 100, 2);
            } else {
                $discountAmount = min($discountRate, $subtotal);
            }

            $taxableAmount = max(0.0, $subtotal - $discountAmount);
            $taxAmount = round(($taxableAmount * $taxRate) / 100, 2);

            $total = max(0.0, round($subtotal - $discountAmount + $taxAmount + $shippingFee, 2));
            $balanceDue = max(0.0, round($total - $amountPaid, 2));

            $status = !empty($validated['status'])
                ? $validated['status']
                : ($balanceDue <= 0 && $total > 0 ? 'paid' : 'draft');

            $invoice = null;
            if (!empty($targetUuid)) {
                $existing = Invoice::where('uuid', $targetUuid)->first();
                if ($existing) {
                    if ($existing->user_id === null || $existing->user_id === $userId) {
                        $invoice = $existing;
                    } elseif ($forbidForeignOwner) {
                        throw new AccessDeniedHttpException('You do not have permission to modify this invoice.');
                    }
                } elseif ($forbidForeignOwner && $forcedUuid !== null) {
                    // Caller explicitly targeted a non-existent UUID via PUT/PATCH
                    abort(404, 'Invoice not found.');
                }
            }

            $invoiceData = [
                'invoice_number' => !empty($validated['invoice_number']) ? $validated['invoice_number'] : ('INV-' . strtoupper(Str::random(6))),
                'invoice_title' => !empty($validated['invoice_title']) ? $validated['invoice_title'] : 'INVOICE',
                'status' => $status,
                'currency' => !empty($validated['currency']) ? $validated['currency'] : 'USD',
                'currency_symbol' => !empty($validated['currency_symbol']) ? $validated['currency_symbol'] : '$',
                'issue_date' => !empty($validated['issue_date']) ? $validated['issue_date'] : now()->toDateString(),
                'due_date' => $validated['due_date'] ?? null,
                'payment_terms' => $validated['payment_terms'] ?? null,
                'po_number' => $validated['po_number'] ?? null,
                'from_name' => $validated['from_name'] ?? null,
                'from_email' => $validated['from_email'] ?? null,
                'from_phone' => $validated['from_phone'] ?? null,
                'from_address' => $validated['from_address'] ?? null,
                'from_tax_id' => $validated['from_tax_id'] ?? null,
                'to_name' => $validated['to_name'] ?? null,
                'to_email' => $validated['to_email'] ?? null,
                'to_phone' => $validated['to_phone'] ?? null,
                'to_address' => $validated['to_address'] ?? null,
                'to_tax_id' => $validated['to_tax_id'] ?? null,
                'subtotal' => $subtotal,
                'discount_type' => $discountType,
                'discount_rate' => $discountRate,
                'discount_amount' => $discountAmount,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'amount_paid' => $amountPaid,
                'balance_due' => $balanceDue,
                'notes' => $validated['notes'] ?? null,
                'terms' => $validated['terms'] ?? null,
                'logo_data' => $validated['logo_data'] ?? ($invoice?->logo_data),
                'cover_image' => $validated['cover_image'] ?? ($invoice?->cover_image),
                'signature_data' => $validated['signature_data'] ?? ($invoice?->signature_data),
                'payment_link' => $validated['payment_link'] ?? null,
                'accent_color' => !empty($validated['accent_color']) ? $validated['accent_color'] : '#0d9488',
                'template_style' => !empty($validated['template_style']) ? $validated['template_style'] : 'modern',
            ];

            $created = false;
            if ($invoice) {
                if ($userId !== null && $invoice->user_id === null) {
                    $invoiceData['user_id'] = $userId;
                }
                $invoice->update($invoiceData);
                $invoice->items()->delete();
            } else {
                $invoiceData['user_id'] = $userId;
                $invoice = Invoice::create($invoiceData);
                $created = true;
            }

            foreach ($itemsData as $item) {
                $invoice->items()->create($item);
            }

            $invoice->load('items');

            return [
                'invoice' => $invoice,
                'created' => $created,
            ];
        });
    }

    /**
     * Build DomPDF instance for the given invoice.
     */
    public function buildPdf(Invoice $invoice): DomPdfWrapper
    {
        $invoice->loadMissing('items');

        return Pdf::loadView('invoice.pdf', compact('invoice'))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
            ]);
    }

    /**
     * Email invoice PDF to recipient and promote draft status to sent.
     */
    public function sendEmail(Invoice $invoice, string $toEmail, ?string $subject = null, ?string $message = null): void
    {
        $pdfContent = $this->buildPdf($invoice)->output();

        Mail::to($toEmail)->send(
            new SendInvoiceMail(
                $invoice,
                $subject ?: "Invoice #{$invoice->invoice_number} from " . ($invoice->from_name ?: config('app.name')),
                $message ?? '',
                $pdfContent
            )
        );

        if ($invoice->status === 'draft') {
            $invoice->update(['status' => 'sent']);
        }
    }

    /**
     * Aggregate summary statistics for an authenticated user's invoices.
     *
     * @return array{total_count: int, paid_count: int, pending_count: int, total_billed: float}
     */
    public function getUserStats(int $userId): array
    {
        $all = Invoice::where('user_id', $userId)->get(['status', 'total']);

        return [
            'total_count' => $all->count(),
            'paid_count' => $all->where('status', 'paid')->count(),
            'pending_count' => $all->whereIn('status', ['draft', 'sent', 'overdue'])->count(),
            'total_billed' => round((float) $all->sum('total'), 2),
        ];
    }

    /**
     * Claim unassigned guest-created invoices for an authenticated user.
     *
     * @param array<int, string> $uuids
     */
    public function claimGuestInvoices(int $userId, array $uuids): int
    {
        $clean = array_values(array_filter(array_map('strval', $uuids)));
        if (empty($clean)) {
            return 0;
        }

        return Invoice::whereIn('uuid', $clean)
            ->whereNull('user_id')
            ->update(['user_id' => $userId]);
    }

    /**
     * Format an Invoice model cleanly for mobile JSON APIs.
     *
     * @return array<string, mixed>
     */
    public function format(Invoice $invoice, bool $includeItems = true): array
    {
        $data = [
            'uuid' => $invoice->uuid,
            'user_id' => $invoice->user_id,
            'invoice_number' => $invoice->invoice_number,
            'invoice_title' => $invoice->invoice_title,
            'status' => $invoice->status,
            'currency' => $invoice->currency,
            'currency_symbol' => $invoice->currency_symbol,
            'issue_date' => $invoice->issue_date?->toDateString(),
            'due_date' => $invoice->due_date?->toDateString(),
            'payment_terms' => $invoice->payment_terms,
            'po_number' => $invoice->po_number,
            'from_name' => $invoice->from_name,
            'from_email' => $invoice->from_email,
            'from_phone' => $invoice->from_phone,
            'from_address' => $invoice->from_address,
            'from_tax_id' => $invoice->from_tax_id,
            'to_name' => $invoice->to_name,
            'to_email' => $invoice->to_email,
            'to_phone' => $invoice->to_phone,
            'to_address' => $invoice->to_address,
            'to_tax_id' => $invoice->to_tax_id,
            'subtotal' => (float) $invoice->subtotal,
            'discount_type' => $invoice->discount_type,
            'discount_rate' => (float) $invoice->discount_rate,
            'discount_amount' => (float) $invoice->discount_amount,
            'tax_rate' => (float) $invoice->tax_rate,
            'tax_amount' => (float) $invoice->tax_amount,
            'shipping_fee' => (float) $invoice->shipping_fee,
            'total' => (float) $invoice->total,
            'amount_paid' => (float) $invoice->amount_paid,
            'balance_due' => (float) $invoice->balance_due,
            'notes' => $invoice->notes,
            'terms' => $invoice->terms,
            'logo_data' => $invoice->logo_data,
            'cover_image' => $invoice->cover_image,
            'signature_data' => $invoice->signature_data,
            'payment_link' => $invoice->payment_link,
            'accent_color' => $invoice->accent_color,
            'template_style' => $invoice->template_style,
            'view_url' => route('invoice.show', $invoice->uuid),
            'pdf_url' => route('invoice.pdf', $invoice->uuid),
            'api_pdf_url' => route('api.v1.invoices.pdf', $invoice->uuid),
            'email_url' => route('api.v1.invoices.email', $invoice->uuid),
            'created_at' => $invoice->created_at?->toIso8601String(),
            'updated_at' => $invoice->updated_at?->toIso8601String(),
        ];

        if ($includeItems) {
            $invoice->loadMissing('items');
            $data['items'] = $invoice->items->map(static fn ($item) => [
                'id' => $item->id,
                'description' => $item->description,
                'quantity' => (float) $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'amount' => (float) $item->amount,
                'sort_order' => $item->sort_order,
            ])->values()->all();
        }

        return $data;
    }
}
