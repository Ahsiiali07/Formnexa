<?php

namespace App\Services;

use App\Models\Resume;
use App\Support\MediaHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ResumeService
{
    /**
     * Normalize multipart/JSON request payloads (decodes JSON-encoded `content` in multipart forms).
     */
    public function normalizeInput(Request $request): array
    {
        $data = $request->all();

        if (isset($data['content']) && is_string($data['content'])) {
            $decoded = json_decode($data['content'], true);
            if (is_array($decoded)) {
                $data['content'] = $decoded;
            }
        }

        return $data;
    }

    /**
     * Validation rules for creating or updating a resume.
     *
     * @return array<string, mixed>
     */
    public function validationRules(): array
    {
        return [
            'uuid' => 'nullable|string|max:64',
            'title' => 'nullable|string|max:150',
            'template_style' => 'nullable|string|max:40',
            'accent_color' => 'nullable|string|max:30',
            'font_family' => 'nullable|string|in:sans,serif,mono',
            'density' => 'nullable|string|in:comfortable,compact',
            'photo_data' => 'nullable|string',
            'photo_file' => 'nullable|image|max:5120',
            'content' => 'required|array',
        ];
    }

    /**
     * Create or update a resume record.
     *
     * @return array{resume: Resume, created: bool}
     */
    public function save(
        array $validated,
        ?int $userId,
        ?Request $request = null,
        ?string $forcedUuid = null,
        bool $forbidForeignOwner = false
    ): array {
        if ($request !== null && $request->hasFile('photo_file')) {
            $validated['photo_data'] = MediaHelper::uploadedImageToDataUri($request->file('photo_file'));
        }

        $targetUuid = $forcedUuid ?: ($validated['uuid'] ?? null);
        $resume = null;

        if (!empty($targetUuid)) {
            $existing = Resume::where('uuid', $targetUuid)->first();
            if ($existing) {
                if ($existing->user_id === null || $existing->user_id === $userId) {
                    $resume = $existing;
                } elseif ($forbidForeignOwner) {
                    throw new AccessDeniedHttpException('You do not have permission to modify this resume.');
                }
            } elseif ($forbidForeignOwner && $forcedUuid !== null) {
                abort(404, 'Resume not found.');
            }
        }

        $content = $validated['content'];
        if (!empty($validated['density'])) {
            $content['density'] = $validated['density'];
        }

        $fullName = trim((string) ($content['personal']['full_name'] ?? ''));
        $defaultTitle = $fullName !== '' ? "{$fullName} Resume" : 'My Resume';

        $data = [
            'title' => !empty($validated['title']) ? $validated['title'] : $defaultTitle,
            'template_style' => !empty($validated['template_style']) ? $validated['template_style'] : 'flow_sidebar',
            'accent_color' => !empty($validated['accent_color']) ? $validated['accent_color'] : '#162a42',
            'font_family' => !empty($validated['font_family']) ? $validated['font_family'] : 'sans',
            'photo_data' => $validated['photo_data'] ?? ($resume?->photo_data),
            'content' => $content,
        ];

        $created = false;
        if ($resume) {
            if ($userId !== null && $resume->user_id === null) {
                $data['user_id'] = $userId;
            }
            $resume->update($data);
        } else {
            $data['user_id'] = $userId;
            $resume = Resume::create($data);
            $created = true;
        }

        return [
            'resume' => $resume,
            'created' => $created,
        ];
    }

    /**
     * Extract and parse a CV from an uploaded file (`cv_file`) or raw text (`cv_text`).
     *
     * @return array<string, mixed>
     */
    public function importFromRequest(Request $request, ResumeParser $parser): array
    {
        $request->validate([
            'cv_file' => 'nullable|file|mimes:pdf,docx,txt,md,json|max:5120',
            'cv_text' => 'nullable|string|max:60000',
        ]);

        if (!$request->hasFile('cv_file') && trim((string) $request->input('cv_text', '')) === '') {
            return [
                'status' => 422,
                'payload' => [
                    'success' => false,
                    'message' => 'Please upload a CV file (PDF, DOCX, TXT) or paste your CV text.',
                ],
            ];
        }

        try {
            $rawText = $request->hasFile('cv_file')
                ? $parser->extractTextFromFile($request->file('cv_file'))
                : (string) $request->input('cv_text');
            $rawText = mb_convert_encoding($rawText, 'UTF-8', 'UTF-8');

            $parsed = $parser->parse($rawText);

            $expCount = count(array_filter(
                $parsed['experience'] ?? [],
                static fn ($e) => trim(($e['role'] ?? '') . ($e['company'] ?? '') . ($e['bullets_text'] ?? '')) !== ''
            ));
            $eduCount = count(array_filter(
                $parsed['education'] ?? [],
                static fn ($e) => trim(($e['degree'] ?? '') . ($e['school'] ?? '')) !== ''
            ));
            $skillCount = count($parsed['skills'] ?? []);

            return [
                'status' => 200,
                'payload' => [
                    'success' => true,
                    'message' => 'CV parsed successfully! Review and customize any field below.',
                    'raw_text' => mb_substr($rawText, 0, 12000),
                    'parsed' => $parsed,
                    'stats' => [
                        'name' => $parsed['personal']['full_name'] ?: 'Not detected',
                        'experience' => $expCount,
                        'education' => $eduCount,
                        'skills' => $skillCount,
                    ],
                ],
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 422,
                'payload' => [
                    'success' => false,
                    'message' => $e->getMessage() ?: 'Failed to extract text from CV.',
                ],
            ];
        }
    }

    /**
     * Build DomPDF instance for the given resume.
     */
    public function buildPdf(Resume $resume): DomPdfWrapper
    {
        return Pdf::loadView('resume.pdf', compact('resume'))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => ($resume->font_family ?? 'sans') === 'serif' ? 'DejaVu Serif' : 'DejaVu Sans',
            ]);
    }

    /**
     * Claim unassigned guest-created resumes for an authenticated user.
     *
     * @param array<int, string> $uuids
     */
    public function claimGuestResumes(int $userId, array $uuids): int
    {
        $clean = array_values(array_filter(array_map('strval', $uuids)));
        if (empty($clean)) {
            return 0;
        }

        return Resume::whereIn('uuid', $clean)
            ->whereNull('user_id')
            ->update(['user_id' => $userId]);
    }

    /**
     * Format a Resume model cleanly for mobile JSON APIs.
     *
     * @return array<string, mixed>
     */
    public function format(Resume $resume): array
    {
        $content = is_array($resume->content) ? $resume->content : [];
        $presetAvatar = (string) ($content['personal']['preset_avatar'] ?? '');
        $avatarUrl = $resume->photo_data ?: ($presetAvatar !== '' ? asset(ltrim($presetAvatar, '/')) : null);

        return [
            'uuid' => $resume->uuid,
            'user_id' => $resume->user_id,
            'title' => $resume->title,
            'template_style' => $resume->template_style,
            'accent_color' => $resume->accent_color,
            'font_family' => $resume->font_family,
            'density' => $content['density'] ?? 'comfortable',
            'photo_data' => $resume->photo_data,
            'avatar_url' => $avatarUrl,
            'content' => $content,
            'view_url' => route('resume.show', $resume->uuid),
            'pdf_url' => route('resume.pdf', $resume->uuid),
            'api_pdf_url' => route('api.v1.resumes.pdf', $resume->uuid),
            'created_at' => $resume->created_at?->toIso8601String(),
            'updated_at' => $resume->updated_at?->toIso8601String(),
        ];
    }
}
