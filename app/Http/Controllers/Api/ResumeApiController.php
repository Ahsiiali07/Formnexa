<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Services\ResumeParser;
use App\Services\ResumeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ResumeApiController extends Controller
{
    /**
     * List saved resumes (authenticated user's resumes, or guest resumes matching ?uuids=...).
     */
    public function index(Request $request, ResumeService $resumeService): JsonResponse
    {
        $user = $request->user();
        $search = trim((string) $request->query('q', ''));
        $perPage = min(50, max(1, (int) $request->query('per_page', 15)));

        if ($user !== null) {
            $query = Resume::where('user_id', $user->id);
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
                ]);
            }
            $query = Resume::whereIn('uuid', $uuids)->whereNull('user_id');
        }

        if ($search !== '') {
            $query->where('title', 'like', "%{$search}%");
        }

        $resumes = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $resumes->getCollection()->map(
                static fn (Resume $res) => $resumeService->format($res)
            )->values()->all(),
            'meta' => [
                'current_page' => $resumes->currentPage(),
                'last_page' => $resumes->lastPage(),
                'per_page' => $resumes->perPage(),
                'total' => $resumes->total(),
            ],
        ]);
    }

    /**
     * Import and parse an existing CV file (PDF, DOCX, TXT, MD, JSON) or raw pasted text.
     */
    public function import(Request $request, ResumeParser $parser, ResumeService $resumeService): JsonResponse
    {
        $result = $resumeService->importFromRequest($request, $parser);
        return response()->json($result['payload'], $result['status']);
    }

    /**
     * Create or update a resume (supports JSON or multipart/form-data).
     */
    public function store(Request $request, ResumeService $resumeService): JsonResponse
    {
        $input = $resumeService->normalizeInput($request);
        $validated = Validator::make($input, $resumeService->validationRules())->validate();

        $saved = $resumeService->save(
            $validated,
            $request->user()?->id,
            $request,
            null,
            true
        );

        $resume = $saved['resume'];

        return response()->json([
            'success' => true,
            'message' => 'Resume saved successfully!',
            'uuid' => $resume->uuid,
            'resume' => $resumeService->format($resume),
            'view_url' => route('resume.show', $resume->uuid),
            'pdf_url' => route('resume.pdf', $resume->uuid),
            'api_pdf_url' => route('api.v1.resumes.pdf', $resume->uuid),
        ], $saved['created'] ? 201 : 200);
    }

    /**
     * Get a single resume for editing/viewing (enforces ownership when user_id is set).
     */
    public function show(Request $request, string $uuid, ResumeService $resumeService): JsonResponse
    {
        $resume = Resume::where('uuid', $uuid)->firstOrFail();

        if ($resume->user_id !== null && $resume->user_id !== $request->user()?->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to access this resume.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'resume' => $resumeService->format($resume),
        ]);
    }

    /**
     * Public shareable resume view by UUID.
     */
    public function publicShow(string $uuid, ResumeService $resumeService): JsonResponse
    {
        $resume = Resume::where('uuid', $uuid)->firstOrFail();

        return response()->json([
            'success' => true,
            'resume' => $resumeService->format($resume),
        ]);
    }

    /**
     * Update an existing resume by UUID.
     */
    public function update(Request $request, string $uuid, ResumeService $resumeService): JsonResponse
    {
        $input = $resumeService->normalizeInput($request);
        $validated = Validator::make($input, $resumeService->validationRules())->validate();

        $saved = $resumeService->save(
            $validated,
            $request->user()?->id,
            $request,
            $uuid,
            true
        );

        $resume = $saved['resume'];

        return response()->json([
            'success' => true,
            'message' => 'Resume updated successfully!',
            'uuid' => $resume->uuid,
            'resume' => $resumeService->format($resume),
            'view_url' => route('resume.show', $resume->uuid),
            'pdf_url' => route('resume.pdf', $resume->uuid),
            'api_pdf_url' => route('api.v1.resumes.pdf', $resume->uuid),
        ]);
    }

    /**
     * Download or stream the crisp A4 vector PDF resume.
     */
    public function downloadPdf(string $uuid, Request $request, ResumeService $resumeService)
    {
        $resume = Resume::where('uuid', $uuid)->firstOrFail();
        $pdf = $resumeService->buildPdf($resume);

        $slug = Str::slug($resume->content['personal']['full_name'] ?? $resume->title ?? 'resume') ?: 'resume';
        $fileName = $slug . '-resume.pdf';

        if ($request->boolean('stream') || $request->has('stream')) {
            return $pdf->stream($fileName);
        }

        return $pdf->download($fileName);
    }

    /**
     * Delete a saved resume owned by the authenticated user.
     */
    public function destroy(Request $request, string $uuid): JsonResponse
    {
        $resume = Resume::where('uuid', $uuid)->firstOrFail();

        if ($resume->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this resume.',
            ], 403);
        }

        $title = $resume->title;
        $resume->delete();

        return response()->json([
            'success' => true,
            'message' => "Resume \"{$title}\" deleted from your history.",
        ]);
    }
}
