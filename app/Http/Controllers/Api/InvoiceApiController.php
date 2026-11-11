<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InvoiceApiController extends Controller
{
    /**
     * List invoices (authenticated user's invoices, or guest invoices matching ?uuids=...).
     */
    public function index(Request $request, InvoiceService $invoiceService): JsonResponse
    {
        $user = $request->user();
        $search = trim((string) $request->query('q', ''));
        $statusFilter = trim((string) $request->query('status', 'all'));
        $perPage = min(50, max(1, (int) $request->query('per_page', 15)));

        if ($user !== null) {
            $baseQuery = Invoice::where('user_id', $user->id);
            $stats = $invoiceService->getUserStats($user->id);
        } else {
            $uuids = $this->parseUuidList($request->query('uuids'));
            if (empty($uuids)) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'meta' => [
                        'current_page' => 1,
                        'last_page' => 1,
                        'per_page' => $perPage,
                        'total' => 0,
                    ],
                    'stats' => [
                        'total_count' => 0,
                        'paid_count' => 0,
                        'pending_count' => 0,
                        'total_billed' => 0.0,
                    ],
                ]);
            }

            $baseQuery = Invoice::whereIn('uuid', $uuids)->whereNull('user_id');
            $guestAll = (clone $baseQuery)->get(['status', 'total']);
            $stats = [
                'total_count' => $guestAll->count(),
                'paid_count' => $guestAll->where('status', 'paid')->count(),
                'pending_count' => $guestAll->whereIn('status', ['draft', 'sent', 'overdue'])->count(),
                'total_billed' => round((float) $guestAll->sum('total'), 2),
            ];
        }

        $query = (clone $baseQuery)->with('items');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('to_name', 'like', "%{$search}%")
                    ->orWhere('to_email', 'like', "%{$search}%")
                    ->orWhere('invoice_title', 'like', "%{$search}%");
            });
        }

        if (in_array($statusFilter, ['draft', 'sent', 'paid', 'overdue'], true)) {
            $query->where('status', $statusFilter);
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $invoices->getCollection()->map(
                static fn (Invoice $inv) => $invoiceService->format($inv)
            )->values()->all(),
            'meta' => [
                'current_page' => $invoices->currentPage(),
                'last_page' => $invoices->lastPage(),
                'per_page' => $invoices->perPage(),
                'total' => $invoices->total(),
            ],
            'stats' => $stats,
        ]);
    }

    /**
     * Quick recent invoices drawer list (up to 20).
     */
    public function history(Request $request): JsonResponse
    {
        $user = $request->user();
        $columns = ['uuid', 'invoice_number', 'invoice_title', 'to_name', 'total', 'balance_due', 'currency_symbol', 'created_at', 'status'];

        if ($user !== null) {
            $invoices = Invoice::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(20)
                ->get($columns);
        } else {
            $uuids = $this->parseUuidList($request->query('uuids'));
            $invoices = !empty($uuids)
                ? Invoice::whereIn('uuid', $uuids)
                    ->whereNull('user_id')
                    ->orderBy('created_at', 'desc')
                    ->take(20)
                    ->get($columns)
                : collect();
        }

        return response()->json([
            'success' => true,
            'data' => $invoices->values()->all(),
        ]);
    }

    /**
     * Create or update an invoice (supports JSON or multipart/form-data).
     */
    public function store(Request $request, InvoiceService $invoiceService): JsonResponse
    {
        $input = $invoiceService->normalizeInput($request);
        $validated = Validator::make($input, $invoiceService->validationRules())->validate();

        $saved = $invoiceService->save(
            $validated,
            $request->user()?->id,
            $request,
            null,
            true
        );

        $invoice = $saved['invoice'];

        return response()->json([
            'success' => true,
            'message' => 'Invoice saved successfully!',
            'uuid' => $invoice->uuid,
            'invoice_number' => $invoice->invoice_number,
            'invoice' => $invoiceService->format($invoice),
            'view_url' => route('invoice.show', $invoice->uuid),
            'pdf_url' => route('invoice.pdf', $invoice->uuid),
            'api_pdf_url' => route('api.v1.invoices.pdf', $invoice->uuid),
            'email_url' => route('api.v1.invoices.email', $invoice->uuid),
        ], $saved['created'] ? 201 : 200);
    }

    /**
     * Get a single invoice for editing/viewing (enforces ownership when user_id is set).
     */
    public function show(Request $request, string $uuid, InvoiceService $invoiceService): JsonResponse
    {
        $invoice = Invoice::with('items')->where('uuid', $uuid)->firstOrFail();

        if ($invoice->user_id !== null && $invoice->user_id !== $request->user()?->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to access this invoice.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'invoice' => $invoiceService->format($invoice),
        ]);
    }

    /**
     * Public shareable client view of an invoice by UUID.
     */
    public function publicShow(string $uuid, InvoiceService $invoiceService): JsonResponse
    {
        $invoice = Invoice::with('items')->where('uuid', $uuid)->firstOrFail();

        return response()->json([
            'success' => true,
            'invoice' => $invoiceService->format($invoice),
        ]);
    }

    /**
     * Update an existing invoice by UUID.
     */
    public function update(Request $request, string $uuid, InvoiceService $invoiceService): JsonResponse
    {
        $input = $invoiceService->normalizeInput($request);
        $validated = Validator::make($input, $invoiceService->validationRules())->validate();

        $saved = $invoiceService->save(
            $validated,
            $request->user()?->id,
            $request,
            $uuid,
            true
        );

        $invoice = $saved['invoice'];

        return response()->json([
            'success' => true,
            'message' => 'Invoice updated successfully!',
            'uuid' => $invoice->uuid,
            'invoice_number' => $invoice->invoice_number,
            'invoice' => $invoiceService->format($invoice),
            'view_url' => route('invoice.show', $invoice->uuid),
            'pdf_url' => route('invoice.pdf', $invoice->uuid),
            'api_pdf_url' => route('api.v1.invoices.pdf', $invoice->uuid),
            'email_url' => route('api.v1.invoices.email', $invoice->uuid),
        ]);
    }

    /**
     * Update invoice payment/workflow status.
     */
    public function updateStatus(Request $request, string $uuid, InvoiceService $invoiceService): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,sent,paid,overdue'],
        ]);

        $invoice = Invoice::with('items')->where('uuid', $uuid)->firstOrFail();

        if ($invoice->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to modify this invoice.',
            ], 403);
        }

        $invoice->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => "Document #{$invoice->invoice_number} marked as " . ucfirst($validated['status']) . '.',
            'invoice' => $invoiceService->format($invoice),
        ]);
    }

    /**
     * Delete an invoice owned by the authenticated user.
     */
    public function destroy(Request $request, string $uuid): JsonResponse
    {
        $invoice = Invoice::where('uuid', $uuid)->firstOrFail();

        if ($invoice->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this invoice.',
            ], 403);
        }

        $number = $invoice->invoice_number;
        $invoice->items()->delete();
        $invoice->delete();

        return response()->json([
            'success' => true,
            'message' => "Document #{$number} deleted from your history.",
        ]);
    }

    /**
     * Download or stream the generated PDF invoice.
     */
    public function downloadPdf(string $uuid, Request $request, InvoiceService $invoiceService)
    {
        $invoice = Invoice::with('items')->where('uuid', $uuid)->firstOrFail();
        $pdf = $invoiceService->buildPdf($invoice);

        $fileName = 'invoice-' . ($invoice->invoice_number ?: $invoice->uuid) . '.pdf';

        if ($request->boolean('stream') || $request->has('stream')) {
            return $pdf->stream($fileName);
        }

        return $pdf->download($fileName);
    }

    /**
     * Send invoice PDF via email.
     */
    public function sendEmail(string $uuid, Request $request, InvoiceService $invoiceService): JsonResponse
    {
        $invoice = Invoice::with('items')->where('uuid', $uuid)->firstOrFail();

        if ($invoice->user_id !== null && $invoice->user_id !== $request->user()?->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to email this invoice.',
            ], 403);
        }

        $validated = $request->validate([
            'to_email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);

        try {
            $invoiceService->sendEmail(
                $invoice,
                $validated['to_email'],
                $validated['subject'] ?? null,
                $validated['message'] ?? null
            );

            return response()->json([
                'success' => true,
                'message' => "Invoice #{$invoice->invoice_number} sent to {$validated['to_email']} successfully!",
                'status' => $invoice->status,
            ]);
        } catch (\Throwable $e) {
            Log::error('Invoice API email failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to deliver email: ' . $e->getMessage(),
            ], 500);
        }
    }
}
