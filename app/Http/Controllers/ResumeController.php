<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Services\ResumeParser;
use App\Services\ResumeService;
use App\Support\ResumeCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResumeController extends Controller
{
    /**
     * FlowCV-inspired Resume Templates Gallery & Landing Page.
     */
    public function templates(): View
    {
        $templates = ResumeCatalog::templates();
        $styles = ResumeCatalog::styles();
        $colors = ResumeCatalog::colors();
        $categories = ResumeCatalog::categories();

        return view('resume.templates', compact('templates', 'styles', 'colors', 'categories'));
    }

    /**
     * Live Interactive Resume Builder Studio (No Login Required).
     */
    public function builder(Request $request): View
    {
        $loadedResume = null;
        if ($request->has('load')) {
            $candidate = Resume::where('uuid', $request->query('load'))->first();
            if ($candidate && ($candidate->user_id === null || $candidate->user_id === Auth::id())) {
                $loadedResume = $candidate;
            }
        }

        $loadedTemplate = null;
        if ($request->has('template')) {
            $loadedTemplate = ResumeCatalog::find((string) $request->query('template'));
        }

        $styles = ResumeCatalog::styles();
        $colors = ResumeCatalog::colors();
        $allTemplates = ResumeCatalog::templates();
        $defaultContent = ResumeCatalog::defaultContent();
        $blankContent = ResumeCatalog::blankContent();
        $openImport = $request->boolean('import');

        if (Auth::check()) {
            $recentResumes = Resume::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get(['id', 'uuid', 'title', 'template_style', 'accent_color', 'content', 'created_at']);
        } else {
            $guestUuids = $request->session()->get('guest_resume_uuids', []);
            $recentResumes = !empty($guestUuids)
                ? Resume::whereIn('uuid', $guestUuids)
                    ->whereNull('user_id')
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get(['id', 'uuid', 'title', 'template_style', 'accent_color', 'content', 'created_at'])
                : collect();
        }

        return view('resume.builder', compact(
            'loadedResume',
            'loadedTemplate',
            'recentResumes',
            'styles',
            'colors',
            'allTemplates',
            'defaultContent',
            'blankContent',
            'openImport'
        ));
    }

    /**
     * Import and parse an existing CV file (PDF, DOCX, TXT, JSON) or raw pasted CV text.
     */
    public function import(Request $request, ResumeParser $parser, ResumeService $resumeService): JsonResponse
    {
        $result = $resumeService->importFromRequest($request, $parser);
        return response()->json($result['payload'], $result['status']);
    }

    /**
     * Save or update a resume record in the database.
     */
    public function store(Request $request, ResumeService $resumeService): JsonResponse
    {
        $input = $resumeService->normalizeInput($request);
        $validated = Validator::make($input, $resumeService->validationRules())->validate();

        $saved = $resumeService->save($validated, Auth::id(), $request);
        $resume = $saved['resume'];

        // Track guest-created resume UUIDs for session & optional signup claiming
        if (!Auth::check() && $request->hasSession()) {
            $guestUuids = $request->session()->get('guest_resume_uuids', []);
            if (!in_array($resume->uuid, $guestUuids, true)) {
                $guestUuids[] = $resume->uuid;
                $request->session()->put('guest_resume_uuids', array_slice($guestUuids, -20));
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Resume saved successfully!',
            'uuid' => $resume->uuid,
            'view_url' => route('resume.show', $resume->uuid),
            'pdf_url' => route('resume.pdf', $resume->uuid),
        ]);
    }

    /**
     * Public Shareable Resume Web View.
     */
    public function show(string $uuid): View
    {
        $resume = Resume::where('uuid', $uuid)->firstOrFail();
        return view('resume.show', [
            'resume' => $resume,
            'seo' => [
                'title' => ($resume->title ?: 'Resume') . ' | Formnexa',
                'robots' => 'noindex,nofollow',
            ],
        ]);
    }

    /**
     * Export Crisp A4 Vector PDF Resume.
     */
    public function downloadPdf(string $uuid, Request $request, ResumeService $resumeService)
    {
        $resume = Resume::where('uuid', $uuid)->firstOrFail();
        $pdf = $resumeService->buildPdf($resume);

        $slug = Str::slug($resume->content['personal']['full_name'] ?? $resume->title ?? 'resume') ?: 'resume';
        $fileName = $slug . '-resume.pdf';

        if ($request->has('stream')) {
            return $pdf->stream($fileName);
        }

        return $pdf->download($fileName);
    }

    /**
     * Delete a saved resume from the authenticated user's history.
     */
    public function destroy(string $uuid): RedirectResponse
    {
        $resume = Resume::where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $title = $resume->title;
        $resume->delete();

        return back()->with('success', "Resume \"{$title}\" deleted from your history.");
    }
}
