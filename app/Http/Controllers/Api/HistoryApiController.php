<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Resume;
use App\Services\InvoiceService;
use App\Services\ResumeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HistoryApiController extends Controller
{
    /**
     * Unified authenticated Document History Dashboard (Invoices, Resumes & Summary Stats).
     */
    public function index(
        Request $request,
        InvoiceService $invoiceService,
        ResumeService $resumeService
    ): JsonResponse {
        $userId = $request->user()->id;
        $search = trim((string) $request->query('q', ''));
        $statusFilter = trim((string) $request->query('status', 'all'));
        $perPage = min(50, max(1, (int) $request->query('per_page', 15)));

        $query = Invoice::with('items')->where('user_id', $userId);

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
        $resumes = Resume::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        $stats = array_merge($invoiceService->getUserStats($userId), [
            'resume_count' => $resumes->count(),
        ]);

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'invoices' => [
                'data' => $invoices->getCollection()->map(
                    static fn (Invoice $inv) => $invoiceService->format($inv)
                )->values()->all(),
                'meta' => [
                    'current_page' => $invoices->currentPage(),
                    'last_page' => $invoices->lastPage(),
                    'per_page' => $invoices->perPage(),
                    'total' => $invoices->total(),
                ],
            ],
            'resumes' => $resumes->map(
                static fn (Resume $res) => $resumeService->format($res)
            )->values()->all(),
        ]);
    }
}
