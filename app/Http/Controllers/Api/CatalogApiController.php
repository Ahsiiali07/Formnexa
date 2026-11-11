<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ConverterCatalog;
use App\Support\ResumeCatalog;
use App\Support\TemplateCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogApiController extends Controller
{
    /**
     * Unified startup payload for mobile home screen & studio initialization.
     */
    public function bootstrap(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'invoice' => [
                'generators' => array_values(TemplateCatalog::generators()),
                'styles' => array_values(TemplateCatalog::styles()),
                'colors' => TemplateCatalog::colors(),
                'currencies' => TemplateCatalog::currencies(),
                'payment_terms' => TemplateCatalog::paymentTerms(),
                'cover_presets' => TemplateCatalog::coverPresets(),
                'featured_templates' => array_slice(TemplateCatalog::templates(), 0, 6),
            ],
            'resume' => [
                'styles' => array_values(ResumeCatalog::styles()),
                'colors' => ResumeCatalog::colors(),
                'fonts' => ['sans', 'serif', 'mono'],
                'densities' => ['comfortable', 'compact'],
                'featured_templates' => array_map(
                    fn (array $tpl) => $this->enrichResumeTemplate($tpl),
                    array_slice(ResumeCatalog::templates(), 0, 6)
                ),
            ],
            'converter' => [
                'groups' => ConverterCatalog::grouped(),
                'tools' => ConverterCatalog::allWithSchemas(),
            ],
        ]);
    }

    /**
     * Full configuration metadata for the Invoice Studio & 12 Document Generators.
     */
    public function invoiceConfig(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'generators' => array_values(TemplateCatalog::generators()),
            'styles' => array_values(TemplateCatalog::styles()),
            'colors' => TemplateCatalog::colors(),
            'categories' => $this->keyedToList(TemplateCatalog::categories()),
            'currencies' => TemplateCatalog::currencies(),
            'payment_terms' => TemplateCatalog::paymentTerms(),
            'cover_presets' => TemplateCatalog::coverPresets(),
        ]);
    }

    /**
     * Filterable Invoice Templates Gallery API.
     */
    public function invoiceTemplates(Request $request): JsonResponse
    {
        $category = trim((string) $request->query('category', 'all'));
        $style = trim((string) $request->query('style', 'all'));
        $popularOnly = $request->boolean('popular', false);
        $search = mb_strtolower(trim((string) $request->query('q', '')));

        $templates = array_values(array_filter(TemplateCatalog::templates(), static function (array $tpl) use ($category, $style, $popularOnly, $search): bool {
            if ($category !== '' && $category !== 'all' && ($tpl['category'] ?? '') !== $category) {
                return false;
            }
            if ($style !== '' && $style !== 'all' && ($tpl['template_style'] ?? '') !== $style) {
                return false;
            }
            if ($popularOnly && empty($tpl['popular'])) {
                return false;
            }
            if ($search !== '') {
                $haystack = mb_strtolower(($tpl['name'] ?? '') . ' ' . ($tpl['description'] ?? '') . ' ' . ($tpl['category_label'] ?? ''));
                if (!str_contains($haystack, $search)) {
                    return false;
                }
            }
            return true;
        }));

        return response()->json([
            'success' => true,
            'templates' => $templates,
            'categories' => $this->keyedToList(TemplateCatalog::categories()),
            'styles' => array_values(TemplateCatalog::styles()),
            'colors' => TemplateCatalog::colors(),
        ]);
    }

    /**
     * Full configuration metadata & starter payloads for the Resume Builder.
     */
    public function resumeConfig(): JsonResponse
    {
        $defaultContent = ResumeCatalog::defaultContent();
        if (!empty($defaultContent['personal']['preset_avatar'])) {
            $defaultContent['personal']['preset_avatar_url'] = asset(ltrim($defaultContent['personal']['preset_avatar'], '/'));
        }

        return response()->json([
            'success' => true,
            'styles' => array_values(ResumeCatalog::styles()),
            'colors' => ResumeCatalog::colors(),
            'categories' => $this->keyedToList(ResumeCatalog::categories()),
            'fonts' => ['sans', 'serif', 'mono'],
            'densities' => ['comfortable', 'compact'],
            'default_content' => $defaultContent,
            'blank_content' => ResumeCatalog::blankContent(),
        ]);
    }

    /**
     * Filterable Resume Templates Gallery API.
     */
    public function resumeTemplates(Request $request): JsonResponse
    {
        $category = trim((string) $request->query('category', 'all'));
        $style = trim((string) $request->query('style', 'all'));
        $popularOnly = $request->boolean('popular', false);
        $search = mb_strtolower(trim((string) $request->query('q', '')));

        $filtered = array_values(array_filter(ResumeCatalog::templates(), static function (array $tpl) use ($category, $style, $popularOnly, $search): bool {
            if ($category !== '' && $category !== 'all' && ($tpl['category'] ?? '') !== $category) {
                return false;
            }
            if ($style !== '' && $style !== 'all' && ($tpl['style'] ?? '') !== $style) {
                return false;
            }
            if ($popularOnly && empty($tpl['popular'])) {
                return false;
            }
            if ($search !== '') {
                $haystack = mb_strtolower(($tpl['name'] ?? '') . ' ' . ($tpl['role'] ?? '') . ' ' . ($tpl['tagline'] ?? '') . ' ' . ($tpl['description'] ?? ''));
                if (!str_contains($haystack, $search)) {
                    return false;
                }
            }
            return true;
        }));

        $templates = array_map(fn (array $tpl) => $this->enrichResumeTemplate($tpl), $filtered);

        return response()->json([
            'success' => true,
            'templates' => $templates,
            'categories' => $this->keyedToList(ResumeCatalog::categories()),
            'styles' => array_values(ResumeCatalog::styles()),
            'colors' => ResumeCatalog::colors(),
        ]);
    }

    /**
     * List all 10 PDF Converter tools with upload & option schemas.
     */
    public function converters(): JsonResponse
    {
        $tools = ConverterCatalog::allWithSchemas();
        $groups = [
            'to_pdf' => array_values(array_filter($tools, static fn (array $t) => $t['group'] === 'to_pdf')),
            'from_pdf' => array_values(array_filter($tools, static fn (array $t) => $t['group'] === 'from_pdf')),
        ];

        return response()->json([
            'success' => true,
            'groups' => $groups,
            'tools' => $tools,
        ]);
    }

    /**
     * Single converter tool metadata & schema.
     */
    public function converterShow(string $tool): JsonResponse
    {
        $meta = ConverterCatalog::findWithSchema($tool);
        if ($meta === null) {
            return response()->json([
                'success' => false,
                'message' => 'Converter tool not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'tool' => $meta,
        ]);
    }

    /**
     * @param array<string, string> $map
     * @return array<int, array{key: string, label: string}>
     */
    protected function keyedToList(array $map): array
    {
        $out = [];
        foreach ($map as $key => $label) {
            $out[] = ['key' => $key, 'label' => $label];
        }
        return $out;
    }

    /**
     * @param array<string, mixed> $tpl
     * @return array<string, mixed>
     */
    protected function enrichResumeTemplate(array $tpl): array
    {
        $avatar = (string) ($tpl['preset_content']['personal']['preset_avatar'] ?? '');
        $tpl['avatar_url'] = $avatar !== '' ? asset(ltrim($avatar, '/')) : null;
        if (isset($tpl['content']['personal'])) {
            $tpl['content']['personal']['preset_avatar_url'] = $tpl['avatar_url'];
        }
        return $tpl;
    }
}
