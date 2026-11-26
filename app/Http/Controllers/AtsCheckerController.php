<?php

namespace App\Http\Controllers;

use App\Services\AtsCheckerService;
use App\Support\ResumeCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AtsCheckerController extends Controller
{
    /**
     * Display the interactive ATS Resume Checker & Scorer tool.
     */
    public function index(): View
    {
        $templates = ResumeCatalog::templates();
        $styles = ResumeCatalog::styles();

        return view('resume.checker', compact('templates', 'styles'));
    }

    /**
     * Audit uploaded file or raw resume text and return detailed ATS score & analysis.
     */
    public function check(Request $request, AtsCheckerService $checker): JsonResponse
    {
        $request->validate([
            'resume_file' => 'nullable|file|mimes:pdf,docx,txt,md,json|max:10240',
            'resume_text' => 'nullable|string|max:65535',
            'job_description' => 'nullable|string|max:65535',
        ]);

        $file = $request->file('resume_file');
        $rawText = $request->input('resume_text');
        $jobDescription = $request->input('job_description');

        if (!$file && empty(trim((string) $rawText))) {
            return response()->json([
                'success' => false,
                'message' => 'Please upload your resume file (PDF, DOCX, TXT) or paste your resume text.',
            ], 422);
        }

        try {
            $auditResult = $checker->audit($file, $rawText, $jobDescription);

            // Store in session for optional 1-click handoff to Resume Builder
            if (!empty($auditResult['parsed_resume'])) {
                session(['pending_builder_import' => $auditResult['parsed_resume']]);
            }

            return response()->json($auditResult);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'An error occurred while analyzing the resume.',
            ], 400);
        }
    }
}
