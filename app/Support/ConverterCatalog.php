<?php

namespace App\Support;

class ConverterCatalog
{
    /**
     * All 10 PDF conversion tools split cleanly into CONVERT TO PDF & CONVERT FROM PDF.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            // =================================================================
            // CONVERT TO PDF (5 Tools)
            // =================================================================
            [
                'slug' => 'jpg-to-pdf',
                'group' => 'to_pdf',
                'name' => 'JPG to PDF',
                'title' => 'Convert JPG & Images to PDF',
                'tagline' => 'Combine one or multiple JPG, PNG, or WebP images into a crisp printable PDF with custom margins & page orientation.',
                'accept' => '.jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp',
                'accept_label' => 'JPG, JPEG, PNG or WebP images (upload 1 or multiple)',
                'multiple' => true,
                'output_ext' => 'pdf',
                'badge' => 'JPG',
                'color_bg' => 'bg-amber-50',
                'color_text' => 'text-amber-600',
                'color_border' => 'border-amber-200',
                'accent_hex' => '#D97706',
                'button_label' => 'Convert to PDF',
            ],
            [
                'slug' => 'word-to-pdf',
                'group' => 'to_pdf',
                'name' => 'WORD to PDF',
                'title' => 'Convert Word (DOCX) to PDF',
                'tagline' => 'Turn editable Microsoft Word documents into formatted PDF files preserving headings, lists, tables & styling.',
                'accept' => '.docx,.rtf,.odt,.txt,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'accept_label' => 'Word (.docx), RTF, ODT or Text files',
                'multiple' => false,
                'output_ext' => 'pdf',
                'badge' => 'W',
                'color_bg' => 'bg-blue-50',
                'color_text' => 'text-blue-600',
                'color_border' => 'border-blue-200',
                'accent_hex' => '#2563EB',
                'button_label' => 'Convert Word to PDF',
            ],
            [
                'slug' => 'powerpoint-to-pdf',
                'group' => 'to_pdf',
                'name' => 'POWERPOINT to PDF',
                'title' => 'Convert PowerPoint (PPTX) to PDF',
                'tagline' => 'Convert PowerPoint slide decks into clean 16:9 widescreen PDF handouts ready for sharing and printing.',
                'accept' => '.pptx,application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'accept_label' => 'PowerPoint (.pptx) presentations',
                'multiple' => false,
                'output_ext' => 'pdf',
                'badge' => 'P',
                'color_bg' => 'bg-orange-50',
                'color_text' => 'text-orange-600',
                'color_border' => 'border-orange-200',
                'accent_hex' => '#EA580C',
                'button_label' => 'Convert PPTX to PDF',
            ],
            [
                'slug' => 'excel-to-pdf',
                'group' => 'to_pdf',
                'name' => 'EXCEL to PDF',
                'title' => 'Convert Excel & CSV Spreadsheets to PDF',
                'tagline' => 'Render XLSX, XLS, CSV & ODS workbooks into clean multi-page PDF tables with crisp gridlines and sheet headers.',
                'accept' => '.xlsx,.xls,.csv,.ods,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'accept_label' => 'Excel (.xlsx, .xls), CSV, or ODS spreadsheets',
                'multiple' => false,
                'output_ext' => 'pdf',
                'badge' => 'X',
                'color_bg' => 'bg-emerald-50',
                'color_text' => 'text-emerald-600',
                'color_border' => 'border-emerald-200',
                'accent_hex' => '#059669',
                'button_label' => 'Convert Excel to PDF',
            ],
            [
                'slug' => 'html-to-pdf',
                'group' => 'to_pdf',
                'name' => 'HTML to PDF',
                'title' => 'Convert HTML Code, File or Webpage URL to PDF',
                'tagline' => 'Paste HTML markup, upload an .html file, or convert a public webpage URL into a high-resolution PDF document.',
                'accept' => '.html,.htm,text/html',
                'accept_label' => 'HTML files (.html, .htm), raw HTML code, or webpage URL',
                'multiple' => false,
                'output_ext' => 'pdf',
                'badge' => '</>',
                'color_bg' => 'bg-amber-50',
                'color_text' => 'text-amber-700',
                'color_border' => 'border-amber-200',
                'accent_hex' => '#B45309',
                'button_label' => 'Convert HTML to PDF',
            ],

            // =================================================================
            // CONVERT FROM PDF (5 Tools)
            // =================================================================
            [
                'slug' => 'pdf-to-jpg',
                'group' => 'from_pdf',
                'name' => 'PDF to JPG',
                'title' => 'Convert PDF Pages to High-Res JPG Images',
                'tagline' => 'Extract every page of your PDF as crisp high-DPI JPG images or download all pages bundled inside one ZIP.',
                'accept' => '.pdf,application/pdf',
                'accept_label' => 'PDF documents (.pdf)',
                'multiple' => false,
                'output_ext' => 'jpg',
                'badge' => 'JPG',
                'color_bg' => 'bg-amber-50',
                'color_text' => 'text-amber-600',
                'color_border' => 'border-amber-200',
                'accent_hex' => '#D97706',
                'button_label' => 'Convert PDF to JPG',
            ],
            [
                'slug' => 'pdf-to-word',
                'group' => 'from_pdf',
                'name' => 'PDF to WORD',
                'title' => 'Convert PDF to Editable Word (DOCX)',
                'tagline' => 'Transform static PDFs into editable Word (.docx) files preserving structured headings, paragraphs & lists.',
                'accept' => '.pdf,application/pdf',
                'accept_label' => 'PDF documents (.pdf)',
                'multiple' => false,
                'output_ext' => 'docx',
                'badge' => 'W',
                'color_bg' => 'bg-blue-50',
                'color_text' => 'text-blue-600',
                'color_border' => 'border-blue-200',
                'accent_hex' => '#2563EB',
                'button_label' => 'Convert PDF to Word',
            ],
            [
                'slug' => 'pdf-to-powerpoint',
                'group' => 'from_pdf',
                'name' => 'PDF to POWERPOINT',
                'title' => 'Convert PDF to PowerPoint (PPTX)',
                'tagline' => 'Turn PDF reports & handouts into 16:9 PowerPoint slides — choose editable text slides or exact visual slide captures.',
                'accept' => '.pdf,application/pdf',
                'accept_label' => 'PDF documents (.pdf)',
                'multiple' => false,
                'output_ext' => 'pptx',
                'badge' => 'P',
                'color_bg' => 'bg-orange-50',
                'color_text' => 'text-orange-600',
                'color_border' => 'border-orange-200',
                'accent_hex' => '#EA580C',
                'button_label' => 'Convert PDF to PowerPoint',
            ],
            [
                'slug' => 'pdf-to-excel',
                'group' => 'from_pdf',
                'name' => 'PDF to EXCEL',
                'title' => 'Convert PDF Tables to Excel (XLSX)',
                'tagline' => 'Extract aligned tables & numerical columns directly from PDFs into structured multi-sheet Excel workbooks.',
                'accept' => '.pdf,application/pdf',
                'accept_label' => 'PDF documents (.pdf)',
                'multiple' => false,
                'output_ext' => 'xlsx',
                'badge' => 'X',
                'color_bg' => 'bg-emerald-50',
                'color_text' => 'text-emerald-600',
                'color_border' => 'border-emerald-200',
                'accent_hex' => '#059669',
                'button_label' => 'Convert PDF to Excel',
            ],
            [
                'slug' => 'pdf-to-pdfa',
                'group' => 'from_pdf',
                'name' => 'PDF to PDF/A',
                'title' => 'Convert PDF to Archival PDF/A (ISO 19005)',
                'tagline' => 'Generate long-term archival PDF/A-1b or PDF/A-3b files with embedded sRGB color profile & XMP compliance metadata.',
                'accept' => '.pdf,application/pdf',
                'accept_label' => 'PDF documents (.pdf)',
                'multiple' => false,
                'output_ext' => 'pdf',
                'badge' => '/A',
                'color_bg' => 'bg-indigo-50',
                'color_text' => 'text-indigo-600',
                'color_border' => 'border-indigo-200',
                'accent_hex' => '#4F46E5',
                'button_label' => 'Convert to PDF/A',
            ],
        ];
    }

    /**
     * @return array{to_pdf: array<int, array<string, mixed>>, from_pdf: array<int, array<string, mixed>>}
     */
    public static function grouped(): array
    {
        $grouped = ['to_pdf' => [], 'from_pdf' => []];
        foreach (self::all() as $tool) {
            $grouped[$tool['group']][] = $tool;
        }
        return $grouped;
    }

    public static function find(string $slug): ?array
    {
        foreach (self::all() as $tool) {
            if ($tool['slug'] === $slug) {
                return $tool;
            }
        }
        return null;
    }

    public static function slugsRegex(): string
    {
        return implode('|', array_column(self::all(), 'slug'));
    }

    /**
     * Input field & option schema per conversion tool for mobile clients.
     *
     * @return array<string, mixed>
     */
    public static function inputSchema(string $slug): array
    {
        return match ($slug) {
            'jpg-to-pdf' => [
                'upload_field' => 'files[]',
                'accepts_multiple' => true,
                'allowed_extensions' => ['jpg', 'jpeg', 'png', 'webp', 'gif'],
                'max_file_kb' => 15360,
                'options' => [
                    'page_size' => ['type' => 'enum', 'values' => ['a4', 'letter', 'fit'], 'default' => 'a4'],
                    'orientation' => ['type' => 'enum', 'values' => ['auto', 'portrait', 'landscape'], 'default' => 'auto'],
                    'margin' => ['type' => 'enum', 'values' => ['none', 'small', 'normal'], 'default' => 'small'],
                ],
            ],
            'word-to-pdf' => [
                'upload_field' => 'file',
                'accepts_multiple' => false,
                'allowed_extensions' => ['docx', 'doc', 'rtf', 'odt', 'txt'],
                'max_file_kb' => 20480,
                'options' => [
                    'page_size' => ['type' => 'enum', 'values' => ['a4', 'letter'], 'default' => 'a4'],
                ],
            ],
            'powerpoint-to-pdf' => [
                'upload_field' => 'file',
                'accepts_multiple' => false,
                'allowed_extensions' => ['pptx'],
                'max_file_kb' => 25600,
                'options' => [],
            ],
            'excel-to-pdf' => [
                'upload_field' => 'file',
                'accepts_multiple' => false,
                'allowed_extensions' => ['xlsx', 'xls', 'csv', 'ods'],
                'max_file_kb' => 20480,
                'options' => [
                    'orientation' => ['type' => 'enum', 'values' => ['landscape', 'portrait'], 'default' => 'landscape'],
                    'page_size' => ['type' => 'enum', 'values' => ['a4', 'letter'], 'default' => 'a4'],
                    'gridlines' => ['type' => 'boolean', 'default' => true],
                ],
            ],
            'html-to-pdf' => [
                'upload_field' => 'file',
                'alternative_inputs' => ['html_code', 'url'],
                'accepts_multiple' => false,
                'allowed_extensions' => ['html', 'htm', 'txt'],
                'max_file_kb' => 10240,
                'options' => [
                    'page_size' => ['type' => 'enum', 'values' => ['a4', 'letter'], 'default' => 'a4'],
                    'orientation' => ['type' => 'enum', 'values' => ['portrait', 'landscape'], 'default' => 'portrait'],
                ],
            ],
            'pdf-to-jpg' => [
                'upload_field' => 'file',
                'accepts_multiple' => false,
                'allowed_extensions' => ['pdf'],
                'max_file_kb' => 25600,
                'options' => [
                    'scale' => ['type' => 'number', 'values' => [1.5, 2.0, 2.75], 'default' => 2.0],
                    'quality' => ['type' => 'integer', 'min' => 30, 'max' => 100, 'default' => 90],
                    'max_pages' => ['type' => 'integer', 'min' => 1, 'max' => 50, 'default' => 40],
                    'output' => ['type' => 'enum', 'values' => ['zip', 'jpg', 'json'], 'default' => 'zip'],
                    'page' => ['type' => 'integer', 'min' => 1, 'default' => 1],
                ],
            ],
            'pdf-to-word' => [
                'upload_field' => 'file',
                'accepts_multiple' => false,
                'allowed_extensions' => ['pdf'],
                'max_file_kb' => 25600,
                'options' => [
                    'mode' => ['type' => 'enum', 'values' => ['editable', 'visual'], 'default' => 'editable'],
                ],
            ],
            'pdf-to-powerpoint' => [
                'upload_field' => 'file',
                'accepts_multiple' => false,
                'allowed_extensions' => ['pdf'],
                'max_file_kb' => 25600,
                'options' => [
                    'mode' => ['type' => 'enum', 'values' => ['visual', 'editable'], 'default' => 'visual'],
                ],
            ],
            'pdf-to-excel' => [
                'upload_field' => 'file',
                'accepts_multiple' => false,
                'allowed_extensions' => ['pdf'],
                'max_file_kb' => 25600,
                'options' => [
                    'merge_sheets' => ['type' => 'enum', 'values' => ['per_page', 'single_sheet'], 'default' => 'per_page'],
                ],
            ],
            'pdf-to-pdfa' => [
                'upload_field' => 'file',
                'accepts_multiple' => false,
                'allowed_extensions' => ['pdf'],
                'max_file_kb' => 25600,
                'options' => [
                    'conformance' => ['type' => 'enum', 'values' => ['1b', '3b'], 'default' => '1b'],
                ],
            ],
            default => [],
        };
    }

    public static function findWithSchema(string $slug): ?array
    {
        $tool = self::find($slug);
        if ($tool === null) {
            return null;
        }
        $tool['schema'] = self::inputSchema($slug);
        return $tool;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function allWithSchemas(): array
    {
        return array_map(static function (array $tool): array {
            $tool['schema'] = self::inputSchema($tool['slug']);
            return $tool;
        }, self::all());
    }
}
