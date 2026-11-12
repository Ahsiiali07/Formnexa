<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Resume;
use App\Services\InvoiceService;
use App\Support\TemplateCatalog;
use App\Support\ToolSeoCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    /**
     * All 12 Document Generator Configurations
     */
    public static array $generators = [
        'invoice-generator' => [
            'name' => 'Invoice Generator',
            'slug' => 'invoice-generator',
            'title' => 'INVOICE',
            'prefix' => 'INV-',
            'badge' => 'Free Invoice Maker',
            'headline' => 'Free Online Invoice Generator',
            'subtitle' => 'Create, customize, and export professional invoices with cover photos, payment links, and instant PDF download.',
            'terms' => 'Due on receipt',
            'is_paid' => false,
        ],
        'receipt-generator' => [
            'name' => 'Receipt Generator',
            'slug' => 'receipt-generator',
            'title' => 'RECEIPT',
            'prefix' => 'REC-',
            'badge' => 'Instant Receipt Maker',
            'headline' => 'Free Online Receipt Generator',
            'subtitle' => 'Generate and send instant payment receipts, proof of purchase, and cash receipts for your customers.',
            'terms' => 'Payment received in full',
            'is_paid' => true,
        ],
        'quote-generator' => [
            'name' => 'Quote Generator',
            'slug' => 'quote-generator',
            'title' => 'QUOTE',
            'prefix' => 'QUO-',
            'badge' => 'Price Quote Maker',
            'headline' => 'Free Online Quote Generator',
            'subtitle' => 'Craft winning price quotes and service bids for clients with custom branding and digital signatures.',
            'terms' => 'Quote valid for 30 days',
            'is_paid' => false,
        ],
        'estimate-generator' => [
            'name' => 'Estimate Generator',
            'slug' => 'estimate-generator',
            'title' => 'ESTIMATE',
            'prefix' => 'EST-',
            'badge' => 'Project Cost Estimator',
            'headline' => 'Free Online Estimate Generator',
            'subtitle' => 'Provide clear project cost estimates and projected budget breakdowns for clients before work starts.',
            'terms' => 'Estimate valid for 14 days',
            'is_paid' => false,
        ],
        'credit-note-generator' => [
            'name' => 'Credit Note Generator',
            'slug' => 'credit-note-generator',
            'title' => 'CREDIT NOTE',
            'prefix' => 'CRN-',
            'badge' => 'Credit Memo Tool',
            'headline' => 'Free Credit Note Generator',
            'subtitle' => 'Issue official credit notes and billing adjustments to balance customer accounts or process refunds.',
            'terms' => 'Credit applied to account balance',
            'is_paid' => false,
        ],
        'purchase-order-generator' => [
            'name' => 'Purchase Order Generator',
            'slug' => 'purchase-order-generator',
            'title' => 'PURCHASE ORDER',
            'prefix' => 'PO-',
            'badge' => 'Vendor Order Maker',
            'headline' => 'Free Purchase Order Generator',
            'subtitle' => 'Create and track formal purchase orders (PO) for vendors, suppliers, and procurement teams.',
            'terms' => 'Net 30 Days upon receipt of goods',
            'is_paid' => false,
        ],
        'sales-order-generator' => [
            'name' => 'Sales Order Generator',
            'slug' => 'sales-order-generator',
            'title' => 'SALES ORDER',
            'prefix' => 'SO-',
            'badge' => 'Sales Order Tool',
            'headline' => 'Free Sales Order Generator',
            'subtitle' => 'Confirm customer purchases and document delivery schedules and terms with clean sales orders.',
            'terms' => 'Fulfillment upon order confirmation',
            'is_paid' => false,
        ],
        'proforma-invoice-generator' => [
            'name' => 'Proforma Invoice Generator',
            'slug' => 'proforma-invoice-generator',
            'title' => 'PROFORMA INVOICE',
            'prefix' => 'PI-',
            'badge' => 'Customs & Export Invoicing',
            'headline' => 'Free Proforma Invoice Generator',
            'subtitle' => 'Generate proforma invoices for international shipments, customs clearance, and pre-payment arrangements.',
            'terms' => 'Pre-payment required prior to shipment',
            'is_paid' => false,
        ],
        'timesheet-generator' => [
            'name' => 'Timesheet Generator',
            'slug' => 'timesheet-generator',
            'title' => 'TIMESHEET',
            'prefix' => 'TS-',
            'badge' => 'Billable Hours & Timesheet',
            'headline' => 'Free Online Timesheet Generator',
            'subtitle' => 'Log billable hours, consulting milestones, and contractor time records ready for invoice billing.',
            'terms' => 'Weekly timesheet approval',
            'is_paid' => false,
        ],
        'work-order-generator' => [
            'name' => 'Work Order Generator',
            'slug' => 'work-order-generator',
            'title' => 'WORK ORDER',
            'prefix' => 'WO-',
            'badge' => 'Service & Maintenance Order',
            'headline' => 'Free Online Work Order Generator',
            'subtitle' => 'Document job tasks, labor rates, materials, and service authorizations for field technicians and clients.',
            'terms' => 'Work authorized by client signature',
            'is_paid' => false,
        ],
        'account-statement-generator' => [
            'name' => 'Account Statement Generator',
            'slug' => 'account-statement-generator',
            'title' => 'STATEMENT OF ACCOUNT',
            'prefix' => 'STMT-',
            'badge' => 'Customer Balance Statement',
            'headline' => 'Free Account Statement Generator',
            'subtitle' => 'Send clear account statements showing total billings, received payments, and current outstanding balances.',
            'terms' => 'Statement closing period',
            'is_paid' => false,
        ],
        'packing-slip-generator' => [
            'name' => 'Packing Slip Generator',
            'slug' => 'packing-slip-generator',
            'title' => 'PACKING SLIP',
            'prefix' => 'PS-',
            'badge' => 'Shipping Manifest Slip',
            'headline' => 'Free Packing Slip Generator',
            'subtitle' => 'Generate clean package slips and shipping manifests to accompany physical product deliveries.',
            'terms' => 'Please inspect items upon arrival',
            'is_paid' => false,
        ],
    ];

    /**
     * Display the Invoice Generator Studio.
     */
    public function index(Request $request): View
    {
        return $this->generator('invoice-generator', $request);
    }

    /**
     * Display a specific Generator (e.g. Receipt, Quote, Estimate, etc.).
     */
    public function generator(string $type, Request $request = null): View
    {
        $request = $request ?: request();
        $config = self::$generators[$type] ?? self::$generators['invoice-generator'];
        $allGenerators = self::$generators;

        $loadedInvoice = null;
        if ($request->has('load')) {
            $candidate = Invoice::with('items')->where('uuid', $request->query('load'))->first();
            if ($candidate && ($candidate->user_id === null || $candidate->user_id === Auth::id())) {
                $loadedInvoice = $candidate;
            }
        }

        $loadedTemplate = null;
        if ($request->has('template')) {
            $loadedTemplate = TemplateCatalog::find((string) $request->query('template'));
        }

        $templateStyles = TemplateCatalog::styles();
        $templateColors = TemplateCatalog::colors();
        $allTemplates = TemplateCatalog::templates();

        if (Auth::check()) {
            $recentInvoices = Invoice::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get(['id', 'uuid', 'invoice_number', 'invoice_title', 'to_name', 'total', 'currency_symbol', 'created_at', 'status']);
        } else {
            $guestUuids = $request->session()->get('guest_invoice_uuids', []);
            $recentInvoices = !empty($guestUuids)
                ? Invoice::whereIn('uuid', $guestUuids)
                    ->whereNull('user_id')
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get(['id', 'uuid', 'invoice_number', 'invoice_title', 'to_name', 'total', 'currency_symbol', 'created_at', 'status'])
                : collect();
        }

        $docSeo = ToolSeoCatalog::document($type);

        return view('invoice.studio', compact(
            'loadedInvoice',
            'loadedTemplate',
            'recentInvoices',
            'config',
            'allGenerators',
            'templateStyles',
            'templateColors',
            'allTemplates',
            'docSeo'
        ));
    }

    /**
     * Display the Invoice & Document Templates Gallery.
     */
    public function templates(): View
    {
        $templates = TemplateCatalog::templates();
        $categories = TemplateCatalog::categories();
        $styles = TemplateCatalog::styles();
        $colors = TemplateCatalog::colors();

        return view('pages.templates', compact('templates', 'categories', 'styles', 'colors'));
    }

    /**
     * Store or update an invoice.
     */
    public function store(Request $request, InvoiceService $invoiceService): JsonResponse
    {
        $input = $invoiceService->normalizeInput($request);
        $validated = Validator::make($input, $invoiceService->validationRules())->validate();

        $saved = $invoiceService->save($validated, Auth::id(), $request);
        $invoice = $saved['invoice'];

        // Track guest-created invoice UUIDs in session so they can be claimed if user signs up or logs in
        if (!Auth::check() && $request->hasSession()) {
            $guestUuids = $request->session()->get('guest_invoice_uuids', []);
            if (!in_array($invoice->uuid, $guestUuids, true)) {
                $guestUuids[] = $invoice->uuid;
                $request->session()->put('guest_invoice_uuids', array_slice($guestUuids, -20));
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Invoice saved successfully!',
            'uuid' => $invoice->uuid,
            'invoice_number' => $invoice->invoice_number,
            'view_url' => route('invoice.show', $invoice->uuid),
            'pdf_url' => route('invoice.pdf', $invoice->uuid),
            'email_url' => route('invoice.email', $invoice->uuid),
        ]);
    }

    /**
     * Display public client view of the invoice.
     */
    public function show(string $uuid): View
    {
        $invoice = Invoice::with('items')->where('uuid', $uuid)->firstOrFail();
        return view('invoice.show', [
            'invoice' => $invoice,
            'seo' => [
                'title' => 'Invoice #' . $invoice->invoice_number . ' | Formnexa',
                'robots' => 'noindex,nofollow',
            ],
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

        if ($request->has('stream')) {
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
            ]);
        } catch (\Exception $e) {
            Log::error('Invoice email failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to deliver email: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get recent invoices history for drawer/modal (user-scoped or guest session).
     */
    public function history(Request $request): JsonResponse
    {
        if (Auth::check()) {
            $invoices = Invoice::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(20)
                ->get(['uuid', 'invoice_number', 'invoice_title', 'to_name', 'total', 'balance_due', 'currency_symbol', 'created_at', 'status']);
        } else {
            $guestUuids = $request->session()->get('guest_invoice_uuids', []);
            $invoices = !empty($guestUuids)
                ? Invoice::whereIn('uuid', $guestUuids)
                    ->whereNull('user_id')
                    ->orderBy('created_at', 'desc')
                    ->take(20)
                    ->get(['uuid', 'invoice_number', 'invoice_title', 'to_name', 'total', 'balance_due', 'currency_symbol', 'created_at', 'status'])
                : collect();
        }

        return response()->json($invoices);
    }

    /**
     * Dedicated authenticated History Dashboard page.
     */
    public function historyPage(Request $request, InvoiceService $invoiceService): View
    {
        $userId = Auth::id();
        $search = trim((string) $request->query('q', ''));
        $statusFilter = trim((string) $request->query('status', 'all'));

        $query = Invoice::where('user_id', $userId);

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

        $invoices = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $resumes = Resume::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        $stats = array_merge($invoiceService->getUserStats($userId), [
            'resume_count' => $resumes->count(),
        ]);

        return view('pages.history', compact('invoices', 'resumes', 'stats', 'search', 'statusFilter'));
    }

    /**
     * Update invoice status from History page.
     */
    public function updateStatus(string $uuid, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,sent,paid,overdue'],
        ]);

        $invoice = Invoice::where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $invoice->update(['status' => $validated['status']]);

        return back()->with('success', "Document #{$invoice->invoice_number} marked as " . ucfirst($validated['status']) . '.');
    }

    /**
     * Delete an invoice from the authenticated user's history.
     */
    public function destroy(string $uuid): RedirectResponse
    {
        $invoice = Invoice::where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $number = $invoice->invoice_number;
        $invoice->items()->delete();
        $invoice->delete();

        return back()->with('success', "Document #{$number} deleted from your history.");
    }
}
