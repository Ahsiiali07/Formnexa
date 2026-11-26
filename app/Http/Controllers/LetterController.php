<?php

namespace App\Http\Controllers;

use App\Support\ResignationCatalog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LetterController extends Controller
{
    /**
     * Interactive Resignation Letter & Two Weeks Notice Generator Studio.
     */
    public function resignation(): View
    {
        return view('letters.resignation');
    }

    /**
     * Dedicated landing page for a specific resignation reason/situation.
     */
    public function landing(string $slug): View
    {
        $page = ResignationCatalog::find($slug);
        abort_if($page === null, 404);

        return view('letters.resignation-landing', [
            'page' => $page,
            'seo' => [
                'title' => $page['title'],
                'description' => $page['description'],
                'image' => 'images/og/resignation-letter-generator.jpg',
            ],
        ]);
    }

    /**
     * Download formatted Executive Letterhead PDF.
     */
    public function downloadPdf(Request $request)
    {
        $validated = $this->validateLetterPayload($request);

        $pdf = Pdf::loadView('letters.resignation-pdf', [
            'letter' => $validated,
        ])->setPaper('a4', 'portrait')->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => 'sans-serif',
            'dpi' => 150,
        ]);

        $fileName = 'resignation-letter-' . Str::slug($validated['employee_name'] ?: 'notice') . '.pdf';

        if ($request->boolean('stream')) {
            return $pdf->stream($fileName);
        }

        return $pdf->download($fileName);
    }

    /**
     * Download formatted Microsoft Word (.doc / .docx compatible) document.
     */
    public function downloadDocx(Request $request)
    {
        $validated = $this->validateLetterPayload($request);

        $content = view('letters.resignation-doc', [
            'letter' => $validated,
        ])->render();

        $fileName = 'resignation-letter-' . Str::slug($validated['employee_name'] ?: 'notice') . '.doc';

        return response($content, 200, [
            'Content-Type' => 'application/msword; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    /**
     * Normalize & sanitize letter inputs.
     */
    protected function validateLetterPayload(Request $request): array
    {
        $data = $request->validate([
            'employee_name' => 'nullable|string|max:120',
            'employee_title' => 'nullable|string|max:120',
            'employee_email' => 'nullable|string|max:120',
            'employee_phone' => 'nullable|string|max:60',
            'employee_address' => 'nullable|string|max:200',
            'manager_name' => 'nullable|string|max:120',
            'manager_title' => 'nullable|string|max:120',
            'company_name' => 'nullable|string|max:150',
            'company_address' => 'nullable|string|max:200',
            'date' => 'nullable|string|max:40',
            'last_day' => 'nullable|string|max:40',
            'reason' => 'nullable|string|max:60',
            'tone' => 'nullable|string|max:40',
            'letter_body' => 'nullable|string|max:10000',
            'personal_note' => 'nullable|string|max:1000',
            'include_contact' => 'nullable|boolean',
            'include_transition' => 'nullable|boolean',
        ]);

        $data['employee_name'] = $data['employee_name'] ?: 'Your Name';
        $data['employee_title'] = $data['employee_title'] ?: 'Software Engineer';
        $data['company_name'] = $data['company_name'] ?: 'Company Name';
        $data['manager_name'] = $data['manager_name'] ?: 'Hiring Manager';
        $data['date'] = $data['date'] ?: now()->format('F j, Y');
        $data['last_day'] = $data['last_day'] ?: now()->addWeeks(2)->format('F j, Y');

        return $data;
    }
}
