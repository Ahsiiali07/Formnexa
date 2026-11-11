<?php

namespace App\Services;

use App\Support\ConverterCatalog;
use Illuminate\Http\Request;

class ConverterDispatcher
{
    public function __construct(protected DocumentConverter $converter) {}

    /**
     * Validate request inputs and execute the requested conversion tool.
     *
     * @return array<string, mixed>
     */
    public function dispatch(Request $request, string $tool): array
    {
        $meta = ConverterCatalog::find($tool);
        if ($meta === null) {
            abort(404, 'Unsupported conversion tool.');
        }

        $pageFrames = [];
        if ($request->hasFile('page_frames')) {
            $pageFrames = $request->file('page_frames');
        } elseif (is_array($request->input('page_images'))) {
            $pageFrames = $request->input('page_images');
        }

        return match ($tool) {
            'jpg-to-pdf' => $this->handleJpgToPdf($request),
            'word-to-pdf' => $this->handleWordToPdf($request),
            'powerpoint-to-pdf' => $this->handlePowerpointToPdf($request),
            'excel-to-pdf' => $this->handleExcelToPdf($request),
            'html-to-pdf' => $this->handleHtmlToPdf($request),
            'pdf-to-jpg' => $this->handlePdfToJpg($request, $pageFrames),
            'pdf-to-word' => $this->handlePdfToWord($request, $pageFrames),
            'pdf-to-powerpoint' => $this->handlePdfToPowerpoint($request, $pageFrames),
            'pdf-to-excel' => $this->handlePdfToExcel($request),
            'pdf-to-pdfa' => $this->handlePdfToPdfA($request, $pageFrames),
        };
    }

    protected function handleJpgToPdf(Request $request): array
    {
        if ($request->hasFile('file') && !$request->hasFile('files')) {
            $request->validate([
                'file' => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:15360',
            ]);
            $files = [$request->file('file')];
        } else {
            $request->validate([
                'files' => 'required|array|min:1|max:30',
                'files.*' => 'file|mimes:jpg,jpeg,png,webp,gif|max:15360',
            ]);
            $files = $request->file('files');
        }

        return $this->converter->imagesToPdf($files, [
            'page_size' => $request->input('page_size', 'a4'),
            'orientation' => $request->input('orientation', 'auto'),
            'margin' => $request->input('margin', 'small'),
        ]);
    }

    protected function handleWordToPdf(Request $request): array
    {
        $request->validate([
            'file' => 'required|file|extensions:docx,doc,rtf,odt,txt|max:20480',
        ]);

        return $this->converter->wordToPdf($request->file('file'), [
            'page_size' => $request->input('page_size', 'a4'),
        ]);
    }

    protected function handlePowerpointToPdf(Request $request): array
    {
        $request->validate([
            'file' => 'required|file|extensions:pptx|max:25600',
        ]);

        return $this->converter->powerpointToPdf($request->file('file'));
    }

    protected function handleExcelToPdf(Request $request): array
    {
        $request->validate([
            'file' => 'required|file|extensions:xlsx,xls,csv,ods|max:20480',
        ]);

        return $this->converter->excelToPdf($request->file('file'), [
            'orientation' => $request->input('orientation', 'landscape'),
            'page_size' => $request->input('page_size', 'a4'),
            'gridlines' => $request->boolean('gridlines', true),
        ]);
    }

    protected function handleHtmlToPdf(Request $request): array
    {
        $request->validate([
            'file' => 'nullable|file|extensions:html,htm,txt|max:10240',
            'html_code' => 'nullable|string|max:500000',
            'url' => 'nullable|string|max:1000',
        ]);

        return $this->converter->htmlToPdf(
            $request->file('file'),
            (string) $request->input('html_code', ''),
            (string) $request->input('url', ''),
            [
                'page_size' => $request->input('page_size', 'a4'),
                'orientation' => $request->input('orientation', 'portrait'),
            ]
        );
    }

    protected function handlePdfToJpg(Request $request, array $pageFrames): array
    {
        if (empty($pageFrames)) {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:25600',
                'scale' => 'nullable|numeric|min:0.5|max:3.5',
                'quality' => 'nullable|integer|min:30|max:100',
                'max_pages' => 'nullable|integer|min:1|max:50',
                'output' => 'nullable|in:zip,jpg,auto,json',
                'page' => 'nullable|integer|min:1',
            ]);
        }

        return $this->converter->pdfToJpg($request->file('file'), $pageFrames, [
            'base_name' => (string) $request->input('base_name', 'pdf-pages'),
            'scale' => $request->input('scale', 2.0),
            'quality' => $request->input('quality', 90),
            'max_pages' => $request->input('max_pages', 40),
            'output' => $request->input('output', 'zip'),
            'page' => $request->input('page', 0),
        ]);
    }

    protected function handlePdfToWord(Request $request, array $pageFrames): array
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:25600',
        ]);

        return $this->converter->pdfToWord($request->file('file'), $pageFrames, [
            'mode' => $request->input('mode', 'editable'),
        ]);
    }

    protected function handlePdfToPowerpoint(Request $request, array $pageFrames): array
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:25600',
        ]);

        return $this->converter->pdfToPowerpoint($request->file('file'), $pageFrames, [
            'mode' => $request->input('mode', 'visual'),
        ]);
    }

    protected function handlePdfToExcel(Request $request): array
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:25600',
        ]);

        return $this->converter->pdfToExcel($request->file('file'), [
            'merge_sheets' => $request->input('merge_sheets', 'per_page'),
        ]);
    }

    protected function handlePdfToPdfA(Request $request, array $pageFrames): array
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:25600',
        ]);

        return $this->converter->pdfToPdfA($request->file('file'), $pageFrames, [
            'conformance' => $request->input('conformance', '1b'),
        ]);
    }
}
